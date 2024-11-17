<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.

// Recebe os dados enviados pelo formulário via método POST.
$id_usuario = $_POST['id_usuario']; // ID do usuário.
$id_permissao = $_POST['id_permissao']; // ID da permissão.

// Consulta a tabela `usuarios_permissoes` para verificar se já existe um registro
$query = $pdo->query("SELECT * FROM usuarios_permissoes where permissao = '$id_permissao' and usuario = '$id_usuario'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
// Verifica se a combinação de permissão e usuário já existe na tabela.
if ($total_registro > 0) {
	// Se já existir, exclui o registro.
	$pdo->query("DELETE FROM usuarios_permissoes where permissao = '$id_permissao' and usuario = '$id_usuario'");
} else {
	// Caso contrário, insere um novo registro com os dados fornecidos.
	$pdo->query("INSERT INTO usuarios_permissoes SET permissao = '$id_permissao', usuario = '$id_usuario'");
}
