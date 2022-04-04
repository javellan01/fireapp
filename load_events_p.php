<?php
	
	require("./DB/conn.php");
	require("./controller/eventsController.php");

	//Atividades.php --- Carrega todas as atividades desse pedido no calendario semanal
	$pid = $_POST['pid'];
	$data = fillAtividadesCalendar($conn,$pid);
	return json_encode($data);
	
		
?>