<?php
session_start();

include('includes/config.php');

// Si l'utilisateur est déconnecté
if(strlen($_SESSION['alogin']) == 0) {
      // L'utilisateur est renvoyé vers la page de login : index.php
      header('location:../index.php');
} else {
      // if($_SESSION['messageEditAut']) {
      //       echo '<script type="text/javascript">window.confirm("'.$_SESSION['messageEditAut'].'");</script>';
      // }
        // Gestion button supprim
      if (isset($_POST['supprim'])) {
            error_log(print_r($_POST, 1));
    
            // On recupere l'identifiant de la catégorie a supprimer
            $idAut = $_POST['idAut'];
            error_log(print_r($idAut, 1));
            // On prepare la requete de suppression
            $changeStat = "UPDATE tblauthors SET Status=0 WHERE id=:idAut";
            $stmtc = $conn->prepare($changeStat);
            $stmtc->bindParam(':idAut', $idAut);
            
            // On execute la requete
            if($stmtc->execute()) {
                // On informe l'utilisateur du resultat de loperation
                  $msg = "L'auteur est maintenant inactive!";
                      echo '<script type="text/javascript">alert("'.$msg.'");</script>';
            } else {
                  $msg = "L'auteur est encore active. Veuillez réessayer!";
                      echo '<script type="text/javascript">alert("'.$msg.'");</script>';
            };
    
            
      } else if(isset($_POST['edit'])) {
            error_log("Avant redirection vers edit " . print_r($_POST, 1));
            $_SESSION['nameAut'] = $_POST['nameAut'];
            $_SESSION['idAut'] = $_POST['idAut'];
            $_SESSION['statusAut'];
            header('location: edit-author.php');
      }
    
            // On redirige l'utilisateur vers la page manage-categories.php
            $affiche = "SELECT * FROM tblauthors";
            error_log($affiche);
            $stmt = $conn->prepare($affiche);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="FR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliothèque en ligne | Gestion des auteurs</title>
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
                  <h3>GESTION DES AUTEURS</h3>
                  </div>
            </div>
            <hr>
            <!-- On affiche le titre de la page-->
            <div class="col-xs-12 col-sm-12 col-md-10 offset-md-1 col-lg-12">
                  <!-- <form method="post" action="manage-categories.php"> -->
                  <div class="card border-light mb-3 ">
                        <div class="card-header text-bg-light p-3" style="background-color: lightgrey">
                              <span style="font-weight: bold">Auteurs</span>
                        </div>
                        <div class="card-body">
                              <table class="table table-striped">
                                    <thead>
                                          <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Nom</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Crée le</th>
                                                <th scope="col">Mise à jour le</th>
                                                <th scope="col">Action</th>
                                          </tr>
                                    </thead>
                                    <tbody>

                                          <?php 
                                          $c = 0;
                                          foreach($results as $result) {
                                              $status = $result['Status'];
                                              $c++;

                                          if ($status === 0) {
                                              $statDef = "Inactive";
                                              $style = "background-color: #bf0628; color: white; padding-left: 2px; border-radius: 5px";
                                          } else {
                                              $statDef = "Active";
                                              $style = "background-color: #49bf65; color: white; padding-left: 5px; border-radius: 5px";
                                          }
                                          
                                          ?>

                                          <tr>
                                                <td scope="row"><?php echo $c ?></td>
                                                <td name="nomAut"><?php echo $result['AuthorName'] ?></td>
                                                <td name="statusAut" >
                                                      <div name="statusAut" style="<?php echo $style; ?>"><?php echo $statDef ?></div>
                                                </td>
                                                <td name="creationAut"><?php echo $result['creationDate'] ?></td>
                                                <td name="MiseajourAut"><?php echo $result['UpdationDate'] ?></td>
                                                <td name="actionAut">
                                                <form method="post" action="manage-authors.php">
                                                      <input type="hidden" name="nameAut" value=" <?php echo $result['AuthorName'] ?>">
                                                      <input type="hidden" name="idAut" value=" <?php echo $result['id'] ?>">
                                                      <button name="edit" class="btn btn-info" type="submit">Editer</button>&nbsp
                                                </form>
                                                
                                                <form method="post" action="manage-authors.php">
                                                      <input type="hidden" name="idAut" value=" <?php echo $result['id'] ?>">
                                                      <button name="supprim" class="btn btn-danger" type="submit">Supprimer</button>
                                                </form>
                                                </td>
                                          </tr>
                                          <?php } ?>
                                    </tbody>
                              </table>
                        </div>
                  </div>
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
