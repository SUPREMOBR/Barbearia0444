<?php
$tabela = 'contratos'; // Define o nome da tabela no banco de dado
require_once("../../../conexao.php"); // Conecta ao banco de dados.

$contrato = $_POST['contrato']; // Conteúdo do contrato recebido via POST
$id = $_POST['id']; // Recebe o ID do cliente via POST.

// Consulta para verificar se já existe um contrato associado ao cliente no banco de dados
$query = $pdo->query("SELECT * FROM $tabela where cliente = '$id' order by id desc");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);

if ($total_registro == 0) { // Caso não exista um contrato para o cliente
	// Insere um novo contrato
	$query = $pdo->prepare("INSERT into $tabela SET cliente = '$id', texto = :texto, data = curDate()");
} else {
	// Atualiza o contrato existente
	$query = $pdo->prepare("UPDATE $tabela SET texto = :texto WHERE cliente = '$id' ");
}

$query->bindValue(":texto", "$contrato");
$query->execute();

echo 'Salvo com Sucesso';
