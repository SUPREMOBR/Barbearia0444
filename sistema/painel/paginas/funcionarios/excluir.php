<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'usuarios'; // Define o nome da tabela no banco de dado

$id = $_POST['id']; // Obtém o valor do ID que foi passado pelo formulário

// Executa uma consulta para buscar o registro do usuário com o ID informado
$query = $pdo->query("SELECT * FROM $tabela where id = '$id'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
$foto = $resultado[0]['foto']; // Obtém o nome da foto do usuário,

// Verifica se o usuário tem uma foto diferente da padrão "sem-foto.jpg"
if ($foto != "sem-foto.jpg") {
	@unlink('../../img/perfil/' . $foto);
}

// Executa a query para excluir o usuário da tabela "usuarios"
$pdo->query("DELETE from $tabela where id = '$id'");

// Executa a query para excluir o registro relacionado ao usuário na tabela "servicos_funcionarios"
// Exclui qualquer vínculo do usuário com os serviços
$pdo->query("DELETE from servicos_funcionarios where funcionario = '$id'");

echo 'Excluído com Sucesso';
