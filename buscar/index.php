<?php include '../../../web/seguranca.php';
$title = "AdminPFC - Alunos";
if ($_SESSION['h'] == 1 and $_GET['r'] != $_SESSION['usuarioID']) {
    echo '<script>alert("Ops... Você não pode editar o perfil do amiguinho...")</script>';
    expulsaVisitante();
} elseif ($_SESSION['h'] != 1) {
    // protegePaginaUnica(3, 901);
    protectPage("3;901;902;7;8;999;");
}

if (isset($_GET['p']) && isset($_GET['r'])) {
    $p = $_GET['p'];
    $r = $_GET['r'];

    //$query = "SELECT * FROM usuario AS u, escola AS e WHERE u.id_escola = e.id_escola AND (u.h = 0 OR u.h = 1 OR u.h = 4) AND u.id_usuario=" . $r . "";
    $query = "SELECT * FROM alunos AS a JOIN escola AS e ON e.id_escola = a.id_escola JOIN usuario AS u ON u.id_usuario = a.id_usuario WHERE (u.h = 0 OR u.h = 1 OR u.h = 4) AND a.id_usuario = " . $r . " ";

    $result = mysqli_query($_SG['link'], $query);
    $aluno = mysqli_fetch_assoc($result);
    $id_escola = $aluno['id_escola'];

    $aluno_escola = mysqli_fetch_assoc(mysqli_query($_SG['link'], "SELECT * FROM escola WHERE id_escola = " . $id_escola . ""));

    $escola1 = mysqli_query($_SG['link'], "SELECT DISTINCT cidade FROM escola");
    $query_qs = "SELECT * FROM questionariose ORDER BY pergunta_num";
    $result_qs = mysqli_query($_SG['link'], $query_qs);
}
include '../../head.php';

//Query's
$escola = mysqli_query($_SG['link'], "SELECT DISTINCT cidade FROM escola");

?>

