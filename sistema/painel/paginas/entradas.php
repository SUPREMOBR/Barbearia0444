<?php
@session_start(); // Inicia a sessão para manter informações do usuário durante a navegação.
require_once("verificar.php"); // Inclui o arquivo "verificar.php", para autenticar o usuário ou verificar permissões.
require_once("../conexao.php"); // Conecta ao banco de dados.

// Define o nome da página atual como entradas
$pag = 'entradas';

//verificar se ele tem a permissão de estar nessa página
if (@$entradas == 'ocultar') {
    echo "<script>window.location='../index.php'</script>";
    exit();
}
?>

<div class="bs-example widget-shadow" style="padding:15px" id="listar">

</div>

<script type="text/javascript">
    var pag = "<?= $pag ?>"
</script>
<script src="js/ajax.js"></script>