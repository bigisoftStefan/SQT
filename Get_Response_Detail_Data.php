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
$response_id = $_POST['response_id'];

// Local variables

// Json array
$json_array = array();

// data array
// First field value is the name of the attribute
// Second field value is the value of the attribute
$response_attribute_data = array(
	 array("incomplete",0),
	 array("speeding",0),
	 array("straight",0),
	 array("priming",0),
	 array("anchoring",0),
	 array("dont_know",0),
	 array("conflict",0),
	 array("patterns",0)
);

$statement = $db_link->prepare("SELECT Measuring_Attributes.patterns, Measuring_Attributes.dont_know, Measuring_Attributes.priming, Measuring_Attributes.conflict, Measuring_Attributes.anchoring, Measuring_Attributes.straight, Measuring_Attributes.speeding, Measuring_Attributes.incomplete FROM Measuring_Attributes join Response_Measuring on Measuring_Attributes.id = Response_Measuring.measuring_id join Responses on Response_Measuring.response_id = Responses.id where Responses.id = ?");
$statement->bind_param("i", $response_id);
$statement->execute();
        
if (!($statement->errno))
{			
	$statement->bind_result($db_patterns, $db_dont, $db_priming, $db_conflict, $db_anchoring, $db_straight, $db_speeding, $db_complete);

	/* Detailed infomration about a single response of an user
			var details_contentd = '[' +
			'{ "name":"Priming" , "value":"80"},' +
			'{ "name":"Anchoring" , "value":"75"},' +
			'{ "name":"Dont know answers" , "value":"N/A"},' +
			'{ "name":"Conflicting answers" , "value":"55"},' +
			'{ "name":"Speeding" , "value":"00"},' +
			'{ "name":"Straight Lining", "value":"15"},' +
			'{ "name":"Patterns" , "value":"N/A"},' +
			'{ "name":"Survey Completion" , "value":"15"} ]';*/

	while ($statement->fetch())
	{	
		// If the survey is not completed nothing to do
		if($db_complete == 0)
		{
			$response_attribute_data[0][1] = $db_complete;
			$response_attribute_data[1][1] = $db_speeding;
			$response_attribute_data[2][1] = $db_straight;
			$response_attribute_data[3][1] = $db_priming;
			$response_attribute_data[4][1] = $db_anchoring;
			$response_attribute_data[5][1] = $db_dont;
			$response_attribute_data[6][1] = $db_conflict;
			$response_attribute_data[7][1] = $db_patterns;
		}
		else
		{
			$response_attribute_data[0][1] = $db_complete;
			$response_attribute_data[1][1] = -1;
			$response_attribute_data[2][1] = -1;
			$response_attribute_data[3][1] = -1;
			$response_attribute_data[4][1] = -1;
			$response_attribute_data[5][1] = -1;
			$response_attribute_data[6][1] = -1;
			$response_attribute_data[7][1] = -1;
		}
	}
    		
	$statement->close();
	
	for($i = 0; $i < 8; $i++)
	{		
		$jsonArrayObject = (array('name' => $response_attribute_data[$i][0], 'value' => $response_attribute_data[$i][1]));
        $json_array[$i] = $jsonArrayObject;
	}
	
	echo json_encode($json_array);
	
}
else
{
	$statement->close();
	http_response_code(404);
	echo "Error adding a new survey";
    die();

}

?>