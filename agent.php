
<!DOCTYPE html>
<html>
<head>
	<title>Login | FireSystems</title>
    <meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />

	<script src="./assets/js/jquery-3.3.1.min.js"></script>
	<script src="./assets/js/jquery.mask.js"></script>
	<script src="./assets/js/md5.min.js"></script>
	<style type="text/css">
		@import url(https://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300);
		* {
		  box-sizing: border-box;
		  margin: 0;
		  padding: 0;
		  font-weight: 400;
		}
		body {
		  font-family: 'Source Sans Pro', sans-serif;
		  color: white;
		  font-weight: 400;
		}
		body ::-webkit-input-placeholder {
		  /* WebKit browsers */
		  font-family: 'Source Sans Pro', sans-serif;
		  color: white;
		  font-weight: 300;
		}
		body :-moz-placeholder {
		  /* Mozilla Firefox 4 to 18 */
		  font-family: 'Source Sans Pro', sans-serif;
		  color: white;
		  opacity: 1;
		  font-weight: 300;
		}
		body ::-moz-placeholder {
		  /* Mozilla Firefox 19+ */
		  font-family: 'Source Sans Pro', sans-serif;
		  color: white;
		  opacity: 1;
		  font-weight: 300;
		}
		body :-ms-input-placeholder {
		  /* Internet Explorer 10+ */
		  font-family: 'Source Sans Pro', sans-serif;
		  color: white;
		  font-weight: 300;
		}
		p{
		  font-family: 'Source Sans Pro', sans-serif;
		  color: white;
		  font-weight: 300;

		  color: white;
		  font-size: 1.3em;
		  margin: 15px 0 10px;
		}
		.wrap {
		  background: #333 ;
		  background: linear-gradient(to bottom, #333 0%, #777 100%);
		  position: absolute;
		  top: 0;
		  left: 0;
		  width: 100%;
		  overflow: hidden;
		}
		
		.container {
		  max-width: 600px;
		  margin: 0 auto;
		  padding: 25vh 0 0;
		  height: 100vh;
		  text-align: center;
		}
		.container h1 {
		  font-size: 40px;/*
		  transition-duration: 1s;
		  transition-timing-function: ease-in-put;*/
		  font-weight: 400;
		}
		
		@-webkit-keyframes square {
		  0% {
		    -webkit-transform: translateY(0);
		            transform: translateY(0);
		  }
		  100% {
		    -webkit-transform: translateY(-700px) rotate(600deg);
		            transform: translateY(-700px) rotate(600deg);
		  }
		}
		@keyframes square {
		  0% {
		    -webkit-transform: translateY(0);
		            transform: translateY(0);
		  }
		  100% {
		    -webkit-transform: translateY(-700px) rotate(600deg);
		            transform: translateY(-700px) rotate(600deg);
		  }
		}
	</style>
</head>
<body>

	<div class="wrap">
		<div class="container">
			<h1>Sistema FireSystems</h1>
				<p>Login Inválido!</p>
<?php 
// session_start inicia a sessão
session_start();
$login = $senha = $user = $catu = $uid = '';
// as variáveis login e senha recebem os dados digitados na página anterior

header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); //

if(isset($_POST['usuario']) && isset($_POST['senha'])){
	if($_POST['usuario'] !== ''){
/* 
    $login = $_GET['usuario'];
    $senha = $_GET['senha'];
 */
    $login = $_POST['usuario'];
    $senha = md5($_POST['senha']);

// as próximas 1 linhas são responsáveis em se conectar com o bando de dados.
require("./DB/conn.php");

// A variavel $result pega as varias $login e $senha, faz uma pesquisa na tabela de usuarios

$result = $conn->query("SELECT * FROM cliente WHERE tx_cnpj = '".$login."' AND tx_password = '".$senha."'");
						
/* Logo abaixo temos um bloco com if e else, verificando se a variável $result foi bem sucedida, ou seja se ela estiver encontrado algum registro idêntico o seu valor será igual a 1, se não, se não tiver registros seu valor será 0. Dependendo do resultado ele redirecionará para a pagina site.php ou retornara  para a pagina do formulário inicial para que se possa tentar novamente realizar o login */

if($result->rowCount() == 1 )
{
  while($row = $result->fetch(PDO::FETCH_OBJ)) {
   	$uid = $row->id_cliente;
	$cliente = $row->tx_nome;
	
	};
	$_SESSION['login'] = $login;
	$_SESSION['cliente'] = $cliente;
	$_SESSION['userid'] = $uid;
	$_SESSION['catuser'] = 0;
	
	header('Location: sistema.php');
	}
	}
}

if(isset($_POST['email']) && isset($_POST['senha'])){
	
/* 
    $login = $_GET['usuario'];
    $senha = $_GET['senha'];
 */
    $login = $_POST['email'];
    $senha = md5($_POST['senha']);


require("./DB/conn.php");



$result = $conn->query("SELECT c.id_cliente, c.tx_nome AS cliente, cu.tx_nome, cu.id_usuario FROM cliente_usr AS cu INNER JOIN cliente AS c ON cu.id_cliente = c.id_cliente WHERE cu.tx_email = '".$login."' AND cu.tx_password = '".$senha."'");
						


if($result->rowCount() == 1 )
{
  while($row = $result->fetch(PDO::FETCH_OBJ)) {
   	$uid = $row->id_cliente;
	$cliente = $row->cliente;
	$cuid = $row->id_usuario;
	$login = $row->tx_nome;
	
	};
	$_SESSION['login'] = $login;
	$_SESSION['cliente'] = $cliente;
	$_SESSION['userid'] = $uid;
	$_SESSION['cuserid'] = $cuid;
	$_SESSION['catuser'] = 1;
	
	header('Location: sistema.php');
	}
}

else{
	
unset ($_SESSION['login']);
unset ($_SESSION['userid']);
unset ($_SESSION['cliente']);
unset ($_SESSION['catuser']);

session_destroy();

echo "<p>CNPJ ou Email não cadastrado no sistema e/ou senha não confere!</p>";

header('Location: login.php');
sleep(1);
exit();

}
 
?>
		</div>
		</div>
	</body>
</html>