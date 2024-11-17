<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'entradas'; // Define o nome da tabela no banco de dados
@session_start(); // Inicia a sessão ou retoma a sessão ativa.
$id_usuario = $_SESSION['id']; // Obtém o ID do usuário logado na sessão.

//Recebe os dados passados via POST (Formulário)
$id_produto = $_POST['id'];  // ID do produto
$estoque = $_POST['estoque'];  // Quantidade atual em estoque do produto
$quantidade_entrada = $_POST['quantidade_entrada'];  // Quantidade de produto que entrou
$motivo_entrada = $_POST['motivo_entrada'];  // Motivo pelo qual a entrada está sendo registrada

// Calcula o novo estoque somando a quantidade que entrou
$novo_estoque = $estoque + $quantidade_entrada;

// Insere um novo registro na tabela 'entradas', registrando a entrada do produto
$query = $pdo->prepare("INSERT INTO $tabela SET produto = '$id_produto', quantidade = '$quantidade_entrada', motivo = :motivo, 
usuario = '$id_usuario', data = curDate()");

$query->bindValue(":motivo", "$motivo_entrada");
$query->execute();

// Atualiza o estoque do produto na tabela 'produtos'
$pdo->query("UPDATE produtos SET estoque = '$novo_estoque' WHERE id = '$id_produto'");

echo 'Salvo com Sucesso';
