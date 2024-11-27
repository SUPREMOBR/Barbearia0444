<?php
require_once("../../sistema/conexao.php");
$url_img = $_POST['url_img'];

$tabela = 'pagar';
$data_hoje = date('Y-m-d');

$dataInicial = @$_POST['dataInicial'];
$funcionario = @$_POST['id_usuario'];


$query = $pdo->query("SELECT * FROM agendamentos where funcionario = '$funcionario' and data = '$dataInicial' ORDER BY hora asc");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
if ($total_registro > 0) {

	for ($i = 0; $i < $total_registro; $i++) {
		$id = $resultado[$i]['id'];
		$funcionario = $resultado[$i]['funcionario'];
		$cliente = $resultado[$i]['cliente'];
		$hora = $resultado[$i]['hora'];
		$data = $resultado[$i]['data'];
		$usuario = $resultado[$i]['usuario'];
		$data_lancamento = $resultado[$i]['data_lancamento'];
		$obs = $resultado[$i]['obs'];
		$status = $resultado[$i]['status'];
		$servico = $resultado[$i]['servico'];

		$dataF = implode('/', array_reverse(explode('-', $data)));
		$horaF = date("H:i", strtotime($hora));


		if ($status == 'Concluído') {
			$classe_linha = '';
		} else {
			$classe_linha = 'text-muted';
		}



		if ($status == 'Agendado') {
			$imagem = 'icone-relogio.png';
			$classe_status = '';
			$oc = '';
		} else {
			$imagem = 'icone-relogio-verde.png';
			$classe_status = 'ocultar';
			$oc = 'none';
		}

		$query2 = $pdo->query("SELECT * FROM usuarios01 where id = '$usuario'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		if (@count($resultado2) > 0) {
			$nome_usuario = $resultado2[0]['nome'];
		} else {
			$nome_usuario = 'Cliente';
		}


		$query2 = $pdo->query("SELECT * FROM servicos where id = '$servico'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		if (@count($resultado2) > 0) {
			$nome_serv = $resultado2[0]['nome'];
			$valor_serv = $resultado2[0]['valor'];
		} else {
			$nome_serv = 'Não Lançado';
			$valor_serv = '';
		}


		$query2 = $pdo->query("SELECT * FROM clientes where id = '$cliente'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		if (@count($resultado2) > 0) {
			$nome_cliente = $resultado2[0]['nome'];
		} else {
			$nome_cliente = 'Sem Cliente';
		}

		echo '<li>';
		echo '<a href="#" class="item-link item-content" onclick="editarAgenda(' . $id . ', \'' . $nome_usuario . '\', \'' . $nome_cliente . '\', \'' . $horaF . '\', \'' . $dataF . '\', \'' . $obs . '\', \'' . $status . '\', \'' . $nome_serv . '\', \'' . $cliente . '\', \'' . $servico . '\', \'' . $funcionario . '\', \'' . $valor_serv . '\', \'' . $oc . '\')">';
		echo ' <div class="item-media"><img src="' . $url_img . $imagem . '" width="40px" height="40px" style="object-fit: cover;"></div>';
		echo ' <div class="item-inner">';
		echo ' <div class="item-title" style="font-size:12px;">';
		echo ' <div class="item-header " style="font-size:9px">Serviço: ' . $nome_serv . '</div><b>' . $horaF . '</b>';
		echo '<div class="item-footer" style="font-size:9px"> Cliente: ' . $nome_cliente . ' <img src="' . $url_img . 'presente.jpg" width="15px" height="15px" style="object-fit: cover; display:">  </div>';
		echo '</div>';

		echo '</div>';
		echo '</a>';
		echo '</li>';
	}
} else {
	echo '<br><small><small><div align="center">Não encontramos nenhum registro!</div></small></small>';
}
