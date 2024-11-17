<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.

// Recebe o dado enviado pelo formulário via método POST.
$id_usuario = $_POST['id']; // ID do usuário

// Inicializa a variável para indicar se o checkbox será marcado ou não.
$checked = '';

//Consulta a tabela acessos para obter permissões sem grupo (grupo = 0).
$query = $pdo->query("SELECT * FROM acessos where grupo = 0 order by id asc");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);

if ($total_registro > 0) {
	// Exibe o título "Sem Grupo" e inicia uma linha para listar as permissões.
	echo '<span class="titulo-grupo"><b>Sem Grupo</b></span><br><div class="row">';
	for ($i = 0; $i < $total_registro; $i++) {
		foreach ($resultado[$i] as $key => $value) {
		}
		// Itera sobre as permissões
		$nome = $resultado[$i]['nome'];  // Nome da permissão.
		$chave = $resultado[$i]['chave'];
		$id = $resultado[$i]['id']; // ID da permissão.

		// Verifica se o usuário já possui a permissão
		$query2 = $pdo->query("SELECT * FROM usuarios_permissoes where usuario = '$id_usuario' and permissao = '$id'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_registro2 = @count($resultado2);
		// Define se o checkbox estará marcado ou não.
		if ($total_registro2 > 0) {
			$checked = 'checked';
		} else {
			$checked = '';
		}


		echo '
		<div class="form-check col-md-3">
		<input class="form-check-input" type="checkbox" value="" id="" ' . $checked . ' onclick="adicionarPermissao(' . $id . ',' . $id_usuario . ')">
		<label class="labelcheck" >
		' . $nome . '
		</label>
		</div>
		';
	}

	echo '</div><hr>';
}

// Consulta todos os grupos de permissões na tabela "grupo_acessos".
$query = $pdo->query("SELECT * FROM grupo_acessos ORDER BY id asc");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
if ($total_registro > 0) {
	// Itera sobre os grupos de acessos.
	for ($i = 0; $i < $total_registro; $i++) {
		foreach ($resultado[$i] as $key => $value) {
		}
		$id = $resultado[$i]['id']; // ID do grupo.
		$nome = $resultado[$i]['nome']; // Nome do grupo.

		// Exibe o título do grupo e inicia uma nova linha para suas permissões.
		echo '<span class="titulo-grupo"><b>' . $nome . '</b></span><br><div class="row">';

		$checked = '';
		// Consulta as permissões associadas ao grupo atual.
		$query3 = $pdo->query("SELECT * FROM acessos where grupo = '$id' order by id asc");
		$resultado3 = $query3->fetchAll(PDO::FETCH_ASSOC);
		$total_registro3 = @count($resultado3);

		if ($total_registro3 > 0) {
			// Itera sobre as permissões do grupo.
			for ($i3 = 0; $i3 < $total_registro3; $i3++) {
				foreach ($resultado3[$i3] as $key => $value) {
				}
				$nome = $resultado3[$i3]['nome']; // Nome da permissão.
				$chave = $resultado3[$i3]['chave'];
				$id = $resultado3[$i3]['id']; // ID da permissão.

				// Verifica se o usuário já possui esta permissão.
				$query2 = $pdo->query("SELECT * FROM usuarios_permissoes where usuario = '$id_usuario' and permissao = '$id'");
				$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
				$total_registro2 = @count($resultado2);
				// Define se o checkbox estará marcado ou não.
				if ($total_registro2 > 0) {
					$checked = 'checked';
				} else {
					$checked = '';
				}


				echo '
		<div class="form-check col-md-3">
		<input class="form-check-input" type="checkbox" value="" id="" ' . $checked . ' onclick="adicionarPermissao(' . $id . ',' . $id_usuario . ')">
		<label class="labelcheck" >
		' . $nome . '
		</label>
		</div>
		';
			}

			echo '</div><hr>';
		}
	}
}
