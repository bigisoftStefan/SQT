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

// Current User ID
$db_user = 0;

// Json array
$json_array = array();

// Index value of the Json array
$runner = 0;

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
		// Getting the user id
		$db_user = $db_user_id;
	}
    		
	$statement->close();

	// Get all available surveys for the current user
	$statement = $db_link->prepare("SELECT Surveys.id, Surveys.name, Surveys.topic, Surveys.quality_score, Surveys.survey_link FROM Surveys join User on Surveys.user_id = User.id where User.id = ? ORDER by Surveys.id ASC");
	$statement->bind_param("i", $db_user);
	$statement->execute();
    
    if (!($statement->errno))
	{
		$statement->bind_result($id,$survey_name,$survey_topic,$survey_quality_score, $survey_link);
				
		while($statement->fetch())
		{
			// Saving the data into a json array
			// '[''{ "id":"1" , "name":"test" , "topic":"test" , "qualityscore":"60", "surveylink":"www.orf.a" } ]';
			
			$jsonArrayObject = (array('id' => $id, 'name' => $survey_name, 'topic' => $survey_topic, 'qualityscore' => $survey_quality_score, 'survey_link' => $survey_link));
            $json_array[$runner] = $jsonArrayObject;
			
			$runner ++;
		}
		
		echo json_encode($json_array);
		
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

?>