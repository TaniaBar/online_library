<?php
session_start();

include('includes/config.php');

// Si l'utilisateur n'est plus logué
if (strlen($_SESSION['alogin']) == 0) {
	// L'utilisateur est renvoyé vers la page de login : index.php
	header('location:../index.php');
	// sinon, on peut continuer,
} else {
	// Sinon on peut continuer. Après soumission du formulaire de modification du mot de passe
	if(TRUE === isset($_POST['btnChangeAdmin'])) {
		// On recupere le mot de passe courant
		$passActuel = $_POST['changepasswordAdmin'];
		error_log($passActuel);
		
		// On recupere le nom de l'utilisateur stocké dans $_SESSION
		$nomAdmin = $_SESSION['username'];

		// On prepare la requete de recherche pour recuperer l'id de l'administrateur (table admin)
		// dont on connait le nom et le mot de passe actuel
		// On execute la requete
		$admin = "SELECT id, Password FROM admin WHERE UserName=:username";
		$stmt = $conn->prepare($admin);
		$stmt->bindParam(':username', $nomAdmin, PDO::PARAM_STR);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_OBJ);
		// Si on trouve un resultat
		if ($result && password_verify($_POST['changepasswordAdmin'], $result->Password)) {

			// On recupere le nouveau mot de passe
			$newpasswordAdmin = password_hash($_POST['newpasswordAdmin'], PASSWORD_DEFAULT);
			
			// On prepare la requete de mise a jour du nouveau mot de passe de cet id
			$changePswAdmin = "UPDATE admin SET Password=:newpasswordAdmin WHERE UserName=:username";
			$query = $conn->prepare($changePswAdmin);
			$query->bindParam(':username', $nomAdmin, PDO::PARAM_STR);
			$query->bindParam(':newpasswordAdmin', $newpasswordAdmin, PDO::PARAM_STR);

			if ($query->execute()) {
				$msg = "Le mot de passe a été changé avec succès";
				echo '<script type="text/javascript">alert("'.$msg.'");</script>';

			} else {
				// sinon (resultat de recherche vide)
				// On stocke le message "mot de passe invalide"
				$msg2 = "Il y a eu un erreur dans le changement de mot de passe. Veuillez réessayer";
				echo '<script type="text/javascript">alert("'.$msg2.'");</script>';
			};
		}
	}
}

?>

<!DOCTYPE html>
<html lang="FR">

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<title>Gestion bibliotheque en ligne</title>
	<!-- BOOTSTRAP CORE STYLE  -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<!-- FONT AWESOME STYLE  -->
	<link href="assets/css/font-awesome.css" rel="stylesheet" />
	<!-- CUSTOM STYLE  -->
	<link href="assets/css/style.css" rel="stylesheet" />
	<!-- Penser a mettre dans la feuille de style les classes pour afficher le message de succes ou d'erreur  -->
</head>
<script type="text/javascript">

function disabledBtn() {
		const btnChange = document.getElementById('btnChangeAdmin');
		btnChange.style.opacity = "0.25";
		btnChange.disabled = true;
		btnChange.style.cursor = "not-allowed";
	}

    function abledBtn() {
		const btnChange = document.getElementById('btnChangeAdmin');
        btnChange.style.opacity = "1";
		btnChange.disabled = false;
        btnChange.style.cursor = "pointer";
    }
	// On cree une fonction JS valid() qui renvoie
	// true si les mots de passe sont identiques
	// false sinon
	function valid() {
		let newpassword = document.getElementById('newpasswordAdmin');
		let confirmpassword = document.getElementById('confirmpasswordAdmin');
		let msgConfirm = document.getElementById('msgConfirmAdmin');
		const btnChange = document.getElementById('btnChangeAdmin');

		if (confirmpassword != "") {
			if (newpassword.value === confirmpassword.value){
				msgConfirm.innerText = "Les deux passwords sont egaux";
				msgConfirm.style.visibility = "visible";
				msgConfirm.style.color = "green";
				abledBtn();
                return true;
			} else {
				msgConfirm.innerText = "Les deux passwords ne sont pas egaux";
				msgConfirm.style.visibility = "visible";
				msgConfirm.style.color = "red";
				disabledBtn();
                return false;
			}
		}	
	}
</script>

<body>
	<!------MENU SECTION START-->
	<?php include('includes/header.php'); ?>
	
	<div class="container">
               <div class="row">
                    <div class="col">
							<!-- On affiche le titre de la page "Changer de mot de passe"  -->
                         <h3 class="text-start">MODIFIER MON MOT DE PASSE</h3><br>
                    </div>
               </div>
				<hr>

		<!-- On affiche le formulaire de changement de mot de passe-->
		<div class="col-xs-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-4 offset-lg-4">
				<form method="post" action="change-password.php">
					<div class="form-group">
						<label>Mot de passe actuel:</label><br>
						<input type="password" name="changepasswordAdmin" id="changepasswordAdmin" class="form-control" required>
					</div>

					<div class="form-group">
						<label>Nouveau mot de passe:</label><br>
						<input type="password" name="newpasswordAdmin" id="newpasswordAdmin" class="form-control" required>
					</div>

					<div class="form-group">
						<label>Confirmer mot de passe:</label><br>
						<input type="password" name="confirmpasswordAdmin" id="confirmpasswordAdmin" class="form-control" required onkeyup="valid()">
					</div>
					<div>
                        <span id="msgConfirmAdmin"></span><br>
                    </div><br>

					<button type ="submit" id="btnChangeAdmin" name="btnChangeAdmin" class="btn btn-info">Changer</button>
                    <br><br>
		</div>
		
	</div>

	<!-- CONTENT-WRAPPER SECTION END-->
	<?php include('includes/footer.php'); ?>
	<!-- FOOTER SECTION END-->
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>