<body id="corpo" class="hold-transition skin-black sidebar-mini fixed">

    <div class="wrapper">

        <?php include '../../menu.php'; ?>
        <div class="content-wrapper">
            <section class="content-header">
                <?php if (!isset($p)) : ?>
                    <a href="<?php echo $root_html ?>sistema/" class="btn btn-default"><i class="fa fa-arrow-left"></i>&ensp;Voltar</a>
                <?php elseif (isset($p)) : ?>
                    <a href="<?php echo $root_html ?>sistema/alunos/buscar/" class="btn btn-default"><i class="fa fa-arrow-left"></i>&ensp;Voltar</a>

                <?php endif; ?>
                <ol class="breadcrumb">
                    <li><a href="<?php echo $root_html ?>sistema/"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li>Alunos</li>
                    <?php if (!isset($p)) : ?>
                        <li class="active">Buscar</li>
                    <?php elseif (isset($p) && $p == 'editar') : ?>
                        <li><a href="<?php echo $root_html ?>sistema/alunos/buscar/">Buscar</a></li>
                        <li class="active">Editar</li>
                    <?php elseif (isset($p) && $p == 'ver') : ?>
                        <li><a href="<?php echo $root_html ?>sistema/alunos/buscar/">Buscar</a></li>
                        <li class="active">Ver</li>
                    <?php elseif (isset($p) && $p == 'notas') : ?>
                        <li><a href="<?php echo $root_html ?>sistema/alunos/buscar/">Buscar</a></li>
                        <li class="active">Notas Internas</li>
                    <?php elseif (isset($p) && $p == 'boletim') : ?>
                        <li><a href="<?php echo $root_html ?>sistema/alunos/buscar/">Buscar</a></li>
                        <li class="active">Notas Externas</li>
                    <?php elseif (isset($p) && $p == 'documentos') : ?>
                        <li><a href="<?php echo $root_html ?>sistema/alunos/buscar/">Buscar</a></li>
                        <li class="active">Documentos</li>
                    <?php endif; ?>
                </ol>
            </section>
            <br><br>
            <div class="container-fluid">

                <?php if (!isset($_GET['p']) && !isset($_GET['r'])) :
                ?>

                    <form id="buscaAluno" method="POST" action="" class="forms-buscar">


                        <?php if ($_SESSION['h'] != 7 or $_SESSION['h'] != 8) : ?>

                        <?php endif; ?>

                        <h2>
                            <i class="fa fa-search"></i> Busca
                        </h2>
                        <div class="form-group col-md-12">
                            <input type="text" id="buscaNome" name="nome" class="input-lg form-control" placeholder="Pesquise pelo nome ou sobrenome do aluno">
                        </div>
                        <br><br><br>
                        <div style="padding-left: 15px;">
                            <div class="row">
                                <div class="col-md-12">
                                    <p class="help-block text-left">
                                        *Os resultados da busca aparecem automaticamente abaixo.
                                    </p>

                                </div>
                            </div>
                            <p onclick="$('.filtro').slideToggle();" class="icon-filter"><i class="fa fa-filter"></i> <span data-toggle="tooltip" data-placement="top" title="Clique aqui para ver os filtros de busca">Filtros</span></p>
                        </div>
                        <div class="filtro">
                            <br>

                            <div class="row">

                                <!-- =======================================================
                        ============================================================
                        ======================== VISAO SUPERVISOR ==================
                        ============================================================
                        ======================================================== -->

                                <?php if ($_SESSION['h'] == 3 or $_SESSION['h'] == 7) :

                                    $id = $_SESSION['usuarioID'];
                                    if ($_SESSION['h'] == 3)
                                        $query = "SELECT * FROM supervisores WHERE id_usuario = '$id' LIMIT 1";
                                    else
                                        $query = "SELECT * FROM secretario_educacao WHERE id_usuario = '$id' LIMIT 1";
                                    $res = mysqli_query($_SG['link'], $query);
                                    $supervisor = mysqli_fetch_assoc($res);

                                    $query_escola = "SELECT id_escola, nome_escola FROM escola WHERE cidade LIKE '" . $supervisor['cidade'] . "'";
                                    $result = mysqli_query($_SG['link'], $query_escola);


                                ?>

                                    <?php
                                    if ($_SESSION['h'] == 3 or $_SESSION['h'] == 7) {

                                        $id = $_SESSION['usuarioID'];

                                        if ($_SESSION['h'] == 3)
                                            $query_aux = mysqli_query($_SG['link'], "SELECT * FROM supervisores WHERE id_usuario = '$id' LIMIT 1");
                                        else
                                            $query_aux = mysqli_query($_SG['link'], "SELECT * FROM secretario_educacao WHERE id_usuario = '$id' LIMIT 1");

                                        $supervisor = mysqli_fetch_assoc($query_aux);

                                        echo '<input type="hidden" name="supervisorCidade" value="' . $supervisor["cidade"] . '">';
                                    }
                                    ?>

                                    <div class="form-group col-md-6">
                                        <label for="buscaCidade">Cidade</label>
                                        <select name="cidade" id="buscaCidade" class="form-control">
                                            <option value="<?php echo $supervisor['cidade']; ?>"><?php echo $supervisor['cidade']; ?></option>
                                        </select>

                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="escola">Escola</label>
                                        <select name="escola" id="buscaEscola" class="form-control text-uppercase">
                                            <option value="" selected>Selecione uma cidade antes</option>
                                            <option value="all">Todas</option>
                                            <?php
                                            while ($escola1 = mysqli_fetch_assoc($result)) {
                                                echo '<option value="' . $escola1['id_escola'] . '">' . $escola1['nome_escola'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <!-------------------------------------------------------------------------------------------------------------->

                                    <!-- =======================================================
                        ============================================================
                        ======================== VISAO DIRETOR/ESCOLA ==================
                        ============================================================
                        ======================================================== -->
                                <?php elseif ($_SESSION['h'] == 8) :

                                    $id = $_SESSION['usuarioID'];
                                    if ($_SESSION['h'] == 8)
                                        $query = "SELECT * FROM diretor_escola WHERE id_usuario = '$id' LIMIT 1";

                                    $res = mysqli_query($_SG['link'], $query);
                                    $dir_esc = mysqli_fetch_assoc($res);


                                    //$query_escola_dir = "SELECT id_escola, nome_escola FROM escola WHERE cidade LIKE '".$dir_esc['cidade']."'";
                                    /*$nome_escola_dir =  "SELECT id_escola, nome_escola FROM diretor_escola WHERE cidade LIKE '".$dir_esc['cidade']."'";*/


                                    //echo '<p>Query: '.$nome_escola_dir.'</p><br><br>';
                                    //$result = mysql_query($query_escola_dir);

                                ?>

                                    <?php
                                    if ($_SESSION['h'] == 8) {

                                        $id = $_SESSION['usuarioID'];

                                        if ($_SESSION['h'] == 8)
                                            $query_aux = mysqli_query($_SG['link'], "SELECT * FROM diretor_escola WHERE id_usuario = '$id' LIMIT 1");
                                        $dir_esc = mysqli_fetch_assoc($query_aux);

                                        echo '<input type="hidden" name="diretorEscola" value="' . $dir_esc["cidade"] . '">';
                                    }
                                    ?>

                                    <div class="form-group col-md-6">
                                        <label for="buscaCidade">Cidade</label>
                                        <select name="cidade" id="buscaCidade" class="form-control">
                                            <option value="<?php echo $dir_esc['cidade']; ?>" selected><?php echo $dir_esc['cidade']; ?></option>
                                        </select>

                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="escola">Escola</label>
                                        <select name="escola" id="buscaEscola" class="form-control text-uppercase">
                                            <option value="<?php echo $dir_esc['id_escola']; ?>" selected><?php echo $dir_esc['nome_escola']; ?></option>
                                        </select>
                                    </div>




                                    <!-------------------------------------------------------------------------------------------------------------->



                                    <!-- =======================================================
                        ============================================================
                        ========================== VISAO ADMIN =====================
                        ============================================================
                        ======================================================== -->

                                <?php else :

                                    $escola = mysqli_query($_SG['link'], "SELECT DISTINCT cidade FROM escola ORDER BY cidade");

                                ?>

                                    <div class="form-group col-md-6">
                                        <label for="cidade">Cidade</label>
                                        <select class="form-control" name="cidade" id="buscaCidade">
                                            <option value="" hidden>Escolha uma cidade</option>
                                            <option value="todas">Todas</option>
                                            <?php
                                            while ($e = mysqli_fetch_array($escola)) {
                                                echo '<option value="' . $e['cidade'] . '">' . $e['cidade'] . '</option>';
                                            }
                                            ?>
                                        </select>

                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="escola">Escola</label>
                                        <select name="escola" id="buscaEscola" class="form-control text-uppercase">
                                            <option value="" selected>Selecione uma cidade antes</option>
                                        </select>
                                    </div>


                                <?php endif; ?>


                                <div class="form-group col-md-6">
                                    <label for="buscaSerie">Série</label>
                                    <select name="serie" id="buscaSerie" class="form-control">
                                        <option value="" selected hidden>Selecione uma série</option>
                                        <option value="EF">Todos Ensino Fundamental</option>
                                        <option value="EM">Todos Ensino Médio</option>
                                        <?php
                                        $query = mysqli_query($_SG['link'], "SELECT DISTINCT serie FROM alunos WHERE serie <> 'XX' AND serie <> 0 ORDER BY serie DESC");
                                        while ($row = mysqli_fetch_assoc($query)) :

                                            switch ($row['serie']) {
                                                case '5EF':
                                                    $serie = "5º Ensino Fundamental";
                                                    break;
                                                case '6EF':
                                                    $serie = "6º Ensino Fundamental";
                                                    break;
                                                case '7EF':
                                                    $serie = "7º Ensino Fundamental";
                                                    break;
                                                case '8EF':
                                                    $serie = "8º Ensino Fundamental";
                                                    break;
                                                case '9EF':
                                                    $serie = "9º Ensino Fundamental";
                                                    break;
                                                case '1EM':
                                                    $serie = "1º Ensino Médio";
                                                    break;
                                                case '2EM':
                                                    $serie = "2º Ensino Médio";
                                                    break;
                                                case '3EM':
                                                    $serie = "3º Ensino Médio";
                                                    break;

                                                default:
                                                    $serie = "ERRO";
                                                    break;
                                            }

                                        ?>

                                            <option value="<?php echo $row['serie'] ?>"><?php echo $serie ?></option>

                                        <?php endwhile; ?>

                                    </select>
                                </div>
                                </br>
                                <div class="form-group col-md-6">
                                    <label for="ano_ingresso">Ano de Ingresso</label>
                                    <select name="ano_ingresso" id="ano_ingresso" class="form-control">
                                        <option value="" selected>Selecione o ano de ingresso</option>
                                        <?php
                                        $sql = mysqli_query($_SG['link'], "SELECT DISTINCT data_ingresso FROM alunos ORDER BY data_ingresso");

                                        while ($row = mysqli_fetch_array($sql)) {
                                            echo '<option value="' . $row['data_ingresso'] . '">' . $row['data_ingresso'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="row">

                          <!---------------------------------------- NOTAS INTERNAS ---------------------------------------------------->   
                       <div class="col-md-6">
                        <div class="form-group">
                    <label style="text-align:center" for="notas">Notas no PFC</label>
                    <select name="notass" id="notass" class="form-control input-lg">
                            <option value="" hidden>Selecione uma opção</option>
                            <option id="sem" value="">Sem filtro</option>
                            <option id="maior" value="maior">Melhores notas do programa</option>
                            <option id="menor" value="menor">Menores notas do programa</option>
                            </select>
                    </div>
                   
                    </div>
                  


                          <!---------------------------------------- NOTAS EXTERNAS ---------------------------------------------------->   
                          <div class="col-md-6">
                        <div class="form-group">
                    <label style="text-align:center" for="notasex">Notas Externas</label>
                    <select name="notasex" id="notasex" class="form-control input-lg">
                            <option value="" hidden>Selecione uma opção</option>
                            <option id="sem" value="">Sem filtro</option>
                            <option id="maiorex" value="maiorex">Aluno com melhor desempenho acadêmico</option>
                            <option id="menorex" value="menorex">Aluno com melhor desempenho acadêmico</option>
                            </select>
                    </div>
                    </div>

                   
                        <!------------------------------------------ANO PFC-------------------------------------------------->

                        <div class="form-group col-md-6">
                            <select name="ano" id="ano" class="form-control input-lg">
                            <option value="" hidden>Selecione um ano do PFC</option>
                            <option value="sem">Sem filtro anual</option>
                            <option value="2022">2022</option>
                            <option value="2023">2023</option>
                            </select>
                            
                        </div>

                        <!-------------------------------------------------------------------------------------------->

                           <!------------------------------------------SEMESTRES-------------------------------------------------->

                           <div class="form-group col-md-6">
                            <select name="anosemest" id="anosemest" class="form-control input-lg">
                            <option value="">Selecione um semestre escolar</option>
                            <option value="2022s">2022/2</option>
                            <option value="2023s">2023/1</option>
                            </select>
                            
                        </div>

                        <!-------------------------------------------------------------------------------------------->


                        <div align="center" class="col-md-12">
                                    <label for="ativo" class="radio-inline">
                                        <input class="buscaStatus" type="radio" name="status" id="ativo" value="ativo" /> Ativos
                                    </label>
                                    <label for="inativo" class="radio-inline">
                                        <input class="buscaStatus" type="radio" name="status" id="inativo" value="inativo" />Inativos
                                    </label>
                                    <br>
                                    <label for="all" class="radio-inline">
                                        <input selected class="buscaStatus" type="radio" name="status" id="all" value="all" />Todos
                                    </label>
                                </div><br>
                        </div>

                    

                        
                            </div>
                            
                        </div>

                        
                        <br>
                        <div class="row">
                            <div class="container-fluid">
                                <div class="form-group col-md-4 col-md-offset-4" align="center">
                                    <select class="form-control" name="qtdBusca" id="qtdBusca">
                                        <option value="50">50 resultados</option>
                                        <option value="100">100 resultados</option>
                                        <option value="200">200 resultados</option>
                                        <option value="500">500 resultados</option>
                                        <option value="all">Todos resultados</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </form>
                    <div align="center" class="row" style="margin: 0 auto; width: 100%;">
                        <div id="resultado" class="container">

                            <?php
                            // $query = "SELECT * FROM alunos AS a, usuario AS u, escola AS e WHERE u.id_usuario = a.id_usuario AND a.id_escola = e.id_escola ";
                            $query = "SELECT * FROM alunos AS a JOIN usuario AS u ON u.id_usuario = a.id_usuario JOIN escola AS e ON e.id_escola = a.id_escola";
                            if ($_SESSION['h'] == 3 or $_SESSION['h'] == 7) {
                                $query .= " AND e.cidade LIKE '" . $supervisor['cidade'] . "' ";
                            } elseif ($_SESSION['h'] == 8) {
                                $query .= " AND e.id_escola LIKE '" . $dir_esc['id_escola'] . "'";
                            }
                            $query .= " ORDER BY e.cidade, e.nome_escola, a.serie, a.data_ingresso, u.nome LIMIT 50";

                            ?>
                            <?php
                            $resultado = mysqli_query($_SG['link'], $query);

                            $sql_todos = "SELECT count(*) AS total FROM alunos JOIN usuario ON usuario.id_usuario = alunos.id_usuario ";

                            if ($_SESSION['h'] == 3 or $_SESSION['h'] == 7) {
                                $sql_todos .= "JOIN escola ON escola.id_escola = alunos.id_escola AND escola.cidade = '" . $_SESSION['supervisorCidade'] . "'";
                            } elseif ($_SESSION['h'] == 8) {
                                $sql_todos .= "JOIN escola ON escola.id_escola = alunos.id_escola AND escola.id_escola = '" . $dir_esc['id_escola'] . "'";
                            }

                            $todos = mysqli_fetch_assoc(mysqli_query($_SG['link'], $sql_todos))['total'];

                            // echo '<p>'.$query.'</p>'; 

                            echo '<br><p class="text-left">Exibindo <b>' . mysqli_num_rows($resultado) . '</b>, filtrados de <b>' . $todos . '</b> resultados</p><hr>';
                            if (mysqli_num_rows($resultado) != 0) {
                                while ($aluno = mysqli_fetch_assoc($resultado)) {
                                    $id_usuario = $aluno['id_usuario'];
                                    if ($aluno['h'] == 0)
                                        $ativado = 0;
                                    else
                                        $ativado = 1;

                                    $cel = ($aluno['cel'] == '') ? 'S/N' : $aluno['cel'];

                                    echo '<div class="alunoContainer">

                <div align="left" class="alunoNome pull-left col-md-6 col-lg-6">
                    
                    <p class="';
    
                                    if (!$ativado) echo 'text-danger';
                                    else echo 'text-success';
                                    echo '" style="font-size: 1.3em;';
                                    echo '">';
                                    if ($aluno['h'] == 4) {
                                        echo '<i class="fa fa-address-card" aria-hidden="true"></i> ';
                                    }
                                    echo '<b>' . $aluno['nome'] . '</b></p>';

                                    echo '<p style="font-size: 1.1em;">
                                   
                                    • Cidade: ' . $aluno['cidade'] . ' <br> 
                                    • Escola: ' . $aluno['nome_escola'] . ' <br>
                                    • Série: ' . $aluno['a.serie'] . '<br> 
                                    • RA: ' .$aluno['ra']. '<br>
                                    • Data de Ingresso: ' .$aluno['data_ingresso']. '</p>
    
                                    
                </div>

                <div class="col-md-3 col-lg-4 pull-left">
                    ';

                                    if ($_SESSION['h'] != 8) :
                                        if ($_SESSION['h'] != 7) :
                                            if ($_SESSION['h'] != 3) :
                                                echo '<a href="?p=notas&r=' . $aluno['id_usuario'] . '" data-toggle="tooltip" data-placement="auto" title="Notas internas" class="btn btn-primary"><i class="pull-left fa fa-signal"></i></a>
                    &ensp;
                    ';
                                            endif;
                                            echo '<a href="?p=boletim&r=' . $aluno['id_usuario'] . '" data-toggle="tooltip" data-placement="auto" title="Notas externas" class="btn btn-default"><i class="pull-left fa fa-signal"></i></a>
                    &ensp;
                    <a title="Documentos" href="?p=documentos&r=' . $aluno['id_usuario'] . '" class="btn btn-info" data-toggle="tooltip" data-placement="auto"><i class="fa fa-folder-open"></i></a>';
                                            if ($_SESSION['h'] == 999) :
                                                echo '&ensp;<span data-toggle="modal" data-target="#modalPremiacao' . $aluno['id_usuario'] . '"><button class="btn btn-warning" data-toggle="tooltip" title="Premiações"><i class="fa fa-trophy"></i></button></span>';
                                            endif;
                                        endif;
                                    endif;

                                    echo '</div>

                <div class="alunoMenu pull-right col-md-3 col-lg-2">
                    <a href="?p=ver&r=' . $aluno['id_usuario'] . '" class="btn btn-success btn-block"><span class="pull-left glyphicon glyphicon-eye-open"></span> Ver
                    </a> ';

                                    if ($_SESSION['h'] != 7 and $_SESSION['h'] != 8) :
                                        echo '<a href="?p=editar&r=' . $aluno['id_usuario'] . '" class="btn btn-warning btn-block"><span class="pull-left glyphicon glyphicon-pencil"></span> Editar
                    </a>';


                                        if ($aluno['h'] != 0) :
                                            echo '<button data-toggle="modal" data-target="#modalJustificativa' . $aluno['id_usuario'] . '" class="btn btn-danger btn-block"><span class="pull-left glyphicon glyphicon-remove"></span>&ensp;Desativar</button>';
                                        else :
                                            echo '<button id="' . $aluno['id_usuario'] . '" class="desativar btn btn-danger btn-block"><span class="pull-left glyphicon glyphicon-ok"></span>&ensp;Ativar</button>';
                                        endif;
                                        if ($_SESSION['h'] == 999) :
                                            echo '<button data-toggle="modal" data-target="#modalExcluir' . $aluno['id_usuario'] . '" class="btn btn-danger btn-block"><span class="pull-left glyphicon glyphicon-trash"></span>&ensp;Excluir</button>';
                                        endif;
                                        echo '<form class="formsHidden' . $aluno['id_usuario'] . '">
                        <input type="hidden" value="' . $aluno['id_usuario'] . '" name="id" />
                        <input id="input' . $aluno['id_usuario'] . '" type="hidden" value="' . $aluno['h'] . '" name="ativo" />';

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
                                                        <div class="form-group">
                                                            <textarea name="justificativa" id="inputJustificativa<?php echo $aluno['id_usuario'] ?>" cols="30" rows="10" class="form-control inputJustificativa" placeholder="Escreva o motivo do afastamento do aluno"></textarea>
                                                        </div>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                                                        <button id="<?php echo $aluno['id_usuario'] ?>" type="button" class="desativar btn btn-danger">Desativar</button>
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

                                                            $sql = "SELECT premiacoes.titulo, premiacoes.id, premiacoes.ano FROM premiacoes JOIN usuario ON usuario.id_usuario = premiacoes.id_usuario WHERE premiacoes.id_usuario = $id_usuario";
                                                            $res = mysqli_query($_SG['link'], $sql);
                                                            if (mysqli_num_rows($res) > 0) :
                                                                while ($row = mysqli_fetch_assoc($res)) :
                                                            ?>

                                                                    <p>
                                                                        <b><i style="color: gold;" class="fa fa-trophy"></i></b> <?php echo $row['titulo']; ?> | <?php echo $row['ano'] ?> &ensp;&ensp;<span style="cursor: pointer;" data-id="<?php echo $row['id'] ?>" class="excluir_premiacao" data-toggle="tooltip" data-title="excluir"><i style="color: red;" class="fa fa-remove"></i></span> <br><br>
                                                                    </p>


                                                            <?php endwhile;
                                                            else : echo '<p align="center">Nenhuma premiação encontrada.</p>';
                                                            endif; ?>
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

                                                            <form name=myform class="noformat">
                                                                <label for="categoria_premio">Participação:</label>
                                                                </br>
                                                                <input type="checkbox" autocomplete="off" name="valor" value="10" onClick="this.form.total.value=checkChoice(this);">Obteve 75% de frequência nos encontros.
                                                                </br>
                                                                <input type="checkbox" autocomplete="off" name="valor" value="10" onClick="this.form.total.value=checkChoice(this);">Entregou todas as atividades do Clube de Ciências.
                                                                </br>
                                                                <input type="checkbox" autocomplete="off" name="valor" value="10" onClick="this.form.total.value=checkChoice(this);">Concurso Literário.
                                                                </br>
                                                                <input type="checkbox" autocomplete="off" name="valor" value="5" onClick="this.form.total.value=checkChoice(this);">Maratona do conhecimento.
                                                                </br></br>
                                                                <!-------------------------------------------------------------------------------------------------------------------------->
                                                                <label for="categoria_premio">Categoria do Prêmio:</label>
                                                                </br>
                                                                <input type="checkbox" autocomplete="off" name="valor" value="5" onClick="this.form.total.value=checkChoice(this);">Concurso Literário.
                                                                </br>
                                                                <input type="checkbox" autocomplete="off" name="valor" value="5" onClick="this.form.total.value=checkChoice(this);">Clube de Ciências.
                                                                </br>
                                                                <input type="checkbox" autocomplete="off" name="valor" value="5" onClick="this.form.total.value=checkChoice(this);">Maratona do conhecimento.
                                                                </br>
                                                                <input type="checkbox" autocomplete="off" name="valor" value="5" onClick="this.form.total.value=checkChoice(this);">Aluno Destaque.
                                                                </br></br>
                                                                <div class="input-group">
                                                                    <span class="input-group-addon">Pontuação</span>
                                                                    <input class="form-control input-lg" id="disabledInput" name="total" type="text" placeholder="" readonly disabled>
                                                                    <input type=hidden name=hiddentotal value=0>
                                                                    <span class="input-group-addon"></span>
                                                                </div>
                                                            </form>


                                                            <form class="form-group" method="post">
                                                                <label for="data">Ano de Ingresso</label>
                                                                <input name="data" type="text" class="form-control" id="data" onblur="calcular()" placeholder="">

                                                                <!--<input id="qtd_ano" type="text"/>-->
                                                            </form>




                                                            <script type="text/javascript">
                                                                // Obtém o Ano
                                                                var data = new Date();

                                                                var ano = data.getFullYear();

                                                                function calcular() {
                                                                    var data = parseInt(document.getElementById('data').value, 10);
                                                                    //var ano = parseInt(document.getElementById('ano_atual').value, 10);
                                                                    document.getElementById('qtd_ano').value = ano - data;

                                                                }
                                                            </script>



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



                            <!-- 
                ============================================
                ============================================
                ================== EDITAR ==================
                ============================================
                ============================================
                -->

                        <?php elseif ($p == 'editar' and $_SESSION['h'] != 1) :
                        protectPage("3;901;902;999;");
                        // var_dump($aluno);
                        ?>
                            <div style="width: 80%; margin-left: 10%;">
                                <form id="editarCadastro" method="POST" action="editar.php" class="alunoCadastro formsEquipe forms-cadastrar">
                                    <h1 class="text-center">Editar Cadastro <br><small>Dados pessoais</small></h1>
                                    <hr><br>

                                    <input type="hidden" name="id_usuario" value="<?php echo $aluno['id_usuario']; ?>">

                                    <div class="form-group">
                                        <label for="nome">Nome</label>
                                        <input type="text" id="nome" name="nome_cadastrar" class="form-control" value="<?php echo $aluno['nome'] ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="h">Hierarquia <span class="glyphicon glyphicon-question-sign" data-toggle="tooltip" data-placement="top" title="Escolha entre Aluno ou Embaixador Jr."></span></label>
                                        <select name="h" id="h" class="form-control">
                                            <option value="" hidden></option>
                                            <option value="0" <?php if ($aluno['h'] == 0) echo 'selected'; ?>>0 - Desativado</option>
                                            <option value="1" <?php if ($aluno['h'] == 1) echo 'selected'; ?>>1 - Aluno</option>
                                            <option value="4" <?php if ($aluno['h'] == 4) echo 'selected'; ?>>2 - Embaixador Jr.</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="email">E-mail</label>
                                        <input type="email" id="email" name="email" class="form-control" value="<?php echo $aluno['email'] ?>">
                                    </div>

                                    <div class="form-group" hidden>
                                        <label for="senha">Senha</label>
                                        <input autocomplete="new-password" type="password" id="senha" name="senha" class="form-control" value="<?php echo $aluno['senha'] ?>">
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="rg">RG</label>
                                            <input type="text" id="rg" name="rg" class="form-control" value="<?php echo $aluno['rg'] ?>">
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="ra">RA</label>
                                            <input type="text" id="ra" name="ra" class="form-control" value="<?php echo $aluno['ra'] ?>">
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="cpf">CPF</label>
                                            <input type="text" id="cpf" name="cpf" class="form-control" value="<?php echo $aluno['cpf'] ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="endereco">Endereço</label>
                                        <input type="text" id="endereco" name="end" class="form-control" value="<?php echo $aluno['end'] ?>">
                                    </div>

                                    <!-- <div class="form-group">
                        <label for="telefone">Telefone</label>
                        <input type="text" id="telefone" name="tel" class="form-control" value="<?php echo $aluno['tel'] ?>">
                    </div> -->

                                    <div class="form-group">
                                        <label for="celular">Celular do Aluno</label>
                                        <input type="text" id="celular" name="cel" class="form-control" value="<?php echo $aluno['cel'] ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="facebook">Perfil do Facebook</label>
                                        <input type="text" id="facebook" name="face" class="form-control" value="<?php echo $aluno['face'] ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="instagram">Perfil do Instagram</label>
                                        <input type="text" id="instagram" name="instagram" class="form-control" placeholder="URL do perfil no instagram">
                                    </div>
                                    <div class="form-group">
                                        <label for="sexo">Gênero</label>
                                        <select id="sexo" name="sexo" class="form-control">
                                            <option value="1" <?php if ($aluno['sexo'] == 1) echo 'selected'; ?>>Masculino</option>
                                            <option value="2" <?php if ($aluno['sexo'] == 2) echo 'selected'; ?>>Feminino</option>
                                            <option value="3" <?php if ($aluno['sexo'] == 3) echo 'selected'; ?>>Outro</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="nasc">Data de Nascimento</label>
                                        <input type="text" id="nasc" name="data" class="form-control" value="<?php echo $aluno['data_nasc'] ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="ano_ingresso">Ano de Ingresso</label>
                                        <input type="text" id="ano_ingresso" name="ano_ingresso" class="form-control" maxlength="4" value="<?php echo $aluno['data_ingresso'] ?>">
                                        <p class="help-block">Ano em que o aluno entrou no programa</p>
                                    </div>

                                    <!-- <div class="form-group">
                        <label for="camiseta">Tamanho da Camiseta</label>
                        <input type="text" id="camiseta" name="camiseta" class="form-control" value="<?php echo $aluno['camiseta'] ?>">
                    </div> -->
                                    <?php if ($_SESSION['h'] == 3) :

                                        $id = $_SESSION['usuarioID'];
                                        $query = "SELECT * FROM supervisores WHERE id_usuario = '$id' LIMIT 1";
                                        $res = mysqli_query($_SG['link'], $query);
                                        $supervisor = mysqli_fetch_assoc($res);

                                        $query_escola = "SELECT id_escola, nome_escola FROM escola WHERE cidade LIKE '" . $supervisor['cidade'] . "'";
                                        $result = mysqli_query($_SG['link'], $query_escola);

                                    ?>

                                        <div class="form-group">
                                            <label for="buscaCidade">Cidade</label>
                                            <select readonly name="cidade" id="buscaCidade" class="form-control">
                                                <option value="<?php echo $supervisor['cidade']; ?>"><?php echo $supervisor['cidade']; ?></option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="escola">Escola</label>
                                            <select name="escola1" id="escola" class="form-control" required>
                                                <?php
                                                while ($escola1 = mysqli_fetch_assoc($result)) {
                                                    echo '<option ';
                                                    if ($escola1['id_escola'] == $aluno['id_escola'])
                                                        echo 'selected ';
                                                    echo 'value="' . $escola1['id_escola'] . '">';
                                                    echo $escola1['nome_escola'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>

                                    <?php else :

                                        $escola = mysqli_query($_SG['link'], "SELECT DISTINCT cidade FROM escola");
                                        $escola1 = mysqli_query($_SG['link'], "SELECT nome_escola, id_escola FROM escola");
                                        $result_escola = mysqli_query($_SG['link'], "SELECT nome_escola, id_escola FROM diretor_escola");

                                    ?>


                                        <div class="form-group">
                                            <label for="escola">Escola</label>
                                            <select name="escola1" id="escola" class="form-control" required>
                                                <?php
                                                while ($e = mysqli_fetch_assoc($escola1)) {
                                                    echo '<option ';
                                                    if ($e['id_escola'] == $aluno['id_escola'])
                                                        echo 'selected ';
                                                    echo 'value="' . $e['id_escola'] . '">';
                                                    echo $e['nome_escola'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>

                                    <?php endif; ?>

                                    <div class="form-group">
                                        <label for="serie">Série</label>
                                        <select name="serie" id="serie" class="form-control">
                                            <option value="" hidden>Selecione a série do aluno</option>
                                            <option value="5EF" <?php if ($aluno['serie'] == "5EF") echo "selected" ?>>5º ano - Ensino Fundamental</option>
                                            <option value="6EF" <?php if ($aluno['serie'] == "6EF") echo "selected" ?>>6º ano - Ensino Fundamental</option>
                                            <option value="7EF" <?php if ($aluno['serie'] == "7EF") echo "selected" ?>>7º ano - Ensino Fundamental</option>
                                            <option value="8EF" <?php if ($aluno['serie'] == "8EF") echo "selected" ?>>8º ano - Ensino Fundamental</option>
                                            <option value="9EF" <?php if ($aluno['serie'] == "9EF") echo "selected" ?>>9º ano - Ensino Fundamental</option>
                                            <option value="1EM" <?php if ($aluno['serie'] == "1EM") echo "selected" ?>>1º ano - Ensino Médio</option>
                                            <option value="2EM" <?php if ($aluno['serie'] == "2EM") echo "selected" ?>>2º ano - Ensino Médio</option>
                                            <option value="3EM" <?php if ($aluno['serie'] == "3EM") echo "selected" ?>>3º ano - Ensino Médio</option>
                                            <option value="formado" <?php if ($aluno['serie'] == "formado") echo "selected" ?>>Ensino Médio Completo</option>

                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="nome_pai">Nome do Responsável</label>
                                        <input type="text" id="nome_pai" name="pai" class="form-control" value="<?php echo $aluno['pai'] ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="nome_mae">Nome da Mãe (para alunos cadastrados antes de 2022)</label>
                                        <input type="text" id="nome_mae" name="mae" class="form-control" value="<?php echo $aluno['mae'] ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="email_pai">E-mail do Responsável </label>
                                        <input type="email_pai" placeholder="example@example.com.br" id="email_pai" name="email_pai" class="form-control" value="<?php echo $aluno['email_pai'] ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="celular_pai">Celular do Responsável </label>
                                        <input type="text" id="celular_pai" name="cel_pai" class="form-control" placeholder="Telefone móvel para contato" maxlength="11" value="<?php echo $aluno['cel_pai'] ?>">
                                    </div>

                                    <br><br>
                                    <?php
                                    while ($questao = mysqli_fetch_assoc($result_qs)) {
                                        $query_respostase = "SELECT resposta FROM respostase WHERE usuario_id = '" . $r . "' AND pergunta_num = '" . $questao['pergunta_num'] . "'";
                                        $result_respostase = mysqli_query($_SG['link'], $query_respostase);
                                        $respostase = mysqli_fetch_assoc($result_respostase);
                                        echo '<div style="text-align: left; width: 100%">';
                                        echo $questao['pergunta_num'] . ') ' . $questao['pergunta_texto'] . '<br><br>';
                                        switch ($questao['tipo']) {
                                            case 1:
                                                echo '<select name="r' . $questao['pergunta_num'] . '">';
                                                for ($i = 1; $i <= $questao['questoes']; $i++) {
                                                    echo '<option value="' . $i . '"';
                                                    if ($i == $respostase['resposta']) {
                                                        echo 'selected = "true" ';
                                                    }
                                                    echo '>' . $questao['r' . $i] . '</option>';
                                                }
                                                echo '</select>';
                                                break;
                                            case 2:

                                                $respostase['resposta'] = explode(',', $respostase['resposta']);
                                                for ($i = 1; $i <= $questao['questoes']; $i++) {

                                                    echo '<input type="checkbox" name="r' . $questao['pergunta_num'] . '[]" value="' . $i . '"';
                                                    for ($j = 0; $j < count($respostase['resposta']); $j++) {
                                                        if ($i == $respostase['resposta'][$j]) {
                                                            echo 'checked ';
                                                        }
                                                    }
                                                    echo '>' . $questao['r' . $i];
                                                    if ($i != $questao['questoes'])
                                                        echo '<br>';
                                                }
                                                break;
                                            case 3:
                                                echo '<textarea name="r' . $questao['pergunta_num'] . '" class="form-control">' . $respostase['resposta'] . '</textarea>';
                                                break;
                                        }
                                        echo '<br><br></div>';
                                    }
                                    ?>


                                    <div class="text-center">
                                        <button id="salvar" type="button" class="btn btn-primary btn-lg">Salvar</button>
                                        <input type="reset" class="btn btn-default pull-right">
                                    </div>
                                </form>
                            </div>
                        <?php elseif ($p == 'editar' and $_SESSION['h'] == 1) :

                        ?>
                            <div style="width: 90%; margin-left: 5%;">

                                <form id="editarCadastro" method="POST" action="editar.php" class="alunoCadastro formsEquipe forms-cadastrar">
                                    <h1 class="text-center">Editar Cadastro <br><small>Dados pessoais</small></h1>
                                    <hr><br>

                                    <input type="hidden" name="id_usuario" value="<?php echo $aluno['id_usuario']; ?>">

                                    <div class="form-group">
                                        <label for="nome">Nome</label>
                                        <input type="text" id="nome" name="nome_cadastrar" class="form-control" value="<?php echo $aluno['nome'] ?>">
                                    </div>


                                    <div class="form-group">
                                        <label for="email">E-mail</label>
                                        <input type="email" id="email" name="email" class="form-control" value="<?php echo $aluno['email'] ?>">
                                    </div>

                                    <div class="form-group" hidden>
                                        <label for="senha">Senha</label>
                                        <input autocomplete="new-password" type="password" id="senha" name="senha" class="form-control" value="<?php echo $aluno['senha'] ?>">
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="rg">RG</label>
                                            <input type="text" id="rg" name="rg" class="form-control" value="<?php echo $aluno['rg'] ?>">
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="ra">RA</label>
                                            <input type="text" id="ra" name="ra" class="form-control" value="<?php echo $aluno['ra'] ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="endereco">Endereço</label>
                                        <input type="text" id="endereco" name="end" class="form-control" value="<?php echo $aluno['end'] ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="telefone">Telefone</label>
                                        <input type="text" id="telefone" name="tel" class="form-control" value="<?php echo $aluno['tel'] ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="celular">Celular</label>
                                        <input type="text" id="celular" name="cel" class="form-control" value="<?php echo $aluno['cel'] ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="facebook">Perfil do Facebook</label>
                                        <input type="text" id="facebook" name="face" class="form-control" value="<?php echo $aluno['face'] ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="sexo">Sexo</label>
                                        <select id="sexo" name="sexo" class="form-control">
                                            <option value="1" <?php if ($aluno['sexo'] == 1) echo 'selected'; ?>>Masculino</option>
                                            <option value="2" <?php if ($aluno['sexo'] == 2) echo 'selected'; ?>>Feminino</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="etnia">Etnia</label>
                                        <select id="etnia" name="etnia" class="form-control">
                                            <option>--</option>
                                            <option value="1" <?php if ($aluno['etnia'] == 1) echo 'selected'; ?>>Branco(a)</option>
                                            <option value="2" <?php if ($aluno['etnia'] == 2) echo 'selected'; ?>>Pardo(a)</option>
                                            <option value="3" <?php if ($aluno['etnia'] == 3) echo 'selected'; ?>>Negro(a)</option>
                                            <option value="4" <?php if ($aluno['etnia'] == 4) echo 'selected'; ?>>Amarelo(a)</option>
                                            <option value="5" <?php if ($aluno['etnia'] == 5) echo 'selected'; ?>>Indígena</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="nome_pai">Nome do Pai</label>
                                        <input type="text" id="nome_pai" name="pai" class="form-control" value="<?php echo $aluno['pai'] ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="nome_mae">Nome da Mãe</label>
                                        <input type="text" id="nome_mae" name="mae" class="form-control" value="<?php echo $aluno['mae'] ?>">
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="nasc">Data de Nascimento</label>
                                            <input type="text" id="nasc" name="data" class="form-control" value="<?php echo $aluno['data_nasc'] ?>">
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="ano_ingresso">Data de Ingresso</label>
                                            <input type="date" id="ano_ingresso" name="ano_ingresso" class="form-control" value="<?php echo $aluno['data_ingresso'] ?>">
                                            <p class="help-block">Data em que o aluno entrou no programa</p>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="camiseta">Tamanho da Camiseta</label>
                                        <input type="text" id="camiseta" name="camiseta" class="form-control" value="<?php echo $aluno['camiseta'] ?>">
                                    </div>

                                    <?php if ($_SESSION['h'] == 3) :

                                        $id = $_SESSION['usuarioID'];
                                        $query = "SELECT * FROM supervisores WHERE id_usuario = '$id' LIMIT 1";
                                        $res = mysqli_query($_SG['link'], $query);
                                        $supervisor = mysqli_fetch_assoc($res);

                                        $query_escola = "SELECT id_escola, nome_escola FROM escola WHERE cidade LIKE '" . $supervisor['cidade'] . "'";
                                        $result = mysqli_query($_SG['link'], $query_escola);

                                    ?>

                                        <div class="form-group">
                                            <label for="buscaCidade">Cidade</label>
                                            <select readonly name="cidade" id="buscaCidade" class="form-control">
                                                <option value="<?php echo $result['cidade']; ?>"><?php echo $supervisor['cidade'];
                                                                                                    echo 'selected'; ?></option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="escola">Escola</label>
                                            <select name="escola1" id="escola" class="form-control" required>
                                                <?php
                                                while ($escola1 = mysqli_fetch_assoc($result)) {
                                                    echo '<option ';
                                                    if ($escola1['id_escola'] == $aluno['id_escola'])
                                                        echo 'selected ';
                                                    echo 'value="' . $escola1['id_escola'] . '">';
                                                    echo $escola1['nome_escola'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>

                                    <?php elseif ($_SESSION['h'] != 1) :

                                        $escola = mysqli_query($_SG['link'], "SELECT DISTINCT cidade FROM escola");
                                        $escola1 = mysqli_query($_SG['link'], "SELECT nome_escola, id_escola FROM escola");

                                    ?>


                                        <div class="form-group">
                                            <label for="escola">Escola</label>
                                            <select name="escola1" id="escola" class="form-control" required>
                                                <?php
                                                while ($e = mysqli_fetch_assoc($escola1)) {
                                                    echo '<option ';
                                                    if ($e['id_escola'] == $aluno['id_escola'])
                                                        echo 'selected ';
                                                    echo 'value="' . $e['id_escola'] . '">';
                                                    echo $e['nome_escola'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>


                                    <?php endif; ?>

                                    <div class="form-group">
                                        <label for="serie">Série</label>
                                        <select name="serie" id="serie" class="form-control">
                                            <option value="" hidden>Selecione a série do aluno</option>
                                            <option value="5EF" <?php if ($aluno['serie'] == "5EF") echo "selected" ?>>5º ano - Ensino Fundamental</option>
                                            <option value="6EF" <?php if ($aluno['serie'] == "6EF") echo "selected" ?>>6º ano - Ensino Fundamental</option>
                                            <option value="7EF" <?php if ($aluno['serie'] == "7EF") echo "selected" ?>>7º ano - Ensino Fundamental</option>
                                            <option value="8EF" <?php if ($aluno['serie'] == "8EF") echo "selected" ?>>8º ano - Ensino Fundamental</option>
                                            <option value="9EF" <?php if ($aluno['serie'] == "9EF") echo "selected" ?>>9º ano - Ensino Fundamental</option>
                                            <option value="1EM" <?php if ($aluno['serie'] == "1EM") echo "selected" ?>>1º ano - Ensino Médio</option>
                                            <option value="2EM" <?php if ($aluno['serie'] == "2EM") echo "selected" ?>>2º ano - Ensino Médio</option>
                                            <option value="3EM" <?php if ($aluno['serie'] == "3EM") echo "selected" ?>>3º ano - Ensino Médio</option>

                                        </select>
                                    </div>


                                    <br><br>
                                    <?php
                                    while ($questao = mysqli_fetch_assoc($result_qs)) {
                                        $query_respostase = "SELECT resposta FROM respostase WHERE usuario_id = '" . $r . "' AND pergunta_num = '" . $questao['pergunta_num'] . "'";
                                        $result_respostase = mysqli_query($_SG['link'], $query_respostase);
                                        $respostase = mysqli_fetch_assoc($result_respostase);
                                        echo '<div style="text-align: left; width: 100%">';
                                        echo $questao['pergunta_num'] . ') ' . $questao['pergunta_texto'] . '<br><br>';
                                        switch ($questao['tipo']) {
                                            case 1:
                                                echo '<select name="r' . $questao['pergunta_num'] . '">';
                                                for ($i = 1; $i <= $questao['questoes']; $i++) {
                                                    echo '<option value="' . $i . '"';
                                                    if ($i == $respostase['resposta']) {
                                                        echo 'selected = "true" ';
                                                    }
                                                    echo '>' . $questao['r' . $i] . '</option>';
                                                }
                                                echo '</select>';
                                                break;
                                            case 2:

                                                $respostase['resposta'] = explode(',', $respostase['resposta']);
                                                for ($i = 1; $i <= $questao['questoes']; $i++) {

                                                    echo '<input type="checkbox" name="r' . $questao['pergunta_num'] . '[]" value="' . $i . '"';
                                                    for ($j = 0; $j < count($respostase['resposta']); $j++) {
                                                        if ($i == $respostase['resposta'][$j]) {
                                                            echo 'checked ';
                                                        }
                                                    }
                                                    echo '>' . $questao['r' . $i];
                                                    if ($i != $questao['questoes'])
                                                        echo '<br>';
                                                }
                                                break;
                                            case 3:
                                                echo '<textarea name="r' . $questao['pergunta_num'] . '" class="form-control">' . $respostase['resposta'] . '</textarea>';
                                                break;
                                        }
                                        echo '<br><br></div>';
                                    }
                                    ?>


                                    <div class="text-center">
                                        <button id="salvar" type="button" class="btn btn-primary btn-lg">Salvar</button>
                                        <input type="reset" class="btn btn-default pull-right">
                                    </div>
                                </form>
                            </div>

                            <!-- 
                ============================================
                ============================================
                ==================== VER ===================
                ============================================
                ============================================
                -->

                        <?php elseif ($p == 'ver') :

                        if ($aluno['h'] == 0)
                            $color = "text-danger";
                        else
                            $color = "text-success";

                        ?>

                            <div class="container-fluid">

                                <div class="equipeTexto">
                                    <?php if ($_SESSION['h'] != 8) : ?>
                                        <p class="pull-right text-right">

                                            <a class="btn btn-primary" href="<?php echo $root_html ?>sistema/alunos/buscar/notas/<?php echo $r ?>"><i class="fa fa-signal"></i> Notas internas</a>
                                            &ensp;&ensp;
                                            <a class="btn btn-default" href="<?php echo $root_html ?>sistema/alunos/buscar/boletim/<?php echo $r ?>"><i class="fa fa-signal"></i> Notas externas</a>
                                        </p>
                                    <?php endif; ?>
                                    <h2 class="<?php echo $color ?>"><strong><?php echo $aluno['nome'] ?></strong></h2><br>
                                    <p><strong>E-mail: </strong><?php echo $aluno['email'] ?></p>
                                    <p><strong>RG: </strong><?php echo $aluno['rg'] ?></p>
                                    <p><strong>RA: </strong><?php echo $aluno['ra'] ?></p>
                                    <p><strong>Série: </strong><?php echo $aluno['série'] ?></p>
                                    <p><strong>Escola: </strong><?php echo $aluno_escola['nome_escola'] ?></p>
                                    <p><strong>Cidade: </strong><?php echo $aluno_escola['cidade'] ?></p>
                                    <p><strong>Celular: </strong><?php echo $aluno['cel'] ?></p>
                                    <p><strong>Telefone: </strong><?php echo $aluno['tel'] ?></p>
                                    <p><strong>Data de nascimento: </strong><?php echo $aluno['data_nasc'] ?></p>
                                    <p><strong>Data de cadastro: </strong><?php echo $aluno['data_cadastro'] ?></p>
                                    <p><strong>Data de ingresso: </strong><?php echo $aluno['data_ingresso'] ?></p>
                                    <p><strong>Facebook: </strong><?php echo $aluno['face'] ?></p>

                                    <br>

                                </div>

                            </div>


                            <!-- 
                ============================================
                ============================================
                =================== NOTAS ==================
                ============================================
                ============================================
                -->

                        <?php elseif ($p == 'notas') :

                        $query = "SELECT * FROM notas WHERE id_usuario = " . $r . " ORDER BY ano DESC";
                        $result = mysqli_query($_SG['link'], $query);

                        $query_aluno = "SELECT * FROM alunos AS a JOIN usuario AS u ON u.id_usuario = a.id_usuario WHERE a.id_usuario = " . $r . " LIMIT 1";
                        $result_aluno = mysqli_query($_SG['link'], $query_aluno);
                        $aluno = mysqli_fetch_assoc($result_aluno);
                        ?>
                            <div class="row">

                                <h1 align="center"><b><?php echo $aluno['nome'] ?></b></h1>

                                <?php
                                /* Verifica se existem resultados */
                                if (mysqli_num_rows($result) != 0) :
                                    /* Cria o array notas[] com os resultados de uma linha */
                                    $nota = mysqli_fetch_assoc($result);
                                    while ($nota != NULL) :
                                        $ano = $nota['ano'];
                                ?>
                                        <div class="col-md-6 col-md-offset-3" style="background: #fff; margin-top: 30px; border: 1px solid rgba(51,51,51,.2); width: 60%;">
                                            <button type="button" data-ano="<?php echo $ano ?>" data-id="<?php echo $nota['id']; ?>" class="removeAno btn btn-circle btn-danger pull-right" style="margin-right: -30px; margin-top: -20px;">
                                                <span class="glyphicon glyphicon-remove"></span>
                                            </button>
                                            <!---------------------------------------------------------------------------------------------->
                                            <div class="modal fade" id="confirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                <div class="modal-dialog modal-sm" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" data-target="#confirm" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                            <h4 class="modal-title" id="myModalLabel">Confirmação</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            Você tem certeza que deseja enviar as respostas?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                                                            <button type="button" class="btn btn-primary" id="enviar_respostas">Enviar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <!---------------------------------------------------------------------------------------------->

                                            <table class="table table-bordered bgWhite">
                                                <h2 align=" center"><?php echo $ano ?></h2>

                                                <tr align="center">
                                                    <td align="center"><b>---</b></td>
                                                    <td align="center"><b>Maratona do Conhecimento 1° Fase</b></td>
                                                    <td align="center"><b>Maratona do Conhecimento 2° Fase</b></td>
                                                    <td align="center"><b>Concurso Literário</b></td>
                                                    <td align="center"><b>Clube de Ciência 1 Fase</b></td>
                                                    <td align="center"><b>Clube de Ciência 2 Fase</b></td>
                                                    <td align="center"><b>Quantidade de Premios</b></td>
                                                </tr>

                                                <?php

                                                while ($nota['ano'] == $ano) : ?>

                                                    <!--Participação-->
                                                    <tr class="" align="center" onmouseover="$('td > i.fa-pencil', this).css('opacity', '1')" onmouseout="$('td > i.fa-pencil', this).css('opacity', '0')">
                                                        <!-- VER -->
                                                        <td class="view">
                                                            <i class="fa fa-pencil pull-left text-muted" style="opacity: 0;"></i>
                                                            </br>
                                                            Participação
                                                        </td>

                                                        <form method="POST">

                                                            <td>
                                                                <input type="checkbox" autocomplete="off" name="m1" id="m1" value="1" onClick="this.form.totalpart.value=checkChoice_3(this);" onchange="habilitar()">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" autocomplete="off" name="m2" id="m2" value="1" onClick="this.form.totalpart.value=checkChoice_3(this);" onchange="habilitar1()">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" autocomplete="off" name="cl" id="cl" value="1" onClick="this.form.totalpart.value=checkChoice_3(this);" onchange="habilitar2()">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" autocomplete="off" name="cc" id="cc" value="1" onClick="this.form.totalpart.value=checkChoice_3(this);" onchange="habilitar3()">
                                                            </td>

                                                            <td>
                                                                <input type="checkbox" autocomplete="off" name="cc" id="cc" value="1" onClick="this.form.totalpart.value=checkChoice_3(this);" onchange="habilitar4()">
                                                            </td>
                                                            <input class="form-control input-lg" id="totalpart" name="totalpart" type="hidden">
                                                            <!--<td class="linhaNota view view-nota" id="viewtotalpart<?php //echo $nota['id'];
                                                                                                                        ?>" data-id="<?php //echo $nota['id'];
                                                                                                                                        ?>" data-tipo="totalpart" >
                                            </td>
                                            <td class="edit" id="edittotalpart<?php //echo $nota['id'];
                                                                                ?>" hidden>
                                            <input  type="text" name="totalpart" style="width: 25%;">    
                                            </input>
                                            <input type="hidden" id="hiddentotalpart" value="1" >
                                            </td> -->


                                                        </form>

                                                        <form id="formsEditarpart<?php echo $nota['id']; ?>">
                                                            <td class="linhaNota view view-nota edit" id="viewtotalpart<?php echo $nota['id']; ?>" data-id="<?php echo $nota['id']; ?>" data-tipo="hiddentotalpart">
                                                                <span class="btn btn-lg btn-primary">Salvar</span>
                                                            <td class="edit" id="edittotalpart<?php echo $nota['id']; ?>" hidden>
                                                                <input style="width: 50%;" autofocus id="hiddentotalpart" class="form-control" type="text" name="hiddentotalpart" value="1">
                                                            </td>
                                                            <input type="hidden" value="<?php echo $nota['id']; ?>" name="id">
                                                            </td>

                                                        </form>
                                                    </tr>

                                                    <!-- NOTAS -->
                                                    <tr class="" align="center" onmouseover="$('td > i.fa-pencil', this).css('opacity', '1')" onmouseout="$('td > i.fa-pencil', this).css('opacity', '0')">
                                                        <!-- VER -->
                                                        <td class="view">
                                                            <i class="fa fa-pencil pull-left text-muted" style="opacity: 0;"></i>
                                                            Notas
                                                        </td>
                                                        <td class="linhaNota view view-nota" id="viewMaratona1-nota<?php echo $nota['id']; ?>" data-id="<?php echo $nota['id']; ?>" data-tipo="maratona1-nota">
                                                            <?php echo $nota['maratona-do-conhecimento-1'] ?>
                                                        </td>
                                                        <!-- EDITAR MARATONA DO CONHECIMENTO 1-->
                                                        <form id="formsEditarMaratona1-nota<?php echo $nota['id']; ?>">
                                                            <td class="edit" id="editMaratona1-nota<?php echo $nota['id']; ?>" hidden>
                                                                <input style="width: 25%;" type="text" id="maratona-do-conhecimento-1-nota" name="maratona-do-conhecimento-1-nota" value="<?php echo $nota['maratona-do-conhecimento-1'] ?>" disabled>
                                                            </td>
                                                            <input type="hidden" value="<?php echo $nota['id']; ?>" name="id">
                                                        </form>

                                                        <td class="linhaNota view view-nota" id="viewMaratona2-nota<?php echo $nota['id']; ?>" data-id="<?php echo $nota['id']; ?>" data-tipo="maratona2-nota">
                                                            <?php echo $nota['maratona-do-conhecimento-2'] ?>
                                                        </td>

                                                        <!-- EDITAR MARATONA DO CONHECIMENTO 2 -->
                                                        <form id="formsEditarMaratona2-nota<?php echo $nota['id']; ?>">
                                                            <td class="edit" id="editMaratona2-nota<?php echo $nota['id']; ?>" hidden>
                                                                <input style="width: 25%;" type="text" id="maratona-do-conhecimento-2-nota" name="maratona-do-conhecimento-2-nota" value="<?php echo $nota['maratona-do-conhecimento-2'] ?>" disabled>
                                                            </td>
                                                            <input type="hidden" value="<?php echo $nota['id']; ?>" name="id">
                                                        </form>

                                                        <td class="linhaNota view view-nota" id="viewLiterario<?php echo $nota['id']; ?>" data-id="<?php echo $nota['id']; ?>" data-tipo="concurso-literario">
                                                            <?php echo $nota['concurso-literario'] ?>
                                                        </td>
                                                        <!-- EDITAR CONCURSO LITERARIO -->
                                                        <form id="formsEditarLiterario<?php echo $nota['id']; ?>">
                                                            <td class="edit" id="editLiterario<?php echo $nota['id']; ?>" hidden>
                                                                <input style="width: 35%;" type="text" id="concurso-literario" name="concurso-literario" value="<?php echo $nota['concurso-literario'] ?>" disabled>
                                                            </td>
                                                            <input type="hidden" value="<?php echo $nota['id']; ?>" name="id">
                                                        </form>

                                                        <td class="linhaNota view view-nota" id="viewClube<?php echo $nota['id']; ?>" data-id="<?php echo $nota['id']; ?>" data-tipo="clube_de_ciencia">
                                                            <?php echo $nota['clube_de_ciencia'] ?>
                                                        </td>

                                                        <td class="linhaNota view view-nota" id="viewClube<?php echo $nota['id']; ?>" data-id="<?php echo $nota['id']; ?>" data-tipo="clube_de_ciencia">
                                                            <?php echo $nota['club_segunda_fase'] ?>
                                                        </td>
                                                        <!-- EDITAR CLUBE DE CIENCIAS -->
                                                        <form id="formsEditarClube<?php echo $nota['id']; ?>">
                                                            <td class="edit" id="editClube<?php echo $nota['id']; ?>" hidden>
                                                                <input style="width: 45%;" type="text" id="clube-de-ciencia" name="clube-de-ciencia" value="<?php echo $nota['clube_de_ciencia'] ?>" disabled>
                                                            </td>
                                                            <input type="hidden" value="<?php echo $nota['id']; ?>" name="id">
                                                        </form>
                                                        <td>---</td>
                                                    </tr>

                

                                                    <!-- MAIOR DIFICULDADE-->
                                                    <tr class="" align="center" onmouseover="$('td > i.fa-pencil', this).css('opacity', '1')" onmouseout="$('td > i.fa-pencil', this).css('opacity', '0')">
                                                        <!-- VER -->
                                                        <td class="view">
                                                            <i class="fa fa-pencil pull-left text-muted" style="opacity: 0;"></i>
                                                            Maior Dificuldade
                                                        </td>

                                                        <td class="linhaNota view view-dificuldade" id="viewMaratona1-dificuldade<?php echo $nota['id']; ?>" data-id="<?php echo $nota['id']; ?>" data-tipo="maratona1-dificuldade">
                                                            <?php echo $nota['dificuldade-maratona-1'] ?>
                                                        </td>

                                                        <!-- Maior Dificuldade Maratona 1 -->
                                                        <form id="formsEditarMaratona1-dificuldade<?php echo $nota['id']; ?>">
                                                            <td class="edit" id="editMaratona1-dificuldade<?php echo $nota['id']; ?>" hidden>
                                                                <select style="width: 100%;" id="maratona-do-conhecimento-1-dificuldade" name="maratona-do-conhecimento-1-dificuldade" disabled>
                                                                    <option value=""> </option>
                                                                    <?php
                                                                    $ano_maratona = $ano . '/1';
                                                                    $query_materias = "SELECT DISTINCT genero FROM maratona_questoes WHERE ano = '$ano_maratona' ORDER BY genero";
                                                                    $res_materias = mysqli_query($_SG['link'], $query_materias);

                                                                    while ($row = mysqli_fetch_array($res_materias)) :
                                                                    ?>
                                                                        <option value="<?php echo $row['genero'] ?>" <?php if ($row['genero'] === $nota['dificuldade-maratona-1']) echo ' selected ' ?>>
                                                                            <?php echo $row['genero'] ?>
                                                                        </option>
                                                                    <?php endwhile; ?>
                                                                </select>
                                                            </td>
                                                            <input type="hidden" value="<?php echo $nota['id']; ?>" name="id">
                                                        </form>

                                                        <td class="linhaNota view view-dificuldade" id="viewMaratona2-dificuldade<?php echo $nota['id']; ?>" data-id="<?php echo $nota['id']; ?>" data-tipo="maratona2-dificuldade">
                                                            <?php echo $nota['dificuldade-maratona-2'] ?>
                                                        </td>

                                                        <!-- Maior Dificuldade Maratona 2 -->
                                                        <form id="formsEditarMaratona2-dificuldade<?php echo $nota['id']; ?>">
                                                            <td class="edit" id="editMaratona2-dificuldade<?php echo $nota['id']; ?>" hidden>
                                                                <select style="width: 100%;" id="maratona-do-conhecimento-2-dificuldade" name="maratona-do-conhecimento-2-dificuldade" disabled>
                                                                    <option value=""></option>
                                                                    <?php
                                                                    $ano_maratona = $ano . '/1';
                                                                    $query_materias = "SELECT DISTINCT genero FROM maratona_questoes WHERE ano = '$ano_maratona' ORDER BY genero";
                                                                    $res_materias = mysqli_query($_SG['link'], $query_materias);

                                                                    while ($row = mysqli_fetch_array($res_materias)) :
                                                                    ?>
                                                                        <option value="<?php echo $row['genero'] ?>" <?php if ($row['genero'] === $nota['dificuldade-maratona-2']) echo ' selected ' ?>>
                                                                            <?php echo $row['genero'] ?>
                                                                        </option>
                                                                    <?php endwhile; ?>
                                                                </select>
                                                            </td>
                                                            <input type="hidden" value="<?php echo $nota['id']; ?>" name="id">
                                                        </form>

                                                        <td>---</td>
                                                        <td>---</td>
                                                        <td>---</td>
                                                        <td>---</td>

                                                    </tr>

                                                    <!--Ouro-->
                                                    <tr class="" align="center" onmouseover="$('td > i.fa-pencil', this).css('opacity', '1')" onmouseout="$('td > i.fa-pencil', this).css('opacity', '0')">
                                                        <!-- VER -->
                                                        <td class="view">
                                                            <i class="fa fa-pencil pull-left text-muted" style="opacity: 0;"></i>
                                                            </br>
                                                            Ouro
                                                        </td>

                                                        <form method="POST">
                                                            <td>
                                                                <input type="checkbox" autocomplete="off" id="checkM1o" name="checkM1o" value="1" onClick="this.form.totalouro.value=checkChoice(this);" disabled onchange="habilitar()">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" autocomplete="off" id="checkM2o" name="checkM2o" value="1" onClick="this.form.totalouro.value=checkChoice(this);" disabled onchange="habilitar1()">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" autocomplete="off" id="checkCLo" name="checkCLo" value="1" onClick="this.form.totalouro.value=checkChoice(this);" disabled onchange="habilitar2()">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" autocomplete="off" id="checkCCo" name="checkCCo" value="1" onClick="this.form.totalouro.value=checkChoice(this);" disabled onchange="habilitar3()">
                                                            </td>

                                                            <td>
                                                                <input type="checkbox" autocomplete="off" id="checkCCo" name="checkCCo" value="1" onClick="this.form.totalouro.value=checkChoice(this);" disabled onchange="habilitar4()">
                                                            </td>
                                                            <input class="form-control input-lg" id="totalouro" name="totalouro" type="hidden">
                                                        </form>

                                                        <form id="formsEditarouro<?php echo $nota['id']; ?>">
                                                            <td class="linhaNota view view-nota edit" id="viewtotalouro<?php echo $nota['id']; ?>" data-id="<?php echo $nota['id']; ?>" data-tipo="hiddentotalouro">
                                                                <span class="btn btn-lg btn-primary">Salvar</span>
                                                            <td class="edit" id="edittotalouro<?php echo $nota['id']; ?>" hidden>
                                                                <input style="width: 50%;" autofocus id="hiddentotalouro" class="form-control" type="text" name="hiddentotalouro" value="0">
                                                            </td>
                                                            <input type="hidden" value="<?php echo $nota['id']; ?>" name="id">
                                                            </td>

                                                        </form>

                                                    </tr>
                                                    <!-- Prata-->
                                                    <tr class="" align="center" onmouseover="$('td > i.fa-pencil', this).css('opacity', '1')" onmouseout="$('td > i.fa-pencil', this).css('opacity', '0')">
                                                        <!-- VER -->
                                                        <td class="view">
                                                            <i class="fa fa-pencil pull-left text-muted" style="opacity: 0;"></i>
                                                            </br>
                                                            Prata
                                                        </td>

                                                        <form method="POST">
                                                            <td>
                                                                <input type="checkbox" autocomplete="off" id="checkM1p" name="checkM1p" value="1" onClick="this.form.totalprata.value=checkChoice_1(this);" disabled onchange="habilitar()">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" autocomplete="off" id="checkM2p" name="checkM2p" value="1" onClick="this.form.totalprata.value=checkChoice_1(this);" disabled onchange="habilitar1()">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" autocomplete="off" id="checkCLp" name="checkCLp" value="1" onClick="this.form.totalprata.value=checkChoice_1(this);" disabled onchange="habilitar2()">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" autocomplete="off" id="checkCCp" name="checkCCp" value="1" onClick="this.form.totalprata.value=checkChoice_1(this);" disabled onchange="habilitar3()">
                                                            </td>

                                                            <td>
                                                                <input type="checkbox" autocomplete="off" id="checkCCp" name="checkCCp" value="1" onClick="this.form.totalprata.value=checkChoice_1(this);" disabled onchange="habilitar4()">
                                                            </td>
                                                            <input class="form-control input-lg" id="totalprata" name="totalprata" type="hidden">
                                                        </form>

                                                        <form id="formsEditarprata<?php echo $nota['id']; ?>">
                                                            <td class="linhaNota view view-nota edit" id="viewtotalprata<?php echo $nota['id']; ?>" data-id="<?php echo $nota['id']; ?>" data-tipo="hiddentotalprata">
                                                                <span class="btn btn-lg btn-primary">Salvar</span>
                                                            <td class="edit" id="edittotalprata<?php echo $nota['id']; ?>" hidden>
                                                                <input style="width: 50%;" autofocus id="hiddentotalprata" class="form-control" type="text" name="hiddentotalprata" value="0">
                                                            </td>
                                                            <input type="hidden" value="<?php echo $nota['id']; ?>" name="id">
                                                            </td>

                                                        </form>
                                                    </tr>
                                                    <!--Bronze-->
                                                    <tr class="" align="center" onmouseover="$('td > i.fa-pencil', this).css('opacity', '1')" onmouseout="$('td > i.fa-pencil', this).css('opacity', '0')">
                                                        <!-- VER -->
                                                        <td class="view">
                                                            <i class="fa fa-pencil pull-left text-muted" style="opacity: 0;"></i>
                                                            </br>
                                                            Bronze
                                                        </td>

                                                        <form method="POST">
                                                            <td>
                                                                <input type="checkbox" autocomplete="off" id="checkM1b" name="checkM1b" value="1" onClick="this.form.totalbronze.value=checkChoice_2(this);" disabled onchange="habilitar()">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" autocomplete="off" id="checkM2b" name="checkM2b" value="1" onClick="this.form.totalbronze.value=checkChoice_2(this);" disabled onchange="habilitar1()">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" autocomplete="off" id="checkCLb" name="checkCLb" value="1" onClick="this.form.totalbronze.value=checkChoice_2(this);" disabled onchange="habilitar2()">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" autocomplete="off" id="checkCCb" name="checkCCb" value="1" onClick="this.form.totalbronze.value=checkChoice_2(this);" disabled onchange="habilitar3()">
                                                            </td>

                                                            <td>
                                                                <input type="checkbox" autocomplete="off" id="checkCCb" name="checkCCb" value="1" onClick="this.form.totalbronze.value=checkChoice_2(this);" disabled onchange="habilitar4()">
                                                            </td>
                                                            <input class="form-control input-lg" id="totalbronze" name="totalbronze" type="hidden">
                                                        </form>

                                                        <form id="formsEditarbronze<?php echo $nota['id']; ?>">
                                                            <td class="linhaNota view view-nota edit" id="viewtotalbronze<?php echo $nota['id']; ?>" data-id="<?php echo $nota['id']; ?>" data-tipo="hiddentotalbronze">
                                                                <span class="btn btn-lg btn-primary">Salvar</span>
                                                            <td class="edit" id="edittotalbronze<?php echo $nota['id']; ?>" hidden>
                                                                <input style="width: 50%;" autofocus id="hiddentotalbronze" class="form-control" type="text" name="hiddentotalbronze" value="0">
                                                            </td>
                                                            <input type="hidden" value="<?php echo $nota['id']; ?>" name="id">
                                                            </td>

                                                        </form>

                                                    </tr>
                                                    <table>
                                                        <tr></tr>
                                                    </table>




                                                    </br>
                                                    </br>
                                        <?php
                                                    $nota = mysqli_fetch_assoc($result);
                                                endwhile;
                                                /* Atualiza o $ano */
                                                $ano = $nota['ano'];
                                                echo '</table></div>';
                                            endwhile;
                                        else :
                                            echo '<div style="margin-top: 50px;" class="alert alert-warning text-center col-md-4 col-md-offset-4"> <h2><span class="glyphicon glyphicon-exclamation-sign"></span></h2> Nenhuma nota cadastrada. <br> Clique no botão abaixo para adicionar uma nota ao aluno.</div>';
                                        endif;
                                        ?>

                                        <div class="row" align="center">
                                            <div class="col-md-12">
                                                <br><br>
                                                <form id="cadastraAno" action="" style="width: 6%;" onSubmit="return false;">
                                                    <button id="adicionaAno" type="button" class="btn btn-circle btn-lg btn-primary" data-container="body" data-toggle="popover" data-placement="top" data-html="true" title="Digite o ano que deseja cadastrar" data-content='                                
                                        <input autofocus id="inputAno" class="form-control" type="text" placeholder="" name="ano">
                                        <input id="inputID" type="hidden" name="id_usuario" value="<?php echo $r ?>">'>
                                                        <span class="glyphicon glyphicon-plus"></span>

                                                    </button>
                                                </form>
                                            </div>
                                        </div>

                                        </div> <!-- /row -->

                                    <?php elseif ($p == 'boletim') :

                                    $query = "SELECT * FROM `notas_externas` WHERE id_usuario = " . $r . " ORDER BY ano DESC";
                                    $result = mysqli_query($_SG['link'], $query);

                                    ?>
                                        <div class="row">


                                            <h1 align="center"><b><?php echo $aluno['nome'] ?></b></h1>

                                            <?php
                                            /* Verifica se existem resultados */
                                            if (mysqli_num_rows($result) != 0) :
                                                /* Cria o array notas[] com os resultados de uma linha */
                                                $nota = mysqli_fetch_assoc($result);
                                                $cont = 0;
                                                while ($nota != NULL) :
                                                    $ano = $nota['ano'];

                                                    if ($cont == 0)
                                                        echo '<div class="row">';

                                            ?>
                                                    <div class="col-md-3 col-md-offset-2 clearfix" style="background: #fff; margin-top: 30px; border: 1px solid rgba(51,51,51,.2)">
                                                        <button type="button" data-ano="<?php echo $ano ?>" data-id="<?php echo $nota['id']; ?>" class="removeAno_externo btn btn-circle btn-danger pull-right" style="margin-right: -30px; margin-top: -20px;">
                                                            <span class="glyphicon glyphicon-remove"></span>
                                                        </button>

                                                        <table class="table table-hover table-bordered bgWhite">
                                                            <h2 align="center"><?php echo $ano ?></h2>

                                                            <tr align="center">
                                                                <th class="text-center"><b>Matéria</b></th>
                                                                <th class="text-center"><b>Nota</b></th>
                                                            </tr>

                                                            <?php
                                                            while ($nota['ano'] == $ano) : ?>
                                                                <!-- Matemática -->

                                                                <tr class="linhaNota" data-id="<?php echo $nota['id']; ?>" data-tipo="matematica" align="center" onmouseover="$('td > i.fa-pencil', this).css('opacity', '1')" onmouseout="$('td > i.fa-pencil', this).css('opacity', '0')">
                                                                    <!-- VER -->
                                                                    <td class="view">
                                                                        <i class="fa fa-pencil pull-left text-muted" style="opacity: 0;"></i>

                                                                        Matemática
                                                                    </td>
                                                                    <td class="view view-nota" id="viewMatematica<?php echo $nota['id']; ?>">
                                                                        <?php echo $nota['matematica'] ?>
                                                                    </td>
                                                                    <!-- EDITAR -->
                                                                    <form id="formsEditarMatematica<?php echo $nota['id']; ?>">
                                                                        <td class="edit" id="editMatematica<?php echo $nota['id']; ?>" hidden>
                                                                            <input style="width: 25%;" type="text" name="matematica" value="<?php echo $nota['matematica'] ?>">
                                                                        </td>
                                                                        <input type="hidden" value="matematica" name="tipo">

                                                                        <input type="hidden" value="<?php echo $nota['id']; ?>" name="id">
                                                                    </form>
                                                                </tr>

                                                                <!-- Portugues -->
                                                                <tr class="linhaNota" data-id="<?php echo $nota['id']; ?>" data-tipo="portugues" align="center" onmouseover="$('td > i.fa-pencil', this).css('opacity', '1')" onmouseout="$('td > i.fa-pencil', this).css('opacity', '0')">
                                                                    <!-- VER -->
                                                                    <td class="view">
                                                                        <i class="fa fa-pencil pull-left text-muted" style="opacity: 0;"></i>

                                                                        Português
                                                                    </td>
                                                                    <td class="view view-nota" id="viewPortugues<?php echo $nota['id']; ?>">
                                                                        <?php echo $nota['portugues'] ?>
                                                                    </td>
                                                                    <!-- EDITAR -->
                                                                    <form id="formsEditarPortugues<?php echo $nota['id']; ?>">
                                                                        <td class="edit" id="editPortugues<?php echo $nota['id']; ?>" hidden>
                                                                            <input style="width: 25%;" type="text" name="portugues" value="<?php echo $nota['portugues'] ?>">
                                                                        </td>
                                                                        <input type="hidden" value="<?php echo $nota['id']; ?>" name="id">
                                                                        <input type="hidden" value="portugues" name="tipo">
                                                                    </form>
                                                                </tr>

                                                                <!-- Ciências -->
                                                                <tr class="linhaNota" data-id="<?php echo $nota['id']; ?>" data-tipo="ciencias" align="center" onmouseover="$('td > i.fa-pencil', this).css('opacity', '1')" onmouseout="$('td > i.fa-pencil', this).css('opacity', '0')">
                                                                    <!-- VER -->
                                                                    <td class="view">
                                                                        <i class="fa fa-pencil pull-left text-muted" style="opacity: 0;"></i>

                                                                        Ciências
                                                                    </td>
                                                                    <td class="view view-nota" id="viewCiencias<?php echo $nota['id']; ?>">
                                                                        <?php echo $nota['ciencias'] ?>
                                                                    </td>
                                                                    <!-- EDITAR -->
                                                                    <form id="formsEditarCiencias<?php echo $nota['id']; ?>">
                                                                        <td class="edit" id="editCiencias<?php echo $nota['id']; ?>" hidden>
                                                                            <input style="width: 25%;" type="text" name="ciencias" value="<?php echo $nota['ciencias'] ?>">
                                                                        </td>
                                                                        <input type="hidden" value="<?php echo $nota['id']; ?>" name="id">
                                                                        <input type="hidden" value="ciencias" name="tipo">

                                                                    </form>
                                                                </tr>

                                                                <!-- História -->
                                                                <tr class="linhaNota" data-id="<?php echo $nota['id']; ?>" data-tipo="historia" align="center" onmouseover="$('td > i.fa-pencil', this).css('opacity', '1')" onmouseout="$('td > i.fa-pencil', this).css('opacity', '0')">
                                                                    <!-- VER -->
                                                                    <td class="view">
                                                                        <i class="fa fa-pencil pull-left text-muted" style="opacity: 0;"></i>

                                                                        História
                                                                    </td>
                                                                    <td class="view view-nota" id="viewHistoria<?php echo $nota['id']; ?>">
                                                                        <?php echo $nota['historia'] ?>
                                                                    </td>
                                                                    <!-- EDITAR -->
                                                                    <form id="formsEditarHistoria<?php echo $nota['id']; ?>">
                                                                        <td class="edit" id="editHistoria<?php echo $nota['id']; ?>" hidden>
                                                                            <input style="width: 25%;" type="text" name="historia" value="<?php echo $nota['historia'] ?>">
                                                                        </td>
                                                                        <input type="hidden" value="historia" name="tipo">

                                                                        <input type="hidden" value="<?php echo $nota['id']; ?>" name="id">
                                                                    </form>
                                                                </tr>

                                                                <!-- Geografia -->
                                                                <tr class="linhaNota" data-id="<?php echo $nota['id']; ?>" data-tipo="geografia" align="center" onmouseover="$('td > i.fa-pencil', this).css('opacity', '1')" onmouseout="$('td > i.fa-pencil', this).css('opacity', '0')">
                                                                    <!-- VER -->
                                                                    <td class="view">
                                                                        <i class="fa fa-pencil pull-left text-muted" style="opacity: 0;"></i>

                                                                        Geografia
                                                                    </td>
                                                                    <td class="view view-nota" id="viewGeografia<?php echo $nota['id']; ?>">
                                                                        <?php echo $nota['geografia'] ?>
                                                                    </td>
                                                                    <!-- EDITAR -->
                                                                    <form id="formsEditarGeografia<?php echo $nota['id']; ?>">
                                                                        <td class="edit" id="editGeografia<?php echo $nota['id']; ?>" hidden>
                                                                            <input style="width: 25%;" type="text" name="geografia" value="<?php echo $nota['geografia'] ?>">
                                                                        </td>
                                                                        <input type="hidden" value="<?php echo $nota['id']; ?>" name="id">
                                                                        <input type="hidden" value="geografia" name="tipo">

                                                                    </form>
                                                                </tr>

                                                                <!-- Inglês -->
                                                                <tr class="linhaNota" data-id="<?php echo $nota['id']; ?>" data-tipo="ingles" align="center" onmouseover="$('td > i.fa-pencil', this).css('opacity', '1')" onmouseout="$('td > i.fa-pencil', this).css('opacity', '0')">
                                                                    <!-- VER -->
                                                                    <td class="view">
                                                                        <i class="fa fa-pencil pull-left text-muted" style="opacity: 0;"></i>

                                                                        Inglês
                                                                    </td>
                                                                    <td class="view view-nota" id="viewIngles<?php echo $nota['id']; ?>">
                                                                        <?php echo $nota['ingles'] ?>
                                                                    </td>
                                                                    <!-- EDITAR -->
                                                                    <form id="formsEditarIngles<?php echo $nota['id']; ?>">
                                                                        <td class="edit" id="editIngles<?php echo $nota['id']; ?>" hidden>
                                                                            <input style="width: 25%;" type="text" name="ingles" value="<?php echo $nota['ingles'] ?>">
                                                                        </td>
                                                                        <input type="hidden" value="<?php echo $nota['id']; ?>" name="id">
                                                                        <input type="hidden" value="ingles" name="tipo">

                                                                    </form>
                                                                </tr>

                                                                <!-- Quimica -->
                                                                <tr class="linhaNota" data-id="<?php echo $nota['id']; ?>" data-tipo="quimica" align="center" onmouseover="$('td > i.fa-pencil', this).css('opacity', '1')" onmouseout="$('td > i.fa-pencil', this).css('opacity', '0')">
                                                                    <!-- VER -->
                                                                    <td class="view">
                                                                        <i class="fa fa-pencil pull-left text-muted" style="opacity: 0;"></i>

                                                                        Química
                                                                    </td>
                                                                    <td class="view view-nota" id="viewQuimica<?php echo $nota['id']; ?>">
                                                                        <?php echo $nota['quimica'] ?>
                                                                    </td>
                                                                    <!-- EDITAR -->
                                                                    <form id="formsEditarQuimica<?php echo $nota['id']; ?>">
                                                                        <td class="edit" id="editQuimica<?php echo $nota['id']; ?>" hidden>
                                                                            <input style="width: 25%;" type="text" name="quimica" value="<?php echo $nota['quimica'] ?>">
                                                                        </td>
                                                                        <input type="hidden" value="<?php echo $nota['id']; ?>" name="id">
                                                                        <input type="hidden" value="quimica" name="tipo">

                                                                    </form>
                                                                </tr>

                                                                <!-- Física -->
                                                                <tr class="linhaNota" data-id="<?php echo $nota['id']; ?>" data-tipo="fisica" align="center" onmouseover="$('td > i.fa-pencil', this).css('opacity', '1')" onmouseout="$('td > i.fa-pencil', this).css('opacity', '0')">
                                                                    <!-- VER -->
                                                                    <td class="view">
                                                                        <i class="fa fa-pencil pull-left text-muted" style="opacity: 0;"></i>

                                                                        Física
                                                                    </td>
                                                                    <td class="view view-nota" id="viewFisica<?php echo $nota['id']; ?>">
                                                                        <?php echo $nota['fisica'] ?>
                                                                    </td>
                                                                    <!-- EDITAR -->
                                                                    <form id="formsEditarFisica<?php echo $nota['id']; ?>">
                                                                        <td class="edit" id="editFisica<?php echo $nota['id']; ?>" hidden>
                                                                            <input style="width: 25%;" type="text" name="fisica" value="<?php echo $nota['fisica'] ?>">
                                                                        </td>
                                                                        <input type="hidden" value="<?php echo $nota['id']; ?>" name="id">
                                                                        <input type="hidden" value="fisica" name="tipo">

                                                                    </form>
                                                                </tr>

                                                                <!-- Sociologia -->
                                                                <tr class="linhaNota" data-id="<?php echo $nota['id']; ?>" data-tipo="sociologia" align="center" onmouseover="$('td > i.fa-pencil', this).css('opacity', '1')" onmouseout="$('td > i.fa-pencil', this).css('opacity', '0')">
                                                                    <!-- VER -->
                                                                    <td class="view">
                                                                        <i class="fa fa-pencil pull-left text-muted" style="opacity: 0;"></i>

                                                                        Sociologia
                                                                    </td>
                                                                    <td class="view view-nota" id="viewSociologia<?php echo $nota['id']; ?>">
                                                                        <?php echo $nota['sociologia'] ?>
                                                                    </td>
                                                                    <!-- EDITAR -->
                                                                    <form id="formsEditarSociologia<?php echo $nota['id']; ?>">
                                                                        <td class="edit" id="editSociologia<?php echo $nota['id']; ?>" hidden>
                                                                            <input style="width: 25%;" type="text" name="sociologia" value="<?php echo $nota['sociologia'] ?>">
                                                                        </td>
                                                                        <input type="hidden" value="<?php echo $nota['id']; ?>" name="id">
                                                                        <input type="hidden" value="sociologia" name="tipo">

                                                                    </form>
                                                                </tr>

                                                                <!-- Filosofia -->
                                                                <tr class="linhaNota" data-id="<?php echo $nota['id']; ?>" data-tipo="filosofia" align="center" onmouseover="$('td > i.fa-pencil', this).css('opacity', '1')" onmouseout="$('td > i.fa-pencil', this).css('opacity', '0')">
                                                                    <!-- VER -->
                                                                    <td class="view">
                                                                        <i class="fa fa-pencil pull-left text-muted" style="opacity: 0;"></i>

                                                                        Filosofia
                                                                    </td>
                                                                    <td class="view view-nota" id="viewFilosofia<?php echo $nota['id']; ?>">
                                                                        <?php echo $nota['filosofia'] ?>
                                                                    </td>
                                                                    <!-- EDITAR -->
                                                                    <form id="formsEditarFilosofia<?php echo $nota['id']; ?>">
                                                                        <td class="edit" id="editFilosofia<?php echo $nota['id']; ?>" hidden>
                                                                            <input style="width: 25%;" type="text" name="filosofia" value="<?php echo $nota['filosofia'] ?>">
                                                                        </td>
                                                                        <input type="hidden" value="<?php echo $nota['id']; ?>" name="id">
                                                                        <input type="hidden" value="filosofia" name="tipo">

                                                                    </form>
                                                                </tr> ?>

                                                                <!-- Média -->
                                                                <tr class="linhaNota" data-id="<?php echo $nota['id']; ?>" data-tipo="media" align="center" onmouseover="$('td > i.fa-pencil', this).css('opacity', '1')" onmouseout="$('td > i.fa-pencil', this).css('opacity', '0')">
                                                                    <!-- VER -->
                                                                     <td class="view">
                                                                            <i class="fa fa-pencil pull-left text-muted" style="opacity: 0;"></i>

                                                                            Média Geral
                                                                        </td>
                                                                        <td class="view view-nota" id="viewMedia<?php echo $nota['id']; ?>">
                                                                            <?php echo $nota['media'] ?>
                                                                        </td>
                                                                    <!-- EDITAR -->
                                                                    <form id="formsEditarMedia<?php echo $nota['id']; ?>">
                                                                        <td class="edit" id="editMedia<?php echo $nota['id']; ?>" hidden>
                                                                            <input style="width: 25%;" type="text" name="media" value="<?php echo $nota['media'] ?>">
                                                                        </td>
                                                                        <input type="hidden" value="<?php echo $nota['id']; ?>" name="id">
                                                                        <input type="hidden" value="media" name="tipo">

                                                                    </form>
                                                                </tr>

                                                                <tr>
                                                                    <td align="center">Avaliação Geral</td>
                                                                    <td align="center">
                                                                        <select id="avaliacao<?php echo $nota['id'] ?>" name="avaliacao" id="avaliacao" class="avaliacao" data-id="<?php echo $nota['id'] ?>" data-ano="<?php echo $nota['ano'] ?>">
                                                                            <option value="" hidden>--</option>
                                                                            <option value="excelente" <?php if ($nota['avaliacao'] == "excelente") echo "selected" ?>>Excelente</option>
                                                                            <option value="bom" <?php if ($nota['avaliacao'] == "bom") echo "selected" ?>>Bom</option>
                                                                            <option value="regular" <?php if ($nota['avaliacao'] == "regular") echo "selected" ?>>Regular</option>
                                                                            <option value="insatisfatorio" <?php if ($nota['avaliacao'] == "insatisfatorio") echo "selected" ?>>Insatisfatório</option>
                                                                        </select>
                                                                    </td>
                                                                </tr>


                                                    <?php

                                                                $nota = mysqli_fetch_assoc($result);
                                                            endwhile;
                                                            /* Atualiza o $ano */
                                                            $ano = $nota['ano'];
                                                            echo '</table></div>';
                                                            if ($cont == 1) {
                                                                echo '</div>';
                                                                $cont = 0;
                                                            } else
                                                                $cont++;
                                                        endwhile;

                                                        if ($cont < 1)
                                                            echo '</div>';
                                                        else
                                                            echo '';

                                                    else :
                                                        echo '<div style="margin-top: 50px;" class="alert alert-warning text-center col-md-4 col-md-offset-4"> <h2><span class="glyphicon glyphicon-exclamation-sign"></span></h2> Nenhuma nota cadastrada. <br> Clique no botão abaixo para adicionar uma nota ao aluno.</div>';
                                                    endif;
                                                    ?>

                                                    <div class="row" align="center">
                                                        <div class="col-md-12">
                                                            <br><br>
                                                            <form id="cadastraAno" action="" style="width: 6%;" onSubmit="return false;">
                                                                <button id="adicionaAno_externo" type="button" class="btn btn-circle btn-lg btn-primary" data-container="body" data-toggle="popover" data-placement="top" data-html="true" title="<p align='center'>Digite o ano e o bimestre que deseja cadastrar<br>Ano/Bimestre</p> <p align='center'>Exemplo: <b>2017/1</b></p>" data-content='                                
                                        <input autofocus id="inputAno" class="form-control" type="text" placeholder="" name="ano">
                                        <input id="inputID" type="hidden" name="id_usuario" value="<?php echo $r ?>">'>
                                                                    <span class="glyphicon glyphicon-plus"></span>

                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>

                                                    </div> <!-- /row -->


                                                <?php elseif ($p == 'documentos') :
                                                $result = mysqli_query($_SG['link'], "SELECT * FROM documentos_alunos WHERE id_usuario = '$r'");
                                                ?>

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <button title="Clique aqui para adicionar um documento" class="btn btn-primary btn-lg" onclick="$('#enviar_doc').click()"><i class="fa fa-plus-circle"></i>&ensp;Adicionar Documento</button>
                                                            <form data-id="<?php echo $r ?>" id="form_enviar_doc" style="margin-top: 30px;" hidden>
                                                                <input type="file" class="form-control" id="enviar_doc" name="doc">
                                                            </form>
                                                        </div>
                                                    </div>


                                                    <div class="row" style="margin-top: 50px;">
                                                        <?php while ($row = mysqli_fetch_assoc($result)) :
                                                            $extensoes = explode(".", $row['documento']);
                                                            $extensao = $extensoes[count($extensoes) - 1];

                                                            switch ($extensao) {
                                                                case 'pdf':
                                                                    $icon = "<i class='fa fa-file-pdf-o fa-3x text-danger'></i>";
                                                                    break;

                                                                case 'docx':
                                                                    $icon = "<i class='fa fa-file-word-o fa-3x text-primary'></i>";
                                                                    break;

                                                                case 'doc':
                                                                    $icon = "<i class='fa fa-file-word-o fa-3x text-primary'></i>";
                                                                    break;

                                                                case 'txt':
                                                                    $icon = "<i class='fa fa-file-text-o fa-3x text-default'></i>";
                                                                    break;

                                                                case 'xlsx':
                                                                    $icon = "<i class='fa fa-file-excel-o fa-3x text-success'></i>";
                                                                    break;

                                                                case 'pptx':
                                                                    $icon = "<i class='fa fa-file-powerpoint-o fa-3x text-danger'></i>";
                                                                    break;

                                                                default:
                                                                    $icon = "<i class='fa fa-file-o fa-3x'></i>";
                                                                    break;
                                                            }

                                                        ?>

                                                            <div class="col-md-2 docs-container text-center">

                                                                <div onclick="download('<?php echo $root_html ?>sistema/alunos/documentos_alunos/<?php echo $row['documento'] ?>', '<?php echo $row['nome_original'] ?>');
" title="Clique aqui para fazer o download do documento">
                                                                    <?php echo $icon ?>

                                                                    <p>
                                                                        <?php echo $row['nome_original'] ?>
                                                                    </p>
                                                                </div>

                                                                <p data-toggle="modal" data-target="#modalConfirm<?php echo $row['id'] ?>" class="hidden text-danger btn-delete" title="Clique aqui para deletar esse documento"><i class="fa fa-trash"></i>&ensp;Deletar</p>


                                                                <div class="modal fade" id="modalConfirm<?php echo $row['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                                <h4 class="modal-title" id="myModalLabel">Confirmação</h4>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <p>Tem certeza que deseja excluir esse documento? </p>
                                                                                <br>
                                                                                <p class="text-danger text-bold">*Documentos deletados não podem ser recuperados.</p>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                                                                                <button data-id-usuario="<?php echo $row['id_usuario'] ?>" data-documento="<?php echo $row['documento'] ?>" data-id="<?php echo $row['id'] ?>" type="button" class="btn btn-danger deletar_documento">Excluir</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        <?php endwhile; ?>
                                                    </div>


                                                <?php endif; ?>
                                        </div>
                            </div>

                        </div>

                        <?php include '../../footer.php'; ?>

                        <script type="text/javascript">
                            //Função Habilitar e desabilitar Botão
                            function habilitar() {
                                if (document.getElementById('m1').checked) {
                                    document.getElementById('maratona-do-conhecimento-1-nota').removeAttribute("disabled");
                                    document.getElementById('maratona-do-conhecimento-1-dificuldade').removeAttribute("disabled");
                                    document.getElementById('checkM1o').removeAttribute("disabled");
                                    document.getElementById('checkM1p').removeAttribute("disabled");
                                    document.getElementById('checkM1b').removeAttribute("disabled");

                                } else {
                                    document.getElementById('m1').value = '1'; //Se o usuario desabilitar o checkbox, desabilita os campos tbem...
                                    document.getElementById('maratona-do-conhecimento-1-nota').setAttribute("disabled", "disabled");
                                    document.getElementById('maratona-do-conhecimento-1-dificuldade').setAttribute("disabled", "disabled");
                                    document.getElementById('checkM1o').setAttribute("disabled", "disabled");
                                    document.getElementById('checkM1p').setAttribute("disabled", "disabled");
                                    document.getElementById('checkM1b').setAttribute("disabled", "disabled");
                                }

                                if (document.getElementById('checkM1o').checked) {
                                    document.getElementById('m1').setAttribute("disabled", "disabled");
                                    document.getElementById('checkM1p').setAttribute("disabled", "disabled");
                                    document.getElementById('checkM1b').setAttribute("disabled", "disabled");
                                } else if (document.getElementById('checkM1p').checked) {
                                    document.getElementById('m1').setAttribute("disabled", "disabled");
                                    document.getElementById('checkM1o').setAttribute("disabled", "disabled");
                                    document.getElementById('checkM1b').setAttribute("disabled", "disabled");
                                } else if (document.getElementById('checkM1b').checked) {
                                    document.getElementById('m1').setAttribute("disabled", "disabled");
                                    document.getElementById('checkM1o').setAttribute("disabled", "disabled");
                                    document.getElementById('checkM1p').setAttribute("disabled", "disabled");
                                } else {
                                    document.getElementById('m1').removeAttribute("disabled");
                                    document.getElementById('checkM1o').removeAttribute("disabled");
                                    document.getElementById('checkM1p').removeAttribute("disabled");
                                    document.getElementById('checkM1b').removeAttribute("disabled");
                                }


                            }

                            function habilitar1() {
                                if (document.getElementById('m2').checked) {
                                    document.getElementById('maratona-do-conhecimento-2-nota').removeAttribute("disabled");
                                    document.getElementById('maratona-do-conhecimento-2-dificuldade').removeAttribute("disabled");
                                    document.getElementById('checkM2o').removeAttribute("disabled");
                                    document.getElementById('checkM2p').removeAttribute("disabled");
                                    document.getElementById('checkM2b').removeAttribute("disabled");

                                } else {
                                    document.getElementById('m2').value = '1'; //Se o usuario desabilitar o checkbox, desabilita os campos tbem...
                                    document.getElementById('maratona-do-conhecimento-2-nota').setAttribute("disabled", "disabled");
                                    document.getElementById('maratona-do-conhecimento-2-dificuldade').setAttribute("disabled", "disabled");
                                    document.getElementById('checkM2o').setAttribute("disabled", "disabled");
                                    document.getElementById('checkM2p').setAttribute("disabled", "disabled");
                                    document.getElementById('checkM2b').setAttribute("disabled", "disabled");
                                }

                                if (document.getElementById('checkM2o').checked) {
                                    document.getElementById('m2').setAttribute("disabled", "disabled");
                                    document.getElementById('checkM2p').setAttribute("disabled", "disabled");
                                    document.getElementById('checkM2b').setAttribute("disabled", "disabled");
                                } else if (document.getElementById('checkM2p').checked) {
                                    document.getElementById('m2').setAttribute("disabled", "disabled");
                                    document.getElementById('checkM2o').setAttribute("disabled", "disabled");
                                    document.getElementById('checkM2b').setAttribute("disabled", "disabled");
                                } else if (document.getElementById('checkM2b').checked) {
                                    document.getElementById('m2').setAttribute("disabled", "disabled");
                                    document.getElementById('checkM2o').setAttribute("disabled", "disabled");
                                    document.getElementById('checkM2p').setAttribute("disabled", "disabled");
                                } else {
                                    document.getElementById('m2').removeAttribute("disabled");
                                    document.getElementById('checkM2o').removeAttribute("disabled");
                                    document.getElementById('checkM2p').removeAttribute("disabled");
                                    document.getElementById('checkM2b').removeAttribute("disabled");
                                }
                            }

                            function habilitar2() {
                                if (document.getElementById('cl').checked) {
                                    document.getElementById('concurso-literario').removeAttribute("disabled");
                                    document.getElementById('checkCLo').removeAttribute("disabled");
                                    document.getElementById('checkCLp').removeAttribute("disabled");
                                    document.getElementById('checkCLb').removeAttribute("disabled");

                                } else {
                                    document.getElementById('cl').value = '1'; //Se o usuario desabilitar o checkbox, desabilita os campos tbem...
                                    document.getElementById('concurso-literario').setAttribute("disabled", "disabled");
                                    document.getElementById('checkCLo').setAttribute("disabled", "disabled");
                                    document.getElementById('checkCLp').setAttribute("disabled", "disabled");
                                    document.getElementById('checkCLb').setAttribute("disabled", "disabled");
                                }

                                if (document.getElementById('checkCLo').checked) {
                                    document.getElementById('cl').setAttribute("disabled", "disabled");
                                    document.getElementById('checkCLp').setAttribute("disabled", "disabled");
                                    document.getElementById('checkCLb').setAttribute("disabled", "disabled");
                                } else if (document.getElementById('checkCLp').checked) {
                                    document.getElementById('cl').setAttribute("disabled", "disabled");
                                    document.getElementById('checkCLo').setAttribute("disabled", "disabled");
                                    document.getElementById('checkCLb').setAttribute("disabled", "disabled");
                                } else if (document.getElementById('checkCLb').checked) {
                                    document.getElementById('cl').setAttribute("disabled", "disabled");
                                    document.getElementById('checkCLo').setAttribute("disabled", "disabled");
                                    document.getElementById('checkCLp').setAttribute("disabled", "disabled");
                                } else {
                                    document.getElementById('cl').removeAttribute("disabled");
                                    document.getElementById('checkCLo').removeAttribute("disabled");
                                    document.getElementById('checkCLp').removeAttribute("disabled");
                                    document.getElementById('checkCLb').removeAttribute("disabled");
                                }
                            }

                            function habilitar3() {
                                if (document.getElementById('cc').checked) {
                                    document.getElementById('clube-de-ciencia').removeAttribute("disabled");
                                    document.getElementById('checkCCo').removeAttribute("disabled");
                                    document.getElementById('checkCCp').removeAttribute("disabled");
                                    document.getElementById('checkCCb').removeAttribute("disabled");

                                } else {
                                    document.getElementById('cc').value = '1'; //Se o usuario desabilitar o checkbox, desabilita os campos tbem...
                                    document.getElementById('clube-de-ciencia').setAttribute("disabled", "disabled");
                                    document.getElementById('checkCCo').setAttribute("disabled", "disabled");
                                    document.getElementById('checkCCp').setAttribute("disabled", "disabled");
                                    document.getElementById('checkCCb').setAttribute("disabled", "disabled");
                                }

                                if (document.getElementById('checkCCo').checked) {
                                    document.getElementById('cc').setAttribute("disabled", "disabled");
                                    document.getElementById('checkCCp').setAttribute("disabled", "disabled");
                                    document.getElementById('checkCCb').setAttribute("disabled", "disabled");
                                } else if (document.getElementById('checkCCp').checked) {
                                    document.getElementById('cc').setAttribute("disabled", "disabled");
                                    document.getElementById('checkCCo').setAttribute("disabled", "disabled");
                                    document.getElementById('checkCCb').setAttribute("disabled", "disabled");
                                } else if (document.getElementById('checkCCb').checked) {
                                    document.getElementById('cc').setAttribute("disabled", "disabled");
                                    document.getElementById('checkCCo').setAttribute("disabled", "disabled");
                                    document.getElementById('checkCCp').setAttribute("disabled", "disabled");
                                } else {
                                    document.getElementById('cc').removeAttribute("disabled");
                                    document.getElementById('checkCCo').removeAttribute("disabled");
                                    document.getElementById('checkCCp').removeAttribute("disabled");
                                    document.getElementById('checkCCb').removeAttribute("disabled");
                                }
                            }

                            function habilitar4() {
                                if (document.getElementById('cc').checked) {
                                    document.getElementById('clube-de-ciencia').removeAttribute("disabled");
                                    document.getElementById('checkCCo').removeAttribute("disabled");
                                    document.getElementById('checkCCp').removeAttribute("disabled");
                                    document.getElementById('checkCCb').removeAttribute("disabled");

                                } else {
                                    document.getElementById('cc').value = '1'; //Se o usuario desabilitar o checkbox, desabilita os campos tbem...
                                    document.getElementById('clube-de-ciencia').setAttribute("disabled", "disabled");
                                    document.getElementById('checkCCo').setAttribute("disabled", "disabled");
                                    document.getElementById('checkCCp').setAttribute("disabled", "disabled");
                                    document.getElementById('checkCCb').setAttribute("disabled", "disabled");
                                }

                                if (document.getElementById('checkCCo').checked) {
                                    document.getElementById('cc').setAttribute("disabled", "disabled");
                                    document.getElementById('checkCCp').setAttribute("disabled", "disabled");
                                    document.getElementById('checkCCb').setAttribute("disabled", "disabled");
                                } else if (document.getElementById('checkCCp').checked) {
                                    document.getElementById('cc').setAttribute("disabled", "disabled");
                                    document.getElementById('checkCCo').setAttribute("disabled", "disabled");
                                    document.getElementById('checkCCb').setAttribute("disabled", "disabled");
                                } else if (document.getElementById('checkCCb').checked) {
                                    document.getElementById('cc').setAttribute("disabled", "disabled");
                                    document.getElementById('checkCCo').setAttribute("disabled", "disabled");
                                    document.getElementById('checkCCp').setAttribute("disabled", "disabled");
                                } else {
                                    document.getElementById('cc').removeAttribute("disabled");
                                    document.getElementById('checkCCo').removeAttribute("disabled");
                                    document.getElementById('checkCCp').removeAttribute("disabled");
                                    document.getElementById('checkCCb').removeAttribute("disabled");
                                }
                            }
                            //Soma Ouro
                            function checkChoice(whichbox) {
                                with(whichbox.form) {
                                    if (whichbox.checked == false)
                                        hiddentotalouro.value = eval(hiddentotalouro.value) - eval(whichbox.value);
                                    else
                                        hiddentotalouro.value = eval(hiddentotalouro.value) + eval(whichbox.value);
                                    return (formatCurrency(hiddentotalouro.value));
                                }
                            }

                            function formatCurrency(num) {
                                num = num.toString().replace(/\$|\,/g, '');
                                if (isNaN(num)) num = "0";
                                num = Math.floor((num * 100 + 0.5) / 100).toString();
                                for (var i = 0; i < Math.floor((num.length - (1 + i)) / 3); i++)
                                    num = num.substring(0, num.length - (4 * i + 3)) + ',' + num.substring(num.length - (4 * i + 3));
                                return (num);
                            }

                            //Soma prata
                            function checkChoice_1(whichbox) {
                                with(whichbox.form) {
                                    if (whichbox.checked == false)
                                        hiddentotalprata.value = eval(hiddentotalprata.value) - eval(whichbox.value);
                                    else
                                        hiddentotalprata.value = eval(hiddentotalprata.value) + eval(whichbox.value);
                                    return (formatCurrency(hiddentotalprata.value));
                                }
                            }

                            //Soma Bronze
                            function checkChoice_2(whichbox) {
                                with(whichbox.form) {
                                    if (whichbox.checked == false)
                                        hiddentotalbronze.value = eval(hiddentotalbronze.value) - eval(whichbox.value);
                                    else
                                        hiddentotalbronze.value = eval(hiddentotalbronze.value) + eval(whichbox.value);
                                    return (formatCurrency(hiddentotalbronze.value));
                                }
                            }

                            //Participação
                            function checkChoice_3(whichbox) {
                                with(whichbox.form) {
                                    if (whichbox.checked == false)
                                        hiddentotalpart.value = eval(hiddentotalpart.value) - eval(whichbox.value);
                                    else
                                        hiddentotalpart.value = eval(hiddentotalpart.value) + eval(whichbox.value);
                                    return (formatCurrency(hiddentotalpart.value));
                                }
                            }

                            $('.close').click(function(event) {
                                var apagar = confirm('Deseja EXCLUIR?');
                                if (apagar) {
                                    //Faz alguma coisa aqui

                                } else {
                                    event.preventDefault();
                                }
                            });

                            $('.deletar_documento').click(function() {

                                $id = $(this).attr('data-id');

                                data = {
                                    'id': $id,
                                    'documento': $(this).attr('data-documento'),
                                    'id_usuario': $(this).attr('data-id-usuario')
                                }

                                $.ajax({
                                        url: '<?php echo $root_html ?>sistema/alunos/buscar/deletarDocumento.php',
                                        type: 'POST',
                                        data: data,
                                    })
                                    .done(function(data) {
                                        $('.alerta').show().addClass('alert-success');
                                        $('#alerta_conteudo').html(data);
                                        $('.modal').modal('close');
                                        setTimeout(function() {
                                            $('.alerta').fadeOut(500).removeClass('alert-success', 500);
                                        }, 4000);

                                        setTimeout(function() {
                                            window.location.reload();
                                        }, 600);
                                    })
                                    .fail(function() {
                                        console.log("error");
                                    })
                                    .always(function() {
                                        console.log("complete");
                                    });


                            });

                            $('.docs-container').hover(function() {
                                $('.btn-delete', this).removeClass("hidden");
                            }, function() {
                                $('.btn-delete', this).addClass("hidden");
                            });

                            function download(url, nome) {
                                var el = document.createElement("a");
                                el.download = nome; //Define o nome
                                el.href = url; //Define a url
                                el.target = "_blank"; //Força abrir em uma nova janela
                                el.className = "hide-link"; //Adiciona uma classe css pra ocultar

                                document.body.appendChild(el);

                                if (el.fireEvent) {
                                    el.fireEvent("onclick"); //Simula o click pra navegadores com suporte ao fireEvent
                                } else {
                                    //Simula o click
                                    var evObj = document.createEvent("MouseEvents");
                                    evObj.initEvent("click", true, false);
                                    el.dispatchEvent(evObj);
                                }

                                //Remove o link da página
                                setTimeout(function() {
                                    document.body.removeChild(el);
                                }, 100);
                            }

                            $('#enviar_doc').change(function(event) {
                                $('#form_enviar_doc').submit();
                            });

                            $('#form_enviar_doc').submit(function(event) {

                                event.preventDefault();

                                var form = new FormData(this);

                                $id = $(this).attr("data-id");

                                form.append('id_usuario', $id);

                                $.ajax({
                                        url: '<?php echo $root_html ?>sistema/alunos/buscar/enviarDocumento.php',
                                        type: 'POST',
                                        cache: false,
                                        contentType: false,
                                        processData: false,
                                        data: form,
                                        xhr: function() { // Custom XMLHttpRequest
                                            var myXhr = $.ajaxSettings.xhr();
                                            if (myXhr.upload) { // Avalia se tem suporte a propriedade upload
                                                myXhr.upload.addEventListener('progress', function() {
                                                    /* faz alguma coisa durante o progresso do upload */
                                                }, false);
                                            }
                                            return myXhr;
                                        }
                                    })
                                    .done(function(form) {
                                        $('.alerta').show().addClass('alert-success');
                                        $('#alerta_conteudo').html(form);

                                        setTimeout(function() {
                                            $('.alerta').fadeOut(500).removeClass('alert-success', 500);
                                        }, 4000);

                                        setTimeout(function() {
                                            window.location.reload();
                                        }, 1000)
                                    })
                                    .fail(function() {
                                        console.log("error");
                                    })
                                    .always(function() {
                                        console.log("complete");
                                    });
                            });


                            // $('#telefone').mask('(99) 9999-9999');
                            // $('#celular').mask('(99) 99999-9999');
                            // $('#rg').mask('99.999.999-9');
                            // $('#ra').mask('99.999.999-9');

                            $(".avaliacao").change(function() {

                                var $id = $(this).attr("data-id");
                                var $ano = $(this).attr("data-ano");
                                var $avaliacao = $("#avaliacao" + $id + " option:selected").val();

                                var data = {
                                    id: $id,
                                    avaliacao: $avaliacao,
                                    ano: $ano
                                }

                                $.ajax({
                                        url: '<?php echo $root_html ?>sistema/alunos/buscar/adicionarAvaliacao.php',
                                        type: 'POST',
                                        data: data,
                                    })
                                    .done(function(data) {
                                        $('.alerta').hide().show().addClass('alert-success');
                                        $('#alerta_conteudo').html(data);

                                        setTimeout(function() {
                                            $('.alerta').fadeOut(500).removeClass('alert-success', 500);
                                        }, 4000);
                                    })
                                    .fail(function() {
                                        $('.alerta').hide().show().addClass('alert-danger');
                                        $('#alerta_conteudo').html(data);

                                        setTimeout(function() {
                                            $('.alerta').fadeOut(500).removeClass('alert-success', 500);
                                        }, 4000);
                                    })
                                    .always(function() {
                                        console.log("complete");
                                    });


                            });

                            $(function() {
                                $('[data-toggle="tooltip"]').tooltip()
                            });

                            $('.removeAno').click(function() {
                                var id = $(this).attr('data-id');
                                var ano = $(this).attr('data-ano');
                                var data = {
                                    'id': id
                                }
                                if (confirm('Deseja realmente excluir o boletim de ' + ano + '?')) {
                                    $.ajax({
                                        url: '<?php echo $root_html ?>sistema/alunos/buscar/desativaNotas.php',
                                        type: 'POST',
                                        data: data,
                                        complete: function() {
                                            location.reload(200);
                                        },
                                        success: function(data) {
                                            $('.alerta').hide().show().addClass('alert-success');
                                            $('#alerta_conteudo').html(data);

                                            setTimeout(function() {
                                                $('.alerta').fadeOut(500).removeClass('alert-success', 500);
                                            }, 4000);
                                        },
                                        error: function(e) {
                                            $('.alerta').hide().show().addClass('alert-danger');
                                            $('#alerta_conteudo').html("<span class='glyphicon glyphicon-remove'></span>&ensp;Nenhum resultado encontrado");

                                            setTimeout(function() {
                                                $('.alerta').fadeOut(500).removeClass('alert-danger', 500);
                                            }, 4000);
                                        }
                                    });
                                }

                            });

                            $('.removeAno_externo').click(function() {
                                var id = $(this).attr('data-id');
                                var ano = $(this).attr('data-ano');
                                var data = {
                                    'id': id
                                }
                                if (confirm('Deseja realmente excluir o boletim de ' + ano + '?')) {
                                    $.ajax({
                                        url: '<?php echo $root_html ?>sistema/alunos/buscar/desativarNotasExternas.php',
                                        type: 'POST',
                                        data: data,
                                        complete: function() {
                                            location.reload(200);
                                        },
                                        success: function(data) {
                                            $('.alerta').hide().show().addClass('alert-success');
                                            $('#alerta_conteudo').html(data);

                                            setTimeout(function() {
                                                $('.alerta').fadeOut(500).removeClass('alert-success', 500);
                                            }, 4000);
                                        },
                                        error: function(e) {
                                            $('.alerta').hide().show().addClass('alert-danger');
                                            $('#alerta_conteudo').html("<span class='glyphicon glyphicon-remove'></span>&ensp;Nenhum resultado encontrado");

                                            setTimeout(function() {
                                                $('.alerta').fadeOut(500).removeClass('alert-danger', 500);
                                            }, 4000);
                                        }
                                    });
                                }

                            });

                            $('#adicionaAno').click(function() {
                                $('#adicionaAno').popover('toggle');
                                $('input.form-control').keypress(function(event) {
                                    if (event.which == 13) {
                                        var id_usuario = $('#inputID').val()
                                        var ano = $('#inputAno').val();
                                        var data = {
                                            'id_usuario': id_usuario,
                                            'ano': ano
                                        }
                                        $.ajax({
                                            url: '<?php echo $root_html ?>sistema/alunos/buscar/cadastrarNotas.php',
                                            type: 'POST',
                                            data: data,
                                            complete: function() {
                                                location.reload();
                                            },
                                            success: function(data) {
                                                $('.alerta').hide().show().addClass('alert-success');
                                                $('#alerta_conteudo').html(data);

                                                setTimeout(function() {
                                                    $('.alerta').fadeOut(500).removeClass('alert-success', 500);
                                                }, 4000);
                                            },
                                            error: function(e) {
                                                $('.alerta').hide().show().addClass('alert-danger');
                                                $('#alerta_conteudo').html("<span class='glyphicon glyphicon-remove'></span>&ensp;Nenhum resultado encontrado");

                                                setTimeout(function() {
                                                    $('.alerta').fadeOut(500).removeClass('alert-danger', 500);
                                                }, 4000);
                                            }
                                        });

                                    }
                                });;
                            });

                            $('#adicionaAno_externo').click(function() {
                                $('#adicionaAno_externo').popover('toggle');
                                $('input.form-control').keypress(function(event) {
                                    if (event.which == 13) {
                                        var id_usuario = $('#inputID').val()
                                        var ano = $('#inputAno').val();
                                        var data = {
                                            'id_usuario': id_usuario,
                                            'ano': ano
                                        }
                                        $.ajax({
                                            url: '<?php echo $root_html ?>sistema/alunos/buscar/cadastrarNotasExternas.php',
                                            type: 'POST',
                                            data: data,
                                            complete: function() {
                                                location.reload();
                                            },
                                            success: function(data) {
                                                $('.alerta').hide().show().addClass('alert-success');
                                                $('#alerta_conteudo').html(data);

                                                setTimeout(function() {
                                                    $('.alerta').fadeOut(500).removeClass('alert-success', 500);
                                                }, 4000);
                                            },
                                            error: function(e) {
                                                $('.alerta').hide().show().addClass('alert-danger');
                                                $('#alerta_conteudo').html("<span class='glyphicon glyphicon-remove'></span>&ensp;Nenhum resultado encontrado");

                                                setTimeout(function() {
                                                    $('.alerta').fadeOut(500).removeClass('alert-danger', 500);
                                                }, 4000);
                                            }
                                        });

                                    }
                                });;
                            });

                            $('.linhaNota').click(function() {

                                var id = $(this).attr('data-id');
                                var tipo = $(this).attr('data-tipo');

                                switch (tipo) {

                                    case 'maratona1-nota':
                                        $('#viewMaratona1-nota' + id).hide();
                                        $('#editMaratona1-nota' + id).show();
                                        $("#editMaratona1-nota" + id + " > input").focus().select();
                                        break;

                                    case 'maratona1-dificuldade':
                                        $('#viewMaratona1-dificuldade' + id).hide();
                                        $('#editMaratona1-dificuldade' + id).show();
                                        $("#editMaratona1-dificuldade" + id + " > select").focus().select();
                                        break;

                                    case 'maratona2-nota':
                                        $('#viewMaratona2-nota' + id).hide();
                                        $('#editMaratona2-nota' + id).show();
                                        $("#editMaratona2-nota" + id + " > input").focus().select();
                                        break;

                                    case 'maratona2-dificuldade':
                                        $('#viewMaratona2-dificuldade' + id).hide();
                                        $('#editMaratona2-dificuldade' + id).show();
                                        $("#editMaratona2-dificuldade" + id + " > select").focus().select();
                                        break;

                                    case 'concurso-literario':
                                        $('#viewLiterario' + id).hide();
                                        $('#editLiterario' + id).show();
                                        $("#editLiterario" + id + " > input").focus().select();
                                        break;

                                    case 'clube_de_ciencia':
                                        $('#viewClube' + id).hide();
                                        $('#editClube' + id).show();
                                        $("#editClube" + id + " > input").focus().select();
                                        break;

                                    case 'hiddentotalpart':
                                        $('#viewtotalpart' + id).hide();
                                        $('#edittotalpart' + id).show();
                                        $("#edittotalpart" + id + " > input").focus().select();
                                        break;

                                    case 'hiddentotalouro':
                                        $('#viewtotalouro' + id).hide();
                                        $('#edittotalouro' + id).show();
                                        $("#edittotalouro" + id + " > input").focus().select();
                                        break;

                                    case 'hiddentotalprata':
                                        $('#viewtotalprata' + id).hide();
                                        $('#edittotalprata' + id).show();
                                        $("#edittotalprata" + id + " > input").focus().select();
                                        break;

                                    case 'hiddentotalbronze':
                                        $('#viewtotalbronze' + id).hide();
                                        $('#edittotalbronze' + id).show();
                                        $("#edittotalbronze" + id + " > input").focus().select();
                                        break;

                                    case 'matematica':
                                        $('#viewMatematica' + id).hide();
                                        $('#editMatematica' + id).show();
                                        $("#editMatematica" + id + " > input").focus().select();
                                        break;

                                    case 'historia':
                                        $('#viewHistoria' + id).hide();
                                        $('#editHistoria' + id).show();
                                        $("#editHistoria" + id + " > input").focus().select();
                                        break;

                                    case 'ciencias':
                                        $('#viewCiencias' + id).hide();
                                        $('#editCiencias' + id).show();
                                        $("#editCiencias" + id + " > input").focus().select();
                                        break;

                                    case 'portugues':
                                        $('#viewPortugues' + id).hide();
                                        $('#editPortugues' + id).show();
                                        $("#editPortugues" + id + " > input").focus().select();
                                        break;

                                    case 'geografia':
                                        $('#viewGeografia' + id).hide();
                                        $('#editGeografia' + id).show();
                                        $("#editGeografia" + id + " > input").focus().select();
                                        break;

                                    case 'ingles':
                                        $('#viewIngles' + id).hide();
                                        $('#editIngles' + id).show();
                                        $("#editIngles" + id + " > input").focus().select();
                                        break;

                                    case 'fisica':
                                        $('#viewFisica' + id).hide();
                                        $('#editFisica' + id).show();
                                        $("#editFisica" + id + " > input").focus().select();
                                        break;

                                    case 'quimica':
                                        $('#viewQuimica' + id).hide();
                                        $('#editQuimica' + id).show();
                                        $("#editQuimica" + id + " > input").focus().select();
                                        break;

                                    case 'sociologia':
                                        $('#viewSociologia' + id).hide();
                                        $('#editSociologia' + id).show();
                                        $("#editSociologia" + id + " > input").focus().select();
                                        break;

                                    case 'filosofia':
                                        $('#viewFilosofia' + id).hide();
                                        $('#editFilosofia' + id).show();
                                        $("#editFilosofia" + id + " > input").focus().select();
                                        break;

                                    case 'media':
                                        $('#viewMedia' + id).hide();
                                        $('#editMedia' + id).show();
                                        $("#editMedia" + id + " > input").focus().select();
                                        break;
                                }

                                $("#editMaratona1-nota" + id).keypress(function(event) {
                                    if (event.which == 13) {
                                        event.preventDefault();

                                        $("#editMaratona1-nota" + id).hide();
                                        $("#editMaratona1-dificuldade" + id).hide();
                                        $("#editMaratona2-nota" + id).hide();
                                        $("#editMaratona2-dificuldade" + id).hide();
                                        $("#editLiterario" + id).hide();
                                        $("#editClube" + id).hide();

                                        $("#viewMaratona1-nota" + id).show();

                                        var data = $('#formsEditarMaratona1-nota' + id).serialize();
                                        $.ajax({
                                            url: '<?php echo $root_html ?>sistema/alunos/buscar/editarNotas.php',
                                            type: 'POST',
                                            data: data,
                                            complete: function() {
                                                var $val = $("#editMaratona1-nota" + id + " input").val().replace(",", ".");

                                                $("#viewMaratona1-nota" + id).html($val);
                                            },
                                            success: function(data) {
                                                $('.alerta').hide().show().addClass('alert-success');
                                                $('#alerta_conteudo').html(data);

                                                setTimeout(function() {
                                                    $('.alerta').fadeOut(500).removeClass('alert-success', 500);
                                                }, 4000);
                                            },
                                            error: function(e) {
                                                $('.alerta').hide().show().addClass('alert-danger');
                                                $('#alerta_conteudo').html("<span class='glyphicon glyphicon-remove'></span>&ensp;Nenhum resultado encontrado");

                                                setTimeout(function() {
                                                    $('.alerta').fadeOut(500).removeClass('alert-danger', 500);
                                                }, 4000);
                                            }
                                        });
                                    }
                                });

                                $("#editMaratona1-dificuldade" + id).keypress(function(event) {
                                    if (event.which == 13) {
                                        event.preventDefault();

                                        // Esconde todos os input
                                        $("#editMaratona1-nota" + id).hide();
                                        $("#editMaratona1-dificuldade" + id).hide();
                                        $("#editMaratona2-nota" + id).hide();
                                        $("#editMaratona2-dificuldade" + id).hide();
                                        $("#editLiterario" + id).hide();
                                        $("#editClube" + id).hide();

                                        $("#viewMaratona1-dificuldade" + id).show();

                                        var data = $('#formsEditarMaratona1-dificuldade' + id).serialize();
                                        $.ajax({
                                            url: '<?php echo $root_html ?>sistema/alunos/buscar/editarNotas.php',
                                            type: 'POST',
                                            data: data,
                                            complete: function() {

                                                // Pega o valor salvo no select
                                                var $val = $("#editMaratona1-dificuldade" + id + " select option:checked").val().replace(",", ".");

                                                // Atualiza o valor da view
                                                $("#viewMaratona1-dificuldade" + id).html($val);
                                            },
                                            success: function(data) {
                                                $('.alerta').hide().show().addClass('alert-success');
                                                $('#alerta_conteudo').html(data);

                                                setTimeout(function() {
                                                    $('.alerta').fadeOut(500).removeClass('alert-success', 500);
                                                }, 4000);
                                            },
                                            error: function(e) {
                                                $('.alerta').hide().show().addClass('alert-danger');
                                                $('#alerta_conteudo').html("<span class='glyphicon glyphicon-remove'></span>&ensp;Nenhum resultado encontrado");

                                                setTimeout(function() {
                                                    $('.alerta').fadeOut(500).removeClass('alert-danger', 500);
                                                }, 4000);
                                            }
                                        });
                                    }
                                });

                                //Total Participacao
                                $("#edittotalpart" + id).keypress(function(event) {
                                    if (event.which == 13) {
                                        event.preventDefault();

                                        $("#editMaratona1-nota" + id).hide();
                                        $("#editMaratona1-dificuldade" + id).hide();
                                        $("#editMaratona2-nota" + id).hide();
                                        $("#edittotalpart" + id).hide();
                                        $("#editMaratona2-dificuldade" + id).hide();
                                        $("#editLiterario" + id).hide();
                                        $("#editClube" + id).hide();

                                        $("#viewtotalpart" + id).show();

                                        var data = $('#formsEditarpart' + id).serialize();
                                        $.ajax({
                                            url: '<?php echo $root_html ?>sistema/alunos/buscar/editarNotas.php',
                                            type: 'POST',
                                            data: data,
                                            complete: function() {
                                                var $val = $("#edittotalpart" + id + " input").val().replace(",", ".");

                                                $("#viewtotalpart" + id).html($val);
                                            },
                                            success: function(data) {
                                                $('.alerta').hide().show().addClass('alert-success');
                                                $('#alerta_conteudo').html(data);

                                                setTimeout(function() {
                                                    $('.alerta').fadeOut(500).removeClass('alert-success', 500);
                                                }, 4000);
                                            },
                                            error: function(e) {
                                                $('.alerta').hide().show().addClass('alert-danger');
                                                $('#alerta_conteudo').html("<span class='glyphicon glyphicon-remove'></span>&ensp;Nenhum resultado encontrado");

                                                setTimeout(function() {
                                                    $('.alerta').fadeOut(500).removeClass('alert-danger', 500);
                                                }, 4000);
                                            }
                                        });
                                    }
                                });

                                //Total Ouro
                                $("#edittotalouro" + id).keypress(function(event) {
                                    if (event.which == 13) {
                                        event.preventDefault();

                                        $("#editMaratona1-nota" + id).hide();
                                        $("#editMaratona1-dificuldade" + id).hide();
                                        $("#editMaratona2-nota" + id).hide();
                                        $("#edittotalouro" + id).hide();
                                        $("#editMaratona2-dificuldade" + id).hide();
                                        $("#editLiterario" + id).hide();
                                        $("#editClube" + id).hide();

                                        $("#viewtotalouro" + id).show();

                                        var data = $('#formsEditarouro' + id).serialize();
                                        $.ajax({
                                            url: '<?php echo $root_html ?>sistema/alunos/buscar/editarNotas.php',
                                            type: 'POST',
                                            data: data,
                                            complete: function() {
                                                var $val = $("#edittotalouro" + id + " input").val().replace(",", ".");

                                                $("#viewtotalouro" + id).html($val);
                                            },
                                            success: function(data) {
                                                $('.alerta').hide().show().addClass('alert-success');
                                                $('#alerta_conteudo').html(data);

                                                setTimeout(function() {
                                                    $('.alerta').fadeOut(500).removeClass('alert-success', 500);
                                                }, 4000);
                                            },
                                            error: function(e) {
                                                $('.alerta').hide().show().addClass('alert-danger');
                                                $('#alerta_conteudo').html("<span class='glyphicon glyphicon-remove'></span>&ensp;Nenhum resultado encontrado");

                                                setTimeout(function() {
                                                    $('.alerta').fadeOut(500).removeClass('alert-danger', 500);
                                                }, 4000);
                                            }
                                        });
                                    }
                                });

                                //Total prata
                                $("#edittotalprata" + id).keypress(function(event) {
                                    if (event.which == 13) {
                                        event.preventDefault();

                                        $("#editMaratona1-nota" + id).hide();
                                        $("#editMaratona1-dificuldade" + id).hide();
                                        $("#editMaratona2-nota" + id).hide();
                                        $("#edittotalprata" + id).hide();
                                        $("#editMaratona2-dificuldade" + id).hide();
                                        $("#editLiterario" + id).hide();
                                        $("#editClube" + id).hide();

                                        $("#viewtotalprata" + id).show();

                                        var data = $('#formsEditarprata' + id).serialize();
                                        $.ajax({
                                            url: '<?php echo $root_html ?>sistema/alunos/buscar/editarNotas.php',
                                            type: 'POST',
                                            data: data,
                                            complete: function() {
                                                var $val = $("#edittotalprata" + id + " input").val().replace(",", ".");

                                                $("#viewtotalprata" + id).html($val);
                                            },
                                            success: function(data) {
                                                $('.alerta').hide().show().addClass('alert-success');
                                                $('#alerta_conteudo').html(data);

                                                setTimeout(function() {
                                                    $('.alerta').fadeOut(500).removeClass('alert-success', 500);
                                                }, 4000);
                                            },
                                            error: function(e) {
                                                $('.alerta').hide().show().addClass('alert-danger');
                                                $('#alerta_conteudo').html("<span class='glyphicon glyphicon-remove'></span>&ensp;Nenhum resultado encontrado");

                                                setTimeout(function() {
                                                    $('.alerta').fadeOut(500).removeClass('alert-danger', 500);
                                                }, 4000);
                                            }
                                        });
                                    }
                                });

                                //Total bronze
                                $("#edittotalbronze" + id).keypress(function(event) {
                                    if (event.which == 13) {
                                        event.preventDefault();

                                        $("#editMaratona1-nota" + id).hide();
                                        $("#editMaratona1-dificuldade" + id).hide();
                                        $("#editMaratona2-nota" + id).hide();
                                        $("#edittotalbronze" + id).hide();
                                        $("#editMaratona2-dificuldade" + id).hide();
                                        $("#editLiterario" + id).hide();
                                        $("#editClube" + id).hide();

                                        $("#viewtotalbronze" + id).show();

                                        var data = $('#formsEditarbronze' + id).serialize();
                                        $.ajax({
                                            url: '<?php echo $root_html ?>sistema/alunos/buscar/editarNotas.php',
                                            type: 'POST',
                                            data: data,
                                            complete: function() {
                                                var $val = $("#edittotalbronze" + id + " input").val().replace(",", ".");

                                                $("#viewtotalbronze" + id).html($val);
                                            },
                                            success: function(data) {
                                                $('.alerta').hide().show().addClass('alert-success');
                                                $('#alerta_conteudo').html(data);

                                                setTimeout(function() {
                                                    $('.alerta').fadeOut(500).removeClass('alert-success', 500);
                                                }, 4000);
                                            },
                                            error: function(e) {
                                                $('.alerta').hide().show().addClass('alert-danger');
                                                $('#alerta_conteudo').html("<span class='glyphicon glyphicon-remove'></span>&ensp;Nenhum resultado encontrado");

                                                setTimeout(function() {
                                                    $('.alerta').fadeOut(500).removeClass('alert-danger', 500);
                                                }, 4000);
                                            }
                                        });
                                    }
                                });

                                $("#editMaratona2-nota" + id).keypress(function(event) {
                                    if (event.which == 13) {
                                        event.preventDefault();

                                        $("#editMaratona1-nota" + id).hide();
                                        $("#editMaratona1-dificuldade" + id).hide();
                                        $("#editMaratona2-nota" + id).hide();
                                        $("#editMaratona2-dificuldade" + id).hide();
                                        $("#editLiterario" + id).hide();
                                        $("#editClube" + id).hide();

                                        $("#viewMaratona2-nota" + id).show();

                                        var data = $('#formsEditarMaratona2-nota' + id).serialize();
                                        $.ajax({
                                            url: '<?php echo $root_html ?>sistema/alunos/buscar/editarNotas.php',
                                            type: 'POST',
                                            data: data,
                                            complete: function() {
                                                var $val = $("#editMaratona2-nota" + id + " input").val().replace(",", ".");

                                                $("#viewMaratona2-nota" + id).html($val);
                                            },
                                            success: function(data) {
                                                $('.alerta').hide().show().addClass('alert-success');
                                                $('#alerta_conteudo').html(data);

                                                setTimeout(function() {
                                                    $('.alerta').fadeOut(500).removeClass('alert-success', 500);
                                                }, 4000);
                                            },
                                            error: function(e) {
                                                $('.alerta').hide().show().addClass('alert-danger');
                                                $('#alerta_conteudo').html("<span class='glyphicon glyphicon-remove'></span>&ensp;Nenhum resultado encontrado");

                                                setTimeout(function() {
                                                    $('.alerta').fadeOut(500).removeClass('alert-danger', 500);
                                                }, 4000);
                                            }
                                        });
                                    }
                                });

                                $("#editMaratona2-dificuldade" + id).keypress(function(event) {
                                    if (event.which == 13) {
                                        event.preventDefault();

                                        // Esconde todos os input
                                        $("#editMaratona1-nota" + id).hide();
                                        $("#editMaratona1-dificuldade" + id).hide();
                                        $("#editMaratona2-nota" + id).hide();
                                        $("#editMaratona2-dificuldade" + id).hide();
                                        $("#editLiterario" + id).hide();
                                        $("#editClube" + id).hide();

                                        $("#viewMaratona2-dificuldade" + id).show();

                                        var data = $('#formsEditarMaratona2-dificuldade' + id).serialize();
                                        $.ajax({
                                            url: '<?php echo $root_html ?>sistema/alunos/buscar/editarNotas.php',
                                            type: 'POST',
                                            data: data,
                                            complete: function() {

                                                // Pega o valor salvo no select
                                                var $val = $("#editMaratona2-dificuldade" + id + " select option:checked").val().replace(",", ".");

                                                // Atualiza o valor da view
                                                $("#viewMaratona2-dificuldade" + id).html($val);
                                            },
                                            success: function(data) {
                                                $('.alerta').hide().show().addClass('alert-success');
                                                $('#alerta_conteudo').html(data);

                                                setTimeout(function() {
                                                    $('.alerta').fadeOut(500).removeClass('alert-success', 500);
                                                }, 4000);
                                            },
                                            error: function(e) {
                                                $('.alerta').hide().show().addClass('alert-danger');
                                                $('#alerta_conteudo').html("<span class='glyphicon glyphicon-remove'></span>&ensp;Nenhum resultado encontrado");

                                                setTimeout(function() {
                                                    $('.alerta').fadeOut(500).removeClass('alert-danger', 500);
                                                }, 4000);
                                            }
                                        });
                                    }
                                });


                                $("#editLiterario" + id).keypress(function(event) {
                                    if (event.which == 13) {
                                        event.preventDefault();


                                        $("#editMaratona1" + id).hide();
                                        $("#editMaratona2" + id).hide();
                                        $("#editLiterario" + id).hide();
                                        $("#editClube" + id).hide();
                                        $("#viewLiterario" + id).show();
                                        var data = $('#formsEditarLiterario' + id).serialize();
                                        $.ajax({
                                            url: '<?php echo $root_html ?>sistema/alunos/buscar/editarNotas.php',
                                            type: 'POST',
                                            data: data,
                                            complete: function() {
                                                var $val = $("#editLiterario" + id + " input").val().replace(",", ".");
                                                $("#viewLiterario" + id).html($val);

                                            },
                                            success: function(data) {
                                                $('.alerta').hide().show().addClass('alert-success');
                                                $('#alerta_conteudo').html(data);

                                                setTimeout(function() {
                                                    $('.alerta').fadeOut(500).removeClass('alert-success', 500);
                                                }, 4000);
                                            },
                                            error: function(e) {
                                                $('.alerta').hide().show().addClass('alert-danger');
                                                $('#alerta_conteudo').html("<span class='glyphicon glyphicon-remove'></span>&ensp;Nenhum resultado encontrado");

                                                setTimeout(function() {
                                                    $('.alerta').fadeOut(500).removeClass('alert-danger', 500);
                                                }, 4000);
                                            }
                                        });
                                    }
                                });
                                $("#editClube" + id).keypress(function(event) {
                                    if (event.which == 13) {
                                        event.preventDefault();

                                        $("#editMaratona1" + id).hide();
                                        $("#editMaratona2" + id).hide();
                                        $("#editLiterario" + id).hide();
                                        $("#editClube" + id).hide();
                                        $("#viewClube" + id).show();
                                        var data = $('#formsEditarClube' + id).serialize();
                                        $.ajax({
                                            url: '<?php echo $root_html ?>sistema/alunos/buscar/editarNotas.php',
                                            type: 'POST',
                                            data: data,
                                            complete: function() {
                                                var $val = $("#editClube" + id + " input").val().replace(",", ".");
                                                $("#viewClube" + id).html($val);
                                            },
                                            success: function(data) {
                                                $('.alerta').hide().show().addClass('alert-success');
                                                $('#alerta_conteudo').html(data);

                                                setTimeout(function() {
                                                    $('.alerta').fadeOut(500).removeClass('alert-success', 500);
                                                }, 4000);
                                            },
                                            error: function(e) {
                                                $('.alerta').hide().show().addClass('alert-danger');
                                                $('#alerta_conteudo').html("<span class='glyphicon glyphicon-remove'></span>&ensp;Nenhum resultado encontrado");

                                                setTimeout(function() {
                                                    $('.alerta').fadeOut(500).removeClass('alert-danger', 500);
                                                }, 4000);
                                            }
                                        });
                                    }
                                });

                                $("#editMatematica" + id).keypress(function(event) {
                                    if (event.which == 13) {
                                        event.preventDefault();
                                        $("#editGeografia" + id).hide();
                                        $("#editHistoria" + id).hide();
                                        $("#editPortugues" + id).hide();
                                        $("#editCiencias" + id).hide();
                                        $("#editMatematica" + id).hide();
                                        $("#editIngles" + id).hide();
                                        $("#editQuimica" + id).hide();
                                        $("#editFisica" + id).hide();
                                        $("#editSociologia" + id).hide();
                                        $("#editFilosofia" + id).hide();
                                        $("#editMedia" + id).hide();
                                        $('#viewMatematica').show();

                                        var data = $('#formsEditarMatematica' + id).serialize();

                                        $.ajax({
                                            url: '<?php echo $root_html ?>sistema/alunos/buscar/editarNotasExternas.php',
                                            type: 'POST',
                                            data: data,
                                            complete: function() {
                                                var $val = $("#editMatematica" + id + " input").val().replace(",", ".");
                                                $("#viewMatematica" + id).html($val);
                                                // $("#viewMatematica"+id).show();                                
                                            },
                                            success: function(data) {
                                                $('.alerta').hide().show().addClass('alert-success');
                                                $('#alerta_conteudo').html(data);

                                                setTimeout(function() {
                                                    $('.alerta').fadeOut(500).removeClass('alert-success', 500);
                                                }, 4000);
                                            },
                                            error: function(e) {
                                                $('.alerta').hide().show().addClass('alert-danger');
                                                $('#alerta_conteudo').html("<span class='glyphicon glyphicon-remove'></span>&ensp;Nenhum resultado encontrado");

                                                setTimeout(function() {
                                                    $('.alerta').fadeOut(500).removeClass('alert-danger', 500);
                                                }, 4000);
                                            }
                                        });
                                    }
                                });
                                $("#editGeografia" + id).keypress(function(event) {
                                    if (event.which == 13) {
                                        event.preventDefault();

                                        $("#editGeografia" + id).hide();
                                        $("#editHistoria" + id).hide();
                                        $("#editPortugues" + id).hide();
                                        $("#editCiencias" + id).hide();
                                        $("#editMatematica" + id).hide();
                                        $("#editIngles" + id).hide();
                                        $("#editQuimica" + id).hide();
                                        $("#editFisica" + id).hide();
                                        $("#editSociologia" + id).hide();
                                        $("#editFilosofia" + id).hide();
                                        $("#editMedia" + id).hide();
                                        $("#viewGeografia" + id).show();

                                        var data = $('#formsEditarGeografia' + id).serialize();

                                        $.ajax({
                                            url: '<?php echo $root_html ?>sistema/alunos/buscar/editarNotasExternas.php',
                                            type: 'POST',
                                            data: data,
                                            complete: function() {
                                                var $val = $("#editGeografia" + id + " input").val().replace(",", ".");
                                                $("#viewGeografia" + id).html($val);
                                            },
                                            success: function(data) {
                                                $('.alerta').hide().show().addClass('alert-success');
                                                $('#alerta_conteudo').html(data);

                                                setTimeout(function() {
                                                    $('.alerta').fadeOut(500).removeClass('alert-success', 500);
                                                }, 4000);
                                            },
                                            error: function(e) {
                                                $('.alerta').hide().show().addClass('alert-danger');
                                                $('#alerta_conteudo').html("<span class='glyphicon glyphicon-remove'></span>&ensp;Nenhum resultado encontrado");

                                                setTimeout(function() {
                                                    $('.alerta').fadeOut(500).removeClass('alert-danger', 500);
                                                }, 4000);
                                            }
                                        });
                                    }
                                });
                                $("#editPortugues" + id).keypress(function(event) {
                                    if (event.which == 13) {
                                        event.preventDefault();

                                        $("#editGeografia" + id).hide();
                                        $("#editHistoria" + id).hide();
                                        $("#editPortugues" + id).hide();
                                        $("#editCiencias" + id).hide();
                                        $("#editMatematica" + id).hide();
                                        $("#editIngles" + id).hide();
                                        $("#editQuimica" + id).hide();
                                        $("#editFisica" + id).hide();
                                        $("#editSociologia" + id).hide();
                                        $("#editFilosofia" + id).hide();
                                        $("#editMedia" + id).hide();
                                        $("#viewPortugues" + id).show();

                                        var data = $('#formsEditarPortugues' + id).serialize();

                                        $.ajax({
                                            url: '<?php echo $root_html ?>sistema/alunos/buscar/editarNotasExternas.php',
                                            type: 'POST',
                                            data: data,
                                            complete: function() {
                                                var $val = $("#editPortugues" + id + " input").val().replace(",", ".");
                                                $("#viewPortugues" + id).html($val);
                                            },
                                            success: function(data) {
                                                $('.alerta').hide().show().addClass('alert-success');
                                                $('#alerta_conteudo').html(data);

                                                setTimeout(function() {
                                                    $('.alerta').fadeOut(500).removeClass('alert-success', 500);
                                                }, 4000);
                                            },
                                            error: function(e) {
                                                $('.alerta').hide().show().addClass('alert-danger');
                                                $('#alerta_conteudo').html("<span class='glyphicon glyphicon-remove'></span>&ensp;Nenhum resultado encontrado");

                                                setTimeout(function() {
                                                    $('.alerta').fadeOut(500).removeClass('alert-danger', 500);
                                                }, 4000);
                                            }
                                        });
                                    }
                                });
                                $("#editCiencias" + id).keypress(function(event) {
                                    if (event.which == 13) {
                                        event.preventDefault();

                                        $("#editGeografia" + id).hide();
                                        $("#editHistoria" + id).hide();
                                        $("#editPortugues" + id).hide();
                                        $("#editCiencias" + id).hide();
                                        $("#editMatematica" + id).hide();
                                        $("#editIngles" + id).hide();
                                        $("#editQuimica" + id).hide();
                                        $("#editFisica" + id).hide();
                                        $("#editSociologia" + id).hide();
                                        $("#editFilosofia" + id).hide();
                                        $("#editMedia" + id).hide();
                                        $("#viewCiencias" + id).show();

                                        var data = $('#formsEditarCiencias' + id).serialize();

                                        $.ajax({
                                            url: '<?php echo $root_html ?>sistema/alunos/buscar/editarNotasExternas.php',
                                            type: 'POST',
                                            data: data,
                                            complete: function() {
                                                var $val = $("#editCiencias" + id + " input").val().replace(",", ".");
                                                $("#viewCiencias" + id).html($val);
                                            },
                                            success: function(data) {
                                                $('.alerta').hide().show().addClass('alert-success');
                                                $('#alerta_conteudo').html(data);

                                                setTimeout(function() {
                                                    $('.alerta').fadeOut(500).removeClass('alert-success', 500);
                                                }, 4000);
                                            },
                                            error: function(e) {
                                                $('.alerta').hide().show().addClass('alert-danger');
                                                $('#alerta_conteudo').html("<span class='glyphicon glyphicon-remove'></span>&ensp;Nenhum resultado encontrado");

                                                setTimeout(function() {
                                                    $('.alerta').fadeOut(500).removeClass('alert-danger', 500);
                                                }, 4000);
                                            }
                                        });
                                    }
                                });
                                $("#editHistoria" + id).keypress(function(event) {
                                    if (event.which == 13) {
                                        event.preventDefault();

                                        $("#editGeografia" + id).hide();
                                        $("#editHistoria" + id).hide();
                                        $("#editPortugues" + id).hide();
                                        $("#editCiencias" + id).hide();
                                        $("#editMatematica" + id).hide();
                                        $("#editIngles" + id).hide();
                                        $("#editQuimica" + id).hide();
                                        $("#editFisica" + id).hide();
                                        $("#editSociologia" + id).hide();
                                        $("#editFilosofia" + id).hide();
                                        $("#editMedia" + id).hide();
                                        $("#viewHistoria" + id).show();

                                        var data = $('#formsEditarHistoria' + id).serialize();

                                        $.ajax({
                                            url: '<?php echo $root_html ?>sistema/alunos/buscar/editarNotasExternas.php',
                                            type: 'POST',
                                            data: data,
                                            complete: function() {
                                                var $val = $("#editHistoria" + id + " input").val().replace(",", ".");
                                                $("#viewHistoria" + id).html($val);
                                            },
                                            success: function(data) {
                                                $('.alerta').hide().show().addClass('alert-success');
                                                $('#alerta_conteudo').html(data);

                                                setTimeout(function() {
                                                    $('.alerta').fadeOut(500).removeClass('alert-success', 500);
                                                }, 4000);
                                            },
                                            error: function(e) {
                                                $('.alerta').hide().show().addClass('alert-danger');
                                                $('#alerta_conteudo').html("<span class='glyphicon glyphicon-remove'></span>&ensp;Nenhum resultado encontrado");

                                                setTimeout(function() {
                                                    $('.alerta').fadeOut(500).removeClass('alert-danger', 500);
                                                }, 4000);
                                            }
                                        });
                                    }
                                });
                                $("#editIngles" + id).keypress(function(event) {
                                    if (event.which == 13) {
                                        event.preventDefault();

                                        $("#editGeografia" + id).hide();
                                        $("#editHistoria" + id).hide();
                                        $("#editPortugues" + id).hide();
                                        $("#editCiencias" + id).hide();
                                        $("#editMatematica" + id).hide();
                                        $("#editIngles" + id).hide();
                                        $("#editQuimica" + id).hide();
                                        $("#editFisica" + id).hide();
                                        $("#editSociologia" + id).hide();
                                        $("#editFilosofia" + id).hide();
                                        $("#editMedia" + id).hide();
                                        $("#viewIngles" + id).show();

                                        var data = $('#formsEditarIngles' + id).serialize();

                                        $.ajax({
                                            url: '<?php echo $root_html ?>sistema/alunos/buscar/editarNotasExternas.php',
                                            type: 'POST',
                                            data: data,
                                            complete: function() {
                                                var $val = $("#editIngles" + id + " input").val().replace(",", ".");
                                                $("#viewIngles" + id).html($val);
                                            },
                                            success: function(data) {
                                                $('.alerta').hide().show().addClass('alert-success');
                                                $('#alerta_conteudo').html(data);

                                                setTimeout(function() {
                                                    $('.alerta').fadeOut(500).removeClass('alert-success', 500);
                                                }, 4000);
                                            },
                                            error: function(e) {
                                                $('.alerta').hide().show().addClass('alert-danger');
                                                $('#alerta_conteudo').html("<span class='glyphicon glyphicon-remove'></span>&ensp;Nenhum resultado encontrado");

                                                setTimeout(function() {
                                                    $('.alerta').fadeOut(500).removeClass('alert-danger', 500);
                                                }, 4000);
                                            }
                                        });
                                    }
                                });

                                $("#editQuimica" + id).keypress(function(event) {
                                    if (event.which == 13) {
                                        event.preventDefault();

                                        $("#editGeografia" + id).hide();
                                        $("#editHistoria" + id).hide();
                                        $("#editPortugues" + id).hide();
                                        $("#editCiencias" + id).hide();
                                        $("#editMatematica" + id).hide();
                                        $("#editIngles" + id).hide();
                                        $("#editQuimica" + id).hide();
                                        $("#editFisica" + id).hide();
                                        $("#editSociologia" + id).hide();
                                        $("#editFilosofia" + id).hide();
                                        $("#editMedia" + id).hide();
                                        $("#viewQuimica" + id).show();

                                        var data = $('#formsEditarQuimica' + id).serialize();

                                        $.ajax({
                                            url: '<?php echo $root_html ?>sistema/alunos/buscar/editarNotasExternas.php',
                                            type: 'POST',
                                            data: data,
                                            complete: function() {
                                                var $val = $("#editQuimica" + id + " input").val().replace(",", ".");
                                                $("#viewQuimica" + id).html($val);
                                            },
                                            success: function(data) {
                                                $('.alerta').hide().show().addClass('alert-success');
                                                $('#alerta_conteudo').html(data);

                                                setTimeout(function() {
                                                    $('.alerta').fadeOut(500).removeClass('alert-success', 500);
                                                }, 4000);
                                            },
                                            error: function(e) {
                                                $('.alerta').hide().show().addClass('alert-danger');
                                                $('#alerta_conteudo').html("<span class='glyphicon glyphicon-remove'></span>&ensp;Nenhum resultado encontrado");

                                                setTimeout(function() {
                                                    $('.alerta').fadeOut(500).removeClass('alert-danger', 500);
                                                }, 4000);
                                            }
                                        });
                                    }
                                });

                                $("#editFisica" + id).keypress(function(event) {
                                    if (event.which == 13) {
                                        event.preventDefault();

                                        $("#editGeografia" + id).hide();
                                        $("#editHistoria" + id).hide();
                                        $("#editPortugues" + id).hide();
                                        $("#editCiencias" + id).hide();
                                        $("#editMatematica" + id).hide();
                                        $("#editIngles" + id).hide();
                                        $("#editQuimica" + id).hide();
                                        $("#editFisica" + id).hide();
                                        $("#editSociologia" + id).hide();
                                        $("#editFilosofia" + id).hide();
                                        $("#editMedia" + id).hide();
                                        $("#viewFisica" + id).show();

                                        var data = $('#formsEditarFisica' + id).serialize();

                                        $.ajax({
                                            url: '<?php echo $root_html ?>sistema/alunos/buscar/editarNotasExternas.php',
                                            type: 'POST',
                                            data: data,
                                            complete: function() {
                                                var $val = $("#editFisica" + id + " input").val().replace(",", ".");
                                                $("#viewFisica" + id).html($val);
                                            },
                                            success: function(data) {
                                                $('.alerta').hide().show().addClass('alert-success');
                                                $('#alerta_conteudo').html(data);

                                                setTimeout(function() {
                                                    $('.alerta').fadeOut(500).removeClass('alert-success', 500);
                                                }, 4000);
                                            },
                                            error: function(e) {
                                                $('.alerta').hide().show().addClass('alert-danger');
                                                $('#alerta_conteudo').html("<span class='glyphicon glyphicon-remove'></span>&ensp;Nenhum resultado encontrado");

                                                setTimeout(function() {
                                                    $('.alerta').fadeOut(500).removeClass('alert-danger', 500);
                                                }, 4000);
                                            }
                                        });
                                    }
                                });

                                $("#editSociologia" + id).keypress(function(event) {
                                    if (event.which == 13) {
                                        event.preventDefault();

                                        $("#editGeografia" + id).hide();
                                        $("#editHistoria" + id).hide();
                                        $("#editPortugues" + id).hide();
                                        $("#editCiencias" + id).hide();
                                        $("#editMatematica" + id).hide();
                                        $("#editIngles" + id).hide();
                                        $("#editQuimica" + id).hide();
                                        $("#editFisica" + id).hide();
                                        $("#editSociologia" + id).hide();
                                        $("#editFilosofia" + id).hide();
                                        $("#editMedia" + id).hide();
                                        $("#viewSociologia" + id).show();

                                        var data = $('#formsEditarSociologia' + id).serialize();

                                        $.ajax({
                                            url: '<?php echo $root_html ?>sistema/alunos/buscar/editarNotasExternas.php',
                                            type: 'POST',
                                            data: data,
                                            complete: function() {
                                                var $val = $("#editSociologia" + id + " input").val().replace(",", ".");
                                                $("#viewSociologia" + id).html($val);
                                            },
                                            success: function(data) {
                                                $('.alerta').hide().show().addClass('alert-success');
                                                $('#alerta_conteudo').html(data);

                                                setTimeout(function() {
                                                    $('.alerta').fadeOut(500).removeClass('alert-success', 500);
                                                }, 4000);
                                            },
                                            error: function(e) {
                                                $('.alerta').hide().show().addClass('alert-danger');
                                                $('#alerta_conteudo').html("<span class='glyphicon glyphicon-remove'></span>&ensp;Nenhum resultado encontrado");

                                                setTimeout(function() {
                                                    $('.alerta').fadeOut(500).removeClass('alert-danger', 500);
                                                }, 4000);
                                            }
                                        });
                                    }
                                });

                                $("#editFilosofia" + id).keypress(function(event) {
                                    if (event.which == 13) {
                                        event.preventDefault();

                                        $("#editGeografia" + id).hide();
                                        $("#editHistoria" + id).hide();
                                        $("#editPortugues" + id).hide();
                                        $("#editCiencias" + id).hide();
                                        $("#editMatematica" + id).hide();
                                        $("#editIngles" + id).hide();
                                        $("#editQuimica" + id).hide();
                                        $("#editFisica" + id).hide();
                                        $("#editSociologia" + id).hide();
                                        $("#editFilosofia" + id).hide();
                                        $("#editMedia" + id).hide();
                                        $("#viewFilosofia" + id).show();

                                        var data = $('#formsEditarFilosofia' + id).serialize();

                                        $.ajax({
                                            url: '<?php echo $root_html ?>sistema/alunos/buscar/editarNotasExternas.php',
                                            type: 'POST',
                                            data: data,
                                            complete: function() {
                                                var $val = $("#editFilosofia" + id + " input").val().replace(",", ".");
                                                $("#viewFilosofia" + id).html($val);
                                            },
                                            success: function(data) {
                                                $('.alerta').hide().show().addClass('alert-success');
                                                $('#alerta_conteudo').html(data);

                                                setTimeout(function() {
                                                    $('.alerta').fadeOut(500).removeClass('alert-success', 500);
                                                }, 4000);
                                            },
                                            error: function(e) {
                                                $('.alerta').hide().show().addClass('alert-danger');
                                                $('#alerta_conteudo').html("<span class='glyphicon glyphicon-remove'></span>&ensp;Nenhum resultado encontrado");

                                                setTimeout(function() {
                                                    $('.alerta').fadeOut(500).removeClass('alert-danger', 500);
                                                }, 4000);
                                            }
                                        });
                                    }
                                });

                                $("#editMedia" + id).keypress(function(event) {
                                    if (event.which == 13) {
                                        event.preventDefault();

                                        $("#editGeografia" + id).hide();
                                        $("#editHistoria" + id).hide();
                                        $("#editPortugues" + id).hide();
                                        $("#editCiencias" + id).hide();
                                        $("#editMatematica" + id).hide();
                                        $("#editIngles" + id).hide();
                                        $("#editQuimica" + id).hide();
                                        $("#editFisica" + id).hide();
                                        $("#editSociologia" + id).hide();
                                        $("#editFilosofia" + id).hide();
                                        $("#editMedia" + id).hide();
                                        $("#viewMedia" + id).show();

                                        var data = $('#formsEditarMedia' + id).serialize();

                                        $.ajax({
                                            url: '<?php echo $root_html ?>sistema/alunos/buscar/editarNotasExternas.php',
                                            type: 'POST',
                                            data: data,
                                            complete: function() {
                                                var $val = $("#editMedia" + id + " input").val().replace(",", ".");
                                                $("#viewMedia" + id).html($val);
                                            },
                                            success: function(data) {
                                                $('.alerta').hide().show().addClass('alert-success');
                                                $('#alerta_conteudo').html(data);

                                                setTimeout(function() {
                                                    $('.alerta').fadeOut(500).removeClass('alert-success', 500);
                                                }, 4000);
                                            },
                                            error: function(e) {
                                                $('.alerta').hide().show().addClass('alert-danger');
                                                $('#alerta_conteudo').html("<span class='glyphicon glyphicon-remove'></span>&ensp;Nenhum resultado encontrado");

                                                setTimeout(function() {
                                                    $('.alerta').fadeOut(500).removeClass('alert-danger', 500);
                                                }, 4000);
                                            }
                                        });
                                    }
                                });
                            });



                            $('.edit input, .edit select').focusout(function(event) {
                                $('.edit').hide();
                                $('.view-nota').show();
                                $('.view-dificuldade').show();
                            });

                            // $('#busca').keyup(function () {

                            //     var data = $("#buscaAluno").serialize();
                            //     $.ajax({
                            //         url: '<?php echo $root_html ?>sistema/alunos/buscar/busca.php',
                            //         type: 'POST',
                            //         data: data,
                            //         success: function (data) {
                            //             $('#resultado').html(data);
                            //         },
                            //         error: function (e) {
                            //             $('#resultado').html("<option>Nenhum resultado encontrado.</option>");

                            //         }
                            //     });

                            // });

                            $('#salvar').click(function() {

                                var data = $("#editarCadastro").serialize();
                                $.ajax({
                                    url: '<?php echo $root_html ?>sistema/alunos/buscar/editar.php',
                                    type: 'POST',
                                    data: data,
                                    success: function(data) {
                                        $('.alerta').hide().show().addClass('alert-success');
                                        $('#alerta_conteudo').html(data);

                                        setTimeout(function() {
                                            $('.alerta').fadeOut(500).removeClass('alert-success', 500);
                                        }, 4000);
                                    },
                                    error: function(e) {
                                        $('.alerta').hide().show().addClass('alert-danger');
                                        $('#alerta_conteudo').html("<span class='glyphicon glyphicon-remove'></span>&ensp;Nenhum resultado encontrado");

                                        setTimeout(function() {
                                            $('.alerta').fadeOut(500).removeClass('alert-danger', 500);
                                        }, 4000);
                                    }
                                });

                            });


                            $('#buscaCidade').change(function() {

                                var values = {
                                    'cidade': $('#buscaCidade').val()
                                };
                                $.ajax({
                                    url: '<?php echo $root_html ?>sistema/alunos/buscar/busca_escola.php',
                                    type: 'POST',
                                    data: values,
                                    success: function(data) {
                                        $('#buscaEscola').html(data);

                                    },
                                    error: function(e) {
                                        $('#buscaEscola').html("<option>Nenhum resultado encontrado.</option>");

                                    }
                                });

                            });

                            $('#buscaNome').keyup(function() {

                                var data = $("#buscaAluno").serialize();
                                $.ajax({
                                    url: '<?php echo $root_html ?>sistema/alunos/buscar/busca.php',
                                    type: 'POST',
                                    data: data,
                                    success: function(data) {
                                        $('#resultado').html(data);

                                    },
                                    error: function(e) {
                                        $('#resultado').html("<span class='ajuda_user'>Nenhum resultado encontrado.</span>");

                                    }
                                });

                            });

                            $('#buscaCidade, #buscaEscola, #notass, #notasex, #ano, .buscaStatus, #qtdBusca, #ano_ingresso, #buscaSerie ').change(function() {

                                var data = $("#buscaAluno").serialize();
                                $.ajax({
                                    url: '<?php echo $root_html ?>sistema/alunos/buscar/busca.php',
                                    type: 'POST',
                                    data: data,
                                    success: function(data) {
                                        $('#resultado').html(data);

                                    },
                                    error: function(e) {
                                        $('#resultado').html("<span class='ajuda_user'>Nenhum resultado encontrado.</span>");

                                    }
                                });

                            });

                            $('.excluir').click(function() {
                                var $id = $(this).attr('id');

                                var data = {
                                    id: $id
                                }

                                $.ajax({
                                    url: '<?php echo $root_html ?>sistema/alunos/buscar/excluir_aluno.php',
                                    type: 'POST',
                                    data: data,
                                    success: function(data) {
                                        $('.alerta').hide().show().addClass('alert-success');
                                        $('#alerta_conteudo').html(data);

                                        setTimeout(function() {
                                            $('.alerta').fadeOut(500).removeClass('alert-success', 500);
                                            window.location.reload(false);
                                        }, 2000);

                                        $('.modal').hide();

                                    },
                                    complete: function(e) {

                                    },
                                    error: function(e) {
                                        $('#resultado').html("ERRO. 404");
                                    }
                                });

                            });

                            $('.adicionarPremiacao').click(function() {
                                var $id = $(this).attr('id');
                                var form = $('.formsHidden' + $id)
                                var data = form.serialize();

                                $.ajax({
                                    url: '<?php echo $root_html ?>sistema/alunos/buscar/adicionarPremiacao.php',
                                    type: 'POST',
                                    data: data,
                                    success: function(data) {
                                        $('.alerta').hide().show();
                                        $('#alerta_conteudo').html(data);

                                        setTimeout(function() {
                                            location.reload();
                                        }, 400);
                                    },
                                    complete: function(e) {
                                        $('.modal').modal('hide');
                                        form.reset();
                                    },
                                    error: function(e) {
                                        $('#resultado').html("ERRO. 404");
                                    }
                                });

                            });

                            $('.excluir_premiacao').click(function(event) {

                                if (confirm("Tem certeza que deseja excluir essa premiação?")) {

                                    var id = $(this).attr('data-id');

                                    var data = {
                                        id: id
                                    };


                                    $.ajax({
                                        url: '<?php echo $root_html ?>sistema/alunos/buscar/removerPremiacao.php',
                                        type: 'POST',
                                        data: data,
                                        success: function(data) {
                                            $('.alerta').hide().show();
                                            $('#alerta_conteudo').html(data);
                                            setTimeout(function() {
                                                location.reload();
                                            }, 400);
                                        },
                                        complete: function(e) {
                                            $('.modal').modal('hide');
                                            form.reset();
                                        },
                                        error: function(e) {
                                            $('#alerta_conteudo').html("ERRO. 404");
                                        }
                                    });
                                } else
                                    event.preventDefault();

                            });

                            $('.desativar').click(function() {
                                var $id = $(this).attr('id');
                                var $ativo = $('#input' + $id).val();
                                var $justificativa = $('#inputJustificativa' + $id).val();

                                if ($justificativa != "") {
                                    var data = $('.formsHidden' + $id).serialize();

                                    $.ajax({
                                        url: '<?php echo $root_html ?>sistema/alunos/buscar/desativar.php',
                                        type: 'POST',
                                        data: data,
                                        beforeSend: function(e) {
                                            if ($ativo == 1)
                                                $("#" + $id).html("Desativando...");
                                            else
                                                $("#" + $id).html("Ativando...");
                                        },
                                        success: function(data) {
                                            setTimeout(function() {
                                                $('#' + $id).html(data);
                                                // window.location.reload(false); 
                                            }, 500);
                                        },
                                        complete: function(e) {
                                            if ($ativo == 1)
                                                $("#input" + $id).val(0);
                                            else
                                                $("#input" + $id).val(1);
                                        },
                                        error: function(e) {
                                            $('#resultado1').html("<option>Nenhum resultado encontrado.</option>");
                                        },
                                    });
                                } else {
                                    $('#inputJustificativa' + $id).parent().addClass('has-error');
                                    $('.alerta').hide().show().addClass('alert-error');
                                    $('#alerta_conteudo').html("<span class='glyphicon glyphicon-exclamation-sign'></span>&ensp;Para desativar um aluno é necessário preencher o campo de justificativa.");

                                    setTimeout(function() {
                                        $('.alerta').fadeOut(500).removeClass('alert-success', 500);
                                    }, 8000);

                                }
                            });

                            $('.inputJustificativa').click(function() {
                                $(this).parent().removeClass("has-error");
                            });

                            function chkInternetStatus() {
                                if (navigator.onLine) {
                                    return true;
                                } else {
                                    return false;
                                }
                            }
                        </script>

</body>

</html>