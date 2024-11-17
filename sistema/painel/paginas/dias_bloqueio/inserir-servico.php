<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'dias_bloqueio'; // Define o nome da tabela no banco de dado

$data = $_POST['data'];   // Obtém a data enviada pelo formulário
$func = $_POST['id'];     // Obtém o ID do usuário (funcionário)

# Consulta para verificar se ja existe a data na tabela (dias_bloqueio) antes de Inserir uma nova data
$query = $pdo->query("SELECT * FROM $tabela where data = '$data'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
if ($total_registro > 0) {
	echo 'Data já adicionada!';
	exit();
}
# Se a data não foi encontrada, então insere uma nova linha na tabela 'dias_bloqueio' com a data e o ID do funcionário
$pdo->query("INSERT INTO $tabela SET data = '$data', funcionario = '0', usuario = '$func'");

echo 'Salvo com Sucesso';
