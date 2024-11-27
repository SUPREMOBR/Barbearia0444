<?php
require_once("../../sistema/conexao.php");

$id_usuario = $_POST['id_usuario'];


$query = $pdo->query("SELECT * from usuarios01 where id = '$id_usuario'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
$atendimento = $resultado[0]['atendimento'];
$nivel = $resultado[0]['nivel'];


$home = 'none';
$configuracoes = 'none';

//grupo pessoas
$usuarios = 'none';
$funcionarios = 'none';
$clientes = 'none';
$fornecedores = 'none';


//grupo cadastros
$servicos = 'none';
$cargos = 'none';
$categoria_servicos = 'none';
$grupos = 'none';
$acessos = 'none';
$pagamento = 'none';

//grupo produtos
$produtos = 'none';
$categoria_produtos = 'none';
$estoque = 'none';
$saidas = 'none';
$entradas = 'none';


//grupo financeiro
$vendas = 'none';
$compras = 'none';
$pagar = 'none';
$receber = 'none';
$comissoes = 'comissoes';


//agendamentos / servico
$agendamentos = 'none';
$servicos_agenda = 'none';


//relatorios
$rel_produtos = 'none';
$rel_entradas = 'none';
$rel_saidas = 'none';
$rel_comissoes = 'none';
$rel_contas = 'none';
$rel_lucro = 'none';
$rel_servicos = 'none';

//dados site
$textos_index = 'none';
$comentarios = 'none';




$query = $pdo->query("SELECT * FROM usuarios_permissoes where usuario = '$id_usuario'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
if ($total_registro > 0) {
	for ($i = 0; $i < $total_registro; $i++) {
		foreach ($resultado[$i] as $key => $value) {
		}
		$permissao = $resultado[$i]['permissao'];

		$query2 = $pdo->query("SELECT * FROM acessos where id = '$permissao'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$nome = $resultado2[0]['nome'];
		$chave = $resultado2[0]['chave'];
		$id = $resultado2[0]['id'];

		if ($chave == 'home') {
			$home = '';
		}


		if ($chave == 'configuracoes') {
			$configuracoes = '';
		}




		if ($chave == 'usuarios') {
			$usuarios = '';
		}

		if ($chave == 'funcionarios') {
			$funcionarios = '';
		}

		if ($chave == 'clientes') {
			$clientes = '';
		}

		if ($chave == 'clientes_retorno') {
			$clientes_retorno = '';
		}

		if ($chave == 'fornecedores') {
			$fornecedores = '';
		}





		if ($chave == 'servicos') {
			$servicos = '';
		}

		if ($chave == 'cargos') {
			$cargos = '';
		}

		if ($chave == 'categoria_servicos') {
			$cat_servicos = '';
		}

		if ($chave == 'grupos') {
			$grupos = '';
		}

		if ($chave == 'acessos') {
			$acessos = '';
		}

		if ($chave == 'pgto') {
			$pgto = '';
		}





		if ($chave == 'produtos') {
			$produtos = '';
		}

		if ($chave == 'categoria_produtos') {
			$cat_produtos = '';
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




		if ($chave == 'agendamentos') {
			$agendamentos = '';
		}

		if ($chave == 'servicos_agenda') {
			$servicos_agenda = '';
		}




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





		if ($chave == 'textos_index') {
			$textos_index = '';
		}

		if ($chave == 'comentarios') {
			$comentarios = '';
		}
	}
}



if ($home != 'none') {
	$pag_inicial = 'home';
} else if ($atendimento == 'Sim') {
	$pag_inicial = 'agenda';
} else {
	$query = $pdo->query("SELECT * FROM usuarios_permissoes where usuario = '$id_usuario' order by id asc limit 1");
	$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
	$total_registro = @count($resultado);
	if ($total_registro > 0) {
		$permissao = $resultado[0]['permissao'];
		$query2 = $pdo->query("SELECT * FROM acessos where id = '$permissao'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$pag_inicial = $resultado2[0]['chave'];
	}
}



if ($usuarios == 'none' and $funcionarios == 'none' and $clientes == 'none' and $fornecedores == 'none') {
	$menu_pessoas = 'none';
} else {
	$menu_pessoas = '';
}



if ($servicos == 'none' and $cargos == 'none' and $categoria_servicos == 'none' and $grupos == 'none' and $acessos == 'none' and $pagamento == 'none') {
	$menu_cadastros = 'none';
} else {
	$menu_cadastros = '';
}



if ($produtos == 'none' and $categoria_produtos == 'none' and $estoque == 'none' and $saidas == 'none' and $entradas == 'none') {
	$menu_produtos = 'none';
} else {
	$menu_produtos = '';
}



if ($compras == 'none' and $vendas == 'none' and $pagar == 'none' and $receber == 'none') {
	$menu_financeiro = 'none';
} else {
	$menu_financeiro = '';
}



if ($agendamentos == 'none' and $servicos_agenda == 'none') {
	$menu_agendamentos = 'none';
} else {
	$menu_agendamentos = '';
}



if ($rel_produtos == 'none' and $rel_lucro == 'none' and $rel_contas == 'none' and $rel_comissoes == 'none' and $rel_saidas == 'none' and $rel_entradas == 'none' and $rel_servicos == 'none') {
	$menu_relatorio = 'none';
} else {
	$menu_relatorio = '';
}



if ($textos_index == 'none' and $comentarios == 'none') {
	$menu_site = 'none';
} else {
	$menu_site = '';
}



$dados = array(
	'home' => $home,
	'usuarios' => $usuarios,
	'funcionarios' => $funcionarios,
	'clientes' => $clientes,
	'fornecedores' => $fornecedores,
	'vendas' => $vendas,
	'compras' => $compras,
	'receber' => $receber,
	'pagar' => $pagar,
	'estoque' => $estoque,
	'atendimento' => $atendimento,
	'menu_financeiro' => $menu_financeiro,
	'menu_pessoas' => $menu_pessoas,


);


$result = json_encode($dados);
echo $result;
