<?php
session_start();

include('includes/config.php');

// Si l'utilisateur n'est plus logué
if (strlen($_SESSION['alogin']) == 0) {
  // On le redirige vers la page de login
  header('location:../index.php');
} else {
  // Sinon on peut continuer. Après soumission du formulaire de creation
  if(TRUE === isset($_POST['ajout'])) {
    // on recupere titre, cat, author, isbn, prix
    $titre = $_POST['titre'];
    $catSelect = $_POST['catSelect'];
    $autSelect = $_POST['autSelect'];
    $isbn = $_POST['isbn'];
    $prix = $_POST['prix'];

    // on fait la requete pour ajouter à la base de données

    $ajoutLivre = "INSERT INTO tblbooks (BookName, CatId, AuthorId, ISBNNumber, BookPrice) VALUES (:titre, :catSelect, :autSelect, :isbn, :prix)";
    $stmta = $conn->prepare($ajoutLivre);
    $stmta->bindParam(':titre', $titre, PDO::PARAM_STR);
    $stmta->bindParam(':catSelect', $catSelect, PDO::PARAM_INT);
    $stmta->bindParam(':autSelect', $autSelect, PDO::PARAM_INT);
    $stmta->bindParam(':isbn', $isbn, PDO::PARAM_INT);
    $stmta->bindParam(':prix', $prix, PDO::PARAM_INT);
    if($stmta->execute()) {
      $_SESSION['messageAddBook'] = "Vous avez ajouté un livre!";
          // echo "<script>alert $_SESSION['message']"; 
      echo '<script type="text/javascript">window.confirm("'.$_SESSION['messageAddBook'].'");</script>';
                  
    } else {
        $_SESSION['messageAddBook'] = "Il y a eu un problème. Veuillez réessayer!";
            // echo $_SESSION['message'];
        echo '<script type="text/javascript">window.confirm("'.$_SESSION['messageAddBook'].'");</script>'; 
    };

  }

  $recupCat = "SELECT CategoryName, id FROM tblcategory";
  $stmt = $conn->prepare($recupCat);
  $stmt->execute();
  $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
  // error_log(print_r($results, 1));

  $recupAut = "SELECT AuthorName, id FROM tblauthors";
  $query = $conn->prepare($recupAut);
  $query->execute();
  $resultats = $query->fetchAll(PDO::FETCH_ASSOC);
  error_log(print_r($resultats, 1));
}

?>
<!DOCTYPE html>
<html lang="FR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliothèque en ligne | Ajout de livres</title>
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
                <h3>AJOUTER UN LIVRE</h3>
            </div>
        </div><br>

        <div class="col-xs-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3">
            <form  method="post" action="add-book.php">
                <div class="card border-info mb-3 ">
                    <div class="card-header text-bg-primary p-3" style="background-color: lightblue">
                        <span style="color: #135cdf">INFORMATIONS LIVRE</span>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label style="font-weight: bold">Titre</label><span style="color:red">*</span>
                            <input type="text" class="form-control" name="titre" required>
                        </div>

                        

                        <div class="form-group">
                            <label style="font-weight: bold">Categorie</label><span style="color:red">*</span><br>
                            <select name="catSelect" class="form-control" aria-label="Default select example" required>
                              <option selected>Choisir une categorie</option>
                              <?php 
                                  foreach ($results as $result) {
                                 
                              ?>
                              <option value="<?php echo $result['id'] ?>"><?php echo $result['CategoryName']?></option>  

                              <?php } ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label style="font-weight: bold">Auteur</label><span style="color:red">*</span><br>
                            <select name="autSelect" class="form-control" aria-label="Default select example" required>
                              <option selected>Choisir un auteur</option>
                              <?php 
                                  foreach ($resultats as $resultat) {
                                  
                              ?>

                              <option value="<?php echo $resultat['id'] ?>"><?php echo $resultat['AuthorName']?></option>

                              <?php } ?>
                            
                            </select>
                        </div>

                        <div class="form-group">
                            <label style="font-weight: bold">ISBN</label><span style="color:red">*</span>
                            <input type="text" class="form-control" name="isbn" required>
                            <span style="opacity:70%">Le numero ISBN doit etre unique</span>
                        </div>

                        <div class="form-group">
                            <label style="font-weight: bold">Prix</label><span style="color:red">*</span>
                            <input type="text" class="form-control" name="prix" required>
                        </div>
                        
                        <!-- bouton Ajouter -->
                        <br>
                        <button type="submit" name="ajout" class="btn btn-info" id="Ajout">Ajouter</button>
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
