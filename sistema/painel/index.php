<?php 
@session_start();
require_once("verificar.php");
require_once("../conexao.php");

$pag_inicial = 'home';
$id_usuario = $_SESSION['id'];

$query = $pdo->query("SELECT * from usuarios01 where id = '$id_usuario'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
if($total_registro > 0){
	$nome_usuario = $resultado[0]['nome'];
	$email_usuario = $resultado[0]['email'];
	$cpf_usuario = $resultado[0]['cpf'];
	$senha_usuario = $resultado[0]['senha'];
	$nivel_usuario = $resultado[0]['nivel'];
	$telefone_usuario = $resultado[0]['telefone'];
	$endereco_usuario = $resultado[0]['endereco'];
	$foto_usuario = $resultado[0]['foto'];
	$atendimento = $resultado[0]['atendimento'];
}

if(@$_GET['pagina'] == ""){
	$pagina = 'home';
}else{
	$pagina = $_GET['pagina'];
}

?>

<!DOCTYPE HTML>
<html>
<head>
	<title><?php echo $nome_sistema ?></title>
	<link rel="icon" type="image/png" href="../img/favicon.ico">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="keywords" content="" />
	<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>

	<!-- Bootstrap Core CSS -->
	<link href="css/bootstrap.css" rel='stylesheet' type='text/css' />

	<!-- Custom CSS -->
	<link href="css/style.css" rel='stylesheet' type='text/css' />

	<!-- font-awesome icons CSS -->
	<link href="css/font-awesome.css" rel="stylesheet"> 
	<!-- //font-awesome icons CSS-->

	<!-- side nav css file -->
	<link href='css/SidebarNav.min.css' media='all' rel='stylesheet' type='text/css'/>
	<!-- //side nav css file -->

	<!-- js-->
	<script src="js/jquery-1.11.1.min.js"></script>
	<script src="js/modernizr.custom.js"></script>

	<!--webfonts-->
	<link href="//fonts.googleapis.com/css?family=PT+Sans:400,400i,700,700i&amp;subset=cyrillic,cyrillic-ext,latin-ext" rel="stylesheet">
	<!--//webfonts--> 

	<!-- chart -->
	<script src="js/Chart.js"></script>
	<!-- //chart -->

	<!-- Metis Menu -->
	<script src="js/metisMenu.min.js"></script>
	<script src="js/custom.js"></script>
	<link href="css/custom.css" rel="stylesheet">
	<!--//Metis Menu -->
	<style>
		#chartdiv {
			width: 100%;
			height: 295px;
		}
	</style>
	<!--pie-chart --><!-- index page sales reviews visitors pie chart -->
	<script src="js/pie-chart.js" type="text/javascript"></script>
	<script type="text/javascript">

		$(document).ready(function () {
			$('#demo-pie-1').pieChart({
				barColor: '#2dde98',
				trackColor: '#eee',
				lineCap: 'round',
				lineWidth: 8,
				onStep: function (from, to, percent) {
					$(this.element).find('.pie-value').text(Math.round(percent) + '%');
				}
			});

			$('#demo-pie-2').pieChart({
				barColor: '#8e43e7',
				trackColor: '#eee',
				lineCap: 'butt',
				lineWidth: 8,
				onStep: function (from, to, percent) {
					$(this.element).find('.pie-value').text(Math.round(percent) + '%');
				}
			});

			$('#demo-pie-3').pieChart({
				barColor: '#ffc168',
				trackColor: '#eee',
				lineCap: 'square',
				lineWidth: 8,
				onStep: function (from, to, percent) {
					$(this.element).find('.pie-value').text(Math.round(percent) + '%');
				}
			});


		});

	</script>
	<!-- //pie-chart --><!-- index page sales reviews visitors pie chart -->


	<link rel="stylesheet" type="text/css" href="DataTables/datatables.min.css"/>
 	<script type="text/javascript" src="DataTables/datatables.min.js"></script>

	
