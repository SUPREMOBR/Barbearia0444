<?php
include('../../conexao.php');

setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');
$data_hoje = utf8_encode(strftime('%A, %d de %B de %Y', strtotime('today')));

$dataInicial = $_GET['dataInicial'];
$dataFinal = $_GET['dataFinal'];
$pago = $_GET['pago'];
$busca = $_GET['busca'];
$tabela = $_GET['tabela'];

$dataInicialFormatado = implode('/', array_reverse(explode('-', $dataInicial)));
$dataFinalFormatado = implode('/', array_reverse(explode('-', $dataFinal)));

if ($dataInicial == $dataFinal) {
	$texto_apuracao = 'APURADO EM ' . $dataInicialFormatado;
} else if ($dataInicial == '1980-01-01') {
	$texto_apuracao = 'APURADO EM TODO O PERÍODO';
} else {
	$texto_apuracao = 'APURAÇÃO DE ' . $dataInicialFormatado . ' ATÉ ' . $dataFinalFormatado;
}


if ($pago == '') {
	$acao_relatorio = '';
} else {
	if ($pago == 'Sim') {
		$acao_relatorio = ' Pagas ';
	} else {
		$acao_relatorio = ' Pendentes ';
	}
}

if ($tabela == 'receber') {
	$texto_tabela = ' à Receber';
	$cor_tabela = 'text-success';
	$tabela_pago = 'RECEBIDAS';
} else {
	$texto_tabela = ' à Pagar';
	$cor_tabela = 'text-danger';
	$tabela_pago = 'PAGAS';
}


$pago = '%' . $pago . '%';

?>

<!DOCTYPE html>
<html>

<head>
	<title>Relatório de Contas</title>
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


	<div class="titulo_cab titulo_img"><u>Relatório de Contas <?php echo $texto_tabela ?> <?php echo $acao_relatorio  ?> </u></div>
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
		$total_pago = 0;
		$total_pagoFormatado = 0;
		$total_a_pagar = 0;
		$total_a_pagarFormatado = 0;
		$query = $pdo->query("SELECT * from $tabela where ($busca >= '$dataInicial' and $busca <= '$dataFinal') and pago LIKE '$pago' order by id desc ");
		$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
		$total_registro = count($resultado);
		if ($total_registro > 0) {
		?>

			<table class="table table-striped borda" cellpadding="6">
				<thead>
					<tr align="center">
						<th scope="col">Descrição</th>
						<th scope="col">Valor</th>
						<th scope="col">Vencimento</th>
						<th scope="col">Data Pagamento</th>
						<th scope="col">Pago</th>
					</tr>
				</thead>
				<tbody>

					<?php
					for ($i = 0; $i < $total_registro; $i++) {
						foreach ($resultado[$i] as $key => $value) {
						}
						$id = $resultado[$i]['id'];
						$descricao = $resultado[$i]['descricao'];
						$valor = $resultado[$i]['valor'];
						$data = $resultado[$i]['data_lancamento'];
						$vencimento = $resultado[$i]['data_vencimento'];
						$pago = $resultado[$i]['pago'];
						$data_pagamento = $resultado[$i]['data_pagamento'];

						if ($pago == 'Sim') {
							$total_pago += $valor;
							$classe_square = 'verde';
							$ocultar_baixa = 'ocultar';
							$imagem = 'verde.jpg';
						} else {
							$total_a_pagar += $valor;
							$classe_square = 'text-danger';
							$ocultar_baixa = '';
							$imagem = 'vermelho.jpg';
						}


						$valorFormatado = number_format($valor, 2, ',', '.');
						$total_pagoFormatado = number_format($total_pago, 2, ',', '.');
						$total_a_pagarFormatado = number_format($total_a_pagar, 2, ',', '.');

						$dataFormatada = implode('/', array_reverse(explode('-', $data)));
						$vencimentoFormatado = implode('/', array_reverse(explode('-', $vencimento)));
						$data_pagamentoFormatado = implode('/', array_reverse(explode('-', $data_pagamento)));

						if ($data_pagamentoFormatado == '00/00/0000') {
							$data_pagamentoFormatado = 'Pendente';
						}



					?>

						<tr align="center" class="">
							<td align="left">
								<img src="<?php echo $url_sistema ?>/sistema/img/<?php echo $imagem ?>" width="11px" height="11px" style="margin-top:3px">
								<?php echo $descricao ?>
							</td>
							<td class="esc">R$ <?php echo $valorFormatado ?></td>
							<td class="esc"><?php echo $vencimentoFormatado ?></td>
							<td class="esc"><?php echo $data_pagamentoFormatado ?></td>
							<td class="esc"><?php echo $pago ?></td>
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

			<span class="text-danger"> <small><small><small><small>TOTAL À <?php echo mb_strtoupper($tabela) ?></small> : R$ <?php echo $total_a_pagarFormatado ?></small></small></small> </span>
			<span class="text-success"> <small><small><small><small>TOTAL <?php echo $tabela_pago ?></small> : R$ <?php echo $total_pagoFormatado ?></small></small></small> </span>


		</div>
	</div>
	<div class="cabecalho" style="border-bottom: solid 1px #0340a3">
	</div>



	<div class="footer" align="center">
		<span style="font-size:10px"><?php echo $nome_sistema ?> Whatsapp: <?php echo $whatsapp_sistema ?></span>
	</div>

</body>

</html>