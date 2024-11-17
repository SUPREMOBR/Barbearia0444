<?php
require_once("../sistema/conexao.php"); // Conecta ao banco de dados.
$serv = $_POST['serv']; // Obtém o ID do serv

// Consulta no banco de dados para buscar funcionários ativos que realizam atendimentos
$query = $pdo->query("SELECT * FROM usuarios01 where ativo = 'Sim' and atendimento = 'Sim'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
echo '<option value="">' . $texto_agendamento . '</option>';
// Verifica se a consulta retornou resultados
if (@count($resultado) > 0) {
	// Itera sobre cada funcionário encontrado
	for ($i = 0; $i < @count($resultado); $i++) {
		// Obtém os dados do funcionário
		$nome_funcionario = @$resultado[$i]['nome']; // Nome do funcionário
		$func = @$resultado[$i]['id']; // ID do funcionário

		// Verifica se o funcionário está vinculado ao serviço especificado
		$query2 = $pdo->query("SELECT * FROM servicos_funcionarios where servico = '$serv' and funcionario = '$func'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		// Se o funcionário estiver vinculado ao serviço, adiciona uma opção ao dropdown
		if (@count($resultado2) > 0) {
			echo '<option value="' . $func . '">' . $nome_funcionario . '</option>';
		}
	}
}
