<?php 
require_once("../../../conexao.php");
$tabela = 'acessos';

$query = $pdo->query("SELECT * FROM $tabela ORDER BY id desc");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
if($total_registro > 0){
 

echo <<<HTML
	<small>
	<table class="table table-hover" id="tabela">
	<thead> 
	<tr> 
	<th>Nome</th>	
	<th class="esc">Chave</th> 	
	<th class="esc">Grupo</th> 		
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
HTML;

for($i=0; $i < $total_registro; $i++){
	foreach ($resultado[$i] as $key => $value){}
	$id = $resultado[$i]['id'];
	$nome = $resultado[$i]['nome'];
	$chave = $resultado[$i]['chave'];
	$grupo = $resultado[$i]['grupo'];
	
	
	$query2 = $pdo->query("SELECT * FROM grupo_acessos where id = '$grupo'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_registro2 = @count($resultado2);
		if($total_registro2 > 0){
			$nome_categoria = $resultado2[0]['nome'];
		}else{
			$nome_categoria = 'Nenhum';
		}

echo <<<HTML
<tr class="">
<td>
{$nome}
</td>
<td class="esc">{$chave}</td>
<td class="esc">{$nome_categoria}</td>
<td>
        <big><a href="#" onclick="editar('{$id}','{$nome}', '{$chave}', '{$grupo}')" title="Editar Dados"><i class="fa fa-edit text-primary"></i></a></big>


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
	function editar(id, nome, chave, grupo){
		$('#id').val(id);
		$('#nome').val(nome);
		$('#chave').val(chave);
		$('#grupo').val(grupo).change();
				
		$('#titulo_inserir').text('Editar Registro');
		$('#modalform').modal('show');

		$('#target').attr('src','img/servicos/' + foto);
	}

	function limparCampos(){
		$('#id').val('');
		$('#nome').val('');
		$('#chave').val('');		
		$('#grupo').val('');
	}
</script>

