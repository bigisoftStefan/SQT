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

$result = "";

// Get the user id from the database
$statement = $db_link->prepare("SELECT name, quality_score FROM Surveys WHERE id = ?");
$statement->bind_param("i", $survey_id);
$statement->execute();

if (!($statement->errno))
{			
	$statement->bind_result($db_survey_name, $db_quality_score);

	// Only one survey name and quality score will be selected because the survey id is a key
	while ($statement->fetch())
	{			
		$result = $db_survey_name . ";" . $db_quality_score;
	}
    		
	$statement->close();
	
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