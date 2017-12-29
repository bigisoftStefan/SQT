<!DOCTYPE html>
<!-- Author: Stefan Biegler -->
<!-- Page: Survey View about one survey -->
<!-- Version: 1.0 -->

<?php 
session_start();
if(!isset($_SESSION['userid'])) 
{
	die('Before using this page please log in at <a href="index.php">this</a> page');
}

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
    die('keine Verbindung möglich: ');
}
?>


<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title id="title"></title>

    <!-- Bootstrap -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">

	<!-- Own CSS for own style -->
	<link href="bootstrap/css/custom.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body id ="body">
	    
    <!--<h1 class="text-center" id = "headline">Quality Score <span id="quality_score_header"></span><br /><span id="survey_title_header"></span></h1>-->
    
    <h1 class="text-center" id = "headline">Overview about Survey<br /><span id="survey_title_header"></span></h1>
    
    <!-- Definition of the nav bar -->
	<nav class="navbar navbar-light navbar-fixed-top" id="navbar">
		<div class="pull-left">
			<ul class="nav navbar-nav">
	            <li class="nav-item pull-left"><a id="overview" class="nav-link navheader active" >Overview <span class="sr-only">(current)</span></a></li>
	            <li class="nav-item pull-left"><a id="details" class="nav-link navheader" >Details</a></li>
	            <li class="nav-item pull-left"><a id="showall" class="nav-link navheader" href="surveypage.php">Show All</a></li>
			</ul>
		</div>
  		<div class="pull-right">
            <ul class="nav navbar-nav">
	            <li class="pull-left"><a href="#" data-toggle="modal" data-target="#compareSurvey" class="btn-lg" ><span class="glyphicon glyphicon-transfer" id="icon_navbar"></span></a></li>
	            <!--<li class="pull-left"><a href="#" data-toggle="modal" data-target="#showThreshold" class="btn-lg" ><span class="glyphicon glyphicon-list" id="icon_navbar"></span></a></li>-->
                <li class="dropdown pull-right">
                <a href="#" class="dropdown-toggle btn-lg" data-toggle="dropdown"><span class="glyphicon glyphicon-user" id="icon_navbar" aria-hidden="true"></span><b class="caret" id="caret_navbar"></b></a>
                    <ul class="dropdown-menu">
                        <li class="disabled"><a href=""><i class="icon-cog"></i>Change password</a></li>
                        <li class="divider"></li>
                        <li><a href="http://www.onlinesurvey.co.at"><i class="icon-off"></i> Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
	</nav>
	
	<!--<div class="row" id="pie_chart"></div>-->
	
	<!-- Inspired from http:\//bootsnipp.com/snippets/featured/panel-tables-with-filter-->
	<div class="row">
        <div class="panel panel-primary filterable" id="grid_panel">
            <div class="panel-heading" style="background-color: white; color: #54bf9e; border-bottom-color: black;">
                <h3 class="panel-title">Quality of Answers per Category</h3>
            </div>
            <table class="table table-responsive" id="survey_table">
                <thead>
                    <tr class="filters">
                        <th class="table_header_center">Negative Response Behavior</th>
                        <th class="table_header_center">Detection Rate</th>
                        <th class="table_header_center">Responses [n]</th>
                        <th class="table_header_center">Responses [%]</th>
                    </tr>
                </thead>
                <tbody id="available_surveys">
                </tbody>
            </table>
        </div>
    </div>
    	
	<!-- Modal for changing the password of the current user -->
	<div id="changePassword" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="dialog_header">Change the current password!</h4>
      </div>
      <div class="modal-body">
       <form id="changepassword-form" action="?change_pwd=1" method="post" role="form">
	   		<div class="form-group" id="form_reg_email">
				<input type="email" name="reg_email" id="reg_email" tabindex="1" class="form-control" placeholder="" disabled="disabled">
			</div>
			<div class="form-group" id="form_current_pwd">
				<input type="password" name="current_password" id="current_password" tabindex="2" class="form-control" placeholder="Current Password" data-toggle="tooltip" title="Enter your current password!">
			</div>
			<div class="form-group" id="form_new_pwd">
				<input type="password" name="new_password" id="new_password" tabindex="3" class="form-control" placeholder="New Password" data-toggle="tooltip" title="Enter your new password minimum length of 9!" disabled="disabled">
			</div>
			<div class="form-group" id="form_new_pwd_confirm">
				<input type="password" name="new_confirm_password" id="new_confirm_password" tabindex="4" class="form-control" placeholder="Confirm New Password" disabled="disabled">
			</div>
      </div>
      <div class="modal-footer">
	  	<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<input type="submit" name="changepassword-submit" id="changepassword" tabindex="5" class="form-control btn btn-login" value="Change Password" disabled="disabled">
			</div>
		</div>
      </div>
      </form>
    </div>

  	</div>
	</div>
	

	<!-- Modal for comparing two surveys -->
	<div id="compareSurvey" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="dialog_header">Compare Surveys</h4>
      </div>
      <div class="modal-body">
	  	<form>
	  		<div class="form-group">
			    <label for="compareControl">Select a survey to compare:</label>
				<select class="form-control" id="compareControl">
						
				</select>
			</div>
	  	</form>
	  	<table class="table table-responsive" id="compare_surveys">
            <thead>
                <tr class="filters">
                    <th class="table_header_center">Negative Response Behavior</th>
                    <th class="table_header_center">Detection Rate current survey</th>
                    <th class="table_header_center">Detection Rate comparable survey</th>
                </tr>
            </thead>
            <tbody id="survey_compare">
	        </tbody>
        </table>
      </div>
    </div>

  	</div>
	</div>
	
	
	<!-- Modal for setting the threshold values -->
	<div id="showThreshold" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="dialog_header">Set Threshold Values</h4>
      </div>
      <div class="modal-body">
	    <form id="chnangethreshold-form" action="" method="post" role="form">
	  	<table class="table">
	  		<thead class="thead-inverse">
			    <tr>
			      <th class="table_header_center">Measurement Category</th>
			      <th class="table_header_center">Tolerant</th>
			      <th class="table_header_center">Moderate</th>
			      <th class="table_header_center">Strict</th>
			    </tr>
			  </thead>
			  <tbody>
			    <tr class="table_odd">
			      <td>Priming</td>
			      <td class="table_rows_center"><input type="radio" name="priming_radio"></td>
			      <td class="table_rows_center"><input type="radio" name="priming_radio" checked="checked"></td>
			      <td class="table_rows_center"><input type="radio" name="priming_radio"></td>
			    </tr>
			    <tr>
			      <td>Anchoring</td>
			      <td class="table_rows_center"><input type="radio" name="anchoring_radio"></td>
			      <td class="table_rows_center"><input type="radio" name="anchoring_radio" checked="checked"></td>
			      <td class="table_rows_center"><input type="radio" name="anchoring_radio"></td>
			    </tr>
			    <tr class = "table_odd">
			      <td>Don't know</td>
			      <td class="table_rows_center"><input type="radio" name="dont_radio"></td>
			      <td class="table_rows_center"><input type="radio" name="dont_radio" checked="checked"></td>
			      <td class="table_rows_center"><input type="radio" name="dont_radio"></td>
			    </tr>
			    <tr>
			      <td>Conflicting answers</td>
			      <td class="table_rows_center"><input type="radio" name="conflict_radio"></td>
			      <td class="table_rows_center"><input type="radio" name="conflict_radio" checked="checked"></td>
			      <td class="table_rows_center"><input type="radio" name="conflict_radio"></td>
			    </tr>
			    <tr class="table_odd">
			      <td>Speeding</td>
			      <td class="table_rows_center"><input type="radio" name="speeding_radio"></td>
			      <td class="table_rows_center"><input type="radio" name="speeding_radio" checked="checked"></td>
			      <td class="table_rows_center"><input type="radio" name="speeding_radio"></td>
			    </tr>
			    <tr>
			      <td>Straight lining</td>
			      <td class="table_rows_center"><input type="radio" name="straight_radio"></td>
			      <td class="table_rows_center"><input type="radio" name="straight_radio" checked="checked"></td>
			      <td class="table_rows_center"><input type="radio" name="straight_radio"></td>
			    </tr>
			    <tr class="table_odd">
			      <td>Patterns</td>
			      <td class="table_rows_center"><input type="radio" name="pattern_radio"></td>
			      <td class="table_rows_center"><input type="radio" name="pattern_radio" checked="checked"></td>
			      <td class="table_rows_center"><input type="radio" name="pattern_radio"></td>
			    </tr>
			    <tr>
			      <td>Survey completion</td>
			      <td class="table_rows_center"><input type="radio" name="survey_radio"></td>
			      <td class="table_rows_center"><input type="radio" name="survey_radio" checked="checked"></td>
			      <td class="table_rows_center"><input type="radio" name="survey_radio"></td>
			    </tr>
			  </tbody>
		</table>
	    </form>
		<div class="alert alert-success alert-dismissable" id="hidden_dialog_threshold" style="display: none;">
			<strong>The new thresholds were successfully saved!</strong>
		</div>
      </div>
      <div class="modal-footer">
	  	<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<input type="button" name="refreshcompleted-submit" id="save_thresholds" tabindex="1" class="form-control btn btn-login" value="Save Thresholds">
			</div>
	  	</div>	
      </div>
    </div>

  	</div>
	</div>
	
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <!-- Include the pie charts from highcharts -->
    <script src="https://code.highcharts.com/highcharts.src.js"></script>
    
    <!-- JScript for the survey page -->
    <script>
	    
	// Shows a failure dialog with a given message from the php side of 
	// the script
	function show_dialog_error(message)
	{
		$(".error").html(message);
		$("#dialog_error").removeAttr("style");
	}
	    	
	// Shows a success dialog with a given message from the php side of 
	// the script
	function show_dialog_success(message)
	{
		$(".success").html(message);
		$("#dialog_succes").removeAttr("style");
	}    
    
	    
	$(document).ready(function(){
	    
	    /*****************************************/
		/***** START Variable DEFINITIONS ********/
		/*****************************************/
	    
	    var $progress = $('.progress');
		var $progressBar = $('.progress-bar');
		var $alert = $("#refreshcompleted");
		
		// Get the actual email address of the logged in user
		var change_pwd_mail = "<?php echo $_SESSION['userid'] ?>";
		
		$("#reg_email").val(change_pwd_mail);
				
		var change_pwd_disabled = true;
		
		function validator_object(first, login, previousState) {
			// The first check no span has to be removed and no class
			this.first = first;
			// If the login is true the login button will be activated
			this.login = login;
			// The last state is undefined
			this.previousState = previousState;
		}		
		
		// Current password
		var current_password = new validator_object(false, false, "UNDEF");
		
		// New Password
		var new_password = new validator_object(false, false, "UNDEF");
			
		// Confirmation of the new password
		var new_password_confirm = new validator_object(false, false, "UNDEF");
		
		var getColor = {
			'Green' : '#29f56d',
			'Yellow' : 'yellow',
			'Red' : 'rgba(255, 0, 0, 0.71)'	
		};
		
		// Saves the selected survey id
		var survey_id = window.location.href.split("?")[1].split("=")[1].split("&")[0];
					
		/*****************************************/
		/***** END Variable DEFINITIONS ********/
		/*****************************************/		
		
		/*****************************************/
		/***** START Function DEFINITIONS ********/
		/*****************************************/
		
		// Removes the css style of a validator feedback class from the current component
		function removeStyle(component_id, state)
		{
			$(component_id).find('span').remove();
			$(component_id).removeClass(state);	
		}	
		
		// Adds the css style of a validaror feedback class to the current component
		function addStyle(component_id, state)
		{
			$(component_id).addClass(state);
		} 
			
		// Adds a component to the parent element with a state
		function addComponent(parent_id, component_type, state)
		{
			var $element = $(document.createElement(component_type));
			$element.addClass(state);
						
			$(parent_id).append($element)
		}
			
		// Activates the login button when username and password have the correct format
		function activateChangePassword()
		{
			if(current_password.login == true && new_password.login == true && new_password_confirm.login == true)
			{
				$("#changepassword").removeAttr("disabled");
				change_pwd_disabled = false;
			}
			else
			{
				if(change_pwd_disabled == false)
				{
					$("#changepassword").attr("disabled","disabled");
					change_pwd_disabled = true;
				}
			}
		}
		
		// Activates the add survey button
		function activateAddSurvey()
		{
			if(new_survey_validator.login == true)
			{
				$("#addsurvey").removeAttr("disabled");
			}
			else
			{
				$("#addsurvey").attr("disabled","disabled");
			}
		}
		
		// Correct file name activates the export survey button
		function activateExportSurvey()
		{
			if(export_survey_validator.login == true)
			{
				$("#exportsurvey").removeAttr("disabled");
			}
			else
			{
				$("#exportsurvey").attr("disabled","disabled");
			}
		}
		
		// If there is a correct value in the form input element this function
		// will be activated
		function formelementOK(element_id, element_object, form_id)
		{
			if(element_object.first)
			{
				removeStyle(form_id,"has-error has-feedback");
			}
					
			// Correct Mail Adress
			addStyle(form_id, "has-success has-feedback");
					
			// Span to get the feedback to this element
			addComponent(form_id, 'span', "glyphicon glyphicon-ok form-control-feedback");
					
			// Sets the boarder colour to green
			$(element_id).css({"border-color":"green"});
						
			element_object.previousState = "OK";
				
			element_object.login = true;	
						
			element_object.first = true;
		}
			
		// If there is an incorrect value in the form input element this function
		// will be activated
		function formelementNOTOK(element_id, element_object, form_id)
		{
			if(element_object.first)
			{
				removeStyle(form_id,"has-success has-feedback");
			}
					
			addStyle(form_id, "has-error has-feedback");
					
			// Span to get the feedback to this element
			addComponent(form_id, 'span', "glyphicon glyphicon-remove form-control-feedback");
					
			// Sets the boarder colour to red
			$(element_id).css({"border-color":"red"});
						
			element_object.previousState = "ERROR";
				
			element_object.login = false;	
						
			element_object.first = true;
		}

		// Activates the login button when username and password have the correct format
		function activateChangePassword()
		{
			if(current_password.login == true && new_password.login == true && new_password_confirm.login == true)
			{
				$("#changepassword").removeAttr("disabled");
				change_pwd_disabled = false;
			}
			else
			{
				if(change_pwd_disabled == false)
				{
					$("#changepassword").attr("disabled","disabled");
					change_pwd_disabled = true;
				}
			}
		}
		
		// Fills the survey table with the actual JSON data
    	function fill_surveytable()
		{	
			dataString = 'survey_id='+survey_id;

			// Save the general attributes of the new survey into the database
			$.ajax({
				type:"post",
				url:"Get_Response_Attributes.php",
				dataTyp: "json",
				data: dataString,
				async: false,
				success: function(data) {
					
					//Fitst of all, removes the child nodes of the table 
					// if there are already surveys available
					mynode = document.getElementById("available_surveys");
					
					while (mynode.firstChild) 
					{
						mynode.removeChild(mynode.firstChild);
					}
	
					table_object = JSON.parse(data);
					rows = "";
					runner = 1;
					
					$.each(table_object, function(){
				
						var get_tableclass = "";
						var quality_score = "";
						rows += "<tr ";
						
						non_response_name = "";
						
						// Rename the actual table names for better view in the grid
						switch(this.name) 
						{
							case "anchoring":
							{
								non_response_name = "Right edge straightlining";
								break;
							}
							case "priming":
							{
								non_response_name = "Left edge straightlining";
								break;
							}
							case "incomplete":
							{
								non_response_name = "Incomplete";
								break;
							}
							case "straight":
							{
								non_response_name = "Straightlining";
								break;
							}
							case "dont_know":
							{
								non_response_name = "Don't know answers";
								break;
							}
							case "conflict":
							{
								non_response_name = "Conflicting answers";
								break;
							}
							case "speeding":
							{
								non_response_name = "Speeding";
								break;
							}	
						}
				
						// Sets the different colouring
						if(runner%2 != 0)
						{
							get_tableclass =  "class=\"table_odd\">";				
						}
						else
						{
							get_tableclass =  "class=\"table_even\">";
						}
						
						if(this.name == "speeding")
						{
							// Sets the colouring for the measurement value
							if(this.value >= 80)
							{
								m_value = "<td class = \"qs_good table_rows_center\">";
							}
							else if(this.value >= 50)
							{
								m_value = "<td class = \"qs_medium table_rows_center\">";
							}
							else if(this.value == -1)
							{
								m_value = "<td class = \" table_rows_center\" style= \"background-color: rgba(0,  0,  0, 0.32);font-weight: bold;\">"; 
							}
							else
							{
								m_value = "<td class = \"qs_bad table_rows_center\">";
							}
						}
						else if (this.name == "incomplete")
						{
							// Sets the colouring for the measurement value
							if(this.responsesp == 0)
							{
								m_value = "<td class = \"qs_good table_rows_center\">";
							}
							else if(this.responsesp < 30)
							{
								m_value = "<td class = \"qs_medium table_rows_center\">";
							}
							else if(this.value == -1)
							{
								m_value = "<td class = \" table_rows_center\" style= \"background-color: rgba(0,  0,  0, 0.32);font-weight: bold;\">"; 
							}
							else
							{
								m_value = "<td class = \"qs_bad table_rows_center\">";
							}
						}
						else
						{
							// Sets the colouring for the measurement value
							if(this.value == 0)
							{
								m_value = "<td class = \"qs_good table_rows_center\">";
							}
							else if(this.value == -1)
							{
								m_value = "<td class = \" table_rows_center\" style= \"background-color: rgba(0,  0,  0, 0.32);font-weight: bold;\">"; 
							}
							else
							{
								m_value = "<td class = \"qs_bad table_rows_center\">";
							}
						}
						
						
					
						if(this.value == -1)
						{
							this.value = "N/A";
						}
				
						rows += get_tableclass;
				
						// Remove the attribute pattern from the visible list
						if(this.name != "patterns")
						{
							// If the current value of the attribute is not available then there is also no link to a further action
							if(this.value == "N/A")
							{
								rows += "<td>" + non_response_name + "</td>" + m_value + "" + this.value + "</td><td class=\"table_rows_center\">" + this.responses + "</td><td class=\"table_rows_center\">" + this.responsesp + "%" + "</td></tr>";
							}// If there is no detected data there is no clickable link for it
							else if(this.value == 0 && this.name != "incomplete")
							{
								if(this.name != "speeding")
								{
									rows += "<td>" + non_response_name + "</td>" + m_value + "not detected" + "</td><td class=\"table_rows_center\">" + this.responses + 
									"</td><td class=\"table_rows_center\">" + this.responsesp + "%" + "</td></tr>";
								}
								else
								{
									rows += "<td>" + non_response_name + "</td>" + m_value + (this.value/100) + "</td><td class=\"table_rows_center\">" + this.responses + 
									"</td><td class=\"table_rows_center\">" + this.responsesp + "%" + "</td></tr>";
								}
							}
							else if(this.value > 0 && this.name != "incomplete")
							{
								if(this.name != "speeding")
								{
									rows += "<td><a href = \"details.php?survey_id=" + encodeURIComponent(survey_id) + "\&attribute=" + 
									encodeURIComponent(this.name) + "\">" + non_response_name + "</a></td>" + m_value + "detected" + "</td><td class=\"table_rows_center\">" + this.responses + "</td><td class=\"table_rows_center\">" + this.responsesp + "%" + "</td></tr>";
								}
								else
								{
									rows += "<td><a href = \"details.php?survey_id=" + encodeURIComponent(survey_id) + "\&attribute=" + 
									encodeURIComponent(this.name) + "\">" + non_response_name + "</a></td>" + m_value + (this.value/100) + "</td><td class=\"table_rows_center\">" + this.responses + "</td><td class=\"table_rows_center\">" + this.responsesp + "%" + "</td></tr>";
								}
								
							}
							else if(this.responsesp > 0) // Percentage view for incomplete rate
							{
								rows += "<td><a href = \"details.php?survey_id=" + encodeURIComponent(survey_id) + "\&attribute=" + encodeURIComponent(this.name) + "\">" + non_response_name + "</a></td>" + m_value + this.responsesp + "%</td><td class=\"table_rows_center\">" + this.responses + "</td><td class=\"table_rows_center\">" + this.responsesp + "%" + "</td></tr>";
							}
							else
							{
								rows += "<td>" + non_response_name + "</td>" + m_value + this.responsesp + "</td><td class=\"table_rows_center\">" + this.responses + "</td><td class=\"table_rows_center\">" + this.responsesp + "%" + "</td></tr>";

							}
						}
				
						get_tableclass = "";
						m_value = "";
				
						runner ++;					
					});

					$(rows).appendTo("#survey_table tbody");	
					
				},
				error:function () {
					show_dialog_error("Error loading the current surveys! Please try again later!");
				}				
			});	

		}
		
		// Writes the headline on the oerview survey page
		function insert_headline()
		{
			dataString = 'survey_id='+survey_id;
			
			$.ajax({
					type:"post",
				 	url:"Show_Survey_Title.php",
				 	dataType:"text",
				 	data: dataString,
				 	async: false,
				 	success: function(data) {
				 		// This will be dynamically in the end product
				 		// Name of the survey and the quality score
				 		
				 		$("#survey_title_header").text(data.substring(0, data.indexOf(";")));	
				 		$("#quality_score_header").text(data.substring(data.indexOf(";")+1, data.length) + "%");
				 		$("#title").text("Overview about the survey " + data.substring(0, data.indexOf(";")));
				 			
					},
					error:function () {
							$("#quality_score_header").text("N/A");	
							$("#survey_title_header").text("N/A");
							$("#title").text("Overview about the survey " +  "N/A");	
					}
			});
		}
		
		// Gets the good responses from the data base for the chart
		function get_good_responses()
		{
			return get_responses("good");
		}	    		    
		
		// Gets the medium responses from the data base for the chart
		function get_medium_responses()
		{
			return get_responses("medium");
		}
		
		// Gets the bad responses from the data base for the chart
		function get_bad_responses()
		{
			return get_responses("bad");
		}
		
		// Gets the requested respones
		function get_responses(quality)
		{
			dataString = 'survey_id='+survey_id+'&quality='+quality;
			
			$result = 0;
			
			$.ajax({
					type:"post",
				 	url:"Get_Responeses_Chart.php",
				 	dataType:"text",
				 	data: dataString,
				 	async: false,
				 	success: function(data) {
				 			result =  data;
					},
					error:function () {
							return 0;
					}
			});
			
			return result;
		}
		
	    /*****************************************/
		/***** END Function DEFINITIONS ********/
		/*****************************************/
    	
    	insert_headline();
    	
    	fill_surveytable();
    	
		/*****************************************/
		/***** START Change Password FORM ********/
		/*****************************************/
			
		// Current password
		$("#current_password").keyup(function() {
			
			reg_password_val = $("#current_password").val();
			
			// Only check whether the password has more than 8 digits
			if((reg_password_val.length > 8) && (reg_password_val.length < 40))
			{	
				if(current_password.previousState == "UNDEF" || current_password.previousState == "ERROR")
				{
					formelementOK("#current_password", current_password, "#form_current_pwd")
					// A correct password, activate the new password
					$("#new_password").removeAttr("disabled");
				}	
			}
			else
			{
				// Incorrect password
				// Deactivate the new and new confirmation password
				$("#new_password").val("");
				$("#new_password").attr("disabled","disabled");
				formelementNOTOK("#new_password", new_password, "#form_new_pwd");
				$("#new_confirm_password").val("");
				$("#new_confirm_password").attr("disabled","disabled");
				formelementNOTOK("#new_confirm_password", new_password_confirm, "#form_new_pwd_confirm");
					
				if(current_password.previousState == "UNDEF" || current_password.previousState == "OK")
				{
					
					formelementNOTOK("#current_password", current_password, "#form_current_pwd");
				}
			}
				
			activateChangePassword();
		});

		// New Password
		$("#new_password").keyup(function() {
			
			// If the new password will be changed the new confirmation password
			// will be deleted
			$("#new_confirm_password").val("");
			formelementNOTOK("#new_confirm_password", new_confirm_password, "#form_new_pwd_confirm");
			new_password_confirm.login = false;
			
			new_password_val = $("#new_password").val();
				
			// Minimum Length is 9 and maximum length is 40
			if((new_password_val.length > 8) && (new_password_val.length < 40))
			{	
				if(new_password.previousState == "UNDEF" || new_password.previousState == "ERROR")
				{
						
					formelementOK("#new_password", new_password, "#form_new_pwd")
						
					// A correct password, activate confirmation password
					$("#new_confirm_password").removeAttr("disabled");
				}
					
			}
				else
				{
					// Incorrect password
					
					// Deactivate the confirmation password
					$("#new_confirm_password").attr("disabled","disabled");
					$("#new_confirm_password").val("");
					
					if(new_password.previousState == "UNDEF" || new_password.previousState == "OK")
					{
					
						formelementNOTOK("#new_password", new_password, "#form_new_pwd");
					}
				}
				
				activateChangePassword();
			});
			
		// Confirmation Password for the new password
		$("#new_confirm_password").keyup(function() {
			
			new_password_conf_val = $("#new_confirm_password").val();
				
			// The same password in the confirm section
			if(new_password_conf_val == $("#new_password").val()) 
			{	
				if(new_password_confirm.previousState == "UNDEF" || new_password_confirm.previousState == "ERROR")
				{
						
					formelementOK("#new_confirm_password", new_password_confirm, "#form_new_pwd_confirm");
				}
					
			}
			else
			{
				// Diference between the register password and the confirmation password
				if(new_password_confirm.previousState == "UNDEF" || new_password_confirm.previousState == "OK")
				{
					
					formelementNOTOK("#new_confirm_password", new_password_confirm, "#form_new_pwd_confirm");
				}
			}
				
			activateChangePassword();
		});			
		/*****************************************/
		/******* END Change Password FORM ********/
		/*****************************************/
		
		// The change in the password will be submitted
		$("#changepassword").click(function(){
			
			$("#hidden_dialog_pwd").removeAttr("style");
				
			setTimeout(function() {
				$("#changepassword-form").submit();
			},2000);
		});
		
		// The change in the threshold will be submitted
		$("#save_thresholds").click(function(){
			
			$("#hidden_dialog_threshold").removeAttr("style");
				
			setTimeout(function() {
				$("#changepassword-form").submit();
			},2000);
		});
    	
    	/*****************************************/
		/*********** Start Pie Chart *************/
		/*****************************************/
		
		/* Build the chart
		Highcharts.chart('pie_chart', {
			chart: {
				backgroundColor: "#e2fbf3",
				plotBorderWidth: null,
				plotShadow: false,
				type: 'pie'
			},
			title: {
				useHTML: true,
				text: 'Quality of Responses',
			},
			tooltip: {
				pointFormat: '{name} <b>{point.percentage:.1f}%</b>'
			},
			plotOptions: {
				pie: {
					allowPointSelect: true,
					cursor: 'pointer',
					dataLabels: {
						enabled: false
					},
					showInLegend: true
				}
			},
			series: [{
				colorByPoint: true,
				data: [{
					name: 'Good',
					y: parseInt(get_good_responses()),
					color: getColor['Green']
				}, {
					name: 'Medium',
					y: parseInt(get_medium_responses()),
					color: getColor['Yellow']
				}, {
					name: 'Poor',
					y: parseInt(get_bad_responses()),
					color: getColor['Red']
				}]
			}]
		});*/
			
		$('#showThreshold').on('loaded.bs.modal', function () {
  			$("input.slider")
		});
		
		// When the compate model is shown the available data from 
		// the data base will be loaded
		$('#compareSurvey').on('show.bs.modal', function () {
  			
  			// Fill available online surveys for comparetion
  			dataString = 'survey_id='+survey_id+'&user_mail='+change_pwd_mail;	
			
			$.ajax({
					type:"Post",
				 	url:"Get_Compare_Surveys.php",
				 	dataType:"text",
				 	data: dataString,
				 	async: false,
				 	success: function(data) {
					 			
						//Delete notes if they are already were loaded
						mynode = document.getElementById("compareControl");
							
						while (mynode.firstChild) 
						{
							mynode.removeChild(mynode.firstChild);
						}

						compare_option = "<option id = '-1'>None</option>";
						
						comp_object = JSON.parse(data);
						
						$.each(comp_object, function(){
										
							// Set the current surveys as option in the select field
							compare_option = compare_option + "<option id = '" + this.id + "'>" + this.name + "</option>";
						});
						
						$(compare_option).appendTo("#compareControl");
						
						// Basically nothing will be selected therefore the table will be invisible
						
						// Delete notes if they are already were loaded
						mynode = document.getElementById("survey_compare");
							
						while (mynode.firstChild) 
						{
							mynode.removeChild(mynode.firstChild);
						}
						
						// Set the table invisble
						$("#compare_surveys").css("visibility","hidden");
				 	},
					error:function () {
							return 0;
					}
			});	
  		
		});
		
		// Selecting one survey will fill the table and will also show the attributes
		$("#compareControl").on("change", function()
		{
			selected_id = $(this).find("option:selected").attr("id");
			
			// If the selected id is smaller than 0 => none was selected
			if(selected_id >= 0)
			{
				// Get the neccessary data from the selected survey and from the current survey
				dataString = 'survey_id='+survey_id;
				dataString_compare = 'survey_id='+selected_id;
				
	
				// Save the general attributes of the new survey into the database
				$.ajax({
					type:"post",
					url:"Get_Response_Attributes.php",
					dataTyp: "json",
					data: dataString,
					async: false,
					success: function(result_survey) {
						
						$.ajax({
						type:"post",
						url:"Get_Response_Attributes.php",
						dataTyp: "json",
						data: dataString_compare,
						async: false,
						success: function(result_compare) {
						
							//Fitst of all, removes the child nodes of the table 
							// if there are already surveys available
							mynode = document.getElementById("survey_compare");
							
							while (mynode.firstChild) 
							{
								mynode.removeChild(mynode.firstChild);
							}
			
							original_object = JSON.parse(result_survey);
							compare_object = JSON.parse(result_compare);
							rows = "";
							runner = 1;
							index = 0;
							
							$.each(original_object, function(){
						
								get_tableclass = "";
								quality_score = "";
								rows += "<tr ";
								non_response_name = "";
								
								// Rename the actual table names for better view in the grid
								switch(this.name) 
								{
									case "anchoring":
									{
										non_response_name = "Right edge straightlining";
										break;
									}
									case "priming":
									{
										non_response_name = "Left edge straightlining";
										break;
									}
									case "incomplete":
									{
										non_response_name = "Incomplete";
										break;
									}
									case "straight":
									{
										non_response_name = "Straightlining";
										break;
									}
									case "dont_know":
									{
										non_response_name = "Don't know answers";
										break;
									}
									case "conflict":
									{
										non_response_name = "Conflicting answers";
										break;
									}
									case "speeding":
									{
										non_response_name = "Speeding";
										break;
									}	
								}
						
								// Sets the different colouring
								if(runner%2 != 0)
								{
									get_tableclass =  "class=\"table_odd\">";				
								}
								else
								{
									get_tableclass =  "class=\"table_even\">";
								}
								
								if(this.name == "speeding")
								{
									// Sets the colouring for the measurement value
									if(this.value >= 80)
									{
										m_value = "<td class = \"qs_good table_rows_center\">";
									}
									else if(this.value >= 50)
									{
										m_value = "<td class = \"qs_medium table_rows_center\">";
									}
									else if(this.value == -1)
									{
										m_value = "<td class = \" table_rows_center\" style= \"background-color: rgba(0,  0,  0, 0.32);font-weight: bold;\">"; 
									}
									else
									{
										m_value = "<td class = \"qs_bad table_rows_center\">";
									}
								}
								else if (this.name == "incomplete")
								{
									// Sets the colouring for the measurement value
									if(this.responsesp == 0)
									{
										m_value = "<td class = \"qs_good table_rows_center\">";
									}
									else if(this.responsesp < 30)
									{
										m_value = "<td class = \"qs_medium table_rows_center\">";
									}
									else if(this.value == -1)
									{
										m_value = "<td class = \" table_rows_center\" style= \"background-color: rgba(0,  0,  0, 0.32);font-weight: bold;\">";
										this.value = "N/A"; 
									}
									else
									{
										m_value = "<td class = \"qs_bad table_rows_center\">";
									}
								}
								else
								{
									// Sets the colouring for the measurement value
									if(this.value == 0)
									{
										m_value = "<td class = \"qs_good table_rows_center\">";
										this.value = "not detected";
									}
									else if(this.value == -1)
									{
										m_value = "<td class = \" table_rows_center\" style= \"background-color: rgba(0,  0,  0, 0.32);font-weight: bold;\">";
										this.value = "N/A";
									}
									else
									{
										m_value = "<td class = \"qs_bad table_rows_center\">";
										this.value = "detected";
									}
								}
								
								if(compare_object[index].name == "speeding")
								{
									// Sets the colouring for the measurement value
									if(compare_object[index].value >= 80)
									{
										c_value = "<td class = \"qs_good table_rows_center\">";
									}
									else if(compare_object[index].value >= 50)
									{
										c_value = "<td class = \"qs_medium table_rows_center\">";
									}
									else if(compare_object[index].value == -1)
									{
										c_value = "<td class = \" table_rows_center\" style= \"background-color: rgba(0,  0,  0, 0.32);font-weight: bold;\">";
										compare_object[index].value = "N/A"; 
									}
									else
									{
										c_value = "<td class = \"qs_bad table_rows_center\">";
									}
								}
								else if (compare_object[index].name == "incomplete")
								{
									// Sets the colouring for the measurement value
									if(compare_object[index].responsesp == 0)
									{
										c_value = "<td class = \"qs_good table_rows_center\">";
									}
									else if(compare_object[index].responsesp < 30)
									{
										c_value = "<td class = \"qs_medium table_rows_center\">";
									}
									else if(compare_object[index].value == -1)
									{
										c_value = "<td class = \" table_rows_center\" style= \"background-color: rgba(0,  0,  0, 0.32);font-weight: bold;\">";
										compare_object[index].value = "N/A"; 
									}
									else
									{
										c_value = "<td class = \"qs_bad table_rows_center\">";
									}
								}
								else
								{
									// Sets the colouring for the measurement value
									if(compare_object[index].value == 0)
									{
										c_value = "<td class = \"qs_good table_rows_center\">";
										compare_object[index].value = "not detected";
									}
									else if(compare_object[index].value == -1)
									{
										c_value = "<td class = \" table_rows_center\" style= \"background-color: rgba(0,  0,  0, 0.32);font-weight: bold;\">"; 
										compare_object[index].value = "N/A";
									}
									else
									{
										c_value = "<td class = \"qs_bad table_rows_center\">";
										compare_object[index].value = "detected";
									}
								}
									
								rows += get_tableclass;
								
								// Remove the attribute pattern from the visible list
								if(this.name != "patterns")
								{
									// If the current value of the attribute is not available then there is also no link to a further action
									if((this.value == "N/A") || (compare_object[index].value == "N/A"))
									{
										rows += "<td>" + non_response_name + "</td>" + m_value + this.value + "</td>" + c_value + compare_object[index].value +"</td></tr>";
									}// If there is no detected data there is no clickable link for it
									else if(this.name != "incomplete")
									{
										if(this.name != "speeding")
										{
											rows += "<td>" + non_response_name + "</td>" + m_value + this.value + "</td>" + c_value + compare_object[index].value + "</td></tr>";
										}
										else
										{
											rows += "<td>" + non_response_name + "</td>" + m_value + (this.value/100) + "</td>" + c_value + (compare_object[index].value/100) + "<td></tr>";
										}
									}
									else if(this.responsesp > 0) // Percentage view for incomplete rate
									{
										rows += "<td>" + non_response_name + "</td>" + m_value + this.responsesp + "%</td>" + c_value + compare_object[index].responsesp  + "%</td></tr>";
									}
									else
									{
										rows += "<td>" + non_response_name + "</td>" + m_value + this.responsesp + "%</td>" + c_value + compare_object[index].responsesp  + "%</td></tr>";
		
									}
								}
								
								get_tableclass = "";
								m_value = "";
								c_value = "";
						
								runner ++;
								index++;					
							});
		
							$(rows).appendTo("#survey_compare");
							
							// Set the table visible
							$("#compare_surveys").css("visibility","visible");
							
						},
						error:function () {
							show_dialog_error("Error loading the current surveys! Please try again later!");
						}
					});	
						
					},
					error:function () {
						show_dialog_error("Error loading the current surveys! Please try again later!");
					}				
				});
			}
			else
			{
				$("#compare_surveys").css("visibility","hidden");
				
				// if there are already surveys available
				mynode = document.getElementById("survey_compare");
							
				while (mynode.firstChild) 
				{
					mynode.removeChild(mynode.firstChild);
				}
			}	
  		});

		
		/*****************************************/
		/************* END Pie Chart *************/
		/*****************************************/

		// Tooltip for the GUI elements
		$('[data-toggle="tooltip"]').tooltip(); 
		$('[data-tt="tooltip"]').tooltip(); 
		
		// Add the survey id to the overview view
		$("#overview").attr("href","overview.php?survey_id=" + encodeURIComponent(survey_id));
		
		$("#details").attr("href","details.php?survey_id=" +  encodeURIComponent(survey_id) + "&attribute=" + encodeURIComponent("all"));
		
});
    	
	</script>
	
	 <?php
	// If a user has forgotten his/her password the user can reset it with the help of this function
	if(isset($_GET['change_pwd']))
	{
		$current_pwd = $_POST['current_password'];
		$new_pwd = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
		$error = false;
		
		// Queries the data base whether the password is correct than an update can be made
		$statement = $db_link->prepare("SELECT pwd, activated FROM User WHERE mail = ?");
		$statement->bind_param("s", $_SESSION['userid']);
		$statement->execute();
			
		$statement->bind_result($db_pwd, $db_activated);
			
		while ($statement->fetch())
		{			
			// Error no update possible of the password 
			if((!(password_verify($current_pwd,$db_pwd))) || ($db_activated == 0))
			{
				$error = true;
				echo "<script type=\"text/javascript\">show_dialog_error('Can\'t change the password, please try again!');</script>";		
			}
    	}
    		
    	$statement->close();

		// If the inserted password is correct and the user is activated the new password can be loaded into the 
		// data base
		if($error == false)
		{
			// Updates the password for the existing user
			$statement = $db_link->prepare("UPDATE User SET pwd = ? WHERE mail = ?");
			$statement->bind_param("ss", $new_pwd, $_SESSION['userid']);
			
			$statement->execute();
	
			if (!($statement->errno)) 
			{
				// If the mail address is not registered in the database no mail will be sent
				if($statement->affected_rows > 0)
				{
					echo "<script type=\"text/javascript\">show_dialog_success('Password was successfully changed!');</script>";
				}
				else
				{
					echo "<script type=\"text/javascript\">show_dialog_error('Can\'t change the password, please try again!');</script>";
				}
			}
			else // Failure
			{
				echo "<script type=\"text/javascript\">show_dialog_error('Can\'t change the password, please try again!');</script>";
			}
	
			$statement->close();
		}
	}					 
 ?>

	
 </body>
</html>