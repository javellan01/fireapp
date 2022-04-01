	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item "><a href="sistema.php">Lista dos Pedidos</a></li>
			<li class="breadcrumb-item active">Detalhes do Pedido</li>
		</ol>
	</nav>
	<div class="container-fluid">
		<div class="card">
		<div class='card-header'><div class="row mt-1"><div class="col-9">
<?php

function data_usql($data) {
		$ndata = substr($data, 8, 2) ."/". substr($data, 5, 2) ."/".substr($data, 0, 4);
		return $ndata;
	}
function moeda($num){
		return number_format($num,2,',','.');
	}		
require("./DB/conn.php");
$pid=$_REQUEST["pid"];	
$balance = array();
$measure = 0.00;
$mid = 0;	
//Carrega dados do pedido
$stmt3 = $conn->query("SELECT p.*, FORMAT(p.nb_valor,2,'de_DE') AS nbvalor, c.tx_nome, cu.tx_nome as tx_cunome, FORMAT(((p.nb_retencao*p.nb_valor)/100),2,'de_DE') AS retencao FROM pedido p INNER JOIN cliente c ON p.id_cliente = c.id_cliente INNER JOIN cliente_usr cu ON p.id_cliente_usr = cu.id_usuario WHERE p.id_pedido = $pid");
$row3 = $stmt3->fetch(PDO::FETCH_OBJ);
	$retencao = $row3->retencao;
echo"<h3><cite>".$row3->tx_nome."</cite> - Detalhes do Pedido: ".$row3->tx_codigo."</h3>
							</div>
							<div class='col-3'>
							<form>
							<input type='text' class='form-control d-none' id='pid' value=".$pid.">
							</form>		
							</div>
						</div>
					</div> 	
		<div class='card-body border border-primary rounded-top'>
			<div class='row justify-content-between'>
						<div class='col-8'>
				<h5>Total do Pedido:<label class='border border-secondary rounded p-1'> R$ ".$row3->nbvalor."</label> - Retenção: <label class='border border-secondary rounded p-1'>R$ ".$retencao." (".$row3->nb_retencao."%)</label>
						</div>
						<div class='col-4'>
						Data Ínicio: <label class='border border-secondary rounded p-1'>".data_usql($row3->dt_idata)."</label>
							
						</div>
						</div>
		<div class='row'>
			<div class='col-8'>
			Área: <label class='border border-secondary rounded p-1'>".$row3->tx_local."</label>
			</div>
			<div class='col-4'>
				Data Término: <label class='border border-secondary rounded p-1'>".data_usql($row3->dt_tdata)."</label>
					
		</div>
		</div>	
		<div class='row'>
			<div class='col-12 border border-secondary'>
			<h4>Informações Relacionadas: 
			
				<p class='m-2'>".$row3->tx_descricao."</p><br><br>
			</div>	
			</h5>
		</div>			
		</div>
	<div class='card-body'>";

// Carrega as somas result das medições

