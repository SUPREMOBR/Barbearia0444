<?php 
require_once("../../../conexao.php");
$tabela = 'servicos_funcionarios';

$id_funcionario = $_POST['funcionario']; //func

$query = $pdo->query("SELECT * FROM $tabela where funcionario = '$id_funcionario'"); //id func
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
if($total_registro > 0){

echo <<<HTML
	<small><small>
	<table class="table table-hover">
	<thead> 
	<tr> 
	<th>Serviço</th>		
	<th>Excluir</th>
	</tr> 
	</thead> 
	<tbody>	
HTML;

for($i=0; $i < $total_registro; $i++){
	foreach ($resultado[$i] as $key => $value){}
	$id = $resultado[$i]['id'];
	$servico = $resultado[$i]['servico'];
	
	
$query2 = $pdo->query("SELECT * FROM servicos where id = '$servico'");
$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);	
$nome_servico = $resultado2[0]['nome'];

echo <<<HTML
<tr class="">
<td class="">{$nome_servico}</td>
<td>


		<li class="dropdown head-dpdn2" style="display: inline-block;">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><big><i class="fa fa-trash-o text-danger"></i></big></a>

		<ul class="dropdown-menu" style="margin-left:-230px;">
		<li>
		<div class="notification_desc2">
		<p>Confirmar Exclusão? <a href="#" onclick="excluirServico('{$id}', '{$id_funcionario}')"><span class="text-danger">Sim</span></a></p>
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
<small><div align="center" id="mensagem-servico-excluir"></div></small>
</table>
</small></small>
HTML;


}else{
	echo '<small>Não possui nenhum Serviço Cadastrado!</small>';
}

?>


<script type="text/javascript">
	function excluirServico(id, funcionario){ //func
    $.ajax({
        url: 'paginas/' + pag + "/excluir-servico.php",
        method: 'POST',
        data: {id},
        dataType: "text",

        success: function (mensagem) {            
            if (mensagem.trim() == "Excluído com Sucesso") {   
            	            
                listarServicos(funcionario);   //func                
            } else {
                $('#mensagem-servico-excluir').addClass('text-danger')
                $('#mensagem-servico-excluir').text(mensagem)
            }

        },      

    });
}
</script>