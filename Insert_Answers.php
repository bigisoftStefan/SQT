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

$question = $_POST['question'];
$answer = $_POST['answer'];
$time = $_POST['time'];
$response_id = $_POST['response_id'];

// Insert the response into the data base
$statement = $db_link->prepare("INSERT INTO Response_Data (question_text,answer,response_time, response_id) VALUES (?,?,?,?)");
$statement->bind_param("ssii", $question, $answer, $time, $response_id);
$statement->execute();
    
if (!($statement->errno))
{
	$statement->close();
}
else
{
	$statement->close();
	echo "Error adding a response data";
	http_response_code(404);
	die();	
}

?>