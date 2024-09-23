<?php
include('../../conexao.php');

setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');
$data_hoje = utf8_encode(strftime('%A, %d de %B de %Y', strtotime('today')));

$dataInicial = $_GET['dataInicial'];
$dataFinal = $_GET['dataFinal'];
$pago = $_GET['pago'];
$funcionario = $_GET['funcionario'];

$dataInicialFormatado = implode('/', array_reverse(explode('-', $dataInicial)));
$dataFinalFormatada = implode('/', array_reverse(explode('-', $dataFinal)));

if ($dataInicial == $dataFinal) {
	$texto_apuracao = 'APURADO EM ' . $dataInicialFormatado;
} else if ($dataInicial == '1980-01-01') {
	$texto_apuracao = 'APURADO EM TODO O PERÍODO';
} else {
	$texto_apuracao = 'APURAÇÃO DE ' . $dataInicialFormatado . ' ATÉ ' . $dataFinalFormatada;
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

$pago = '%' . $pago . '%';


if ($funcionario == '') {
	$nome_funcionario = '';
} else {
	$query = $pdo->query("SELECT * FROM usuarios01 where id = '$funcionario'");
	$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
	$nome_funcionario = ' - Funcionário: ' . $resultado[0]['nome'];
	$nome_funcionario2 = $resultado[0]['nome'];
	$telefone_funcionario = $resultado[0]['telefone'];
	$pix_funcionario = ' <b>Chave:</b> ' . $resultado[0]['tipo_chave'] . ' <b>Pix:</b> ' . $resultado[0]['chave_pix'];
}

$funcionario = '%' . $funcionario . '%';

?>

<!DOCTYPE html>
<html>

<head>
	<title>Relatório de Comissões</title>
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
			font-size: 17px;
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

	<div class="titulo_cab titulo_img"><u>Relatório de Comissões <?php echo $acao_relatorio ?> <?php echo $nome_funcionario ?></u></div>
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
		$total_a_pagar = 0;
		$total_pendente = 0;

		$query = $pdo->query("SELECT * FROM pagar where data_lancamento >= '$dataInicial' and data_lancamento <= '$dataFinal' and pago LIKE '$pago' and funcionario LIKE '$funcionario' and tipo = 'Comissão' ORDER BY pago asc, data_vencimento asc");
		$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
		$total_registro = @count($resultado);
		if ($total_registro > 0) {
		?>

			<table class="table table-striped borda" cellpadding="6">
				<thead>
					<tr align="center">
						<th scope="col">Serviço</th>
						<th scope="col">Valor</th>
						<th scope="col">Funcionário</th>
						<th scope="col">Data Serviço</th>
						<th scope="col">Pagamento</th>
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
						$funcionario = $resultado[$i]['funcionario'];
						$cliente = $resultado[$i]['cliente'];

						$pago = $resultado[$i]['pago'];
						$servico = $resultado[$i]['servico'];

						$valorFormatado = number_format($valor, 2, ',', '.');
						$data_lancamentoFormatado = implode('/', array_reverse(explode('-', $data_lancamento)));
						$data_pagamentoFormatado = implode('/', array_reverse(explode('-', $data_pagamento)));
						$data_vencimentoFormatado = implode('/', array_reverse(explode('-', $data_vencimento)));


						$query2 = $pdo->query("SELECT * FROM clientes where id = '$pessoa'");
						$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
						$total_registro2 = @count($resultado2);
						if ($total_registro2 > 0) {
							$nome_pessoa = $resultado2[0]['nome'];
							$telefone_pessoa = $resultado2[0]['telefone'];
						} else {
							$nome_pessoa = 'Nenhum!';
							$telefone_pessoa = 'Nenhum';
						}


						$query2 = $pdo->query("SELECT * FROM usuarios01 where id = '$usuario_baixa'");
						$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
						$total_registro2 = @count($resultado2);
						if ($total_registro2 > 0) {
							$nome_usuario_pagamento = $resultado2[0]['nome'];
						} else {
							$nome_usuario_pagamento = 'Nenhum!';
						}



						$query2 = $pdo->query("SELECT * FROM clientes where id = '$cliente'");
						$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
						$total_registro2 = @count($resultado2);
						if ($total_registro2 > 0) {
							$nome_cliente = $resultado2[0]['nome'];
						} else {
							$nome_cliente = 'Nenhum!';
						}



						$query2 = $pdo->query("SELECT * FROM usuarios01 where id = '$usuario_lancou'");
						$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
						$total_registro2 = @count($resultado2);
						if ($total_registro2 > 0) {
							$nome_usuario_lancou = $resultado2[0]['nome'];
						} else {
							$nome_usuario_lancou = 'Sem Referência!';
						}



						$query2 = $pdo->query("SELECT * FROM usuarios01 where id = '$funcionario'");
						$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
						$total_registro2 = @count($resultado2);
						if ($total_registro2 > 0) {
							$nome_funcionario = $resultado2[0]['nome'];
							$chave_pix_funcionario = $resultado2[0]['chave_pix'];
							$tipo_chave_funcionario = $resultado2[0]['tipo_chave'];
						} else {
							$nome_funcionario = 'Sem Referência!';
							$chave_pix_funcionario = '';
							$tipo_chave_funcionario = '';
						}


						$query2 = $pdo->query("SELECT * FROM servicos where id = '$servico'");
						$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
						$total_registro2 = @count($resultado2);
						if ($total_registro2 > 0) {
							$nome_serv = $resultado2[0]['nome'];
						} else {
							$nome_serv = 'Sem Referência!';
						}


						if ($data_pgto == '0000-00-00') {
							$classe_alerta = 'text-danger';
							$data_pagamentoFormatado = 'Pendente';
							$visivel = '';
							$total_a_pagar += $valor;
							$total_pendente += 1;
							$imagem = 'vermelho.jpg';
						} else {
							$classe_alerta = 'verde';
							$visivel = 'ocultar';
							$total_pago += $valor;
							$imagem = 'verde.jpg';
						}




						if ($data_vencimento < $data_hoje and $pago != 'Sim') {
							$classe_debito = 'vermelho-escuro';
						} else {
							$classe_debito = '';
						}


						$total_pagoFormatado = number_format($total_pago, 2, ',', '.');
						$total_a_pagarFormatado = number_format($total_a_pagar, 2, ',', '.');


					?>

						<tr align="center" class="<?php echo $classe_debito ?>">
							<td align="left">
								<img src="<?php echo $url_sistema ?>/sistema/img/<?php echo $imagem ?>" width="11px" height="11px" style="margin-top:3px">
								<?php echo $nome_serv ?>
							</td>
							<td class="esc">R$ <?php echo $valorFormatado ?></td>
							<td class="esc"><?php echo $nome_funcionario ?></td>
							<td class="esc"><?php echo $data_lancamentoFormatado ?></td>
							<td class="esc"><?php echo $data_vencimentoFormatado ?></td>
							<td class="esc"><?php echo $nome_cliente ?></td>
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

			<span class=""> <small><small><small><small>TOTAL DE COMISSÕES</small> : <?php echo @$total_registro ?></small></small></small> </span>

			<span class="text-success"> <small><small><small><small>TOTAL PAGO R$</small> : <?php echo @$total_pagoFormatado ?></small></small></small> </span>

			<span class="text-danger"> <small><small><small><small>TOTAL À PAGAR R$</small> : <?php echo @$total_a_pagarFormatado ?></small></small></small> </span>



		</div>
	</div>
	<div class="cabecalho" style="border-bottom: solid 1px #0340a3">
	</div>





	<?php if ($funcionario != "") { ?>

		<div class="col-md-12 p-2" align="center">
			<div class="">
				<small><small>
						<span class=""> <b>Funcionário</b> : <?php echo @$nome_funcionario2 ?> </span>

						<span class=""> <b>Telefone</b> : <?php echo @$telefone_funcionario ?> </span>

						<span class=""> <?php echo @$pix_funcionario ?> </span>

						<span class="text-success"> <b>Total à Receber</b> : <?php echo @$total_a_pagarFormatado ?> </span>
					</small></small>


			</div>
		</div>
		<div class="cabecalho" style="border-bottom: solid 1px #0340a3">
		</div>

	<?php } ?>



	<div class="footer" align="center">
		<span style="font-size:10px"><?php echo $nome_sistema ?> Whatsapp: <?php echo $whatsapp_sistema ?></span>
	</div>

</body>

</html>