</head> 
<body class="cbp-spmenu-push">
	<div class="main-content">
		<div class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-left" id="cbp-spmenu-s1">
			<!--left-fixed -navigation-->
			<aside class="sidebar-left">
				<nav class="navbar navbar-inverse">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".collapse" aria-expanded="false">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<h1><a class="navbar-brand" href="index.html"><span class="fa fa-area-chart"></span> Sistema<span class="dashboard_text"><?php echo $nome_sistema ?></span></a></h1>
					</div>
					<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
						<ul class="sidebar-menu">
							<li class="header">MENU DE NAVEGAÇÃO</li>


							<li class="treeview <?php echo @$home ?>">
								<a href="index.php">
									<i class="fa fa-dashboard"></i> <span>Home</span>
								</a>
							</li>


							<li class="treeview <?php echo $menu_pessoas ?>">
								<a href="#">
									<i class="fa fa-users"></i>
									<span>Pessoas</span>
									<i class="fa fa-angle-left pull-right"></i>
								</a>
								<ul class="treeview-menu">
								<li class="<?php echo @$usuarios ?>"><a href="index.php?pagina=usuarios"><i class="fa fa-angle-right"></i>Usuários</a></li>

									<li class="<?php echo @$funcionarios ?>"><a href="index.php?pagina=funcionarios"><i class="fa fa-angle-right"></i>Funcionários</a></li>

									<li class="<?php echo @$clientes ?>"><a href="index.php?pagina=clientes"><i class="fa fa-angle-right"></i>Clientes</a></li>

									<li class="<?php echo @$fornecedores ?>"><a href="index.php?pagina=fornecedores"><i class="fa fa-angle-right"></i>Fornecedores</a></li>
								</ul>
							</li>

							<li class="treeview <?php echo $menu_cadastros ?>">
								<a href="#">
									<i class="fa fa-plus"></i>
									<span>Cadastros</span>
									<i class="fa fa-angle-left pull-right"></i>
								</a>
								<ul class="treeview-menu">
								<li class="<?php echo @$servicos ?>"><a href="index.php?pagina=servicos"><i class="fa fa-angle-right"></i>Serviços</a></li>

                                <li class="<?php echo @$cargos ?>"><a href="index.php?pagina=cargos"><i class="fa fa-angle-right"></i>Cargos</a></li>

                                <li class="<?php echo @$categoria_servicos ?>"><a href="index.php?pagina=categoria_servicos"><i class="fa fa-angle-right"></i>Categoria Serviços</a></li>

                                <li class="<?php echo @$grupos ?>"><a href="index.php?pagina=grupos"><i class="fa fa-angle-right"></i>Grupo Acessos</a></li>

                                <li class="<?php echo @$acessos ?>"><a href="index.php?pagina=acessos"><i class="fa fa-angle-right"></i>Acessos</a></li>
								</ul>
							</li>

                            <li class="treeview <?php echo $menu_produtos ?>">
								<a href="#">
									<i class="fa fa-plus"></i>
									<span>Produtos</span>
									<i class="fa fa-angle-left pull-right"></i>
								</a>
								<ul class="treeview-menu">
								    <li class="<?php echo @$produtos ?>"><a href="index.php?pagina=produtos"><i class="fa fa-angle-right"></i>Produtos</a></li>

									<li class="<?php echo @$categoria_produtos ?>"><a href="index.php?pagina=categoria_produtos"><i class="fa fa-angle-right"></i>Categorias</a></li>
									
									<li class="<?php echo @$estoque ?>"><a href="index.php?pagina=estoque"><i class="fa fa-angle-right"></i>Estoque Baixo</a></li>

									<li class="<?php echo @$saidas ?>"><a href="index.php?pagina=saidas"><i class="fa fa-angle-right"></i>Saídas</a></li>

									<li class="<?php echo @$entradas ?>"><a href="index.php?pagina=entradas"><i class="fa fa-angle-right"></i>Entradas</a></li>
								</ul>
							</li>

							<li class="treeview <?php echo $menu_financeiro ?>">
								<a href="#">
									<i class="fa fa-usd"></i>
									<span>Financeiro</span>
									<i class="fa fa-angle-left pull-right"></i>
								</a>
								<ul class="treeview-menu">

								<li class="<?php echo @$vendas ?>"><a href="index.php?pagina=vendas"><i class="fa fa-angle-right"></i>Vendas</a></li>

                                <li class="<?php echo @$compras ?>"><a href="index.php?pagina=compras"><i class="fa fa-angle-right"></i>Compras</a></li>

                                <li class="<?php echo @$pagar ?>"><a href="index.php?pagina=pagar"><i class="fa fa-angle-right"></i>Contas à Pagar</a></li>

                                <li class="<?php echo @$receber ?>"><a href="index.php?pagina=receber"><i class="fa fa-angle-right"></i>Contas à Receber</a></li>	

                                <li class="<?php echo @$comissoes ?>"><a href="index.php?pagina=comissoes"><i class="fa fa-angle-right"></i>Comissões</a></li>																
								
								</ul>
							</li>


                            <li class="treeview <?php echo $menu_agendamentos ?>">
								<a href="#">
									<i class="fa fa-calendar-o"></i>
									<span>Agendamento / Serviço</span>
									<i class="fa fa-angle-left pull-right"></i>
								</a>
								<ul class="treeview-menu">

									<li class="<?php echo @$agendamentos ?>"><a href="index.php?pagina=agendamentos"><i class="fa fa-angle-right"></i>Agendamentos</a></li>

									<li class="<?php echo @$servicos_agenda ?>"><a href="index.php?pagina=servicos_agenda"><i class="fa fa-angle-right"></i>Serviços</a></li>
									
																	
								
								</ul>
							</li>


							<?php if(@$atendimento == 'Sim'){ ?>
							<li class="treeview">
								<a href="index.php?pagina=agenda">
									<i class="fa fa-calendar-o"></i> <span>Minha Agenda</span>
								</a>
							</li>
							<?php } ?>


							<?php if(@$atendimento == 'Sim'){ ?>
							<li class="treeview">
								<a href="index.php?pagina=meus_servicos">
									<i class="fa fa-server"></i> <span>Meus Serviços</span>
								</a>
							</li>
							<?php } ?>


							<li class="treeview">
								<a href="index.php?pagina=minhas_comissoes">
									<i class="fa fa-server"></i> <span>Minhas Comissões</span>
								</a>
							</li>		


                            <li class="treeview">
								<a href="#">
									<i class="fa fa-usd"></i>
									<span>Horário / Dias</span>
									<i class="fa fa-clock-o pull-right"></i>
								</a>
								<ul class="treeview-menu">

									<li><a href="index.php?pagina=horarios"><i class="fa fa-angle-right"></i>Meus Horários</a></li>
									
									<li><a href="index.php?pagina=dias"><i class="fa fa-angle-right"></i>Dias Semana</a></li>


																		
								
								</ul>
							</li>
                           



						</ul>
					</div>
					<!-- /.navbar-collapse -->
				</nav>
			</aside>
		</div>
		<!--left-fixed -navigation-->
		
		<!-- header-starts -->
		<div class="sticky-header header-section ">
			<div class="header-left">
				<!--toggle button start-->
				<button id="showLeftPush"><i class="fa fa-bars"></i></button>
				<!--toggle button end-->
				<div class="profile_details_left"><!--notifications of menu start -->
					<ul class="nofitications-dropdown">


						<?php if($atendimento == 'Sim'){ 

													//totalizando agendamentos dia usuario
						$query = $pdo->query("SELECT * FROM agendamentos where data = curDate() and funcionario = '$id_usuario' and status = 'Agendado'");
						$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
						$total_agendamentos_hoje_usuario_pendentes = @count($resultado);

							?>
						<li class="dropdown head-dpdn">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-bell"></i><span class="badge text-danger"><?php echo $total_agendamentos_hoje_usuario_pendentes ?></span></a>
							<ul class="dropdown-menu">
								<li>
									<div class="notification_header" align="center">
										<h3><?php echo $total_agendamentos_hoje_usuario_pendentes ?> Agendamento Pendente Hoje</h3>
									</div>
								</li>

								<?php 
								for($i=0; $i < @count($resultado); $i++){
									foreach ($resultado[$i] as $key => $value){}
								$id = $resultado[$i]['id'];								
								$cliente = $resultado[$i]['cliente'];
								$hora = $resultado[$i]['hora'];
								$servico = $resultado[$i]['servico'];
								$horaFormatada = date("H:i", strtotime($hora));


									$query2 = $pdo->query("SELECT * FROM servicos where id = '$servico'");
									$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
									if(@count($resultado2) > 0){
										$nome_serv = $resultado2[0]['nome'];
										$valor_serv = $resultado2[0]['valor'];
									}else{
										$nome_serv = 'Não Lançado';
										$valor_serv = '';
									}


									$query2 = $pdo->query("SELECT * FROM clientes where id = '$cliente'");
									$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
									if(@count($resultado2) > 0){
										$nome_cliente = $resultado2[0]['nome'];
									}else{
										$nome_cliente = 'Sem Cliente';
									}
								 ?>
								<li>									
									<div class="notification_desc">
										<p><b><?php echo $horaFormatada ?> </b> - <?php echo $nome_cliente ?> / <?php echo $nome_serv ?></p>
										<p><span></span></p>
									</div>
									<div class="clearfix"></div>	
								</li>
								<?php 
							}
								 ?>
								
								
							
								<li>
									<div class="notification_bottom" style="background: #ffe8e6">
										<a href="index.php?pagina=agenda">Ver Agendamentos</a>
									</div> 
								</li>
							</ul>
						</li>	
					<?php } ?>



						


					</ul>
					<div class="clearfix"> </div>
				</div>
				<!--notification menu end -->
				<div class="clearfix"> </div>
			</div>
			<div class="header-right">
				
				
				
				
				<div class="profile_details">		
					<ul>
						<li class="dropdown profile_details_drop">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
								<div class="profile_img">	
									<span class="prfil-img"><img src="img/perfil/<?php echo $foto_usuario ?>" alt="" width="50" height="50"> </span> 
									<div class="user-name">
										<p><?php echo $nome_usuario ?></p>
										<span><?php echo $nivel_usuario ?></span>
									</div>
									<i class="fa fa-angle-down lnr"></i>
									<i class="fa fa-angle-up lnr"></i>
									<div class="clearfix"></div>	
								</div>	
							</a>
							<ul class="dropdown-menu drp-mnu">
								<li> <a href="" data-toggle="modal" data-target="#modalConfig"><i class="fa fa-cog"></i> Configurações</a> </li> 	
								<li> <a href="" data-toggle="modal" data-target="#modalPerfil"><i class="fa fa-suitcase"></i> Editar Perfil</a> </li> 
								<li> <a href="logout.php"><i class="fa fa-sign-out"></i> Sair</a> </li>
							</ul>
						</li>
					</ul>
				</div>
				<div class="clearfix"> </div>				
			</div>
			<div class="clearfix"> </div>	
		</div>
		<!-- //header-ends -->








		<!-- main content start-->
		<div id="page-wrapper">
			<?php require_once("paginas/".$pagina.'.php') ?>
		</div>









		<!--footer-->
		<div class="footer">
			<p>Apenas um Trabalho ^^</p>		
		</div>
		<!--//footer-->
	</div>


	<!--scrolling js-->
	<script src="js/jquery.nicescroll.js"></script>
	<script src="js/scripts.js"></script>
	<!--//scrolling js-->
	
	<!-- side nav js -->
	<script src='js/SidebarNav.min.js' type='text/javascript'></script>
	<script>
		$('.sidebar-menu').SidebarNav()
	</script>
	<!-- //side nav js -->
	
	
	
	<!-- Bootstrap Core JavaScript -->
	<script src="js/bootstrap.js"> </script>
	<!-- //Bootstrap Core JavaScript -->
	