$stmt0 = $conn->query("SELECT FORMAT(SUM(am.nb_valor),2,'de_DE') AS v_medido, m.id_usuario, m.*, u.tx_name  FROM atividade_medida am 
			LEFT JOIN medicao m ON am.id_pedido=m.id_pedido AND am.nb_ordem = m.nb_ordem 
			INNER JOIN usuario u ON m.id_usuario = u.id_usuario 
			WHERE m.id_pedido = $pid GROUP BY m.nb_ordem ASC;");

echo"<div class='accordion border border-danger rounded-top mb-3' id='accordion'>
		<div class='card mb-0'>
			<div class='card-header' id='headingMedicao'>
				<h5 class='mb-0'>
				<button type='button' class='btn btn-outline-danger float-left'  data-toggle='collapse' data-target='#collapseMedicao' aria-expanded='true' aria-controls='collapseMedicao'>Medições Cadastradas <i class='nav-icon cui-chevron-bottom'></i>
				</button>
				</h5>
			</div>
				
			<div id='collapseMedicao' class='collapse' aria-labelledby='headingMedicao' data-parent='#accordion'>
				<div class='card-body'>";
	
if($stmt0->rowCount() == 0){
		echo"<div class='card border border-light'><h4><cite>Não há medições cadastradas para este pedido.</cite></h4></div>";}
	else{					
while($row0 = $stmt0->fetch(PDO::FETCH_OBJ)){
	
	$mid = $row0->nb_ordem;	
	echo"		  
		<div class='card border border-light mb-3'>
		  <h5 class='card-header'>Medição ".$mid." - ".data_usql($row0->dt_data)."</h5>
		  <div class='card-body'>
			<h5 class='card-title'>Valor Medido: R$ ".$row0->v_medido." - Reponsável: ".$row0->tx_name."</h5>
			<p class='card-text'>Nota: ".$row0->tx_nota." - Vencimento: ".$row0->dt_vencimento."</p>
			<button type='button' class='btn btn-primary' data-toggle='modal' data-target='#modalLAtv$mid'>Listar Atividades Medidas</button>
			</div>
		</div>
		<div class='modal' style='text-align: left' id='modalLAtv$mid' tabindex='-1' role='dialog' aria-labelledby='modalLAtv$mid' aria-hidden='true'>
			  <div class='modal-dialog modal-lg' role='document'>
				<div class='modal-content'>
				  <div class='modal-header border border-danger rounded-top'>
					<h5 class='modal-title' id='modalLAtv$mid'><cite>".$row3->tx_codigo."</cite> - Medição ".$mid."</h5>
					<button type='button' class='close' data-dismiss='modal' aria-label='Close'>
					  <span aria-hidden='true'>&times;</span>
					</button>
				  </div>
				  <div class='modal-body'><h6>
				  <table class='table table-striped'>
					<thead>
						<tr>
							<th>Item</th>
							<th>Atividade</th>
							<th>Categoria</th>
							<th>Percentagem</th>
							<th>Valor</th>
						</tr>
					</thead>
					<tbody>";
			
		$stmt4 = $conn->query("SELECT a.id_categoria, a.tx_descricao, cat.tx_nome, FORMAT(am.nb_valor,2,'de_DE') AS nb_valor, FORMAT(((am.nb_valor/a.nb_valor)*100),2) AS percent FROM atividade_medida AS am INNER JOIN atividade AS a ON am.id_atividade = a.id_atividade INNER JOIN categoria AS cat ON a.id_categoria = cat.id_categoria WHERE am.id_pedido = ".$pid." AND am.nb_ordem = ".$mid." ORDER BY a.id_categoria ASC;");	
				$item = 1;
				while($row4 = $stmt4->fetch(PDO::FETCH_OBJ)){
					echo"<tr>
							<th>".$item.".</th>
							<th>".$row4->tx_descricao."</th>
							<th>".$row4->tx_nome."</th>
							<th>".$row4->percent."%</th>
							<th>R$ ".$row4->nb_valor."</th>
						</tr>";
					$item += 1;	
					}
			?>
					<tr>
						<th></th><th></th><th></th><th></th><th></th>
					</tr>
					<tr>
						<th></th>
						<th></th>
						<th></th>
						<th>Total:</th>
						<th style='font-weight: 500;'>R$ <?php echo $row0->v_medido;?>
					</tr>		
					</tbody>
					</table>		
					</h6>	
					</div>
					<div class='modal-footer'>
				  </div>
				  <button type='button' class='btn btn-secondary' data-dismiss='modal'>Fechar</button>
				</div>
			  </div>
			</div>
		
<?php				
		}
	}
	
	$mid += 1;
echo"</div></div></div></div>";
		echo"<h4 class='mb-3'><cite>Atividades por Categoria:</cite></h4>";
			
// Carrega as categorias
$stmt1 = $conn->query("SELECT c.* FROM atividade a  
		INNER JOIN categoria c ON a.id_categoria=c.id_categoria
		WHERE a.id_pedido = $pid GROUP BY a.id_categoria ASC");
		
if($stmt1->rowCount() == 0){
		echo"<h4 class='danger'>Ainda não há atividades cadastradas para este pedido!</h4>";}
	else{	

//Inicia card para organização das Categorias

while($row1 = $stmt1->fetch(PDO::FETCH_OBJ)){	

	$cid = $row1->id_categoria;
	$cpercent = $count = $execpercent = $medpercent = $balpercent = 0;	

	$stmt2 = $conn->query("SELECT id_categoria, SUM(nb_valor) nbvalor, SUM(valor_sum) valorsum, SUM(qtd_sum) qtdsum, SUM(nb_qtd) nbqtd, CAST(SUM(progresso) AS DECIMAL(10,2)) progresso, v_unit FROM v_categoria_sums WHERE id_pedido = $pid AND id_categoria = $cid GROUP BY id_categoria");
	
	$row2 = $stmt2->fetch(PDO::FETCH_OBJ);
		
	$execpercent = ($row2->progresso / $row2->nbvalor) * 100;
	$execpercent = round($execpercent,1);
	
	$medpercent = ($row2->valorsum / $row2->nbvalor) * 100;
	$medpercent = round($medpercent,1);	
	
	
		//Inicia accordion para cada categoria
	echo"
<div class='accordion border border-success rounded-top mb-3' id='accordion'>
  <div class='card mb-0'>
    <div class='card-header' id='headingCat$cid'>
      <h5 class='mb-0'>
	    <div class='row'>
		
		<div class='col-5'>";
		echo"<button class='btn btn-outline-success float-left' type='button' data-toggle='collapse' data-target='#collapseCat$cid' aria-expanded='true' aria-controls='collapseCat$cid'><strong>";
		echo $row1->tx_nome;
		echo" <i class='nav-icon cui-chevron-bottom'></i></strong></button>
		</div>
		 
		<div class='col-7'>";
				
				if( $row2->progresso > $row2->nbvalor){
					
				echo"  <div class='callout callout-danger m-0'>
							<small class='text-muted text-danger'>Progresso Categoria</small><br>
							<strong class='h5 text-danger text-nowrap'>".$execpercent."% - (R$ ".moeda($row2->progresso)."/".moeda($row2->nbvalor).")</strong>";
				}
				else{
				echo"  <div class='callout callout-success m-0'>
							<small class='text-muted text-success'>Progresso Categoria</small><br>
							<strong class='h5 text-success text-nowrap'>".$execpercent."% - (R$ ".moeda($row2->progresso)."/".moeda($row2->nbvalor).")</strong>";
				}
		echo"		
			</div>
		</div>
		</div>
      </h5>
    
	</div>

    <div id='collapseCat$cid' class='collapse' aria-labelledby='headingCat$cid' data-parent='#accordion'>
      <div class='card-body'>
	  
	  <!-- MAIN WHILE FOR ATIVIDADE CATEGORIA -->";
		$encerradas = 0;
		$subtotal = 0.00;
        $stmt2 = $conn->query("SELECT a.*, v1.qtd_sum, v1.progresso, v1.nb_valor, v1.valor_sum FROM atividade a 
		LEFT JOIN v_categoria_sums v1 ON a.id_atividade=v1.id_atividade 
		WHERE a.id_pedido = $pid AND a.id_categoria = $cid");
		
		
		while($row = $stmt2->fetch(PDO::FETCH_OBJ)){
		if($row->cs_finalizada	== 1) $encerradas += 1;	
		if($row->valor_sum == null) $row->valor_sum = 0;
		if($row->nb_valor == 0 || $row->nb_valor == null){
			$execpercent = '0.0';
			$medpercent = '0.0';
		}else{
		$execpercent = ($row->progresso / $row->nb_valor) * 100;
		$execpercent = round($execpercent,1);
		
		$medpercent = ($row->valor_sum / $row->nb_valor) * 100;
		$medpercent = round($medpercent,1);	
		}	
		$balpercent = $execpercent - $medpercent;
		
		if($balpercent < 0) $balpercent = 0;
		if($balpercent > 100) $balpercent = 100;
		$balance[$row->id_atividade] = round(($row->progresso - $row->valor_sum),2);
		if($balance[$row->id_atividade] < 0) $balance[$row->id_atividade] = 0;	
		if($balance[$row->id_atividade] > $row->nb_valor) $balance[$row->id_atividade] = $row->nb_valor;
		echo"	
		<div class='row align-items-center'>
					
						
		<div class='col-12 p-2'>
			<div class='callout callout-success b-t-1 b-r-1 b-b-1 m-1 col-12 float-left'>
			
			<div class='progress-group-prepend'>";
		  if($row->cs_finalizada == 0) 
					echo "<div class='progress-group-header align-items-end'><button type='button' class='btn btn-outline-primary p-1'><strong>" . $row->tx_descricao . "</strong></div>";
		  if($row->cs_finalizada == 1) 
					echo "<div class='progress-group-header align-items-end' style='color: #777;'><strong>" . $row->tx_descricao . " (Encerrada)</strong></div>";
		  $percent = ($row->qtd_sum / $row->nb_qtd) * 100;
		  $percent = round($percent,1);
		  echo "<div class='ml-auto'>Progresso: " . $row->qtd_sum . " / " . $row->nb_qtd ." ". $row->tx_tipo . "</div>";
		  echo"	  
		  <div class='progress-group-bars mb-1'>
			<div class='progress progress'>
			  <div class='progress-bar bg-orange' role='progressbar' style='width: ".$percent."%' aria-valuenow='".$percent."' aria-valuemin='0' aria-valuemax='100'>".$percent."% Executados</div>
			</div>
		  </div>
		  <div class='progress-group-bars mb-1'>
			<div class='progress progress'>
			  <div class='progress-bar bg-primary' role='progressbar' style='width: ".$medpercent."%' aria-valuenow='".$medpercent."' aria-valuemin='0' aria-valuemax='100'>".$medpercent."% Medidos</div>
			</div>
		  </div>
		</div>
		</div><!--/.callout-->
		</div>
		</div><!--/.BAR CALLOUT INFO GROUP END ROW -->";
		$subtotal +=  $balance[$row->id_atividade];
		$ativas = $stmt2->rowCount() - $encerradas;
		//echo"<div class='row'>Id:".$row->id_atividade.",Sub:".$subtotal.",Bal:".$balance[$row->id_atividade]."<h6></h6></div>";
		}
		$measure += $subtotal;
	echo"	
      </div>
    </div>
	<div class='card-footer'>
		<div class='row'>
			<div class='col-6 text-muted text-left'>
				<h5 class='mb-0'><label class='border border-danger rounded p-1'>Ativas: ".$ativas."</label> / <label class='border border-success rounded p-1'>Encerradas: ".$encerradas.".</label></h5>
			</div>
			<div class='col-6 text-right'>
				<h5 class='mb-0'><label class='border border-primary rounded p-1'>Total Atividades: ".$stmt2->rowCount()."</label>    -  Saldo: R$ ".moeda($subtotal)."</h5>
			</div>
		</div>	
		
	</div>
	</div>
	</div>";
	}
}
?>
</div></div>