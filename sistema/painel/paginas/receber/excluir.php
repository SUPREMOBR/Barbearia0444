<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'receber'; // Define o nome da tabela no banco de dados

$id = $_POST['id']; //Recebe o ID passado via POST (Formulário)

// Consulta os dados do registro que será excluído
$query = $pdo->query("SELECT * FROM $tabela where id = '$id'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
// Obtém a foto associada e o produto relacionado ao registro
$foto = $resultado[0]['foto'];
$produto = $resultado[0]['produto'];
$quantidade = $resultado[0]['quantidade'];

if ($foto != "sem-foto.jpg") {
	@unlink('../../img/contas/' . $foto);
}
// Se o registro tem produto associado (produto > 0)
if ($produto > 0) {
	// Consulta os dados do produto
	$query = $pdo->query("SELECT * FROM produtos where id = '$produto'");
	$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
	$total_registro = @count($resultado);
	$estoque = $resultado[0]['estoque'];  // Obtém o estoque atual do produto
	// Atualiza o estoque do produto com a quantidade associada ao registro
	$total_estoque = $estoque + $quantidade;
	// Atualiza a tabela 'produtos' com o novo estoque e zera o valor de compra
	$pdo->query("UPDATE produtos SET estoque = '$total_estoque', valor_compra = '0' WHERE id = '$produto'");
}
// Exclui o registro da tabela 'receber'
$pdo->query("DELETE from $tabela where id = '$id'");
echo 'Excluído com Sucesso';
