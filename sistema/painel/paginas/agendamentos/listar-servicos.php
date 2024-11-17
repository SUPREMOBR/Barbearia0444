<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.

// Recebe o ID do funcionário enviado via método POST
$func = $_POST['func'];

// Consulta a tabela `servicos_funcionarios` para obter todos os serviços relacionados ao funcionário informado
$query = $pdo->query("SELECT * FROM servicos_funcionarios where funcionario = '$func' ");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
// Verifica se o funcionário possui algum serviço associado
if (@count($resultado) > 0) {
	// Itera sobre cada serviço encontrado para o funcionário
	for ($i = 0; $i < @count($resultado); $i++) {
		$serv = $resultado[$i]['servico']; // Obtém o ID do serviço atual

		// Busca o nome do serviço na tabela `servicos`, garantindo que o serviço esteja ativo
		$query2 = $pdo->query("SELECT * FROM servicos where id = '$serv' and ativo = 'Sim' ");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$nome_funcionario = $resultado2[0]['nome']; // Armazena o nome do serviço
		// Cria um elemento <option> com o ID e nome do serviço para exibir na lista de opções
		echo '<option value="' . $serv . '">' . $nome_funcionario . '</option>';
	}
} else {
	// Se o funcionário não possui serviços associados, exibe uma opção informando que não há serviços
	echo '<option value="">Nenhum Serviço</option>';
}
