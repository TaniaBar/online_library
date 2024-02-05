<?php 

require_once("includes/config.php");

$findBookName = $_GET['isbn'];

	$sql = "SELECT BookName FROM tblbooks WHERE ISBNNumber=:isbn";

	$stmt = $conn->prepare($sql);
	$stmt->bindParam(':isbn', $findBookName, PDO::PARAM_INT);
	$stmt->execute();
	$find = $stmt->fetch(PDO::FETCH_ASSOC);
	if ($find) {
		$bookName = $find["BookName"]; 
		echo '{"response" : "find", "bookName" : "'.$bookName.'"}';
	} else {
		echo '{"response" : "notFind"}';
	}
/* Cette fonction est declenchee au moyen d'un appel AJAX depuis le formulaire de sortie de livre */
/* On recupere le numero ISBN du livre*/
// On prepare la requete de recherche du livre correspondnat
// On execute la requete
// Si un resultat est trouve
	// On affiche le nom du livre
	// On active le bouton de soumission du formulaire
// Sinon
	// On affiche que "ISBN est non valide"
	// On desactive le bouton de soumission du formulaire 
?>
