<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'servicos'; // Define o nome da tabela no banco de dados


// Recebe os dados passados via POST (dados do serviço)
$id = $_POST['id']; // ID do serviço a ser atualizado (ou vazio se for um novo serviço)
$nome = $_POST['nome']; // Nome do serviço
$valor = $_POST['valor']; // Valor do serviço
$valor = str_replace(',', '.', $valor); // Converte o valor, substituindo vírgula por ponto
$comissao = $_POST['comissao']; // Comissão do serviço
$comissao = str_replace(',', '.', $comissao); // Converte a comissão, substituindo vírgula por ponto
$comissao = str_replace('%', '', $comissao); // Remove o símbolo '%' da comissão
$tempo = $_POST['tempo']; // Tempo do serviço
$categoria = $_POST['categoria']; // Categoria do serviço

// Valida se a categoria foi selecionada
if ($categoria == 0) {
	echo 'Cadastre uma Categoria de Serviços para o Serviço'; // Se categoria não for selecionada, exibe mensagem de erro
	exit(); // Interrompe a execução do script
}

// Valida se o nome do serviço já existe no banco
$query = $pdo->query("SELECT * from $tabela where nome = '$nome'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
if (@count($resultado) > 0 and $id != $resultado[0]['id']) {
	echo 'Nome já Cadastrado, escolha outro!!';
	exit();
}

// Valida se a foto foi alterada
$query = $pdo->query("SELECT * FROM $tabela where id = '$id'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
if ($total_registro > 0) {
	$foto = $resultado[0]['foto'];
} else {
	$foto = 'sem-foto.jpg';
}


//SCRIPT PARA SUBIR FOTO NO SERVIDOR
$nome_img = date('d-m-Y H:i:s') . '-' . @$_FILES['foto']['name'];
$nome_img = preg_replace('/[ :]+/', '-', $nome_img);

$caminho = '../../img/servicos/' . $nome_img;

$imagem_temp = @$_FILES['foto']['tmp_name'];

if (@$_FILES['foto']['name'] != "") {
	$ext = pathinfo($nome_img, PATHINFO_EXTENSION);
	if ($ext == 'png' or $ext == 'jpg' or $ext == 'jpeg' or $ext == 'gif') {

		//EXCLUO A FOTO ANTERIOR
		if ($foto != "sem-foto.jpg") {
			@unlink('../../img/servicos/' . $foto);
		}

		$foto = $nome_img;

		move_uploaded_file($imagem_temp, $caminho);
	} else {
		echo 'Extensão de Imagem não permitida!';
		exit();
	}
}

// Se o ID for vazio (novo serviço), faz um INSERT
if ($id == "") {
	$query = $pdo->prepare("INSERT INTO $tabela SET nome = :nome, categoria = '$categoria', valor = :valor, ativo = 'Sim', foto = '$foto', 
	comissao = :comissao, tempo = :tempo");
} else {
	// Caso contrário, faz um UPDATE
	$query = $pdo->prepare("UPDATE $tabela SET nome = :nome, categoria = '$categoria', valor = :valor, foto = '$foto', comissao = :comissao, 
	tempo = :tempo WHERE id = '$id'");
}

$query->bindValue(":nome", "$nome");
$query->bindValue(":valor", "$valor");
$query->bindValue(":comissao", "$comissao");
$query->bindValue(":tempo", "$tempo");
$query->execute();

echo 'Salvo com Sucesso';
