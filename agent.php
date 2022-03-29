<?php 
// session_start inicia a sessão
session_start();
$login = $senha = $user = $catu = $uid = '';
// as variáveis login e senha recebem os dados digitados na página anterior

header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); //

if(isset($_POST['email']) && isset($_POST['senha'])){
	
    $login = $_POST['email'];
    $senha = md5($_POST['senha']);


require("./DB/conn.php");
require("./controller/agentController.php");


$data = getUser($conn,$login,$senha);
						


if(count($data) == 1 ){
   	$uid = $data->id_cliente;
	$cliente = $data->cliente;
	$cuid = $data->id_usuario;
	$login = $data->tx_nome;
	$usertype = $data->nb_category_user;
	
	$_SESSION['login'] = $login;
	$_SESSION['cliente'] = $cliente;
	$_SESSION['userid'] = $uid;
	$_SESSION['cuserid'] = $cuid;
	$_SESSION['usertype'] = $usertype;
	$_SESSION['catuser'] = 1;
	
	header('Location: sistema.php');
	}

else{
	
	unset ($_SESSION['login']);
	unset ($_SESSION['userid']);
	unset ($_SESSION['cliente']);
	unset ($_SESSION['catuser']);
	unset ($_SESSION['usertype']);

	session_destroy();

	echo "<p>Email não cadastrado no sistema e/ou senha não confere!</p>";

	header('Location: login.php');
	sleep(1);
	exit();
	}
} 
?>