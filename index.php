<?php 
// Inicia sessões
session_start(); 

// Verifica se existe os dados da sessão de login 
if(!isset($_SESSION["login"]) || !isset($_SESSION["usuario"]) || !isset($_SESSION["userid"])) 
		{ 
	// Usuário não logado! Redireciona para a página de login 
		header("Location: login.php"); 
		exit; 
	} 
	else{
		if($_SESSION['catuser'] == 0) header('Location: sistema.php');
		if($_SESSION['catuser'] == 1) header('Location: sistema.php');
		exit; 
	}	
		
?>

<!DOCTYPE html>
<html>
<head>
	<title>FireSystems.online</title>
    <meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />

	<script src="./assets/js/jquery-3.3.1.min.js"></script>
	<script src="./assets/js/jquery.mask.min.js"></script>
	<script src="./assets/js/md5.min.js"></script>
	
</head>
<body>

</body>
</html>