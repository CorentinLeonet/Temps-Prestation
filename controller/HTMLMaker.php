<?php
		
	class HTMLMaker
	{
		function echoNavBar()
		{
			echo'
			
			<nav class="navbar shadow navbar-expand-lg navbar-light bg-light">
				<div class="container">
				  <span class="navbar-brand">Working Time Count</span>
				  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				  </button>
				  <div class="collapse navbar-collapse" id="navbarNav">
					<ul class="navbar-nav">
					   <li class="nav-item'.' ';  echo (basename($_SERVER['PHP_SELF']) == 'listclient.php') ? 'active' : ''; echo' ">
						<a class="nav-link" href="listclient.php">Gestion Clients</a>
					  </li>
					  <li class="nav-item'.' '; echo (basename($_SERVER['PHP_SELF']) == 'editentreprise.php') ? 'active' : ''; echo '">
						<a class="nav-link" href="editentreprise.php">Gestion Entreprise</a>
					  </li>
					</ul>
					
				  </div>
				</div>
				<div class="d-flex justify-content-end align-items-center">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
					<path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4Zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10Z"/>
					</svg>

					<span class="text-end font-bold">'.$_SESSION['userName'].'</span>
					<a class=" m-2" href="index.php">Se deconnecter</a>
				</div>
			</nav>
			';
		}
		
		function echoFooter()
		{
			echo '
			
			<footer class="footer bg-light shadow-x text-center py-3" style="bottom: 0;">
			  <hr>
			  <span>&copy; C. LEONET - EAFC de Marche-en-Famenne</span>
			</footer>
			';
		}
		
		function echoClassFrom()
		{
			echo 'form-group p-2 m-2 pt-0 border border-secondary border-2';
		}
	}
	