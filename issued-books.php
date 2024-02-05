<?php
// On récupère la session courante
session_start();
// On inclue le fichier de configuration et de connexion à la base de données
include('includes/config.php');
// Si l'utilisateur n'est pas connecte, on le dirige vers la page de login
if (strlen($_SESSION['rdid']) == 0) {
    header('location:index.php');
    // Sinon on peut continuer
} else {
    $userId = $_SESSION['rdid'];

    // jointure entre tblissuedbookdetails et tblbooks
    $jointure = "SELECT tblissuedbookdetails.IssuesDate, tblissuedbookdetails.ReturnDate, tblbooks.ISBNNumber, tblbooks.BookName
    FROM tblissuedbookdetails JOIN tblbooks ON tblissuedbookdetails.BookId = tblbooks.ISBNNumber WHERE ReaderId=:rdid";
    $stmtj = $dbh->prepare($jointure);
    $stmtj->bindParam(':rdid', $userId, PDO::PARAM_STR);
    $stmtj->execute();
    $resultats = $stmtj->fetchAll(PDO::FETCH_ASSOC);
    // error_log($jointure);
    // error_log(print_r($resultats, 1));   
    
}

?>

<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliotheque en ligne | Gestion des livres</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
</head>

<body>
    <!--On insere ici le menu de navigation -->
    <?php include('includes/header.php'); ?>
    <!-- On affiche le titre de la page : LIVRES SORTIS -->
    <div class="col-xs-12 col-sm-10 offset-sm-1 col-md-10 offset-md-1 col-lg-10 offset-lg-1">
        <div class="container">
               <div class="row">
                    <div class="col">
                         <h3 class="text-start">LIVRES EMPRUNTES</h3><br>
                    </div>
               </div>
               <hr>
        <!-- On affiche la liste des sorties contenus dans $results sous la forme d'un tableau -->
        <!-- Si il n'y a pas de date de retour, on affiche non retourne -->
            <table class="table table-primary table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">TITRE</th>
                        <th scope="col">ISBN</th>
                        <th scope="col">DATE DE SORTIE</th>
                        <th scope="col">DATE DE RETOUR</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $y = 0;
                    foreach($resultats as $resultat) { 
                        $retour = $resultat['ReturnDate'];
                        $y++;
                        
                        if ($retour === NULL) {
                          $retour1 = "Non Retourné";  
                          $style = "color:red;";  
                        } else {
                          $retour1 = $retour;
                          $style = "color:green;"; 
                        }
                    
                        ?>
                    <tr>
                        <td scope="row"><?php echo $y ?></td>
                        <td name="bookname"><?php echo $resultat['BookName'] ?></td>
                        <td name="isbn"><?php echo $resultat['ISBNNumber'] ?></td>
                        <td name="dateSor"><?php echo $resultat['IssuesDate'] ?></td>
                        <!-- <td name="dateRet">< echo $resultat['ReturnDate'] ?></td>      -->
                        <td name="dateRet" style="<?php echo $style; ?>"><?php echo $retour1 ?></td>
                        <!-- <td name="dateRet">< echo $retour ?></td> -->
                    </tr>
                   <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php include('includes/footer.php'); ?>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>