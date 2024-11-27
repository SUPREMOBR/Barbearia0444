<?php
require_once("../../sistema/conexao.php");

$tabela = 'horarios';

$id_func = $_POST['id_usuario'];

$query = $pdo->query("SELECT * FROM $tabela where funcionario = '$id_func' ORDER BY horario asc");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($resultado);

if ($total_registro > 0) {

  for ($i = 0; $i < $total_registro; $i++) {
    $id = $resultado[$i]['id'];
    $horario = $resultado[$i]['horario'];
    $horarioF = date("H:i", strtotime($horario));
    $data = $resultado[$i]['data'];
    $dataF = implode('/', array_reverse(explode('-', $data)));

    if ($data != "") {
      $temp = ' <span style="color:red"><small>(Temporário Data: ' . $dataF . '</small></span>';
    } else {
      $temp = '';
    }

    echo '<li>';
    echo '<a href="#" class="item-link item-content" onclick="editarHorarios(' . $id . ', \'' . $horarioF . '\')">';
    echo ' <div class="item-inner">';
    echo ' <div class="item-title" style="font-size:11px">';
    echo $horarioF . $temp;
    echo '</div>';
    echo '</div>';
    echo '</a>';
    echo '</li>';
  }
} else {
  echo '<br><small><small><div align="center">Não encontramos nenhum registro!</div></small></small>';
}
