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

// Local variables

$runner = 0;

// Json array
$json_array = array();

// data array
// First one is the id, second the quality score, and the other fields the attributes of the response
$data_array = array (
	array(0,0,0,0,0,0,0,0)
	);
	
$result = "[";

// First of all get all Responses and the needed data for the attributes of the current survey
$statement = $db_link->prepare("SELECT Responses.id, Measuring_Attributes.dont_know, Measuring_Attributes.priming, Measuring_Attributes.conflict, Measuring_Attributes.anchoring, Measuring_Attributes.straight, Measuring_Attributes.speeding, Measuring_Attributes.incomplete FROM Responses join Response_Measuring on Responses.id = Response_Measuring.response_id join Measuring_Attributes on Measuring_Attributes.id = Response_Measuring.measuring_id WHERE Responses.survey_id = ? and Responses.evaluated = 1");
$statement->bind_param("i", $survey_id);
$statement->execute();
        
if (!($statement->errno))
{			
	$statement->bind_result($db_responseid,$db_dont,$db_priming,$db_conflict,$db_anchoring, $db_straight, $db_speeding, $db_complete);

	while ($statement->fetch())
	{	
		$data_array[$runner][0] = $db_responseid;
		$data_array[$runner][1] = $db_complete;
		$data_array[$runner][2] = $db_speeding;
		$data_array[$runner][3] = $db_straight;
		$data_array[$runner][4] = $db_priming;
		$data_array[$runner][5] = $db_anchoring;
		$data_array[$runner][6] = $db_dont;
		$data_array[$runner][7] = $db_conflict;
					
		$runner ++;
	}
    		
	$statement->close();
	
	// Get for the stored responses the available questions and answers
	foreach($data_array as &$data)
	{
		$statement = $db_link->prepare("Select Response_Data.question_text, Response_Data.answer from Surveys join Responses on Surveys.id = Responses.survey_id join Response_Data on Responses.id = Response_Data.response_id where Surveys.id = ? AND Responses.id = ? and Responses.evaluated = 1 ORDER by Response_Data.id");
		$statement->bind_param("ii", $survey_id, $data[0]);
		$statement->execute();
		
		if (!($statement->errno))
		{			
			$statement->bind_result($db_questiontext, $db_answer);
			
			$index = 0;
			
			$question_array = array (
			array("",""));
			
			// For one response all questions and answers
			while ($statement->fetch())
			{	
				$question_array[$index][0] = $db_questiontext;
				$question_array[$index][1] = $db_answer;
				
				$index ++;
			}
			
			$jsonArrayObject = (array('id' => $data[0], 'incomplete' => $data[1], 'speeding' => $data[2], 'straight' => $data[3], 'priming' => $data[4], 'anchoring' => $data[5], 'dont' => $data[6], 'conflict' => $data[7]));
			
			$jsonArrayQuestions = array();
			
			// Write the data of the actual response into the json array
			foreach($question_array as &$question)
			{	
				$jsonArrayQuestions[] = (array('question' => $question[0], 'answer' => $question[1]));
			}
			
			$questions = array ('questions' => $jsonArrayQuestions);
			
			$encode =  json_encode($jsonArrayObject + $questions);
			
			if(strcmp($encode,"") !== 0)
			{
				$result = $result . $encode . ",";
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

	$result = substr_replace($result ,"",-1);
	
	$result = $result . "]";
	
	echo $result;
}
else
{
	$statement->close();
	http_response_code(404);
	echo "Error adding a new survey";
    die();

}

?>