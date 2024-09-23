<?php 
require_once("../../../conexao.php");
$tabela = 'saidas';

$query = $pdo->query("SELECT * FROM $tabela ORDER BY id desc");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
if($total_registro > 0){

	echo <<<HTML
	<small>
	<table class="table table-hover" id="tabela">
	<thead> 
	<tr> 
	<th>Produto</th>	
	<th class="">Quantidade</th> 	
	<th class="esc">Motivo</th> 	
	<th class="esc">Usuário Lançou</th> 
	<th class="esc">Data</th>	
	
	</tr> 
	</thead> 
	<tbody>	
HTML;

	


for($i=0; $i < $total_registro; $i++){
	foreach ($resultado[$i] as $key => $value){}
	$id = $resultado[$i]['id'];
	$produto = $resultado[$i]['produto'];	
	$quantidade = $resultado[$i]['quantidade'];
	$motivo = $resultado[$i]['motivo'];
	$usuario = $resultado[$i]['usuario'];
	$data = $resultado[$i]['data'];
	

	$query2 = $pdo->query("SELECT * FROM produtos where id = '$produto'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_registro2 = @count($resultado2);
		if($total_registro2 > 0){
			$nome_produto = $resultado2[0]['nome'];
			$foto_produto = $resultado2[0]['foto'];
		}else{
			$nome_produto = 'Sem Referência!';
			$foto_produto = 'sem-foto.jpg';
		}
    
	$query2 = $pdo->query("SELECT * FROM usuarios01 where id = '$usuarios'");
	$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	$total_registro2 = @count($resultado2);
	if($total_registro2 > 0){
		$nome_usuario = $resultado2[0]['nome'];

	}else{
		$nome_usuario= 'Sem Referência!';
		
	}

	$dataFormatada = implode('/', array_reverse(explode('-', $data)));
		
	echo <<<HTML
	<tr class="">
	<td>
	<img src="img/produtos/{$foto_produto}" width="27px" class="mr-2">
	{$nome_produto}
	</td>
	<td class="">{$quantidade}</td>
	<td class="esc">{$motivo}</td>
	<td class="esc"> {$nome_usuario}</td>
	<td class="esc">{$dataFormatada}</td>
	
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

