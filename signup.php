<?php
// On récupère la session courante
session_start();
error_log(print_r($_SESSION, 1));
error_log(print_r($_POST, 1));

// On inclue le fichier de configuration et de connexion à la base de données
include('includes/config.php');

// Après la soumission du formulaire de compte (plus bas dans ce fichier)*******
if (TRUE === isset($_POST['login'])) {

    // On vérifie si le code captcha est correct en comparant ce que l'utilisateur a saisi dans le formulaire*********
    // $_POST["vercode"] et la valeur initialisée $_SESSION["vercode"] lors de l'appel à captcha.php (voir plus bas)
    if ($_POST['vercode'] != $_SESSION['vercode']) {
        // Le code est incorrect on informe l'utilisateur par une fenetre pop_up
        echo "<script>alert('Code de vérification incorrect')</script>";
    } else {
        //On lit le contenu du fichier readerid.txt au moyen de la fonction 'file'. Ce fichier contient le dernier identifiant lecteur cree.
        $ressourceLue = file('readerid.txt');
        // On incrémente de 1 la valeur lue
        $ressourceIncr = ++$ressourceLue[0];
        // On ouvre le fichier readerid.txt en écriture
        $ressource = fopen('readerid.txt', 'c+b');
        // On écrit dans ce fichier la nouvelle valeur******
        fwrite($ressource, $ressourceIncr); 
        // On referme le fichier******
        fclose($ressource);
        // On récupère le nom saisi par le lecteur*****
        $name = $_POST['nom'];
        // On récupère le numéro de portable*****
        $number = $_POST['number'];
        // On récupère l'email*****
        $email = $_POST['emailid'];
        // On récupère le mot de passe*****
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        // On fixe le statut du lecteur à 1 par défaut (actif)*****
        $status = 1;
        // On prépare la requete d'insertion en base de données de toutes ces valeurs dans la table tblreaders*****
        $sql = "INSERT INTO tblreaders (ReaderId, FullName, MobileNumber, EmailId, Password, Status)
        VALUES (:ressource, :nom, :number, :email, :password, :status)";
        error_log($sql);
        $stmt = $dbh->prepare($sql);
       
        $stmt->bindParam(':ressource', $ressourceIncr, PDO::PARAM_STR);
        $stmt->bindParam(':nom', $name, PDO::PARAM_STR);
        $stmt->bindParam(':number', $number, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        $stmt->bindParam(':status', $status, PDO::PARAM_INT);

        // On éxecute la requete*****
        $stmt->execute();
        // On récupère le dernier id inséré en bd (fonction lastInsertId)****
        $lastInsertId = $dbh->lastInsertId();
        error_log($lastInsertId);
        // Si ce dernier id existe, on affiche dans une pop-up que l'opération s'est bien déroulée,****
        // et on affiche l'identifiant lecteur (valeur de $hit[0] après incrémentation)
        if (isset($lastInsertId)) {
            $message = 'l\'opération s\'est bien déroulée. Identifiant lecteur : ' . $ressourceIncr;
              echo '<script type="text/javascript">window.confirm("'.$message.'");</script>';
        // Sinon on affiche qu'il y a eu un problème****
        } else {
            $message1 = 'Il y a eu un problème avec vos coordonnées';
            echo '<script type="text/javascript">window.confirm("'.$message1.'");</script>';
        } 
    }
}

?>

<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <!--[if IE]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <![endif]-->
    <title>Gestion de bibliotheque en ligne | Signup</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <!-- link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' / -->
    <script type="text/javascript">

        function disabledBtn() {
            btnSubmit.style.opacity = "0.25";
            btnSubmit.disabled = true;
            btnSubmit.style.cursor = "not-allowed";
        }

        function abledBtn() {
            btnSubmit.style.opacity = "1";
            btnSubmit.disabled = false;
            btnSubmit.style.cursor = "pointer";
        }

        // On cree une fonction valid() sans paramètre qui renvoie ***********
        // TRUE si les mots de passe saisis dans le formulaire sont identiques
        // FALSE sinon
        function valid() {

            let password = document.getElementById('password');
            let checkPassword = document.getElementById('check-password');
            let alert = document.getElementById('message');
            const btnSubmit = document.getElementById('btnSubmit');
            
            if (checkPassword != "") {
                if (password.value === checkPassword.value) {  
                    alert.innerText = "Valid password";
                    alert.style.visibility = "visible";
                    alert.style.color = "green";
                    abledBtn();
                    return true;
                } else {   
                    alert.innerText = "Invalid password";
                    alert.style.visibility = "visible";
                    alert.style.color = "red";
                    disabledBtn();
                    return false;
                }   
            }  
        } 

        // On cree une fonction avec l'email passé en paramêtre et qui vérifie la disponibilité de l'email ***********
        // Cette fonction effectue un appel fetch vers check_availability.php
        // Le mail est passé dans l'url
        async function checkAvailability(mail) {
            let emailInput = document.getElementById('mailInput');
            if (mail != "") {
                console.log(mail);
                fetch(`check_availability.php?mail=${mail}`)
                .then(response => response.json())
                .then(data => { 
                    if (data.response === 'false') {
                        alert('addresse email invalide');
                        emailInput.style.borderColor = "red";
                        disabledBtn();
                    } else {
                        if (data.response === 'find') {
                            alert('email déjà utilisé');
                            emailInput.style.borderColor = "red";
                            disabledBtn();
                        } else if (data.response === 'not found') {
                            console.log('email disponible');
                            emailInput.style.borderColor = "green";
                            abledBtn();
                        }
                    }
                })
                .catch((error) => console.log('error')); 
            } else {
                disabledBtn();
                emailInput.style.borderColor = "lightgrey";
            }
        }

    </script>
</head>

<body>
    <!-- On inclue le fichier header.php qui contient le menu de navigation-->
    <?php include('includes/header.php'); ?>
    <!--On affiche le titre de la page : CREER UN COMPTE *********-->  
    <div class="container">
		<div class="row">
			<div class="col">
				<h3 class="text-start">CRÉER UN COMPTE</h3><br>
			</div>
		</div>
        <!--On affiche le formulaire de creation de compte *********-->
        <!-- <div class="col-xs-12 col-sm-6 offset-sm-3 col-md-6 offset-md-3 col-lg-4 offset-lg-5">  -->
        <div class="col-xs-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-4 offset-lg-4">
				<form method="post" action="signup.php">
                    <div class="form-group">
                        <label>Entrez votre nom complet</label>
                        <input type="text" class="form-control" name="nom" required>
                    </div>

                    <div class="form-group">
                        <label>Portable:</label>
                        <input type="number" id="number" class="form-control" name="number" required>
                    </div>

					<div class="form-group">
						<label>Email:</label><br>
                        <!-- On appelle la fonction checkAvailability() dans la balise <input> de l'email onBlur="checkAvailability(this.value)" *********-->
						<input type="text" id="mailInput" class="form-control" name="emailid" required onblur="checkAvailability(this.value)" >                      
					</div>

					<div class="form-group">
						<label>Mot de passe:</label><br>
						<input type="password" id="password" class="form-control" name="password" required>
					</div>

					<div class="form-group">
						<label>Confirmer mot de passe:</label><br>
                        <!-- On appelle la fonction valid() dans la balise <form> onSubmit="return valid()"; *********-->
						<input type="password" id="check-password" class="form-control" name="password" required onkeyup="valid()">
					</div>
                    <div>
                        <span id="message"></span><br>
                    </div><br>
					<!--A la suite de la zone de saisie du captcha, on insere l'image cree par captcha.php : <img src="captcha.php">  *********-->
					<div class="form-group">
						<label style="margin-bottom: 0rem">Code de vérification:</label><br>
						<input type="text" name="vercode" required style="height:25px;"><br>
						<!-- &nbsp;&nbsp;&nbsp; -->
						<br>
						<img src="captcha.php">
					</div>

					<br>
					<button type="submit" name="login" class="btn btn-danger" id="btnSubmit" on>Enregistrer</button>
					<br><br>
				</form>
        </div>
	</div>

    <?php include('includes/footer.php'); ?>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>