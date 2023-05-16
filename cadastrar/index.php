<?php include '../../../web/seguranca.php';
$query_qs = "SELECT * FROM questionariose ORDER BY pergunta_num";
$result_qs = mysqli_query($_SG['link'], $query_qs);
$title = "AdminPFC - Alunos";

include '../../head.php';
?>

<body class="hold-transition skin-black sidebar-mini fixed">

	<div class="wrapper">

		<?php include '../../menu.php'; ?>
		<div class="content-wrapper">
			<section class="content-header">
				<a href="<?php echo $root_html ?>sistema/" class="btn btn-default"><i class="fa fa-arrow-left"></i>&ensp;Voltar</a>
				<ol class="breadcrumb">
					<li><a href="<?php echo $root_html ?>sistema/"><i class="fa fa-dashboard"></i> Home</a></li>
					<li>Alunos</li>
					<li class="active">Cadastrar</li>
				</ol>
			</section>
			<br><br>
			<div class="container-fluid">

				<form method="POST" action="cadastrar.php" class="alunoCadastro forms-cadastrar" autocomplete="off">

					<h1 class="text-center">Ficha de Cadastro <br> <small>Aluno</small></h1>
					<hr><br>

					<div class="form-group">
						<label for="nome">Nome <span style="color: red;">*</span></label>
						<input type="text" id="nome" name="nome_cadastrar" placeholder="Nome completo" class="form-control" required>
					</div>

					<div class="form-group">
					</div>

					<div class="form-group">
						<label for="email">E-mail </label>
						<input type="email" placeholder="example@example.com.br" id="email" name="email" class="form-control">
						<!-- 						<div class="checkbox">
							<label data-toggle="tooltip" title="Clique aqui caso o aluno não possua um e-mail">
								<input type="checkbox" id="sem_email" onclick="$('#email').slideToggle(); $('#email').val('');"> Sem e-mail
							</label>
						</div>
 -->
					</div>

					<div class="form-group" hidden>
						<label for="senha">Senha</label>
						<input autocomplete="new-password" type="password" id="senha" name="senha" class="form-control">
					</div>

					<div class="row">
						<div class="form-group col-md-6">
							<label for="rg">RG</label>
							<input type="text" id="rg" name="rg" class="form-control" placeholder="99.999.999-X">
						</div>

						<div class="form-group col-md-6">
							<label for="ra">RA <span style="color: red;">*</span></label>
							<input type="text" id="ra" name="ra" class="form-control" placeholder="999.999.999-9">
						</div>

						<div class="form-group col-md-12">
							<label for="cpf">CPF <span style="color: red;">*</span></label>
							<input required type="text" id="cpf" name="cpf" class="form-control" placeholder="xxx.xxx.xxx-x">
						</div>
					</div>

					<div class="form-group">
						<label for="endereco">Endereço </label>
						<input type="text" id="endereco" name="end" class="form-control" placeholder="Rua das Pamanhas, número 45 - Bairo das Árvores">
					</div>

					<!-- Campo "telefone" não será mais utlizado -->

					<!-- <div class="form-group">
						<label for="telefone">Telefone</label>
						<input type="text" id="telefone" name="tel" class="form-control" placeholder="Telefone fixo para contato">
					</div> -->

					<div class="form-group">
						<label for="celular">Celular do Aluno</label>
						<input type="text" id="celular" name="cel" class="form-control" placeholder="Telefone móvel para contato">
					</div>

					<div class="form-group">
						<label for="facebook">Perfil do Facebook</label>
						<input type="text" id="facebook" name="face" class="form-control" placeholder="URL do perfil no facebook">
					</div>

					<div class="form-group">
						<label for="instagram">Perfil do Instagram</label>
						<input type="text" id="instagram" name="instagram" class="form-control" placeholder="URL do perfil no instagram">
					</div>

					<div class="form-group">
						<label for="genero">Gênero <span style="color: red;">*</span></label>
						<select id="genero" name="genero" class="form-control" required>
							<option value="1">Masculino</option>
							<option value="2">Feminino</option>
							<option value="3">Outro </option>
						</select>
					</div>

					<!-- <div class="form-group">
						<label for="etnia">Etnia</label>
						<select id="etnia" name="etnia" class="form-control">
							<option hidden>Escolha uma etnia</option>
							<option value="1">Branco(a)</option>
							<option value="2">Pardo(a)</option>
							<option value="3">Negro(a)</option>
							<option value="4">Amarelo(a)</option>
							<option value="5">Indígena</option>
						</select>
					</div> -->

					<div class="row">

						<div class="form-group col-md-6">
							<label for="nasc">Data de Nascimento <span style="color: red;">*</span></label>
							<input required type="text" id="nasc" name="data" class="form-control">
						</div>

						<div class="form-group col-md-6">
							<label for="ano_ingresso">Ano de Ingresso <span style="color: red;">*</span></label>
							<input type="text" id="ano_ingresso" name="ano_ingresso" class="form-control" minlength="4" maxlength="4" required oninvalid="this.setCustomValidity('Ano Incorreto!!!')" onchange="try{setCustomValidity('')}catch(e){}">
							<p class="help-block">Ano em que o aluno entrou no programa</p>
						</div>

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
							<label for="buscaCidade">Cidade <span style="color: red;">*</span></label>
							<select readonly name="cidade" id="buscaCidade" class="form-control" required>
								<option value="<?php echo $supervisor['cidade']; ?>" selected><?php echo $supervisor['cidade']; ?></option>
							</select>
						</div>

						<div class="form-group">
							<label for="escola">Escola <span style="color: red;">*</span></label>
							<select name="escola1" id="escola" class="form-control" required>
								<?php
								while ($escola1 = mysqli_fetch_assoc($result)) {
									echo '<option value="' . $escola1['id_escola'] . '">' . $escola1['nome_escola'] . '</option>';
								}
								?>
							</select>
						</div>

					<?php else :

						$escola = mysqli_query($_SG['link'], "SELECT DISTINCT cidade FROM escola");

					?>

						<div class="form-group">
							<label for="cidade">Cidade <span style="color: red;">*</span></label>
							<select class="form-control" name="cidade" id="cidade" required>
								<option value="" hidden>Escolha uma cidade</option>
								<?php
								while ($e = mysqli_fetch_array($escola)) {
									echo '<option value="' . $e['cidade'] . '">' . $e['cidade'] . '</option>';
								}
								?>
							</select>

						</div>

						<div class="form-group">
							<label for="escola">Escola <span style="color: red;">*</span></label>
							<select name="escola1" id="escola" class="form-control" required>
								<option value="" hidden>Selecione uma cidade para ver suas respectivas escolas</option>
							</select>
						</div>


					<?php endif; ?>


					<div class="form-group">
						<label for="serie">Série <span style="color: red;">*</span></label>
						<select name="serie" id="serie" class="form-control" required>
							<option value="" hidden>Selecione a série do aluno</option>
							<option value="5EF">5º ano - Ensino Fundamental</option>
							<option value="6EF">6º ano - Ensino Fundamental</option>
							<option value="7EF">7º ano - Ensino Fundamental</option>
							<option value="8EF">8º ano - Ensino Fundamental</option>
							<option value="9EF">9º ano - Ensino Fundamental</option>
							<option value="1EM">1º ano - Ensino Médio</option>
							<option value="2EM">2º ano - Ensino Médio</option>
							<option value="3EM">3º ano - Ensino Médio</option>

						</select>
					</div>

					<!-- substituido nome do pai e nome da mae por nome do responsavel (enviar nome do responsavel no campo: "nome do pai") -->
					<div class="form-group">
						<label for="nome_pai">Nome do Responsável</label>
						<input type="text" id="nome_pai" name="pai" class="form-control" placeholder="Nome completo do responsável">
					</div>

					<div class="form-group">
						<label for="email_pai">E-mail do Responsável </label>
						<input type="email_pai" placeholder="example@example.com.br" id="email_pai" name="email_pai" class="form-control">
					</div>

					<div class="form-group">
						<label for="celular_pai">Celular do Responsável </label>
						<input type="text" id="celular_pai" name="cel_pai" class="form-control" placeholder="Telefone móvel para contato" maxlength="11">
					</div>

					<!-- 
					<div class="form-group">
						<label for="nome_mae">Nome da Mãe</label>
						<input type="text" id="nome_mae" name="mae" class="form-control" placeholder="Nome completo da mãe">
					</div>
					-->

					<div class="form-group" hidden>
						<label for="camiseta">Tamanho da Camiseta</label>
						<input type="text" id="camiseta" name="camiseta" class="form-control">
					</div>

					<br><br>

					<!-- 
						<h3>Formulário Socio-Econômico</h3>
					<hr><br>

					<?php
					/* while ($questao = mysqli_fetch_assoc($result_qs)) {
						echo '<div style="text-align: left; width: 100%">';
						echo '<label>' . $questao['pergunta_num'] . ') ' . $questao['pergunta_texto'] . '</label><br><br>';
						switch ($questao['tipo']) {
							case 1:
								echo '<select class="form-control" name="r' . $questao['pergunta_num'] . '">';
								for ($i = 1; $i <= $questao['questoes']; $i++) {
									echo '<option value="' . $i . '">' . $questao['r' . $i] . '</option>';
								}
								echo '</select>';
								break;
							case 2:
								echo '<div class="checkbox">';
								for ($i = 1; $i <= $questao['questoes']; $i++) {

									echo '<label>
                                <input type="checkbox" name="r' . $questao['pergunta_num'] . '[]" value="' . $i . '"> ' . $questao['r' . $i] . "</label>";
									if ($i != $questao['questoes'])
										echo '<br>';
								}
								echo '</div>';
								break;
							case 3:
								echo '<textarea class="form-control upper" type="text" name="r' . $questao['pergunta_num'] . '"></textarea>';
								break;
						}
						echo '<br><br></div>';
					} */
					?> 
					-->


					<div class="text-center">
						<p><span style="color: red;">*</span> Campos obrigatórios</p>
						<input type="submit" class="btn btn-primary btn-lg" value="Cadastrar">
						<input type="reset" class="btn btn-default pull-right">
					</div>
				</form>

			</div>
		</div>

	</div>

	<?php include '../../footer.php'; ?>
	<script type="text/javascript">
		$('#cidade').change(function() {


			var values = {
				'cidade': $('#cidade').val()
			};
			$.ajax({
				url: '<?php echo $root_html ?>sistema/alunos/cadastrar/busca_escola.php',
				type: 'POST',
				data: values,
				success: function(data) {
					$('#escola').html(data);

				},
				error: function(e) {
					$('#escola').html("<option>Nenhum resultado encontrado.</option>");

				}
			});


		});
	</script>

</body>

</html>