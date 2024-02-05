<?php
session_start();

include('includes/config.php');

// Si l'utilisateur n'est plus logué
if (strlen($_SESSION['alogin']) == 0) {
     // On le redirige vers la page de login  
     header('location:../index.php');
} else {
     // On recupere l'identifiant, le nom 
    $id = $_SESSION['idAut'];
    error_log($id);
    $nameAut = $_SESSION['nameAut'];
    
    // On prepare la requete de recherche des elements de l author dans tblauthors
    // On execute la requete
    $sql = "SELECT Status FROM tblauthors WHERE id=:idAut";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':idAut', $id, PDO::PARAM_INT);
    $stmt->execute();
    $status = $stmt->fetch(PDO::FETCH_NUM);
    error_log(print_r($status, 1));
 
    // Apres soumission du formulaire de author
    if(isset($_POST['editer'])) {
        $newNameAut = $_POST['nameAut'];
        $newStatus = $_POST['flexRadio'];
        // On prepare la requete de mise a jour
        $miseAjour = "UPDATE tblauthors SET AuthorName=:nameAut, Status=:flexRadio WHERE id=:idAut";
        $query = $conn->prepare($miseAjour);
        $query->bindParam(':nameAut', $newNameAut, PDO::PARAM_STR);
        $query->bindParam(':flexRadio', $newStatus, PDO::PARAM_INT);
        $query->bindParam(':idAut', $id, PDO::PARAM_INT);
        // On stocke dans $_SESSION le message "Author mise a jour"
         if ($query->execute()) {
               // on redirige vers manage-authors.php
               header('location: manage-authors.php');   
               $_SESSION['messageEditAut'] = "Vous avez edité un auteur!";
          } else {
               $_SESSION['messageEditAut'] = "Il y a eu un problème. Veuillez réessayer!";
               echo '<script type="text/javascript">window.confirm("'.$_SESSION['messageEditAut'].'");</script>';   
          } 
     }  
}
?>
<!DOCTYPE html>
<html lang="FR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliothèque en ligne | Auteurs</title>
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

     <!-- On affiche le titre de la page "Editer la categorie-->
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
               <form  method="post" action="edit-author.php">
                    <div class="card border-info mb-3 ">
                         <div class="card-header text-bg-primary p-3" style="background-color: lightblue">
                              <span style="color: #135cdf">INFO AUTEUR</span>
                         </div>
                   
                         <div class="card-body">
                              <div class="form-group">
                                   <label style="font-weight: bold">NOM AUTEUR</label>
                                   <input type="text" class="form-control" name="nameAut" placeholder="<?php echo $nameAut ?>" required>
                              </div>
                              <!-- radio box -->
                        
                              <div>
                                   <span style="font-weight: bold">STATUS</span>
                              </div>
                              <!-- Si la categorie est active (status == 1)-->
                              <!-- On coche le bouton radio "actif"-->
                              <div class="form-check">
                                   <input class="form-check-input" type="radio" name="flexRadio" value="1" <?php if($status[0] == 1) echo "checked" ?>> 
                                   <label class="form-check-label" for="flexRadioDefault1">
                                        Active
                                   </label>
                              </div>
                              <!-- Sinon-->
                              <!-- On coche le bouton radio "inactif"-->
                              <div class="form-check">
                                   <input class="form-check-input" type="radio" name="flexRadio" value="0" <?php if($status[0] == 0) echo "checked" ?>>
                                   <label class="form-check-label" for="flexRadioDefault2">
                                        Inactive
                                   </label>
                              </div>
                              <!-- bouton Creer -->
                              <br>
                              <div>
                                   <!-- <input type="hidden" name="idCat" value="< echo $result['id'] ?>"> -->
                                   <button type="submit" name="editer" class="btn btn-info">Mettre à jour</button>
                              </div>
                              <br><br>
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
