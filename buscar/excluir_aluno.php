<?php 
	include '../../../web/seguranca.php';

	$id_responsavel = $_SESSION['usuarioID'];
	$id = $_POST['id'];

	$query = "DELETE FROM usuario, alunos WHERE id_usuario = $id";

	if(mysqli_query($_SG['link'], $query)){

	    // Ficou ao contrário id_usuario e id_usuario_afetado
	    // É a vida... Segue o jogo
	    $id_usuario_afetado = $_SESSION['usuarioID'];
	    $time = date("Y-m-d H:i:s");

	    mysqli_query($_SG['link'], "INSERT INTO log_sistema_alunos (id_usuario, id_usuario_afetado, acao, date_time) VALUES ('$id_usuario_afetado','$id', 'excluir', '$time')");

		echo '<span class="glyphicon glyphicon-ok"></span></b>&ensp;Aluno excluído com sucesso!';
	}else {
		echo '<span class="glyphicon glyphicon-times"></span></b>&ensp;Aluno não pode ser excluído, tente novamente mais tarde.';
		echo '<script>alert("'.mysqli_error($_SG['link']).'")</script>';
	}
	
?>

