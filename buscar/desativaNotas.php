<?php 
include '../../../web/seguranca.php';

$id = $_POST['id'];

$query = "DELETE FROM notas WHERE id = '$id'";
$res = mysqli_query($_SG['link'], "SELECT id_usuario FROM notas WHERE id = '$id'");
$row = mysqli_fetch_assoc($res);    

if(mysqli_query($_SG['link'], $query)){

    // Ficou ao contrário id_usuario e id_usuario_afetado
    // É a vida... Segue o jogo
    $id_usuario = $row['id_usuario'];

    $id_usuario_afetado = $_SESSION['usuarioID'];
    $time = date("Y-m-d H:i:s");

    mysqli_query($_SG['link'], "INSERT INTO log_sistema_alunos (id_usuario, id_usuario_afetado, acao, date_time) VALUES ('$id_usuario_afetado','$id_usuario', 'excluir-notas-internas', '$time')");

	echo '<b><span class="glyphicon glyphicon-ok"></span></b>&ensp;Nota excluida com sucesso!';
}
else {
	echo '<b><span class="glyphicon glyphicon-alert"></span></b>&ensp;Nota não pode ser excluida, tente novamente mais tarde.';
	echo mysqli_error();
}

?>