</body>
</html>


<!-- Mascaras JS -->
<script type="text/javascript" src="js/mascaras.js"></script>

<!-- Ajax para funcionar Mascaras JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script> 

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style type="text/css">
		.select2-selection__rendered {
			line-height: 36px !important;
			font-size:16px !important;
			color:#666666 !important;

		}

		.select2-selection {
			height: 36px !important;
			font-size:16px !important;
			color:#666666 !important;

		}
	</style>  


<!-- Modal Perfil-->
<div class="modal fade" id="modalPerfil" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel">Editar Perfil</h4>
				<button id="btn-fechar-perfil" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
					<span aria-hidden="true" >&times;</span>
				</button>
			</div>
			<form method="post" id="form-perfil">
				<div class="modal-body">

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="exampleInputEmail1">Nome</label>
								<input type="text" class="form-control" id="nome-perfil" name="nome" placeholder="Nome" value="<?php echo $nome_usuario ?>" required>    
							</div> 	
						</div>
						<div class="col-md-6">

							<div class="form-group">
								<label for="exampleInputEmail1">Email</label>
								<input type="email" class="form-control" id="email-perfil" name="email" placeholder="Email" value="<?php echo $email_usuario ?>" required>    
							</div> 	
						</div>
					</div>


					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="exampleInputEmail1">Telefone</label>
								<input type="text" class="form-control" id="telefone-perfil" name="telefone" placeholder="Telefone" value="<?php echo $telefone_usuario ?>" >    
							</div> 	
						</div>
						<div class="col-md-6">
							
							<div class="form-group">
								<label for="exampleInputEmail1">CPF</label>
								<input type="text" class="form-control" id="cpf-perfil" name="cpf" placeholder="CPF" value="<?php echo $cpf_usuario ?>">    
							</div> 	
						</div>
					</div>


					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="exampleInputEmail1">Senha</label>
								<input type="password" class="form-control" id="senha-perfil" name="senha" placeholder="Senha" value="<?php echo $senha_usuario ?>" required>    
							</div> 	
						</div>
						<div class="col-md-6">
							
							<div class="form-group">
								<label for="exampleInputEmail1">Confirmar Senha</label>
								<input type="password" class="form-control" id="conf-senha-perfil" name="conf_senha" placeholder="Confirmar Senha" required>    
							</div> 	
						</div>
					</div>

					<div class="col-md-4">
							<div class="form-group">
								<label for="exampleInputEmail1">Atendimento</label>
								<select class="form-control" name="atendimento" id="atendimento-perfil">
									<option <?php if($atendimento == 'Sim'){ ?> selected <?php } ?> value="Sim">Sim</option>
									<option <?php if($atendimento == 'Não'){ ?> selected <?php } ?> value="Não">Não</option>
								</select>  
							</div> 	
						</div>

					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="exampleInputEmail1">Endereço</label>
								<input type="text" class="form-control" id="endereco-perfil" name="endereco" placeholder="Rua, Número, Bairro" value="<?php echo $endereco_usuario ?>" >    
							</div> 	
						</div>
						
					</div>





						<div class="row">
							<div class="col-md-8">						
								<div class="form-group"> 
									<label>Foto</label> 
									<input class="form-control" type="file" name="foto" onChange="carregarImgPerfil();" id="foto-usu">
								</div>						
							</div>
							<div class="col-md-4">
								<div id="divImg">
									<img src="img/perfil/<?php echo $foto_usuario ?>"  width="80px" id="target-usu">									
								</div>
							</div>

						</div>


					
						<input type="hidden" name="id" value="<?php echo $id_usuario ?>">

					<br>
					<small><div id="mensagem-perfil" align="center"></div></small>
				</div>
				<div class="modal-footer">      
					<button type="submit" class="btn btn-success">Editar Perfil</button>
				</div>
			</form>
		</div>
	</div>
