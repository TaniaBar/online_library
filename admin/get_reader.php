<?php  

	require_once("includes/config.php");

	$findNameReader = $_GET['readerId'];

	error_log(print_r($findNameReader, 1)); 

		$sql = "SELECT FullName FROM tblreaders WHERE ReaderId=:readerId";

		$stmt = $conn->prepare($sql);
		$stmt->bindParam(':readerId', $findNameReader, PDO::PARAM_STR);
		$stmt->execute();
		$find = $stmt->fetch(PDO::FETCH_ASSOC);
		$fullName = $find['FullName'];
		
		if ($find) {
			echo '{"response" : "find", "fullName" : "'.$fullName.'"}';
		} else {
			echo '{"response" : "notFind"}';
		}


	/* Cette fonction est declenchee au moyen d'un appel AJAX depuis le formulaire de sortie de livre */
	/* On recupere le numero l'identifiant du lecteur SID---*/
	// On prepare la requete de recherche du lecteur correspondnat
	// On execute la requete
	// Si un resultat est trouve
		// On affiche le nom du lecteur
		// On active le bouton de soumission du formulaire
	// Sinon
		// Si le lecteur n existe pas
			// On affiche que "Le lecteur est non valide"
			// On desactive le bouton de soumission du formulaire
		// Si le lecteur est bloque
			// On affiche lecteur bloque
			// On desactive le bouton de soumission du formulaire

?>
