<?php
@session_start();
require_once("verificar.php");
require_once("../conexao.php");

$pag_inicial = 'home';

$id_usuario = $_SESSION['id'];

$query = $pdo->query("SELECT * from usuarios01 where id = '$id_usuario'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
if ($total_registro > 0) {
	$nome_usuario = $resultado[0]['nome'];
	$email_usuario = $resultado[0]['email'];
	$cpf_usuario = $resultado[0]['cpf'];
	$senha_usuario = $resultado[0]['senha'];
	$nivel_usuario = $resultado[0]['nivel'];
	$telefone_usuario = $resultado[0]['telefone'];
	$endereco_usuario = $resultado[0]['endereco'];
	$foto_usuario = $resultado[0]['foto'];
	$atendimento = $resultado[0]['atendimento'];
	$intervalo_horarios = $resultado[0]['intervalo'];
}

if (@$_SESSION['nivel'] != 'Administrador') {
	require_once("verificar-permissoes.php");
}

if (@$_GET['pag'] == "") {
	$pag = $pag_inicial;
} else {
	$pag = $_GET['pag'];
}

$data_atual = date('Y-m-d');
$mes_atual = Date('m');
$ano_atual = Date('Y');
$data_mes = $ano_atual . "-" . $mes_atual . "-01";
$data_ano = $ano_atual . "-01-01";

$partesInicial = explode('-', $data_atual);
$dataDiaInicial = $partesInicial[2];
$dataMesInicial = $partesInicial[1];

?>

<!DOCTYPE HTML>
<html>

<head>
	<title><?php echo $nome_sistema ?></title>
	<link rel="icon" type="image/png" href="../img/favicon.png">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="keywords" content="" />
	<script type="application/x-javascript">
		addEventListener("load", function() {
			setTimeout(hideURLbar, 0);
		}, false);

		function hideURLbar() {
			window.scrollTo(0, 1);
		}
	</script>

	<!-- Bootstrap Core CSS -->
	<link href="css/bootstrap.css" rel='stylesheet' type='text/css' />

	<!-- Custom CSS -->
	<link href="css/style.css" rel='stylesheet' type='text/css' />

	<!-- font-awesome icons CSS -->
	<link href="css/font-awesome.css" rel="stylesheet">
	<!-- //font-awesome icons CSS-->

	<!-- side nav css file -->
	<link href='css/SidebarNav.min.css' media='all' rel='stylesheet' type='text/css' />
	<!-- //side nav css file -->

	<link rel="stylesheet" href="css/monthly.css">

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
		$(document).ready(function() {
			$('#demo-pie-1').pieChart({
				barColor: '#2dde98',
				trackColor: '#eee',
				lineCap: 'round',
				lineWidth: 8,
				onStep: function(from, to, percent) {
					$(this.element).find('.pie-value').text(Math.round(percent) + '%');
				}
			});

			$('#demo-pie-2').pieChart({
				barColor: '#8e43e7',
				trackColor: '#eee',
				lineCap: 'butt',
				lineWidth: 8,
				onStep: function(from, to, percent) {
					$(this.element).find('.pie-value').text(Math.round(percent) + '%');
				}
			});

			$('#demo-pie-3').pieChart({
				barColor: '#e32424',
				trackColor: '#eee',
				lineCap: 'square',
				lineWidth: 8,
				onStep: function(from, to, percent) {
					$(this.element).find('.pie-value').text(Math.round(percent) + '%');
				}
			});

		});
	</script>
	<!-- //pie-chart --><!-- index page sales reviews visitors pie chart -->

	<link rel="stylesheet" type="text/css" href="DataTables/datatables.min.css" />
	<script type="text/javascript" src="DataTables/datatables.min.js"></script>

</head>

<body class="cbp-spmenu-push">
	<div class="main-content">
		<div class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-left" id="cbp-spmenu-s1">
			<!--left-fixed -navigation-->
			<aside class="sidebar-left" style="overflow: scroll; height:100%; scrollbar-width: thin;">
				<nav class="navbar navbar-inverse">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".collapse" aria-expanded="false" id="showLeftPush2">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<h1><a class="navbar-brand" href="index.php"><span class="fa fa-area-chart"></span> Sistema<span class="dashboard_text"><?php echo $nome_sistema ?></span></a></h1>
					</div>
					<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
						<ul class="sidebar-menu">
							<li class="header">MENU DE NAVEGAÇÃO</li>

							<li class="treeview <?php echo @$home ?>">
								<a href="index.php">
									<i class="fa fa-dashboard"></i> <span>Home</span>
								</a>
							</li>

							<li class="treeview <?php echo @$comanda ?>">
								<a href="comanda">
									<i class="fa fa-file-o"></i> <span>Nova Comanda</span>
								</a>
							</li>

							<li class="treeview <?php echo @$menu_pessoas ?>">
								<a href="#">
									<i class="fa fa-users"></i>
									<span>Pessoas</span>
									<i class="fa fa-angle-left pull-right"></i>
								</a>
								<ul class="treeview-menu">
									<li class="<?php echo @$usuarios ?>"><a href="usuarios"><i class="fa fa-angle-right"></i>Usuários</a></li>
									<li class="<?php echo @$funcionarios ?>"><a href="funcionarios"><i class="fa fa-angle-right"></i>Funcionários</a></li>
									<li class="<?php echo @$clientes ?>"><a href="clientes"><i class="fa fa-angle-right"></i>Clientes</a></li>
									<li class="<?php echo @$fornecedores ?>"><a href="fornecedores"><i class="fa fa-angle-right"></i>Fornecedores</a></li>

								</ul>
							</li>

							<li class="treeview <?php echo @$menu_cadastros ?>">
								<a href="#">
									<i class="fa fa-plus"></i>
									<span>Cadastros</span>
									<i class="fa fa-angle-left pull-right"></i>
								</a>
								<ul class="treeview-menu">
									<li class="<?php echo @$servicos ?>"><a href="servicos"><i class="fa fa-angle-right"></i>Serviços</a></li>

									<li class="<?php echo @$cargos ?>"><a href="cargos"><i class="fa fa-angle-right"></i>Cargos</a></li>

									<li class="<?php echo @$categoria_servicos ?>"><a href="categoria_servicos"><i class="fa fa-angle-right"></i>Categoria Serviços</a></li>

									<li class="<?php echo @$grupos ?>"><a href="grupos"><i class="fa fa-angle-right"></i>Grupo Acessos</a></li>

									<li class="<?php echo @$acessos ?>"><a href="acessos"><i class="fa fa-angle-right"></i>Acessos</a></li>

									<li class="<?php echo @$pagamento ?>"><a href="pagamento"><i class="fa fa-angle-right"></i>Formas de Pagamento</a></li>

									<li class="<?php echo @$dias_bloqueio ?>"><a href="dias_bloqueio"><i class="fa fa-angle-right"></i>Bloqueio de Dias</a></li>

								</ul>
							</li>

							<li class="treeview <?php echo @$menu_produtos ?>">
								<a href="#">
									<i class="fa fa-plus"></i>
									<span>Produtos</span>
									<i class="fa fa-angle-left pull-right"></i>
								</a>
								<ul class="treeview-menu">

									<li class="<?php echo @$produtos ?>"><a href="produtos"><i class="fa fa-angle-right"></i>Produtos</a></li>

									<li class="<?php echo @$categoria_produtos ?>"><a href="categoria_produtos"><i class="fa fa-angle-right"></i>Categorias</a></li>

									<li class="<?php echo @$estoque ?>"><a href="estoque"><i class="fa fa-angle-right"></i>Estoque Baixo</a></li>

									<li class="<?php echo @$saidas ?>"><a href="saidas"><i class="fa fa-angle-right"></i>Saídas</a></li>

									<li class="<?php echo @$entradas ?>"><a href="entradas"><i class="fa fa-angle-right"></i>Entradas</a></li>

								</ul>
							</li>

							<li class="treeview <?php echo @$menu_financeiro ?>">
								<a href="#">
									<i class="fa fa-usd"></i>
									<span>Financeiro</span>
									<i class="fa fa-angle-left pull-right"></i>
								</a>
								<ul class="treeview-menu">

									<li class="<?php echo @$vendas ?>"><a href="vendas"><i class="fa fa-angle-right"></i>Vendas</a></li>

									<li class="<?php echo @$compras ?>"><a href="compras"><i class="fa fa-angle-right"></i>Compras</a></li>

									<li class="<?php echo @$pagar ?>"><a href="pagar"><i class="fa fa-angle-right"></i>Contas à Pagar</a></li>

									<li class="<?php echo @$receber ?>"><a href="receber"><i class="fa fa-angle-right"></i>Contas à Receber</a></li>

									<li class="<?php echo @$comissoes ?>"><a href="comissoes"><i class="fa fa-angle-right"></i>Comissões</a></li>

								</ul>
							</li>

							<li class="treeview <?php echo @$menu_agendamentos ?>">
								<a href="#">
									<i class="fa fa-calendar-o"></i>
									<span>Agendamento / Serviço</span>
									<i class="fa fa-angle-left pull-right"></i>
								</a>
								<ul class="treeview-menu">

									<li class="<?php echo @$agendamentos ?>"><a href="agendamentos"><i class="fa fa-angle-right"></i>Agendamentos</a></li>

									<li class="<?php echo @$servicos_agenda ?>"><a href="servicos_agenda"><i class="fa fa-angle-right"></i>Serviços</a></li>

								</ul>
							</li>

							<li class="treeview <?php echo @$menu_relatorio ?>">
								<a href="#">
									<i class="fa fa-file-pdf-o"></i>
									<span>Relatórios</span>
									<i class="fa fa-angle-left pull-right"></i>
								</a>
								<ul class="treeview-menu">

									<li class="<?php echo @$rel_produtos ?>"><a href="rel/rel_produtos_class.php" target="_blank"><i class="fa fa-angle-right"></i>Relatório de Produtos</a></li>

									<li class="<?php echo @$rel_entradas ?>"><a href="#" data-toggle="modal" data-target="#RelEntradas"><i class="fa fa-angle-right"></i>Entradas / Ganhos</a></li>

									<li class="<?php echo @$rel_saidas ?>"><a href="#" data-toggle="modal" data-target="#RelSaidas"><i class="fa fa-angle-right"></i>Saídas / Despesas</a></li>

									<li class="<?php echo @$rel_comissoes ?>"><a href="#" data-toggle="modal" data-target="#RelComissoes"><i class="fa fa-angle-right"></i>Relatório de Comissões</a></li>

									<li class="<?php echo @$rel_contas ?>"><a href="#" data-toggle="modal" data-target="#RelCon"><i class="fa fa-angle-right"></i>Relatório de Contas</a></li>

									<li class="<?php echo @$rel_servicos ?>"><a href="#" data-toggle="modal" data-target="#RelServicos"><i class="fa fa-angle-right"></i>Relatório de Serviços</a></li>

									<li class="<?php echo @$rel_lucro ?>"><a href="#" data-toggle="modal" data-target="#RelLucro"><i class="fa fa-angle-right"></i>Demonstrativo de Lucro</a></li>

								</ul>
							</li>

							<li class="treeview" style="display:none">
								<a href="#">
									<i class="fa fa-whatsapp"></i>
									<span>Whatsapp - (Menuia)</span>
									<i class="fa fa-angle-left pull-right"></i>
								</a>
								<ul class="treeview-menu">

									<li class="treeview <?= @$whatsapp ?>"><a href="whatsapp"><i class="fa fa-cog"></i>Configurações</a></li>

								</ul>
							</li>

							<li class="treeview <?= @$marketings ?>">
								<a href="marketingp">
									<i class="fa fa-paper-plane"></i><span>Campanha Marketing</span>
								</a>
							</li>

							<li class="treeview <?php echo @$calendario ?>">
								<a href="calendario">
									<i class="fa fa-calendar-o"></i> <span>Calendário</span>
								</a>
							</li>

							<li class="treeview <?php echo @$menu_site ?>">
								<a href="#">
									<i class="fa fa-globe"></i>
									<span>Dados do Site</span>
									<i class="fa fa-angle-left pull-right"></i>
								</a>
								<ul class="treeview-menu">

									<li class="<?php echo @$textos_index ?>"><a href="textos_index"><i class="fa fa-angle-right"></i>Textos Index</a></li>

									<li class="<?php echo @$comentarios ?>"><a href="comentarios"><i class="fa fa-angle-right"></i>Comentários</a></li>

								</ul>
							</li>

							<?php if (@$atendimento == 'Sim') { ?>
								<li class="treeview">
									<a href="agenda">
										<i class="fa fa-calendar-o"></i> <span>Minha Agenda</span>
									</a>
								</li>

								<li class="treeview">
									<a href="meus_servicos">
										<i class="fa fa-server"></i> <span>Meus Serviços</span>
									</a>
								</li>

								<li class="treeview">
									<a href="minhas_comissoes">
										<i class="fa fa-server"></i> <span>Minhas Comissões</span>
									</a>
								</li>

								<li class="treeview">
									<a href="#">
										<i class="fa fa-usd"></i>
										<span>Meus Horário / Dias</span>
										<i class="fa fa-clock-o pull-right"></i>
									</a>
									<ul class="treeview-menu">

										<li><a href="dias"><i class="fa fa-angle-right"></i>Horários / Dias</a></li>

										<li><a href="servicos_funcionarios"><i class="fa fa-angle-right"></i>Lançar Serviços</a></li>

										<li><a href="dias_bloqueio_func"><i class="fa fa-angle-right"></i>Bloqueio de Dias</a></li>

									</ul>
								</li>

							<?php } ?>

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
				<button id="showLeftPush" data-toggle="collapse" data-target=".collapse"><i class="fa fa-bars"></i></button>
				<!--toggle button end-->
				<div class="profile_details_left"><!--notifications of menu start -->
					<ul class="nofitications-dropdown">

						<?php if ($atendimento == 'Sim') {

							//totalizando agendamentos dia usuario
							$query = $pdo->query("SELECT * FROM agendamentos where data = curDate() and funcionario = '$id_usuario' and 
							status = 'Agendado'");
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
									for ($i = 0; $i < @count($resultado); $i++) {
										foreach ($resultado[$i] as $key => $value) {
										}
										$id = $resultado[$i]['id'];
										$cliente = $resultado[$i]['cliente'];
										$hora = $resultado[$i]['hora'];
										$servico = $resultado[$i]['servico'];
										$horaF = date("H:i", strtotime($hora));

										$query2 = $pdo->query("SELECT * FROM servicos where id = '$servico'");
										$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
										if (@count($resultado2) > 0) {
											$nome_serv = $resultado2[0]['nome'];
											$valor_serv = $resultado2[0]['valor'];
										} else {
											$nome_serv = 'Não Lançado';
											$valor_serv = '';
										}

										$query2 = $pdo->query("SELECT * FROM clientes where id = '$cliente'");
										$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
										if (@count($resultado2) > 0) {
											$nome_cliente = $resultado2[0]['nome'];
										} else {
											$nome_cliente = 'Sem Cliente';
										}
									?>
										<li>
											<div class="notification_desc">
												<p><b><?php echo $horaF ?> </b> - <?php echo $nome_cliente ?> / <?php echo $nome_serv ?></p>
												<p><span></span></p>
											</div>
											<div class="clearfix"></div>
										</li>
									<?php
									}
									?>

									<li>
										<div class="notification_bottom" style="background: #ffe8e6">
											<a href="agenda">Ver Agendamentos</a>
										</div>
									</li>
								</ul>
							</li>
						<?php } ?>

						<?php if (@$comentarios == '') {

							//totalizando aniversariantes do dia
							$query = $pdo->query("SELECT * FROM comentarios where ativo != 'Sim'");
							$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
							$total_comentarios = @count($resultado);

						?>
							<li class="dropdown head-dpdn">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-comment" style="color:#FFF"></i><span class="badge" style="background: #22168a"><?php echo $total_comentarios ?></span></a>
								<ul class="dropdown-menu">
									<li>
										<div class="notification_header" align="center">
											<h3><?php echo $total_comentarios ?> Depoimentos Pendente</h3>
										</div>
									</li>

									<?php
									for ($i = 0; $i < @count($resultado); $i++) {
										foreach ($resultado[$i] as $key => $value) {
										}

										$nome = $resultado[$i]['nome'];

									?>
										<li>
											<div class="notification_desc">
												<p><b>Cliente: <?php echo $nome ?> </b> </p>
												<p><span></span></p>
											</div>
											<div class="clearfix"></div>
										</li>
									<?php
									}
									?>

									<li>
										<div class="notification_bottom" style="background: #d8d4fc">
											<a href="comentarios">Ver Depoimentos</a>
										</div>
									</li>
								</ul>
							</li>
						<?php } ?>

					</ul>
					<div class="clearfix"> </div>
				</div>

			</div>
			<div class="header-right">

				<div class="profile_details">
					<ul>
						<li class="dropdown profile_details_drop">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
								<div class="profile_img">
									<span class="prfil-img"><img src="img/perfil/<?php echo $foto_usuario ?>" alt="" width="50" height="50"> </span>
									<div class="user-name esc">
										<p><?php echo $nome_usuario ?></p>
										<span><?php echo $nivel_usuario ?></span>
									</div>
									<i class="fa fa-angle-down lnr"></i>
									<i class="fa fa-angle-up lnr"></i>
									<div class="clearfix"></div>
								</div>
							</a>
							<ul class="dropdown-menu drp-mnu">
								<?php if (@$configuracoes == '') { ?>
									<li> <a href="configuracoes"><i class="fa fa-cog"></i> Configurações</a> </li>
								<?php } ?>

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
			<?php require_once("paginas/" . $pag . '.php') ?>
		</div>

		<!--footer-->
		<div class="footer">
			<p>.....<a href="" target="_blank"></a></p>
		</div>
		<!--//footer-->
	</div>

	<!-- Classie --><!-- for toggle left push menu script -->
	<script src="js/classie.js"></script>
	<script>
		var menuLeft = document.getElementById('cbp-spmenu-s1'),
			showLeftPush = document.getElementById('showLeftPush'),
			body = document.body;

		showLeftPush.onclick = function() {
			classie.toggle(this, 'active');
			classie.toggle(body, 'cbp-spmenu-push-toright');
			classie.toggle(menuLeft, 'cbp-spmenu-open');
			disableOther('showLeftPush');
		};

		function disableOther(button) {
			if (button !== 'showLeftPush') {
				classie.toggle(showLeftPush, 'disabled');
			}
		}

		showLeftPush2 = document.getElementById('showLeftPush2'),

			showLeftPush2.onclick = function() {
				classie.toggle(this, 'active');
				classie.toggle(body, 'cbp-spmenu-push-toright');
				classie.toggle(menuLeft, 'cbp-spmenu-open');
				disableOther2('showLeftPush2');
			};

		function disableOther2(button) {
			if (button !== 'showLeftPush2') {
				classie.toggle(showLeftPush2, 'disabled');
			}
		}
	</script>
	<!-- //Classie --><!-- //for toggle left push menu script -->

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
		font-size: 16px !important;
		color: #666666 !important;

	}

	.select2-selection {
		height: 36px !important;
		font-size: 16px !important;
		color: #666666 !important;

	}
</style>

<!-- Modal Perfil-->
<div class="modal fade" id="modalPerfil" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel">Editar Perfil</h4>
				<button id="btn-fechar-perfil" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
					<span aria-hidden="true">&times;</span>
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
								<input type="text" class="form-control" id="telefone-perfil" name="telefone" placeholder="Telefone" value="<?php echo $telefone_usuario ?>">
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
						<div class="col-md-4">
							<div class="form-group">
								<label for="exampleInputEmail1">Senha</label>
								<input type="password" class="form-control" id="senha-perfil" name="senha" placeholder="Senha" value="<?php echo $senha_usuario ?>" required>
							</div>
						</div>
						<div class="col-md-4">

							<div class="form-group">
								<label for="exampleInputEmail1">Confirmar Senha</label>
								<input type="password" class="form-control" id="conf-senha-perfil" name="conf_senha" placeholder="Confirmar Senha" required>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label for="exampleInputEmail1">Atendimento</label>
								<select class="form-control" name="atendimento" id="atendimento-perfil">
									<option <?php if ($atendimento == 'Sim') { ?> selected <?php } ?> value="Sim">Sim</option>
									<option <?php if ($atendimento == 'Não') { ?> selected <?php } ?> value="Não">Não</option>
								</select>
							</div>
						</div>

					</div>

					<div class="row">
						<div class="col-md-8">
							<div class="form-group">
								<label for="exampleInputEmail1">Endereço</label>
								<input type="text" class="form-control" id="endereco-perfil" name="endereco" placeholder="Rua X Número 1 Bairro xxx" value="<?php echo $endereco_usuario ?>">
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label for="exampleInputEmail1">Intervalo Minutos</label>
								<input type="number" class="form-control" id="intervalo_perfil" name="intervalo" placeholder="Intervalo Horários" value="<?php echo $intervalo_horarios ?>" required>
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
								<img src="img/perfil/<?php echo $foto_usuario ?>" width="80px" id="target-usu">
							</div>
						</div>

					</div>

					<input type="hidden" name="id" value="<?php echo $id_usuario ?>">

					<br>
					<small>
						<div id="mensagem-perfil" align="center"></div>
					</small>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">Editar Perfil</button>
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
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

		</div>
	</div>
</div>

<!-- Modal Rel Entradas / Ganhos -->
<div class="modal fade" id="RelEntradas" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel">Relatório de Ganhos
					<small>(
						<a href="#" onclick="datas('1980-01-01', 'tudo-Ent', 'Ent')">
							<span style="color:#000" id="tudo-Ent">Tudo</span>
						</a> /
						<a href="#" onclick="datas('<?php echo $data_atual ?>', 'hoje-Ent', 'Ent')">
							<span id="hoje-Ent">Hoje</span>
						</a> /
						<a href="#" onclick="datas('<?php echo $data_mes ?>', 'mes-Ent', 'Ent')">
							<span style="color:#000" id="mes-Ent">Mês</span>
						</a> /
						<a href="#" onclick="datas('<?php echo $data_ano ?>', 'ano-Ent', 'Ent')">
							<span style="color:#000" id="ano-Ent">Ano</span>
						</a>
						)</small>

				</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method="post" action="rel/rel_entradas_class.php" target="_blank">
				<div class="modal-body">

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Data Inicial</label>
								<input type="date" class="form-control" name="dataInicial" id="dataInicialRel-Ent" value="<?php echo date('Y-m-d') ?>" required>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Data Final</label>
								<input type="date" class="form-control" name="dataFinal" id="dataFinalRel-Ent" value="<?php echo date('Y-m-d') ?>" required>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label>Entradas / Ganhos</label>
								<select class="form-control sel13" name="filtro" style="width:100%;">
									<option value="">Todas</option>
									<option value="Venda">Vendas</option>
									<option value="Serviço">Serviços</option>
									<option value="Conta">Demais Ganhos</option>

								</select>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label>Selecionar Cliente</label>
								<select class="form-control selcli" name="cliente" style="width:100%;">
									<option value="">Todos</option>
									<?php
									$query = $pdo->query("SELECT * FROM clientes");
									$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
									$total_registro = @count($resultado);
									if ($total_registro > 0) {
										for ($i = 0; $i < $total_registro; $i++) {
											foreach ($resultado[$i] as $key => $value) {
											}
											echo '<option value="' . $resultado[$i]['id'] . '">' . $resultado[$i]['nome'] . '</option>';
										}
									}
									?>

								</select>
							</div>
						</div>

					</div>

				</div>

				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">Gerar Relatório</button>
				</div>
			</form>

		</div>
	</div>
</div>

<!-- Modal Rel Saidas / Despesas -->
<div class="modal fade" id="RelSaidas" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel">Relatório de Saídas
					<small>(
						<a href="#" onclick="datas('1980-01-01', 'tudo-Saida', 'Saida')">
							<span style="color:#000" id="tudo-Saida">Tudo</span>
						</a> /
						<a href="#" onclick="datas('<?php echo $data_atual ?>', 'hoje-Saida', 'Saida')">
							<span id="hoje-Saida">Hoje</span>
						</a> /
						<a href="#" onclick="datas('<?php echo $data_mes ?>', 'mes-Saida', 'Saida')">
							<span style="color:#000" id="mes-Saida">Mês</span>
						</a> /
						<a href="#" onclick="datas('<?php echo $data_ano ?>', 'ano-Saida', 'Saida')">
							<span style="color:#000" id="ano-Saida">Ano</span>
						</a>
						)</small>

				</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method="post" action="rel/rel_saidas_class.php" target="_blank">
				<div class="modal-body">

					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label>Data Inicial</label>
								<input type="date" class="form-control" name="dataInicial" id="dataInicialRel-Saida" value="<?php echo date('Y-m-d') ?>" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Data Final</label>
								<input type="date" class="form-control" name="dataFinal" id="dataFinalRel-Saida" value="<?php echo date('Y-m-d') ?>" required>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label>Saídas / Despesas</label>
								<select class="form-control sel13" name="filtro" style="width:100%;">
									<option value="">Todas</option>
									<option value="Conta">Despesas</option>
									<option value="Comissão">Comissões</option>
									<option value="Compra">Compras</option>

								</select>
							</div>
						</div>

					</div>

				</div>

				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">Gerar Relatório</button>
				</div>
			</form>

		</div>
	</div>
</div>

<!-- Modal Rel Comissoes -->
<div class="modal fade" id="RelComissoes" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel">Relatório de Comissões
					<small>(
						<a href="#" onclick="datas('1980-01-01', 'tudo-Com', 'Com')">
							<span style="color:#000" id="tudo-Com">Tudo</span>
						</a> /
						<a href="#" onclick="datas('<?php echo $data_atual ?>', 'hoje-Com', 'Com')">
							<span id="hoje-Com">Hoje</span>
						</a> /
						<a href="#" onclick="datas('<?php echo $data_mes ?>', 'mes-Com', 'Com')">
							<span style="color:#000" id="mes-Com">Mês</span>
						</a> /
						<a href="#" onclick="datas('<?php echo $data_ano ?>', 'ano-Com', 'Com')">
							<span style="color:#000" id="ano-Com">Ano</span>
						</a>
						)</small>

				</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method="post" action="rel/rel_comissoes_class.php" target="_blank">
				<div class="modal-body">

					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label>Data Inicial</label>
								<input type="date" class="form-control" name="dataInicial" id="dataInicialRel-Com" value="<?php echo date('Y-m-d') ?>" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Data Final</label>
								<input type="date" class="form-control" name="dataFinal" id="dataFinalRel-Com" value="<?php echo date('Y-m-d') ?>" required>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label>Pago</label>
								<select class="form-control " name="pago" style="width:100%;">
									<option value="">Todas</option>
									<option value="Sim">Somente Pagas</option>
									<option value="Não">Pendentes</option>

								</select>
							</div>
						</div>

					</div>

					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Funcionário</label>
								<select class="form-control sel15" name="funcionario" style="width:100%;">
									<option value="">Todos</option>
									<?php
									$query = $pdo->query("SELECT * FROM usuarios01 where atendimento = 'Sim' ORDER BY id desc");
									$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
									$total_registro = @count($resultado);
									if ($total_registro > 0) {
										for ($i = 0; $i < $total_registro; $i++) {
											foreach ($resultado[$i] as $key => $value) {
											}
											echo '<option value="' . $resultado[$i]['id'] . '">' . $resultado[$i]['nome'] . '</option>';
										}
									} ?>

								</select>
							</div>
						</div>
					</div>

				</div>

				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">Gerar Relatório</button>
				</div>
			</form>

		</div>
	</div>
</div>

<!-- Modal Rel Contas -->
<div class="modal fade" id="RelCon" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel">Relatório de Contas
					<small>(
						<a href="#" onclick="datas('1980-01-01', 'tudo-Con', 'Con')">
							<span style="color:#000" id="tudo-Con">Tudo</span>
						</a> /
						<a href="#" onclick="datas('<?php echo $data_atual ?>', 'hoje-Con', 'Con')">
							<span id="hoje-Con">Hoje</span>
						</a> /
						<a href="#" onclick="datas('<?php echo $data_mes ?>', 'mes-Con', 'Con')">
							<span style="color:#000" id="mes-Con">Mês</span>
						</a> /
						<a href="#" onclick="datas('<?php echo $data_ano ?>', 'ano-Con', 'Con')">
							<span style="color:#000" id="ano-Con">Ano</span>
						</a>
						)</small>

				</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method="post" action="rel/rel_contas_class.php" target="_blank">
				<div class="modal-body">

					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label>Data Inicial</label>
								<input type="date" class="form-control" name="dataInicial" id="dataInicialRel-Con" value="<?php echo date('Y-m-d') ?>" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Data Final</label>
								<input type="date" class="form-control" name="dataFinal" id="dataFinalRel-Con" value="<?php echo date('Y-m-d') ?>" required>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label>Pago</label>
								<select class="form-control" name="pago" style="width:100%;">
									<option value="">Todas</option>
									<option value="Sim">Somente Pagas</option>
									<option value="Não">Pendentes</option>

								</select>
							</div>
						</div>

					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Pagar / Receber</label>
								<select class="form-control sel13" name="tabela" style="width:100%;">
									<option value="pagar">Contas à Pagar</option>
									<option value="receber">Contas à Receber</option>

								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Consultar Por</label>
								<select class="form-control sel13" name="busca" style="width:100%;">
									<option value="data_vencimento">Data de Vencimento</option>
									<option value="data_pagamento">Data de Pagamento</option>

								</select>
							</div>
						</div>

					</div>

				</div>

				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">Gerar Relatório</button>
				</div>
			</form>

		</div>
	</div>
</div>

<!-- Modal Rel Lucro -->
<div class="modal fade" id="RelLucro" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel">Demonstrativo de Lucro
					<small>(
						<a href="#" onclick="datas('1980-01-01', 'tudo-Lucro', 'Lucro')">
							<span style="color:#000" id="tudo-Lucro">Tudo</span>
						</a> /
						<a href="#" onclick="datas('<?php echo $data_atual ?>', 'hoje-Lucro', 'Lucro')">
							<span id="hoje-Lucro">Hoje</span>
						</a> /
						<a href="#" onclick="datas('<?php echo $data_mes ?>', 'mes-Lucro', 'Lucro')">
							<span style="color:#000" id="mes-Lucro">Mês</span>
						</a> /
						<a href="#" onclick="datas('<?php echo $data_ano ?>', 'ano-Lucro', 'Lucro')">
							<span style="color:#000" id="ano-Lucro">Ano</span>
						</a>
						)</small>

				</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method="post" action="rel/rel_lucro_class.php" target="_blank">
				<div class="modal-body">

					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label>Data Inicial</label>
								<input type="date" class="form-control" name="dataInicial" id="dataInicialRel-Lucro" value="<?php echo date('Y-m-d') ?>" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Data Final</label>
								<input type="date" class="form-control" name="dataFinal" id="dataFinalRel-Lucro" value="<?php echo date('Y-m-d') ?>" required>
							</div>
						</div>

					</div>

				</div>

				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">Gerar Relatório</button>
				</div>
			</form>

		</div>
	</div>
</div>


</div>

<!-- Modal Rel Entradas / Ganhos -->
<div class="modal fade" id="RelServicos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel">Relatório de Serviços
					<small>(
						<a href="#" onclick="datas('1980-01-01', 'tudo-Ser', 'Ser')">
							<span style="color:#000" id="tudo-Ser">Tudo</span>
						</a> /
						<a href="#" onclick="datas('<?php echo $data_atual ?>', 'hoje-Ser', 'Ser')">
							<span id="hoje-Ser">Hoje</span>
						</a> /
						<a href="#" onclick="datas('<?php echo $data_mes ?>', 'mes-Ser', 'Ser')">
							<span style="color:#000" id="mes-Ser">Mês</span>
						</a> /
						<a href="#" onclick="datas('<?php echo $data_ano ?>', 'ano-Ser', 'Ser')">
							<span style="color:#000" id="ano-Ser">Ano</span>
						</a>
						)</small>

				</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method="post" action="rel/rel_servicos_class.php" target="_blank">
				<div class="modal-body">

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Data Inicial</label>
								<input type="date" class="form-control" name="dataInicial" id="dataInicialRel-Ser" value="<?php echo date('Y-m-d') ?>" required>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Data Final</label>
								<input type="date" class="form-control" name="dataFinal" id="dataFinalRel-Ser" value="<?php echo date('Y-m-d') ?>" required>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Forma de Pagamento</label>
								<select class="form-control" name="pgto" style="width:100%;">
									<option value="">Selecionar Pagamento</option>
									<?php
									$query = $pdo->query("SELECT * FROM formas_pagamento");
									$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
									$total_registro = @count($resultado);
									if ($total_registro > 0) {
										for ($i = 0; $i < $total_registro; $i++) {
											foreach ($resultado[$i] as $key => $value) {
											}
											echo '<option value="' . $resultado[$i]['nome'] . '">' . $resultado[$i]['nome'] . '</option>';
										}
									}
									?>

								</select>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label>Selecionar Serviço</label>
								<select class="form-control" name="servico" style="width:100%;">
									<option value="">Selecionar Serviço</option>
									<?php
									$query = $pdo->query("SELECT * FROM servicos");
									$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
									$total_registro = @count($resultado);
									if ($total_registro > 0) {
										for ($i = 0; $i < $total_registro; $i++) {
											foreach ($resultado[$i] as $key => $value) {
											}
											echo '<option value="' . $resultado[$i]['id'] . '">' . $resultado[$i]['nome'] . '</option>';
										}
									}
									?>

								</select>
							</div>
						</div>

					</div>

				</div>

				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">Gerar Relatório</button>
				</div>
			</form>

		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$('.sel15').select2({
			dropdownParent: $('#RelComissoes')
		});

		$('.selcli').select2({
			dropdownParent: $('#RelEntradas')
		});
	});
