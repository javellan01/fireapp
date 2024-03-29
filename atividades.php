	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item "><a href="sistema.php">Lista dos Pedidos</a></li>
			<li class="breadcrumb-item active">Detalhes do Pedido</li>
		</ol>
	</nav>
	<div class="container-fluid">
		<div class="card">
			
		<div class='card-header'><div class="row mt-1"><div class="col-9">
		<h3><i class='nav-icon cui-list'></i> Detalhes do Pedido: 
<?php
session_start(); 

	
require("./DB/conn.php");
require("./controller/atividadesController.php");

$pid = $_REQUEST["pid"];
$balance = array();
$measure = 0.00;
$mid = 0;	
$cuid = $_SESSION['cuserid'];
//Carrega dados do pedido
$pedido = getPedidoData($conn,$pid);
$acesso = getUserAcesso($conn,$cuid);

//Carrega dados do pedido
echo $pedido->tx_codigo."</h3>
					</div>
					<div class='col-3'>
					<input type='text' class='form-control' id='formMPed' value=$pid name='idPedido' hidden></input>
					</div>
				</div>
			</div> 	
<div class='card-body border border-primary rounded-top'>

	<div class='row justify-content-between'>";
if($acesso == 0)echo"<div class='col-8'>
	<h4><i class='nav-icon cui-pie-chart'></i> Total do Pedido:<label class='border border-secondary rounded p-1'> R$ ".moeda($pedido->nb_valor)."</label> - Retenção: <label class='border border-secondary rounded p-1'>R$ ".moeda($pedido->retencao)." (".$pedido->nb_retencao."%)</label>
	</div>";
echo"</div>
<div class='row'>
	<div class='col-6'>
	<h4><i class='nav-icon cui-location-pin'></i> Área: <label class='border border-secondary rounded p-1'>".$pedido->tx_local."</label></h4>
	</div>
	<div class='col-3'>
						<h4><i class='nav-icon cui-calendar'></i> Data Ínicio: <label class='border border-secondary rounded p-1'>".data_usql($pedido->dt_idata)."</label>
						</h4>
	</div>
	<div class='col-3'>
		<h4><i class='nav-icon cui-calendar'></i> Data Término: <label class='border border-secondary rounded p-1'>".data_usql($pedido->dt_tdata)."</label>
			</h4>
</div>
</div>	
<div class='row'>
	<div class='col-12 border border-secondary'>
	<h4>Informações Relacionadas: </h4>
	
		<p class='m-2'>".$pedido->tx_descricao."</p><br><br>
	</div>	
</div>			
</div>

<div class='card-body'>
<p id='dataChart'></p>
<canvas class='px-4 my-4 shadow rounded' id='myChart' style='display: block; box-sizing: border-box; height: 400px; width: 100%;'>

</canvas>";
$fisico = getProgressoFisico($conn,$pedido->id_pedido);

	echo "<div class='row mx-1 my-2 '><div class='callout callout-primary b-t-1 b-r-1 b-b-1 m-1 col-12 float-left shadow rounded'> <div class='progress-group'>";
	echo "<div class='progress-group-header align-items-center text-center my-1' style='color: #27b;'><strong><i class='nav-icon cui-chart'></i> Progresso da Obra: </strong></div>";
	echo "<div class='progress-group-bars'> <div class='progress progress-lg' style='height: 1.25rem'>";
	echo "<div class='progress-bar progress-bar-striped bg-warning' role='progressbar' style='width: ". $fisico->execpercent ."%; color:'black'; 
		aria-valuenow='". $fisico->execpercent ."' aria-valuemin='0' aria-valuemax='100'><strong class='text-primary' >". $fisico->execpercent ."%</strong></div></div></div></div></div></div>";  
	echo"		
	<div class='card-text mb-3' id='week'>

	</div>";
// Carrega as somas result das medições
$medicoes = getMedicoes($conn, $pid);

echo"<div class='accordion border border-danger rounded-top mb-3 shadow rounded' id='accordion'>
<div class='card mb-0'>
	<div class='card-header' id='headingMedicao'>
		<h5 class='mb-0'>
		<button type='button' class='btn btn-outline-danger float-left'  data-toggle='collapse' data-target='#collapseMedicao' aria-expanded='true' aria-controls='collapseMedicao'>Medições Cadastradas <i class='nav-icon cui-chevron-bottom'></i>
		</button>
		</h5>
	</div>
		
	<div id='collapseMedicao' class='collapse' aria-labelledby='headingMedicao' data-parent='#accordion'>
		<div class='card-body'>";

