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
$filter_option = $_POST['filter'];

// Local variables

$runner = 0;

// Json array
$json_array = array();

$statement = null;

switch ($filter_option) {
    case "all":
        $statement = $db_link->prepare("SELECT id, quality_score, evaluated FROM Responses WHERE Responses.survey_id = ?");
        $statement->bind_param("i", $survey_id);
		$statement->execute();
        break;
    default:
    	if($filter_option == "speeding")
    	{
	    	 $statement = $db_link->prepare("SELECT Responses.id, Responses.quality_score, Responses.evaluated FROM Responses join Response_Measuring on Responses.id = Response_Measuring.response_id join Measuring_Attributes on Response_Measuring.measuring_id = Measuring_Attributes.id WHERE Responses.survey_id = ? and Measuring_Attributes." . $filter_option . " < 100 and Measuring_Attributes." . $filter_option . " > 0");
    	}
    	else
    	{
        	$statement = $db_link->prepare("SELECT Responses.id, Responses.quality_score, Responses.evaluated FROM Responses join Response_Measuring on Responses.id = Response_Measuring.response_id join Measuring_Attributes on Response_Measuring.measuring_id = Measuring_Attributes.id WHERE Responses.survey_id = ? and Measuring_Attributes." . $filter_option . " > 0");
        }
        
        $statement->bind_param("i", $survey_id);
		$statement->execute();
        break;
}
        
if (!($statement->errno))
{			
	$statement->bind_result($db_id, $db_quality_score, $db_evaluated);

	/*var table_contend = '[' +
			'{ "id":"1" , "qualityscore":"78" , "evaluated":"TRUE"},' +
			'{ "id":"2" , "qualityscore":"10" , "evaluated":"TRUE"},' +
			'{ "id":"3" , "qualityscore":"62" , "evaluated":"TRUE"},' +
			'{ "id":"4" , "qualityscore":"20" , "evaluated":"TRUE"}, '+
			'{ "id":"5" , "qualityscore":"40" , "evaluated":"TRUE"} ]';*/
	
	while ($statement->fetch())
	{	
		$evaluated = true;
		
		if($db_evaluated == 0)
		{
			$evaluated = false;
		}
				
		$jsonArrayObject = (array('id' => $db_id, 'qualityscore' => $db_quality_score, 'evaluated' => $evaluated));
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

?>