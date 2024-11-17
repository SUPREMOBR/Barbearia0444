<?php
require_once("../../../conexao.php");
$tabela = 'receber';

$id = $_POST['id'];

$query = $pdo->query("SELECT * FROM receber where id = '$id'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$produto = $resultado[0]['produto'];
$quantidade = $resultado[0]['quantidade'];

$query = $pdo->query("SELECT * FROM produtos where id = '$produto'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$estoque = $resultado[0]['estoque'];

//atualizar estoque do produto
$total_estoque = $estoque + $quantidade;
$pdo->query("UPDATE produtos SET estoque = '$total_estoque' WHERE id = '$produto'");

$pdo->query("DELETE from $tabela where id = '$id'");
