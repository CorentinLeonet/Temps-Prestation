<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Working Time Count</title>
	<meta charset="UTF-8">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
	<link rel="stylesheet" href="../css/font.css">
</head>
<body class="bg-secondary">

	<?php 
	
		require_once("../controller/Controller.php");	
		require_once("../controller/HTMLMaker.php");
		session_start(); 
		
		if(!isset($_SESSION['userName'])) { 
			header("location: index.php");
		}
		
		$controller = $_SESSION['controller'];
		
		$html = new HTMLMaker();
		$html->echoNavBar();
		
		echo'<div class="container bg-light contentPane  p-3">';

		echo '<div class="text-center">';
		echo '<h1 class="pt-4 text-center">Prestation</h1>';
		echo '<div class="d-flex justify-content-center align-items-center mb-4"><label class="me-2" style="font-weight : 500; font-size : 25px">Client :</label> <span style="font-weight : 500; font-size : 25px">'.$controller->client->getNomPrenom().'</span></div>';
		
		echo '<form name="choixClient" method="POST">';
		echo '<br>
			<div class="d-flex text-center justify-content-center">
				<div name="prestation" class="form-group p-2 m-2 pt-0 border border-secondary border-2">
					<label class="mt-2" for="tarif">Tarif de la prestation :</label>
					<input type="number" style="width : 40px" step="0.01" name="tarif" id="tarif" required value="'.$controller->entreprise->tarif.'">
					<label for="tarif">&#8364/h</label></br>
					
					
					
					<label class="mt-2" for="description">Description du travail :</label>
					<input type="text" class="form-text"  name="description" required id="description"></br>
					
					<label class="mt-2" for="urgent">Urgent :</label>
					<input type="checkbox"  name="urgent" id="urgent"></br>
				</div>
			</div>
			<input type="submit" class="btn btn-success btn-lg mt-4" name="validatePresation" id="validatePresation" value="Valider La prestation">
		</form>';
		
		if(isset($_POST['validatePresation'])){
			
			if(isset($_POST['urgent'])){
				$controller->prestation->urgent = true;
			}
			else{
				$controller->prestation->urgent = false;
			}
			$controller->prestation->entreprise = $controller->entreprise;
			$controller->prestation->client = $controller->client;
			$controller->prestation->setValues(date('Y-m-d H:i:s'), $_SESSION['elapsed_time'], $_POST['description'], $controller->entreprise->tarif, $controller->entreprise->tva);
			$controller->savePrestation();
			
			
			ob_end_clean();
			$controller->client = $controller->dao->findClientById($controller->client->id);
							
			header('Location: listprestation.php');
			
		}
		
		
	?>
			<div class="d-flex text-center justify-content-center">
				<div name="compteur" class="form-group p-2 pb-4 m-2 border border-secondary border-2">
					
					<div class="mt-4" style="font-size : 25px">
						<label class="m-2 mb-4">Temps écoulé :</label></br>
						<span class="bg-dark text-success p-3 mt-3" id="tempsEcoule">00:00:00</span>
					</div>
					<button id="startCompteur" class="btn btn-primary btn-lg mt-4"><i class="bi bi-play-fill"></i></button>
					<button id="pauseCompteur" class="btn btn-secondary btn-lg mt-4"><i class="bi bi-pause-fill"></i></button>
					<button id="unpauseCompteur" class="btn btn-secondary btn-lg mt-4"><i class="bi bi-play-fill"></i></button>
					<button id="stopCompteur" class="btn btn-danger btn-lg mt-4"><i class="bi bi-stop-fill"></i></button>
				</div>

			</div>
		</div>
	</div>
	
	<?php $html->echoFooter() ?>
	<script>
	var on = false;
	
		$(document).ready(function() {
			
			$('#stopCompteur').hide();
			$('#validatePresation').hide();
			$('#pauseCompteur').hide();
			$('#unpauseCompteur').hide();
			
			$('#urgent').change(function() {
				if($('#urgent').is(':checked')){
					$('#tarif').val(<?php echo $controller->entreprise->tarifUrgent?>);
				}
				else{
					$('#tarif').val(<?php echo $controller->entreprise->tarif?>);
				}
			});
			
            $('#startCompteur').click(function() {
                on = true;
				$('#stopCompteur').show();
				$('#pauseCompteur').show();
				$('#startCompteur').hide();
				$.ajax({
					type: 'POST',
					url: '../controller/compteur.php',
					data: { action: 'start' },
					success: function(response) {
					
					},
					error: function(xhr, status, error) {
						console.log('Error:', error);
					}
				});
				
            });
			
			$('#pauseCompteur').click(function() {
				$('#unpauseCompteur').show();
				$('#pauseCompteur').hide();
                on = false;
				
				$.ajax({
					type: 'POST',
					url: '../controller/compteur.php',
					data: { action: 'pause' },
					success: function(response) {
					},
					error: function(xhr, status, error) {
						console.log('Error:', error);
					}
				});
            });
			
			$('#unpauseCompteur').click(function() {
				$('#stopCompteur').show();
				$('#pauseCompteur').show();
				$('#unpauseCompteur').hide();
                on = true;
				
				$.ajax({
					type: 'POST',
					url: '../controller/compteur.php',
					data: { action: 'unpause' },
					success: function(response) {

					},
					error: function(xhr, status, error) {
						console.log('Error:', error);
					}
				});
            });
			
			$('#stopCompteur').click(function() {
                on = false;
				$('#startCompteur').hide();
				$('#pauseCompteur').hide();
				$('#unpauseCompteur').hide();
				$.ajax({
					type: 'POST',
					url: '../controller/compteur.php',
					data: { action: 'stop' },
					dataType: 'json',
					success: function(response) {
					
					},
					error: function(xhr, status, error) {
						console.log('Error:', error);
					}
				});
				$('#stopCompteur').hide();
				$('#validatePresation').show();
				
				
            });
			
			setInterval(function () {
				if(on == true){
					updateCompteur();
				}
			}, 1000);
        });

		
		function updateCompteur() {
			$.ajax({
				type: 'POST',
				url: '../controller/compteur.php',
				data: { action: 'update' },
				dataType: 'json',
				success: function(response) {
					$("#tempsEcoule").text(response.time);
				},
				error: function(xhr, status, error) {
					console.log('Error:', error);
					console.log('Status:', status);
				}
			});
		}


	</script>

</body>
</html>
