<!DOCTYPE html>
<html lang="fr" xmlns:th="https://www.thymeleaf.org">
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
		ob_start();
		if(!isset($_SESSION['userName'])) { 
			header("location: index.php");
		}
		$controller = $_SESSION['controller'];
		$html = new HTMLMaker();
		$html->echoNavBar();
	
	?>

		<div class="container bg-light contentPane p-3">
			<h1 class="pt-4 text-center">Gestion des Clients</h1>
			<hr>
			
			<a href="addclient.php" class="btn btn-primary btn-sm mb-3">Ajouter</a>
			<div class="table-responsive">
				<table class="table table-hover table-striped table-dark table-bordered">
					<thead>
					<tr>
					<th scope="col">Nom</th>
					<th scope="col">Prenom</th>
					<th scope="col">Telephone</th>
					<th scope="col">Email</th>
					<th scope="col">Adresse</th>
					<th scope="col">Action</th>
					</tr>
					<tbody>
					<?php 
					
						foreach($controller->entreprise->listClients as $client){
							echo '<tr>
							<td>'.$client->nom.'</td>
							<td>'.$client->prenom.'</td>
							<td>'.$client->telephone.'</td>
							<td>'.$client->email.'</td>
							<td>'.$client->adresse->toString().'</td>
							<td>
								<form method="post">
										<input type="hidden" name="client_id" value="'.$client->id.'">
										<input type="submit" class="btn btn-success" name="edit_client" value="Selectionner">
										<button id="'.$client->id.'" type="button" onclick="setClient('.$client->id.')"class="btn btn-danger" data-toggle="modal" data-target="#confirm-delete-modal">Supprimer</button>
								</form>

							</td>
							</tr>';
						}
				
						
						if(isset($_POST['edit_client'])){
							ob_end_clean();
							$controller->client = $controller->dao->findClientById($_POST['client_id']);
							
							header('Location: listprestation.php');
						}
						
	
					?>
					</tbody>
				</table>
			</div>
		</div>
		
		<!-- Fenêtre de confirmation modal -->
		<div class="modal fade" id="confirm-delete-modal" tabindex="-1" role="dialog" aria-labelledby="confirm-delete-modal-title"
			aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="confirm-delete-modal-title">Confirmation de suppression</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						Êtes-vous sûr de vouloir supprimer ce Client? Cette action est irréversible.
					</div>
					<div class="modal-footer">

							<button id="annuler" type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
							<button id="confirmer" type="button" class="btn btn-danger" name="deleteClientsubmitconfirm" value="" onclick="deleteClient($('#confirmer').val())">Supprimer</button>
					</div>
				</div>
			</div>
		</div>
		<?php $html->echoFooter() ?>
		
		<script>

			function setClient(idClient){
				$("#confirmer").val(idClient);
			}
			
			function deleteClient(idClient){
				$.ajax({
					type: 'POST',
					url: '../controller/setClient.php',
					data: { action:  idClient},
					success: function(response) {
						location.reload();
					},
					error: function(xhr, status, error) {
						console.log('Error:', error);
					}
				});
			}

		</script>
	</body>
</html>