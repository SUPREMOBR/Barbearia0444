<?php 
require_once("../conexao.php");
@session_start();
$id_usuario = $_SESSION['id'];


$home = 'ocultar';

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
$comissoes = 'comissoes';


//agendamentos / servico
$agendamentos = 'ocultar';
$servicos_agenda = 'ocultar';





$query = $pdo->query("SELECT * FROM usuarios_permissoes where usuario = '$id_usuario'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
if($total_registro > 0){
	for($i=0; $i < $total_registro; $i++){
		foreach ($resultado[$i] as $key => $value){}
		$permissao = $resultado[$i]['permissao'];
		
		$query2 = $pdo->query("SELECT * FROM acessos where id = '$permissao'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$nome = $resultado2[0]['nome'];
		$chave = $resultado2[0]['chave'];
		$id = $resultado2[0]['id'];

		if($chave == 'home'){
			$home = '';
		}



		if($chave == 'usuarios'){
			$usuarios = '';
		}

		if($chave == 'funcionarios'){
			$funcionarios = '';
		}

		if($chave == 'clientes'){
			$clientes = '';
		}

		if($chave == 'fornecedores'){
			$fornecedores = '';
		}





		if($chave == 'servicos'){
			$servicos = '';
		}

		if($chave == 'cargos'){
			$cargos = '';
		}

		if($chave == 'categoria_servicos'){
			$categoria_servicos = '';
		}

		if($chave == 'grupos'){
			$grupos = '';
		}

		if($chave == 'acessos'){
			$acessos = '';
		}




		if($chave == 'produtos'){
			$produtos = '';
		}

		if($chave == 'cat_produtos'){
			$categoria_produtos = '';
		}

		if($chave == 'estoque'){
			$estoque = '';
		}

		if($chave == 'saidas'){
			$saidas = '';
		}

		if($chave == 'entradas'){
			$entradas = '';
		}





		if($chave == 'compras'){
			$compras = '';
		}

		if($chave == 'vendas'){
			$vendas = '';
		}

		if($chave == 'pagar'){
			$pagar = '';
		}

		if($chave == 'receber'){
			$receber = '';
		}

		if($chave == 'comissoes'){
			$comissoes = '';
		}




		if($chave == 'agendamentos'){
			$agendamentos = '';
		}

		if($chave == 'servicos_agenda'){
			$servicos_agenda = '';
		}




	}

}



if($home != 'ocultar'){
	$pag_inicial = 'home';
}else if($atendimento == 'Sim'){
	$pag_inicial = 'agenda';
}else{
	$query = $pdo->query("SELECT * FROM usuarios_permissoes where usuario = '$id_usuario' order by id asc limit 1");
	$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
	$total_registro = @count($resultado);
	if($total_registro > 0){	
			$permissao = $resultado[0]['permissao'];		
			$query2 = $pdo->query("SELECT * FROM acessos where id = '$permissao'");
			$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);		
			$pag_inicial = $resultado2[0]['chave'];		

	}
}



if($usuarios == 'ocultar' and $funcionarios == 'ocultar' and $clientes == 'ocultar'  and $fornecedores == 'ocultar'){
	$menu_pessoas = 'ocultar';
}else{
	$menu_pessoas = '';
}



if($servicos == 'ocultar' and $cargos == 'ocultar' and $categoria_servicos == 'ocultar' and $grupos == 'ocultar' and $acessos == 'ocultar'){
	$menu_cadastros = 'ocultar';
}else{
	$menu_cadastros = '';
}



if($produtos == 'ocultar' and $categoria_produtos == 'ocultar' and $estoque == 'ocultar' and $saidas == 'ocultar' and $entradas == 'ocultar'){
	$menu_produtos = 'ocultar';
}else{
	$menu_produtos = '';
}



if($compras == 'ocultar' and $vendas == 'ocultar' and $pagar == 'ocultar' and $receber == 'ocultar' and $comissoes == 'ocultar'){
	$menu_financeiro = 'ocultar';
}else{
	$menu_financeiro = '';
}



if($agendamentos == 'ocultar' and $servicos_agenda == 'ocultar' ){
	$menu_agendamentos = 'ocultar';
}else{
	$menu_agendamentos = '';
}

 ?>