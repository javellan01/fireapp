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
		<script src="./assets/js/docs.min.js"></script>
		<script src="./assets/js/vue.min.js"></script>
		<script src="./assets/js/toastr.min.js"></script>
		<script>
		function atvPhp(str) {
			var xhttp = new XMLHttpRequest();
				xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
			document.getElementById("main").innerHTML = this.responseText;

			}
			
			};
			xhttp.open("GET", "atividades.php?pid="+str, true);
			xhttp.send();
			}
		</script>
		
	<!-- AJAX Scriping for loading dynamically PHP on server -->
		
</head>
<?php 
	require("./DB/conn.php");
	require("./controller/sistemaController.php");
	$cnpj = $_SESSION['login'];
	$uid = $_SESSION['userid'];
	$cliente = $_SESSION['cliente'];
	function moeda($num){
		return number_format($num,2,',','.');
	}
?>
<body class="app header-fixed sidebar-md-show sidebar-fixed">
<header class='app-header navbar' style='background: #2f353a; border-bottom: 4px solid #a60117;'>
	<button class="navbar-toggler sidebar-toggler d-lg-none mr-auto" type="button" data-toggle="sidebar-show">
	<span class="navbar-toggler-icon"></span>
	</button>
		<a class="navbar-brand pl-3" href="http://www.firesystems-am.com.br/">
		<img src="./img/fire.png" alt="FIRE-AM" height="47">
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
	if($_SESSION['catuser'] == 0){
	clienteLastAccess($conn,$uid);
	//getPedidosCliente($conn,$uid);
	$stmt = $conn->query("SELECT c.id_cliente, p.tx_local, p.tx_codigo, p.id_pedido, p.cs_estado, u.tx_name, cu.tx_nome, v.medido_total, v.nb_valor, FORMAT(((v.medido_total/v.nb_valor)*100),2) AS percent FROM cliente As c 
							INNER JOIN pedido AS p ON c.id_cliente = p.id_cliente
							INNER JOIN cliente_usr AS cu ON p.id_cliente_usr = cu.id_usuario
							INNER JOIN usuario AS u ON p.id_usu_resp = u.id_usuario
							INNER JOIN v_sum_pedido_total AS v ON p.id_pedido = v.id_pedido
							WHERE p.id_cliente = " . $uid . " ORDER BY p.tx_codigo ASC;");
		}
	// Carrega os pedidos e coloca nos cards MODO USER
	if($_SESSION['catuser'] == 1){
	$cuid = $_SESSION['cuserid'];
	usrclienteLastAccess($conn,$cuid);
	//getPedidosUsrCliente($conn,$uid,$cuid);
	$stmt = null;
	$stmt = $conn->query("SELECT c.id_cliente, p.tx_local, p.tx_codigo, p.id_pedido, p.cs_estado, u.tx_name, cu.tx_nome, v.medido_total, v.nb_valor, FORMAT(((v.medido_total/v.nb_valor)*100),2) AS percent FROM cliente As c 
							INNER JOIN pedido AS p ON c.id_cliente = p.id_cliente
							INNER JOIN cliente_usr AS cu ON p.id_cliente_usr = cu.id_usuario
							INNER JOIN usuario AS u ON p.id_usu_resp = u.id_usuario
							INNER JOIN v_sum_pedido_total AS v ON p.id_pedido = v.id_pedido
							WHERE p.id_cliente = " . $uid . " AND p.id_cliente_usr = " . $cuid . " ORDER BY p.tx_codigo ASC;");		
	}
echo" <div class='container-fluid'>
				<div class='card'>
					<div class='card-header'><div class='row mt-1'><div class='col-7'>";
		if($_SESSION['catuser'] == 0) echo "<h3><cite>".$cliente."</cite> - Lista de Pedidos:</h3><h5>CNPJ: ".$cnpj."</h5>";
		if($_SESSION['catuser'] == 1) echo "<h3><cite>".$cliente."</cite> - Lista de Pedidos:</h3><h5>".$_SESSION['login'].", olá!</h5>";
					echo"</div>
							<div class='col-5'>
						<h3 class='btn btn-outline-success float-right'>Data Atual: ".date("d/m/Y", $_SERVER['REQUEST_TIME'])."</h3>
					</div>
						</div>
					</div> 	
					<div class='row'>
						<div class='col-12'>	
					<div class='card-body'>";
	
	if($stmt->rowCount() == 0){
		echo"<h4><cite> Ainda não há pedidos cadastrados! <cite></h4>";}
	else{  
		echo"<h4><cite> Pedidos disponíveis para consulta: </cite></h4>";
		while($row = $stmt->fetch(PDO::FETCH_OBJ)){
			$fisico = getProgressoFisico($conn,$row->id_pedido);

			echo "<div class='progress-group'>";
			  if($row->cs_estado == 0) 
					echo "<div class='progress-group-header align-items-end' style='color: #27b;'><div><a class='btn btn-ghost-primary' href='javascript:atvPhp(".$row->id_pedido.");' role='button'><strong>Pedido: " . $row->tx_codigo . " (Ativo)</strong></a></div>";
			  if($row->cs_estado == 1) 
					echo "<div class='progress-group-header align-items-end' style='color: #777;'><div><a class='btn btn-ghost-secondary' href='javascript:atvPhp(".$row->id_pedido.");' role='button'><strong>Pedido: " . $row->tx_codigo . " (Encerrado)</strong></a></div>";
			  
					echo "<div class='ml-auto'>Atividades Concluídas: " . $fisico->execpercent ."%</div></div>";
					echo "<div class='progress-group-bars'> <div class='progress progress-lg'>";
					echo "<div class='progress-bar progress-bar-striped bg-warning' role='progressbar' style='width: ". $fisico->execpercent ."%' aria-valuenow='". $fisico->execpercent ."' aria-valuemin='0' aria-valuemax='100'>". $fisico->execpercent ."%</div></div>";	

			  echo "<div class='ml-auto'>Financeiro: (" . $row->percent ."%) - ";
			  echo "R$ " . moeda($row->medido_total) . " / " . moeda($row->nb_valor) . "</div></div>";
			  echo "<div class='progress-group-bars'> <div class='progress progress-lg'>";
			  echo "<div class='progress-bar progress-bar-striped bg-success' role='progressbar' style='width: ". $row->percent ."%' aria-valuenow='". $row->percent ."' aria-valuemin='0' aria-valuemax='100'>". $row->percent ."%</div>
			  </div>
			  </div>
			  </div>
			<p class='mb-0 mt-1 ml-auto'><cite> Resp. FireSystems: ".$row->tx_name." - Resp. Cliente: ".$row->tx_nome."</cite></p>
			<p class='mb-0 mt-1 ml-auto'><cite>Local: ".$row->tx_local."</cite></p>
		</div>";
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
		<span>© 2018-2022 Produtos e Serviços Contra Incêndio</span>
		</div>
		<div class="ml-auto">
		<span>Sistema de Gerenciamento Online</span>
		</div>
	</footer>
		<!-- jQuery (necessary for Boot strap's JavaScript plugins) -->
		
 </body> 
</html> 