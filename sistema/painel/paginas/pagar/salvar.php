<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'pagar'; // Define o nome da tabela no banco de dados
@session_start();  // Inicia a sessão ou retoma a sessão ativa.
$id_usuario = $_SESSION['id']; // Obtém o ID do usuário logado na sessão.

$id = $_POST['id'];  // Recebe o ID do pagamento (se estiver sendo editado)
$descricao = $_POST['descricao'];  // Recebe a descrição do pagamento
$valor = $_POST['valor'];  // Recebe o valor do pagamento
$valor = str_replace(',', '.', $valor);  // Substitui a vírgula por ponto no valor para adequar ao formato de banco de dados
$pessoa = $_POST['pessoa'];  // Recebe o ID da pessoa (fornecedor ou funcionário)
$data_vencimento = $_POST['data_vencimento'];  // Recebe a data de vencimento
$data_pagamento = $_POST['data_pagamento'];  // Recebe a data de pagamento (caso tenha sido pago)
$funcionario = $_POST['funcionario'];  // Recebe o ID do funcionário que lançou o pagamento

// Verifica se a descrição foi informada. Caso contrário, exibe uma mensagem de erro e encerra o script
if ($descricao == "") {
	echo 'Insira uma descrição!';
	exit();
}

// Se o ID do funcionário não for informado, atribui valor 0
if ($funcionario == "") {
	$funcionario = 0;
}

// Se o ID da pessoa não for informado, atribui valor 0
if ($pessoa == "") {
	$pessoa = 0;
}

// Verifica se foi informada a data de pagamento. Caso tenha, marca o pagamento como "Sim" e armazena o ID do usuário que realizou o pagamento
if ($data_pagamento != '') {
	$usuario_pagamento = $id_usuario;  // Usuário que fez o pagamento
	$pago = 'Sim';  // Marca o pagamento como realizado
	$pagamento = " ,data_pagamento = '$data_pagamento'";  // Adiciona o campo de data de pagamento na query
} else {
	$usuario_pagamento = 0;  // Se não houver pagamento, o ID do usuário é 0
	$pago = 'Não';  // Marca o pagamento como não realizado
	$pagamento = "";  // Não adiciona o campo de data de pagamento
}

// Verifica se o ID do pagamento já existe no banco para pegar a foto associada
$query = $pdo->query("SELECT * FROM $tabela WHERE id = '$id'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);  // Obtém o resultado da consulta
$total_registro = @count($resultado);  // Conta quantos registros foram encontrados

// Se o pagamento for encontrado, pega a foto associada
if ($total_registro > 0) {
	$foto = $resultado[0]['foto'];
} else {
	$foto = 'sem-foto.jpg';  // Caso não tenha foto associada, usa uma foto padrão
}


//SCRIPT PARA SUBIR FOTO NO SERVIDOR
$nome_img = date('d-m-Y H:i:s') . '-' . @$_FILES['foto']['name'];
$nome_img = preg_replace('/[ :]+/', '-', $nome_img);

$caminho = '../../img/contas/' . $nome_img; // Define o caminho para salvar a foto

$imagem_temp = @$_FILES['foto']['tmp_name'];

if (@$_FILES['foto']['name'] != "") {
	$ext = pathinfo($nome_img, PATHINFO_EXTENSION);
	if ($ext == 'png' or $ext == 'jpg' or $ext == 'jpeg' or $ext == 'gif' or $ext == 'pdf' or $ext == 'rar' or $ext == 'zip') {

		//EXCLUO A FOTO ANTERIOR
		if ($foto != "sem-foto.jpg") {
			@unlink('../../img/contas/' . $foto);
		}

		$foto = $nome_img;

		move_uploaded_file($imagem_temp, $caminho);
	} else {
		echo 'Extensão de Imagem não permitida!';
		exit();
	}
}

// Se o ID do funcionário não for informado, atribui valor 0
if ($funcionario == "") {
	$funcionario = 0;
}

// Se o ID do pagamento não for informado, insere um novo pagamento no banco. Caso contrário, atualiza um pagamento existente.
if ($id == "") {
	// Query para inserir um novo pagamento
	$query = $pdo->prepare("INSERT INTO $tabela SET descricao = :descricao, tipo = 'Conta', valor = :valor, data_lancamento = curDate(), 
	data_vencimento = '$data_vencimento',  usuario_lancou = '$id_usuario', usuario_baixa = '$usuario_pagamento', foto = '$foto', 
	pessoa = '$pessoa', pago = '$pago', funcionario = '$funcionario' $pagamento");
} else {
	// Query para atualizar um pagamento existente
	$query = $pdo->prepare("UPDATE $tabela SET descricao = :descricao, valor = :valor, data_vencimento = '$data_vencimento', 
	data_pagamento = '$data_pagamento', foto = '$foto', pessoa = '$pessoa', funcionario = '$funcionario' WHERE id = '$id'");
}

$query->bindValue(":descricao", "$descricao");
$query->bindValue(":valor", "$valor");
$query->execute();

echo 'Salvo com Sucesso';
