<?php 
	
	include '../../../web/seguranca.php';

	$id = $_POST['id_usuario'];
	$doc = $_FILES['doc'];
	$nome_original = $doc['name'];

	// Pasta onde as imagens serao salvas
	$_UP['pasta_doc'] = '../documentos_alunos/';

	$extensoes = explode(".", $nome_original);
	$extensao = $extensoes[count($extensoes)-1];

    $nome_final = md5(uniqid(time())) . "." . $extensao;

	if (move_uploaded_file($doc['tmp_name'], $_UP['pasta_doc'] . $nome_final)) {
		
		$query = "INSERT INTO documentos_alunos (id_usuario, documento, nome_original) VALUES ('$id', '$nome_final', '$nome_original')";
		if(mysqli_query($_SG['link'], $query)) {
			$id_usuario = $_SESSION['usuarioID'];
		    $time = date("Y-m-d H:i:s");

		    $res = mysqli_query($_SG['link'], "INSERT INTO log_sistema_alunos (id_usuario, id_usuario_afetado, acao, date_time) VALUES ('$id_usuario','$id', 'inserir-documento', '$time')");


			echo 'Documento adicionado com sucesso!';
		}
		else{
			echo 'Erro no banco de dados, entre em contato com o administrador!';
			echo mysqli_error();
		}
	}
	else{
		echo 'Erro no upload';
	}
 ?>