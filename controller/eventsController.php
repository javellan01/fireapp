<?php

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
//Events em andamento no Pedido on foi registrada alguma execução na atividade	
function getEventsPedido($conn,$pid){

    $stmt = $conn->query("SELECT a.*,p.tx_codigo,cat.tx_nome, cat.tx_color FROM atividade a 
                        INNER JOIN pedido p ON a.id_pedido = p.id_pedido 
                        INNER JOIN categoria cat ON a.id_categoria = cat.id_categoria 
                        INNER JOIN v_sum_atividade_exec vsa ON a.id_atividade = vsa.id_atividade
                        WHERE p.id_pedido = $pid AND a.cs_finalizada = 0 AND NOT vsa.qtd_sum IS NULL AND NOT a.dt_inicio = '00-00-0000'");
    
    $data = $stmt->fetchAll(PDO::FETCH_OBJ);

    return $data;

	}
			
function fillAtividadesCalendar($conn,$pid){

    $data = getEventsPedido($conn,$pid);
    echo "[ ";
    foreach($data as $event){
        if($event->cs_finalizada == 1) {
            $color = cat_color(9);
            $status = 'Encerrada';
        }	
        if($event->cs_finalizada == 0){ 
            $color = $event->tx_color;
            $status = 'Ativa';
            }
        $url = "#";
        $periodo = 'Início: '.data_usql($event->dt_inicio).' - Término: '.data_usql($event->dt_fim);
        
        echo "{ title: '".$event->tx_descricao."', start:'".$event->dt_inicio."', end:'".$event->dt_fim."T18:00:00',url:'".$url."', color:'".$color."', status:'".$status."', pedido:'".$event->tx_codigo."', categoria:'".$event->tx_nome."', periodo:'".$periodo."', allDay: false},";
    }
    echo " ]";
}

?>