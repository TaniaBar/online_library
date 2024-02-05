<?php
session_start();

include('includes/config.php');

error_log("POST :".print_r($_POST, 1));

// / Si l'utilisateur n'est plus logué
if (strlen($_SESSION['alogin']) == 0) {
  // On le redirige vers la page de login
  header('location:../index.php');
} else {
      if (TRUE === isset($_POST['edit'])) {
            $titre = $_POST['titre'];
            $catSelect = $_POST['catSelect'];
            $autSelect = $_POST['autSelect'];
            $isbn = $_POST['isbn'];
            $prix = $_POST['prix'];
            $idLivre = $_SESSION['idLivre'];
            
            $updateLivre = "UPDATE tblbooks SET BookName='$titre', CatId='$catSelect', AuthorId='$autSelect', ISBNNumber=$isbn, BookPrice=$prix WHERE id=$idLivre";
            error_log(print_r($updateLivre, 1));
            $stmt = $conn->prepare($updateLivre);

            // $updateLivre = "UPDATE tblbooks SET BookName=:titre, CatId=:catId, AuthorId=:autId, ISBNNumber=:isbn, BookPrice=:prix WHERE id=:idLivre";
           
            // $stmt = $conn->prepare($updateLivre);
            // $stmt->bindParam(':titre', $titre, PDO::PARAM_STR);
            // $stmt->bindParam(':catId', $catSelect, PDO::PARAM_INT);
            // $stmt->bindParam(':autId', $autSelect, PDO::PARAM_INT);
            // $stmt->bindParam(':isbn', $isbn, PDO::PARAM_INT);
            // $stmt->bindParam(':prix', $prix, PDO::PARAM_INT);
            // $stmt->bindParam(':idLivre', $idLivre, PDO::PARAM_INT);

            if ($stmt->execute()) {
                  error_log("edition ok");
                  //error_log(print_r($stmt, 1));
                  $_SESSION['messageUpdateBook'] = "Vous avez bien edité le livre!";
                  //echo '<script type="text/javascript">window.confirm("' .$_SESSION['messageUpdateBook']. '");</script>';
                  header('location: manage-books.php');
            } else {
                  $_SESSION['messageUpdateBookError'] = "Il y a eu un problème.Veuillez réessayer!";
                  //echo '<script type="text/javascript">window.confirm("' .$_SESSION['messageUpdateBookError']. '");</script>';
            }

      }

      $startTitre = $_SESSION['titre'];
      $startCatLivre = $_SESSION['catLivre'];
      $startAutLivre = $_SESSION['autLivre'];
      $startIsbn = $_SESSION['isbn']; 
      $startPrix = $_SESSION['prix'];
      $idLivre = $_SESSION['idLivre'];

      $recupCat = "SELECT CategoryName, id FROM tblcategory";
      $stmtr = $conn->prepare($recupCat);
      $stmtr->execute();
      $catResults = $stmtr->fetchAll(PDO::FETCH_ASSOC);

      $recupAuthor = "SELECT AuthorName, id FROM tblauthors";
      $stmta = $conn->prepare($recupAuthor);
      $stmta->execute();
      $authorResults = $stmta->fetchAll(PDO::FETCH_ASSOC);


}
?>

<!DOCTYPE html>
<html>

<head>
      <meta charset="utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

      <title>Gestion de bibliothèque en ligne | Livres</title>
      <!-- BOOTSTRAP CORE STYLE  -->
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
      <!-- FONT AWESOME STYLE  -->
      <link href="assets/css/font-awesome.css" rel="stylesheet" />
      <!-- CUSTOM STYLE  -->
      <link href="assets/css/style.css" rel="stylesheet" />
</head>

<body>
      <!------MENU SECTION START-->
      <?php include('includes/header.php'); ?>

      <div class="container">
            <div class="row">
                  <div class="col">
                  <h3>EDITER UN LIVRE</h3>
                  </div>
            </div><br>

            <div class="col-xs-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3">
                  <form method="post" action="edit-book.php">
                  <div class="card border-info mb-3 ">
                        <div class="card-header text-bg-primary p-3" style="background-color: lightblue">
                              <span style="color: #135cdf">INFORMATIONS LIVRE</span>
                        </div>
                        <div class="card-body">
                              <div class="form-group">
                              <label style="font-weight: bold">Titre</label><span style="color:red">*</span>
                              <input type="text" class="form-control" name="titre" placeholder="<?php echo $startTitre ?>" required>
                              </div>     

                              <div class="form-group">
                              <label style="font-weight: bold">Categorie</label><span style="color:red">*</span><br>
                              <select name="catSelect" class="form-control" aria-label="Default select example" required>
                                    <option selected>Choisir une categorie</option>
                                    <?php 
                                    foreach ($catResults as $catResult) {
                                          $id = $catResult['id'];
                                          $nameCat = $catResult['CategoryName'];
                                    ?>
                                    <option value="<?php echo $id ?>"><?php echo $nameCat?></option>  

                                    <?php } ?>
                              </select>
                              </div>

                              <div class="form-group">
                              <label style="font-weight: bold">Auteur</label><span style="color:red">*</span><br>
                              <select name="autSelect" class="form-control" aria-label="Default select example" required>
                                    <option selected>Choisir un auteur</option>
                                    <?php 
                                    foreach ($authorResults as $authorResult) {
                                          $id = $authorResult['id'];
                                          $nameAuthor = $authorResult['AuthorName'];
                                    ?>

                                    <option value="<?php echo $id ?>"><?php echo $nameAuthor?></option>

                                    <?php } ?>
                              
                              </select>
                              </div>

                              <div class="form-group">
                              <label style="font-weight: bold">ISBN</label><span style="color:red">*</span>
                              <input type="text" class="form-control" name="isbn" placeholder="<?php echo $startIsbn ?>" required>
                              <span style="opacity:70%">Le numero ISBN doit etre unique</span>
                              </div>

                              <div class="form-group">
                              <label style="font-weight: bold">Prix</label><span style="color:red">*</span>
                              <input type="text" class="form-control" name="prix" placeholder="<?php echo $startPrix ?>" required>
                              </div>
                              
                              <!-- bouton mettre à jour -->
                              <br>
                              <button type="submit" name="edit" class="btn btn-info">Mettre à jour</button>
                              <br><br>
                        </div>
                  </div>
                  </form>
                  
            </div>

      </div>
      <!-- MENU SECTION END-->

      <!-- CONTENT-WRAPPER SECTION END-->
      <?php include('includes/footer.php'); ?>
      <!-- FOOTER SECTION END-->
      <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>