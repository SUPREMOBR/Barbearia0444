<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'dias_bloqueio'; // Define o nome da tabela no banco de dado

@session_start(); // Inicia a sessão ou retoma a sessão ativa
$id_usuario = $_SESSION['id']; // Obtém o ID do usuário logado na sessão.

$data = $_POST['data']; // Recebe a data enviada via POST (formulário).
$func = $_POST['id']; // Recebe o ID do funcionário, também enviado via POST (formulário).

// consulta para verificar se a data já está registrada na tabela `dias_bloqueio`.
$query = $pdo->query("SELECT * FROM $tabela where data = '$data'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
if ($total_registro > 0) {
	// Se já houver um registro com essa data, envia uma mensagem e encerra o script.
	echo 'Data já adicionada!';
	exit();
}
// Caso a data ainda não exista, insere uma nova linha na tabela com a data, o ID do usuário e o ID do funcionário.
$pdo->query("INSERT INTO $tabela SET data = '$data', funcionario = '$id_usuario', usuario = '$func'");

echo 'Salvo com Sucesso';
