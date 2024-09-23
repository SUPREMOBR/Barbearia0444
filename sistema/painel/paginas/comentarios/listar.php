<?php 
require_once("../../../conexao.php");
$tabela = 'comentarios';

$query = $pdo->query("SELECT * FROM $tabela ORDER BY id desc");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
if($total_registro > 0){

echo <<<HTML
	<small>
	<table class="table table-hover" id="tabela">
	<thead> 
	<tr> 
	<th>Cliente</th>	
	<th class="esc">Texto</th> 		
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
HTML;

for($i=0; $i < $total_registro; $i++){
	foreach ($resultado[$i] as $key => $value){}
	$id = $resultado[$i]['id'];	
	$foto = $resultado[$i]['foto'];
	$texto = $resultado[$i]['texto'];
	$nome = $resultado[$i]['nome'];
	$ativo = $resultado[$i]['ativo'];

	$textoFormatado = mb_strimwidth($texto, 0, 100, "...");

	if($ativo == 'Sim'){
			$icone = 'fa-check-square';
			$titulo_link = 'Desativar Item';
			$acao = 'Não';
			$classe_linha = '';
		}else{
			$icone = 'fa-square-o';
			$titulo_link = 'Ativar Item';
			$acao = 'Sim';
			$classe_linha = 'text-muted';
		}


echo <<<HTML
<tr class="{$classe_linha}">
<td>
<img src="img/comentarios/{$foto}" width="27px" class="mr-2">
{$nome}
</td>
<td class="esc">{$textoFormatado}</td>
<td>
		<big><a href="#" onclick="editar('{$id}','{$nome}', '{$texto}', '{$foto}')" title="Editar Dados"><i class="fa fa-edit text-primary"></i></a></big>

		<big><a href="#" onclick="mostrar('{$nome}', '{$texto}', '{$foto}')" title="Ver Dados"><i class="fa fa-info-circle text-secondary"></i></a></big>



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



		<big><a href="#" onclick="ativar('{$id}', '{$acao}')" title="{$titulo_link}"><i class="fa {$icone} text-success"></i></a></big>


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
	function editar(id, nome, texto, foto){
		$('#id').val(id);
		$('#nome').val(nome);
		$('#texto').val(texto);
					
		$('#titulo_inserir').text('Editar Registro');
		$('#modalform').modal('show');
		$('#foto').val('');
		$('#target').attr('src','img/comentarios/' + foto);
	}

	function limparCampos(){
		$('#id').val('');
		$('#nome').val('');
		$('#texto').val('');
		
		$('#foto').val('');
		$('#target').attr('src','img/comentarios/sem-foto.jpg');
	}
</script>



<script type="text/javascript">
	function mostrar(nome, texto, foto){

		$('#nome_dados').text(nome);
		$('#texto_dados').text(texto);
				
		$('#target_mostrar').attr('src','img/comentarios/' + foto);

		$('#modalDados').modal('show');
	}
</script>