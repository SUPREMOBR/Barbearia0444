<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'pagar'; // Define o nome da tabela no banco de dados

$id = $_POST['id']; // Obtém o valor do ID que foi passado pelo formulário

// Consulta para obter os dados do pagamento com o ID especificado
$query = $pdo->query("SELECT * FROM $tabela where id = '$id'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
$foto = $resultado[0]['foto']; // Obtém o nome do arquivo da foto
$produto = $resultado[0]['produto']; // Obtém o ID do produto associado ao pagamento
$quantidade = $resultado[0]['quantidade']; // Obtém a quantidade de produto associada

if ($foto != "sem-foto.jpg") {
	@unlink('../../img/contas/' . $foto);
}

// Se um produto estiver relacionado ao pagamento, o estoque do produto é atualizado
if ($produto > 0) {
	$query = $pdo->query("SELECT * FROM produtos where id = '$produto'");
	$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
	$total_registro = @count($resultado);
	$estoque = $resultado[0]['estoque']; // Obtém o estoque atual do produto

	// Atualiza o estoque do produto, subtraindo a quantidade do pagamento excluído
	$total_estoque = $estoque - $quantidade;

	// Exclui o registro da tabela 'pagar' com o ID especificado
	$pdo->query("UPDATE produtos SET estoque = '$total_estoque', valor_compra = '0' WHERE id = '$produto'");
}

$pdo->query("DELETE from $tabela where id = '$id'");
echo 'Excluído com Sucesso';
