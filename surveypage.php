<!DOCTYPE html>
<!-- Author: Stefan Biegler -->
<!-- Page: Survey Overview of the current user -->
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
    <title>Available Online Surveys</title>

    <!-- Bootstrap -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">

	<!-- Own CSS for own style -->
	<link href="bootstrap/css/custom.css" rel="stylesheet">
	
	<!-- Toggle Button Style Sheet -->
	<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">

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
	    
    <h1 class="text-center" id = "headline">Available Online Surveys</h1>
    
    <!-- Definition of the nav bar -->
	<nav class="navbar navbar-light navbar-fixed-top" id="navbar">
  		<div class="pull-right">
            <ul class="nav navbar-nav nav-right">
	            <!--<li class="pull-left"><a href="#" id="updateSurveys" class="btn-lg" ><span class="glyphicon glyphicon-refresh" id="icon_navbar"></span></a></li>-->
                <li class="dropdown pull-right">
                <a href="#" class="dropdown-toggle btn-lg" data-toggle="dropdown"><span class="glyphicon glyphicon-user" id="icon_navbar" aria-hidden="true"></span><b class="caret" id="caret_navbar"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="" data-toggle="modal" data-target="#changePassword"><i class="icon-cog"></i>Change password</a></li>
                        <li class="divider"></li>
                        <li><a href="logout.php"><i class="icon-off"></i> Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
	</nav>
	
	<!-- Inspired from http:\//bootsnipp.com/snippets/featured/panel-tables-with-filter-->
	<div class="row">
        <div class="panel panel-primary filterable" id="grid_panel">
            <div class="panel-heading" style="background-color: white; color: #54bf9e; border-bottom-color: black;">
                <h3 class="panel-title">Available Online Surveys</h3>
                <div class="pull-right">
                    <button class="btn btn-default btn-xs btn-filter"><span class="glyphicon glyphicon-filter"></span> Filter</button>
                </div>
                <div class="pull-right">
                    <button class="btn btn-default btn-xs btn-survey" data-toggle="modal" data-target="#add_survey_modal" id="add_survey" ><span class="glyphicon glyphicon-plus"></span> Add Survey</button>
                </div>
            </div>
            <table class="table table-responsive" id="survey_table">
                <thead>
                    <tr class="filters">
                        <th><input type="text" class="form-control" placeholder="Id" disabled></th>
                        <th><input type="text" class="form-control" placeholder="Name" disabled></th>
                        <th><input type="text" class="form-control" placeholder="Topic" disabled></th>
                        <!--<th><input type="text" class="form-control" placeholder="Quality Score" disabled></th>-->
                        <th style="width:15em;"> Action </th>
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
	
	<!-- Modal for adding a new survey -->
	<div id="add_survey_modal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="dialog_header">Add a new survey to your view!</h4>
      </div>
      <div class="modal-body">
       <form id="add_survey_form" action="" method="post" role="form">
	   		<div class="form-group" id="form_add_survey">
		   		<h4>General Attributes</h4>
				<input type="text" style="margin-bottom: 2em; margin-top: 2em;" name="survey_name" id="survey_name" tabindex="1" class="form-control" placeholder="Name of the survey" value="">
				<input type="text" style="margin-bottom: 2em;" name="survey_topic" id="survey_topic" tabindex="2" class="form-control" placeholder="Survey category" value="">
				<input type="text" style="margin-bottom: 2em;" name="survey_link" id="survey_link" tabindex="3" class="form-control" placeholder="Link to the existing survey" value="">
				<h4>Measurement Category</h4>
				<table class="table" style="margin-top: 2em;">
					<tbody>
						<tr>
							<td style="vertical-align: middle;">Priming</td>
							<td style="text-align: right"><input id="toggle_primimg" checked type="checkbox" data-toggle="toggle"></td>
						</tr>
						<tr>
							<td style="vertical-align: middle;">Anchoring</td>
							<td style="text-align: right"><input id="toggle_anchoring" checked type="checkbox" data-toggle="toggle"></td>
						</tr>
						<tr>
							<td style="vertical-align: middle;">Don't know</td>
							<td style="text-align: right"><input id="toggle_know" checked type="checkbox" data-toggle="toggle"></td>
						</tr>
						<tr>
							<td style="vertical-align: middle;">Conflicting answers</td>
							<td style="text-align: right"><input id="toggle_conflict" checked type="checkbox" data-toggle="toggle"></td>
						</tr>
						<tr>
							<td style="vertical-align: middle;">Speeding</td>
							<td style="text-align: right"><input id="toggle_speeding" checked type="checkbox" data-toggle="toggle"></td>
						</tr>
						<tr>
							<td style="vertical-align: middle;">Straight lining</td>
							<td style="text-align: right"><input id="toggle_straight" checked type="checkbox" data-toggle="toggle"></td>
						</tr>
						<!--<tr>
							<td style="vertical-align: middle;">Patterns</td>
							<td style="text-align: right"><input id="toggle_patterns" checked type="checkbox" data-toggle="toggle"></td>
						</tr>-->
						<tr>
							<td style="vertical-align: middle;">Survey completion</td>
							<td style="text-align: right"><input id="toggle_completion" checked type="checkbox" data-toggle="toggle"></td>
						</tr>
					</tbody>
				</table>
				<h4>Upload Piwik File (JSON)</h4>
				<input type="file" style="margin-top: 2em;" id="piwik_file" accept=".json">
			</div>
			<div class="progress" id="div_add" style="display: none;">
				<div class="progress-bar" id="add_survey_bar" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0" style="width: 0%;"><span id="bar_label_add">0%</span></div>
			</div>
			<div class="alert alert-success alert-dismissable" id="hidden_dialog_add_survey" style="display: none;">
				<strong>Your new survey is now added!</strong>
			</div>
			<div class="alert alert-danger alert-dismissable" id="hidden_dialog_add_survey_error" style="display: none;">
				<strong>An error occurred adding your survey! Please try later!</strong>
			</div>
		</form>
      </div>
      <div class="modal-footer">
	  	<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<input type="button" name="addsurvey-submit" id="addsurvey" tabindex="2" class="form-control btn btn-login" value="Add Survey" disabled="disabled">
			</div>
		</div>
      </div>
    </div>

  	</div>
	</div>
	
	<!-- Modal for exporting the survey date -->
	<div id="export_survey_modal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="dialog_header">Export the current survey to CSV</h4>
      </div>
      <div class="modal-body">
       <form id="export_survey_form" action="" method="post" role="form">
	   		<div class="form-group" id="form_export_survey">
				<input type="text" name="file_name" id="file_name" tabindex="1" class="form-control" placeholder="File name for the CSV export" value="">
			</div>
			<div class="alert alert-success alert-dismissable" id="hidden_dialog_export_survey" style="display: none;">
				<strong>The survey is successfully exported!</strong>
			</div>
		</form>
      </div>
      <div class="modal-footer">
	  	<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<input type="button" name="exportsurvey-submit" id="exportsurvey" tabindex="2" class="form-control btn btn-login" value="Export Survey" disabled="disabled">
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
	<!-- Include the file saver js -->
	<script src="FileSaver.js"></script>
	<!-- Include Toogle Button -->
	<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    
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
	    
		var $alert = $("#refreshcompleted");
		
		// Get the actual email address of the logged in user
		var change_pwd_mail = "<?php echo $_SESSION['userid'] ?>";
		
		$("#reg_email").val(change_pwd_mail);
		
		// Regex for checking wether it is a correct e-mail address 
		var emailRegex = new RegExp(/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i);
			
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
		
		// Adding a new survey
		var new_survey_validator_name = new validator_object(false, false, "UNDEF");
		
		var new_survey_validator_category = new validator_object(false, false, "UNDEF");
		
		var file_selected = false;
		
		// Exporting the existing survey
		var export_survey_validator = new validator_object(false, false, "UNDEF");
		
		// Json file for adding a survey
		var jsonFile = null;
		
		// Checks whether the response is complete for caluclating the speeding value
		var response_completed = false;
		
		// Survey id for export
		var survey_id_export = -1;
		
		// Specifies the path of the property file
		var file ="property.txt";
		
		// Definition of the different answer options
		var first_answer_option = "";
		
		var last_answer_option = "";
		
		var dont_answer_option = "";
		
		var answer_count_option = "";
				
		/*****************************************/
		/***** END Variable DEFINITIONS ********/
		/*****************************************/		
		
		/*****************************************/
		/***** START Function DEFINITIONS ********/
		/*****************************************/
		
		// Closes the refresh modal
		function modal_close()
		{
			$progressBar.css('width', '0%');
			$("#hidden_dialog").attr("style","display:none;");		
		}
		
		// When clicking on the update survey button the avaiable surveys will be loaded from the database
		$('#updateSurveys').click(function() {
			fill_surveytable();
		});
    	
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
			if(new_survey_validator_name.login == true && new_survey_validator_category.login == true && file_selected == true)
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

		// Fills the survey table with the actual JSON data
    	function fill_surveytable()
		{
			// Getting all available surveys from the data base
			// for the current user
			dataString = 'user_mail_address='+change_pwd_mail;

			// Save the general attributes of the new survey into the database
			$.ajax({
				type:"post",
				url:"Show_Available_Surveys.php",
				dataTyp: "json",
				data: dataString,
				async: false,
				success: function(data) {
					
					// Fitst of all, removes the child nodes of the table 
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
						link_button = "";
						rows += "<tr id = allsurvey_row_" + this.id + "\ data-id = " + this.id + " ";
				
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
						
						if(this.survey_link == null)
						{
							link_button = "<button type=\"button\" id = \"link_"+ this.id + "\"" + 
						"class=\"btn btn-default disabled survey_link\" style=\"margin-left:3%;color: white;background-color: rgba(98, 96, 96, 0.93);\": value=" + this.survey_link + " data-toggle=\"tooltip\" title= \"Redirect to your survey.\" >"
						}
						else
						{
							link_button = "<button type=\"button\" id = \"link_"+ this.id + "\"" + 
						"class=\"btn btn-default survey_link\" style=\"margin-left:3%;color: white;background-color: rgba(232, 174, 28, 0.88);\": value=" + this.survey_link + " data-toggle=\"tooltip\" title= \"Redirect to your survey.\" >"
						}
					
				
						rows += "<td>" + this.id + "</td><td>" + this.name + "</td><td>" + this.topic + "</td>" + /*quality_score + "" + this.qualityscore + "%" + "</td>" +*/
						"<td id=\"action_buttons\"><button type=\"button\" id = \"details_"+ this.id + "\"" + "class=\"btn btn-default details\" data-toggle=\"tooltip\" title= \"Details for the survey.\" >" + "<span class=\"glyphicon glyphicon-info-sign\"></span>" + 
						"</button><button type=\"button\" data-tt=\"tooltip\" data-toggle=\"modal\" title=\"Export your survey data.\" id =\"export_" + this.id + "\"" + 
						"class=\"btn btn-default export\" data-target=\"#export_survey_modal\"><span class=\"glyphicon glyphicon-floppy-disk\"></span>" + link_button + "<span class=\"glyphicon glyphicon glyphicon-eye-open\"></span>" + 
						"</button><button type=\"button\" data-toggle=\"tooltip\" title=\"Delete your survey.\" id =\"delete_"+ this.id + "\" class=\"btn btn-default remove\"><span class=\"glyphicon glyphicon-trash\"></span></button></td></tr>";
				
				
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
		
		// Valides the upload file type as a json file
		function validFileType(file) 
		{
			var extension = file.value.substr(file.value.lastIndexOf('.'));
			
			if(extension === ".json")
			{
				return true;
			}
			
			return false;
		}
		
		// Calculates the amount of the given answers
		// for the actual question
		function get_answeramount(answers)
		{
			// no match found
			if(answers.match(/;/g) == null)
			{
				return 0;
			}
			
			return answers.match(/;/g).length;
		}
		
		// Calculates the priming factor for the given answer
		function calculate_priming(answers)
		{
			if(get_answeramount(answers) == 0)
			{
				return 0;
			}
			
			answer_count = get_answeramount(answers);
			answer_array = answers.split(';');
			priming_count = 0;
			
			// If there are to few answer possibilities no
			// recommentaion can be given 
			if(answer_count <= answer_count_option)
			{
				return 0;
			}
			
			for(i = 0; i < answer_array.length; i ++)
			{
				if(answer_array[i] === first_answer_option)
				{
					priming_count ++;
				}
			}
			
			return priming_count;
		}
		
		// Calculates how often the last answer option was selected
		function calculate_anchor(answers)
		{
			if(get_answeramount(answers) == 0)
			{
				return 0;
			}
			
			answer_array = answers.split(';');
			answer_count = get_answeramount(answers);
			anchor_count = 0;
			
			// If there are to few answer possibilities no
			// recommentaion can be given 
			if(answer_count <= answer_count_option)
			{
				return 0;
			}
			
			for(i = 0; i < answer_array.length; i ++)
			{
				if(answer_array[i] === last_answer_option)
				{
					anchor_count ++;
				}
			}
			
			return anchor_count;
		}
		
		// Calculates how often the dont know option was selected
		function calculate_dont(answers)
		{
			if(get_answeramount(answers) == 0)
			{
				return 0;
			}
			
			answer_array = answers.split(';');
			answer_count = get_answeramount(answers);
			priming_count = 0;
			
			// If there are to few answer possibilities no
			// recommentaion can be given 
			if(answer_count <= answer_count_option)
			{
				return 0;
			}
			
			for(i = 0; i < answer_array.length; i ++)
			{
				if(answer_array[i] === dont_answer_option)
				{
					priming_count ++;
				}
			}
			
			return priming_count;
		}

		
		
		// Calculates the amount of straight given answers
		function calculate_straight(answers)
		{
			if(get_answeramount(answers) == 0)
			{
				return 0;
			}
			
			answer_array = answers.split(';');
			answer_count = get_answeramount(answers);
			previous_answer = "";
			
			// Stores the maximum straight into the counter
			streight_counter = 0;
			streight_counter_max = streight_counter;
						
			// If there are to few answer possibilities no
			// recommentaion can be given 
			if(answer_count <= answer_count_option)
			{
				return 0;
			}
			
			for(i = 0; i < answer_array.length; i ++)
			{
				if(previous_answer !== "")
				{
					if(answer_array[i] === previous_answer)
					{
						streight_counter++;
					}
					else
					{
						if(streight_counter >  streight_counter_max)
						{
							streight_counter_max = streight_counter;
						}
					
						streight_counter = 0;
					}
				}
				
				previous_answer = answer_array[i];
				
			}
			
			return streight_counter_max;
		}
		
		function calculate_conflict(answers)
		{
			return 0;
		}
		
		// Draws the add progress bar
		function drawprogressbar_add(maxsize, current_size)
		{	
			
			// FURTHER WORK HERE
			
			progress_width = Math.round((current_size/maxsize)*100);
		
			
			//alert(progress_width);
			
			//$("#add_survey_bar").attr("style", "width: " + progress_width + "%;");
			//$("#add_survey_bar").attr("aria-valuenow", progress_width);
		}
		
		// Saves the general attributes of the online survey into the data base
		function insert_Survey(survey_name,survey_topic,survey_link)
		{
			user_mail_address = change_pwd_mail;
			survey_id = 0;
			
			dataString = 'survey_name='+survey_name+'&survey_topic='+survey_topic+'&survey_link='+survey_link+"&user_mail_address="+user_mail_address;

			// Save the general attributes of the new survey into the database
			$.ajax({
				type:"post",
				url:"Insert_Survey.php",
				data: dataString,
				async: false,
				success: function(data) {
					
					survey_id = data;
					// If there was a sucess entering the new survey all
					// responses will be entered 
					insert_Responses(data);
				},
				error:function () {
					show_error_adding_survey();
				}				
			});			
		}
		
		// Enters the responses of the current survey
		function insert_Responses(survey_id)
		{
			var reader = new FileReader();

		 	reader.onload = function(event) 
		 	{
		 		var jsonObj = JSON.parse(event.target.result);
		 		dataset_length = jsonObj.length;
		 		current_data_set = 0;
		 		var response_id = 0;
		 		
		 		// Iterate through all available responses
		 		$.each(jsonObj, function(){
			 		
			 		// Add a response
			 		dataString = 'survey_id='+survey_id; 
			 		// Save the general attributes of the new survey into the database
			 		$.ajax({
			 			type:"post",
			 			url:"Insert_Responses.php",
			 			async: false,
			 			data: dataString,
			 			success: function(data) {
					
			 			// The response was succesfull inserted
			 			// next all questions will be added
			 			
			 			response_id = data;
						},
						error:function () {
							show_error_adding_survey();
						}
					});	
					
					// Saves the questions into the data base
					// and in addition calculates all attributes exept the speeding
					insert_Questions(response_id, this);
					
					dataStringTime = "";
					
					// Check whether speeding should be calculated or not
					if($("#toggle_speeding").prop('checked') && response_completed == true)
	 				{		
		 				dataStringTime = 'response_id='+response_id+'&speeding='+'yes';
					}
					else
					{
						dataStringTime = 'response_id='+response_id+'&speeding='+'no';
					}
					
					response_completed = false;
					
					// Speeding will be calculated
		 			$.ajax({
			 			type:"post",
			 			url:"Save_time.php",
			 			async: false,
			 			data: dataStringTime,
			 			success: function(data) {	
						},
						error:function () {
							show_error_adding_survey();
						}
					});
		 			
		 			current_data_set++;
		 			
		 			// Draws the progress bar
				 	drawprogressbar_add(dataset_length, current_data_set);
		 			
		 			if(dataset_length == current_data_set)
		 			{
			 			// The response was succesfull inserted
			 			// next all questions will be added
			
			 			dataStringSurveyTime = 'survey_id='+survey_id;
		 	
			 			// Calculate the median of the speeding attribute
			 			$.ajax({
				 			type:"post",
				 			url:"Save_time_survey.php",
				 			async: false,
				 			data: dataStringSurveyTime,
				 			success: function(data) {
				 				// All data was loaded into the data base
				 				setTimeout(function() 
				 				{
				 					$("#hidden_dialog_add_survey").removeAttr("style");
				 					setTimeout(function() 
				 					{
				 						$("#add_survey_form").submit();
									},3000);
								},1500);
							},
							error:function () {
								show_error_adding_survey();
							}
						});
		 			}
		 		});
		 	}		 	
		 	reader.readAsText(jsonFile);			
		}
		
		// Error occurred during adding the survey
		function show_error_adding_survey()
		{
			setTimeout(function() 
			{
				$("#hidden_dialog_add_survey_error").removeAttr("style");
				setTimeout(function() 
				{
				 	$("#add_survey_form").submit();
				},3000);
				},1500);
		}
		
		// Inserts a question
		function insert_Questions(response_id, json_object)
		{	
			// Messuring attributes
			priming = 0;
			straight = 0;
			don_t = 0;
			anchor = 0;
			conflict = 0;
			pattern = 0;
			// Will be calulcated later on when all data is available
			speed = -1;
			incomplete = 100;
			
			// Amount of all answer options for all questions
			answer_options = 0;
			
			checker = 0;
			
			$.each(json_object.actionDetails, function()
		 	{	
			 	if(this.eventCategory === "Answering")
			 	{	
				 	// For the questions of the response
				 	dataString = 'question='+this.eventAction+'&answer='+this.eventName +'&time='+this.timeSpent+'&response_id='+response_id;
				 	
				 	// Save the response data into the data base
				 	$.ajax({
				 		type:"post",
				 		url:"Insert_Answers.php",
				 		data: dataString,
				 		async: false,
				 		success: function(data) {
					
				 			// The response was succesfull inserted
				 			// next all questions will be added
				 			//insert_questions(data);
				 			
						},
						error:function () {
								alert("error");
							}
						});

						// If the option is selected, than the attribute will be calculated
						// else -1 will indicate that there was no selection
				 		if($("#toggle_primimg").prop('checked'))
				 		{
					 		priming = priming + calculate_priming(this.eventName);
				 		}
				 		else
				 		{
					 		priming = -1;
				 		}
				 		
				 		if($("#toggle_anchoring").prop('checked'))
				 		{
					 		anchor = anchor + calculate_anchor(this.eventName);
				 		}
				 		else
				 		{
					 		anchor = -1;
				 		}
				 		
				 		if($("#toggle_know").prop('checked'))
				 		{
					 		don_t = don_t + calculate_dont(this.eventName);
				 		}
				 		else
				 		{
					 		don_t = -1;
				 		}
				 		
				 		if($("#toggle_conflict").prop('checked'))
				 		{
					 		conflict = conflict + calculate_conflict(this.eventName);
				 		}
				 		else
				 		{
					 		conflict = -1;
				 		}
				 		
				 		if($("#toggle_straight").prop('checked'))
				 		{
					 		straight = straight + calculate_straight(this.eventName);
				 		}
				 		else
				 		{
					 		straight = -1;
				 		}
				 		
				 		if($("#toggle_patterns").prop('checked'))
				 		{
					 		pattern = pattern + calculate_pattern(this.eventName);
				 		}
				 		else
				 		{
					 		pattern = -1;
				 		}
				 		
				 		answer_options = answer_options + get_answeramount(this.eventName);
				 	}
				 	
				 	// Toggle switch for completion detection is selected to on
				 	if($("#toggle_completion").prop('checked'))
				 	{
						if(this.eventCategory === "Survey Completion")
					 	{
				 			if(this.eventAction === "Complete")
				 			{
					 			// If the survey is completed 100 percent will be assigned
					 			if(this.eventName === "TRUE")
					 			{
					 				incomplete = 0;
					 				response_completed = true;
					 			}
				 			}
			 			}
				 	}
				 	else
				 	{
					 	incomplete = -1;
				 	}
				 	
				 	checker ++;				 			
		 	});
		 	
		 	// If all possible sets has looped through the response attributes will be added into the data base
		 	if(checker === json_object.actionDetails.length)
		 	{
		 		// Data String for saving the attributes
		 		dataString_Attributes = 'response_id='+response_id+'&priming='+calculate_percentage_attributes(answer_options,priming) +'&anchor='+calculate_percentage_attributes(answer_options,anchor)+'&don_t='+calculate_percentage_attributes(answer_options,don_t)+'&conflict='+calculate_percentage_attributes(answer_options,conflict)+'&straight='+calculate_percentage_attributes(answer_options,straight)+'&pattern='+calculate_percentage_attributes(answer_options,pattern)+'&complete='+incomplete;
				 	
		 		// Send this calculation data to the php page
		 		$.ajax({
					 type:"post",
					 url:"Insert_Attributes.php",
					 data: dataString_Attributes,
					 async: false,
					 success: function(data) {
					},
					error:function () {
						alert("error saving the respons attributes");
					}
				});
			}
		}
		
		// Calculates the percentage value for each attribute
		function calculate_percentage_attributes(answer_options, attribute)
		{
			if((answer_options < attribute) || (answer_options == 0) || (attribute == -1))
			{
				return -1;
		 	}
		 	
			return Math.round(((attribute / answer_options) * 100));
		}
		
		// Reads the property file for the current thresholds for calculation
		function readPropertyFile(file)
		{
		    var rawFile = new XMLHttpRequest();
		    rawFile.open("GET", file, false);
		    rawFile.onreadystatechange = function ()
		    {
		        if(rawFile.readyState === 4)
		        {
		            if(rawFile.status === 200 || rawFile.status == 0)
		            {
		                var allText = rawFile.responseText;
		                
		                // Get the different properties from the text file
		                survey_options = allText.split(";");
		                
		                // Set the parameter for the first answer option
		                first_answer_option =  survey_options[0].split("=")[1];
		                
		                // Set the parameter for the last answer option
		                last_answer_option =  survey_options[1].split("=")[1];
		                
		                // Set the paremter for the dont know option
		                dont_answer_option =  survey_options[2].split("=")[1];
		                
		                // Set the parameter for the number of answers
		                answer_count_option = survey_options[3].split("=")[1];   
		                
		            }
		            if(rawFile.status === 404)
		            {
			            // Set default values
			            first_answer_option = "A1";
			            last_answer_option = "A4";
			            dont_answer_option = "A5";
			            
			            answer_count_option = 3;
		            }
		        }
		    }
			rawFile.send(null);
		}
	    		    
	    /*****************************************/
		/***** END Function DEFINITIONS ********/
		/*****************************************/
			
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
		/******* START ADD SURVEY FORM ***********/
		/*****************************************/
		
		$("#survey_name").keyup(function() {
			
			new_survey = $("#survey_name").val();
				
			// The url is a url regex
			if(new_survey.length > 0 && new_survey.length < 30) 
			{	
				if(new_survey_validator_name.previousState == "UNDEF" || new_survey_validator_name.previousState == "ERROR")
				{
						
					formelementOK("#survey_name", new_survey_validator_name, "#add_survey_form");
				}
					
			}
			else
			{
				// Diference between the register password and the confirmation password
				if(new_survey_validator_name.previousState == "UNDEF" || new_survey_validator_name.previousState == "OK")
				{
					
					formelementNOTOK("#survey_name", new_survey_validator_name, "#add_survey_form");
				}
			}
				
			activateAddSurvey();
		});
		
		$("#survey_topic").keyup(function() {
			
			new_survey = $("#survey_topic").val();
				
			// The url is a url regex
			if(new_survey.length > 0 && new_survey.length < 30) 
			{	
				if(new_survey_validator_category.previousState == "UNDEF" || new_survey_validator_category.previousState == "ERROR")
				{
						
					formelementOK("#survey_topic", new_survey_validator_category, "#add_survey_form");
				}
					
			}
			else
			{
				// Diference between the register password and the confirmation password
				if(new_survey_validator_category.previousState == "UNDEF" || new_survey_validator_category.previousState == "OK")
				{
					
					formelementNOTOK("#survey_topic", new_survey_validator_category, "#add_survey_form");
				}
			}
				
			activateAddSurvey();
		});
		
		// Checks whether a file has been selected and writes the selected file name into the dialog
		$("input:file").change(function (event)
		{
			jsonFile = event.target.files[0];
			var fileName = $(this).val();
			$(".filename").html(fileName);
			
			// Checks whether the actual file is a json file
			if(validFileType(this))
			{
				file_selected = true;
			}
			else
			{
				file_selected = false;
			}
			
			activateAddSurvey();
     	});
     	
     	// Clicking on the add survey button
     	$("#addsurvey").on("click", function ()
     	{
	     	$("#div_add").removeAttr("style");
			$("#addsurvey").attr("disabled","disabled");
			
			readPropertyFile(file);
			
			insert_Survey($("#survey_name").val(),$("#survey_topic").val(),$("#survey_link").val());
			
  		});
  		  		
		/*****************************************/
		/******* END ADD SURVEY FORM *************/
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
		
		// The change in the password will be submitted
		$("#changepassword").click(function(){
			
			$("#hidden_dialog_pwd").removeAttr("style");
				
			setTimeout(function() {
				$("#changepassword-form").submit();
			},2000);
		});
		
		/********************************************/
		/****** DEFINITION OF ACTION BUTTONS ********/
		/********************************************/
		
		// Add click handler for the details view
		$(document).on("click", ".details", function(){
			
			survey_id_redirect = $(this).attr("id").substring($(this).attr("id").indexOf("_") + 1,$(this).attr("id").length);
			
			window.location.href = "overview.php?survey_id=" + survey_id_redirect;
		});
		
		// Add click handler for the survey link
		$(document).on("click", ".survey_link", function(){
			
			external_link = $(this).val();
			
			window.open(external_link, "_blank");
			
		});
		
		// Add click handler for deleting the survey including all answers and responses
		$(document).on("click", "button.remove", function(){
			
			survey_id_delete = $(this).attr("id").substring($(this).attr("id").indexOf("_") + 1,$(this).attr("id").length);
			
			dataString = 'survey_id=' + survey_id_delete;
			
			$.ajax({
				 	type:"post",
				 	url:"Delete_Survey.php",
				 	data: dataString,
				 	async: false,
				 	success: function(data) {
					 	
					 	if(data === "true")
					 	{
					 		show_dialog_success("Survey was succesfully removed from the service");
					 		
					 		fill_surveytable();
					 	}
					 	else
					 	{
						 	alert(data);
						 	show_dialog_error("An error occured removing the survey from the service");
					 	}
				 			
					},
					error:function () {
							show_dialog_error("An error occured removing the survey from the service");
						}
			});
			
		});
		
		// Click on the export action button, thereafter the id will be saved
		$(document).on("click", "button.export", function(){
			
			survey_id_export = $(this).attr("id").substring($(this).attr("id").indexOf("_") + 1,$(this).attr("id").length);	
		});
		
		// For the export all needed data was entered so the selected survey could be exported
		$("#exportsurvey").click(function(){
		
			$("#file_name").attr("disabled","disabled");
			
			save_file_name = $("#file_name").val();
			
			$("#div_export").removeAttr("style");
			$("#exportsurvey").attr("disabled","disabled");
			
			// Data String for saving the attributes
		 	dataString_Export = 'survey_id='+survey_id_export;
		 					 	
		 	// Send this calculation data to the php page
		 	$.ajax({
					type:"post",
					url:"ExportSurvey.php",
					data: dataString_Export,
					async: false,
					success: function(data) {
						
						json_object = JSON.parse(data);
						json_object_responses = JSON.parse(data);
						var question_headline = [];
						question_available = false;
						headline = "Response Id, Incomplete, Speeding, Straightlining, Left edge straightlining, Right edge straightlining, Don't know answers, Conflicting answers, ";
						responses = "";
						
						// Get the question number and also the questions for the current survey
						$.each(json_object, function(){
						
							if((this.incomplete == 0) && (question_available == false))
							{
								for(run = 0; run < this.questions.length; run ++)
								{
									// Add quotes to the intial part if a semi colon is present in the text
									question_headline.push("\"" + this.questions[run].question + "\"");
								}
								
								question_available = true;
							}
						});
						
						// Connect the headline together
						for(run = 0; run < question_headline.length; run ++)
						{
							if((run + 1) < question_headline.length)
							{
								headline = headline + question_headline[run] + ", ";
							}
							else
							{
								headline = headline + question_headline[run] + "\n";
							}
						}
						
						// Build the answering set
						$.each(json_object_responses, function(){
						
							// Get the different attributes and convert it in accordance to the response details view
							
							// First incomplete
							if(this.incomplete > 0)
							{
								this.incomplete = "Yes";
							}
							else if (this.incomplete == -1)
							{
								this.incompleze = "N/A";
							}
							else
							{
								this.incomplete = "No";
							}
							
							if(this.speeding == -1)
							{
								this.speeding = "N/A";
							}
							else
							{
								this.speeding = this.speeding/100;
							}
							
							first_response = this.id + ", " + this.incomplete + ", " + this.speeding + ", " + behaviour_detected(this.straight) + ", " + behaviour_detected(this.priming) + ", " + behaviour_detected(this.anchoring) + ", " + behaviour_detected(this.dont) + ", " + behaviour_detected(this.conflict) + ", ";
							
							for(run = 0; run < this.questions.length; run ++)
							{
								if((run + 1) < question_headline.length)
								{
									// Add quotes to the intial part if a semi colon is present in the text
									first_response = first_response + "\"" + this.questions[run].answer + "\"" + ", ";
								}
								else
								{
									first_response = first_response + "\"" + this.questions[run].answer + "\"";
								}
							}
							
							// Add the response to the answer string
							responses = responses + first_response + "\n";
						});
						
						var blob = new Blob([headline,responses], {type: "text/plain;charset=utf-8"});
						
						saveAs(blob, save_file_name+".csv");
						
						$("#hidden_dialog_export_survey").removeAttr("style");
						
						setTimeout(function() 
						{
							$("#export_survey_form").submit();
						},1500);
												
					},
					error:function () {
						alert("error saving the respons attributes");
					}
			});
		});		

		function behaviour_detected(value)
		{
			if(value > 0)
			{
				return "detected";
			}
		
			if(value == -1)
			{
				return "N/A";
			}
			
			return "not detected";
		}
		
		// Checks whether the file name for the exported survey is larger than 1
		$("#file_name").keyup(function() {
			
			export_survey = $("#file_name").val();
				
			// The url is a url regex
			if(export_survey.length > 0) 
			{	
				if(export_survey_validator.previousState == "UNDEF" || export_survey_validator.previousState == "ERROR")
				{
						
					formelementOK("#file_name", export_survey_validator, "#export_survey_form");
				}
					
			}
			else
			{
				// Diference between the register password and the confirmation password
				if(export_survey_validator.previousState == "UNDEF" || export_survey_validator.previousState == "OK")
				{
					
					formelementNOTOK("#file_name", export_survey_validator, "#export_survey_form");
				}
			}
				
			activateExportSurvey();
		});
				
		fill_surveytable();
		
		// Tooltip for the GUI elements
		$('[data-toggle="tooltip"]').tooltip(); 
		$('[data-tt="tooltip"]').tooltip(); 
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