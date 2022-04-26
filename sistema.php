<?php 
	// Inicia sessões
	session_start(); 
	//echo session_status(); 
	// Verifica se existe os dados da sessão de login 
	if(!isset($_SESSION["login"]) || !isset($_SESSION["catuser"]) || !isset($_SESSION["cliente"])) 
		{ 
	// Usuário não logado! Redireciona para a página de login 
		header("Location: login.php"); 
		exit; 
	} 
	require("./DB/conn.php");
	require("./controller/sistemaController.php");
	
	$cnpj = $_SESSION['login'];
	$uid = $_SESSION['userid'];
	$cliente = $_SESSION['cliente'];	

?>
<!DOCTYPE html>
<html><head>
	<meta lang='pt-BR'>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
	<title>Sistema | FireSystems</title>
	<link rel="stylesheet" href="./assets/css/toastr.min.css">
	<link rel="stylesheet" href="./dist/css/coreui.min.css">
	<link rel="stylesheet" href="./dist/css/coreui-icons.min.css">
	<link rel="stylesheet" href="./dist/css/fullcalendar.min.css">
	
	<style>
      .app-body { overflow-x: initial;}
	  .fc-sat {background-color: #eee;}
	  .fc-sun {background-color: #eee;}
	  .fc-week-number {background-color: #09568d; color: white;}
	  .fc-day-top {color: #09568d;}
	  .fc-day-header {color: #09568d;}
	  th {font-weight: normal;}
    </style>
		<script src="./assets/js/jquery-3.6.0.min.js"></script>
		<script src="./assets/js/jquery-ui.min.js"></script>
		<script src="./assets/js/jquery.ajax.form.js"></script>
		<script src="./assets/js/jquery.mask.min.js"></script>
		<script src="./assets/js/popper.min.js"></script>
		<script src="./dist/js/bootstrap.js"></script>
		<script src="./assets/js/perfect-scrollbar.min.js"></script>
		<script src="./assets/js/coreui.min.js"></script>
		<script src="./assets/js/vue.min.js"></script>
		<script src="./assets/js/toastr.min.js"></script>
		<script src="./assets/js/moment.min.js"></script>
		<script src="./dist/js/fullcalendar.min.js"></script>
		<script src="./dist/js/locale/pt-br.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
		
		
	<!-- AJAX Scriping for loading dynamically PHP on server -->
		<script src="./assets/js/central.js"></script>
</head>

<body class="app header-fixed sidebar-md-show sidebar-fixed">
<header class='app-header navbar' style='background: #2f353a; border-bottom: 4px solid #a60117;'>
	<button class="navbar-toggler sidebar-toggler d-lg-none mr-auto" type="button" data-toggle="sidebar-show">
	<span class="navbar-toggler-icon"></span>
	</button>
		<a class="navbar-brand pl-3" href="http://www.firesystems-am.com.br/">
		<img src="./img/fire.png" alt="FIRE-AM" height="40">
		</a>
			<ul class="nav navbar-nav ml-auto">
				<li class="nav-item px-3">
				<?php
				if($_SESSION['catuser'] == 1) echo "<a class='nav-link text-warning' style='font-weight: 500;' href=''><i class='nav-icon'><img src='./img/logo/".substr($_SESSION['cliente'],0,3).".png' height='35'></i><span> </span><i class='nav-icon cui-user'></i><span> </span>".$_SESSION['login']."</a>";
				?>
				</li>
			</ul>
			<ul class="nav navbar-nav">
				<li class="nav-item px-3">
				<a class="btn btn-light" href="logout.php?token=<?php echo md5(session_id());?>">Logout <i class="nav-icon cui-account-logout"></i></a>
				</li>
			</ul>
</header>
<div class="app-body">	
	<div class="sidebar">
	  <nav class="sidebar-nav" style="font-weight: 480;">
		<ul class="nav">
		  <li class="nav-title" id="fecharBtn"><strong>Sistema Fire Systems</strong></li>
		  
		  <li class="nav-item nav-dropdown ">
			<a class="nav-link nav-dropdown-toggle text-warning" href="#">
			  <strong><i class="nav-icon cui-layers"></i> Gerenciamento </strong> 
			  
			</a>
			<ul class="nav-dropdown-items">
				<li class="nav-item">
				<a class="nav-link text-light" href="sistema.php">
				  <i class="nav-icon cui-home"></i>Lista Pedidos
				</a>
			  </li>
			  <li class="nav-item">
				<a class="nav-link text-warning" href="calendario.php">
				  <i class="nav-icon cui-calendar"></i>Calendário
				</a>
			  </li>
			  <li class="nav-item">
				<a class="nav-link text-warning" href="tecnico.php">
				  <i class="nav-icon cui-file"></i>Técnico
				</a>
			  </li>
			</ul>
		  </li>
		  <li class="nav-item">
			<a class="nav-link nav-link-danger" href="logout.php?token=<?php echo md5(session_id());?>">
			  <i class="nav-icon cui-account-logout"></i>
			  <strong>SAIR</strong>
			</a>
		  </li>
		</ul>
	  </nav>
	  <button class="sidebar-minimizer brand-minimizer" type="button"></button>
	</div>

<!-- Seção MAIN -->
<main class="main" style="background-image:url('./img/back.jpg'); background-repeat: no-repeat; background-attachment: fixed; background-position: top;">
	<div id="main">
		<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item ">Lista dos Pedidos</a></li>
		</ol>
	</nav>

<?php
	
// Carrega os pedidos e coloca nos cards MODO USER Unico Modo Agora
if($_SESSION['catuser'] == 1){
$cuid = $_SESSION['cuserid'];
usrclienteLastAccess($conn,$cuid);
$pedidos = getPedidosUsrCliente($conn,$cuid);
			
	
echo" <div class='container-fluid'>
				<div class='card'>
					<div class='card-header'><div class='row mt-1'><div class='col-7'>";
		echo "<h3><i class='nav-icon cui-home'></i><cite> ".$cliente."</cite> - Lista de Pedidos:</h3><h5>".$_SESSION['login'].", olá!</h5>";
				echo"</div>
						<div class='col-5'>
						<h3 class='btn btn-outline-success float-right'>Data Atual: ".date("d/m/Y", $_SERVER['REQUEST_TIME'])."</h3>
					</div>
						</div>
					</div> 	
					<div class='row'>
						<div class='col-12'>	
					<div class='card-body'>";
	
	if(count($pedidos) == 0){
		echo"<h4><cite> Ainda não há pedidos cadastrados! <cite></h4>";}
	else{  
		echo"<h4><i class='nav-icon cui-list'></i><cite> Pedidos disponíveis para consulta:</cite></h4>";
		
		foreach($pedidos as $pedido){
			$fisico = getProgressoFisico($conn,$pedido->id_pedido);

			echo "<div class='card-text border-top border-secondary'><div class='progress-group mt-2'>";
			  if($pedido->cs_estado == 0) 
					echo "<div class='progress-group-header align-items-end' style='color: #27b;'><div><a class='btn btn-ghost-primary'
					 href='javascript:atvPhp(".$pedido->id_pedido.");' role='button'><strong>Pedido: " . $pedido->tx_codigo . " (Ativo) <i class='nav-icon cui-chevron-right'></i></strong></a></div>";
			  if($pedido->cs_estado == 1) 
					echo "<div class='progress-group-header align-items-end' style='color: #777;'><div><a class='btn btn-ghost-secondary'
					 href='javascript:atvPhp(".$pedido->id_pedido.");' role='button'><strong>Pedido: " . $pedido->tx_codigo . " (Encerrado) <i class='nav-icon cui-chevron-right'></i></strong></a></div>";
					echo "<div class='ml-auto'>Atividades Concluídas: " . $fisico->execpercent ."%</div></div>";
					echo "<div class='progress-group-bars'> <div class='progress progress-lg'>";
					echo "<div class='progress-bar progress-bar-striped bg-warning' role='progressbar' style='width: ". $fisico->execpercent ."%' aria-valuenow='". $fisico->execpercent ."' aria-valuemin='0' aria-valuemax='100'>". $fisico->execpercent ."%</div></div>";	

			  echo "<div class='ml-auto'>Progresso Financeiro: ";
			  // echo "R$ " . moeda($pedido->medido_total) . " / " . moeda($pedido->nb_valor) . "</div></div>";
			  echo "<div class='progress-group-bars'> <div class='progress progress-lg'>";
			  echo "<div class='progress-bar progress-bar-striped bg-success' role='progressbar' style='width: ". $pedido->percent ."%' aria-valuenow='". $pedido->percent ."' aria-valuemin='0' aria-valuemax='100'>". $pedido->percent ."%</div>
			  </div>
			  </div>
			  </div>
			<p class='mb-0 mt-1 ml-auto'><cite> Resp. FireSystems: ".$pedido->tx_name." - Resp. Cliente: ".$pedido->tx_nome."</cite></p>
			<p class='mb-0 mt-1 ml-auto'><cite>Local: ".$pedido->tx_local."</cite></p>
		</div></div>";
		}
	}
	echo"</div>
	</div></div></div>";
}
?>
			</div>
		</main>
	</div>				
<!-- Div body-app Encerramento -->
</div>
	
	
	<footer class="app-footer">
		<div>
		<a href="http://www.firesystems-am.com.br">Fire Systems</a>
		<span>© 2018-2022 Produtos e Serviços Contra Incêndio</span>
		</div>
		<div class="ml-auto">
		<span>Sistema de Gerenciamento Online</span>
		</div>
	</footer>
		<!-- jQuery (necessary for Boot strap's JavaScript plugins) -->
		
 </body> 
</html> 