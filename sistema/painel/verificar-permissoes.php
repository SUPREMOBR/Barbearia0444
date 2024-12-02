<?php
// Inclui o arquivo de conexão com o banco de dados
require_once("../conexao.php");
// Inicia a sessão para acessar variáveis de sessão
@session_start();
$id_usuario = $_SESSION['id']; // Obtém o ID do usuário logado a partir da sessão

// Inicializa variáveis de visibilidade como 'ocultar' para restringir o acesso inicial
$home = 'ocultar';
$configuracoes = 'ocultar';
$comanda = 'ocultar';
$marketing = 'ocultar';
$calendario = 'ocultar';

//grupo pessoas
$usuarios = 'ocultar';
$funcionarios = 'ocultar';
$clientes = 'ocultar';
$fornecedores = 'ocultar';


//grupo cadastros
$servicos = 'ocultar';
$cargos = 'ocultar';
$categoria_servicos = 'ocultar';
$grupos = 'ocultar';
$acessos = 'ocultar';
$pagamento = 'ocultar';
$dias_bloqueio = 'ocultar';

//grupo produtos
$produtos = 'ocultar';
$categoria_produtos = 'ocultar';
$estoque = 'ocultar';
$saidas = 'ocultar';
$entradas = 'ocultar';


//grupo financeiro
$vendas = 'ocultar';
$compras = 'ocultar';
$pagar = 'ocultar';
$receber = 'ocultar';
$comissoes = 'ocultar';


//agendamentos / servico
$agendamentos = 'ocultar';
$servicos_agenda = 'ocultar';


//relatorios
$rel_produtos = 'ocultar';
$rel_entradas = 'ocultar';
$rel_saidas = 'ocultar';
$rel_comissoes = 'ocultar';
$rel_contas = 'ocultar';
$rel_lucro = 'ocultar';
$rel_servicos = 'ocultar';

//dados site
$textos_index = 'ocultar';
$comentarios = 'ocultar';


