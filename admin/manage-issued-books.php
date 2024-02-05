<?php
session_start();

include('includes/config.php');

// Si l'utilisateur est déconnecté
if(strlen($_SESSION['alogin']) == 0) {
    // L'utilisateur est renvoyé vers la page de login : index.php
    header('location:../index.php');
} else {

        
    if(isset($_POST['edit'])) {
        error_log("Avant redirection vers edit " . print_r($_POST, 1));
        $_SESSION['id'] = $_POST['bookId'];
        error_log("Avant redirection vers edit " . print_r($_SESSION, 1));
        header('location: edit-issue-book.php');
    }

    // On redirige l'utilisateur vers la page manage-issued-books.php
    $afficher = "SELECT tblreaders.ReaderId, tblbooks.BookName, tblbooks.ISBNNumber, tblissuedbookdetails.IssuesDate, tblissuedbookdetails.ReturnDate, tblissuedbookdetails.id
            FROM tblbooks JOIN tblissuedbookdetails ON tblbooks.id=tblissuedbookdetails.BookId
            JOIN tblreaders ON tblreaders.ReaderId = tblissuedbookdetails.ReaderID";
            // error_log($afficher);
            $stmt = $conn->prepare($afficher);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);  
}
?>


<!DOCTYPE html>
<html lang="FR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliothèque en ligne | Gestion des sorties</title>
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
        <br>
            <div class="row">
            <div class="col">
                <h3>GESTION DES SORTIES</h3>
            </div>
        </div><br>

        <!-- On affiche le formulaire de gestion des categories-->
        <div class="col-xs-12 col-sm-12 col-md-10 offset-md-1 col-lg-12">
            <!-- <form method="post" action="manage-categories.php"> -->
                <div class="card border-light mb-3 ">
                    <div class="card-header text-bg-light p-3" style="background-color: lightgrey">
                        <span style="font-weight: bold">Sorties</span>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Lecteur</th>
                                    <th scope="col">Titre</th>
                                    <th scope="col">ISBN</th>
                                    <th scope="col">Sortie le</th>
                                    <th scope="col">Retourne le</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php 
                                    $c = 0;
                                    foreach($results as $result) {
                                       
                                        $c++;

                                       $status = $result['ReturnDate'];
                                       if ($status === NULL) {
                                        $statusLabel = "NON RETOURNE";
                                        $style = "color:red";
                                        $disabled = "";
                                       } else {
                                        $statusLabel = $status;
                                        $style = "color:green";
                                        $disabled = "disabled";
                                       }
                                    
                                ?>

                                <tr>
                                    <td scope="row"><?php echo $c ?></td>
                                    <td name="lecteur"><?php echo $result['ReaderId'] ?></td>
                                    <td name="titre"><?php echo $result['BookName'] ?></td>
                                    <td name="isbn"><?php echo $result['ISBNNumber'] ?></td>
                                    <td name="sortie"><?php echo $result['IssuesDate'] ?></td>
                                    <td name="retour" style="<?php echo $style ?>"><?php echo $statusLabel ?></td>
                                    <td name="actionSortie">
                                        <form method="post" action="manage-issued-books.php">
                                            <!-- <input type="hidden" name="nameCat" value=" < echo $result['CategoryName'] ?>"> -->
                                             <input type="hidden" name="bookId" value=" <?php echo $result['id'] ?>"> 
                                            <button name="edit" id="btn-issuedBook-edit"class="btn btn-info" type="submit" <?php echo $disabled ?>>Editer</button>&nbsp
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
     <!-- CONTENT-WRAPPER SECTION END-->
 <?php include('includes/footer.php');?>
      <!-- FOOTER SECTION END-->
     <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>

