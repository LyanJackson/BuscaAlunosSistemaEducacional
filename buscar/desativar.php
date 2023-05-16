<?php 
	include '../../../web/seguranca.php';
	include_once("../../../web/class.phpmailer.php");

	$id_responsavel = $_SESSION['usuarioID'];
	$ativo = $_POST['ativo'];
	$id = $_POST['id'];
	
	$user = mysqli_fetch_assoc(mysqli_query($_SG['link'], "SELECT usuario.nome, usuario.h, usuario.serie, usuario.id_usuario, escola.nome_escola, escola.cidade FROM usuario JOIN escola ON usuario.id_escola = escola.id_escola WHERE usuario.id_usuario = '$id'"));

	if(isset($_POST['justificativa']))
		$justificativa = htmlentities($_POST['justificativa']);

	$date = date("Y");

	if($ativo == 1){
		$query = "UPDATE usuario SET h = 0 WHERE id_usuario = $id";

		$query2 = "INSERT INTO justificativa_afastamento (id_usuario, motivo, id_responsavel, `ano`) VALUES ('$id', '$justificativa', '$id_responsavel', '$date')";

		if(mysqli_query($_SG['link'], $query) AND mysqli_query($_SG['link'], $query2)){
			
			sendEmails($user, $ativo);

		    // Ficou ao contrário id_usuario e id_usuario_afetado
		    // É a vida... Segue o jogo
		    $id_usuario_afetado = $_SESSION['usuarioID'];
		    $time = date("Y-m-d H:i:s");

		    mysqli_query($_SG['link'], "INSERT INTO log_sistema_alunos (id_usuario, id_usuario_afetado, acao, date_time) VALUES ('$id_usuario_afetado','$id', 'desativar', '$time')");

    		echo '<span class="glyphicon glyphicon-ok"></span></b>&ensp;Aluno desativado com sucesso!';
		}else {
    		echo '<span class="glyphicon glyphicon-times"></span></b>&ensp;Aluno não pode ser desativado, tente novamente mais tarde.';
			echo '<script>alert("'.mysqli_error().'")</script>';
		}
	}
	else {
		$query = "UPDATE usuario SET h = 1 WHERE id_usuario = $id";

		if(mysqli_query($_SG['link'], $query)){

			sendEmails($user, $ativo);

		    // Ficou ao contrário id_usuario e id_usuario_afetado
		    // É a vida... Segue o jogo
		    $id_usuario_afetado = $_SESSION['usuarioID'];
		    $time = date("Y-m-d H:i:s");

		    mysqli_query($_SG['link'], "INSERT INTO log_sistema_alunos (id_usuario, id_usuario_afetado, acao, date_time) VALUES ('$id_usuario_afetado','$id', 'ativar', '$time')");

    		echo '<span class="glyphicon glyphicon-ok"></span></b>&ensp;Aluno ativado com sucesso!';
		}else {
			echo '<div class="alert-danger">'.mysqli_error().'</div>';
		}		
	}



function sendEmails($user, $ativo){

	$msg = "<b>[INFORME]</b><br><br>";
	if($ativo == 0){
		$msg .= "O aluno " . strtoupper(html_entity_decode($user['nome'])) . " foi ativado da escola " . strtoupper(html_entity_decode($user['nome_escola'])) . " em " . strtoupper(html_entity_decode($user['cidade']));

	}
	elseif($ativo == 1) {
		$msg .= "O aluno " . strtoupper(html_entity_decode($user['nome'])) . " foi desativado da escola " . strtoupper(html_entity_decode($user['nome_escola'])) . " em " . strtoupper(html_entity_decode($user['cidade']));
	}

    $msg .= '<br><br>';
    $msg .= '*ALTERADO POR: ' . $_SESSION['usuarioNome'] . ' às ' . date("d/m/Y H:i:s", time());	

	$usuario = 'noreply@futurocientista.net';
	$senha = 'pfc20102017';
	$nomeRemetente = ''.$nome.'';
	$emaiRemetente = ''.$email.'';
	$Subject = utf8_decode('[INFORME DO SISTEMA] Alteração no status de um aluno do Ensino Fundamental');
	$Message = ''.$msg.'';
	$Host = 'mail.' . substr(strstr($usuario, '@'), 1);

	$mail = new PHPMailer();

	$mail->IsSMTP(); // telling the class to use SMTP
	$mail->Host = $Host;
	$mail->SMTPAuth = true;                  // enable SMTP authentication
	$mail->SMTPKeepAlive = true;                  // SMTP connection will not close after each email sent
	$mail->Port = "587";                    // set the SMTP port for the GMAIL server
	$mail->Username = $usuario;
	$mail->Password = $senha;
	$mail->isHTML(true);

	$mail->SetFrom('noreply@futurocientista.net', 'Futuro Cientista');
	// $mail->AddReplyTo($emaiRemetente, $nomeRemetente);
	$mail->Body = $Message;
	$mail->Subject = $Subject;
	// $mail->AddAddress("bernardopcamargo@gmail.com", "Bernardo");
	//$mail->AddAddress("andressa_rleite@hotmail.com", "Andressa Ribeiro");
	//$mail->AddAddress("paolaamvieira@gmail.com", "Paola Vieira");
	// $mail->AddAddress("fabiolimaleite@gmail.com", "Fábio Leite");
	//$mail->AddAddress("programafuturocientista@gmail.com", "PFC");
	//$mail->AddAddress("eliezer-lorena@hotmail.com", "Eliezer Lorena");


	// $emails = ['bernardopcamargo@gmail.com'];	

	if (!$mail->Send()) {
	    $mensagemRetorno = 'Erro ao enviar e-mail: ' . print($mail->ErrorInfo);
		echo '<script>  alert("' . $mensagemRetorno . '");</script>';
	}

}

?>

