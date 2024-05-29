<?php 
@session_start();
require_once("verificar.php");
require_once("../conexao.php");

$pag = 'categoria_produtos';

?>

<div class="">      
	<a class="btn btn-success" onclick="inserir()" class="btn btn-success btn-flat btn-pri"><i class="fa fa-plus" aria-hidden="true"></i> Nova Categoria</a>
</div>

<div class="bs-example widget-shadow" style="padding:15px" id="listar">
	
</div>




<!-- Modal Inserir-->
<div class="modal fade" id="modalform" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><span id="titulo_inserir"></span></h4>
				<button id="btn-fechar" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
					<span aria-hidden="true" >&times;</span>
				</button>
			</div>
			
			<form id="form">
			<div class="modal-body">

					<div class="row">
						<div class="col-md-9">
							<div class="form-group">
								
								<input type="text" class="form-control" id="nome" name="nome" placeholder="Digite o Nome" required>    
							</div> 	
						</div>
						<div class="col-md-3">
							<button type="submit" class="btn btn-success">Salvar</button>
						
						</div>
					</div>

					
						<input type="hidden" name="id" id="id">

					<br>
					<small><div id="mensagem" align="center"></div></small>
				</div>
			</form>

							
		</div>
	</div>
</div>



<script type="text/javascript">var pag = "<?=$pag?>"</script>
<script src="js/ajax.js"></script>