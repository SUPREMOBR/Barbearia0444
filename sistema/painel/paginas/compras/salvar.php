<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'pagar';  // Define o nome da tabela no banco de dado
@session_start(); // Inicia a sessão ou retoma a sessão ativa.
$id_usuario = $_SESSION['id']; // Obtém o ID do usuário logado na sessão.

$id = $_POST['id']; // ID da entrada a ser alterada (se houver).
$produto = $_POST['produto']; // ID do produto.
$valor = $_POST['valor']; // Valor da compra.
$valor = str_replace(',', '.', $valor); // Converte o valor para ponto como separador decimal.
$pessoa = $_POST['pessoa']; // Fornecedor.
$data_vencimento = $_POST['data_vencimento']; // Data de vencimento.
$data_pagamento = $_POST['data_pagamento']; // Data de pagamento.
$quantidade = $_POST['quantidade']; // Quantidade comprada.

if ($quantidade == "") {
	$quantidade = 0;
}

if ($pessoa == "") {
	$pessoa = 0;
}

if ($produto == 0) {
	echo 'Cadastre um Produto e Depois selecione!';
	exit();
}

# Consulta busca o nome e o estoque atual do produto na tabela produtos para calcular a descrição da compra e o estoque atualizado.
$query = $pdo->query("SELECT * FROM produtos where id = '$produto'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$descricao = 'Compra - (' . $quantidade . ') ' . $resultado[0]['nome'];  // Descrição da compra.
$estoque = $resultado[0]['estoque']; // Estoque atual do produto.

# Se a data de pagamento for fornecida, o status de pagamento é alterado para "Sim" e a data de pagamento é registrada.
# Caso contrário,o pagamento é "Não".
if ($data_pagamento != '') {
	$usuario_pagamento = $id_usuario;
	$pago = 'Sim';
	$pagamento = " ,data_pagamento = '$data_pagamento'"; // Adiciona a data de pagamento.
} else {
	$usuario_pagamento = 0;
	$pago = 'Não';
	$pagamento = ""; // Não adiciona a data de pagamento.
}


//validar troca da foto
$query = $pdo->query("SELECT * FROM $tabela where id = '$id'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
if ($total_registro > 0) {
	$foto = $resultado[0]['foto'];
} else {
	$foto = 'sem-foto.jpg';
}

//atualizar dados do produto
$valor_unitario = $valor / $quantidade;
$total_estoque = $estoque + $quantidade;
$pdo->query("UPDATE produtos SET estoque = '$total_estoque', valor_compra = '$valor_unitario' WHERE id = '$produto'");



//SCRIPT PARA SUBIR FOTO NO SERVIDOR
$nome_img = date('d-m-Y H:i:s') . '-' . @$_FILES['foto']['name'];  // Nome da imagem com data e hora.
$nome_img = preg_replace('/[ :]+/', '-', $nome_img);

$caminho = '../../img/contas/' . $nome_img; // Caminho da imagem no servidor.

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
		exit(); // Se a extensão for inválida, interrompe o processo.
	}
}

if ($id == "") {
	// Se o ID não for fornecido, insere um novo registro.
	$query = $pdo->prepare("INSERT INTO $tabela SET descricao = :descricao, tipo = 'Compra', valor = :valor, data_lancamento = curDate(), 
	data_vencimento = '$data_vencimento', usuario_lancou = '$id_usuario', usuario_baixa = '$usuario_pagamento', foto = '$foto', pessoa = '$pessoa', 
	pago = '$pago', produto = '$produto', quantidade = '$quantidade' $pagamento");
} else {
	// Se o ID for fornecido, atualiza o registro existente.
	$query = $pdo->prepare("UPDATE $tabela SET descricao = :descricao, valor = :valor, data_vencimento = '$data_vencimento', 
	data_pagamento = '$data_pagamento', foto = '$foto', pessoa = '$pessoa', produto = '$produto', quantidade = '$quantidade' WHERE id = '$id'");
}

$query->bindValue(":descricao", "$descricao");
$query->bindValue(":valor", "$valor");
$query->execute();

echo 'Salvo com Sucesso';
