<?php
// On récupère la session courante
session_start();
error_log(print_r($_SESSION, 1));
// On inclue le fichier de configuration et de connexion à la base de données
include('includes/config.php');
// Si l'utilisateur n'est plus logué
if (strlen($_SESSION['rdid']) == 0) {
    // On le redirige vers la page de login
    header('location:index.php');
    exit();
} else {
    // Sinon on peut continuer. Après soumission du formulaire de profil
    if(TRUE === isset($_POST['login'])) {
    // On recupere l'id du lecteur (cle secondaire)
    $userId = $_SESSION['rdid'];
    // error_log(print_r($userId, 1));
    // On recupere le nom complet du lecteur
    $nomLecteur = $_POST['nomComplet'];
    // On recupere le numero de portable
    $portable = $_POST['number'];
    // On recupere le mail
    $newMail = $_POST['emailid'];

    // On update la table tblreaders avec ces valeurs
    $sql = "UPDATE tblreaders SET FullName=:nomComplet, MobileNumber=:number, EmailId=:emailid WHERE ReaderId=:rdid";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':nomComplet', $nomLecteur, PDO::PARAM_STR);
    $stmt->bindParam(':number', $portable, PDO::PARAM_STR);
    $stmt->bindParam(':emailid', $newMail, PDO::PARAM_STR);
    $stmt->bindParam(':rdid', $userId, PDO::PARAM_STR);
    
    // On informe l'utilisateur du resultat de l'operation
        if (!$stmt->execute()) {
            $error = 'Une erreur s\'est produite lors de la mise à jour';
            echo '<script type="text/javascript">window.confirm("'.$error.'");</script>';
        } else {
            $messageOk = 'Votre profil à été mis à jour avec succès';
            echo '<script type="text/javascript">window.confirm("'.$messageOk.'");</script>'; 
        }
    }

    // On souhaite voir la fiche de lecteur courant
    //header('Location: my-profile.php');
    //exit();
    // On recupere l'id de session dans $_SESSION
    $userId = $_SESSION['rdid'];
    error_log($userId);
    // On prepare la requete permettant d'obtenir 
    $new = "SELECT * FROM tblreaders WHERE ReaderId=:rdid";
    $stmtProfil = $dbh->prepare($new);
    $stmtProfil->bindParam('rdid', $userId, PDO::PARAM_STR);
    $stmtProfil->execute();
    $lecteur = $stmtProfil->fetch(PDO::FETCH_ASSOC);
    error_log(print_r($lecteur, 1)); 
}

$status = $lecteur['Status'];
if ($status == 1) {
    $statusLabel = "Actif";
    $style = "color: green;";
} else {
    $statusLabel = "Desactif";
    $style = "color: red;";
}

?>


<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliotheque en ligne | Profil</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <script type="text/javascript">

        
        function disabledBtn() {
            const btnUpdate = document.getElementById('update');
            btnUpdate.style.opacity = "0.25";
            btnUpdate.disabled = true;
            btnUpdate.style.cursor = "not-allowed";
        }

        function abledBtn() {
            const btnUpdate = document.getElementById('update');
            btnUpdate.style.opacity = "1";
            btnUpdate.disabled = false;
            btnUpdate.style.cursor = "pointer";
        }

        async function checkMail(mail) {
            let checkMailInput = document.getElementById('checkMailInput');
            if (mail != "") {
                console.log(mail);
                fetch(`check_availability.php?mail=${mail}`)
                .then(response => response.json())
                .then(data => { 
                    if (data.response === 'false') {
                        alert('addresse email invalide');
                        checkMailInput.style.borderColor = "red";
                        disabledBtn();
                    } else {
                        checkMailInput.style.borderColor = "green";
                        abledBtn();
                    }
                })
            }
        }
        
    </script>
    
</head>

<body>
    <!-- On inclue le fichier header.php qui contient le menu de navigation-->
    <?php include('includes/header.php'); ?>
    <!--On affiche le titre de la page : EDITION DU PROFIL-->
    <div class="container">
               <div class="row">
                    <div class="col">
                         <h3 class="text-start">MON COMPTE</h3><br>
                    </div>
               </div>
               <hr>
        <!--On affiche le formulaire--> 
        <div class="col-xs-12 col-sm-8 offset-sm-2 col-md-8 offset-md-2 col-lg-6 offset-lg-3">

				<form method="post" action="my-profile.php">
                    <!--On affiche l'identifiant - non editable-->
                    <div class="form-floating mb-3">
                        <input type="text" readonly class="form-control-plaintext" name="identifiant">
                        <label for="floatingEmptyPlaintextInput">Identifiant: <?php echo $lecteur['ReaderId'] ?></label>    
                    </div>

                    <!--On affiche la date d'enregistrement - non editable-->
                    <div class="form-floating mb-3">
                        <!-- <input type="text" readonly class="form-control-plaintext" name="date"> -->
                        <label for="floatingEmptyPlaintextInput">Date d'enregistrement: <?php echo $lecteur['RegDate'] ?></label>    
                    </div>

                    <!--On affiche la date de derniere mise a jour - non editable-->
                    <div class="form-floating mb-3">
                        <!-- <input type="text" readonly class="form-control-plaintext" name="miseAJour"> -->
                        <label for="floatingEmptyPlaintextInput">Dernière mise à jour: <?php echo $lecteur['UpdateDate'] ?></label>    
                    </div>

                    <!--On affiche la statut du lecteur - non editable-->
                    <div class="form-floating mb-3">
                        <!-- <input type="text" readonly class="form-control-plaintext" name="status"> -->
                        <label for="floatingEmptyPlaintextInput">Status: 
                            <span id="userstatus" style="<?php echo $style; ?>"><?php echo $statusLabel ?></span>
                        </label>    
                    </div>

                    <!--On affiche le nom complet - editable-->
                    <div class="form-group">
                        <label>Nom complet:</label>
                        <input type="text" class="form-control" name="nomComplet" required>
                    </div>

                    <!--On affiche le numero de portable- editable-->
                    <div class="form-group">
                        <label>Portable:</label>
                        <input type="number" id="number" class="form-control" name="number" required>
                    </div>

                    <!--On affiche l'email- editable-->
                    <div class="form-group">
						<label>Email:</label><br>
						<input type="text" id="checkMailInput" class="form-control" name="emailid" required onblur="checkMail(this.value)">                      
					</div>

                    <button type ="submit" id="update" name="update" class="btn btn-info">Mettre à jour</button>
                    <br><br>
                </form>
        </div>
    </div>           
    <?php include('includes/footer.php'); ?>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>