<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'receber'; // Define a tabela 'receber' onde estão os dados das contas a receber.
@session_start(); // Inicia a sessão ou retoma a sessão ativa.
$id_usuario = $_SESSION['id']; // Obtém o ID do usuário logado.

$id = $_POST['id']; // Recebe o ID da conta a ser atualizada ou inserida.
$produto = $_POST['produto']; // Recebe o ID do produto.
$valor = $_POST['valor']; // Recebe o valor da venda.
$valor = str_replace(',', '.', $valor); // Substitui vírgula por ponto no valor para garantir que seja formatado corretamente.
$pessoa = $_POST['pessoa']; // Recebe o ID do cliente.
$data_vencimento = $_POST['data_vencimento']; // Recebe a data de vencimento da conta.
$data_pagamento = $_POST['data_pagamento']; // Recebe a data de pagamento (caso tenha sido paga).
$quantidade = $_POST['quantidade']; // Recebe a quantidade do produto vendido.
$pagamento = $_POST['pagamento']; // Recebe a forma de pagamento.

if ($produto == 0) { // Verifica se foi selecionado um produto válido.
	echo 'Cadastre um Produto e Depois selecione!'; // Se não foi, exibe mensagem de erro.
	exit(); // Encerra a execução do código.
}

// Recupera os dados do produto selecionado na tabela 'produtos'.
$query = $pdo->query("SELECT * FROM produtos where id = '$produto'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$descricao = 'Venda - (' . $quantidade . ') ' . $resultado[0]['nome'];  // Cria a descrição da venda com a quantidade e nome do produto.
$estoque = $resultado[0]['estoque']; // Obtém a quantidade de estoque disponível do produto.

if ($data_pagamento != '') { // Verifica se a data de pagamento foi informada.
	$usuario_pagamento = $id_usuario; // Se foi, registra o ID do usuário que fez o pagamento.
	$pago = 'Sim'; // Marca o status do pagamento como "Sim".
} else {
	$usuario_pagamento = 0; // Se não foi, define o usuário de pagamento como 0.
	$pago = 'Não'; // Marca o status de pagamento como "Não".
}

// Verifica se a quantidade vendida é maior que o estoque disponível.
if ($quantidade > $estoque) {
	// Exibe erro se for maior.
	echo 'Você não pode vendar mais do que você possui em estoque! Você tem ' . $estoque . ' produtos em estoque!';
	exit();
}

// Atualiza o estoque do produto após a venda.
$total_estoque = $estoque - $quantidade; // Calcula o novo estoque.
// Atualiza o estoque na tabela 'produtos'.
$pdo->query("UPDATE produtos SET estoque = '$total_estoque' WHERE id = '$produto'");

// Recupera os dados da conta na tabela 'receber', se já existir um ID.
$query = $pdo->query("SELECT * FROM $tabela where id = '$id'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado); // Verifica se a conta já existe.
if ($total_registro > 0) {
	$foto = $resultado[0]['foto'];  // Se existe, pega a foto associada à conta.
} else {
	$foto = 'sem-foto.jpg';  // Se não existe, define uma foto padrão.
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
	// Se o ID não foi passado, realiza uma inserção (cria uma nova conta).
	$query = $pdo->prepare("INSERT INTO $tabela SET descricao = :descricao, tipo = 'Venda', valor = :valor, data_lancamento = curDate(), 
	data_vencimento = '$data_vencimento', data_pagamento = '$data_pagamento', usuario_lancou = '$id_usuario', usuario_baixa = '$usuario_pagamento', 
	foto = '$foto', pessoa = '$pessoa', pago = '$pago', produto = '$produto', quantidade = '$quantidade', pagamento = '$pagamento'");
} else {
	//Caso contrário, realiza uma atualização.
	$query = $pdo->prepare("UPDATE $tabela SET descricao = :descricao, valor = :valor, data_vencimento = '$data_vencimento', 
	data_pagamento = '$data_pagamento', foto = '$foto', pessoa = '$pessoa', produto = '$produto', quantidade = '$quantidade' WHERE id = '$id'");
}

$query->bindValue(":descricao", "$descricao");
$query->bindValue(":valor", "$valor");
$query->execute();

echo 'Salvo com Sucesso';
