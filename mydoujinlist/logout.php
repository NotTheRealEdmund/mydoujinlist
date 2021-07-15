<!-- Some code from this source was used 
Author: David Adams
Date: 27 January 2021
Title: Secure Login System with PHP and MySQL
Available at: https://codeshack.io/secure-login-system-php-mysql/
-->

<?php
	session_start();
	session_destroy();
	
	// Redirect to the login page
	header('Location: index.html');
?>
