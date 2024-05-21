<?php 
require_once("../../../conexao.php");
$tabela = 'horarios';

$id_funcionario = $_POST['funcionario'];

$query = $pdo->query("SELECT * FROM $tabela where funcionario = '$id_funcionario' ORDER BY horario asc");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
if($total_registro > 0){

	echo <<<HTML
	<small><small>
	<table class="table table-hover">
	<thead> 
	<tr> 
	<th>Horário</th>		
	<th>Excluir</th>
	</tr> 
	</thead> 
	<tbody>	
HTML;


for($i=0; $i < $total_registro; $i++){
	foreach ($resultado[$i] as $key => $value){}
	$id = $resultado[$i]['id'];
	$horario = $resultado[$i]['horario'];
	$horarioFormatado = date("H:i", strtotime($horario));

	

    echo <<<HTML
    <tr class="">
    <td class="">{$horarioFormatado}</td>
    <td>
    
    
            <li class="dropdown head-dpdn2" style="display: inline-block;">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><big><i class="fa fa-trash-o text-danger"></i></big></a>
    
            <ul class="dropdown-menu" style="margin-left:-230px;">
            <li>
            <div class="notification_desc2">
            <p>Confirmar Exclusão? <a href="#" onclick="excluirHorarios('{$id}')"><span class="text-danger">Sim</span></a></p>
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
    <small><div align="center" id="mensagem-horario-excluir"></div></small>
    </table>
    </small></small>
    HTML;
    
    
    }else{
        echo '<small>Não possui nenhum Horário Cadastrado!</small>';
    }
    
    ?>
    
    
    <script type="text/javascript">
        function excluirHorarios(id){
        $.ajax({
            url: 'paginas/' + pag + "/excluir-horarios.php",
            method: 'POST',
            data: {id},
            dataType: "text",
    
            success: function (mensagem) {            
                if (mensagem.trim() == "Excluído com Sucesso") {   
                    var funcionario = $("#id_horarios").val();             
                    listarHorarios(funcionario);                
                } else {
                    $('#mensagem-horario-excluir').addClass('text-danger')
                    $('#mensagem-horario-excluir').text(mensagem)
                }
    
            },      
    
        });
    }
    </script>