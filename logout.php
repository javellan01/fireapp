<?php
session_start();
$token = md5(session_id());
if(isset($_GET['token']) && $_GET['token'] === $token) {
	unset ($_SESSION['login']);
	unset ($_SESSION['userid']);
	unset ($_SESSION['cuserid']);
	unset ($_SESSION['cliente']);
	unset ($_SESSION['catuser']);
	session_destroy();
	header('Location: login.php');
	exit();
	}
	else {
	   echo '<a href="logout.php?token='.$token.'>Confirmar logout</a>';
	}
	
?>