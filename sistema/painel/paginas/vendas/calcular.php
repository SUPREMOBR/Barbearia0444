<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.

// Recebe os dados enviados via POST.
$quantidade = $_POST['quant']; // A quantidade do produto.
$produto = $_POST['produto']; // O ID do produto.

// Realiza uma consulta no banco para obter os detalhes do produto com o ID informado.
$query = $pdo->query("SELECT * FROM produtos where id = '$produto'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
// Obtém o valor de venda do produto a partir do resultado da consulta.
$valor = $resultado[0]['valor_venda'];

// Exibe o valor total, multiplicando o valor de venda pela quantidade.
echo $valor * $quantidade;
