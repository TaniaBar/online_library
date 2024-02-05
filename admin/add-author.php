<?php
session_start();

include('includes/config.php');

// Si l'utilisateur n'est plus logué
if (strlen($_SESSION['alogin']) == 0) {
      // On le redirige vers la page de login
      header('location:../index.php');
} else {
      // Sinon on peut continuer. Après soumission du formulaire de creation
      if(TRUE === isset($_POST['ajouter'])) {
            // On recupere le nom de l'auteur
            $nameAuteur = $_POST['nameAuteur'];
            // On prepare la requete d'insertion dans la table tblcategory
            $insertAuthor = "INSERT INTO tblauthors (AuthorName) VALUES (:nameAuteur)";
            $stmt = $conn->prepare($insertAuthor);
            $stmt->bindParam(':nameAuteur', $nameAuteur, PDO::PARAM_STR);
            // On execute la requete
            $stmt->execute();

            $lastInsertId = $conn->lastInsertId();
            error_log($lastInsertId);
            // On stocke dans $_SESSION le message correspondant au resultat de loperation
            if(isset($lastInsertId)) {
                  $_SESSION['messageAddAut'] = "Vous avez ajouté un auteur!";
                  // echo "<script>alert $_SESSION['message']"; 
                  echo '<script type="text/javascript">window.confirm("'.$_SESSION['messageAddAut'].'");</script>';
                  
            } else {
                  $_SESSION['messageAddAut'] = "Il y a eu un problème. Veuillez réessayer!";
                  // echo $_SESSION['message'];
                  echo '<script type="text/javascript">window.confirm("'.$_SESSION['messageAddAut'].'");</script>';   
            }  
      }
}
?>

<!DOCTYPE html>
<html lang="FR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliothèque en ligne | Ajout de categories</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
</head>
<body>
      <!------MENU SECTION START-->
<?php include('includes/header.php');?>

    <div class="container">
        <div class="row">
            <div class="col">
                <h3>AJOUTER UN AUTEUR</h3>
            </div>
        </div><br>

      <!-- On affiche le formulaire de creation-->
        <div class="col-xs-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3">
            <form  method="post" action="add-author.php">
                <div class="card border-info mb-3 ">
                    <div class="card-header text-bg-primary p-3" style="background-color: lightblue">
                        <span style="color: #135cdf">INFORMATION AUTEUR</span>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label style="font-weight: bold">NOM AUTEUR</label>
                            <input type="text" class="form-control" name="nameAuteur" required>
                        </div>
                        
                        <!-- bouton Ajouter -->
                        <br>
                        <button type="submit" name="ajouter" class="btn btn-info" id="btnAjouter">Ajouter</button>
                        <br><br>
                    </div>
                </div>
            </form>
        </div>
    </div>
     <!-- CONTENT-WRAPPER SECTION END-->
<?php include('includes/footer.php');?>
      <!-- FOOTER SECTION END-->
     <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
