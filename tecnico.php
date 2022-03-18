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
      .app-body {
        overflow-x: initial;
      }
	  th {font-weight: normal;}
    </style>
		<script src="./assets/js/jquery-3.6.0.min.js"></script>
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
		
		
	<!-- AJAX Scriping for loading dynamically PHP on server -->
		<script src="./assets/js/central.js"></script>
			
</head>
<?php 
	require("./DB/conn.php");
	$cnpj = $_SESSION['login'];
	$uid = $_SESSION['userid'];
	$cliente = $_SESSION['cliente'];
	
	function data_usql($data) {
		$ndata = substr($data, 8, 2) ."/". substr($data, 5, 2) ."/".substr($data, 0, 4);
		return $ndata;
	}
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
				<a class="nav-link text-warning" href="calendario.php">
				  <i class="nav-icon cui-calendar"></i>Calendário
				</a>
			  </li>
			  <li class="nav-item">
				<a class="nav-link text-light" href="tecnico.php">
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
			<li class="breadcrumb-item ">Arquivo Técnico</a></li>
		</ol>
	</nav>

<?php
echo" <div class='container-fluid'>
				<div class='card'>
					<div class='card-header'><div class='row mt-1'><div class='col-9'>
				<h3><cite>".$cliente."</cite> - Arquivo Técnico:</h3>
			</div>
							<div class='col-3'>
						<h3 class='btn btn-outline-success float-right'>Data Atual: ".date("d/m/Y", $_SERVER['REQUEST_TIME'])."</h3>
					</div>
						</div>
					</div> 	
					<div class='row'>
						<div class='col-12'>	
					<div class='card-body'>";
	
		
	// Carrega os pedidos e coloca nos cards
	if($_SESSION['catuser'] == 0){
	$stmt = $conn->query("SELECT p.tx_local, p.tx_codigo, p.cs_estado, arq.id_pedido FROM arq_tecnico As arq 
							INNER JOIN pedido AS p ON arq.id_pedido = p.id_pedido
							WHERE p.id_cliente = " . $uid . " GROUP BY arq.id_pedido ORDER BY p.tx_codigo ASC");
					}
	if($_SESSION['catuser'] == 1){
	$stmt = $conn->query("SELECT p.tx_local, p.tx_codigo, p.cs_estado, arq.id_pedido FROM arq_tecnico As arq 
							INNER JOIN pedido AS p ON arq.id_pedido = p.id_pedido
							WHERE p.id_cliente_usr = " . $_SESSION['cuserid'] . " GROUP BY arq.id_pedido ORDER BY p.tx_codigo ASC");
					}
	if($stmt->rowCount() == 0){
		echo"<h4><cite> Ainda não há arquivos vinculado aos pedidos! <cite></h4>";}
	else{
 
		echo"<h4><cite> Arquivos disponíveis para consulta: </cite></h4>";
		while($row = $stmt->fetch(PDO::FETCH_OBJ)){
			$pid=$row->id_pedido;     																		
			echo "<div class='accordion border border-success rounded-top mb-3' id='accordion'>
			  <div class='card mb-0'>
				<div class='card-header' id='headingCat$pid'>
				  <h5 class='mb-0'>
					<div class='row'>
						<div class='col-8'>
		<button class='btn btn-ghost-primary float-left' type='button' data-toggle='collapse' data-target='#collapseCat$pid' aria-expanded='true' aria-controls='collapseCat$pid'><strong>Pedido ".$row->tx_codigo."</strong></button>
						</div>
						<div class='col-4'>
							<p class='mb-0 mt-1 ml-auto'><cite>Local: ".$row->tx_local."</cite></p>
						</div>		
					</div></h5>
				</div>
					<div id='collapseCat$pid' class='collapse show' aria-labelledby='headingCat$pid' data-parent='#accordion'>
				<div class='card-body'>
					<div class='row align-items-center'>
						<div class='col-12 p-2'>
		<table class='table table-responsive-lg table-striped'>
			<thead>
				<tr>
					<th>Nome Arquivo</th>
					<th>Descrição</th>
					<th>Data Upload</th>
					<th></th>
				</tr>
			</thead>
			<tbody>";			
				

			$stmt0 = $conn->query("SELECT arq.* FROM arq_tecnico arq WHERE arq.id_pedido = ".$pid." ORDER BY arq.dt_upload DESC");
				
				while($row0 = $stmt0->fetch(PDO::FETCH_OBJ)){
					echo"<tr>
							<th>".$row0->tx_arquivo."</th>
							<th>".$row0->tx_documento."</th>
							<th>".data_usql($row0->dt_upload)."</th>
							<th><a class='btn btn-outline-primary' href='download.php?pe=".$row->tx_codigo."&fname=".$row0->tx_arquivo."'>Download</a></th>
						</tr>";
			
					}		
	
		echo"	</tbody>	
		</table>
					</div></div>
					
			</div>   
		</div>
			<div class='card-footer'>
			<h5 class='mb-0 ml-auto'><label class='border border-primary rounded p-1'>Total Arquivos: ".$stmt0->rowCount()."</label></h5>
			</div>
		</div></div>";
		}
	}
	echo"</div>
	</div></div></div>";

$stmt = null;
$stmt0 = null;
?>
			</div>
		</main>
	</div>				
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