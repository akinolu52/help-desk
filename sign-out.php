<?php
	
	# i nid to do more on this

	unset( $_SESSION['account_id'] );
	unset( $_SESSION['account_type'] );
	$getProfile = null;
	session_destroy();

	// Verify that the logout command worked
	(!isset($_SESSION['account_id'], $_SESSION['account_type'])) ? header('Location: sign-in.php') : die();

?>