if(count($medicoes) == 0){
echo"<div class='card border border-light'><h4><i class='nav-icon cui-info'></i> Não há medições cadastradas para este pedido. </h4></div>";}
else{					
foreach($medicoes as $medicao){

$mid=$medicao->nb_ordem;	
echo"		  
<div class='card border border-light mb-3'>
  <h5 class='card-header'>Medição ".$mid." - ".data_usql($medicao->dt_data)."</h5>
  
  <div class='card-body' style='overflow-y: scroll; overflow-x: clip; max-height: 600px;'>
  <div class='row mb-2'>
  <div class='col-6'>
	<h5 class='card-title'>Valor Medido: ";
	if($acesso == 0)echo"R$ ".moeda($medicao->v_medido)." - ";
	echo calcularPercent($medicao->v_medido,$pedido->nb_valor,1)."% do Pedido.</h5>	
	<h5 class='cart-text'>Status: ".getStatus($conn,$medicao->id_medicao)."</h5>
	<h5 class='cart-text'>Responsável: ".$medicao->tx_name."</h5>
	<p class='card-text'>Nota: ".$medicao->tx_nota." - Data de Emissão: ".$medicao->dt_emissao.", Data de Vencimento: ".$medicao->dt_vencimento."</p>
	</div>
	<div class='col-6'>".getMessage($conn,$medicao->id_medicao)."</div>
	</div>
	<button type='button' class='btn btn-primary mx-2' data-toggle='modal' 
	data-target='#modalLAtv$mid'><i class='nav-icon cui-list'></i> Listar Atividades Medidas</button>";

if($medicao->cs_aprovada == 0){	
echo"
	<button type='button' class='btn btn-success mx-2' data-toggle='modal' 
	data-target='#modalAprovar'><i class='nav-icon cui-check'></i> Aprovar Medição</button>
	<button type='button' class='btn btn-warning mx-2' data-toggle='modal'
	data-target='#modalRevisar'><i class='nav-icon cui-pencil'></i> Solicitar Revisão</button>";
	
}
else{
echo"
	<button type='button' class='btn btn-success mx-2' disabled><i class='nav-icon cui-check'></i> Aprovar Medição</button>
	<button type='button' class='btn btn-warning mx-2' disabled><i class='nav-icon cui-pencil'></i> Solicitar Revisão</button>";
}

echo"	
</div>
</div>
<!!----- MODAL PARA LISTAR ATIVIDADES --------------------------------------->
<div class='modal' style='text-align: left' id='modalLAtv$mid' tabindex='-1' role='dialog' aria-labelledby='modalLAtv$mid' aria-hidden='true'>
	  <div class='modal-dialog modal-lg' role='document'>
		<div class='modal-content'>
		  <div class='modal-header border border-danger rounded-top'>
			<h5 class='modal-title' id='modalLAtv$mid'><cite>".$pedido->tx_codigo."</cite> - Medição ".$mid."</h5>
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
					<th>Progresso Medido</th>";
	if($acesso == 0)echo"<th>Valor Medido</th>";			
		echo"</tr>
			</thead>
			<tbody>";
		$medidas = getMedicaoResume($conn,$pid,$mid);
		$item = 1;
		foreach($medidas as $medida){
			echo"<tr>
					<th>".$item.".</th>
					<th>".$medida->tx_descricao."</th>
					<th>".$medida->tx_nome."</th>
					<th>".moeda($medida->percent)."%</th>";
	if($acesso == 0)echo"<th>R$ ".moeda($medida->nb_valor)."</th>";
			echo"</tr>";
			$item += 1;	
			}
	echo"	<tr>
				<th></th><th></th><th></th><th></th><th></th>
			</tr>";
	if($acesso == 0)echo"<tr>
						<th></th>
						<th></th>
						<th></th>
						<th>Total:</th>
						<th style='font-weight: 500;'>R$ ".moeda($medicao->v_medido)."
					</tr>";
	echo"</tbody>
			</table>		
			</h6>	
			</div>
			<div class='modal-footer'>
		  </div>
		  <button type='button' class='btn btn-secondary' data-dismiss='modal'><i class='nav-icon cui-action-undo'></i> Fechar</button>
		</div>
	  </div>
	</div>";


}
}
$mid += 1;
echo"</div></div></div></div>";
echo"
	<h3 class='mb-3'>Atividades por Categoria:</h3>";
	
// Carrega as categorias
$categorias = getCategoria($conn,$pid);

