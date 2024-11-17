<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.

// Recebe o dado enviado pelo formulário via método POST.
$id_usuario = $_POST['id']; // ID do usuário.

// Remove todas as permissões associadas ao usuário.
$pdo->query("DELETE FROM usuarios_permissoes where usuario = '$id_usuario'");

// Consulta a tabela `acessos` para obter todas as permissões disponíveis, ordenadas pelo campo `id` em ordem crescente.
$query = $pdo->query("SELECT * FROM acessos order by id asc");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado); // Conta o número total de permissões encontradas.
// Verifica se existem permissões disponíveis.
if ($total_registro > 0) {
	// Loop para percorrer cada permissão encontrada na tabela `acessos`.
	for ($i = 0; $i < $total_registro; $i++) {
		foreach ($resultado[$i] as $key => $value) {
		}
		// Extrai os dados da permissão atual.
		$nome = $resultado[$i]['nome']; // Nome da permissão.
		$chave = $resultado[$i]['chave']; // Chave da permissão.
		$id = $resultado[$i]['id']; // ID da permissão.

		// Insere uma nova permissão para o usuário com base no ID da permissão atual.
		$query = $pdo->query("INSERT INTO usuarios_permissoes SET permissao = '$id', usuario = '$id_usuario'");
	}
}
