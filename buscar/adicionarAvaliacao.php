<?php 
	include '../../../web/seguranca.php';

	$id = $_POST['id'];
	$avaliacao = $_POST['avaliacao'];
	$ano = $_POST['ano'];


	$query = "UPDATE notas_externas SET avaliacao = '".$avaliacao."' WHERE id = ".$id." AND ano LIKE '".$ano."'";

	// echo '<script>alert("'.$query.'")</script>';

	if(mysqli_query($_SG['link'], $query)){
	    // Ficou ao contrário id_usuario e id_usuario_afetado
	    // É a vida... Segue o jogo
	    $res = mysqli_query($_SG['link'], "SELECT id_usuario FROM notas_externas WHERE id = '$id'");
	    $row = mysqli_fetch_assoc($res);    
	    $id_usuario = $row['id_usuario'];	    
	    $id_usuario_afetado = $_SESSION['usuarioID'];
	    $time = date("Y-m-d H:i:s");

	    mysqli_query($_SG['link'], "INSERT INTO log_sistema_alunos (id_usuario, id_usuario_afetado, acao, date_time) VALUES ('$id_usuario_afetado','$id_usuario', 'cadastrar-avaliacao', '$time')");

		echo '<b><span class="glyphicon glyphicon-ok"></span></b>&ensp;Avaliação adicionada com sucesso!';
	}
	else {
		echo '<b><span class="glyphicon glyphicon-ok"></span></b>&ensp;Erro enviar a Avaliação';
		echo mysqli_error();
	}
 ?>