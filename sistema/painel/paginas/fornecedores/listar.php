<?php 
require_once("../../../conexao.php");
$tabela = 'fornecedores';

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
	<th class="esc">Telefone</th> 	
	<th class="esc">Cadastro</th> 	
	
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
HTML;

for($i=0; $i < $total_registro; $i++){
	foreach ($resultado[$i] as $key => $value){}
	$id = $resultado[$i]['id'];
	$nome = $resultado[$i]['nome'];	
	
	$data_cadastro = $resultado[$i]['data_cadastro'];	
	$telefone = $resultado[$i]['telefone'];
	$endereco = $resultado[$i]['endereco'];
	
	
	$data_cadastroFormatada = implode('/', array_reverse(explode('-', $data_cadastro)));
	
	$whats = '55'.preg_replace('/[ ()-]+/' , '' , $telefone);
	
	echo <<<HTML
	<tr class="">
	<td>{$nome}</td>
	<td class="esc">{$telefone}</td>
	<td class="esc">{$data_cadastroFormatada}</td>
	<td>
	<big><a href="#" onclick="editar('{$id}','{$nome}', '{$telefone}', '{$endereco}' )" title="Editar Dados"><i class="fa fa-edit text-primary"></i></a></big>

    <big><a href="#" onclick="mostrar('{$nome}', '{$telefone}','{$data_cadastroFormatada}', '{$endereco}')" title="Ver Dados"><i class="fa fa-info-circle text-secondary"></i></a></big>




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

        <big><a href="http://api.whatsapp.com/send?1=pt_BR&phone=$whats&text=" target="_blank" title="Abrir Whatsapp"><i class="fa fa-whatsapp verde"></i></a></big>


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
	function editar(id, nome, telefone, endereco){
		$('#id').val(id);
		$('#nome').val(nome);		
		$('#telefone').val(telefone);		
		$('#endereco').val(endereco);

		
		$('#titulo_inserir').text('Editar Registro');
		$('#modalform').modal('show');
		
	}

	function limparCampos(){
		$('#id').val('');
		$('#nome').val('');
		$('#telefone').val('');
		$('#endereco').val('');
	}
</script>



<script type="text/javascript">
	function mostrar(nome, telefone,data_cadastro, endereco){

		$('#nome_dados').text(nome);		
		$('#data_cadastro_dados').text(data_cadastro);
		
		$('#telefone_dados').text(telefone);
		$('#endereco_dados').text(endereco);		

		$('#modalDados').modal('show');
	}
</script>