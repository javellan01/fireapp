<?php
//	session_start();
	//Modo por cnpj login='tx_cnpj' e userid='id_cliente'
if(!isset($_SESSION["login"]) || !isset($_SESSION["userid"])) 
		{ 
	// Usuário não logado! Redireciona para a página de login 
		header("Location: login.php"); 
		exit; 
	} 
	

//	header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
//	header("Pragma: no-cache"); // HTTP 1.0.
//	header("Expires: 0");
	
//	require("./DB/conn.php");
	$e = null;
	$stmt = null;
	
	
/* 	function data_sql($data) {
		$ndata = substr($data, 6, 4) ."-". substr($data, 3, 2) ."-".substr($data, 0, 2);
		return $ndata;
	} */
	function data_usql($data) {
		$ndata = substr($data, 8, 2) ."/". substr($data, 5, 2) ."/".substr($data, 0, 4);
		return $ndata;
	}
	
	function cat_color($cat){
		$color = '#343236';
		if($cat == 9) $color = '#777777';
		if($cat == 5) $color = '#ce3500';
		if($cat == 6) $color = '#f8a300';
		if($cat == 7) $color = '#65623c';
		if($cat == 8) $color = '#46554f';
		if($cat == 1) $color = '#457725';
		if($cat == 2) $color = '#646e83';
		if($cat == 3) $color = '#09568d';
		if($cat == 4) $color = '#172035';
			
		return $color;
	}
	
	//Calendario.php --- Carrega todas as atividades desse cliente no calendario
		$id_cliente = $_SESSION["userid"];
		echo "[ ";
		//$mes = $_GET['mes'];
		//$ano = $_GET['ano'];
		if($_SESSION["catuser"] == 0){
			try{
		$stmt = $conn->query("SELECT a.*,p.tx_codigo,cat.tx_nome FROM atividade a INNER JOIN pedido p ON a.id_pedido = p.id_pedido INNER JOIN categoria cat ON a.id_categoria = cat.id_categoria WHERE p.id_cliente = ".$id_cliente."");
				}
			catch(PDOException $e)
				{
				echo "Erro: " . $e->getMessage();
				}
			}
		if($_SESSION["catuser"] == 1){
			$id_usuario = $_SESSION["cuserid"];
			try{
		$stmt = $conn->query("SELECT a.*,p.tx_codigo,cat.tx_nome FROM atividade a INNER JOIN pedido p ON a.id_pedido = p.id_pedido INNER JOIN categoria cat ON a.id_categoria = cat.id_categoria WHERE p.id_cliente_usr = ".$id_usuario."");
				}
			catch(PDOException $e)
				{
				echo "Erro: " . $e->getMessage();
				}
			}
			
				
				if($e == null){
				
					while($row = $stmt->fetch(PDO::FETCH_OBJ)){
						
						if($row->cs_finalizada == 1) {
							$color = cat_color(9);
							$status = 'Encerrada';
						}	
						if($row->cs_finalizada == 0){ 
							$color = cat_color($row->id_categoria);
							$status = 'Ativa';
							}
						$url = "javascript:atvPhp(".$row->id_pedido.")";
						$periodo = 'Início: '.data_usql($row->dt_inicio).' - Término: '.data_usql($row->dt_fim);
						
						echo "{ title: '".$row->tx_descricao."', start:'".$row->dt_inicio."', end:'".$row->dt_fim."T18:00:00',url:'".$url."', color:'".$color."', status:'".$status."', pedido:'".$row->tx_codigo."', categoria:'".$row->tx_nome."', periodo:'".$periodo."', allDay: false},";
					}
					
				}
			echo " ] ";
		
?>