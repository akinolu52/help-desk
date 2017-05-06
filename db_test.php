<?php 
	
	require 'includes/DB.php';
	
	/*$result = DB::query('SELECT * FROM admin');
	print_r($result);*/
	$firstname = 'Akin';
	$lastname = 'Olu';
	$email = 'a@b.com';
	$password = 'mide';

	$result = DB::query('INSERT INTO admin(firstname, lastname, email, password) 
						VALUES(:firstname, :lastname, :email, :password)')

?>