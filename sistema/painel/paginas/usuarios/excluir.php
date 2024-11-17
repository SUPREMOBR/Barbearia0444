<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'usuarios01';

// Recebe o dado enviado pelo formulário via método POST.
$id = $_POST['id']; // ID do usuário q será excluido

// Consulta a tabela para obter os dados do registro com o ID fornecido.
$query = $pdo->query("SELECT * FROM $tabela where id = '$id'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado); // Conta o número de registros encontrados.
$foto = $resultado[0]['foto']; // Obtém o nome da foto associada ao registro (usuário).

// Verifica se o usuário possui uma foto cadastrada e se não é a padrão "sem-foto.jpg".
if ($foto != "sem-foto.jpg") {
	// Remove o arquivo de foto do servidor usando a função `unlink`.
	@unlink('../../img/perfil/' . $foto);
}

// Exclui o registro do banco de dados com base no ID fornecido.
$pdo->query("DELETE from $tabela where id = '$id'");
echo 'Excluído com Sucesso';
