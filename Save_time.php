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

$response_id = $_POST['response_id'];
$calculate_speeding = $_POST['speeding'];
$response_time = 0;

if($calculate_speeding == "yes")
{	
		
	// Calculate the used time for the current response
	$statement = $db_link->prepare("SELECT Count(response_time) FROM Response_Data WHERE response_id = ?");
	$statement->bind_param("i", $response_id);
	$statement->execute();

	if (!($statement->errno))
	{
		$statement->bind_result($time);
	
		while ($statement->fetch()) 
		{
    		$response_time = $time;
		}
	
		$statement->close();
	
		// Save the response time into the data base
		$statement = $db_link->prepare("UPDATE Responses SET respond_speeding = ? WHERE id = ?");
		$statement->bind_param("ii", $response_time, $response_id);
			
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
	}
	else
	{
		$statement->close();
		http_response_code(404);
		echo "Error adding a response";
		die();
	}
}
else
{
	$response_time = -1;
	
	$statement = $db_link->prepare("UPDATE Responses SET respond_speeding = ? WHERE id = ?");
	$statement->bind_param("ii", $response_time, $response_id);
			
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
}

?>