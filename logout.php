<script>
	// If the log in was successful redirect to the survey overview
	function logout_success()
	{
		window.location = "/index.php";
	}
</script>

<?php 
	session_start();
	session_destroy();
	$_SESSION = array();
	
	echo "<script type=\"text/javascript\">logout_success();</script>";
		
?>