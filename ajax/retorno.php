<?php
header('Access-Control-Allow-Origin: *'); // Define o cabeçalho HTTP para permitir requisições de qualquer origem (CORS).

require_once("../sistema/conexao.php"); // Conecta ao banco de dados.

// Recebe os dados enviados no corpo da requisição HTTP em formato JSON e os decodifica para um objeto PHP.
$dados = json_decode(file_get_contents('php://input'), false);

$id = $dados->id; // Obtém o ID do agendamento do objeto $dados.
$status = $dados->status; // Obtém o status do agendamento do objeto $dados.

if ($status == '1') {
  // Se o status for '1', atualiza o agendamento para "Confirmado".
  $pdo->query("UPDATE agendamentos SET status = 'Confirmado' where id = '$id'");
} else {
  // Caso contrário, remove o agendamento e os registros relacionados.
  $pdo->query("DELETE FROM agendamentos where id = '$id'");
  $pdo->query("DELETE FROM horarios_agd where agendamento = '$id'");
}
