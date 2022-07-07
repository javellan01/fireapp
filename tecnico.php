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
	require("./controller/sistemaController.php");
	
	$cuid = $_SESSION['cuserid'];
	$pedidos = getPedidosUsrCliente($conn,$cuid);
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
		<img src="./img/fire.png" alt="FIRE-AM" height="40">
		</a>
			<ul class="nav navbar-nav ml-auto">
				<li class="nav-item px-3">
				<?php
				if($_SESSION['catuser'] == 0) echo "<a class='nav-link text-warning' style='font-weight: 500;' href=''><i class='nav-icon'><img src='./img/logo/".substr($_SESSION['cliente'],0,3).".png' height='35'></i>".$_SESSION['cliente']."</a>";
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
				<h3><cite><i class='nav-icon cui-paperclip'></i> ".$cliente."</cite> - Arquivo Técnico:</h3>
			</div>
							<div class='col-3'>
						<h3 class='btn btn-outline-success float-right'>Data Atual: ".date("d/m/Y", $_SERVER['REQUEST_TIME'])."</h3>
					</div>
						</div>
					</div> 	
					<div class='row'>
						<div class='col-12'>	
					<div class='card-body'>";
	
	if(count($pedidos) == 0){
		echo"<h4><cite> Ainda não há pedidos vinculado a este perfil! <cite></h4>";
	}
	else{
		echo"<h4><cite> Arquivos disponíveis para consulta: </cite></h4>";

	foreach($pedidos AS $pedido){
		$pid=$pedido->id_pedido;   
		$arquivos = getArquivosPedido($conn,$pid);	  																		
			echo "<div class='accordion border border-light rounded-top mb-3 shadow rounded' id='accordion'>
			  <div class='card mb-0'>
				<div class='card-header' id='headingCat$pid'>
				  <h5 class='mb-0'>
					<div class='row'>
						<div class='col-8'>
		<button class='btn btn-ghost-primary float-left' type='button' data-toggle='collapse' data-target='#collapseCat$pid' aria-expanded='true' aria-controls='collapseCat$pid'><strong>Pedido ".$pedido->tx_codigo." <i class='nav-icon cui-chevron-bottom'></i></strong></button>
						</div>
						<div class='col-4'>
							<p class='mb-0 mt-1 ml-auto'><cite>Local: ".$pedido->tx_local."</cite></p>
						</div>		
					</div></h5>
				</div>
					<div id='collapseCat$pid' class='collapse show' aria-labelledby='headingCat$pid' data-parent='#accordion'>
				<div class='card-body'>
					<div class='row align-items-center'>
						<div class='col-12 p-2'>
		<table class='table table-responsive-lg table-striped shadow rounded'>
			<thead>
				<tr>
				<th>Nome do Arquivo</th>
				<th>Descrição</th>
				<th>Tipo do Arquivo</th>
				<th>Tamanho</th>
				<th>Data de Upload</th>
				
			</tr>
		</thead>
		<tbody>";	
		if(count($arquivos) == 0){
			echo"<tr><td><cite> Ainda não há arquivos vinculado a este pedido! <cite><td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			</tr>";
		}
		else{
		foreach($arquivos AS $arquivo){
	
			if($arquivo->nb_tamanho >= 1024 && $arquivo->nb_tamanho < 1048576){
				$tamanho = 1*$arquivo->nb_tamanho/1024;
				$tamanho = number_format($tamanho,1,'.',',').' kB';
			
			} else if($arquivo->nb_tamanho >= 1048576){
				$tamanho = 1*$arquivo->nb_tamanho/1024/1024;
				$tamanho = number_format($tamanho,1,'.',',').' MB';
			}else $tamanho = $arquivo->nb_tamanho.' bytes';
			// Aloca os medicaos e cria a list
			echo"<tr>
					<td><a class='btn btn-ghost-primary' href='download.php?token=".md5(session_id())."&data=".md5($pid)."&fname=".$arquivo->tx_arquivo."&type=tecnico'><i class='nav-icon cui-cloud-download'></i> ".$arquivo->tx_arquivo."</a></td>
					<td>".$arquivo->tx_documento."</td>
					<td>".$arquivo->tx_version."</td>
					<td>".$tamanho."</td>
					<td>".data_usql($arquivo->dt_upload)."</td>
				</tr>";	
			
			}		
		}	
		echo"</tbody>	
				</table>
				</div></div>
					
			</div>   
		</div>
			<div class='card-footer'>
			<h5 class='my-1 ml-auto'></h5>
			</div>
		</div></div>";
		}
	}
	echo"</div>
	</div></div></div>";

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