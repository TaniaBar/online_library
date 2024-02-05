<?php
session_start();
include('includes/config.php');

// Si l'utilisateur est déconnecté
if(strlen($_SESSION['alogin']) == 0) {
      // L'utilisateur est renvoyé vers la page de login : index.php
      header('location:../index.php');
} else {
      if (isset($_POST['supprimer'])) {
            error_log(print_r($_POST, 1));
    
            // On recupere l'identifiant du livre a supprimer
            $idLivre = $_POST['idLivre'];
            error_log(print_r($idLivre, 1));
            // On prepare la requete de suppression
            $deleteBook = "DELETE FROM tblbooks WHERE id=:idLivre";
            $stmt = $conn->prepare($deleteBook);
            $stmt->bindParam(':idLivre', $idLivre);

            // On execute la requete
            if($stmt->execute()) {
                // On informe l'utilisateur du resultat de loperation
                $msg = "Le livre à été supprimé!";
                      echo '<script type="text/javascript">alert("'.$msg.'");</script>';
            } else {
                $msg = "L'action n'as pas fonctionnée. Veuillez réessayer!";
                      echo '<script type="text/javascript">alert("'.$msg.'");</script>';
            };
    
            
      } else if(isset($_POST['edit'])) {
            error_log("Avant redirection vers edit " . print_r($_POST, 1));
            $_SESSION['titre'] = $_POST['titre'];
            $_SESSION['catLivre'] = $_POST['catLivre'];
            $_SESSION['autLivre'] = $_POST['autLivre'];
            $_SESSION['isbn'] = $_POST['isbn'];
            $_SESSION['prix'] = $_POST['prix'];
            $_SESSION['idLivre'] = $_POST['idLivre'];
            
            header('location: edit-book.php');
      }
   
            // On redirige l'utilisateur vers la page manage-books.php
            $affiche = "SELECT tblbooks.BookName, tblcategory.CategoryName, tblauthors.AuthorName, tblbooks.ISBNNumber, tblbooks.BookPrice, tblbooks.id
            FROM tblbooks JOIN tblauthors ON tblauthors.id=tblbooks.AuthorId
            JOIN tblcategory ON tblcategory.id = tblbooks.CatId";
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

    <title>Gestion de bibliothèque en ligne | Gestion livres</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <script>

            function deleteBook() {
                return confirm('Etes vous sur de vouloir supprimer un livre?');
            }

    </script>
</head>
<body>
      <!------MENU SECTION START-->
<?php include('includes/header.php');?>

<!-- On affiche le titre de la page-->
      <div class="container">
            <div class="row">
                  <div class="col">
                  <h3>GESTION DES LIVRES</h3>
                  </div>
            </div>
            <hr>

            <!-- On affiche le formulaire de gestion des categories-->
            <div class="col-xs-12 col-sm-12 col-md-10 offset-md-1 col-lg-12">
                  <!-- <form method="post" action="manage-categories.php"> -->
                  <div class="card border-light mb-3 ">
                        <div class="card-header text-bg-light p-3" style="background-color: lightgrey">
                              <span style="font-weight: bold">Livres</span>
                        </div>
                        <div class="card-body">
                              <table class="table table-striped">
                                    <thead>
                                          <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Titre</th>
                                                <th scope="col">Categorie</th>
                                                <th scope="col">Auteur</th>
                                                <th scope="col">ISBN</th>
                                                <th scope="col">Prix</th>
                                                <th scope="col">Action</th>
                                          </tr>
                                    </thead>
                                    <tbody>

                                          <?php 
                                                $c = 0;
                                                foreach($results as $result) {
                                                
                                                $c++;
                                     
                                          ?>

                                          <tr>
                                                <td scope="row"><?php echo $c ?></td>
                                                <td name="titre"><?php echo $result['BookName'] ?></td>
                                                <td name="catLivre"><?php echo $result['CategoryName'] ?></td>
                                                <td name="autLivre"><?php echo $result['AuthorName'] ?></td>
                                                <td name="isbn"><?php echo $result['ISBNNumber'] ?></td>
                                                <td name="prix"><?php echo $result['BookPrice'] ?></td>
                                                <td name="actionLivre">
                                                      <form method="post" action="manage-books.php">
                                                            <input type="hidden" name="titre" value=" <?php echo $result['BookName'] ?>">
                                                            <input type="hidden" name="idLivre" value=" <?php echo $result['id'] ?>">
                                                            <input type="hidden" name="catLivre" value=" <?php echo $result['CategoryName'] ?>">
                                                            <input type="hidden" name="autLivre" value=" <?php echo $result['AuthorName'] ?>">
                                                            <input type="hidden" name="isbn" value=" <?php echo $result['ISBNNumber'] ?>">
                                                            <input type="hidden" name="prix" value=" <?php echo $result['BookPrice'] ?>">
                                                            <button name="edit" class="btn btn-info" type="submit">Editer</button>&nbsp
                                                      </form>
                                                      
                                                      <form method="post" action="manage-books.php">
                                                            <input type="hidden" name="idLivre" value=" <?php echo $result['id'] ?>">
                                                            <button name="supprimer" class="btn btn-danger" type="submit" onclick="return deleteBook()">Supprimer</button>
                                                      </form>
                                                </td>
                                          </tr>
                                          <?php } ?>
                                    </tbody>
                              </table>
                        </div>
                  </div>
                  <!-- </form> -->
            </div>
      </div>

<?php include('includes/footer.php');?>
      <!-- FOOTER SECTION END-->
     <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