if(count($categorias) == 0){
echo"<h4 class='danger'><i class='nav-icon cui-info'></i> Ainda não há atividades cadastradas para este pedido!</h4>";}
else{	

//Inicia card para organização das Categorias

foreach($categorias as $categoria){	

$cid = $categoria->id_categoria;
$cat = getCategoriaSum($conn,$pid,$cid);
$atividades = getAtividades($conn,$pid,$cid);

$cpercent = $count = $execpercent = $medpercent = $balpercent = 0;

//Inicia accordion para cada categoria
echo"
<div class='accordion border border-success rounded-top mb-3 shadow rounded' id='accordion'>
<div class='card mb-0'>
<div class='card-header' id='headingCat$cid'>
<h5 class='mb-0'>
<div class='row'>

<div class='col-5'>";
echo"<button class='btn btn-outline-success float-left' type='button' data-toggle='collapse' data-target='#collapseCat$cid' aria-expanded='true' aria-controls='collapseCat$cid'><strong>";
echo $categoria->tx_nome;
echo" <i class='nav-icon cui-chevron-bottom'></i></strong></button>
</div>
 
<div class='col-7'>";
		
		if( $cat->progresso > $cat->nbvalor){
			
		echo"  <div class='callout callout-danger m-0'>
					<small class='text-muted text-danger'>Progresso Categoria</small><br>
					<strong class='h5 text-danger text-nowrap'>".$cat->execpercent."%</strong>";
		if($acesso == 0)echo" - (R$ ".moeda($cat->progresso)."/ ".moeda($cat->nbvalor).")";
		}
		else{
		echo"  <div class='callout callout-success m-0'>
					<small class='text-muted text-success'>Progresso Categoria</small><br>
					<strong class='h5 text-success text-nowrap'>".$cat->execpercent."%</strong>";
		if($acesso == 0)echo" - (R$ ".moeda($cat->progresso)."/ ".moeda($cat->nbvalor).")";			
		}
echo"		
	</div>
</div>
</div>
</h5>

</div>

<div id='collapseCat$cid' class='collapse' aria-labelledby='headingCat$cid' data-parent='#accordion'>
<div class='card-body' style='overflow-y: scroll; overflow-x: clip; max-height: 600px;'>

<!-- MAIN FOREACH FOR ATIVIDADE CATEGORIA -->";
$encerradas = 0;
$em_andamento = 0;
$subtotal = 0.00;
foreach($atividades AS $atividade)  {
if($atividade->qtd_sum > 0 && $atividade->cs_finalizada == 0) $em_andamento += 1;
if($atividade->cs_finalizada == 1) $encerradas += 1;	
if($atividade->valor_sum == null) $atividade->valor_sum = 0;


if(is_null($atividade->medpercent)){
	$atividade->medpercent = '0.00';
}
if(is_null($atividade->execpercent)){
	$atividade->execpercent = '0.0';
}

$balpercent = $atividade->execpercent - $atividade->medpercent;

if($balpercent < 0) $balpercent = 0;
if($balpercent > 100) $balpercent = 100;
$balance[$atividade->id_atividade] = round(($atividade->progresso - $atividade->valor_sum),2);
if($balance[$atividade->id_atividade] < 0) $balance[$atividade->id_atividade] = 0;	
if($balance[$atividade->id_atividade] > $atividade->nb_valor) $balance[$atividade->id_atividade] = $atividade->nb_valor;
echo"	
<div class='row align-items-center'>
				
				
<div class='col-12 p-1'>
	<div class='callout callout-success b-t-1 b-r-1 b-b-1 m-1 col-12 float-left'>
	
	<div class='progress-group-prepend'>";
  if($atividade->cs_finalizada == 0) 
			echo "<div class='progress-group-header align-items-end my-1'><button type='button' class='btn btn-outline-primary p-1'><strong>" . $atividade->tx_descricao . "</strong></div>";
  if($atividade->cs_finalizada == 1) 
			echo "<div class='progress-group-header align-items-end my-1' style='color: #777;'><strong><i class='nav-icon cui-check'></i> " . $atividade->tx_descricao . " (Encerrada)</strong></div>";
  $percent = ($atividade->qtd_sum / $atividade->nb_qtd) * 100;
  $percent = round($percent,1);
  echo "<div class='ml-auto'>Progresso: " . $atividade->qtd_sum . " / " . $atividade->nb_qtd ." ". $atividade->tx_tipo . "</div>";
  echo"	  
  <div class='progress-group-bars mb-1'>
	<div class='progress progress'>
	  <div class='progress-bar bg-orange' role='progressbar' style='width: ".$atividade->percent."%' aria-valuenow='".$atividade->percent."' aria-valuemin='0' aria-valuemax='100'>".$atividade->percent."% Executados</div>
	</div>
  </div>
  <div class='progress-group-bars mb-1'>
	<div class='progress progress'>
	  <div class='progress-bar bg-primary' role='progressbar' style='width: ".$atividade->medpercent."%' aria-valuenow='".$atividade->medpercent."' aria-valuemin='0' aria-valuemax='100'>".$atividade->medpercent."% Medidos</div>
	</div>
  </div>
</div>
</div><!--/.callout-->
		</div><!--/.col--></div>";
$subtotal +=  $balance[$atividade->id_atividade];

echo"<div class='row'><h6></h6></div>";
}
$measure += $subtotal;
echo"	
</div>
</div>
<div class='card-footer'>
<div class='row'>
	<div class='col-6 text-muted text-left'>
		<h5 class='mb-0'>
		<label class='border border-primary rounded p-1'>Total Atividades: ".count($atividades)."</label> / 
		<label class='border border-warning rounded p-1'>Em Andamento: ".$em_andamento."</label> / 
		<label class='border border-success rounded p-1'>Finalizadas: ".$encerradas."</label>
		</h5>
	</div>
	<div class='col-6 text-right'>";
if($acesso == 0) echo"<h5 class='mb-0'>Saldo: R$ ".moeda($subtotal)."</h5>";		
echo"	</div>
</div>	

</div>
</div>
</div>";
}
}

