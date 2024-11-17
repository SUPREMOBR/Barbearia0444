<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'pagar'; // Define o nome da tabela no banco de dado

$id = $_POST['id']; // Obtém o valor do ID que foi passado pelo formulário

// Consulta o banco de dados para pegar as informações do pagamento com o ID fornecido
$query = $pdo->query("SELECT * FROM $tabela where id = '$id'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
$foto = $resultado[0]['foto']; // Obtém o nome do arquivo da foto associada ao pagamento
$produto = $resultado[0]['produto']; // Obtém o ID do produto associado ao pagamento
$quantidade = $resultado[0]['quantidade']; // Obtém a quantidade do produto associada ao pagamento

// Verifica se a foto existe e não é uma foto padrão ('sem-foto.jpg'), se sim, deleta a imagem do servidor
if ($foto != "sem-foto.jpg") {
	@unlink('../../img/contas/' . $foto); // Deleta o arquivo de foto da pasta 'img/contas'
}

// Consulta o banco de dados para obter informações do produto relacionado ao pagamento
$query = $pdo->query("SELECT * FROM produtos where id = '$produto'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
$estoque = $resultado[0]['estoque']; // Obtém a quantidade de estoque atual do produto

// Calcula o novo estoque após a exclusão da quantidade associada ao pagamento
$total_estoque = $estoque - $quantidade;
// Atualiza o estoque do produto no banco de dados com o novo valor
$pdo->query("UPDATE produtos SET estoque = '$total_estoque', valor_compra = '0' WHERE id = '$produto'");
// Exclui o registro do pagamento da tabela 'pagar'
$pdo->query("DELETE from $tabela where id = '$id'");
echo 'Excluído com Sucesso';
