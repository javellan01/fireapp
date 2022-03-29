<?php 

function getUser($conn,$login,$senha){

    $stmt = $conn->query("SELECT c.id_cliente, c.tx_nome AS cliente, cu.tx_nome, cu.id_usuario, cu.nb_category_user 
    FROM cliente_usr AS cu 
    INNER JOIN cliente AS c ON cu.id_cliente = c.id_cliente 
    WHERE cu.tx_email = '".$login."' AND cu.tx_password = '".$senha."'");

    $data = $stmt->fetch(PDO::FETCH_OBJ);

    return $data;


}

?>