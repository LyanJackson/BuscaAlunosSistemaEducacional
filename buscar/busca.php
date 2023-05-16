<?php

include '../../../web/seguranca.php';

$ano_ingresso = $_GET['data_ingresso'];/**/

$notas = ($_POST['notass']);
$notasex = ($_POST['notasex']);
$_POST['nome'] = htmlentities($_POST['nome']);

$query = "SELECT DISTINCT * FROM alunos AS a 
JOIN usuario AS u ON u.id_usuario = a.id_usuario 
JOIN escola AS e ON e.id_escola = a.id_escola
JOIN notas AS n ON n.id_usuario = a.id_usuario
JOIN notas_externas AS nx ON nx.id_usuario = a.id_usuario";
/////JOIN escola AS e ON e.id_escola = a.id_escola



if($_SESSION['h'] == 3 OR $_SESSION['h'] == 7){
    $query .= " AND e.cidade LIKE '".($_SESSION['supervisorCidade'])."' ";
}else {
    if (isset($_POST['cidade']) AND $_POST['cidade'] != 'todas' AND $_POST['cidade'] != '')
        $query .= " AND e.cidade LIKE '". htmlentities($_POST['cidade'])."' ";
}
if($_SESSION['h'] == 8){
                            
    $id = $_SESSION['usuarioID'];

    if($_SESSION['h'] == 8)
        $query_aux = mysqli_query($_SG['link'], "SELECT * FROM diretor_escola WHERE id_usuario = '$id' LIMIT 1");                                                            
    $dir_esc = mysqli_fetch_assoc($query_aux);

    echo '<input type="hidden" name="diretorEscola" value="'.$dir_esc["cidade"].'">';
}

if (isset($_POST['nome']) != "")
    $query .= " WHERE u.nome LIKE '%". $_POST['nome']."%' ";

if (isset($_POST['escola']) AND $_POST['escola'] != 'all' AND $_POST['cidade'] != 'todas' AND $_POST['escola'] != '')
    $query .= " AND a.id_escola = '". $_POST['escola']."' ";

if (isset($_POST['status']) AND ($_POST['status'] == 'inativo'))
    $query .=" AND   u.h = 0 ";
elseif(isset($_POST['status']) AND ($_POST['status'] == 'ativo'))
    $query .=" AND   (u.h <> 0) AND n.clube_de_ciencia IS NOT NULL ";
elseif(isset($_POST['status']) AND $_POST['status'] == 'all')
    $query .= " AND (u.h >= 0)";


if(isset($_POST['serie'])){
    $query .= " AND a.serie LIKE '%". $_POST['serie'] . "%' ";
}

if(isset($_POST['ano_ingresso']) AND !empty($_POST['ano_ingresso'])){/**/
    $query .= " AND a.data_ingresso LIKE '%". $_POST['ano_ingresso'] . "%' ";
}

if(isset($_POST['ano'])){
    if(($_POST['ano']) == 2022){
        $query .= " AND n.ano LIKE '%". $_POST['ano'] . "%' ";
    } elseif(($_POST['ano']) == 2023){
        $query .= " AND n.ano LIKE '%". $_POST['ano'] . "%' ";
    } 
}


//////////////////////////////////////NOTAS INTERNAS//////////////////////////////

if(isset($_POST['notass'])){
    if($notas == 'maior'){
        $query .= " AND n.clube_de_ciencia IS NOT NULL ORDER BY n.clube_de_ciencia DESC";
    } elseif($notas == 'menor'){
        $query .= " AND n.clube_de_ciencia IS NOT NULL ORDER BY n.clube_de_ciencia ASC";
    } else {
    }
}

//////////////////////////////////////NOTAS EXTERNAS//////////////////////////////

if(isset($_POST['notasex'])){
 if($notasex == 'maiorex'){
    $query .= " AND nx.media IS NOT NULL ORDER BY nx.media DESC";
 } elseif($notasex == 'menorex'){
    $query .= " AND nx.media IS NOT NULL ORDER BY nx.media ASC";
    } else {
    }
}



if($_POST['qtdBusca'] != "all")
    $query .= " LIMIT ". $_POST['qtdBusca']."";

$sql_todos = "SELECT count(*) AS total FROM alunos JOIN usuario ON usuario.id_usuario = alunos.id_usuario ";

if($_SESSION['h'] == 3 OR $_SESSION['h'] == 7){
    $sql_todos .= " JOIN escola ON escola.id_escola = alunos.id_escola AND escola.cidade = '" . $_SESSION['supervisorCidade']."'";
}elseif ($_SESSION['h'] == 8) {
    $sql_todos .= "JOIN escola ON escola.id_escola = alunos.id_escola AND escola.id_escola = '". $dir_esc['id_escola'] ."'";
}

