<?php
session_start();

if(!isset($_SESSION["login"]) && !isset($_SESSION["pedido"])) 
		{ 
	// Usuário não logado! Redireciona para a página de login 
		header("Location: login.php"); 
		exit; 
	} 
	
	$type = $_GET['type'];
	$id = $_GET['data'];
	$fname = $_GET['fname'];
	
	if($type == 'tecnico'){
		// path to the file
		$path = 'https://admin.firesystems-am.com.br/storage/'.$type.'/'.$id;
		// file path
		$file = $path.'/'.$fname.'';

		$fh = fopen($file, 'r');

		if($fh){

			$maxRead = 1 * 512 * 1024;
				
				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment; filename="' . $fname . '"');

				while (!feof($fh)) {
					
					echo fread($fh, $maxRead);
					
					ob_flush();
				}
			exit('Arquivo Encontrado!');
			}
			else{
				header('HTTP/1.1 404 Not Found: File Not Found.');
				exit('Arquivo não encontrado!');
				
			}
		
	} 

?>