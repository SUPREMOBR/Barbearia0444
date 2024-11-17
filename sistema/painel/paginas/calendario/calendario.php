<script>
	// Função para exibir o modal de agendamentos.
	function modalShow() {
		$('#modalShow').modal('show');
	}

	$(document).ready(function() {
		$('#calendar').fullCalendar({

			// Configuração do cabeçalho do calendário.
			header: {
				left: 'prev,next today', // Botões de navegação.
				center: 'title', // Título centralizado.
				right: 'month,agendaWeek,agendaDay,listYear' // Visualizações disponíveis.
			},

			defaultDate: '<?php echo date('Y-m-d'); ?>', // Define a data padrão para a data atual.
			editable: true, // Permite que os eventos sejam editáveis.
			navLinks: true, // Ativa links de navegação para dias e semanas.
			eventLimit: true, // Limita o número de eventos visíveis.

			selectable: true, // Permite seleção de intervalo de datas.
			selectHelper: true,
			select: function(start, end) {
				return;
				$('#ModalAdd #inicio').val(moment(start).format('DD-MM-YYYY HH:mm:ss'));
				$('#ModalAdd #termino').val(moment(end).format('DD-MM-YYYY HH:mm:ss'));
				$('#ModalAdd').modal('show'); // Exibe o modal para adicionar evento.
			},

			// Renderização de eventos no calendário.
			eventRender: function(event, element) {
				return;
				element.bind('click', function() {
					$('#ModalEdit #id_evento').val(event.id);
					$('#ModalEdit #titulo').val(event.title);
					$('#ModalEdit #descricao').val(event.description);
					$('#ModalEdit #cor').val(event.color);
					$('#ModalEdit #convidado').val(event.cliente);
					$('#ModalEdit #remetente').val(event.servico);
					$('#ModalEdit #status').val(event.status);
					$('#ModalEdit #inicio').val(event.start.format('DD-MM-YYYY HH:mm:ss'));
					$('#ModalEdit').modal('show'); // Exibe o modal de edição.
				});
			},

			eventDrop: function(event, delta, revertFunc) {
				edit(event); // Função para salvar modificações ao arrastar.
			},

			eventResize: function(event, dayDelta, minuteDelta, revertFunc) {
				edit(event); // Função para salvar modificações ao redimensionar.
			},


			events: [
				// Loop que percorre cada registro de evento no banco de dados.
				<?php for ($i = 0; $i < $total_registro; $i++) {
					// Define a data e hora de início e de término do evento.
					$data_inicio = $resultado[$i]['data'] . " " . $resultado[$i]['hora'];
					$data_final = $resultado[$i]['data'] . " " . $resultado[$i]['hora'];

					$hora_inicio = $resultado[$i]['hora'];
					$hora_final = $resultado[$i]['hora'];

					// Verifica se a hora de início e de fim são nulas ou "00:00:00".
					// Se forem, o evento usa apenas a data sem uma hora específica.
					if ($hora_inicio == '00:00:00' || $hora_inicio == '') {
						$start = $resultado[$i]['data'];
					} else {
						$start = $data_inicio;
					}
					if ($hora_final == '00:00:00' || $hora_inicio == '') {
						$end = $resultado[$i]['data'];
					} else {
						$end = $data_final;
					}

					// Obtém o nome do cliente associado ao evento.
					$cliente = $resultado[$i]['cliente'];
					$query2 = $pdo->query("SELECT * FROM clientes where id = '$cliente'");
					$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
					// Se o cliente existir no banco de dados, armazena o nome; caso contrário, define "Sem Cliente".
					if (@count($resultado2) > 0) {
						$nome_cliente = $resultado2[0]['nome'];
					} else {
						$nome_cliente = 'Sem Cliente';
					}

					// Obtém o nome do funcionário associado ao evento.
					$funcionario = $resultado[$i]['funcionario'];
					$query2 = $pdo->query("SELECT * FROM usuarios01 where id = '$funcionario'");
					$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
					// Se o funcionário existir no banco de dados, armazena o nome; caso contrário, define "Sem Registro".
					if (@count($resultado2) > 0) {
						$profissional = $resultado2[0]['nome'];
					} else {
						$profissional = 'Sem Registro';
					}

					// Obtém o nome do serviço associado ao evento.
					$servico = $resultado[$i]['servico'];
					$query2 = $pdo->query("SELECT * FROM servicos where id = '$servico'");
					$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
					if (@count($resultado2) > 0) {
						// Se o serviço existir, armazena o nome; caso contrário, define "Sem Serviço".
						$nome_servico = $resultado2[0]['nome'];
					} else {
						$nome_servico = 'Sem Serviço';
					}

					// Define a cor do evento no calendário.
					// A cor é alterada com base no status do evento: "Agendado" (vermelho) ou qualquer outro (verde).
					if ($resultado[$i]['status'] == "Agendado") {
						$cor_agd = "#80050b";
					} else {
						$cor_agd = "#013b11";
					}

					// Imprime os dados do evento que será interpretado pelo calendário.
				?> {
						id: '<?php echo $resultado[$i]['id'] ?>',
						title: '<?php echo $profissional ?> / Serviço <?php echo $nome_servico ?> / Cliente <?php echo $nome_cliente ?>',
						description: '<?php echo $nome_servico ?>',
						start: '<?php echo $start; ?>',
						end: '<?php echo $end; ?>',
						color: '<?php echo $cor_agd ?>',
						cliente: '<?php echo $resultado[$i]['cliente'] ?>',
						servico: '<?php echo $resultado[$i]['servico'] ?>',
						status: '<?php echo $resultado[$i]['status'] ?>',
					},
				<?php } ?>
			]
		});
		// Função para editar eventos.
		function edit(event) {
			alert('recurso indisponível')
			return;
			start = event.start.format('DD-MM-YYYY HH:mm:ss');
			if (event.end) {
				end = event.end.format('DD-MM-YYYY HH:mm:ss');
			} else {
				end = start;
			}

			id_evento = event.id;

			Event = [];
			Event[0] = id_evento;
			Event[1] = start;
			Event[2] = end;

			$.ajax({
				url: 'evento/action/eventoEditData.php',
				type: "POST",
				data: {
					Event: Event
				},
				success: function(rep) {
					if (rep == 'OK') {
						alert('Modificação Salva!');
					} else {
						alert('Falha ao salvar, tente novamente!');
					}
				}
			});
		}
	});
</script>