</div>


<!-- Modal Config-->
<div class="modal fade" id="modalConfig" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel">Editar Configurações</h4>
				<button id="btn-fechar-config" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
					<span aria-hidden="true" >&times;</span>
				</button>
			</div>
			<form method="post" id="form-config">
				<div class="modal-body">

					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="exampleInputEmail1">Nome Barbearia</label>
								<input type="text" class="form-control" id="nome_sistema" name="nome_sistema" placeholder="Nome da Barbearia" value="<?php echo $nome_sistema ?>" required>    
							</div> 	
						</div>
						<div class="col-md-4">

							<div class="form-group">
								<label for="exampleInputEmail1">Email Barbearia</label>
								<input type="email" class="form-control" id="email_sistema" name="email_sistema" placeholder="Email" value="<?php echo $email_sistema ?>" required>    
							</div> 	
						</div>

						<div class="col-md-4">

							<div class="form-group">
								<label for="exampleInputEmail1">Whatsapp Barbearia</label>
								<input type="text" class="form-control" id="whatsapp_sistema" name="whatsapp_sistema" placeholder="Whatsapp" value="<?php echo $whatsapp_sistema ?>" required>    
							</div> 	
						</div>
					</div>


					<div class="row">
						
						<div class="col-md-4">

							<div class="form-group">
								<label for="exampleInputEmail1">Tel Fixo Barbearia</label>
								<input type="text" class="form-control" id="telefone_fixo_sistema" name="telefone_fixo_sistema" placeholder="Fixo" value="<?php echo $telefone_fixo_sistema ?>">    
							</div> 	
						</div>
						<div class="col-md-8">
							
							<div class="form-group">
								<label for="exampleInputEmail1">Endereço Barbearia</label>
								<input type="text" class="form-control" id="endereco_sistema" name="endereco_sistema" placeholder="Rua X Numero X Bairro Cidade" value="<?php echo $endereco_sistema ?>">    
							</div> 	
						</div>
					</div>


					<div class="row">
						<div class="col-md-2">
							<div class="form-group">
								<label for="exampleInputEmail1">Tipo Relatório</label>
								<select class="form-control" name="tipo_relatorio" id="tipo_relatorio">
									<option value="PDF" <?php if($tipo_relatorio == 'PDF'){?> selected <?php } ?> >PDF</option>
									<option value="HTML" <?php if($tipo_relatorio == 'HTML'){?> selected <?php } ?> >HTML</option>
								</select>   
							</div> 	
						</div>

						
						<div class="col-md-2">
							<div class="form-group">
								<label for="exampleInputEmail1">Tipo Comissão</label>
								<select class="form-control" name="tipo_comissao" id="tipo_comissao">
									<option value="Porcentagem" <?php if($tipo_comissao == 'Porcentagem'){?> selected <?php } ?> >Porcentagem</option>
									<option value="R$" <?php if($tipo_comissao == 'R$'){?> selected <?php } ?> >R$ Reais</option>
								</select>   
							</div> 	
						</div>
			
					</div>


						<div class="row">

							<div class="col-md-4">						
								<div class="form-group"> 
									<label>Logo (*PNG)</label> 
									<input class="form-control" type="file" name="foto-logo" onChange="carregarImgLogo();" id="foto-logo">
								</div>						
							</div>
							<div class="col-md-2">
								<div id="divImg">
									<img src="../img/<?php echo $logo_sistema ?>"  width="80px" id="target-logo">									
								</div>
							</div>


							<div class="col-md-4">						
								<div class="form-group"> 
									<label>Ícone (*Ico)</label> 
									<input class="form-control" type="file" name="foto-icone" onChange="carregarImgIcone();" id="foto-icone">
								</div>						
							</div>
							<div class="col-md-2">
								<div id="divImg">
									<img src="../img/<?php echo $icone_sistema ?>"  width="20px" id="target-icone">									
								</div>
							</div>

						</div>



						<div class="row">

							<div class="col-md-4">						
								<div class="form-group"> 
									<label>Logo Relatório (*Jpg)</label> 
									<input class="form-control" type="file" name="foto-logo-relatorio" onChange="carregarImgLogoRelatorio();" id="foto-logo-relatorio">
								</div>						
							</div>
							<div class="col-md-2">
								<div id="divImg">
									<img src="../img/<?php echo $logo_relatorio ?>"  width="80px" id="target-logo-relatorio">									
								</div>
							</div>



						</div>
					
						

					<br>
					<small><div id="mensagem-config" align="center"></div></small>
				</div>
				<div class="modal-footer">      
					<button type="submit" class="btn btn-success">Salvar Dados</button>
				</div>
			</form>
		</div>
	</div>
