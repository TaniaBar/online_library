<?php
session_start();

include('includes/config.php');

// Si l'utilisateur n'est plus logué
if (strlen($_SESSION['alogin']) == 0) {
      // On le redirige vers la page de login  
      header('location:../index.php');
} else {
      if(isset($_POST['editer'])) {
            $returnDate = $_POST['returnDate'];
            $id = $_SESSION['id'];
            error_log($id);
            $miseAjourDate = "UPDATE tblissuedbookdetails SET ReturnDate=:returnDate, ReturnStatus=1 WHERE id=:id";
            $stmt = $conn->prepare($miseAjourDate);
            $stmt->bindParam(':returnDate', $returnDate, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            if($stmt->execute()) {
                  header('location: manage-issued-books.php');   
                  $_SESSION['messageDateRetour'] = "Vous avez ajouté la date de retour!";
            } else {
                  $_SESSION['messageDateRetour'] = "Il y a eu un problème. Veuillez réessayer!";
                  echo '<script type="text/javascript">window.confirm("'.$_SESSION['messageDateRetour'].'");</script>';
            }
            
      }
}
?>


<!DOCTYPE html>
<html lang="FR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliothèque en ligne | Sorties</title>
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

      <!-- On affiche le titre de la page-->
      <div class="container">
            <div class="row">
                  <div class="col">
                        <h3>EDITER UN AUTEUR</h3>
                  </div>
            </div><br>
            <!-- <div>
                  <span name="message" id="messageAddCat"></span>
            </div><br> -->
            <!-- On affiche le formulaire de creation-->
            <div class="col-xs-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3">
                  <form  method="post" action="edit-issue-book.php">
                        <div class="card border-info mb-3 ">
                              <div class="card-header text-bg-primary p-3" style="background-color: lightblue">
                                    <span style="color: #135cdf">AJOUTER RETURN DATE</span>
                              </div>
                        
                              <div class="card-body">
                                    <div class="form-group">
                                    <label style="font-weight: bold">Return Date</label>
                                    <input type="text" class="form-control" name="returnDate" required>
                                    </div>
                                    
                                    <!-- bouton Creer -->
                                    <br>
                                    <div>
                                    <!-- <input type="hidden" name="idCat" value="< echo $result['id'] ?>"> -->
                                    <button type="submit" name="editer" class="btn btn-info">Mettre à jour</button>
                                    </div>
                                    
                              </div>
                        
                        </div>
                  </form>
            </div>
      </div>
<!-- MENU SECTION END-->

     <!-- CONTENT-WRAPPER SECTION END-->
<?php include('includes/footer.php');?>
      <!-- FOOTER SECTION END-->
     <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
