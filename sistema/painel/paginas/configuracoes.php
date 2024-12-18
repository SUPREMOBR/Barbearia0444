<?php
require_once("verificar.php"); // Inclui o arquivo "verificar.php", para autenticar o usuário ou verificar permissões.
require_once("../conexao.php"); // Conecta ao banco de dados.

// Define o nome da página atual como 'configurações'.
$pag = 'configuracoes';
$data_atual = date('Y-m-d');

//verificar se ele tem a permissão de estar nessa página
if (@$configuracoes == 'ocultar') {
	echo "<script>window.location='../index.php'</script>";
	exit();
}

?>

<div class="row">

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

				<div class="col-md-2">
					<div class="form-group">
						<label for="exampleInputEmail1">Tipo Relatório</label>
						<!-- Campo de seleção para escolher o tipo de relatório (PDF ou HTML) -->
						<select class="form-control" name="tipo_rel" id="tipo_rel">
							<!-- Opção para PDF, com verificação para marcar como selecionada se a variável $tipo_rel for "PDF" -->
							<option value="PDF" <?php if ($tipo_rel == 'PDF') { ?> selected <?php } ?>>PDF</option>
							<!-- Opção para HTML, com verificação para marcar como selecionada se a variável $tipo_rel for "HTML" -->
							<option value="HTML" <?php if ($tipo_rel == 'HTML') { ?> selected <?php } ?>>HTML</option>
						</select>
					</div>
				</div>

			</div>

			<div class="row">

				<div class="col-md-2">
					<div class="form-group">
						<label for="exampleInputEmail1">Api Pagamento</label>
						<select class="form-control" name="pagamento_api" id="pagamento_api">
							<!-- Campo de seleção para definir o uso de API para pagamento (Sim ou Não) -->
							<option value="Sim" <?php if ($pagamento_api == 'Sim') { ?> selected <?php } ?>>Sim</option>
							<option value="Não" <?php if ($pagamento_api == 'Não') { ?> selected <?php } ?>>Não</option>

						</select>
					</div>
				</div>

				<div class="col-md-2">
					<div class="form-group">
						<label for="exampleInputEmail1">Token Api</label>
						<input type="text" class="form-control" id="token" name="token" placeholder="Token Api Whatsapp" value="<?php echo @$token ?>">
					</div>
				</div>

				<div class="col-md-2">
					<div class="form-group">
						<label for="exampleInputEmail1">Instancia API</label>
						<input type="text" class="form-control" id="instancia" name="instancia" placeholder="Instância Api Whatsapp" place value="<?php echo @$instancia ?>">
					</div>
				</div>

				<div class="col-md-2">
					<div class="form-group">
						<label for="exampleInputEmail1">Seletor de Api</label>
						<select class="form-control" name="api" id="api">
							<option value="menuia" <?php if ($api == 'menuia') { ?> selected <?php } ?>>Menuia</option>
							<option value="outros" <?php if ($api == 'outros') { ?> selected <?php } ?>>Word Mensagens</option>
						</select>
					</div>
				</div>

			</div>

			<div class="row">

				<div class="col-md-4">

					<div class="form-group">
						<label for="exampleInputEmail1">Endereço Barbearia</label>
						<input type="text" class="form-control" id="endereco_sistema" name="endereco_sistema" placeholder="Rua X Numero X Bairro Cidade" value="<?php echo $endereco_sistema ?>">
					</div>
				</div>

				<div class="col-md-3">

					<div class="form-group">
						<label for="exampleInputEmail1">Telefone Fixo Barbearia</label>
						<input type="text" class="form-control" id="telefone_fixo_sistema" name="telefone_fixo_sistema" placeholder="Fixo" value="<?php echo $telefone_fixo_sistema ?>">
					</div>
				</div>

				<div class="col-md-3">

					<div class="form-group">
						<label for="exampleInputEmail1">Whatsapp Barbearia</label>
						<input type="text" class="form-control" id="whatsapp_sistema" name="whatsapp_sistema" placeholder="Whatsapp" value="<?php echo $whatsapp_sistema ?>" required>
					</div>
				</div>

			</div>

			<div class="row">
				<div class="col-md-2">
					<div class="form-group">
						<label for="exampleInputEmail1">Taxa Pagamento Serviço</label>
						<select class="form-control" name="taxa_sistema" id="taxa_sistema">
							<!-- Campo de seleção para definir quem paga a taxa de serviço (Cliente ou Empresa) -->
							<option value="Cliente" <?php if (@$taxa_sistema == 'Cliente') { ?> selected <?php } ?>>Cliente Paga</option>
							<option value="Empresa" <?php if (@$taxa_sistema == 'Empresa') { ?> selected <?php } ?>>Salão Paga</option>

						</select>
					</div>
				</div>

				<div class="col-md-2">
					<div class="form-group">
						<label for="exampleInputEmail1">Tipo Comissão</label>
						<select class="form-control" name="tipo_comissao" id="tipo_comissao">
							<!-- Campo de seleção para escolher o tipo de comissão (Porcentagem ou R$) -->
							<option value="Porcentagem" <?php if ($tipo_comissao == 'Porcentagem') { ?> selected <?php } ?>>Porcentagem</option>
							<option value="R$" <?php if ($tipo_comissao == 'R$') { ?> selected <?php } ?>>R$ Reais</option>
						</select>
					</div>
				</div>

				<div class="col-md-3">
					<div class="form-group">
						<label for="exampleInputEmail1">Lançamento Comissão </label>
						<!-- Campo de seleção para definir o momento do lançamento da comissão (Sempre ou Pago) -->
						<select class="form-control" name="lancamento_comissao" id="lancamento_comissao">
							<option value="Sempre" <?php if ($lancamento_comissao == 'Sempre') { ?> selected <?php } ?>>Serviço Pendente e Pago</option>
							<option value="Pago" <?php if ($lancamento_comissao == 'Pago') { ?> selected <?php } ?>>Serviço Pago</option>
						</select>
					</div>
				</div>

			</div>

			<div class="row">

				<div class="col-md-4">
					<div class="form-group">
						<label for="exampleInputEmail1">Texto Agendamento</label>
						<input maxlength="30" type="text" class="form-control" id="texto_agendamento" name="texto_agendamento" placeholder="Selecionar Cabelereira" value="<?php echo $texto_agendamento ?>">
					</div>
				</div>

				<div class="col-md-2">
					<div class="form-group">
						<label for="exampleInputEmail1">Mensagem Agendamento</label>
						<select class="form-control" name="msg_agendamento" id="msg_agendamento">
							<!-- Campo de seleção para definir a necessidade de mensagem no agendamento (Sim, Não ou Api) -->
							<option value="Sim" <?php if ($msg_agendamento == 'Sim') { ?> selected <?php } ?>>Sim</option>
							<option value="Não" <?php if ($msg_agendamento == 'Não') { ?> selected <?php } ?>>Não</option>
							<option value="Api" <?php if ($msg_agendamento == 'Api') { ?> selected <?php } ?>>Api Paga</option>
						</select>
					</div>
				</div>

				<div class="col-md-2">
					<div class="form-group">
						<label for="exampleInputEmail1">Manter Agendamento Dias</label>
						<input type="number" class="form-control" id="agendamento_dias" name="agendamento_dias" value="<?php echo $agendamento_dias ?>" placeholder="Manter no Banco de Dados">
					</div>
				</div>

				<div class="col-md-2">
					<div class="form-group">
						<label for="exampleInputEmail1">% Agendamento</label>
						<input type="number" class="form-control" id="porc_servico" name="porc_servico" placeholder="% pagar Agendamento" value="<?php echo $porc_servico ?>">
					</div>
				</div>

			</div>

			<div class="row">

				<div class="col-md-2">
					<div class="form-group">
						<label for="exampleInputEmail1">Cidade</label>
						<input type="text" class="form-control" id="cidade_sistema" name="cidade_sistema" value="<?php echo $cidade_sistema ?>" placeholder="Cidade para o contrato">
					</div>
				</div>

				<div class="col-md-2">
					<div class="form-group">
						<label for="exampleInputEmail1">CNPJ</label>
						<input type="text" class="form-control" id="cnpj_sistema" name="cnpj_sistema" value="<?php echo $cnpj_sistema ?>">
					</div>
				</div>

				<div class="col-md-2">
					<div class="form-group">
						<label for="exampleInputEmail1">Horas Confirmação</label>
						<input type="number" class="form-control" id="minutos_aviso" name="minutos_aviso" placeholder="Alerta Agendamento" value="<?php echo @$minutos_aviso ?>">
					</div>
				</div>

				<div class="col-md-2">
					<div class="form-group">
						<label for="exampleInputEmail1">Itens Paginação</label>
						<input type="number" class="form-control" id="itens_pag" name="itens_pag" value="<?php echo $itens_pag ?>" placeholder="">
					</div>
				</div>

			</div>

			<div class="row">

				<div class="col-md-4">
					<div class="form-group">
						<label for="exampleInputEmail1">Instagram</label>
						<input type="text" class="form-control" id="instagram_sistema" name="instagram_sistema" placeholder="Link do Perfil no Instagram" value="<?php echo $instagram_sistema ?>">
					</div>
				</div>

			</div>

			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label for="exampleInputEmail1">Texto Rodapé Site <small>(255) Caracteres</small></label>
						<input maxlength="255" type="text" class="form-control" id="texto_rodape" name="texto_rodape" placeholder="Texto para o Rodapé do site" value="<?php echo $texto_rodape ?>">
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label for="exampleInputEmail1">Texto Sobre (Site) <small>(600) Caracteres</small></label>
						<input maxlength="255" type="text" class="form-control" id="texto_sobre" name="texto_sobre" placeholder="Texto para a área Sobre a empresa no site" value="<?php echo $texto_sobre ?>">
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
						<img src="../img/<?php echo $logo_sistema ?>" width="80px" id="target-logo">
					</div>
				</div>

				<div class="col-md-4">
					<div class="form-group">
						<label>Ícone (*Png)</label>
						<input class="form-control" type="file" name="foto-icone" onChange="carregarImgIcone();" id="foto-icone">
					</div>
				</div>
				<div class="col-md-2">
					<div id="divImg">
						<img src="../img/<?php echo $icone_sistema ?>" width="50px" id="target-icone">
					</div>
				</div>

			</div>

			<div class="row">

				<div class="col-md-4">
					<div class="form-group">
						<label>Logo Relatório (*Jpg)</label>
						<input class="form-control" type="file" name="foto-logo-rel" onChange="carregarImgLogoRel();" id="foto-logo-rel">
					</div>
				</div>
				<div class="col-md-2">
					<div id="divImg">
						<img src="../img/<?php echo $logo_rel ?>" width="80px" id="target-logo-rel">
					</div>
				</div>

				<div class="col-md-4">
					<div class="form-group">
						<label>Ícone Site (*png)</label>
						<input class="form-control" type="file" name="foto-icone-site" onChange="carregarImgIconeSite();" id="foto-icone-site">
					</div>
				</div>
				<div class="col-md-2">
					<div id="divImg">
						<img src="../../images/<?php echo $icone_site ?>" width="50px" id="target-icone-site">
					</div>
				</div>

			</div>

			<div class="row">

				<div class="col-md-4">
					<div class="form-group">
						<label>Imagem Área Sobre (Site)</label>
						<input class="form-control" type="file" name="foto-sobre" onChange="carregarImgSobre();" id="foto-sobre">
					</div>
				</div>
				<div class="col-md-2">
					<div id="divImg">
						<img src="../../images/<?php echo $imagem_sobre ?>" width="80px" id="target-sobre">
					</div>
				</div>

				<div class="col-md-4">
					<div class="form-group">
						<label>Imagem Banner Index <small>(1500x1000)</small></label>
						<input class="form-control" type="file" name="foto-banner-index" onChange="carregarImgBannerIndex();" id="foto-banner-index">
					</div>
				</div>
				<div class="col-md-2">
					<div id="divImg">
						<img src="../../images/<?php echo $img_banner_index ?>" width="80px" id="target-banner-index">
					</div>
				</div>

			</div>

			<br>
			<small>
				<div id="mensagem-config" align="center"></div>
			</small>
		</div>
		<div class="modal-footer">
			<button type="submit" class="btn btn-success">Salvar Dados</button>
		</div>
	</form>

</div>