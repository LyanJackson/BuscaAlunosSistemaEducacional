<?php 
include "../../../web/seguranca.php";

$id = $_POST['id'];

$sql = "DELETE FROM premiacoes WHERE id = '$id'";

if(mysqli_query($_SG['link'], $sql)){
	echo '<script>$(".alerta").addClass("alert-success")</script>';
	echo '<b><i class="fa fa-check"></i></b> Premiação deletada com sucesso!';	
}
else {
	echo '<script>$(".alerta").addClass("alert-danger");</script>';
	echo '<b><i class="fa fa-close"></i></b> Houve um erro no sistema, aguarde uns instantes e tente novamente.';
}


echo "<script>setTimeout(function(){ 
        $('.alerta').fadeOut(500).removeClass('alert-success alert-danger', 500);
    	}, 2000);
    </script>";
 ?>