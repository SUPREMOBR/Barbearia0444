<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'saidas'; // Define o nome da tabela no banco de dados
@session_start();  // Inicia a sessão ou retoma a sessão ativa.
$id_usuario = $_SESSION['id']; // Obtém o ID do usuário logado na sessão.

//Recebe os dados passados via POST (Formulário)
$id_produto = $_POST['id']; // ID do produto
$estoque = $_POST['estoque']; // Quantidade atual em estoque do produto
$quantidade_saida = $_POST['quantidade_saida']; // Quantidade de produto que saiu
$motivo_saida = $_POST['motivo_saida']; // Motivo pelo qual a saida está sendo registrada

// Calcula o novo estoque subtraindo a quantidade que saiu
$novo_estoque = $estoque - $quantidade_saida;

// Insere um novo registro na tabela 'saidas', registrando a saida do produto
$query = $pdo->prepare("INSERT INTO $tabela SET produto = '$id_produto', quantidade = '$quantidade_saida', motivo = :motivo, 
usuario = '$id_usuario', data = curDate()");

$query->bindValue(":motivo", "$motivo_saida");
$query->execute();

// Atualiza o estoque do produto na tabela 'produtos'
$pdo->query("UPDATE produtos SET estoque = '$novo_estoque' WHERE id = '$id_produto'");

echo 'Salvo com Sucesso';
