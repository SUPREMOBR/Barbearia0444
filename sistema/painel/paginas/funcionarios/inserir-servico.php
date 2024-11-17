<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'servicos_funcionarios'; // Define o nome da tabela no banco de dado

// Obtém os valores enviados pelo formulário via POST
$id = $_POST['id'];  // ID do funcionário
$servico = $_POST['servico'];  // ID do serviço que será atribuído ao funcionário
$func = $_POST['id'];  // ID do funcionário, o qual vai receber o serviço

// Verifica se o serviço já foi atribuído ao funcionário
$query = $pdo->query("SELECT * FROM $tabela WHERE funcionario = '$func' AND servico = '$servico'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);  // Conta a quantidade de registros encontrados

// Se o serviço já estiver atribuído ao funcionário, exibe uma mensagem e encerra a execução
if ($total_registro > 0) {
	echo 'Serviço já adicionado ao Funcionário!';  // Mensagem indicando que o serviço já foi atribuído
	exit();  // Encerra a execução do código
}

// Caso o serviço não tenha sido atribuído ao funcionário ainda, insere o novo registro
$pdo->query("INSERT INTO $tabela SET servico = '$servico', funcionario = '$func'");

echo 'Salvo com Sucesso';