$result = mysqli_query($_SG['link'], $query);

$todos = mysqli_fetch_assoc(mysqli_query($_SG['link'], $sql_todos))['total'];

$exibindo = mysqli_num_rows($result);

// echo '<script>console.log("'.$query.'")</script>';
echo '<br><p class="text-left">Exibindo <b>'.$exibindo.'</b>, filtrados de <b>'.$todos.'</b> resultados</p><hr>';

//echo '<p>Query: '.$sql_todos.'</p><br><br>';

if ($exibindo > 0){
    while ($aluno = mysqli_fetch_assoc($result)) {
            $id_usuario = $aluno['id_usuario'];

            if($aluno['h'] == 0)
                $ativado = 0;
            elseif ($aluno['h'] == 1 || $aluno['h'] == 4)
                $ativado = 1;

            $cel = ($aluno['cel'] == '') ? 'S/N' : $aluno['cel'];
            $ra = ($aluno['ra'] == '') ? 'RA NÃO INFORMADO' : $aluno['ra'];
            $datn = ($aluno['data_ingresso'] == 0) ? 'DATA INGRESSO NÃO INFORMADA' : $aluno['data_ingresso'];
            


            $nomescola = ($aluno['nome_escola'] == '') ? 'ESCOLA NÃO INFORMADA' : $aluno['nome_escola'];

            echo '<div class="alunoContainer">

                <div align="left" class="alunoNome pull-left col-md-6 col-lg-6">
                    
                
                    <p class="'; if(!$ativado) echo 'text-danger'; else echo 'text-success'; echo '" style="font-size: 1.3em;';echo '">'; if($aluno['h'] == 4){
                        echo '<i class="fa fa-address-card" aria-hidden="true"></i> ';
                    } echo '<b>' .$aluno['nome'].'</b></p>';

                    echo '<p style="font-size: 1.1em;">
                    • Cidade: '.$aluno['cidade'].' <br> 
                    • Escola: '.$nomescola.' <br>
                    • Série: '.$aluno['serie'].' <br> 
                    • RA: '.$ra.'<br>
                    • Data de Ingresso: '.$datn.'<br> 
                    • <b>Nota média PFC:   '.$aluno['clube_de_ciencia'].'</b></p> 
                    
            
                </div>

                <div class="col-md-3 col-lg-4 pull-left">';
                       if($_SESSION['h'] != 8 ):
                        if ($_SESSION['h'] != 7): 
                        if($_SESSION['h'] != 3):
                    echo '<a href="?p=notas&r='.$aluno['id_usuario'].'" data-toggle="tooltip" data-placement="auto" title="Notas internas" class="btn btn-primary"><i class="pull-left fa fa-signal"></i></a>
                    &ensp;
                    ';
                    endif;
                    echo '<a href="?p=boletim&r='.$aluno['id_usuario'].'" data-toggle="tooltip" data-placement="auto" title="Notas externas" class="btn btn-default"><i class="pull-left fa fa-signal"></i></a>
                    &ensp;
                    <a title="Documentos" href="?p=documentos&r='.$aluno['id_usuario'].'" class="btn btn-info" data-toggle="tooltip" data-placement="auto"><i class="fa fa-folder-open"></i></a>';
                    if($_SESSION['h'] == 999):
                        echo '&ensp;<span data-toggle="modal" data-target="#modalPremiacao'.$aluno['id_usuario'].'"><button class="btn btn-warning" data-toggle="tooltip" title="Premiações"><i class="fa fa-trophy"></i></button></span>';
                    endif;
                    endif;
                endif;

               echo '</div>

                <div class="alunoMenu pull-right col-md-3 col-lg-2">
                    <a href="?p=ver&r='.$aluno['id_usuario'].'" class="btn btn-success btn-block"><span class="pull-left glyphicon glyphicon-eye-open"></span> Ver
                    </a> ';
                    if($_SESSION['h'] != 7 AND $_SESSION['h'] != 8):
                    echo '<a href="?p=editar&r='.$aluno['id_usuario'].'" class="btn btn-warning btn-block"><span class="pull-left glyphicon glyphicon-pencil"></span> Editar
                    </a>';
                    if($aluno['h'] != 0): 
                        echo '<button data-toggle="modal" data-target="#modalJustificativa'.$aluno['id_usuario'].'" class="btn btn-danger btn-block"><span class="pull-left glyphicon glyphicon-remove"></span>&ensp;Desativar</button>';
                    else:
                        echo '<button id="'.$aluno['id_usuario'].'" class="desativar btn btn-danger btn-block"><span class="pull-left glyphicon glyphicon-ok"></span>&ensp;Ativar</button>';
                    endif; 
                    if($_SESSION['h'] == 999):
                        echo '<button data-toggle="modal" data-target="#modalExcluir'.$aluno['id_usuario'].'" class="btn btn-danger btn-block"><span class="pull-left glyphicon glyphicon-trash"></span>&ensp;Excluir</button>';            
                    endif;                            
                    echo '<form class="formsHidden'.$aluno['id_usuario'].'">
                        <input type="hidden" value="'.$aluno['id_usuario'].'" name="id" />
                        <input id="input'.$aluno['id_usuario'].'" type="hidden" value="'.$aluno['h'].'" name="ativo" />';

                        ?>
                        <div class="modal fade" id="modalJustificativa<?php echo $aluno['id_usuario'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">Justificativa de afastamento</h4>
                              </div>
                              <div class="modal-body">
                                <p class="text-left">Aluno: <b><?php echo $aluno['nome'] ?></b></p>
                                <textarea name="justificativa" id="inputJustificativa<?php echo $aluno['id_usuario'] ?>" cols="30" rows="10" class="form-control" placeholder="Escreva o motivo do afastamento do aluno"></textarea>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                                <button id="<?php echo $aluno['id_usuario'] ?>" type="button" class="desativar btn btn-primary">Desativar</button>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="modal fade" id="modalExcluir<?php echo $aluno['id_usuario'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">Confirmação de Exclusão</h4>
                              </div>
                              <div class="modal-body">
                                <p class="text-left">Tem certeza que deseja <u>excluir</u> o aluno <b><?php echo $aluno['nome'] ?></b> permanentemente do sistema?</p>
                                <p class="help-block">
                                    *Após deletar o aluno todos seus dados serão perdidos e será impossível de recuperá-los.
                                </p>
                               
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                                <button id="<?php echo $aluno['id_usuario'] ?>" type="button" class="excluir btn btn-danger">Excluir</button>
                              </div>
                            </div>
                          </div>
                        </div>                        

                        <div class="modal fade" id="modalPremiacao<?php echo $aluno['id_usuario'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 onclick="$('#ver_premiacao<?php echo $id_usuario ?>').slideToggle();$('#cadastrar_premiacao<?php echo $id_usuario ?>').slideToggle(); $('i', this).toggleClass('fa-plus-square fa-minus-square'); $('#<?php echo $id_usuario ?>.adicionarPremiacao').toggleClass('hidden');" class="modal-title" id="myModalLabel"><button class="btn btn-default" type="button"><i class="fa fa-plus-square"></i>&ensp;Adicionar premiação</button></h4>
                              </div>
                              <div class="modal-body" align="left">
                                <div id="ver_premiacao<?php echo $aluno['id_usuario'] ?>" align="center">
                                    <?php 
                                        
                                        $sql = "SELECT * FROM premiacoes JOIN usuario ON usuario.id_usuario = premiacoes.id_usuario WHERE premiacoes.id_usuario = $id_usuario";
                                        $res = mysqli_query($_SG['link'], $sql);
                                        if(mysqli_num_rows($res) > 0):
                                        while($row = mysqli_fetch_assoc($res)):
                                    ?>
                                        
                                        <p>
                                            <b><i style="color: gold;" class="fa fa-trophy"></i></b> <?php echo $row['titulo']; ?> | <?php echo $row['ano'] ?> &ensp;&ensp;<span style="cursor: pointer;" data-id="<?php echo $row['id'] ?>" class="excluir_premiacao" data-toggle="tooltip" data-title="excluir"><i style="color: red;" class="fa fa-remove"></i></span> <br><br>
                                        </p>
                                        

                                    <?php endwhile; else: echo '<p align="center">Nenhuma premiação encontrada.</p>'; endif; ?>
                                </div>                                
                                <div id="cadastrar_premiacao<?php echo $aluno['id_usuario'] ?>" class="text-left" hidden>

                                        <input type="hidden" name="id_usuario" value="<?php echo $aluno['id_usuario'] ?>">

                                        <div class="form-group">
                                            <label for="nome">Aluno</label>
                                            <input name="aluno" type="text" class="form-control" id="nome" value="<?php echo $aluno['nome'] ?>" disabled>
                                        </div>

                                        <div class="form-group">
                                            <label for="ano">Ano</label>
                                            <input type="text" id="ano" class="form-control" name="ano" placeholder="Digite o ano da premiação">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="titulo">Título</label>
                                            <input name="titulo" type="text" class="form-control" id="titulo" placeholder="Digite o título da premiação">
                                        </div>

                                        <div class="form-group">
                                            <label for="categoria_premio">Categoria do prêmio</label>
                                            <select name="categoria_premio" id="categoria_premio" class="form-control">
                                                <option value="" hidden>Selecione a categoria do prêmio</option>
                                                <option value="melhor_aluno_escola">Melhor aluno da escola</option>
                                                <option value="melhor_aluno_ano">Melhor aluno do ano em geral</option>
                                                <option value="melhor_aluno_literario">Melhor aluno do Concurso Literário</option>
                                                <option value="melhor_curta_metragem">Melhor curta metragem</option>
                                                <option value="melhores_clubes">Melhor clubes de ciências</option>
                                                <option value="melhor_do_pfc">Melhor do PFC</option>
                                            </select>
                                        </div>
                                        
                                </div>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                                <button id="<?php echo $aluno['id_usuario'] ?>" type="button" class="hidden adicionarPremiacao btn btn-primary">Salvar</button>
                              </div>
                            </div>
                          </div>
                        </div>

                        <?php
                endif;
                    echo '</form>';

                echo '</div>

            </div>';

        }
} else {

        echo "<br><br><div class='alert alert-danger'> <span class='glyphicon glyphicon-exclamation-sign'></span> Nenhum resultado encontrado</div>";
}
?>

