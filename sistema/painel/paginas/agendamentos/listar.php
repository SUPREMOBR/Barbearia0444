<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
@session_start(); // Inicia a sessão para acessar variáveis de sessão
$usuario = @$_SESSION['id']; // Obtém o ID do usuário logado a partir da sessão.
$data_atual = date('Y-m-d'); // Obtém a data atual

$funcionario = @$_POST['funcionario'];
$data = @$_POST['data'];

// Verifica se a data foi enviada; caso contrário, utiliza a data atual.
if ($data == "") {
	$data = date('Y-m-d');
}

// Verifica se o funcionário foi selecionado.
if ($funcionario == "") {
	echo '<small>Selecione um Funcionário!</small>';
	exit();
}

echo <<<HTML
<small>
HTML;
// Consulta os agendamentos para o funcionário e data selecionados, ordenando por hora.
$query = $pdo->query("SELECT * FROM agendamentos where funcionario = '$funcionario' and data = '$data' ORDER BY hora asc");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
// Verifica se há agendamentos.
if ($total_registro > 0) {
	// Loop para exibir cada agendamento.
	for ($i = 0; $i < $total_registro; $i++) {
		foreach ($resultado[$i] as $key => $value) {
		}
		// Extrai informações de cada agendamento.
		$id = $resultado[$i]['id'];
		$funcionario = $resultado[$i]['funcionario'];
		$cliente = $resultado[$i]['cliente'];
		$hora = $resultado[$i]['hora'];
		$data = $resultado[$i]['data'];
		$usuario = $resultado[$i]['usuario'];
		$data_lancamento = $resultado[$i]['data_lancamento'];
		$obs = $resultado[$i]['obs'];
		$status = $resultado[$i]['status'];
		$servico = $resultado[$i]['servico'];
		$valor_pago = $resultado[$i]['valor_pago'];


		$valor_pagoF = @number_format($valor_pago, 2, ',', '.');
		if ($valor_pago > 0 and $status == 'Agendado') {
			$classe_valor_pago = '';
		} else {
			$classe_valor_pago = 'ocultar';
		}
		// Formatação de data e hora.
		$dataF = implode('/', array_reverse(explode('-', $data)));
		$horaF = date("H:i", strtotime($hora));
		// Classe de estilo para status "Concluído".
		if ($status == 'Concluído') {
			$classe_linha = '';
		} else {
			$classe_linha = 'text-muted';
		}
		// Determinação do ícone de status.
		if ($status == 'Agendado') {
			$imagem = 'icone-relogio.png';
			$classe_status = '';
		} else {
			$imagem = 'icone-relogio-verde.png';
			$classe_status = 'ocultar';
		}
		// Busca o nome do usuário que agendou.
		$query2 = $pdo->query("SELECT * FROM usuarios01 where id = '$usuario'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		if (@count($resultado2) > 0) {
			$nome_usuario = $resultado2[0]['nome'];
		} else {
			$nome_usuario = 'Sem Usuário';
		}
		// Busca informações do serviço.
		$query2 = $pdo->query("SELECT * FROM servicos where id = '$servico'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		if (@count($resultado2) > 0) {
			$nome_serv = $resultado2[0]['nome'];
			$valor_serv = $resultado2[0]['valor'];
		} else {
			$nome_serv = 'Não Lançado';
			$valor_serv = '';
		}
		// Busca o nome do cliente.
		$query2 = $pdo->query("SELECT * FROM clientes where id = '$cliente'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		if (@count($resultado2) > 0) {
			$nome_cliente = $resultado2[0]['nome'];
		} else {
			$nome_cliente = 'Sem Cliente';
		}

		//retirar aspas do texto do obs
		$obs = str_replace('"', "**", $obs);

		// Determinação dos débitos do cliente.
		$classe_deb = '#043308';
		$total_debitos = 0;
		$total_pagar = 0;
		$total_vencido = 0;
		$total_debitosF = 0;
		$total_pagarF = 0;
		$total_vencidoF = 0;
		$query2 = $pdo->query("SELECT * FROM receber where pessoa = '$cliente' and pago != 'Sim'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_registro2 = @count($resultado2);
		if ($total_registro2 > 0) {
			$classe_deb = '#661109';
			for ($i2 = 0; $i2 < $total_registro2; $i2++) {
				$valor_s = $resultado2[$i2]['valor'];
				$data_vencimento = $resultado2[$i2]['data_vencimento'];

				$total_debitos += $valor_s;
				$total_debitosF = @number_format($total_debitos, 2, ',', '.');

				// Classificação dos débitos entre vencidos e a vencer.
				if (strtotime($data_vencimento) < strtotime($data_atual)) {
					$total_vencido += $valor_s;
				} else {
					$total_pagar += $valor_s;
				}

				$total_pagarF = @number_format($total_pagar, 2, ',', '.');
				$total_vencidoF = @number_format($total_vencido, 2, ',', '.');
			}
		}

		if ($valor_serv == $valor_pago) {
			$valor_pagoF = ' Pago';
		} else {
			$valor_pagoF = 'R$ ' . $valor_pagoF;
		}
		// Verificação de pagamento do serviço.
		if ($valor_pago > 0) {
			$valor_serv = $valor_serv - $valor_pago;
		}

		echo <<<HTML
			<div class="col-xs-12 col-md-4 widget cardTarefas mobile100">
        		<div class="r3_counter_box">     		
        		
				<li class="dropdown head-dpdn2" style="list-style-type: none;">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		<button type="button" class="close" title="Excluir agendamento" style="margin-top: -10px">
					<span aria-hidden="true"><big>&times;</big></span>
				</button>
				</a>

		<ul class="dropdown-menu" style="margin-left:-30px;">
		<li>
		<div class="notification_desc2">
		<p>Confirmar Exclusão? <a href="#" onclick="excluir('{$id}', '{$horaF}')"><span class="text-danger">Sim</span></a></p>
		</div>
		</li>										
		</ul>
		</li>

		<div class="row">
        		<div class="col-md-3">


				<li class="dropdown head-dpdn2" style="list-style-type: none;">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		<img class="icon-rounded-vermelho" src="img/{$imagem}" width="45px" height="45px">
				</a>

		<ul class="dropdown-menu" style="margin-left:-30px;">
		<li>
		<div class="notification_desc2">
		<p>
		<span style="margin-right: 20px; "><b>Débitos do Cliente</b></span><br>
		<span style="margin-right: 20px; ">Total Vencido <span style="color:red">R$ {$total_vencidoF}</span></span><br>
<span style="margin-right: 20px; ">Total à Vencer <span style="color:blue">R$ {$total_pagarF}</span></span><br>
<span >Total Pagar <span style="color:green">R$ {$total_debitosF}</span></span>
		</p>
		<p>Observações: {$obs}</p>
		</div>
		</li>										
		</ul>
		</li>
        	 
        		</div>
        		<div class="col-md-9">
        			<h5><strong>{$horaF}</strong> <a href="#" onclick="fecharServico('{$id}', '{$cliente}', '{$servico}', '{$valor_serv}', '{$funcionario}', '{$nome_serv}')" title="Finalizar Serviço" class="{$classe_status}"> <img class="icon-rounded-vermelho" src="img/check-square.png" width="15px" height="15px"></a> 

        			<span class="{$classe_valor_pago} verde" style="font-size: 12px; font-weight: 300" >({$valor_pagoF})</span>

        				</h5>

        		</div>
        		</div>
        				
        		<hr style="margin-top:-2px; margin-bottom: 3px">                    
                    <div class="stats" align="center">
                      <span style="">                      
                        <small><span style="color:{$classe_deb}; font-size:13px">{$nome_cliente}</span> (<i><span style="color:#061f9c; font-size:12px">{$nome_serv}</span></i>)</small></span>
                    </div>
                </div>
        	</div>
HTML;
	}
} else {
	echo 'Nenhum horário para essa Data!';
}

?>

<script type="text/javascript">
	function fecharServico(id, cliente, servico, valor_servico, funcionario, nome_serv) {

		$('#id_agd').val(id);
		$('#cliente_agd').val(cliente);
		$('#servico_agd').val(servico);
		$('#valor_serv_agd').val(valor_servico);
		$('#funcionario_agd').val(funcionario).change();
		$('#titulo_servico').text(nome_serv);
		$('#descricao_serv_agd').val(nome_serv);
		$('#obs2').val('');

		$('#valor_serv_agd_restante').val('');
		$('#data_pagamento_restante').val('');
		$('#pagamento_restante').val('').change();

		$('#modalServico').modal('show');
	}
</script>