<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'produtos'; // Define o nome da tabela no banco de dados

// Obtém os valores passados pelo formulário (POST)
$id = $_POST['id'];  // ID do produto (se estiver editando um produto)
$nome = $_POST['nome'];  // Nome do produto
$valor_compra = $_POST['valor_compra'];  // Valor de compra
$valor_compra = str_replace(',', '.', $valor_compra);  // Substitui a vírgula por ponto para garantir que o valor esteja correto
$valor_venda = $_POST['valor_venda'];  // Valor de venda
$valor_venda = str_replace(',', '.', $valor_venda);  // Substitui a vírgula por ponto para garantir que o valor esteja correto
$descricao = $_POST['descricao'];  // Descrição do produto
$nivel_estoque = $_POST['nivel_estoque'];  // Nível de estoque mínimo

$categoria = $_POST['categoria']; // Categoria do produto

// Verifica se a categoria foi selecionada
if ($categoria == 0) {
	echo 'Cadastre uma Categoria de Produtos para o Produto';
	exit();
}

// Verifica se já existe um produto com o mesmo nome
$query = $pdo->query("SELECT * from $tabela where nome = '$nome'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
if (@count($resultado) > 0 and $id != $resultado[0]['id']) {
	echo 'Nome já Cadastrado, escolha outro!!';
	exit();
}

// Verifica se o produto já existe para obter a foto atual (caso de edição)
$query = $pdo->query("SELECT * FROM $tabela where id = '$id'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
if ($total_registro > 0) {
	$foto = $resultado[0]['foto']; // Obtém a foto atual do produto
} else {
	$foto = 'sem-foto.jpg';
}

//SCRIPT PARA SUBIR FOTO NO SERVIDOR
$nome_img = date('d-m-Y H:i:s') . '-' . @$_FILES['foto']['name'];
$nome_img = preg_replace('/[ :]+/', '-', $nome_img);

$caminho = '../../img/produtos/' . $nome_img;

$imagem_temp = @$_FILES['foto']['tmp_name'];

if (@$_FILES['foto']['name'] != "") {
	$ext = pathinfo($nome_img, PATHINFO_EXTENSION);
	if ($ext == 'png' or $ext == 'jpg' or $ext == 'jpeg' or $ext == 'gif') {

		//EXCLUO A FOTO ANTERIOR
		if ($foto != "sem-foto.jpg") {
			@unlink('../../img/produtos/' . $foto);
		}

		$foto = $nome_img;

		move_uploaded_file($imagem_temp, $caminho);
	} else {
		echo 'Extensão de Imagem não permitida!';
		exit();
	}
}

// Verifica se o produto é novo ou se é uma atualização (edição)
if ($id == "") {
	// Caso seja um novo produto, insere no banco de dados
	$query = $pdo->prepare("INSERT INTO $tabela SET nome = :nome, categoria = '$categoria', valor_compra = :valor_compra, valor_venda = :valor_venda, 
    descricao = :descricao, foto = '$foto', nivel_estoque = '$nivel_estoque'");
} else {
	// Caso seja uma edição de produto, faz o update no banco de dados
	$query = $pdo->prepare("UPDATE $tabela SET nome = :nome, categoria = '$categoria', valor_compra = :valor_compra, valor_venda = :valor_venda, 
    descricao = :descricao, foto = '$foto', nivel_estoque = '$nivel_estoque' WHERE id = '$id'");
}

$query->bindValue(":nome", "$nome");
$query->bindValue(":valor_venda", "$valor_venda");
$query->bindValue(":valor_compra", "$valor_compra");
$query->bindValue(":descricao", "$descricao");
$query->execute();

echo 'Salvo com Sucesso';