</script>

<script type="text/javascript">
	$("#form-perfil").submit(function() {

		event.preventDefault();
		var formData = new FormData(this);

		$.ajax({
			url: "editar-perfil.php",
			type: 'POST',
			data: formData,

			success: function(mensagem) {
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

		reader.onloadend = function() {
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
	$("#form-config").submit(function() {

		event.preventDefault();
		var formData = new FormData(this);

		$.ajax({
			url: "editar-config.php",
			type: 'POST',
			data: formData,

			success: function(mensagem) {
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

		reader.onloadend = function() {
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
	function carregarImgLogoRel() {
		var target = document.getElementById('target-logo-relatorio');
		var file = document.querySelector("#foto-logo-relatorio").files[0];

		var reader = new FileReader();

		reader.onloadend = function() {
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

		reader.onloadend = function() {
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
	function carregarImgIconeSite() {
		var target = document.getElementById('target-icone-site');
		var file = document.querySelector("#foto-icone-site").files[0];

		var reader = new FileReader();

		reader.onloadend = function() {
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
	function carregarImgBannerIndex() {
		var target = document.getElementById('target-banner-index');
		var file = document.querySelector("#foto-banner-index").files[0];

		var reader = new FileReader();

		reader.onloadend = function() {
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
	function carregarImgSobre() {
		var target = document.getElementById('target-sobre');
		var file = document.querySelector("#foto-sobre").files[0];

		var reader = new FileReader();

		reader.onloadend = function() {
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
	function datas(data, id, campo) {

		var data_atual = "<?= $data_atual ?>";
		var separarData = data_atual.split("-");
		var mes = separarData[1];
		var ano = separarData[0];

		var separarId = id.split("-");

		if (separarId[0] == 'tudo') {
			data_atual = '2100-12-31';
		}

		if (separarId[0] == 'ano') {
			data_atual = ano + '-12-31';
		}

		if (separarId[0] == 'mes') {
			if (mes == 1 || mes == 3 || mes == 5 || mes == 7 || mes == 8 || mes == 10 || mes == 12) {
				data_atual = ano + '-' + mes + '-31';
			} else if (mes == 4 || mes == 6 || mes == 9 || mes == 11) {
				data_atual = ano + '-' + mes + '-30';
			} else {
				data_atual = ano + '-' + mes + '-28';
			}

		}

		$('#dataInicialRel-' + campo).val(data);
		$('#dataFinalRel-' + campo).val(data_atual);

		document.getElementById('hoje-' + campo).style.color = "#000";
		document.getElementById('mes-' + campo).style.color = "#000";
		document.getElementById(id).style.color = "blue";
		document.getElementById('tudo-' + campo).style.color = "#000";
		document.getElementById('ano-' + campo).style.color = "#000";
		document.getElementById(id).style.color = "blue";
	}
</script>