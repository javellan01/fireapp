<?php

function data_usql($data) {
    $ndata = substr($data, 8, 2) ."/". substr($data, 5, 2) ."/".substr($data, 0, 4);
    return $ndata;
}

function moeda($num){
    return number_format($num,2,',','.');
}	

function calcularPercent($parcel,$total,$precision){
    
    $result = ($parcel/$total)*100;
    
    return number_format($result,$precision,',','.');
}
function getPedidoData($conn, $pid){

    $stmt = $conn->query("SELECT p.*, (( p.nb_valor / 100) * p.nb_retencao) AS retencao, c.tx_nome FROM pedido p INNER JOIN cliente c ON p.id_cliente = c.id_cliente WHERE p.id_pedido = $pid");

    $data = $stmt->fetch(PDO::FETCH_OBJ);
	
    return $data;

}

function getUserAcesso($conn,$uid){

    $stmt = $conn->query("SELECT nb_category_user FROM cliente_usr WHERE id_usuario = $uid");

    $data = $stmt->fetch(PDO::FETCH_OBJ);
	
    return $data->nb_category_user;

}

function getPedido($conn,$pid){

    $stmt = $conn->query("SELECT p.*, 
    FORMAT(p.nb_valor,2,'de_DE') AS nbvalor, c.tx_nome, cu.tx_nome as tx_cunome, 
    FORMAT(((p.nb_retencao*p.nb_valor)/100),2,'de_DE') AS retencao 
    FROM pedido p 
    INNER JOIN cliente c ON p.id_cliente = c.id_cliente 
    INNER JOIN cliente_usr cu ON p.id_cliente_usr = cu.id_usuario 
    WHERE p.id_pedido = $pid;");

    $data = $stmt->fetch(PDO::FETCH_OBJ);

    return $data;
}

function getMedicoes($conn, $pid){
    $stmt = $conn->query("SELECT SUM(am.nb_valor) AS v_medido, m.id_usuario, m.*, u.tx_name  FROM atividade_medida am 
			LEFT JOIN medicao m ON am.id_pedido=m.id_pedido AND am.nb_ordem = m.nb_ordem 
			INNER JOIN usuario u ON m.id_usuario = u.id_usuario 
            WHERE m.id_pedido = $pid GROUP BY m.nb_ordem ASC");
            
    $data = $stmt->fetchAll(PDO::FETCH_OBJ);

    return $data;

}


function getMedicaoResume($conn,$pid,$mid){
    $stmt = $conn->query("SELECT a.id_categoria, a.tx_descricao, cat.tx_nome, am.nb_valor AS nb_valor, ((am.nb_valor/a.nb_valor)*100) AS percent FROM atividade_medida AS am INNER JOIN atividade AS a ON am.id_atividade = a.id_atividade INNER JOIN categoria AS cat ON a.id_categoria = cat.id_categoria WHERE am.id_pedido = ".$pid." AND am.nb_ordem = ".$mid." ORDER BY a.id_categoria ASC;");

    $data = $stmt->fetchAll(PDO::FETCH_OBJ);

    return $data;
}

function getStatus($conn,$mid){

    $stmt = $conn->query("SELECT md.id_cliente_usr, md.cs_aprovada, md.cs_revisar, md.cs_finalizada, md.dt_aprovacao, cu.tx_nome FROM medicao AS md
                            INNER JOIN cliente_usr AS cu ON md.id_cliente_usr = cu.id_usuario
                            WHERE md.id_medicao = $mid");

    $data = $stmt->fetchAll(PDO::FETCH_OBJ);

    if(count($data) == 0){
        $e = '<span class="text-warning">Aguardando Aprovação.</span>';
        return $e;    
    }else{
        if($data[0]->cs_aprovada == 1 && $data[0]->cs_finalizada == 0){
            $e = '<span class="text-success">Aprovada por '.$data[0]->tx_nome.' em '.data_usql($data[0]->dt_aprovacao).'.</span>';
            return $e;
        }
        
        if($data[0]->cs_revisar == 1 && $data[0]->cs_finalizada == 0){
            $e = '<span class="text-warning">Revisão Solicitada por '.$data[0]->tx_nome.'.</span>';
            return $e;
        }
        if($data[0]->cs_finalizada == 1){
            $e = '<span class="text-success">Medição Finalizada.</span>';
            return $e;
        }

            $e = '<span class="text-warning">Aguardando Aprovação.</span>';
            return $e;
        
    }                           
}

function getMessage($conn, $mid){

    $stmt = $conn->query("SELECT * FROM comentario_medicao WHERE id_medicao = $mid");

    $data = $stmt->fetchAll(PDO::FETCH_OBJ);

    if(count($data) == 0){
        return true;
    } else{ 
        $e = '
                <h5 class="card-title">Comentário:</h5>
                <p class="card-text text-primary">'.$data[0]->tx_comentario.'</p>';
        return $e;

    }

    return $data;
}

function getCategoria($conn,$pid){
    $stmt = $conn->query("SELECT c.* FROM atividade a  
        INNER JOIN categoria c ON a.id_categoria=c.id_categoria	WHERE a.id_pedido = $pid GROUP BY a.id_categoria ASC");
        
    $data = $stmt->fetchAll(PDO::FETCH_OBJ);

    return $data;
}

function getCategoriaSum($conn,$pid,$cid){
    $stmt = $conn->query("SELECT FORMAT(((progresso / nbvalor) *100),1) as execpercent, 
    FORMAT(((valorsum / nbvalor) *100),2) as medpercent, id_categoria, nbvalor, valorsum, qtdsum, nbqtd, progresso, v_unit 
    FROM (SELECT id_categoria, SUM(nb_valor) nbvalor, SUM(valor_sum) valorsum, SUM(qtd_sum) qtdsum, SUM(nb_qtd) nbqtd, SUM(progresso) progresso, v_unit 
            FROM v_categoria_sums 
            WHERE id_pedido = $pid AND id_categoria = $cid 
            GROUP BY id_categoria) AS tb");

    $data = $stmt->fetch(PDO::FETCH_OBJ);

    return $data;
}

function getAtividades($conn,$pid,$cid){
    $stmt = $conn->query("SELECT b.*, FORMAT(((b.qtd_sum/b.nb_qtd)*100),1) AS percent, 
    FORMAT(((b.valor_sum/b.nb_valor)*100),2) AS medpercent, 
    FORMAT(((b.progresso/b.nb_valor)*100),1) AS execpercent 
    FROM (SELECT a.*, v1.qtd_sum, v1.progresso, v1.valor_sum 
            FROM atividade a 
            LEFT JOIN v_categoria_sums v1 ON a.id_atividade=v1.id_atividade 
            WHERE a.id_pedido = $pid AND a.id_categoria = $cid) AS b");

    $data = $stmt->fetchAll(PDO::FETCH_OBJ);

    return $data;
}

function getAtividade($conn, $id){
    $stmt = $conn->query("SELECT * FROM atividades WHERE id_atividade = $id");

    $data = $stmt->fetchAll(PDO::FETCH_OBJ);

    return $data;
}
//progresso da obra executada
function getProgressoFisico($conn,$pid){
    
    $stmt = $conn->query("SELECT vs.id_pedido, FORMAT(((SUM(vs.progresso) / SUM(vs.nb_valor)) *100),1) as execpercent
    FROM v_categoria_sums vs 
    WHERE vs.id_pedido = $pid GROUP BY vs.id_pedido");
    
    $data = $stmt->fetch(PDO::FETCH_OBJ);
    
    return $data;
    }    
//CARREGA E CRIA OBJETO JSON PARA DESENHAR O GRAFICO DO PEDIDO
function getGraphProgressoPedido($conn,$pid){

        $stmt = $conn->query("SELECT COUNT(*) AS total,
        COUNT(CASE WHEN done = 1 THEN 1 END) AS pronto,
        COUNT(CASE WHEN done < 1 AND done > 0.75 THEN 1 END) AS quasepronto,
        COUNT(CASE WHEN done <= 0.75 AND done > 0.50 THEN 1 END) AS meiocheio,
        COUNT(CASE WHEN done <= 0.50 AND done > 0.25 THEN 1 END) AS meiovazio,
        COUNT(CASE WHEN done <= 0.25 AND done > 0 THEN 1 END) AS umquarto,
        COUNT(CASE WHEN done = 0 THEN 1 END) AS semprogresso
        FROM (SELECT qtd_sum /nb_qtd AS done FROM v_categoria_sums WHERE id_pedido = $pid) AS tabela001");
    
        $data = $stmt->fetch(PDO::FETCH_OBJ);
    
        $output = array();
        
        $output[0]=$data->total;
        $output[1]=$data->pronto;
        $output[2]=$data->quasepronto;
        $output[3]=$data->meiocheio;
        $output[4]=$data->meiovazio;
        $output[5]=$data->umquarto;
        $output[6]=$data->semprogresso;
        
        echo json_encode($output);
    
    }
?>