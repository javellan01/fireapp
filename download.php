<?php
session_start();

if(!isset($_SESSION["login"]) && !isset($_SESSION["pedido"])) 
		{ 
	// Usuário não logado! Redireciona para a página de login 
		header("Location: login.php"); 
		exit; 
	} 
	
	$pe = $_GET['pe'];
	$fname = $_GET['fname'];
	
	header('Location: ./storage/'.$pe.'/'.$fname.'');

?>