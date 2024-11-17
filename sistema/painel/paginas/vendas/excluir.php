<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'receber'; // Define o nome da tabela no banco de dados

$id = $_POST['id']; //Recebe o ID passado via POST (Formulário)

// Realiza uma consulta no banco de dados para obter os dados da conta com o ID informado.
$query = $pdo->query("SELECT * FROM $tabela where id = '$id'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
$foto = $resultado[0]['foto']; // Obtém o nome da foto associada à conta.
$produto = $resultado[0]['produto']; // Obtém o ID do produto relacionado à conta.
$quantidade = $resultado[0]['quantidade']; // Obtém a quantidade do produto relacionada à conta.

if ($foto != "sem-foto.jpg") {
	@unlink('../../img/contas/' . $foto);
}

// Realiza uma consulta para obter os dados do produto relacionado à conta.
$query = $pdo->query("SELECT * FROM produtos where id = '$produto'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
$estoque = $resultado[0]['estoque']; // Obtém a quantidade de estoque do produto.

// Atualiza o estoque do produto. A quantidade do produto na tabela 'produtos' é aumentada pela quantidade retirada da conta.
$total_estoque = $estoque + $quantidade; // Calcula o novo estoque somando a quantidade retirada.
// Atualiza o estoque do produto no banco de dados.
$pdo->query("UPDATE produtos SET estoque = '$total_estoque' WHERE id = '$produto'");

// Exclui o registro da tabela 'receber' (a conta) com o ID fornecido.
$pdo->query("DELETE from $tabela where id = '$id'");
echo 'Excluído com Sucesso';
