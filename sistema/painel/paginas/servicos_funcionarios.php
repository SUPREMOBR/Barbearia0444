<?php 
@session_start();
require_once("verificar.php");
require_once("../conexao.php");

$pag = 'funcionarios';
?>


<form id="form-dias">
				<div class="row">
					<div class="col-md-2">						
						<div class="form-group">
							<label for="exampleInputEmail1">Dia</label>
							<select class="form-control sel3" id="servico" name="servico" style="width:100%;" required> 

                                    <?php 
                                    $query = $pdo->query("SELECT * FROM servicos ORDER BY nome asc");
                                    $resultado = $query->fetchAll(PDO::FETCH_ASSOC);
                                    $total_registro = @count($resultado);
                                    if($total_registro > 0){
                                        for($i=0; $i < $total_registro; $i++){
                                            foreach ($resultado[$i] as $key => $value){}
                                                echo '<option value="'.$resultado[$i]['id'].'">'.$resultado[$i]['nome'].'</option>';
                                        }
                                    }
                                    ?>


                                </select>        
						</div> 	
					</div>

					<div class="col-md-4">						
						<button type="submit" class="btn btn-primary" style="margin-top:20px">Salvar</button>
					</div>

					<input type="hidden" name="id" id="id_dias" value="<?php echo $id_usuario ?>">

				</div>
				</form>

<small><div id="mensagem-dias"></div></small>

<big>
<div class="bs-example widget-shadow" style="padding:15px" id="listar-dias">
	
</div>
</big>



<script type="text/javascript">var pag = "<?=$pag?>"</script>


<script type="text/javascript">
	$(document).ready(function() {
		var funcionario = $("#id_dias").val();  //func
		listarServicos(funcionario)  //func
	});
</script>



<script type="text/javascript">
	

$("#form-dias").submit(function () {

	var funcionario = $("#id_dias").val(); //func
    event.preventDefault();
    var formData = new FormData(this);

    $.ajax({
        url: 'paginas/' + pag + "/inserir-servico.php",
        type: 'POST',
        data: formData,

        success: function (mensagem) {
            $('#mensagem-dias').text('');
            $('#mensagem-dias').removeClass()
            if (mensagem.trim() == "Salvo com Sucesso") {

                //$('#btn-fechar-horarios').click();
                listarServicos(funcionario); //func         

            } else {

                $('#mensagem-dias').addClass('text-danger')
                $('#mensagem-dias').text(mensagem)
            }


        },

        cache: false,
        contentType: false,
        processData: false,

    });

});


</script>


<script type="text/javascript">
	function listarServicos(funcionario){  //func
		
    $.ajax({
        url: 'paginas/' + pag + "/listar-servicos.php",
        method: 'POST',
        data: {funcionario},  //func
        dataType: "html",

        success:function(result){
            $("#listar-dias").html(result);
            $('#mensagem-dias-excluir').text('');
        }
    });
}

</script>