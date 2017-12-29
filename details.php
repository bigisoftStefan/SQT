<!DOCTYPE html>
<!-- Author: Stefan Biegler -->
<!-- Page: Details View about one survey -->
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
	  
	<div class="alert alert-success alert-dismissable" id="dialog_succes" style="display: none;">
		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		<strong class = "success"></strong>
	</div>
									
	<div class="alert alert-danger alert-dismissable" id="dialog_error" style="display: none;">
		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		<strong class = "error"></strong>
	</div>

	    
    <h1 class="text-center" id = "headline">Details about Survey<br /><span id="survey_title_header"></span></h1>
    
    <!-- Definition of the nav bar -->
	<nav class="navbar navbar-light navbar-fixed-top" id="navbar">
		<div class="pull-left">
			<ul class="nav navbar-nav">
	            <li class="nav-item pull-left"><a id="overview" class="nav-link navheader" >Overview</a></li>
	            <li class="nav-item pull-left"><a id="details" class="nav-link navheader active" >Details <span class="sr-only">(current)</span></a></li>
	            <li class="nav-item pull-left"><a id="showall" class="nav-link navheader" href="surveypage.php">Show All</a></li>
			</ul>
		</div>
  		<div class="pull-right">
            <ul class="nav navbar-nav">
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
	
	<!-- Inspired from http:\//bootsnipp.com/snippets/featured/panel-tables-with-filter-->
	<div class="row">
        <div class="panel panel-primary filterable" id="grid_panel">
            <div class="panel-heading" style="background-color: white; color: #54bf9e; border-bottom-color: black;">
                <h3 class="panel-title">Detailed Response Information</h3>
                <div class="pull-right">
                    <button class="btn btn-default btn-xs btn-filter"><span class="glyphicon glyphicon-filter"></span> Filter</button>
                </div>
                <div class="pull-right">
                    <button style="margin-right: 1.5em;" class="btn btn-default btn-xs btn-survey" id="apply_changes" ><span class="glyphicon glyphicon-ok"></span> Apply Changes</button>
                </div>
                <div  style="margin-right: 1.5em; margin-top: -1.8em; width: auto;" class="pull-right">
					<select class="form-control" id="filter_dropdown">
						<option value = "all" id ="option_all">Show All</option>
						<option value = "incomplete" id ="option_complete">Incomplete</option>
						<option value = "speeding" id ="option_speeding">Speeding</option>
						<option value = "straight" id ="option_straight">Straightlining</option>
						<option value = "priming" id ="option_priming">Left edge straightlining</option>
						<option value = "anchoring" id ="option_anchoring">Right edge straight lining</option>
						<option value = "dont_know" id ="option_dont">Don't know answers</option>
						<option value = "conflict" id ="option_conflict">Conflict answers</option>
					</select>	
                </div>
            </div>
            <table class="table table-responsive" id="survey_table">
                <thead>
                    <tr class="filters">
                        <th><input type="text" class="form-control" placeholder="Id" disabled></th>
                        <th id = "action_header">Details</th>
                        <!--<th><input type="text" class="form-control" placeholder="Quality Score" disabled></th>-->
                        <th id = "action_header" class = "table_header_center">Include cases</th>
                    </tr>
                </thead>
                <tbody id="details_view">
                </tbody>
            </table>
        </div>
    </div>
   	
	<!-- Modal showing the details of a user respond -->
	<div id="showDetails" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title dialog_header"><span class="dialog_header" id="header_details"></span></h4>
      </div>
      <div class="modal-body">
	    <div class="row">
        <div class="panel panel-primary filterable" id="grid_panel">
            <div class="panel-heading" style="background-color: white; color: #54bf9e; border-bottom-color: black;">
                <h3 class="panel-title">Quality of Answers per Category</h3>
            </div>
            <table class="table table-responsive" id="details_table">
                <thead>
                    <tr class="filters">
                        <th class="table_header_center">Non Response Behavior</th>
                        <th class="table_header_center">Value [%]</th>
                    </tr>
                </thead>
                <tbody id="details_body">
                </tbody>
            </table>
        </div>
    	</div>

      </div>
    </div>

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
		
		var filter_option = window.location.href.split("&")[1].split("=")[1];
		
		// Saves this responses where the statistical evaluated value should be set to
		// false
		var statistical_evaluated_array = [];
		
		// Represents a checkbox element
		function statistical_evaluated(id,status)
		{
			// The id of the response
			this.id = id;
			
			// The status of the evaluation, true => evaluated, false => not
			this.status = status;
		}
				
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
			dataString = 'survey_id='+survey_id+"&filter="+filter_option;

			// Save the general attributes of the new survey into the database
			$.ajax({
				type:"post",
				url:"Get_Response_Data.php",
				dataTyp: "json",
				data: dataString,
				async: false,
				success: function(data) {
			
					//Fitst of all, removes the child nodes of the table 
					// if there are already surveys available
					
					mynode = document.getElementById("details_view");
					
					while (mynode.firstChild) 
					{
						mynode.removeChild(mynode.firstChild);
					}
	
					table_object = JSON.parse(data);
					rows = "";
					runner = 1;
					
					$.each(table_object, function()
					{
						var get_tableclass = "";
						var quality_score = "";
						var details_button = "<button type=\"button\" id = \"details_" + this.id + "\" class=\"btn btn-default details\" data-toggle=\"modal\" data-target=\"#showDetails\"><span class=\"glyphicon glyphicon-info-sign\" aria-hidden=\"true\"></span></button>";
						var check = "";
						
						// Whether the attribute is true than it will be checked otherwise it is unchecked
						if(this.evaluated == true)
						{
							check = "<label><input class=\"evaluated\" id = \"" + this.id + "\" type=\"checkbox\" checked = \"true\"></label>";
						}
						else
						{
							check = "<label><input class=\"evaluated\" id = \"" + this.id + "\" type=\"checkbox\" ></label>";
						}
						
						rows += "<tr ";
				
						// Sets the different colouring
						if(runner%2 != 0)
						{
							get_tableclass =  "class=\"table_odd\">";				
						}
						else
						{
							get_tableclass =  "class=\"table_even\">";
						}
				
						/* Sets the colouring for the quality score
						if(this.qualityscore <= 30)
						{
							quality_score = "<td class = \"qs_bad\">";
						}
						else if (this.qualityscore > 30 && this.qualityscore <= 75)
						{
							quality_score = "<td class = \"qs_medium\">";
						}
						else
						{		
							quality_score = "<td class = \"qs_good\">";
						}*/
				
						rows += get_tableclass;
				
						rows += "<td>" + this.id + "</td><td>" + details_button + "</td>"+ /*+ quality_score + this.qualityscore + "%" + "</td>*/"<td class=\"check\">" + check + "</td></tr>";
				
						get_tableclass = "";
						quality_score = "";
			
						runner ++;
					});

					$(rows).appendTo("#survey_table tbody");
				},
				error:function () {
					show_dialog_error("Error loading the current surveys! Please try again later!");
				}				
			});	
		}   		    
				
		// Fills the details table with data
		function fill_detailstable(response_id)
		{			
			//Fitst of all, removes the child nodes of the table 
			// if there are already surveys available
					
			mynode = document.getElementById("details_body");
					
			while (mynode.firstChild) 
			{
				mynode.removeChild(mynode.firstChild);
			}
			
			// Show as the headline the current response id
			$("#header_details").text("Respondent id: " + response_id);
			
			dataString = 'response_id='+response_id;

			// Save the general attributes of the new survey into the database
			$.ajax({
				type:"post",
				url:"Get_Response_Detail_Data.php",
				dataTyp: "json",
				data: dataString,
				async: false,
				success: function(data) {
			
					table_object = JSON.parse(data);
					rows = "";
					runner = 1;
					
					$.each(table_object, function()
					{
				
						var get_tableclass = "";
						var quality_score = "";
								
						rows += "<tr ";
						
						// Sets the correct name for the different non response behavior
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
							if(this.value == 0)
							{
								m_value = "<td class = \"qs_good table_rows_center\">";
							}
							else if(this.value < 30)
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
								rows += "<td>" + non_response_name + "</td>" + m_value + "" + this.value + "</td></tr>";
							}// If there is no detected data there is no clickable link for it
							else if(this.value == 0 && this.name != "incomplete")
							{
								if(this.name != "speeding")
								{
									rows += "<td>" + non_response_name + "</td>" + m_value + "not detected" + "</td></tr>";
								}
								else
								{
									rows += "<td>" + non_response_name + "</td>" + m_value + (this.value/100) + "</td></tr>";
								}
							}
							else if(this.value > 0 && this.name != "incomplete")
							{
								if(this.name != "speeding")
								{
									rows += "<td>" + non_response_name + "</td>" + m_value + "detected" + "</td></tr>";
								}
								else
								{
									rows += "<td>" + non_response_name + "</td>" + m_value + (this.value/100) + "</td></tr>";
								}
								
							}
							else if(this.value > 0) // Percentage view for incomplete rate
							{
								rows += "<td>" + non_response_name + "</td>" + m_value + "Yes" + "</td></tr>";
							}
							else
							{
								rows += "<td>" + non_response_name + "</td>" + m_value + "No" + "</td></tr>";
							}
						}

						get_tableclass = "";
						m_value = "";
				
						runner ++;
					});

					$(rows).appendTo("#details_table tbody");
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
				 		$("#title").text("Details about the survey " + data.substring(0, data.indexOf(";")));
				 			
					},
					error:function () {
							$("#quality_score_header").text("N/A");	
							$("#survey_title_header").text("N/A");
							$("#title").text("Details about the survey " +  "N/A");	
					}
			});
		}
		
		// Saves for which responses the evaluated value should be changed
		function save_statistical_selection(evaluated_id)
		{			
			// First of all check, whether this object is already in the array
			// if not add it to the array
			
			element_index = check_object(evaluated_id);
			
			if(element_index >= 0)
			{
				statistical_evaluated_array[element_index].status = evaluated_check(evaluated_id);
			}
			else
			{
				// add the value with the id
				statistical_evaluated_array.push(new statistical_evaluated(evaluated_id,evaluated_check(evaluated_id)));
			}
		}
		
		// Loops through the statiscal eveluated checkbox array
		// if the the element is found return the index
		// if not return -1
		function check_object(evaluated_id)
		{
			for(runner = 0; runner < statistical_evaluated_array.length; runner++)
			{				
				if(statistical_evaluated_array[runner].id == evaluated_id)
				{
					return runner;
				}
			}
			
			return -1;
		}
		
		// Returns true if the evaluated checkbox is checked
		// otherwise false
		function evaluated_check(evaluated_id)
		{
			if(document.getElementById(evaluated_id).checked)
			{
				return true;
			}
			
			return false;
			
		}
		
		// After loading the page the filter will show the selected filter option from 
		// the URL
		function set_filteroption_dropdown()
		{
			$("#filter_dropdown").val(filter_option).change();
			
		}  		
	    /*****************************************/
		/***** END Function DEFINITIONS ********/
		/*****************************************/
		    	
		insert_headline();
    	
    	fill_surveytable();
    	
    	set_filteroption_dropdown()
    	
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
		
		/*****************************************/
		/************ START FILTER ***************/
		/*****************************************/
		$('.filterable .btn-filter').click(function(){
        	var $panel = $(this).parents('.filterable'),
			
			$filters = $panel.find('.filters input'),
			$tbody = $panel.find('.table tbody');
				
			if ($filters.prop('disabled') == true) 
			{
				$filters.prop('disabled', false);
				$filters.first().focus();
        	} 
        	else 
        	{
				$filters.val('').prop('disabled', true);
				$tbody.find('.no-result').remove();
				$tbody.find('tr').show();
        	}
  		});

  		$('.filterable .filters input').keyup(function(e){
       		
       		/* Ignore tab key */
	   		var code = e.keyCode || e.which;
	   		if (code == '9') return;
        
	   		/* Useful DOM data and selectors */
	   		var $input = $(this),
	   		inputContent = $input.val().toLowerCase(),
	   		$panel = $input.parents('.filterable'),
	   		column = $panel.find('.filters th').index($input.parents('th')),
	   		$table = $panel.find('.table'),
	   		$rows = $table.find('tbody tr');
	   		
	   		/* Dirtiest filter function ever ;) */
	   		var $filteredRows = $rows.filter(function(){
            	var value = $(this).find('td').eq(column).text().toLowerCase();
				return value.indexOf(inputContent) === -1;
        	});
			
			/* Clean previous no-result if exist */
			$table.find('tbody .no-result').remove();
        
			/* Show all rows, hide filtered ones (never do that outside of a demo ! xD) */
			$rows.show();
			$filteredRows.hide();
        
			/* Prepend no-result row if all rows are filtered */
			if ($filteredRows.length === $rows.length) {
            	$table.find('tbody').prepend($('<tr class="no-result text-center"><td colspan="'+ $table.find('.filters th').length +'">No result found</td></tr>'));
        	}
    	});
    	
    	/*****************************************/
		/************* END FILTER ****************/
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
    	
		// Tooltip for the GUI elements
		$('[data-toggle="tooltip"]').tooltip(); 
		$('[data-tt="tooltip"]').tooltip();
		
		// Add the survey id to the overview view
		$("#overview").attr("href","overview.php?survey_id=" + encodeURIComponent(survey_id));
	
		$("#details").attr("href","details.php?survey_id=" +  encodeURIComponent(survey_id) + "&attribute=" + encodeURIComponent(filter_option));
		
		// Onclick handler for the details button
		$('#survey_table tbody').on('click', '.details', function(){
			current_details = this.id.substring(this.id.indexOf("_") + 1, this.id.length);
			
			fill_detailstable(current_details);
		});

		// Defines what to do, when the user clicks on apply changes
		// if he deselcts some responses a new quality index will be 
		// calculated
		$("#apply_changes").on("click", function(){
			
			// Save the changes into the data base and calculate again the 
			// quality score of the survey
			// Thereafter there is a list refresh
			
			local_runner = 0;
			
			for(runner=0; runner < statistical_evaluated_array.length; runner++)
			{
				dataString = 'response_id='+statistical_evaluated_array[runner].id + '&status=' + statistical_evaluated_array[runner].status;
			
				$.ajax({
					type:"post",
					url:"Save_Evaluated_Items.php",
					dataTyp: "text",
					data: dataString,
					async: false,
					success: function(data) {

					},
					error:function () {
						show_dialog_error("Error loading the current surveys! Please try again later!");
					}				
				});
				
				local_runner ++;
			}	
			
			// If all responses are updated, calculate the new quality score based on
			// this data
			if(local_runner == statistical_evaluated_array.length)
			{
				//Recalculate the quality score of the current survey
				dataString = 'survey_id='+survey_id;
				
				
				$.ajax({
					type:"post",
					url:"Update_Evaluated.php",
					dataTyp: "text",
					data: dataString,
					async: false,
					success: function(data) {
						fill_surveytable();
						
						// Update the quality score
						alert(data);
						//$("#quality_score_header").text(data.substring(data.indexOf(";")+1, data.length) + "%");
					},
					error:function () {
						show_dialog_error("Error loading the current surveys! Please try again later!");
					}				
				});				
			}
		});
		
		// Add Drop Down filter event if a new element will be selected
		$(document).on("change", "#filter_dropdown", function(){
			
			// Save the new filter option
			filter_option = $(this).val();
			
			// fill the survey table
			fill_surveytable();
		});
		
		// Add click handler for the evaluated check box
		$(document).on("click", ".evaluated", function(){
			
			save_statistical_selection($(this).attr("id"));
			
		});
 
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