?>
</div></div>

	<!-- Modal Enviar Notificação  -->
	<div class="modal" style="text-align: left" id="modalAprovar" tabindex="-1" role="dialog" aria-labelledby="modalAprovar" aria-hidden="true">
						  <div class="modal-dialog modal-lg" role="document">
							<div class="modal-content">
							  <div class="modal-header border border-danger rounded-top">
								<h5 class="modal-title">Aprovação da Medição <span id="ordemMedicao"></span> - <?php echo $pedido->tx_codigo;?></h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								  <span aria-hidden="true">&times;</span>
								</button>
							  </div>
							  <div class="modal-body"><h5>
	<form class="container">
	<h5>Ao aprovar esta medição, o sistema enviará e-mail de noticação para o(s) endereço(s) a seguir: </h5>
<?php 	
	
		echo"<div class='input-group mb-2'>
				<div class='input-group-prepend'>
				<span class='input-group-text'>Márcio Kunzendorff</span>
			</div>
		<input type='text' class='form-control' value='marcio@firesystems-am.com.br'></input>
		</div>";

	
	?>
	
	<button type='button' class='btn btn-success float-right mx-2' id='sendAprovacao' value='1'>
			<i class='nav-icon cui-check'></i> Aprovar Medição</button>
	</div>
				<div class="modal-footer">
				<button type="button" class="btn btn-secondary btn-block" data-dismiss="modal"><i class='nav-icon cui-ban'></i> Fechar</button>
				</div>
			</div>
			</div>
		</div>
						
					</div>
				</div>
			</div>	
		</div>
		
	</div>

<!-- Modal Revisar  -->
<div class="modal" style="text-align: left" id="modalRevisar" tabindex="-1" role="dialog" aria-labelledby="modalRevisar" aria-hidden="true">
						  <div class="modal-dialog modal-lg" role="document">
							<div class="modal-content">
							  <div class="modal-header border border-danger rounded-top">
								<h5 class="modal-title">Solicitação de Revisão da Medição <span id="ordemMedicao"></span> - <?php echo $pedido->tx_codigo;?></h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								  <span aria-hidden="true">&times;</span>
								</button>
							  </div>
							  <div class="modal-body"><h5>
	<form class="container">
	<h5>Ao solicitar a revisão desta medição, o sistema enviará e-mail de noticação para o(s) endereço(s) a seguir: </h5>
<?php 
	
		echo"<div class='input-group mb-2'>
				<div class='input-group-prepend'>
				<span class='input-group-text'>Márcio Kunzendorff</span>
				</div>
			<input type='text' class='form-control' value='marcio@firesystems-am.com.br'></input>
			</div>";

	
	?>
	
	<label for="mensagemRevisao">Motivo da Solicitação: <cite>Limite de caracteres: <span id='mensagemCounter'></span>.</label>
	<textarea class='form-control' id='mensagemRevisao' rows='4' maxlength='255' placeholder='Informe aqui o motivo da solicitação.'></textarea>

	<button type='button' class='btn btn-warning float-right mx-2 my-2' id='sendRevisar' value='1'>
			<i class='nav-icon cui-pencil'></i> Solicitar Revisão</button>
	</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary btn-block" data-dismiss="modal"><i class='nav-icon cui-ban'></i> Fechar</button>
			</div>
			</div>
			</div>
		</div>
						
					</div>
				</div>
			</div>	
		</div>
		
	</div>