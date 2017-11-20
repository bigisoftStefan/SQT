<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Registration Page</title>

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
    <h1 class="text-center" id = "headline">Survey Analysis</h1>
    
    <!-- Parts are based on a template from https:\//bootsnipp.com/snippets/featured/login-and-register-tabbed-form -->
    <div class="container">
    	<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="alert alert-success alert-dismissable" id="dialog_succes" style="display: none;">
					<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					<strong>Your account is successfully activated! Please close this page</strong>
				</div>
				<div class="alert alert-danger alert-dismissable" id="dialog_fail" style="display: none;">
					<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					<strong>Maybe your account is already activated or an error occurred please try again!</strong>
				</div>
  			</div>
		</div>
    </div>
    
    <script>
	    
	    // Show success dialog
		function show_success_dialog()
		{
			$("#dialog_succes").removeAttr("style");
		}
		
		// Show fail dialog
		function show_fail_dialog()
		{
			$("#dialog_fail").removeAttr("style");
		}
	</script>
	
	<?php
	
	// Activates the registered user
	
	// Config file of the data base
	require_once('config.php');
	
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
	// Checks whether the actual mail adress isn't already in the data base
	$statement = $db_link->prepare("UPDATE User SET activated = true WHERE mail = ?");
	$statement->bind_param("s", $_GET['user']);
	
	$statement->execute();
	
	if ($statement->errno) 
	{
		echo "<script type=\"text/javascript\">show_fail_dialog();</script>";
	}
	else 
	{
		// When there is nothing to update either the email address is not in the system
		// or the account was already activated
		if($statement->affected_rows == 0)
		{
			echo "<script type=\"text/javascript\">show_fail_dialog();</script>";
		}
		else
		{
			echo "<script type=\"text/javascript\">show_success_dialog();</script>";
		}
	}
	
	$statement->close();
			 
	?>
    
  </body>
</html>