<?php
// On récupère la session courante
session_start();

// On inclue le fichier de configuration et de connexion à la base de données
include('includes/config.php');
// Après la soumission du formulaire de login ($_POST['change'] existe
if (TRUE === isset($_POST['change'])) {
// On verifie si le code captcha est correct en comparant ce que l'utilisateur a saisi dans le formulaire
// $_POST["vercode"] et la valeur initialisee $_SESSION["vercode"] lors de l'appel a captcha.php (voir plus bas)
     if ($_POST['vercode'] != $_SESSION['vercode']) {
     // Si le code est incorrect on informe l'utilisateur par une fenetre pop_up
          echo "<script>alert('Code de vérification incorrect')</script>";
     // Sinon on continue
    } else {
          // on recupere l'email et le numero de portable saisi par l'utilisateur
          // et le nouveau mot de passe que l'on encode (fonction password_hash)
          $email = $_POST['emailid'];
          $number = $_POST['number'];
          $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
          // On cherche en base le lecteur avec cet email et ce numero de tel dans la table tblreaders
          $verif = "SELECT EmailId, MobileNumber FROM tblreaders WHERE EmailId=:email AND MobileNumber=:number";
          $stmt = $dbh->prepare($verif);
          $stmt->bindParam(':email', $email, PDO::PARAM_STR);
          $stmt->bindParam(':number', $number, PDO::PARAM_STR);
          $stmt->execute();
          $resultat = $stmt->fetch(PDO::FETCH_ASSOC);
          error_log("resultat ".print_r($resultat, 1));

          if (!$resultat) {
               $accountInvalid = 'Vous n\'avez pas un compte';
               echo '<script type="text/javascript">window.confirm("'.$accountInvalid.'");</script>';
               // Si le resultat de recherche n'est pas vide
          } else {
               // On met a jour la table tblreaders avec le nouveau mot de passe
               $sql = "UPDATE tblreaders SET Password=:password WHERE EmailId=:email";
               $query = $dbh->prepare($sql);
               $query->bindParam(':password', $password, PDO::PARAM_STR);
               $query->bindParam(':email', $email, PDO::PARAM_STR);
               $query->execute();
               // On informa l'utilisateur par une fenetre popup de la reussite ou de l'echec de l'operation
               $messagePwdOk = 'Votre password à été changée correctement';
               echo '<script type="text/javascript">window.confirm("'.$messagePwdOk.'");</script>';    
          }
     }
}
?>

<!DOCTYPE html>
<html lang="FR">

<head>
     <meta charset="utf-8" />
     <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

     <title>Gestion de bibliotheque en ligne | Recuperation de mot de passe </title>
     <!-- BOOTSTRAP CORE STYLE  -->
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
     <!-- FONT AWESOME STYLE  -->
     <link href="assets/css/font-awesome.css" rel="stylesheet" />
     <!-- CUSTOM STYLE  -->
     <link href="assets/css/style.css" rel="stylesheet" />

     <script type="text/javascript">
          // On cree une fonction nommee valid() qui verifie que les deux mots de passe saisis par l'utilisateur sont identiques.
          function disabledBtn() {
            btnSubmitf.style.opacity = "0.25";
            btnSubmitf.disabled = true;
            btnSubmitf.style.cursor = "not-allowed";
          }

          function abledBtn() {
            btnSubmitf.style.opacity = "1";
            btnSubmitf.disabled = false;
            btnSubmitf.style.cursor = "pointer";
          }

          function valid() {
               let password = document.getElementById('passwordf');
               let checkPassword = document.getElementById('check-passwordf');
               let alert = document.getElementById('messagef');
               const btnSubmit = document.getElementById('btnSubmitf');
               
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
     </script>

</head>

<body>
     <!--On inclue ici le menu de navigation includes/header.php-->
     <?php include('includes/header.php'); ?>
     <!-- On insere le titre de la page (RECUPERATION MOT DE PASSE -->
     <div class="container">
		<div class="row">
			<div class="col">
				<h3 class="text-start">RECUPERATION MOT DE PASSE</h3><br>
			</div>
		</div>
     <!--On insere le formulaire de recuperation-->
     <div class="col-xs-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-4 offset-lg-4">
          <form method="post" action="user-forgot-password.php">
               <div class="form-group">
                    <label>Email:</label><br>
                         <!-- On appelle la fonction checkAvailability() dans la balise <input> de l'email onBlur="checkAvailability(this.value)" *********-->
                    <input type="text" id="emailf" class="form-control" name="emailid" required >                      
               </div>

               <div class="form-group">
                    <label>Portable:</label>
                    <input type="number" id="numberf" class="form-control" name="number" required>
               </div>
                         
               <div class="form-group">
                    <label>Nouveau mot de passe:</label><br>
                    <input type="password" id="passwordf" class="form-control" name="password" required>
               </div>
                         
               <div class="form-group">
                    <label>Confirmer mot de passe:</label><br>
                    <!--L'appel de la fonction valid() se fait dans la balise <form> au moyen de la propri�t� onSubmit="return valid();"-->
                    <!-- On appelle la fonction valid() dans la balise <form> onSubmit="return valid()"; *********-->
                         <input type="password" id="check-passwordf" class="form-control" name="password" required onkeyup="valid()">
               </div>
               <div>
                    <span id="messagef"></span><br>
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
               <div>
               <button type="submit" name="change" class="btn btn-info" id="btnSubmitf" on>Envoyer</button> 
               <span>| <a href="index.php"> Login</a></span>
               </div>
               <br><br>			
                    
		</form>
	</div>
     

     <?php include('includes/footer.php'); ?>
     <!-- FOOTER SECTION END-->
     <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>