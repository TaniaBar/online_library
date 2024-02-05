<?php 

// On inclue le fichier de configuration et de connexion a la base de donnees
require_once("includes/config.php");
// On recupere dans $_GET l email soumis par l'utilisateur*******
	$user_email = $_GET['mail'];
	$email = $user_email;
	// On verifie que l'email est un email valide (fonction php filter_var)*******
	if ( !filter_var($email, FILTER_VALIDATE_EMAIL)) {
		// Si ce n'est pas le cas, on fait un echo qui signale l'erreur**********
		echo '{"response" : "false"}';
		error_log('no');
	} else {
		// Si c'est bon **********
		// On prepare la requete qui recherche la presence de l'email dans la table tblreaders******
		$user_email = $_GET['mail'];
		$verif = "SELECT EmailId FROM tblreaders WHERE EmailId=:email";
		$stmt = $dbh->prepare($verif);
		$stmt->bindParam(':email', $user_email);
		// On execute la requete et on stocke le resultat de recherche*****
		$stmt->execute();
		$resultat = $stmt->fetch(PDO::FETCH_ASSOC);
		// error_log('$resultat');
		// Si le resultat n'est pas vide. On signale a l'utilisateur que cet email existe deja et on desactive le bouton*****
		// de soumission du formulaire****
		if ($resultat) {
			echo '{"response" : "find"}';
			error_log("Adresse email déjà utilisée");
			// Sinon on signale a l'utlisateur que l'email est disponible et on active le bouton du formulaire*****
		} else {
			error_log("Addresse email libre");
			echo '{"response" : "not found"}';
		}	
	}	
		
	

		
