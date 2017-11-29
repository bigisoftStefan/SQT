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

// Post parameters from the survey
$response_id = $_POST['response_id'];
$priming = $_POST['priming'];
$anchor = $_POST['anchor'];
$don_t = $_POST['don_t'];
$conflict = $_POST['conflict'];
$straight = $_POST['straight'];
$pattern = $_POST['pattern'];
$incomplete = $_POST['complete'];
$speeding = -1;

// If the survey was not completed all values will be set to -1
if($incomplete == 100)
{
	$priming = -1;
	$anchor = -1;
	$don_t = -1;
	$conflict = -1;
	$straight = -1;
	$pattern = -1;
}
	
// Save the attributes in the data base
$statement = $db_link->prepare("INSERT INTO Measuring_Attributes (patterns, dont_know, priming, conflict, anchoring, straight, speeding, incomplete) VALUES (?,?,?,?,?,?,?,?)");
$statement->bind_param("iiiiiiii", $pattern, $don_t, $priming, $conflict, $anchor, $straight, $speeding, $incomplete);
$statement->execute();
	
if (!($statement->errno))
{
	$attribute_id = $db_link->insert_id;
	$statement->close();
		
	$statement = $db_link->prepare("INSERT INTO Response_Measuring (response_id,measuring_id) VALUES (?,?)");
	$statement->bind_param("ii", $response_id, $attribute_id);
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
		
}
else
{
	echo $statement -> errno;
	$statement->close();
	http_response_code(404);
	die();	
}

?>