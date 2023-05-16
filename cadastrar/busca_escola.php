<?php

include '../../../web/seguranca.php';

$query = "SELECT id_escola, nome_escola FROM escola WHERE cidade LIKE '".htmlentities($_POST['cidade'])."'";
$result = mysqli_query($_SG['link'], $query);
    echo '<option selected="true" value="todas">--</option>';
    while ($escola = mysqli_fetch_assoc($result)) {
            echo '<option value="'.$escola['id_escola'].'">'.$escola['nome_escola'].'</option>';
        }
?>

