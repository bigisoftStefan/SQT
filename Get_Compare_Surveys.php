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
$user_mail = $_POST['user_mail'];

// Json array
$json_array = array();

// Runner value
$runner = 0;

$user_id = 0;

// Gets the user id from the email address
$statement = $db_link->prepare("SELECT id from User where mail = ?");
$statement->bind_param("s", $user_mail);
$statement->execute();

if (!($statement->errno))
{	
	$statement->bind_result($db_user_id);
	
	// Get all availabe surveys with name and id except the actual one
	while ($statement->fetch())
	{
		$user_id = 	$db_user_id;		
	}
		
	$statement->close();

	// Select all surveys except the current one
	$statement = $db_link->prepare("SELECT id, name FROM Surveys WHERE id != ? AND user_id = ?");
	$statement->bind_param("ii", $survey_id,$user_id);
	$statement->execute();

	if (!($statement->errno))
	{			
		$statement->bind_result($db_id, $db_name);
	
		// Get all availabe surveys with name and id except the actual one
		while ($statement->fetch())
		{			
			$jsonArrayObject = (array('id' => $db_id, 'name' => $db_name));
	        $json_array[$runner] = $jsonArrayObject;
	
			$runner ++;
		}
	    		
		$statement->close();
		
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