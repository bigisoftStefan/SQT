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

$survey_id = $_POST['survey_id'];

// Insert the response into the data base
$statement = $db_link->prepare("INSERT INTO Responses (survey_id) VALUES (?)");
$statement->bind_param("i", $survey_id);
$statement->execute();
    
if (!($statement->errno))
{
	echo $db_link->insert_id;
	$statement->close();
}
else
{
	$statement->close();
	http_response_code(404);
	echo "Error adding a response";
	die();	
}

?>