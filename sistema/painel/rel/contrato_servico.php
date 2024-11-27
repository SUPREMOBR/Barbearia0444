<?php
include('../../conexao.php');
include('data_formatada.php');

$id = $_GET['id'];

$query = $pdo->query("SELECT * FROM clientes where id = '$id'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
if ($total_registro > 0) {
	$id = $resultado[0]['id'];
	$nome = $resultado[0]['nome'];
	$data_nascimento = $resultado[0]['data_nascimento'];
	$data_cadastro = $resultado[0]['data_cadastro'];
	$telefone = $resultado[0]['telefone'];
	$endereco = $resultado[0]['endereco'];
	$ultimo_servico = $resultado[0]['ultimo_servico'];
	$cpf = $resultado[0]['cpf'];
}

if ($cpf == "") {
	echo 'Preencha o CPF do cliente';
	exit();
}

$query = $pdo->query("SELECT * FROM contratos where cliente = '$id'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$texto_contrato = $resultado[0]['texto'];

?>

<!DOCTYPE html>
<html>

<head>
	<title>Contrato de Serviços</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">


	<style>
		@page {
			margin: 15px;

		}

		body {
			margin-top: 5px;
			font-family: TimesNewRoman, Geneva, sans-serif;
		}

		.footer {
			margin-top: 20px;
			width: 100%;
			background-color: #ebebeb;
			padding: 5px;
			position: absolute;
			bottom: 0;
		}

		.cabecalho {
			padding: 10px;
			margin-bottom: 30px;
			width: 100%;
			font-family: TimesNewRoman, Geneva, sans-serif;
		}

		.titulo_cab {
			color: #0340a3;
			font-size: 20px;
		}

		.titulo {
			margin: 0;
			font-size: 28px;
			font-family: TimesNewRoman, Geneva, sans-serif;
			color: #6e6d6d;

		}

		.subtitulo {
			margin: 0;
			font-size: 12px;
			font-family: TimesNewRoman, Geneva, sans-serif;
			color: #6e6d6d;
		}

		hr {
			margin: 8px;
			padding: 0px;
		}

		.area-cab {

			display: block;
			width: 100%;
			height: 10px;

		}

		.coluna {
			margin: 0px;
			float: left;
			height: 30px;
		}

		.area-tab {

			display: block;
			width: 100%;
			height: 30px;

		}

		.imagem {
			width: 150px;
			position: absolute;
			right: 20px;
			top: 10px;
		}

		.titulo_img {
			position: absolute;
			margin-top: 10px;
			margin-left: 10px;

		}

		.data_img {
			position: absolute;
			margin-top: 40px;
			margin-left: 10px;
			border-bottom: 1px solid #000;
			font-size: 10px;
		}

		.endereco {
			position: absolute;
			margin-top: 50px;
			margin-left: 10px;
			border-bottom: 1px solid #000;
			font-size: 10px;
		}

		.verde {
			color: green;
		}

		table.borda {
			border-collapse: collapse;
			/* CSS2 */
			background: #FFF;
			font-size: 12px;
			vertical-align: middle;
		}

		table.borda td {
			border: 1px solid #dbdbdb;
		}

		table.borda th {
			border: 1px solid #dbdbdb;
			background: #ededed;
			font-size: 13px;
		}
	</style>

</head>

<body>

	<div class="titulo_cab titulo_img"><u>Contrato de Prestação de Serviços </u></div>
	<div class="data_img"><?php echo mb_strtoupper($data_hoje) ?></div>

	<img class="imagem" src="<?php echo $url_sistema ?>/sistema/img/logo_rel.jpg" width="150px">


	<br><br><br>
	<div class="cabecalho" style="border-bottom: solid 1px #0340a3">
	</div>

	<div class="mx-2" style="font-size:13px">


		<?php echo $texto_contrato ?>


	</div>
</body>

</html>