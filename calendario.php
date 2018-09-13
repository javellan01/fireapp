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
    </style>
		<script src="./assets/js/jquery-3.3.1.min.js"></script>
		<script src="./assets/js/jquery-ui.min.js"></script>
		<script src="./assets/js/jquery.ajax.form.js"></script>
		<script src="./assets/js/jquery.mask.min.js"></script>
		<script src="./assets/js/popper.min.js"></script>
		<script src="./assets/js/moment.min.js"></script>
		<script src="./dist/js/bootstrap.js"></script>
		<script src="./assets/js/perfect-scrollbar.min.js"></script>
		<script src="./assets/js/coreui.min.js"></script>
		<script src="./assets/js/docs.min.js"></script>
		<script src="./assets/js/vue.min.js"></script>
		<script src="./assets/js/toastr.min.js"></script>
		<script src="./dist/js/fullcalendar.min.js"></script>
		<script src="./dist/js/locale/pt-br.js"></script>
		
	<!-- AJAX Scriping for loading dynamically PHP on server -->
		<script src="./assets/js/central.js"></script>
		<script>
		$(document).ready(function() {

			$('#calendar').fullCalendar({
			  defaultDate: '<?php echo date("Y-m-d", $_SERVER['REQUEST_TIME']);?>',
			  eventRender: function(eventObj, $el) {
				  $el.popover({
					title: eventObj.title+', Pedido: '+eventObj.pedido,
					content: eventObj.periodo,
					trigger: 'hover',
					placement: 'top',
					container: 'body'
				});
			  },
			  
			  editable: false,
			  eventLimit: true,
			  events: 	<?php require("./load_events.php");?>,
			  weekNumbers: true,
			  weekNumberTitle: 'W',
			  weekNumberCalculation: 'ISO'
			});

			});
		</script>

  
		
</head>
<?php 
	
	$cnpj = $_SESSION['login'];
	$uid = $_SESSION['userid'];
	$cliente = $_SESSION['cliente'];
?>
<body class="app header-fixed sidebar-md-show sidebar-fixed">
<header class='app-header navbar' style='background: #2f353a; border-bottom: 4px solid #a60117;'>
	<button class="navbar-toggler sidebar-toggler d-lg-none mr-auto" type="button" data-toggle="sidebar-show">
	<span class="navbar-toggler-icon"></span>
	</button>
		<a class="navbar-brand pl-3" href="http://www.firesystems-am.com.br/">
		<img src="./img/fire.png" alt="FIRE-AM" width="180" height="47">
		</a>
			<ul class="nav navbar-nav ml-auto">
				<li class="nav-item px-3">
				<?php
				if($_SESSION['catuser'] == 0) echo "<a class='nav-link text-warning' style='font-weight: 500;' href=''><i class='nav-icon'><img src='./img/logo/".substr($_SESSION['cliente'],0,3).".png' height='35'></i>".$_SESSION['cliente']."</a>";
				if($_SESSION['catuser'] == 1) echo "<a class='nav-link text-warning' style='font-weight: 500;' href=''><i class='nav-icon'><img src='./img/logo/".substr($_SESSION['cliente'],0,3).".png' height='35'></i>".$_SESSION['login']."</a>";
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
				<a class="nav-link text-warning" href="sistema.php">
				  <i class="nav-icon cui-home"></i>Lista Pedidos
				</a>
			  </li>
			  <li class="nav-item">
				<a class="nav-link text-light" href="calendario.php">
				  <i class="nav-icon cui-calendar"></i>Calendário
				</a>
			  </li>
			  <li class="nav-item">
				<a class="nav-link text-warning" href="tecnico.php">
				  <i class="nav-icon cui-calendar"></i>Técnico
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

<!-- Seção 0000, PARTE CENTRAL DOS DISPLAY DOS DADOS - USAR AJAX PARA DAR NAVEGAR SEM SAIR DA CENTRAL -->
<main class="main" style="background-image:url('./img/back.jpg'); background-repeat: no-repeat; background-attachment: fixed; background-position: top;">
	<div id="main">
		<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item ">Calendário</a></li>
		</ol>
	</nav>
	<div class='container-fluid'>
				<div class="card">
			<div class="card-header"><div class='row mt-1'><div class='col-9'>
				<h3><cite><?php echo $cliente;?></cite> - Calendário de Atividades:</h3>
			</div>
			<div class='col-3'>
			<h3 class='btn btn-outline-success float-right'>Data Atual: <?php echo date("d/m/Y", $_SERVER['REQUEST_TIME']);?></h3>
					</div>
			</div></div>
				<div class="card-body"><h4><cite>Visão Geral:</cite></h4>
					<div class="m-4" id="calendar">	</div>
				</div>
			</div>
	</div></div>;
</main>

<!-- Div body-app Encerramento -->
	</div>
	
	
	<footer class="app-footer">
		<div>
		<a href="http://www.firesystems-am.com.br">Fire Systems</a>
		<span>© 2018 Produtos e Serviços Contra Incêndio</span>
		</div>
		<div class="ml-auto">
		<span>Sistema de Gerenciamento Online</span>
		</div>
	</footer>
		<!-- jQuery (necessary for Boot strap's JavaScript plugins) -->
		
 </body> 
</html> 