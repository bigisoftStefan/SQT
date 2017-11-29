<?php	
	
	// First Step => Get the amount of all responses which have evaluated = true
	
	// Second Steo => Get the amount of the evaluated attributes (!= -1)
	
	// Third load the percentage values from the measurment table and return it
	
	// Config file of the data base
	require_once ('config.php');

	$db_link = mysqli_connect (MYSQL_HOST, 
                           MYSQL_BENUTZER, 
                           MYSQL_KENNWORT, 
                           MYSQL_DATENBANK);

	// Checks whether a connection is possible 
	if ( !$db_link )
	{
		// Error message when a connection is not possible
		http_response_code(404);
		die('keine Verbindung möglich: ');
	}

	// Ajax request variables
	$survey_id = $_POST['survey_id'];
	
	// Local variables
	$response_count = 0;
	
	// First field is the name of the property
	// Second field is the quality score of this attribute for the survey
	// Third field is the responses for this attribute in numbers
	// Fourth field is the responses number in %
	$survey_quality_score = array(
	 array("incomplete",0,0,0),
	 array("speeding",0,0,0),
	 array("straight",0,0,0),
	 array("priming",0,0,0),
	 array("anchoring",0,0,0),
	 array("dont_know",0,0,0),
	 array("conflict",0,0,0),
	 array("patterns",0,0,0)
	 );
	
	// Json array
	$json_array = array();
	
	// Get the overall respondes count for the current survey which where evaluated
	$statement = $db_link->prepare("SELECT Count(Responses.id) from Responses where Responses.survey_id = ? and Responses.evaluated = 1");
	$statement->bind_param("i", $survey_id);
	$statement->execute();

	if (!($statement->errno))
	{			
		$statement->bind_result($db_response_count);

		// Online one user id can be found because each user is unique
		while ($statement->fetch())
		{			
			// Gets the amount of all responses
			$response_count = $db_response_count;
		}
		
		$statement->close();
		
		// Getting the count for the responses for each attribute except the completion rate
		for($i = 0; $i < 8; $i++)
		{
			$statement = $db_link->prepare("SELECT Count(Responses.id) FROM Responses join Response_Measuring on Responses.id = Response_Measuring.response_id join Measuring_Attributes on Measuring_Attributes.id = Response_Measuring.measuring_id WHERE Responses.survey_id = ? and Measuring_Attributes.".$survey_quality_score[$i][0]." > 0 and Responses.evaluated = 1 ");
			$statement->bind_param("i", $survey_id);
			$statement->execute();
			
			if (!($statement->errno))
			{			
				$statement->bind_result($db_attribute_count);

				while ($statement->fetch())
				{			
					$survey_quality_score[$i][2] = $db_attribute_count;
					
					if($response_count > 0)
					{
						$survey_quality_score[$i][3] = intval(($db_attribute_count/$response_count)*100);
					}
					else
					{
						$survey_quality_score[$i][3] = 0;
					}
				}
				
				$statement->close();
			}
			else
			{
				$statement->close();
				http_response_code(404);
				echo "Error adding a new survey";
				die();
			}		
		}	
		
		
		// Getting the quality score for the different attributes of the survey
		$statement = $db_link->prepare("SELECT Measuring_Attributes.patterns, Measuring_Attributes.dont_know, Measuring_Attributes.priming, Measuring_Attributes.conflict, Measuring_Attributes.anchoring,Measuring_Attributes.straight, Measuring_Attributes.speeding, Measuring_Attributes.incomplete FROM Surveys join Measuring_Attributes on Surveys.measuring_id = Measuring_Attributes.id where Surveys.id = ?");
			$statement->bind_param("i", $survey_id);
			$statement->execute();
		
		if (!($statement->errno))
		{			
			$statement->bind_result($db_attribute_pattern, $db_attribute_dont, $db_attribute_priming, $db_attribute_conflict, $db_attribute_anchoring, $db_attribute_straight, $db_attribute_speeding, $db_attribute_complete);

			while ($statement->fetch())
			{	
				$survey_quality_score[0][1] = $db_attribute_complete;
				$survey_quality_score[1][1] = $db_attribute_speeding;
				$survey_quality_score[2][1] = $db_attribute_straight;
				$survey_quality_score[3][1] = $db_attribute_priming;
				$survey_quality_score[4][1] = $db_attribute_anchoring;
				$survey_quality_score[5][1] = $db_attribute_dont;
				$survey_quality_score[6][1] = $db_attribute_conflict;
				$survey_quality_score[7][1] = $db_attribute_pattern;
			}
				
			$statement->close();
			
			/* For the GUI prototype this JSON Code is hardcoded
		var table_contend = '[' +
		'{ "name":"Priming" , "value":"36" , "responses":"4" , "responsesp":"80"},' +
		'{ "name":"Anchoring" , "value":"18" , "responses":"4" , "responsesp":"80"},' +
		'{ "name":"Dont know answers" , "value":"N/A" , "responses":"0" , "responsesp":"0"},' +
		'{ "name":"Conflicting answers" , "value":"N/A" , "responses":"0" , "responsesp":"0"}, '+
		'{ "name":"Speeding" , "value":"15" , "responses":"4" , "responsesp":"80"},' +
		'{ "name":"Straight Lining" , "value":"45" , "responses":"4" , "responsesp":"80"},' +
		'{ "name":"Patterns" , "value":"N/A" , "responses":"0" , "responsesp":"0"},' +
		'{ "name":"Survey Completion" , "value":"60" , "responses":"5" , "responsesp":"100"} ]';*/
			
			// Compute JSON array
			for($i = 0; $i < 8; $i++)
			{
				$jsonArrayObject = (array('name' => $survey_quality_score[$i][0], 'value' => $survey_quality_score[$i][1], 'responses' => $survey_quality_score[$i][2] . "/" . $response_count, 'responsesp' => $survey_quality_score[$i][3]));
				$json_array[$i] = $jsonArrayObject;
			
			}
			
			// Return the JSON array
			echo json_encode($json_array);
			
		}
		else
		{
			$statement->close();
			http_response_code(404);
			echo "Error adding a new survey";
			die();
		}				
	}
	else
	{
		$statement->close();
		http_response_code(404);
		echo "Error adding a new survey";
		die();	
	}
?>