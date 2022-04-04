<?php

function moeda($num){
    return number_format($num,2,',','.');
}

function clienteLastAccess($conn,$uid){
    $stmt = $conn->prepare("UPDATE cliente SET last_access = current_timestamp() WHERE id_cliente = :uid");	

	$stmt->bindParam(':uid', $uid);

	$stmt->execute();	

}

function usrclienteLastAccess($conn,$cuid){
    $stmt = $conn->prepare("UPDATE cliente_usr SET last_access = current_timestamp() WHERE id_usuario = :cuid");

	$stmt->bindParam(':cuid', $cuid);

	$stmt->execute();

}

function getPedidosCliente($conn,$uid){
    $stmt = $conn->query("SELECT c.id_cliente, p.tx_local, p.tx_codigo, p.id_pedido, p.cs_estado, u.tx_name, cu.tx_nome, v.medido_total, v.nb_valor, FORMAT(((v.medido_total/v.nb_valor)*100),2) AS percent FROM cliente As c 
                        INNER JOIN pedido AS p ON c.id_cliente = p.id_cliente
                        INNER JOIN cliente_usr AS cu ON p.id_cliente_usr = cu.id_usuario
                        INNER JOIN usuario AS u ON p.id_usu_resp = u.id_usuario
                        INNER JOIN v_sum_pedido_total AS v ON p.id_pedido = v.id_pedido
                        WHERE p.id_cliente = $uid ORDER BY p.tx_codigo ASC;");

    $data = $stmt->fetchAll(PDO::FETCH_OBJ);

    return $data;

}

function getPedidosUsrCliente($conn,$cuid){

    $stmt = $conn->query("SELECT p.tx_local, p.tx_codigo, p.id_pedido, p.cs_estado, u.tx_name, cu.tx_nome, v.medido_total, v.nb_valor, FORMAT(((v.medido_total/v.nb_valor)*100),2) AS percent 
    FROM acesso_pedido AS ap 
    INNER JOIN pedido AS p ON ap.id_pedido = p.id_pedido
    INNER JOIN cliente_usr AS cu ON ap.id_cliente_usr = cu.id_usuario
    INNER JOIN usuario AS u ON p.id_usu_resp = u.id_usuario
    INNER JOIN v_sum_pedido_total AS v ON p.id_pedido = v.id_pedido
    WHERE ap.id_usuario IS NULL AND ap.id_cliente_usr = $cuid ORDER BY p.tx_codigo ASC;");
                    
    $data = $stmt->fetchAll(PDO::FETCH_OBJ);

    return $data;

}


function getProgressoFisico($conn,$pid){
    $stmt = $conn->query("SELECT vs.id_pedido, FORMAT(((SUM(vs.progresso) / tb.total) *100),1) as execpercent
    FROM v_categoria_sums vs 
    INNER JOIN (SELECT id_pedido, ((nb_valor / 100) * (100 - nb_retencao)) as total FROM pedido) AS tb ON vs.id_pedido = tb.id_pedido
    WHERE vs.id_pedido = $pid GROUP BY vs.id_pedido
    ");

    $data = $stmt->fetch(PDO::FETCH_OBJ);

    return $data;
}

?>