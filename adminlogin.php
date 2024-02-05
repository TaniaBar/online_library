<?php
// On demarre ou on recupere la session courante
session_start();

// On inclue le fichier de configuration et de connexion � la base de donn�es
include('includes/config.php');

// On invalide le cache de session $_SESSION['alogin'] = ''
if (isset($_SESSION['alogin']) && $_SESSION['alogin'] != '') {
    $_SESSION['alogin'] = '';
}

// A faire :
// Apres la soumission du formulaire de login (plus bas dans ce fichier)
if(TRUE === isset($_POST['alogin'])) {

// On verifie si le code captcha est correct en comparant ce que l'utilisateur a saisi dans le formulaire
// $_POST["vercode"] et la valeur initialis�e $_SESSION["vercode"] lors de l'appel a captcha.php (voir plus bas)
    if ($_POST['vercode'] != $_SESSION['vercode'] && $_POST['vercode'] != "") {
        echo "<script>alert('Code de verification incorrect')</script>";
    } else { 
        // Le code est correct, on peut continuer
        // On recupere le nom de l'utilisateur saisi dans le formulaire
        $username = $_POST['username'];
            error_log(print_r($username, 1));
        // On recupere le mot de passe saisi par l'utilisateur et on le crypte 
       $pwd = $_POST['password'];
        // On construit la requete qui permet de retrouver l'utilisateur a partir de son nom et de son mot de passe
        // depuis la table admin
        $cherche = "SELECT UserName, Password FROM admin WHERE UserName =:username";
        $stmt = $dbh->prepare($cherche);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        // $stmt->bindParam(':password', $pwd, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
    
            error_log("result ".print_r($result, 1));
        // Si le resultat de recherche n'est pas vide 
        if (!empty($result) && password_verify($_POST['password'], $result->Password)){
            // On stocke le nom de l'utilisateur $_POST['username'] en session $_SESSION
            $_SESSION['username'] = $_POST['username'];
            $_SESSION['alogin'] = 'ok';
                // error_log('coucou');
                echo "<script>alert('Acces valid')</script>";
            // On redirige l'utilisateur vers le tableau de bord administration (n'existe pas encore)
            header('Location:admin/dashboard.php');
            exit();
        } else {
             // sinon le login est refuse. On le signal par une popup
            echo "<script>alert('Vous n\'avez pas accès à la page. Veuillez réessayer')</script>";
        } 
    }
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliotheque en ligne</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
</head>

<body>
    <!-- On inclue le fichier header.php qui contient le menu de navigation-->
    <?php include('includes/header.php'); ?>

    <div class="content-wrapper">
        <!--On affiche le titre de la page-->
        <div class="row">
			<div class="col">
				<h3 class="text-center">LOGIN ADMIN</h3><br>
			</div>
		</div>
        <!--On affiche le formulaire de login-->
        <div class="col-xs-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-4 offset-lg-4">
				<form method="post" action="adminlogin.php">
                    <div class="form-group">
                        <label>Entrez votre username:</label>
                        <input type="text" class="form-control" name="username" required>
                    </div>

                    <div class="form-group">
						<label>Mot de passe:</label><br>
						<input type="password" class="form-control" name="password" required>
					</div>

                    <div class="form-group">
						<label style="margin-bottom: 0rem">Code de vérification:</label><br>
						&nbsp<input type="text" name="vercode" required style="height:25px;"><br>
						<!-- &nbsp;&nbsp;&nbsp; -->
						<br>
                        <!--A la suite de la zone de saisie du captcha, on ins�re l'image cr��e par captcha.php : <img src="captcha.php">  -->
						<img src="captcha.php">
					</div>

                    <br>
					<button type="submit" name="alogin" class="btn btn-primary" on>Login</button>
					<br><br>

				</form>
        </div>
        
    </div>
    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('includes/footer.php'); ?>
    <!-- FOOTER SECTION END-->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>