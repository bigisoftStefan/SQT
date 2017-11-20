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

// Ajax request variables
$response_id = $_POST['response_id'];
$status = $_POST['status'];

// local variable
$db_status = 0;

if($status == "true")
{
	$db_status = 1;
}
	
// Save the response time into the data base
$statement = $db_link->prepare("UPDATE Responses SET evaluated = ? WHERE id = ?");
$statement->bind_param("ii", $db_status, $response_id);
			
$statement->execute();
    
if (!($statement->errno))
{
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