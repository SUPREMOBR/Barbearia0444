<?php
require_once("../../sistema/conexao.php");
$url_img = $_POST['url_img'];

$tabela = 'pagar';
$data_hoje = date('Y-m-d');

$dataInicial = @$_POST['dataInicial'];
$dataFinal = @$_POST['dataFinal'];
$status = '%' . @$_POST['status'] . '%';
$funcionario = @$_POST['id_usuario'];


$query2 = $pdo->query("SELECT * FROM usuarios01 where id = '$funcionario'");
$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$total_registro2 = @count($resultado2);
if ($total_registro2 > 0) {
	$nome_funcionario2 = $resultado2[0]['nome'];
} else {
	$nome_funcionario2 = 'Sem Referência!';
}

$total_pago = 0;
$total_a_pagar = 0;

$query = $pdo->query("SELECT * FROM $tabela where data_lancamento >= '$dataInicial' and data_lancamento <= '$dataFinal' and pago LIKE '$status' and 
funcionario = '$funcionario' and tipo = 'Comissão' ORDER BY pago asc, data_vencimento asc");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
if ($total_registro > 0) {

	for ($i = 0; $i < $total_registro; $i++) {
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

		$valorF = number_format($valor, 2, ',', '.');
		$data_lancamentoF = implode('/', array_reverse(explode('-', $data_lancamento)));
		$data_pagamentoF = implode('/', array_reverse(explode('-', $data_pagamento)));
		$data_vencimentoF = implode('/', array_reverse(explode('-', $data_vencimento)));


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
		} else {
			$nome_funcionario = 'Sem Referência!';
		}


		$query2 = $pdo->query("SELECT * FROM servicos where id = '$servico'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_registro2 = @count($resultado2);
		if ($total_registro2 > 0) {
			$nome_serv = $resultado2[0]['nome'];
		} else {
			$nome_serv = 'Sem Referência!';
		}



		if ($data_pagamento == '0000-00-00') {
			$classe_alerta = 'red';
			$data_pagamentoF = 'Pendente';
			$visivel = '';
			$total_a_pagar += $valor;
			$japago = 'ocultar';
			$oc = '';
		} else {
			$classe_alerta = 'green';
			$visivel = 'ocultar';
			$total_pago += $valor;
			$japago = '';
			$oc = 'none';
		}


		//extensão do arquivo
		$ext = pathinfo($foto, PATHINFO_EXTENSION);
		if ($ext == 'pdf') {
			$tumb_arquivo = 'pdf.png';
		} else if ($ext == 'rar' || $ext == 'zip') {
			$tumb_arquivo = 'rar.png';
		} else {
			$tumb_arquivo = $foto;
		}


		if ($data_vencimento < $data_hoje and $pago != 'Sim') {
			$classe_debito = 'red';
		} else {
			$classe_debito = '';
		}




		$whats = '55' . preg_replace('/[ ()-]+/', '', $telefone_pessoa);

		echo '<li>';
		echo '<a href="#" class="item-link item-content" onclick="editarComissoes(' . $id . ', \'' . $descricao . '\', \'' . $valorF . '\', \'' . $nome_cliente . '\', \'' . $data_vencimentoF . '\', \'' . $data_pagamentoF . '\', \'' . $tumb_arquivo . '\', \'' . $nome_usuario_lancou . '\', \'' . $nome_usuario_pagamento . '\', \'' . $foto . '\', \'' . $ext . '\', \'' . $oc . '\', \'' . $telefone_pessoa . '\', \'' . $whats . '\', \'' . $nome_funcionario . '\', \'' . $nome_serv . '\', \'' . $data_lancamentoF . '\')">';

		echo ' <div class="item-inner">';
		echo ' <div class="item-title" style="font-size:11px;">';
		echo ' <div class="item-header " style="font-size:9px"><i class="mdi mdi-square" style="color:' . $classe_alerta . '"></i> ' . $nome_serv . '</div>R$' . $valorF . ' (' . $nome_cliente . ')';
		echo '<div class="item-footer" style="font-size:9px">Vencimento: ' . $data_vencimentoF . '</div>';
		echo '</div>';

		echo '</div>';
		echo '</a>';
		echo '</li>';
	}
	$total_pagoF = number_format($total_pago, 2, ',', '.');
	$total_a_pagarF = number_format($total_a_pagar, 2, ',', '.');

	echo '<div align="center" style="margin-top:10px"><small><small><span>Total Recebido: <span  style="margin-right:15px; color:green">R$ ' . $total_pagoF . '</span> 
<span>Total à Receber: <span style="color:red">R$ ' . $total_a_pagarF . '</span></small></small></div>';
} else {
	echo '<br><small><small><div align="center">Não encontramos nenhum registro!</div></small></small>';
}
