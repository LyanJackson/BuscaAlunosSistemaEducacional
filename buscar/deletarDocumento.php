<?php 
	include '../../../web/seguranca.php';

	$id = $_POST['id'];
	$id_usuario = $_POST['id_usuario'];
	$arquivo = htmlentities($_POST['documento']);
	$path = "../documentos_alunos/";
	$pasta = $path.$arquivo;	

	$query = "DELETE FROM documentos_alunos WHERE id = '$id'";

	if(unlink($pasta) AND mysqli_query($_SG['link'], $query)){
		
		$id_responsavel = $_SESSION['usuarioID'];
	    $time = date("Y-m-d H:i:s");

	    $res = mysqli_query($_SG['link'], "INSERT INTO log_sistema_alunos (id_usuario, id_usuario_afetado, acao, date_time) VALUES ('$id_responsavel','$id_usuario', 'deletar-documento', '$time')");

		echo 'Documento deletado com sucesso';

	}
	else{
		echo 'Erro no banco de dados, aguarde alguns minutos e tente novamente.';
	}

 ?>