<?php
require_once("../sistema/conexao.php"); // Conecta ao banco de dados.
$servico = $_POST['serv']; // Obtém o ID do serv enviado pelo formulário

// Realiza a consulta no banco de dados para buscar o serviço pelo ID
$query = $pdo->query("SELECT * FROM servicos where id = '$servico' ");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
// Verifica se algum resultado foi encontrado
if (@count($resultado) > 0) {
	// Obtém o nome do serviço do primeiro resultado
	$nome = $resultado[0]['nome'];
}

// Retorna o nome do serviço encontrado
echo $nome;
