<?php 
require_once("../../../conexao.php");
$tabela = 'textos_index';

$query = $pdo->query("SELECT * FROM $tabela ORDER BY id desc");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
if($total_registro > 0){

echo <<<HTML
	<small>
	<table class="table table-hover" id="tabela">
	<thead> 
	<tr> 
	<th>Título</th>	
	<th>Descrição</th>		
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
HTML;

for($i=0; $i < $total_registro; $i++){
	foreach ($resultado[$i] as $key => $value){}
	$id = $resultado[$i]['id'];
	$titulo = $resultado[$i]['titulo'];
	$descricao = $resultado[$i]['descricao'];

	$descricaoFormatada = mb_strimwidth($descricao, 0, 100, "...");
	
		
	
echo <<<HTML
<tr class="">
<td>{$titulo}</td>
<td>{$descricaoFormatada}</td>
<td>
		<big><a href="#" onclick="editar('{$id}','{$titulo}','{$descricao}')" title="Editar Dados"><i class="fa fa-edit text-primary"></i></a></big>

		
		<li class="dropdown head-dpdn2" style="display: inline-block;">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><big><i class="fa fa-trash-o text-danger"></i></big></a>

		<ul class="dropdown-menu" style="margin-left:-230px;">
		<li>
		<div class="notification_desc2">
		<p>Confirmar Exclusão? <a href="#" onclick="excluir('{$id}')"><span class="text-danger">Sim</span></a></p>
		</div>
		</li>										
		</ul>
		</li>


		</td>
</tr>
HTML;

}

echo <<<HTML
</tbody>
<small><div align="center" id="mensagem-excluir"></div></small>
</table>
</small>
HTML;


}else{
	echo '<small>Não possui nenhum registro Cadastrado!</small>';
}

?>


<script type="text/javascript">
	$(document).ready( function () {
    $('#tabela').DataTable({
    		"ordering": false,
			"stateSave": true
    	});
    $('#tabela_filter label input').focus();
} );
</script>


<script type="text/javascript">
	function editar(id, titulo, descricao){
		$('#id').val(id);
		$('#titulo').val(titulo);
		$('#descricao').val(descricao);
		$('#titulo_inserir').text('Editar Registro');
		$('#modalform').modal('show');
	}

	function limparCampos(){
		$('#titulo').val('');
		$('#descricao').val('');
		$('#id').val('');
	}
</script>