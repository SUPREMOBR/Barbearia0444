<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.

// Recebe o dado enviado pelo formulário via método POST.
$id_usuario = $_POST['id']; // ID do usuário 

// Remove todas as permissões associadas ao usuário especificado.
$pdo->query("DELETE FROM usuarios_permissoes where usuario = '$id_usuario'");
