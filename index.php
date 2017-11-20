<!DOCTYPE html>
<!-- Author: Stefan Biegler -->
<!-- Page: Log in and register page -->
<!-- Version: 1.0 -->

<?php 
session_start();

// Config file of the data base
require_once ('config.php');

// PHP Mailer
require("PHPMailer/PHPMailerAutoload.php");


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
    <title>Welcome Page</title>

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
    
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="bootstrap/js/bootstrap.min.js"></script>
    
    <!-- JScript for the login page -->
    <script src="/JScript/Login.js"></script>
    
  </head>
  <body id ="body">	
	<div>  
		<div class="alert alert-success alert-dismissable" id="dialog_succes" style="display: none;">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<strong class = "success"></strong>
		</div>
									
		<div class="alert alert-danger alert-dismissable" id="dialog_error" style="display: none;">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<strong class = "error"></strong>
		</div>
	</div>  
	  		
    <h1 class="text-center" id = "headline">Survey Analysis</h1>
    
    <!-- Parts are based on a template from https:\//bootsnipp.com/snippets/featured/login-and-register-tabbed-form -->
    <div class="container">
    	<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="panel panel-login">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-6">
								<a href="#" class="active" id="login-form-link">Login</a>
							</div>
							<div class="col-xs-6">
								<a href="#" id="register-form-link">Register</a>
							</div>
						</div>
						<hr>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-lg-12">
								<form id="login-form" action="?login=1" method="post" role="form" style="display: block;">
									<div class="form-group" id="form_user">
										<input type="text" name="username" id="username" tabindex="1" class="form-control" placeholder="E-Mail" value="" data-toggle="tooltip" title="Enter your E-Mail Address!">
									</div>
									<div class="form-group" id="form_pwd">
										<input type="password" name="password" id="password" tabindex="2" class="form-control" placeholder="Password" data-toggle="tooltip" title="Enter your password minimum length of 9!">
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-sm-6 col-sm-offset-3">
												<input type="submit" name="login-submit" id="login-submit" tabindex="3" class="form-control btn btn-login" value="Log In" disabled="disabled">
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-lg-12">
												<div class="text-center">
													<a href="" data-toggle="modal" data-target="#forgottenpwdModel" tabindex="5" class="forgot-password">Forgot Password?</a>
												</div>
											</div>
										</div>
									</div>
								</form>
								<form id="register-form" action="?register=1" method="post" role="form" style="display: none;">
									<div class="form-group" id="form_reg_email">
										<input type="email" name="reg_email" id="reg_email" tabindex="1" class="form-control" placeholder="Email Address" value="">
									</div>
									<div class="form-group" id="form_reg_pwd">
										<input type="password" name="reg_password" id="reg_password" tabindex="2" class="form-control" placeholder="Password" data-toggle="tooltip" title="Enter your password minimum length of 9!">
									</div>
									<div class="form-group" id="form_reg_pwd_confirm">
										<input type="password" name="reg_confirm_password" id="reg_confirm_password" tabindex="3" class="form-control" placeholder="Confirm Password" disabled="disabled">
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-sm-6 col-sm-offset-3">
												<input type="button" name="register-submit" id="register-submit" tabindex="4" class="form-control btn btn-login" value="Register" disabled="disabled">
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Modal for the forgotten password option-->
	<div id="forgottenpwdModel" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="dialog_header">Forgotten the password ?</h4>
      </div>
      <div class="modal-body">
        <form method="post" role="form" action="?forgotten=1">
	        <div class="form-group" id="forgotten_pwd_form">
				<input type="text" class="form-control" name="forgotten_pwd" id="forgotten_pwd" tabindex="1" placeholder="E-Mail" value="">
  			</div>
      </div>
      <div class="modal-footer">
	  	<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<input type="submit" name="forgottenpwd-submit" id="forgottenpwd" tabindex="2" class="form-control btn btn-login" value="Get Password" data-toggle="tooltip" title="Get a new password for logging in!" disabled="disbaled">
			</div>
		</div>
      </div>
      </form>
    </div>

  	</div>
	</div>
    
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
	    				
			// If the log in was successful redirect to the survey overview
			function login_success()
			{
				window.location = "/surveypage.php";
			}
	    
    	$(document).ready(function () {
		
			/*****************************************/
			/***** START Variable DEFINITIONS ********/
			/*****************************************/

			// Regex for checking wether it is a correct e-mail address 
			var emailRegex = new RegExp(/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i);
			
			var login_disabled = true;
			
			var forgotten_pwd_disabled = true;
			
			var register_disabled = true;
			
			function validator_object(first, login, previousState) {
				// The first check no span has to be removed and no class
				this.first = first;
				// If the login is true the login button will be activated
				this.login = login;
				// The last state is undefined
				this.previousState = previousState;
			}
			
			// UserName object
			var username = new validator_object(false, false, "UNDEF");
			
			// Password object
			var password = new validator_object(false, false, "UNDEF");
			
			// Forgotten password object
			var forgotten_pwd = new validator_object(false, false, "UNDEF");
			
			// Register E-Mail
			var email = new validator_object(false, false, "UNDEF");
			
			// Register Password
			var reg_password = new validator_object(false, false, "UNDEF");
			
			// Confirmation of the register password
			var reg_password_confirm = new validator_object(false, false, "UNDEF");
			
			/*****************************************/
			/******* END Variable DEFINITIONS ********/
			/*****************************************/
						
			/*****************************************/
			/***** START FUNCTION DEFINITIONS ********/
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
			function activateLogin()
			{
				if(username.login == true && password.login == true)
				{
					$("#login-submit").removeAttr("disabled");
					login_disabled = false;
				}
				else
				{
					if(login_disabled == false)
					{
						$("#login-submit").attr("disabled","disabled");
						login_disabled = true;
					}
				}
			}
			
			// Activates the forgotten password button
			function activateForgottenPwd()
			{
				if(forgotten_pwd.login == true)
				{
					$("#forgottenpwd").removeAttr("disabled");
					forgotten_pwd_disabled = false;
				}
				else
				{
					if(forgotten_pwd_disabled == false)
					{
						$("#forgottenpwd").attr("disabled","disabled");
						forgotten_pwd_disabled = true;
					}
				}
			}
			
			// Activates the register button
			function activateRegisterButton()
			{
				if(email.login == true && reg_password.login == true && reg_password_confirm.login == true)
				{
					$("#register-submit").removeAttr("disabled");
					register_disabled = false;
				}
				else
				{
					if(register_disabled == false)
					{
						$("#register-submit").attr("disabled","disabled");
						register_disabled = true;
					}
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
			
			/*****************************************/
			/******* END FUNCTION DEFINITIONS ********/
			/*****************************************/	
			
			/*****************************************/
			/******* START REGISTER FORM *************/
			/*****************************************/
			
			// E-Mail address for the register process
			$("#reg_email").keyup(function() {
			
				mail_val = $("#reg_email").val();
				
				// Validate the username against a normal E-Mail layout
				// and that the maximum size is 40
				if(emailRegex.test(mail_val) && mail_val.length < 40 )
				{	
					if(email.previousState == "UNDEF" || email.previousState == "ERROR")
					{
						
						formelementOK("#reg_email", email, "#form_reg_email")
					}
					
				}
				else
				{
					// Incorrect Mail Address
					
					if(email.previousState == "UNDEF" || email.previousState == "OK")
					{
					
						formelementNOTOK("#reg_email", email, "#form_reg_email");
					}
				}
				
				activateRegisterButton();
			});
			
			// Password for the register process
			$("#reg_password").keyup(function() {
			
				// If the original password will be changed the confirmation password
				// will be deleted
				$("#reg_confirm_password").val("");
				formelementNOTOK("#reg_confirm_password", reg_password_confirm, "#form_reg_pwd_confirm");
			
				reg_password_val = $("#reg_password").val();
				
				// Minimum Length is 9 and maximum length is 40
				if((reg_password_val.length > 8) && (reg_password_val.length < 40)) 
				{	
					if(reg_password.previousState == "UNDEF" || reg_password.previousState == "ERROR")
					{
						
						formelementOK("#reg_password", reg_password, "#form_reg_pwd")
						
						// A correct password, activate confirmation password
						$("#reg_confirm_password").removeAttr("disabled");
					}
					
				}
				else
				{
					// Incorrect password
					
					// Deactivate the confirmation password
					$("#reg_confirm_password").attr("disabled","disabled");
					$("#reg_confirm_password").val("");
					
					if(reg_password.previousState == "UNDEF" || reg_password.previousState == "OK")
					{
					
						formelementNOTOK("#reg_password", reg_password, "#form_reg_pwd");
					}
				}
				
				activateRegisterButton();
			});
			
			// Confirmation Password for the register process
			$("#reg_confirm_password").keyup(function() {
			
				reg_password_conf_val = $("#reg_confirm_password").val();
				
				// The same password in the confirm section
				if(reg_password_conf_val == $("#reg_password").val()) 
				{	
					if(reg_password_confirm.previousState == "UNDEF" || reg_password_confirm.previousState == "ERROR")
					{
						
						formelementOK("#reg_confirm_password", reg_password_confirm, "#form_reg_pwd_confirm")
					}
					
				}
				else
				{
					// Diference between the register password and the confirmation password
					if(reg_password_confirm.previousState == "UNDEF" || reg_password_confirm.previousState == "OK")
					{
					
						formelementNOTOK("#reg_confirm_password", reg_password_confirm, "#form_reg_pwd_confirm");
					}
				}
				
				activateRegisterButton();
			});
			
			/*****************************************/
			/******* END REGISTER FORM *************/
			/*****************************************/
			
			/*****************************************/
			/***** START FORGOTTEN PWD FORM **********/
			/*****************************************/
			
			// If the forgotten password mail address is entered the validate will be open
			$("#forgotten_pwd").keyup(function() {
			
				forgotten_pwd_val = $("#forgotten_pwd").val();
				
				// Validate the username against a normal E-Mail layout
				if(emailRegex.test(forgotten_pwd_val))
				{	
					if(forgotten_pwd.previousState == "UNDEF" || forgotten_pwd.previousState == "ERROR")
					{
						
						formelementOK("#forgotten_pwd", forgotten_pwd, "#forgotten_pwd_form")
					}
					
				}
				else
				{
					// Incorrect Mail Address
					
					if(forgotten_pwd.previousState == "UNDEF" || forgotten_pwd.previousState == "OK")
					{
					
						formelementNOTOK("#forgotten_pwd", forgotten_pwd, "#forgotten_pwd_form");
					}
				}
				
				activateForgottenPwd();
			});
			
			/*****************************************/
			/***** END FORGOTTEN PWD FORM **********/
			/*****************************************/
			
			/*****************************************/
			/********** START LOGIN FORM *************/
			/*****************************************/

			// If the username is entered the validate will be open
			$("#username").keyup(function() {
			
				username_val = $("#username").val();
				
				// Validate the username against a normal E-Mail layout
				if(emailRegex.test(username_val))
				{	
					if(username.previousState == "UNDEF" || username.previousState == "ERROR")
					{
						
						formelementOK("#username", username, "#form_user")
					}
					
				}
				else
				{
					// Incorrect Mail Address
					
					if(username.previousState == "UNDEF" || username.previousState == "OK")
					{
					
						formelementNOTOK("#username", username, "#form_user");
					}
				}
				
				activateLogin();
			});
			
			// If the password is entered the validate will be open
			$("#password").keyup(function() {
			
				password_val = $("#password").val();
				
				// Validate password againts a length of 9
				if(password_val.length > 8)
				{	
					if(password.previousState == "UNDEF" || password.previousState == "ERROR")
					{
						formelementOK("#password", password, "#form_pwd");
					}
					
				}
				else
				{
					// Incorrect length of the password
					
					if(password.previousState == "UNDEF" || password.previousState == "OK")
					{
					
						formelementNOTOK("#password", password, "#form_pwd");
					}
				}
				
				activateLogin();
			});
			
			/*****************************************/
			/********** END LOGIN FORM *************/
			/*****************************************/
			
			// Submits the register form
			$("#register-submit").click(function(){
				$("#register-form").submit();
			});

			});
	</script>
	
	<?php
		
		error_reporting(E_ALL);
		
		// Sends an email with a given text to a mail address
		// If everything has worked fine true will be returned else
		// false will be returned
		function send_Mail($user_mail_adress, $subject, $body)
		{
			$mail = new PHPMailer(true);

			$mail->isSMTP();
			$mail->SMTPDebug = 0;
			$mail->Debugoutput = 'html';
			$mail->Host = "smtp.world4you.com";
			$mail->Port = 587;
			$mail->SMTPSecure = 'tls';
			$mail->SMTPAuth = true;
			$mail->Username = "survey_analysis@onlinesurvey.co.at";
			$mail->Password = "moedling3";
			$mail->setFrom('survey_analysis@onlinesurvey.co.at', 'Survey Analysis Admin');
			$mail->addAddress($user_mail_adress, 'To');
			$mail->isHTML(true); 

			$mail->Subject = $subject;
			$mail->Body    = $body;

			if (!$mail->send()) 
			{
				return false;	
			}
			
			return true;
		}
		
		// Creates a random password of the given characters
		// $passnumber defines how long the length of the password
		// should be
		function GeraHash($passnumber)
		{ 
			//Definition of the character set 
			$Caracteres = 'ABCDEFGHIJKLMOPQRSTUVXWYZ0123456789!?%&)(=/'; 
			$QuantidadeCaracteres = strlen($Caracteres); 
			$QuantidadeCaracteres--; 

			$Hash=NULL; 
			for($x=1;$x<=$passnumber;$x++)
			{ 
				$Posicao = rand(0,$QuantidadeCaracteres); 
				$Hash .= substr($Caracteres,$Posicao,1); 
    		}
			return $Hash; 
		} 
				
		// Inserts a new user into the data base
		if(isset($_GET['register'])) 
		{			
			$error = false;
			$email = $_POST['reg_email'];
			$password = $_POST['reg_password'];
			$password2 = $_POST['reg_confirm_password'];
			 
			// Checks whether the actual mail adress isn't already in the data base
			$statement = $db_link->prepare("SELECT * FROM User WHERE mail = ?");
			$statement->bind_param("s", $email);
			$statement->execute();
			$user = $statement->fetch();
			 
			if($user != false) 
			{
				echo "<script type=\"text/javascript\">show_dialog_error('E-Mail address already registered!');</script>";
				$error = true;
			} 
			 
			//No error occured so the user can be registered
			if(!$error) 
			{ 
				$password_hash = password_hash($password, PASSWORD_DEFAULT);
			 
				$statement = $db_link->prepare("INSERT INTO User (mail, pwd) VALUES (?,?)");
				$statement->bind_param("ss", $email, $password_hash);
				$result = $statement->execute();
			 
				// Successfuly registered the user
				if($result) 
				{ 
					$subject_mail = "Complete Registration Process for the Survey Analysis";
					$body_mail = "Complete the registration process by clicking on this www.onlinesurvey.co.at/registration.php?user=" . $email . "";
					
					if(send_Mail($email, $subject_mail, $body_mail) == false)
					{
						echo "<script type=\"text/javascript\">show_dialog_error('An error occurred sending a confirmation mail. Please try another mail address!');</script>";
					} 
					else 
					{
						echo "<script type=\"text/javascript\">show_dialog_success('You are successfully registered! Please check your mail account!');</script>";
					}
			 	} 
			 	else 
			 	{
			 		echo "<script type=\"text/javascript\">show_dialog_error('An error occurred saving your data please try again later!');</script>";
			 	}
			} 
			
			$statement->close();
		}
		
		// A user wants to log in therefore check the credentials
		if(isset($_GET['login'])) 
		{		
			$email = $_POST['username'];
			$password = $_POST['password'];
			
			// Checks the stored credentials against the user inserted ones
			$statement = $db_link->prepare("SELECT pwd, mail, activated FROM User WHERE mail = ?");
			$statement->bind_param("s", $email);
			$statement->execute();
			
			$statement->bind_result($db_pwd, $db_mail, $db_activated);
			
			while ($statement->fetch())
			{			
				if(password_verify($password,$db_pwd))
				{
					$_SESSION['userid'] = $email;
					echo "<script type=\"text/javascript\">login_success();</script>";
				}
				else
				{
					if($db_activated == 1)
					{
						echo "<script type=\"text/javascript\">show_dialog_error('Wrong password or user name!');</script>";
					}
					else // account is not activated
					{
						echo "<script type=\"text/javascript\">show_dialog_error('The account is not activated! Please check your mails!');</script>";
					}
				}
    		}
    		
    		$statement->close();
		}
		
		// If a user has forgotten his/her password the user can reset it with the help of this function
		if(isset($_GET['forgotten']))
		{
			$randompassword = GeraHash(9);
			$forgotten_mail = $_POST['forgotten_pwd'];
			
			// Save the new random password into the data base
			$newpassword_hash = password_hash($randompassword, PASSWORD_DEFAULT);
			
			// Checks whether a connection is possible 
			if ( !$db_link )
			{
				// Error message when a connection is not possible
				die('keine Verbindung möglich: ');
			}
			
			// Checks whether the actual mail adress isn't already in the data base
			$statement = $db_link->prepare("UPDATE User SET pwd = ? WHERE mail = ?");
			$statement->bind_param("ss", $newpassword_hash, $forgotten_mail);
	
			$statement->execute();
	
			if (!($statement->errno)) 
			{
				// If the mail address is not registered in the database no mail will be sent
				if($statement->affected_rows > 0)
				{
					echo "<script type=\"text/javascript\">show_dialog_success('Password was restored please check your mail account!');</script>";
				
					// Send mail to the user with the new random password 
					$subject_mail = "Restored Password for Survey Analysis";
					$body_mail = "For the email address: " . $forgotten_mail . " the new password is: " . $randompassword . "";
					
					send_Mail($forgotten_mail, $subject_mail, $body_mail);
				}
				else // To prevent the email address for a hacking attack, also if the mail address is not saved in the data base
					// there will be a success dialog shown
				{
					echo "<script type=\"text/javascript\">show_dialog_success('Password was restored please check your mail account!');</script>";
				}
			}
	
			$statement->close();
		} 			
	?>  

    
    <script>
    	$(document).ready(function(){
		$('[data-toggle="tooltip"]').tooltip(); 
		});
	</script>

  </body>
</html>