</div>



 <script type="text/javascript">
	$("#form-perfil").submit(function () {

		event.preventDefault();
		var formData = new FormData(this);

		$.ajax({
			url: "editar-perfil.php",
			type: 'POST',
			data: formData,

			success: function (mensagem) {
				$('#mensagem-perfil').text('');
				$('#mensagem-perfil').removeClass()
				if (mensagem.trim() == "Editado com Sucesso") {

					$('#btn-fechar-perfil').click();
					location.reload();			
					
				} else {

					$('#mensagem-perfil').addClass('text-danger')
					$('#mensagem-perfil').text(mensagem)
				}


			},

			cache: false,
			contentType: false,
			processData: false,

		});

	});
</script>


<script type="text/javascript">
	function carregarImgPerfil() {
    var target = document.getElementById('target-usu');
    var file = document.querySelector("#foto-usu").files[0];
    
        var reader = new FileReader();

        reader.onloadend = function () {
            target.src = reader.result;
        };

        if (file) {
            reader.readAsDataURL(file);

        } else {
            target.src = "";
        }
    }
</script>


<script type="text/javascript">
	$("#form-config").submit(function () {

		event.preventDefault();
		var formData = new FormData(this);

		$.ajax({
			url: "editar-config.php",
			type: 'POST',
			data: formData,

			success: function (mensagem) {
				$('#mensagem-config').text('');
				$('#mensagem-config').removeClass()
				if (mensagem.trim() == "Editado com Sucesso") {

					$('#btn-fechar-config').click();
					location.reload();			
					
				} else {

					$('#mensagem-config').addClass('text-danger')
					$('#mensagem-config').text(mensagem)
				}


			},

			cache: false,
			contentType: false,
			processData: false,

		});

	});
</script>


<script type="text/javascript">
	function carregarImgLogo() {
    var target = document.getElementById('target-logo');
    var file = document.querySelector("#foto-logo").files[0];
    
        var reader = new FileReader();

        reader.onloadend = function () {
            target.src = reader.result;
        };

        if (file) {
            reader.readAsDataURL(file);

        } else {
            target.src = "";
        }
    }
</script>


<script type="text/javascript">
	function carregarImgLogoRelatorio() {
    var target = document.getElementById('target-logo-relatorio');
    var file = document.querySelector("#foto-logo-relatorio").files[0];
    
        var reader = new FileReader();

        reader.onloadend = function () {
            target.src = reader.result;
        };

        if (file) {
            reader.readAsDataURL(file);

        } else {
            target.src = "";
        }
    }
</script>


<script type="text/javascript">
	function carregarImgIcone() {
    var target = document.getElementById('target-icone');
    var file = document.querySelector("#foto-icone").files[0];
    
        var reader = new FileReader();

        reader.onloadend = function () {
            target.src = reader.result;
        };

        if (file) {
            reader.readAsDataURL(file);

        } else {
            target.src = "";
        }
    }
</script>