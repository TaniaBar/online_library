<?php
// On recupere la session courante
session_start();
// On inclue le fichier de configuration et de connexion à la base de données
include('includes/config.php');
// Si l'utilisateur n'est pas logue, on le redirige vers la page de login (index.php)
if (strlen($_SESSION['rdid']) == 0) {
    // On le redirige vers la page de login
    header('location:index.php');
    exit();
	// sinon, on peut continuer,
} else {
	// si le formulaire a ete envoye : $_POST['change'] existe
	if(TRUE === isset($_POST['change'])) {
		// On recupere le mot de passe et on le crypte (fonction php password_hash)
		// $passActuel = password_hash($_POST['changepassword'], PASSWORD_DEFAULT);
		$passActuel = $_POST['changepassword'];
		error_log($passActuel);
		// On recupere l'email de l'utilisateur dans le tabeau $_SESSION
		$userId = $_SESSION['rdid'];
		error_log($userId);

		
		$newpassword = password_hash($_POST['newpassword'], PASSWORD_DEFAULT);
		error_log($newpassword);
		// je cherche email et password de l'utilisateur loggé
		$sql = "SELECT EmailId, Password FROM tblreaders WHERE ReaderId=:rdid";
		$stmt = $dbh->prepare($sql);
		$stmt->bindParam(':rdid', $userId, PDO::PARAM_STR);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_OBJ);

		error_log(password_verify($_POST['changepassword'], $result->Password));

		// si $result n'est pas vide et la password est correcte
		if (!empty($result) && password_verify($_POST['changepassword'], $result->Password)) {
			// On met a jour en base le nouveau mot de passe (tblreader) pour ce lecteur
			// On stocke le message d'operation reussie
			$change = "UPDATE tblreaders SET Password=:newpassword WHERE ReaderId=:rdid";
			$query = $dbh->prepare($change);
			$query->bindParam(':rdid', $userId, PDO::PARAM_STR);
			$query->bindParam(':newpassword', $newpassword, PDO::PARAM_STR);

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
<html>

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

	<title>Gestion de bibliotheque en ligne | changement de mot de passe</title>
	<!-- BOOTSTRAP CORE STYLE  -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<!-- FONT AWESOME STYLE  -->
	<link href="assets/css/font-awesome.css" rel="stylesheet" />
	<!-- CUSTOM STYLE  -->
	<link href="assets/css/style.css" rel="stylesheet" />

</head>
<script type="text/javascript">
	
	function disabledBtn() {
		btnChange.style.opacity = "0.25";
		btnChange.disabled = true;
		btnChange.style.cursor = "not-allowed";
	}

    function abledBtn() {
        btnChange.style.opacity = "1";
		btnChange.disabled = false;
        btnChange.style.cursor = "pointer";
    }
	/* On cree une fonction JS valid() qui verifie si les deux mots de passe saisis sont identiques 
	Cette fonction retourne un booleen*/
	function valid() {

		let newpassword = document.getElementById('newpassword');
		let confirmpassword = document.getElementById('confirmpassword');
		let msgConfirm = document.getElementById('msgConfirm');
		const btnChange = document.getElementById('btnChange');

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
	<!-- Mettre ici le code CSS de mise en forme des message de succes ou d'erreur -->
	<?php include('includes/header.php'); ?>
	<!--On affiche le titre de la page : CHANGER MON MOT DE PASSE-->
	<div class="container">
               <div class="row">
                    <div class="col">
                         <h3 class="text-start">CHANGER MON MOT DE PASSE</h3><br>
                    </div>
               </div>
				<hr>
		<!--  Si on a une erreur, on l'affiche ici -->
		<!--  Si on a un message, on l'affiche ici -->
		<span id="change_psw"></span>

		<!--On affiche le formulaire-->
		<div class="col-xs-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-4 offset-lg-4">
				<form method="post" action="change-password.php">
					<div class="form-group">
						<label>Mot de passe actuel:</label><br>
						<input type="password" name="changepassword" id="changepassword" class="form-control" required>
					</div>

					<div class="form-group">
						<label>Nouveau mot de passe:</label><br>
						<input type="password" name="newpassword" id="newpassword" class="form-control" required>
					</div>

					<div class="form-group">
						<label>Confirmer mot de passe:</label><br>
						<input type="password" name="confirmpassword" id="confirmpassword" class="form-control" required onkeyup="valid()">
					</div>
					<div>
                        <span id="msgConfirm"></span><br>
                    </div><br>

					<button type ="submit" id="btnChange" name="change" class="btn btn-info">Changer</button>
                    <br><br>
		</div>
		
	</div>
	<?php include('includes/footer.php'); ?>
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>