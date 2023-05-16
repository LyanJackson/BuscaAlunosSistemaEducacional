<?php  
include '../../../web/seguranca.php';

$id = $_POST['id'];

if(isset($_POST['maratona-do-conhecimento-1-nota'])){
	$maratona1 = preg_replace("/[^-0-9\.]/", ".", $_POST['maratona-do-conhecimento-1-nota']);

	if($maratona1 > 10)
		$maratona1 = 10;
	if($maratona1 < 0)
		$maratona1 = -1;
	

	$query = "UPDATE notas SET `maratona-do-conhecimento-1` = '$maratona1' WHERE id = '$id'";
}

if(isset($_POST['maratona-do-conhecimento-1-dificuldade'])){
	
	$maratona1_dificuldade = htmlentities($_POST['maratona-do-conhecimento-1-dificuldade']);
		

	$query = "UPDATE notas SET `dificuldade-maratona-1` = '$maratona1_dificuldade' WHERE id = '$id'";
}

if(isset($_POST['maratona-do-conhecimento-2-nota'])){
	$maratona2 = preg_replace("/[^-0-9\.]/", ".", $_POST['maratona-do-conhecimento-2-nota']);

	if($maratona2 > 10)
		$maratona2 = 10;
	if($maratona2 < 0)
		$maratona2 = -1;
	

	$query = "UPDATE notas SET `maratona-do-conhecimento-2` = '$maratona2' WHERE id = '$id'";
}

if(isset($_POST['maratona-do-conhecimento-2-dificuldade'])){
	
	$maratona2_dificuldade = htmlentities($_POST['maratona-do-conhecimento-2-dificuldade']);

	$query = "UPDATE notas SET `dificuldade-maratona-2` = '$maratona2_dificuldade' WHERE id = '$id'";
}


if(isset($_POST['concurso-literario'])){
	$concurso_literario = preg_replace("/[^-0-9\.]/", ".", $_POST['concurso-literario']);

	if($concurso_literario > 10)
		$concurso_literario = 10;
	if($concurso_literario < 0)
		$concurso_literario = -1;
	

	$query = "UPDATE notas SET `concurso-literario` = '$concurso_literario' WHERE id = '$id'";
}

if(isset($_POST['clube-de-ciencia'])){
	$clube_de_ciencia = preg_replace("/[^-0-9\.]/", ".", $_POST['clube-de-ciencia']);
	
	if($clube_de_ciencia > 10)
		$clube_de_ciencia = 10;
	if($clube_de_ciencia < 0)
		$clube_de_ciencia = -1;

	$query = "UPDATE notas SET `clube-de-ciencia` = '$clube_de_ciencia' WHERE id = '$id'";
}

if(isset($_POST['hiddentotalpart'])){
	$participacao = preg_replace("/[^-0-9\.]/", ".", $_POST['hiddentotalpart']);

	if($participacao > 10)
		$participacao = 10;
	if($participacao < 0)
		$participacao = -1;
	

	$query = "UPDATE notas SET `estrelas` = '$participacao' WHERE id = '$id'";
}

if(isset($_POST['hiddentotalouro'])){
	$ouro = preg_replace("/[^-0-9\.]/", ".", $_POST['hiddentotalouro']);

	if($ouro > 10)
		$ouro = 10;
	if($ouro < 0)
		$ouro = -1;
	

	$query = "UPDATE notas SET `ouro` = '$ouro' WHERE id = '$id'";
}

if(isset($_POST['hiddentotalprata'])){
	$prata = preg_replace("/[^-0-9\.]/", ".", $_POST['hiddentotalprata']);

	if($prata > 10)
		$prata = 10;
	if($prata < 0)
		$prata = -1;
	

	$query = "UPDATE notas SET `prata` = '$prata' WHERE id = '$id'";
}

if(isset($_POST['hiddentotalbronze'])){
	$bronze = preg_replace("/[^-0-9\.]/", ".", $_POST['hiddentotalbronze']);

	if($bronze > 10)
		$bronze = 10;
	if($bronze < 0)
		$bronze = -1;
	

	$query = "UPDATE notas SET `bronze` = '$bronze' WHERE id = '$id'";
}


if(mysqli_query($_SG['link'], $query)){

    // Ficou ao contrário id_usuario e id_usuario_afetado
    // É a vida... Segue o jogo
    $res = mysqli_query($_SG['link'], "SELECT id_usuario FROM notas WHERE id = '$id'");
    $row = mysqli_fetch_assoc($res);    
    $id_usuario = $row['id_usuario'];
    $id_usuario_afetado = $_SESSION['usuarioID'];
    $time = date("Y-m-d H:i:s");

    mysqli_query($_SG['link'], "INSERT INTO log_sistema_alunos (id_usuario, id_usuario_afetado, acao, date_time) VALUES ('$id_usuario_afetado','$id_usuario', 'editar-notas-internas', '$time')");

	echo '<b><span class="glyphicon glyphicon-ok"></span></b>&ensp;Nota atualizada com sucesso!';
}
else {
	echo '<b><span class="glyphicon glyphicon-alert"></span></b>&ensp;Nota não atualizada, tente novamente mais tarde.';
	echo mysqli_error();
}

?>