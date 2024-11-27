<?php
require_once("../../sistema/conexao.php");

$tabela = 'dias';

$id_func = $_POST['id_usuario'];

$query = $pdo->query("SELECT * FROM $tabela where funcionario = '$id_func' ORDER BY id asc");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);

if ($total_registro > 0) {

  for ($i = 0; $i < $total_registro; $i++) {
    $id = $resultado[$i]['id'];
    $dia = $resultado[$i]['dia'];

    echo '<li>';
    echo '<a href="#" class="item-link item-content" onclick="editarDias(' . $id . ', \'' . $dia . '\')">';
    echo ' <div class="item-inner">';
    echo ' <div class="item-title" style="font-size:11px">';
    echo $dia;
    echo '</div>';
    echo '</div>';
    echo '</a>';
    echo '</li>';
  }
} else {
  echo '<br><small><small><div align="center">Não encontramos nenhum registro!</div></small></small>';
}
