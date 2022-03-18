<?php

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