<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
@session_start(); // Inicia a sessão para acessar variáveis de sessão
$usuario = @$_SESSION['id'];  // Obtém o ID do usuário logado a partir da sessão.
$data_atual = date('Y-m-d'); // Armazena a data atual

$funcionario = @$_SESSION['id']; // Obtém o ID do funcionário logado a partir da variável de sessão.
$data = @$_POST['data']; // Recebe a data do agendamento enviada via POST.

if ($data == "") {
	$data = date('Y-m-d'); // Se não houver uma data específica, usa a data atual.
}

echo <<<HTML
<small>
HTML;
// Consulta os agendamentos do funcionário para a data específica (utilizando a data recebida ou a data atual)
$query = $pdo->query("SELECT * FROM agendamentos WHERE funcionario = '$funcionario' AND data = '$data' ORDER BY hora ASC");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado); // Conta quantos agendamentos foram encontrados para essa data.

if ($total_registro > 0) {
	// Se houverem agendamentos, entra no laço para processar cada um deles.
	for ($i = 0; $i < $total_registro; $i++) {
		foreach ($resultado[$i] as $key => $value) {
		}
		// Extração de dados do agendamento
		$id = $resultado[$i]['id']; // ID do agendamento
		$funcionario = $resultado[$i]['funcionario']; // ID do funcionário
		$cliente = $resultado[$i]['cliente']; // ID do cliente
		$hora = $resultado[$i]['hora']; // Hora marcada para o agendamento
		$data = $resultado[$i]['data']; // Data do agendamento
		$usuario = $resultado[$i]['usuario']; // ID do usuário que fez o agendamento
		$data_lancamento = $resultado[$i]['data_lancamento']; // Data de quando o agendamento foi registrado
		$obs = $resultado[$i]['obs']; // Observações do agendamento
		$status = $resultado[$i]['status']; // Status do agendamento (Agendado ou Concluído)
		$servico = $resultado[$i]['servico']; // ID do serviço relacionado ao agendamento
		$valor_pago = $resultado[$i]['valor_pago']; // Valor pago pelo cliente

		// Formatação do valor pago para exibição no formato brasileiro (R$ 1.234,56)
		$valor_pagoF = @number_format($valor_pago, 2, ',', '.');
		// Condicional que determina a visibilidade do valor pago. Se o valor pago for maior que 0 e o status for 'Agendado', o valor será visível.
		if ($valor_pago > 0 && $status == 'Agendado') {
			$classe_valor_pago = ''; // Classe de CSS para exibir o valor pago
		} else {
			$classe_valor_pago = 'ocultar'; // Caso contrário, oculta o valor pago
		}

		// Formatação de data e hora
		$dataF = implode('/', array_reverse(explode('-', $data))); // Formata a data no formato 'DD/MM/YYYY'
		$horaF = date("H:i", strtotime($hora)); // Formata a hora para o formato de 24 horas (HH:MM)

		// Definição de classe CSS com base no status do agendamento
		if ($status == 'Concluído') {
			$classe_linha = '';
		} else {
			$classe_linha = 'text-muted';
		}

		// Define qual imagem será mostrada para o status do agendamento
		if ($status == 'Agendado') {
			$imagem = 'icone-relogio.png'; // Imagem de relógio para agendamentos.
			$classe_status = ''; // Exibe o ícone de relógio.
		} else {
			$imagem = 'icone-relogio-verde.png'; // Imagem de relógio verde para agendamentos concluídos.
			$classe_status = 'ocultar'; // Oculta o ícone se o status não for 'Agendado'.
		}

		// Consulta o nome do usuário (quem fez o agendamento)
		$query2 = $pdo->query("SELECT * FROM usuarios01 WHERE id = '$usuario'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		if (@count($resultado2) > 0) {
			$nome_usuario = $resultado2[0]['nome']; // Nome do usuário que fez o agendamento.
		} else {
			$nome_usuario = 'Sem Usuário'; // Se não encontrado, exibe "Sem Usuário".
		}

		// Consulta o serviço associado ao agendamento para exibir o nome do serviço e o valor.
		$query2 = $pdo->query("SELECT * FROM servicos WHERE id = '$servico'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		if (@count($resultado2) > 0) {
			$nome_serv = $resultado2[0]['nome']; // Nome do serviço
			$valor_serv = $resultado2[0]['valor']; // Valor do serviço
		} else {
			$nome_serv = 'Não Lançado'; // Caso o serviço não tenha sido encontrado, exibe 'Não Lançado'.
			$valor_serv = ''; // Caso não haja valor, deixa vazio.
		}

		// Consulta informações do cliente (nome)
		$query2 = $pdo->query("SELECT * FROM clientes WHERE id = '$cliente'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		if (@count($resultado2) > 0) {
			$nome_cliente = $resultado2[0]['nome']; // Nome do cliente.
		} else {
			$nome_cliente = 'Sem Cliente'; // Se não encontrado, exibe 'Sem Cliente'.
		}

		//retirar aspas do texto do obs
		$obs = str_replace('"', "**", $obs);

		// Definição de variáveis de controle de débitos do cliente
		$classe_deb = '#043308'; // Cor padrão dos débitos (verde)
		$total_debitos = 0; // Total de débitos encontrados
		$total_pagar = 0; // Total a pagar (débitos ainda não vencidos)
		$total_vencido = 0; // Total vencido (débitos com vencimento já passado)
		$total_debitosF = 0; // Formatação do total de débitos
		$total_pagarF = 0; // Formatação do total a pagar
		$total_vencidoF = 0; // Formatação do total vencido

		// Consulta débitos do cliente que ainda não foram pagos
		$query2 = $pdo->query("SELECT * FROM receber WHERE pessoa = '$cliente' AND pago != 'Sim'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_registro2 = @count($resultado2); // Conta quantos débitos existem.
		if ($total_registro2 > 0) {
			$classe_deb = '#661109'; // Se houver débitos, muda a cor para vermelha.
			for ($i2 = 0; $i2 < $total_registro2; $i2++) {
				$valor_s = $resultado2[$i2]['valor']; // Valor do débito
				$data_vencimento = $resultado2[$i2]['data_vencimento']; // Data de vencimento do débito

				// Atualiza os totais de débitos, débitos a vencer e débitos vencidos
				$total_debitos += $valor_s;
				$total_debitosF = @number_format($total_debitos, 2, ',', '.'); // Formata o total de débitos

				if (strtotime($data_vencimento) < strtotime($data_atual)) {
					$total_vencido += $valor_s; // Débito vencido
				} else {
					$total_pagar += $valor_s; // Débito ainda a vencer
				}

				// Formata os valores de débitos
				$total_pagarF = @number_format($total_pagar, 2, ',', '.');
				$total_vencidoF = @number_format($total_vencido, 2, ',', '.');
			}
		}

		// Se o valor do serviço for igual ao valor pago, mostra "Pago", caso contrário, exibe o valor em reais.
		if ($valor_serv == $valor_pago) {
			$valor_pagoF = ' Pago';
		} else {
			$valor_pagoF = 'R$ ' . $valor_pagoF;
		}

		if ($valor_pago > 0) {
			$valor_serv = $valor_serv - $valor_pago;
		}

		// Se houver valor a pagar, mostra as informações de débito do cliente.
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
				 <!-- Exibe o horário do agendamento e botão para finalização -->
        		<div class="col-md-9">
        			<h5><strong>{$horaF}</strong> <a href="#" onclick="fecharServico('{$id}', '{$cliente}', '{$servico}', '{$valor_serv}', '{$funcionario}', '{$nome_serv}')" title="Finalizar Serviço" class="{$classe_status}"> <img class="icon-rounded-vermelho" src="img/check-square.png" width="15px" height="15px"></a>

        			<span class="{$classe_valor_pago} verde" style="font-size: 12px; font-weight: 300" >({$valor_pagoF})</span>

        			</h5>

        		</div>
        		</div>
        					
        		<hr style="margin-top:-2px; margin-bottom: 3px">                    
                    <div class="stats esc" align="center">
                      <span style="">                      
                        <small> <span class="{$ocultar_cartoes}" style=""><img class="icon-rounded-vermelho" src="img/presente.jpg" width="20px" height="20px"></span> <span style="color:{$classe_deb}; font-size:13px">{$nome_cliente}</span> (<i><span style="color:#061f9c; font-size:12px">{$nome_serv}</span></i>)</small></span>
                    </div>
                </div>
        	</div>
HTML;
	}
} else {
	// Caso não haja agendamentos para a data selecionada, exibe a mensagem.
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