// Consulta para buscar permissões do usuário logado
$query = $pdo->query("SELECT * FROM usuarios_permissoes where usuario = '$id_usuario'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
if ($total_registro > 0) {
	// Loop para processar cada permissão encontrada
	for ($i = 0; $i < $total_registro; $i++) {
		foreach ($resultado[$i] as $key => $value) {
		}
		$permissao = $resultado[$i]['permissao'];
		// Busca o nome e chave da permissão na tabela acessos
		$query2 = $pdo->query("SELECT * FROM acessos where id = '$permissao'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$nome = $resultado2[0]['nome'];
		$chave = $resultado2[0]['chave'];
		$id = $resultado2[0]['id'];

		// Verifica a chave da permissão e, se encontrada, libera o acesso removendo a classe 'ocultar'
		if ($chave == 'home') {
			$home = '';
		}
		if ($chave == 'configuracoes') {
			$configuracoes = '';
		}
		if ($chave == 'comanda') {
			$comanda = '';
		}
		if ($chave == 'marketing') {
			$marketing = '';
		}
		if ($chave == 'calendario') {
			$calendario = '';
		}

		// Permissões do grupo de pessoas
		if ($chave == 'usuarios') {
			$usuarios = '';
		}
		if ($chave == 'funcionarios') {
			$funcionarios = '';
		}
		if ($chave == 'clientes') {
			$clientes = '';
		}
		if ($chave == 'fornecedores') {
			$fornecedores = '';
		}

		// Permissões do grupo de cadastros
		if ($chave == 'servicos') {
			$servicos = '';
		}
		if ($chave == 'cargos') {
			$cargos = '';
		}
		if ($chave == 'categoria_servicos') {
			$categoria_servicos = '';
		}
		if ($chave == 'grupos') {
			$grupos = '';
		}
		if ($chave == 'acessos') {
			$acessos = '';
		}
		if ($chave == 'pagamento') {
			$pagamento = '';
		}
		if ($chave == 'dias_bloqueio') {
			$dias_bloqueio = '';
		}

		// Permissões do grupo de produtos
		if ($chave == 'produtos') {
			$produtos = '';
		}
		if ($chave == 'categoria_produtos') {
			$categoria_produtos = '';
		}
		if ($chave == 'estoque') {
			$estoque = '';
		}
		if ($chave == 'saidas') {
			$saidas = '';
		}
		if ($chave == 'entradas') {
			$entradas = '';
		}

		// Permissões do grupo financeiro
		if ($chave == 'compras') {
			$compras = '';
		}
		if ($chave == 'vendas') {
			$vendas = '';
		}
		if ($chave == 'pagar') {
			$pagar = '';
		}
		if ($chave == 'receber') {
			$receber = '';
		}
		if ($chave == 'comissoes') {
			$comissoes = '';
		}

		// Permissões de agendamentos e serviços
		if ($chave == 'agendamentos') {
			$agendamentos = '';
		}
		if ($chave == 'servicos_agenda') {
			$servicos_agenda = '';
		}

		// Permissões de relatórios
		if ($chave == 'rel_produtos') {
			$rel_produtos = '';
		}
		if ($chave == 'rel_entradas') {
			$rel_entradas = '';
		}
		if ($chave == 'rel_saidas') {
			$rel_saidas = '';
		}
		if ($chave == 'rel_comissoes') {
			$rel_comissoes = '';
		}
		if ($chave == 'rel_contas') {
			$rel_contas = '';
		}
		if ($chave == 'rel_lucro') {
			$rel_lucro = '';
		}
		if ($chave == 'rel_servicos') {
			$rel_servicos = '';
		}

		// Permissões para dados do site
		if ($chave == 'textos_index') {
			$textos_index = '';
		}
		if ($chave == 'comentarios') {
			$comentarios = '';
		}
	}
}


// Define a página inicial com base nas permissões e condições
if ($home != 'ocultar') {
	// Se o usuário tiver permissão para "home", essa é a página inicial
	$pag_inicial = 'home';
} else if ($atendimento == 'Sim') {
	// Caso contrário, se o atendimento estiver habilitado, define a página inicial como "agenda"
	$pag_inicial = 'agenda';
} else {
	// Caso não tenha acesso à home e atendimento esteja desabilitado, escolhe a primeira permissão do usuário
	$query = $pdo->query("SELECT * FROM usuarios_permissoes where usuario = '$id_usuario' order by id asc limit 1");
	$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
	$total_registro = @count($resultado);
	if ($total_registro > 0) {
		$permissao = $resultado[0]['permissao'];
		// Obtém a chave de acesso da primeira permissão para definir a página inicial
		$query2 = $pdo->query("SELECT * FROM acessos where id = '$permissao'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$pag_inicial = $resultado2[0]['chave'];
	}
}

// Define a visibilidade do menu "Pessoas" com base nas permissões relacionadas a pessoas
if ($usuarios == 'ocultar' and $funcionarios == 'ocultar' and $clientes == 'ocultar' and $fornecedores == 'ocultar') {
	$menu_pessoas = 'ocultar';
} else {
	// Mostra o menu "Pessoas" caso tenha pelo menos uma das permissões visíveis
	$menu_pessoas = '';
}

// Define a visibilidade do menu "Cadastros" com base nas permissões de cadastros
if (
	$servicos == 'ocultar' and $cargos == 'ocultar' and $categoria_servicos == 'ocultar' and $grupos == 'ocultar' and $acessos == 'ocultar' and
	$pagamento == 'ocultar' and $dias_bloqueio == 'ocultar'
) {
	$menu_cadastros = 'ocultar';
} else {
	// Mostra o menu "Cadastros" se houver alguma permissão visível
	$menu_cadastros = '';
}

// Define a visibilidade do menu "Produtos" com base nas permissões de produtos
if ($produtos == 'ocultar' and $categoria_produtos == 'ocultar' and $estoque == 'ocultar' and $saidas == 'ocultar' and $entradas == 'ocultar') {
	$menu_produtos = 'ocultar';
} else {
	// Mostra o menu "Produtos" se houver pelo menos uma permissão visível
	$menu_produtos = '';
}

// Define a visibilidade do menu "Financeiro" com base nas permissões de financeiro
if ($compras == 'ocultar' and $vendas == 'ocultar' and $pagar == 'ocultar' and $receber == 'ocultar' and $comissoes == 'ocultar') {
	$menu_financeiro = 'ocultar';
} else {
	// Mostra o menu "Financeiro" caso tenha ao menos uma permissão visível
	$menu_financeiro = '';
}

// Define a visibilidade do menu "Agendamentos" com base nas permissões de agendamentos
if ($agendamentos == 'ocultar' and $servicos_agenda == 'ocultar') {
	$menu_agendamentos = 'ocultar';
} else {
	// Mostra o menu "Agendamentos" se houver pelo menos uma permissão visível
	$menu_agendamentos = '';
}

// Define a visibilidade do menu "Relatórios" com base nas permissões de relatórios
if ($rel_produtos == 'ocultar' and $rel_lucro == 'ocultar'  and $rel_contas == 'ocultar' and $rel_comissoes == 'ocultar' and $rel_saidas == 'ocultar' and $rel_entradas == 'ocultar' and $rel_servicos == 'ocultar') {
	$menu_relatorio = 'ocultar';
} else {
	// Mostra o menu "Relatórios" se houver alguma permissão visível
	$menu_relatorio = '';
}

// Define a visibilidade do menu "Site" com base nas permissões relacionadas ao site
if ($textos_index == 'ocultar' and $comentarios == 'ocultar') {
	$menu_site = 'ocultar';
} else {
	// Mostra o menu "Site" se houver alguma permissão visível
	$menu_site = '';
}