<script type="text/javascript">

    $('.desativar').click(function () {
        var $id = $(this).attr('id');
        var $ativo = $('#input'+$id).val();
        var $justificativa = $('#inputJustificativa'+$id).val();

        var data = $('.formsHidden'+ $id).serialize();

        $.ajax({
            url: '<?php echo $root_html?>sistema/alunos/buscar/desativar.php',
            type: 'POST',
            data: data,
            beforeSend: function (e) {
                if($ativo == 1)
                    $("#"+$id).html("Desativando...");
                else
                    $("#"+$id).html("Ativando...");
            },
            success: function (data) {
                setTimeout(function() {
                    $('#'+$id).html(data); 
                    // window.location.reload(false);
                }, 500);
            },
            complete: function (e) {
                if($ativo == 1)
                    $("#input"+$id).val(0);
                else
                    $("#input"+$id).val(1);         
            },
            error: function (e) {
                $('#resultado1').html("<option>Nenhum resultado encontrado.</option>");
            },
        });

    });

    $('.excluir').click(function(){
        var $id = $(this).attr('id');

        var data = {
            id: $id
        }

        $.ajax({
            url: '<?php echo $root_html?>sistema/alunos/buscar/excluir_aluno.php',
            type: 'POST',
            data: data,
            success: function (data) {
                $('.alerta').hide().show().addClass('alert-success');
                $('#alerta_conteudo').html(data);

                setTimeout(function(){ 
                    $('.alerta').fadeOut(500).removeClass('alert-success', 500);
                    window.location.reload(false);
                }, 2000);

                $('.modal').hide();

            },
            complete: function (e) {

            },
            error: function (e) {
                $('#resultado').html("ERRO. 404");
            }
        });

    });

    $('.adicionarPremiacao').click(function(){
        var $id = $(this).attr('id');
        var form = $('.formsHidden'+$id)
        var data = form.serialize();

        $.ajax({
            url: '<?php echo $root_html?>sistema/alunos/buscar/adicionarPremiacao.php',
            type: 'POST',
            data: data,
            success: function (data) {
                $('.alerta').hide().show();
                $('#alerta_conteudo').html(data);

                setTimeout(function(){
                    location.reload();
                }, 400);
            },
            complete: function (e) {
                $('.modal').modal('hide');
                form.reset();
            },
            error: function (e) {
                $('#resultado').html("ERRO. 404");
            }
        });

    });

    $('.excluir_premiacao').click(function(event) {
        
        if(confirm("Tem certeza que deseja excluir essa premiação?")){

            var id = $(this).attr('data-id');

            var data = {id: id};


            $.ajax({
                url: '<?php echo $root_html?>sistema/alunos/buscar/removerPremiacao.php',
                type: 'POST',
                data: data,
                success: function (data) {
                    $('.alerta').hide().show();
                    $('#alerta_conteudo').html(data);
                    setTimeout(function(){
                        location.reload();
                    }, 400);
                },
                complete: function (e) {
                    $('.modal').modal('hide');
                    form.reset();
                },
                error: function (e) {
                    $('#alerta_conteudo').html("ERRO. 404");
                }            
            });        
        }
        else
            event.preventDefault();

    });

</script>