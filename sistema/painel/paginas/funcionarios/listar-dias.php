<?php
require_once("../../../conexao.php");
$tabela = 'dias';

$id_funcionario = $_POST['funcionario']; //func

$query = $pdo->query("SELECT * FROM $tabela where funcionario = '$id_funcionario' ORDER BY id asc"); //id_func
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
if ($total_registro > 0) {

    echo <<<HTML
	<small><small>
	<table class="table table-hover">
	<thead> 
	<tr> 
	<th>Dia</th>	
	<th>Jornada</th>	
	<th>Almoço</th>		
	<th>Excluir</th>
	</tr> 
	</thead> 
	<tbody>	
HTML;


    for ($i = 0; $i < $total_registro; $i++) {
        foreach ($resultado[$i] as $key => $value) {
        }
        $id = $resultado[$i]['id'];
        $dia = $resultado[$i]['dia'];
        $inicio = $resultado[$i]['inicio'];
        $final = $resultado[$i]['final'];
        $inicio_almoco = $resultado[$i]['inicio_almoco'];
        $final_almoco = $resultado[$i]['final_almoco'];

        if ($inicio_almoco == '00:00:00') {
            $inicio_almoco = 'Não Lançado';
        }

        if ($final_almoco == '00:00:00') {
            $final_almoco = 'Não Lançado';
        }



        echo <<<HTML
    <tr class="">
    <td class="">{$dia}</td>
    <td class="">{$inicio} / {$final}</td>
    <td class="">{$inicio_almoco} / {$final_almoco}</td>
    <td>
    
    
            <li class="dropdown head-dpdn2" style="display: inline-block;">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><big><i class="fa fa-trash-o text-danger"></i></big></a>
    
            <ul class="dropdown-menu" style="margin-left:-230px;">
            <li>
            <div class="notification_desc2">
            <p>Confirmar Exclusão? <a href="#" onclick="excluirDias('{$id}')"><span class="text-danger">Sim</span></a></p>
            </div>
            </li>										
            </ul>
            </li>
    
            <big><a href="#" onclick="editar('{$id}','{$dia}', '{$inicio}', '{$final}', '{$inicio_almoco}', '{$final_almoco}')" title="Editar Dados"><i class="fa fa-edit text-primary"></i></a></big>

    
            </td>
    </tr>
    HTML;
    }

    echo <<<HTML
    </tbody>
    <small><div align="center" id="mensagem-dias-excluir"></div></small>
    </table>
    </small></small>
    HTML;
} else {
    echo '<small>Não possui nenhum Dia Cadastrado!</small>';
}

?>


<script type="text/javascript">
    function excluirDias(id) {
        $.ajax({
            url: 'paginas/' + pag + "/excluir-dias.php",
            method: 'POST',
            data: {
                id
            },
            dataType: "text",

            success: function(mensagem) {
                if (mensagem.trim() == "Excluído com Sucesso") {
                    var funcionario = $("#id_dias").val(); //var func
                    listarDias(funcionario);  //func
                } else {
                    $('#mensagem-dias-excluir').addClass('text-danger')
                    $('#mensagem-dias-excluir').text(mensagem)
                }

            },

        });
    }

    function editar(id, dia, inicio, final, inicio_almoco, final_almoco){
		$('#id_d').val(id);
		$('#dias').val(dia).change();
		$('#inicio').val(inicio);
		$('#final').val(final);
		$('#inicio_almoco').val(inicio_almoco);
		$('#final_almoco').val(final_almoco);	
	}


	function limparCampos(){
		$('#id_d').val('');
		
	}
</script>