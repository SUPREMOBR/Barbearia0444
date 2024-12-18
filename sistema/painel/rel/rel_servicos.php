<?php
include('../../conexao.php');
include('data_formatada.php');

$dataInicial = $_GET['dataInicial'];
$dataFinal = $_GET['dataFinal'];
$pagamento = $_GET['pagamento'];
$servico = $_GET['servico'];

$dataInicialF = implode('/', array_reverse(explode('-', $dataInicial)));
$dataFinalF = implode('/', array_reverse(explode('-', $dataFinal)));

if ($dataInicial == $dataFinal) {
	$texto_apuracao = 'APURADO EM ' . $dataInicialF;
} else if ($dataInicial == '1980-01-01') {
	$texto_apuracao = 'APURADO EM TODO O PERÍODO';
} else {
	$texto_apuracao = 'APURAÇÃO DE ' . $dataInicialF . ' ATÉ ' . $dataFinalF;
}



if ($pagamento == '') {
	$acao_rel = '';
} else {
	$acao_rel = 'Pagamento: ' . $pagamento;
}

$pagamento = '%' . $pagamento . '%';


$query = $pdo->query("SELECT * FROM servicos where id = '$servico'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
if ($total_registro > 0) {
	$titulo_servico = $resultado[0]['nome'];
} else {
	$titulo_servico = '';
}

$servico = '%' . $servico . '%';

?>

<!DOCTYPE html>
<html>

<head>
	<title>Relatório de Serviços</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">


	<style>
		@page {
			margin: 0px;

		}

		body {
			margin-top: 5px;
			font-family: Times, "Times New Roman", Georgia, serif;
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
			font-family: Times, "Times New Roman", Georgia, serif;
		}

		.titulo_cab {
			color: #0340a3;
			font-size: 20px;
		}



		.titulo {
			margin: 0;
			font-size: 28px;
			font-family: Arial, Helvetica, sans-serif;
			color: #6e6d6d;

		}

		.subtitulo {
			margin: 0;
			font-size: 12px;
			font-family: Arial, Helvetica, sans-serif;
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

	<div class="titulo_cab titulo_img"><u>Relatório de Serviços <?php echo $titulo_servico ?> <?php echo $acao_rel ?> </u></div>
	<div class="data_img"><?php echo mb_strtoupper($data_hoje) ?></div>

	<img class="imagem" src="<?php echo $url_sistema ?>/sistema/img/logo_rel.jpg" width="150px">


	<br><br><br>
	<div class="cabecalho" style="border-bottom: solid 1px #0340a3">
	</div>

	<div class="mx-2">

		<section class="area-cab">

			<div>
				<small><small><small><u><?php echo $texto_apuracao ?></u></small></small></small>
			</div>


		</section>

		<br>

		<?php
		$total_entradas = 0;
		$query = $pdo->query("SELECT * FROM receber where data_pagamento >= '$dataInicial' and data_pagamento <= '$dataFinal' and tipo = 'Serviço' 
		and pago = 'Sim' and servico LIKE '$servico' and pagamento LIKE '$pagamento'  ORDER BY data_pagamento asc");
		$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
		$total_registro = @count($resultado);
		if ($total_registro > 0) {
		?>

			<table class="table table-striped borda" cellpadding="6">
				<thead>
					<tr align="center">
						<th scope="col">Descrição</th>
						<th scope="col">Valor</th>
						<th scope="col">Data pagamento</th>
						<th scope="col">Recebido Por</th>
						<th scope="col">Forma pagamento</th>
						<th scope="col">Cliente</th>
					</tr>
				</thead>
				<tbody>

					<?php
					for ($i = 0; $i < $total_registro; $i++) {
						foreach ($resultado[$i] as $key => $value) {
						}
						$id = $resultado[$i]['id'];
						$descricao = $resultado[$i]['descricao'];
						$tipo = $resultado[$i]['tipo'];
						$valor = $resultado[$i]['valor'];
						$data_lancamento = $resultado[$i]['data_lancamento'];
						$data_pagamento = $resultado[$i]['data_pagamento'];
						$data_vencimento = $resultado[$i]['data_vencimento'];
						$usuario_lancou = $resultado[$i]['usuario_lancou'];
						$usuario_baixa = $resultado[$i]['usuario_baixa'];
						$foto = $resultado[$i]['foto'];
						$pessoa = $resultado[$i]['pessoa'];
						$pago = $resultado[$i]['pago'];
						$servico = $resultado[$i]['servico'];
						$pagamento = $resultado[$i]['pagamento'];
						$valor2 = $resultado[$i]['valor2'];

						if ($valor <= 0 || $valor == "") {
							$valor = $valor2;
						}


						$total_entradas += $valor;

						$valorF = number_format($valor, 2, ',', '.');
						$total_entradasF = number_format($total_entradas, 2, ',', '.');
						$data_lancamentoF = implode('/', array_reverse(explode('-', $data_lancamento)));
						$data_pagamentoF = implode('/', array_reverse(explode('-', $data_pagamento)));
						$data_vencimentoF = implode('/', array_reverse(explode('-', $data_vencimento)));


						$query2 = $pdo->query("SELECT * FROM clientes where id = '$pessoa'");
						$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
						$total_registro2 = @count($resultado2);
						if ($total_registro2 > 0) {
							$nome_pessoa = $resultado2[0]['nome'];
							$telefone_pessoa = $resultado2[0]['telefone'];
							$classe_whats = '';
						} else {
							$nome_pessoa = 'Nenhum!';
							$telefone_pessoa = '';
							$classe_whats = 'ocultar';
						}


						$query2 = $pdo->query("SELECT * FROM usuarios01 where id = '$usuario_baixa'");
						$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
						$total_registro2 = @count($resultado2);
						if ($total_registro2 > 0) {
							$nome_usuario_pagamento = $resultado2[0]['nome'];
						} else {
							$nome_usuario_pagamento = 'Nenhum!';
						}



						$query2 = $pdo->query("SELECT * FROM usuarios01 where id = '$usuario_lancou'");
						$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
						$total_registro2 = @count($resultado2);
						if ($total_registro2 > 0) {
							$nome_usuario_lancou = $resultado2[0]['nome'];
						} else {
							$nome_usuario_lancou = 'Sem Referência!';
						}

						$query2 = $pdo->query("SELECT * FROM servicos where id = '$servico'");
						$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
						$total_registro2 = @count($resultado2);
						if ($total_registro2 > 0) {
							$nome_servico = $resultado2[0]['nome'];
						} else {
							$nome_servico = '';
						}


					?>

						<tr align="center" class="">
							<td align="left">
								<?php echo $descricao ?>
							</td>

							<td class="esc">R$ <?php echo $valorF ?></td>
							<td class="esc"><?php echo $data_pagamentoF ?></td>
							<td class="esc"><?php echo $nome_usuario_pagamento ?></td>
							<td class="esc"><?php echo $pagamento ?></td>
							<td class="esc"><?php echo $nome_pessoa ?></td>
						</tr>

					<?php } ?>

				</tbody>
			</table>

		<?php } else {
			echo 'Não possuem registros para serem exibidos!';
			exit();
		} ?>

	</div>



	<div class="col-md-12 p-2">
		<div class="" align="right" style="margin-right: 20px">

			<span class=""> <small><small><small><small>TOTAL DE RECEBIMENTOS</small> : <?php echo @$total_registro ?></small></small></small> </span>

			<span class="text-success"> <small><small><small><small>TOTAL R$</small> : <?php echo @$total_entradasF ?></small></small></small> </span>



		</div>
	</div>
	<div class="cabecalho" style="border-bottom: solid 1px #0340a3">
	</div>



	<div class="footer" align="center">
		<span style="font-size:10px"><?php echo $nome_sistema ?> Whatsapp: <?php echo $whatsapp_sistema ?></span>
	</div>

</body>

</html>