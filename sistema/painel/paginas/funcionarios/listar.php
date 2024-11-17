<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'usuarios01'; // Define o nome da tabela no banco de dado

// Verifica o tipo de comissão 
if ($tipo_comissao == 'Porcentagem') {
	$tipo_comissao = '%'; // Se o tipo de comissão for 'Porcentagem', ajusta para o formato de porcentagem
}

// Consulta os registros da tabela 'usuarios01', excluindo os administradores
$query = $pdo->query("SELECT * FROM $tabela where nivel != 'Administrador' ORDER BY id desc");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado); // Conta o número de registros retornados pela consulta
// Verifica se existem registros de usuários (exceto administradores)
if ($total_registro > 0) {
	// Exibe a tabela com os usuários e suas informações
	echo <<<HTML
	<small>
	<table class="table table-hover" id="tabela">
	<thead> 
	<tr> 
	<th>Nome</th>	
	<th class="esc">Email</th> 	
	<th class="esc">CPF</th> 	
	<th class="esc">Cargo</th> 	
	<th class="esc">Cadastro</th>
	<th class="esc">Comissão <small>({$tipo_comissao})</small></th>	
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
HTML;
	// Loop para percorrer os usuários e exibir as informações na tabela
	for ($i = 0; $i < $total_registro; $i++) {
		foreach ($resultado[$i] as $key => $value) {
		}
		// Obtém os dados do usuário
		$id = $resultado[$i]['id'];
		$nome = $resultado[$i]['nome'];
		$email = $resultado[$i]['email'];
		$cpf = $resultado[$i]['cpf'];
		$senha = $resultado[$i]['senha'];
		$nivel = $resultado[$i]['nivel'];
		$data = $resultado[$i]['data'];
		$ativo = $resultado[$i]['ativo'];
		$telefone = $resultado[$i]['telefone'];
		$endereco = $resultado[$i]['endereco'];
		$foto = $resultado[$i]['foto'];
		$atendimento = $resultado[$i]['atendimento'];
		$tipo_chave = $resultado[$i]['tipo_chave'];
		$chave_pix = $resultado[$i]['chave_pix'];
		$intervalo = $resultado[$i]['intervalo'];
		$comissao = $resultado[$i]['comissao'];

		// Formatação da data para o formato brasileiro
		$dataF = implode('/', array_reverse(explode('-', $data)));

		// Mascarando a senha, para não exibir a senha real
		$senha = '*******';

		// Verifica o status do usuário (ativo ou inativo) e define o ícone e a ação
		if ($ativo == 'Sim') {
			$icone = 'fa-check-square';  // Ícone para usuário ativo
			$titulo_link = 'Desativar Item'; // Texto do link para desativar
			$acao = 'Não'; // Ação para desativar o usuário
			$classe_linha = '';
		} else {
			$icone = 'fa-square-o';  // Ícone para usuário inativo
			$titulo_link = 'Ativar Item'; // Texto do link para ativar
			$acao = 'Sim';  // Ação para ativar o usuário
			$classe_linha = 'text-muted';
		}

		// Formatação do telefone para WhatsApp
		$whats = '55' . preg_replace('/[ ()-]+/', '', $telefone);

		// Formatação da comissão, dependendo do tipo
		if ($tipo_comissao == '%') {
			// Comissão em porcentagem
			$comissaoF = @number_format($comissao, 0, ',', '.') . '%';
		} else {
			// Comissão em valor monetário
			$comissaoF = 'R$ ' . @number_format($comissao, 2, ',', '.');
		}

		// Caso a comissão não esteja definida, limpa o valor da comissão
		if ($comissao == "") {
			$comissaoF = "";
		}

		// Exibe a linha da tabela com as informações do usuário
		echo <<<HTML
<tr class="{$classe_linha}">
<td>
<img src="img/perfil/{$foto}" width="27px" class="mr-2">
{$nome}
</td>
<td class="esc">{$email}</td>
<td class="esc">{$cpf}</td>
<td class="esc">{$nivel}</td>
<td class="esc">{$dataF}</td>
<td class="esc">{$comissaoF}</td>
<td>
		<big><a href="#" onclick="editar('{$id}','{$nome}', '{$email}', '{$telefone}', '{$cpf}', '{$nivel}', '{$endereco}', '{$foto}', '{$atendimento}', '{$tipo_chave}', '{$chave_pix}', '{$intervalo}', '{$comissao}')" title="Editar Dados"><i class="fa fa-edit text-primary"></i></a></big>

		<big><a href="#" onclick="mostrar('{$nome}', '{$email}', '{$cpf}', '{$senha}', '{$nivel}', '{$dataF}', '{$ativo}', '{$telefone}', '{$endereco}', '{$foto}', '{$atendimento}', '{$tipo_chave}', '{$chave_pix}')" title="Ver Dados"><i class="fa fa-info-circle text-secondary"></i></a></big>

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
		
		<a href="#" onclick="dias('{$id}', '{$nome}')" title="Ver Dias"><i class="fa fa-calendar text-danger"></i></a>

		<big><a href="http://api.whatsapp.com/send?1=pt_BR&phone=$whats&text=" target="_blank" title="Abrir Whatsapp"><i class="fa fa-whatsapp verde"></i></a></big>

			<a href="#" onclick="servico('{$id}', '{$nome}')" title="Definir Serviços"><i class="fa fa-briefcase" style="color:#a60f4b"></i></a>


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
} else {
	echo '<small>Não possui nenhum registro Cadastrado!</small>';
}

?>

<script type="text/javascript">
	$(document).ready(function() {
		$('#tabela').DataTable({
			"ordering": false,
			"stateSave": true
		});
		$('#tabela_filter label input').focus();
	});
</script>


<script type="text/javascript">
	// Função para preencher o formulário de edição com os dados do registro
	function editar(id, nome, email, telefone, cpf, nivel, endereco, foto, atendimento, tipo_chave, chave_pix, intervalo, comissao) {
		$('#id').val(id);
		$('#nome').val(nome);
		$('#email').val(email);
		$('#telefone').val(telefone);
		$('#cpf').val(cpf);
		$('#cargo').val(nivel).change();
		$('#endereco').val(endereco);
		$('#atendimento').val(atendimento).change();
		$('#chave_pix').val(chave_pix);
		$('#tipo_chave').val(tipo_chave).change();
		$('#intervalo').val(intervalo);
		$('#comissao').val(comissao);

		$('#titulo_inserir').text('Editar Registro');
		$('#modalForm').modal('show');
		$('#foto').val('');
		$('#target').attr('src', 'img/perfil/' + foto);
	}
	// Função para limpar os campos do formulário
	function limparCampos() {
		$('#id').val('');
		$('#nome').val('');
		$('#telefone').val('');
		$('#email').val('');
		$('#cpf').val('');
		$('#endereco').val('');
		$('#foto').val('');
		$('#chave_pix').val('');
		$('#target').attr('src', 'img/perfil/sem-foto.jpg');
		$('#intervalo').val('');
		$('#comissao').val('');
	}
</script>



<script type="text/javascript">
	// Função para mostrar as informações do usuário em um modal
	function mostrar(nome, email, cpf, senha, nivel, data, ativo, telefone, endereco, foto, atendimento, tipo_chave, chave_pix) {

		$('#nome_dados').text(nome);
		$('#email_dados').text(email);
		$('#cpf_dados').text(cpf);
		$('#senha_dados').text(senha);
		$('#nivel_dados').text(nivel);
		$('#data_dados').text(data);
		$('#ativo_dados').text(ativo);
		$('#telefone_dados').text(telefone);
		$('#endereco_dados').text(endereco);
		$('#atendimento_dados').text(atendimento);
		$('#tipo_chave_dados').text(tipo_chave);
		$('#chave_pix_dados').text(chave_pix);

		$('#target_mostrar').attr('src', 'img/perfil/' + foto);

		$('#modalDados').modal('show');
	}
</script>

<script type="text/javascript">
	// Função para abrir o modal de horários de um usuário
	function horarios(id, nome) {

		$('#nome_horarios').text(nome); // Preenche o título do modal com o nome do usuário
		$('#id_horarios').val(id); // Armazena o id do usuário

		$('#modalHorarios').modal('show');
		listarHorarios(id); // Lista os horários do funcionário
	}
</script>


<script type="text/javascript">
	// Função para abrir o modal de dias de trabalho de um usuário
	function dias(id, nome) {

		$('#nome_dias').text(nome); // Preenche o título do modal com o nome do usuário
		$('#id_dias').val(id); // Armazena o id do usuário

		$('#modalDias').modal('show');
		listarDias(id); // Lista os dias de trabalho do funcionário
	}
</script>



<script type="text/javascript">
	// Função para abrir o modal de serviços de um usuário
	function servico(id, nome) {

		$('#nome_servico').text(nome);
		$('#id_servico').val(id);

		$('#modalServicos').modal('show');
		listarServicos(id);
	}
</script>