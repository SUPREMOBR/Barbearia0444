<?php
// Variáveis de conexão com o banco de dados
$banco = 'barbearia01';
$usuario = 'root';
$senha = '';
$servidor = 'localhost';

// Configura a URL do sistema, ajustando se está em localhost
$url_sistema = "http://$_SERVER[HTTP_HOST]/";
$url = explode("//", $url_sistema);
if ($url[1] == 'localhost/') {
	$url_sistema = "http://$_SERVER[HTTP_HOST]/barbearia/";
}
// Define o fuso horário
date_default_timezone_set('America/Sao_Paulo');

// conectar ao banco de dados usando PDO
try {
	$pdo = new PDO("mysql:dbname=$banco;host=$servidor;charset=utf8", "$usuario", "$senha");
} catch (Exception $erro) {
	echo 'Não conectado ao Banco de Dados! <br><br>' . $erro;
}


//VARIAVEIS DO SISTEMA
$nome_sistema = 'Barbearia Lima';
$email_sistema = 'nagatabrisa.05@gmail.com';
$whatsapp_sistema = '(96) 99182-7077';
$not_sistema = 'Sim';


// Consulta para buscar configurações do sistema
$query = $pdo->query("SELECT * from config ");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
// Se não há registros, insere as configurações padrão
if ($total_registro == 0) {
	$pdo->query("INSERT INTO config SET nome = '$nome_sistema', email = '$email_sistema', telefone_whatsapp = '$whatsapp_sistema', logo = 'logo.png', 
	icone = 'favicon.ico', logo_relatorio = 'logo_relatorio.jpg', tipo_rel = 'pdf', tipo_comissao = 'Porcentagem', 
	texto_rodape = 'Edite este texto nas configurações do painel administrador', img_banner_index = 'hero-bg.jpg', 
	texto_agendamento = 'Selecionar Prestador de Serviço', msg_agendamento = 'Sim', agendamento_dias = '30', itens_pag = '10', minutos_aviso = '0', 
	porc_servico = '0', pagamento_api = 'Sim', api = 'menuia'");
} else {
	// Caso existam registros, define as variáveis do sistema com os valores recuperados
	$nome_sistema = $resultado[0]['nome'];
	$email_sistema = $resultado[0]['email'];
	$whatsapp_sistema = $resultado[0]['telefone_whatsapp'];
	$tipo_rel = $resultado[0]['tipo_rel'];
	$telefone_fixo_sistema = $resultado[0]['telefone_fixo'];
	$endereco_sistema = $resultado[0]['endereco'];
	$logo_relatorio = $resultado[0]['logo_relatorio'];
	$logo_sistema = $resultado[0]['logo'];
	$icone_sistema = $resultado[0]['icone'];
	$instagram_sistema = $resultado[0]['instagram'];
	$tipo_comissao = $resultado[0]['tipo_comissao'];
	$texto_rodape = $resultado[0]['texto_rodape'];
	$img_banner_index = $resultado[0]['img_banner_index'];
	$icone_site = $resultado[0]['icone_site'];
	$texto_sobre = $resultado[0]['texto_sobre'];
	$imagem_sobre = $resultado[0]['imagem_sobre'];
	$texto_agendamento = $resultado[0]['texto_agendamento'];
	$msg_agendamento = $resultado[0]['msg_agendamento'];
	$cnpj_sistema = $resultado[0]['cnpj'];
	$cidade_sistema = $resultado[0]['cidade'];
	$agendamento_dias = $resultado[0]['agendamento_dias'];
	$itens_pag = $resultado[0]['itens_pag'];
	$minutos_aviso = $resultado[0]['minutos_aviso'];
	$antAgendamento = $resultado[0]['minutos_aviso'];
	$token = $resultado[0]['token'];
	$instancia = $resultado[0]['instancia'];
	$taxa_sistema = $resultado[0]['taxa_sistema'];
	$lancamento_comissao = $resultado[0]['lancamento_comissao'];
	$ativo_sistema = $resultado[0]['ativo'];
	$porc_servico = $resultado[0]['porc_servico'];
	$pagamento_api = $resultado[0]['pagamento_api'];
	$api = $resultado[0]['api'];

	// Novas variaveis
	$emailMenuia = $resultado[0]['email_menuia'] ?? '';
	$planoMenuia = $resultado[0]['plano_menuia'] ?? '';
	$validadeMenuia = $resultado[0]['validade_menuia'] ?? '';
	$senhaMenuia = $resultado[0]['senha_menuia'] ?? '';

	// Fim das variaveis Menuia


	$horas_confirmacaoF = $minutos_aviso . ':00:00';
	// Formata o telefone do WhatsApp
	$telefone_whatsapp = '55' . preg_replace('/[ ()-]+/', '', $whatsapp_sistema);

	// Caso o sistema esteja inativo, exibe uma imagem de bloqueio
	if ($ativo_sistema != 'Sim' and $ativo_sistema != '') { ?>
		<style type="text/css">
			@media only screen and (max-width: 700px) {
				.imgsistema_mobile {
					width: 300px;
				}

			}
		</style>
		<div style="text-align: center; margin-top: 100px">
			<img src="sistema/img/bloqueio.png" class="imgsistema_mobile">
		</div>
<?php
		exit();
	}
}







?>