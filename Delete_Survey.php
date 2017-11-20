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

// Values from the ajax request
$survey_id = $_POST['survey_id'];

// local variables
$runner = 0;

// saves all responses for the current survey
$responses = array();

$measuring = array();

$attribute = array();

// Get all responses for the current survey
$statement = $db_link->prepare("SELECT id FROM Responses WHERE survey_id = ?");
$statement->bind_param("i", $survey_id);
$statement->execute();

if (!($statement->errno))
{			
	$statement->bind_result($db_response_id);

	// Saves all responses ids in the array
	
	while ($statement->fetch())
	{			
		// Getting the response ids
		$responses[$runner] = $db_response_id;
		
		$runner ++;
	}
    		
	$statement->close();
	
	$runner = 0;

	// Delete all response data (answers and questions from the data base
	foreach($responses as &$response)
	{
		$statement = $db_link->prepare("DELETE FROM Response_Data WHERE response_id= ?");
		$statement->bind_param("i", $response);
		$statement->execute();
		
		// All Response data is now deleted
		if (!($statement->errno))
		{
			$statement->close();
		}
		else
		{
			$statement->close();
			http_response_code(404);
			echo "Error removing the current survey";
			die();
		}
	}

	// Get the Response_Measuring id and Measuring_Attributes id for further operation
	foreach($responses as &$response)
	{
		$statement = $db_link->prepare("SELECT Response_Measuring.id, Measuring_Attributes.id FROM Responses join Response_Measuring on Responses.id = Response_Measuring.response_id join Measuring_Attributes on Response_Measuring.measuring_id = Measuring_Attributes.id where Responses.id = ?");
		$statement->bind_param("i", $response);
		$statement->execute();
		
		if (!($statement->errno))
		{
			$statement->bind_result($db_measuring_id, $db_attribute_id);
			
			while ($statement->fetch())
			{			
				// Getting the ids
				$measuring[$runner] = $db_measuring_id;
				$attribute[$runner] = $db_attribute_id;
			
				$runner ++;
			}
			
			$statement->close();
			
		}
		else
		{
			$statement->close();
			http_response_code(404);
			echo "Error removing the current survey";
			die();
		}
	}
	
	$runner = 0;
	
	// Deleting the Response Measurings
	foreach($measuring as &$data)
	{
		$statement = $db_link->prepare("DELETE FROM Response_Measuring WHERE id= ?");
		$statement->bind_param("i", $data);
		$statement->execute();
		
		// All Response data is now deleted
		if (!($statement->errno))
		{
			$statement->close();
		}
		else
		{
			$statement->close();
			http_response_code(404);
			echo "Error removing the current survey";
			die();
		}
	}
	
	// Deleting the Response Measurings
	foreach($attribute as &$data)
	{
		$statement = $db_link->prepare("DELETE FROM Measuring_Attributes WHERE id= ?");
		$statement->bind_param("i", $data);
		$statement->execute();
		
		// All Response data is now deleted
		if (!($statement->errno))
		{
			$statement->close();
		}
		else
		{
			$statement->close();
			http_response_code(404);
			echo "Error removing the current survey";
			die();
		}
	}
	
	// Delete the responses
	foreach($responses as &$response)
	{
		$statement = $db_link->prepare("DELETE FROM Responses WHERE id= ?");
		$statement->bind_param("i", $response);
		$statement->execute();
		
		// All Response data is now deleted
		if (!($statement->errno))
		{
			$statement->close();
		}
		else
		{
			$statement->close();
			http_response_code(404);
			echo "Error removing the current survey";
			die();
		}
	}
	
	// Deleting the Quality Weights
	$statement = $db_link->prepare("DELETE FROM Quality_Weights WHERE survey_id= ?");
	$statement->bind_param("i", $survey_id);
	$statement->execute();
		
	// All Response data is now deleted
	if (!($statement->errno))
	{
		$statement->close();
		
		// Deleting the Measuring Attribute from the current survey
		$statement = $db_link->prepare("SELECT measuring_id FROM Surveys WHERE id = ?");
		$statement->bind_param("i", $survey_id);
		$statement->execute();

		if (!($statement->errno))
		{			
			$statement->bind_result($db_survey_measuring_id);
		
			$measuring_id_survey = 0;
		
			while ($statement->fetch())
			{			
				// Getting the ids
				$measuring_id_survey = $db_survey_measuring_id;
			}
			
			$statement->close();
			
			// Deleting the measurment attribute for the survey
			$statement = $db_link->prepare("DELETE FROM Measuring_Attributes WHERE id= ?");
			$statement->bind_param("i", $measuring_id_survey);
			$statement->execute();
			
			if (!($statement->errno))
			{
				$statement->close();
				
				// Delete the survey entry
				$statement = $db_link->prepare("DELETE FROM Surveys WHERE id= ?");
				$statement->bind_param("i", $survey_id);
				$statement->execute();
				
				if (!($statement->errno))
				{
					$statement->close();
					
					echo "true";
				}
				else
				{
					$statement->close();
					http_response_code(404);
					echo "Error removing the current survey";
					die();
				}

			}
			else
			{
				$statement->close();
				http_response_code(404);
				echo "Error removing the current survey";
				die();
			}
			
		}
		else
		{
			$statement->close();
			http_response_code(404);
			echo "Error removing the current survey";
			die();
		}	
	}
	else
	{
		$statement->close();
		http_response_code(404);
		echo "Error removing the current survey";
		die();
	}
		
}
else
{
	$statement->close();
	http_response_code(404);
	echo "Error removing the current survey";
	die();
}	
?>