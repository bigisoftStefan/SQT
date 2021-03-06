<?php 
	
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

// Array for saving the speeding time in connection
// with the id of the response
// First data value is the response id
// Second data value is the speeding time
$data_time = array();

// Stores the quality score of the current response
// First data value is the response id
// Second data value is quality score of the current response
$data_quality_score = array();

// Stores the needed data for calculation of the quality score
// for the survey
// First field is the name of the attribute
// Second field is the current value
// Thrid field is the sum of the available records
$survey_quality_score = array(
	 array("patterns",-1,0),
	 array("dont",-1,0),
	 array("priming",-1,0),
	 array("conflict",-1,0),
	 array("anchoring",-1,0),
	 array("straight",-1,0),
	 array("speeding",-1,0),
	 array("complete",-1,0)
);

// Saving the measurement id for the survey
$measuring_id_survey = 0;

// Variable received from privous form
$survey_id = $_POST['survey_id'];

// local variable
$response_data = array();

// Get all available Responses for the current survey which should be evaluated
$statement= $db_link->prepare("Select Responses.id from Responses where Responses.evaluated = 1 and Responses.survey_id = ?");
$statement->bind_param("i", $survey_id);

$statement->execute();

if (!($statement->errno))
{	
	$statement->bind_result($response_id);

	$runner = 0;
	while ($statement->fetch())
	{	
		$response_data[$runner] = $response_id;
	}
	
			
	// GET ALL ATTRIBUTES SCORS FOR ONE RECORD + Sum them up + if an attribute is not valied reduce the divider amount (instead of 8 only 7 and so on)
	//Calculating the quality score of each response
	$index = 0;
				
	foreach($response_data as &$dat)
	{
		// To calculate the quality score, the quality overall amount starts everytime at 8
		// When a attribute es detected to be -1 than this will be not calculated 
		// and the $quality_overall_amount decreases by 1
		$quality_overall_amount = 8;
		$quality_oervall_sum = 0 ;
		
		$statement= $db_link->prepare("SELECT Measuring_Attributes.id, Measuring_Attributes.patterns, Measuring_Attributes.dont_know, Measuring_Attributes.priming, Measuring_Attributes.conflict, Measuring_Attributes.anchoring, Measuring_Attributes.straight, Measuring_Attributes.speeding, Measuring_Attributes.complete FROM Measuring_Attributes join Response_Measuring on Measuring_Attributes.id = Response_Measuring.measuring_id join Responses on Response_Measuring.response_id = Responses.id where Responses.id = ? and Responses.evaluated= 1");
		$statement->bind_param("i", $dat);
	
		$statement->execute();
	    
		if (!($statement->errno))
		{	
			$statement->bind_result($measuring_id, $db_patterns, $db_dont, $db_priming, $db_conflict, $db_anchoring, $db_straight, $db_speeding, $db_complete);
	
			while ($statement->fetch())
			{			
				
				// Checks whether the current response has a complete value
				// if there is a ciomplete value all other attributes will be calculated
				// else there is no caluclation of the other attributes
				
				// Calculate the quality score
				if($db_patterns >= 0 && $db_complete > 0)
				{
					$quality_oervall_sum = $quality_oervall_sum + $db_patterns;
					
					// Calculate the overall quality index for the category patterns
					$survey_quality_score[0][1] = $survey_quality_score[0][1] + $db_patterns;
					$survey_quality_score[0][2] = $survey_quality_score[0][2] + 1;
				}
				else
				{
					$quality_overall_amount--;
					$db_patterns = -1;
				}
				
				if($db_dont >= 0 && $db_complete > 0)
				{
					$quality_oervall_sum = $quality_oervall_sum + $db_dont;
					
					$survey_quality_score[1][1] = $survey_quality_score[1][1] + $db_dont;
					$survey_quality_score[1][2] = $survey_quality_score[1][2] + 1;
				}
				else
				{
					$quality_overall_amount--;
					$db_dont = -1;
				}
				
				if($db_priming >= 0 && $db_complete > 0)
				{
					$quality_oervall_sum = $quality_oervall_sum + $db_priming;
					
					$survey_quality_score[2][1] = $survey_quality_score[2][1] + $db_priming;
					$survey_quality_score[2][2] = $survey_quality_score[2][2] + 1;
				}
				else
				{
					$quality_overall_amount--;
					$db_priming = -1;
				}
				
				if($db_conflict >= 0 && $db_complete > 0)
				{
					$quality_oervall_sum = $quality_oervall_sum + $db_conflict;
					
					$survey_quality_score[3][1] = $survey_quality_score[3][1] + $db_conflict;
					$survey_quality_score[3][2] = $survey_quality_score[3][2] + 1;
				}
				else
				{
					$quality_overall_amount--;
					$db_conflict = -1;
				}
				
				if($db_anchoring >= 0 && $db_complete > 0)
				{
					$quality_oervall_sum = $quality_oervall_sum + $db_anchoring;
					
					$survey_quality_score[4][1] = $survey_quality_score[4][1] + $db_anchoring;
					$survey_quality_score[4][2] = $survey_quality_score[4][2] + 1;
				}
				else
				{
					$quality_overall_amount--;
					$db_anchoring = -1;
				}
				
				if($db_straight >= 0 && $db_complete > 0)
				{
					$quality_oervall_sum = $quality_oervall_sum + $db_straight;
					
					$survey_quality_score[5][1] = $survey_quality_score[5][1] + $db_straight;
					$survey_quality_score[5][2] = $survey_quality_score[5][2] + 1;
				}
				else
				{
					$quality_overall_amount--;
					$db_straight = -1;
				}
				
				if($db_speeding >= 0 && $db_complete > 0)
				{
					$quality_oervall_sum = $quality_oervall_sum + $db_speeding;
					
					$survey_quality_score[6][1] = $survey_quality_score[6][1] + $db_speeding;
					$survey_quality_score[6][2] = $survey_quality_score[6][2] + 1;
				}
				else
				{
					$quality_overall_amount--;
					$db_speeding = -1;
				}
				
				if($db_complete >= 0)
				{
					// Conversion because in the data base is 100 percent if the
					// responcent has complezted sucessfully the survey
					if($db_complete == 0)
					{
						$quality_oervall_sum = $quality_oervall_sum + 100;
						
						$survey_quality_score[7][1] = $survey_quality_score[7][1] + 100;
					}
					else
					{
						$quality_oervall_sum = $quality_oervall_sum + 0;
						
						$survey_quality_score[7][1] = $survey_quality_score[7][1] + 0;
					}
					
					$survey_quality_score[7][2] = $survey_quality_score[7][2] + 1;
				}
				else
				{
					$quality_overall_amount--;
				}
			}
			
			$data_quality_score[$index][0] = $dat[0];
			
			if($quality_overall_amount >= 0)
			{
				$data_quality_score[$index][1] = 100 - ($quality_oervall_sum / $quality_overall_amount);
			}
			else
			{
				$data_quality_score[$index][1] = 0;
			}	
		
			$index ++;
			
			$statement->close();
		}
		else
		{
			$statement->close();
			http_response_code(404);
			echo "Error computing the speeding time";
			die();
		}
	}
	
	// Calculate the quality score for the whole survey (only with evaluated responses)
	// And save it into the data base
	
	$survey_pattern = calculate_QS($survey_quality_score[0][1],$survey_quality_score[0][2]);
	$survey_dont = calculate_QS($survey_quality_score[1][1],$survey_quality_score[1][2]);
	$survey_priming = calculate_QS($survey_quality_score[2][1],$survey_quality_score[2][2]);
	$survey_conflict = calculate_QS($survey_quality_score[3][1],$survey_quality_score[3][2]);
	$survey_anchoring = calculate_QS($survey_quality_score[4][1],$survey_quality_score[4][2]);
	$survey_straight = calculate_QS($survey_quality_score[5][1],$survey_quality_score[5][2]);
	$survey_speeding = calculate_QS($survey_quality_score[6][1],$survey_quality_score[6][2]);
	$survey_complete = 100 - calculate_QS($survey_quality_score[7][1],$survey_quality_score[7][2]);
	
	// Gets the Measuring id for the current survey
	$statement = $db_link->prepare("Select Surveys.measuring_id from Surveys where Surveys.id = ?");
	$statement->bind_param("i", $survey_id);
	$statement->execute();
	
	if (!($statement->errno))
	{	
		$statement->bind_result($db_measuring_id_survey);
	
		while ($statement->fetch())
		{	
			$measuring_id_survey = $db_measuring_id_survey;
		}
	
		// Insert the survey quality score for the individual attributes in the data bases
		$statement = $db_link->prepare("UPDATE Measuring_Attributes SET patterns = ?, dont_know = ?, priming = ?, conflict = ?, anchoring = ?, straight = ?, speeding = ?, complete= ? where id = ?");
		$statement->bind_param("iiiiiiiii", $survey_pattern, $survey_dont, $survey_priming, $survey_conflict, $survey_anchoring, $survey_straight, $survey_speeding, $survey_complete,$measuring_id_survey);
		$statement->execute();
		
		if (!($statement->errno))
		{
			$statement->close();
				
			$overall_survey_sum = 0;
			$overall_survey_count = 8;
			$overall_quality_score = 0;
			
			if($survey_pattern >= 0)
			{
				$overall_survey_sum = $overall_survey_sum + $survey_pattern;
			}
			else
			{
				$overall_survey_count--;
			}
			
			if($survey_dont >= 0)
			{
				$overall_survey_sum = $overall_survey_sum + $survey_dont;
			}
			else
			{
				$overall_survey_count--;
			}
			
			if($survey_priming >= 0)
			{
				$overall_survey_sum = $overall_survey_sum + $survey_priming;
			}
			else
			{
				$overall_survey_count--;
			}
			
			if($survey_conflict >= 0)
			{
				$overall_survey_sum = $overall_survey_sum + $survey_conflict;
			}
			else
			{
				$overall_survey_count--;
			}
			
			if($survey_anchoring >= 0)
			{
				$overall_survey_sum = $overall_survey_sum + $survey_anchoring;
			}
			else
			{
				$overall_survey_count--;
			}
			
			if($survey_straight >= 0)
			{
				$overall_survey_sum = $overall_survey_sum + $survey_straight;
			}
			else
			{
				$overall_survey_count--;
			}
			
			if($survey_speeding >= 0)
			{
				$overall_survey_sum = $overall_survey_sum + $survey_speeding;
			}
			else
			{
				$overall_survey_count--;
			}
			
			if($survey_complete >= 0)
			{
				$overall_survey_sum = $overall_survey_sum + (100 - $survey_complete);
			}
			else
			{
				$overall_survey_count--;
			}
			
			
			if($overall_survey_count > 0)
			{
				$overall_quality_score = 100 - ($overall_survey_sum/$overall_survey_count);
			}
			else
			{
				$overall_quality_score = 0;
			}
			
			// Update the current survey with the calculated quality score
			$statement = $db_link->prepare("UPDATE Surveys SET quality_score = ? where id= ?");
			$statement->bind_param("ii", $overall_quality_score, $survey_id);
			$statement->execute();
	
			if (!($statement->errno))
			{
				$statement->close();
				
				echo $overall_quality_score;
			}
			else
			{
				$statement->close();
				http_response_code(404);
				echo "Error adding a response";
				die();	
			}
		}
		else
		{
			$statement->close();
			echo "Error adding a response data";
			http_response_code(404);
			die();
		}	
	}
	else
	{
		$statement->close();
		echo "Error adding a response data";
		http_response_code(404);
		die();	
	}
}
else
{
	$statement->close();
	echo "Error adding a response data";
	http_response_code(404);
	die();	
}
		


// Function which calculates the quality score for each attribute of the current survey
function calculate_QS($attribute_value, $sum)
{
	// Attribute was not selected for calculation
	if($attribute_value == -1 && $sum == 0)
	{
		return -1;
	}
	
	// Attribute was not detected 
	if($attribute_value == -1 && $sum > 0)
	{
		return 0;
	}
	
	if($sum == 0)
	{
		return 0;	
	}
	
	return 	$attribute_value / $sum;
}

?>