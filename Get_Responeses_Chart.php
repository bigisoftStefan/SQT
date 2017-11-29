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
$response_quality = $_POST['quality'];

$quality_response_count = 0;

$statement = null;

// Quality bad is, when the quality score is equal less 30, medium between 31 and 75 and good above 75
switch ($response_quality) {
    case "good":
        // Get amount of each quality response
		$statement = $db_link->prepare("SELECT Count(Responses.id) FROM Responses join Surveys on Responses.survey_id = Surveys.id where Responses.quality_score >= 75 and Surveys.id = ? and Responses.evaluated = 1");
		$statement->bind_param("i", $survey_id);
		$statement->execute();
        break;
    case "medium":
        $statement = $db_link->prepare("SELECT Count(Responses.id) FROM Responses join Surveys on Responses.survey_id = Surveys.id where Responses.quality_score > 30 and Responses.quality_score < 75 and Surveys.id = ? and Responses.evaluated = 1");
        $statement->bind_param("i", $survey_id);
		$statement->execute();
        break;
    case "bad":
        $statement = $db_link->prepare("SELECT Count(Responses.id) FROM Responses join Surveys on Responses.survey_id = Surveys.id where Responses.quality_score <= 30 and Surveys.id = ? and Responses.evaluated = 1");
        $statement->bind_param("i", $survey_id);
		$statement->execute();
        break;
}

if (!($statement->errno))
{			
	$statement->bind_result($db_number);

	// Only one count will be available
	while ($statement->fetch())
	{			
		$quality_response_count = $db_number;
	}
    		
	$statement->close();
	
	echo $quality_response_count;
	
}
else
{
	$statement->close();
	http_response_code(404);
	echo "Error adding a new survey";
    die();

}

?>