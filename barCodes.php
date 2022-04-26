<?php

require("./DB/conn.php");
require("./controller/atividadesController.php");

	$data = $_POST['id_pedido'];

    getGraphProgressoPedido($conn,$data);
    

?>