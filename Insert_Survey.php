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
$user_mail = $_POST['user_mail_address'];
$survey_topic = $_POST['survey_topic'];
$survey_name = $_POST['survey_name'];

// User value
$db_user = 0;

// Survey id after the insertion
$survey_id = 0;

// Get the user id from the database
$statement = $db_link->prepare("SELECT id FROM User WHERE mail = ?");
$statement->bind_param("s", $user_mail);
$statement->execute();

if (!($statement->errno))
{			
	$statement->bind_result($db_user_id);

	// Online one user id can be found because each user is unique
	while ($statement->fetch())
	{			
		// Error no update possible of the password 
		$db_user = $db_user_id;
	}
    		
	$statement->close();

	// Saves the new survey into the data base
	$statement = $db_link->prepare("INSERT INTO Surveys (user_id,name,topic) VALUES (?,?,?)");
	$statement->bind_param("sss", $db_user,$survey_name,$survey_topic);
	$statement->execute();
    
    if (!($statement->errno))
	{
		
		$survey_id = $db_link->insert_id;
		$statement->close();
		
		// Insertion of the attributes weights after the survey was generated
		$statement = $db_link->prepare("INSERT INTO Quality_Weights (survey_id) VALUES (?)");
		$statement->bind_param("i",$survey_id);
		$statement->execute();
		
		if (!($statement->errno))
		{
			echo($survey_id);
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
