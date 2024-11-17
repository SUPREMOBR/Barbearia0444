<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados
$tabela = 'receber'; // Define o nome da tabela no banco de dados
@session_start(); // Inicia a sessão ou retoma a sessão ativa.
$id_usuario = $_SESSION['id']; // Obtém o ID do usuário logado na sessão.

// Recebe os dados enviados via POST (do formulário)
$id = $_POST['id']; // ID do registro
$descricao = $_POST['descricao']; // Descrição da conta
$valor = $_POST['valor']; // Valor da conta
$valor = str_replace(',', '.', $valor); // Substitui a vírgula por ponto no valor para garantir que seja numérico
$pessoa = $_POST['pessoa']; // Pessoa associada à conta
$data_vencimento = $_POST['data_vencimento']; // Data de vencimento da conta
$data_pagamento = $_POST['pagamento']; // Data de pagamento da conta

// Validação: Verifica se a descrição foi informada
if ($descricao == "") {
	echo 'Insira uma descrição!'; // Exibe mensagem de erro se a descrição estiver vazia
	exit(); // Encerra o script
}

// Se o campo 'pessoa' estiver vazio, define como 0 (sem pessoa associada)
if ($pessoa == "") {
	$pessoa = 0;
}

// Verifica se foi informada uma data de pagamento
if ($data_pagamento != '') {
	// Se pago, define o usuário que realizou o pagamento e altera o status para 'Sim'
	$usuario_pgto = $id_usuario;
	$pago = 'Sim';
	// Adiciona a data de pagamento na query de atualização
	$pgto = " ,pagamento = '$data_pagamento'";
} else {
	// Se não foi pago, define como 'Não' e não adiciona a data de pagamento
	$usuario_pgto = 0;
	$pago = 'Não';
	$pgto = "";
}

// Verifica se o registro já existe para obter a foto atual (caso exista)
$query = $pdo->query("SELECT * FROM $tabela where id = '$id'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
// Se o registro existir, obtém o nome da foto armazenada
if ($total_registro > 0) {
	$foto = $resultado[0]['foto'];
} else {
	// Se não existir foto, define um valor padrão
	$foto = 'sem-foto.jpg';
}


//SCRIPT PARA SUBIR FOTO NO SERVIDOR
$nome_img = date('d-m-Y H:i:s') . '-' . @$_FILES['foto']['name'];
$nome_img = preg_replace('/[ :]+/', '-', $nome_img);

$caminho = '../../img/contas/' . $nome_img;

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

if ($id == "") {
	// Se não foi fornecido um ID, realiza uma inserção
	$query = $pdo->prepare("INSERT INTO $tabela SET descricao = :descricao, tipo = 'Conta', valor = :valor, data_lancamento = curDate(), 
	data_vencimento = '$data_vencimento', usuario_lancou = '$id_usuario', usuario_baixa = '$usuario_pagamento', foto = '$foto', pessoa = '$pessoa', 
	pago = '$pago' $pagamento");
} else {
	// Se o ID for fornecido, realiza uma atualização
	$query = $pdo->prepare("UPDATE $tabela SET descricao = :descricao, valor = :valor, data_vencimento = '$data_vencimento', pagamento = '$pagamento', 
	foto = '$foto', pessoa = '$pessoa' WHERE id = '$id'");
}

$query->bindValue(":descricao", "$descricao");
$query->bindValue(":valor", "$valor");
$query->execute();

echo 'Salvo com Sucesso';
