<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'marketing'; // Define o nome da tabela no banco de dado

$id = $_POST['id']; // Obtém o valor do ID enviado pelo formulário via POST

// Consulta o registro com o ID especificado para obter informações sobre o arquivo associado (foto)
$query = $pdo->query("SELECT * FROM $tabela where id = '$id'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($resultado);
$foto = $resultado[0]['arquivo']; // Nome do arquivo de imagem associado ao registro

// Verifica se o arquivo de foto existe e não é a imagem padrão "sem-foto.jpg". Se existir, exclui-o do servidor.
if ($foto != "sem-foto.jpg") {
	@unlink('../../img/marketing/' . $foto);
}

// Exclui um arquivo de áudio relacionado ao marketing, caso exista (se a variável $audio estivesse definida corretamente).
@unlink('../../img/marketing/' . $audio);

// Exclui o registro do banco de dados com o ID especificado
$pdo->query("DELETE from $tabela where id = '$id'");
echo 'Excluído com Sucesso';
