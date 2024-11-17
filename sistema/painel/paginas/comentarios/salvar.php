<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'comentarios'; // Define o nome da tabela no banco de dado

$id = $_POST['id']; // Recebe o ID do comentário a ser editado ou inserido.
$nome = $_POST['nome']; // Recebe o nome do cliente que fez o comentário.
$texto = $_POST['texto']; // Recebe o texto do comentário.
$cliente = @$_POST['cliente']; // Recebe a informação se o comentário é de um cliente específico (usado para definir a ativação ou não do comentário).

// Verifica se o cliente está ativo ou não. Se o valor de cliente for 1, o comentário será desativado, caso contrário, será ativado.
if ($cliente == 1) {
	$ativo = 'Não'; // Desativa o comentário.
} else {
	$ativo = 'Sim'; // Ativa o comentário.
}

// Valida se existe uma foto associada ao comentário.
$query = $pdo->query("SELECT * FROM $tabela where id = '$id'"); // Consulta o banco para verificar a foto do comentário.
$resultado = $query->fetchAll(PDO::FETCH_ASSOC); // Busca o resultado da consulta.
$total_registro = @count($resultado); // Conta o número de registros retornados pela consulta.
if ($total_registro > 0) {
	$foto = $resultado[0]['foto']; // Se o comentário já existe, pega a foto associada a ele.
} else {
	$foto = 'sem-foto.jpg'; // Caso contrário, atribui uma foto padrão.
}

// SCRIPT PARA SUBIR FOTO NO SERVIDOR
$nome_img = date('d-m-Y H:i:s') . '-' . @$_FILES['foto']['name']; // Cria um nome único para a imagem baseado na data e hora atual.
$nome_img = preg_replace('/[ :]+/', '-', $nome_img); // Substitui espaços e dois pontos no nome da imagem por hífens.

$caminho = '../../img/comentarios/' . $nome_img; // Define o caminho onde a foto será salva no servidor.
$imagem_temp = @$_FILES['foto']['tmp_name']; // Obtém o caminho temporário da imagem.

if (@$_FILES['foto']['name'] != "") { // Verifica se uma imagem foi enviada.
	$ext = pathinfo($nome_img, PATHINFO_EXTENSION); // Obtém a extensão do arquivo de imagem.
	if ($ext == 'png' or $ext == 'jpg' or $ext == 'jpeg' or $ext == 'gif') { // Verifica se a imagem tem uma das extensões permitidas.

		// Exclui a foto anterior caso exista, para evitar sobrecarga de arquivos.
		if ($foto != "sem-foto.jpg") {
			@unlink('../../img/comentarios/' . $foto); // Exclui a foto anterior se não for a foto padrão.
		}

		$foto = $nome_img; // Define o novo nome da foto.

		move_uploaded_file($imagem_temp, $caminho); // Move a imagem do diretório temporário para o diretório de destino.
	} else {
		echo 'Extensão de Imagem não permitida!'; // Caso a imagem não tenha uma extensão permitida, exibe erro.
		exit(); // Interrompe a execução do script.
	}
}

// Se o ID for vazio, é um novo comentário, então executa a inserção no banco de dados.
if ($id == "") {
	// Prepara a inserção de um novo comentário.
	$query = $pdo->prepare("INSERT INTO $tabela SET nome = :nome, texto = :texto, ativo = '$ativo', foto = '$foto'");
} else {
	// Prepara a atualização do comentário.
	// Se o ID não for vazio, significa que é uma edição, então executa a atualização do comentário.
	$query = $pdo->prepare("UPDATE $tabela SET nome = :nome, texto = :texto, foto = '$foto' WHERE id = '$id'");
}

// Atribui os valores recebidos via POST para a consulta.
$query->bindValue(":nome", "$nome");
$query->bindValue(":texto", "$texto");
$query->execute(); // Executa a consulta no banco de dados.

echo 'Salvo com Sucesso';
