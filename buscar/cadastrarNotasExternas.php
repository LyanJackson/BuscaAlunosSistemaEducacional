<?php 
include '../../../web/seguranca.php';

$id_usuario = $_POST['id_usuario'];
$ano = $_POST['ano'];

$query = "INSERT INTO notas_externas (id_usuario, `matematica`, `portugues`, `historia`, `geografia`, ciencias, ano) VALUES ('$id_usuario', '-1', '-1', '-1', '-1', '-1', '$ano')";

if(mysqli_query($_SG['link'], $query)){

    // Ficou ao contrário id_usuario e id_usuario_afetado
    // É a vida... Segue o jogo
    $id_usuario_afetado = $_SESSION['usuarioID'];
    $time = date("Y-m-d H:i:s");

    mysqli_query($_SG['link'], "INSERT INTO log_sistema_alunos (id_usuario, id_usuario_afetado, acao, date_time) VALUES ('$id_usuario_afetado','$id_usuario', 'cadastrar-notas-externas', '$time')");

	echo '<b><span class="glyphicon glyphicon-ok"></span></b>&ensp;Nota cadastrada com sucesso!';
}
else {
	echo '<b><span class="glyphicon glyphicon-alert"></span></b>&ensp;Nota não cadastrada, tente novamente mais tarde.';
	echo mysqli_error($_SG['link']);
}
?>