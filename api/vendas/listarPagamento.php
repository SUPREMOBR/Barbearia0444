<?php
require_once("../../sistema/conexao.php");


$query = $pdo->query("SELECT * FROM formas_pagamento");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
if ($total_registro > 0) {
	for ($i = 0; $i < $total_registro; $i++) {
		foreach ($resultado[$i] as $key => $value) {
		}
		echo '<option value="' . $resultado[$i]['nome'] . '">' . $resultado[$i]['nome'] . '</option>';
	}
} else {
	echo '<option value="0">Cadastre um Cargo</option>';
}
