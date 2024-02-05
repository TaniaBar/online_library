<?php
// On démarre (ou on récupère) la session courante
session_start();
error_log("Admin dashboard : ".print_r($_SESSION, 1));

// On inclue le fichier de configuration et de connexion à la base de données
include('includes/config.php');

// error_log($_SESSION['alogin']);
if (strlen($_SESSION['alogin']) == 0) {
  // Si l'utilisateur est déconnecté
  // L'utilisateur est renvoyé vers la page de login : index.php
  header('location:../index.php');
} else {
    // On récupère le nombre de livres depuis la table tblbooks
    $nombreLivres = "SELECT COUNT(id) FROM tblbooks";
    $stmt = $conn->prepare($nombreLivres);
    $stmt->execute();
    $result = $stmt->fetch();
    
    // On récupère le nombre de livres en prêt depuis la table tblissuedbookdetails
    $livresEmpruntes = "SELECT COUNT(IssuesDate) FROM tblissuedbookdetails";
    $stmte = $conn->prepare($livresEmpruntes);
    $stmte->execute();
    $result1 = $stmte->fetch();

    // On récupère le nombre de livres retournés  depuis la table tblissuedbookdetails
    // Ce sont les livres dont le statut est à 1
    $livresRetournes = "SELECT COUNT(ReturnStatus) FROM tblissuedbookdetails WHERE ReturnStatus=1";
    $stmtr = $conn->prepare($livresRetournes);
    $stmtr->execute();
    $result2 = $stmtr->fetch();

    // On récupère le nombre de lecteurs dans la table tblreaders
    $nombreLecteurs = "SELECT COUNT(id) FROM tblreaders";
    $stmtn = $conn->prepare($nombreLecteurs);
    $stmtn->execute();
    $result3 = $stmtn->fetch();

    // On récupère le nombre d'auteurs dans la table tblauthors
    $nombreAuthors = "SELECT COUNT(id) FROM tblauthors";
    $stmta = $conn->prepare($nombreAuthors);
    $stmta->execute();
    $result4 = $stmta->fetch();

    // On récupère le nombre de catégories dans la table tblcategory
    $categories = "SELECT COUNT(id) FROM tblcategory";
    $stmtc = $conn->prepare($categories);
    $stmtc->execute();
    $result5 = $stmtc->fetch();
?>

  <!DOCTYPE html>
  <html lang="FR">

  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Gestion de bibliothèque en ligne | Tab bord administration</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
  </head>

  <body>
    <!--On inclue ici le menu de navigation includes/header.php-->
    <?php include('includes/header.php'); ?>
    <!-- On affiche le titre de la page : TABLEAU DE BORD ADMINISTRATION-->
    <div class="container">
      <div class="row">
        <div class="col">
          <h3>TABLEAU DE BORD ADMINISTRATION</h3>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-4 col-md-4">
          <!-- On affiche la carte Nombre de livres -->
          <div class="alert alert-succes text-center">
            <span class="fa fa-book fa-5x text-success">
              <h3 class="text-success"><?php echo $result[0]; ?></h3>
            </span><br>
            <span class="text-success">Nombre de livre</span>
          </div>
        </div>
        <div class="col-sm-4 col-md-4">
          <!-- On affiche la carte Livres en pr�t -->
          <div class="alert alert-succes text-center">
            <span class="fa fa-book fa-5x text-primary">
              <h3 class="text-primary"><?php echo $result1[0]; ?></h3>
            </span><br>
            <span class="text-primary">Livres en pret</span>
          </div>
        </div>
        <div class="col-sm-4 col-md-4">
          <!-- On affiche la carte Livres retourn�s -->
          <div class="alert alert-succes text-center">
            <span class="fa fa-bars fa-5x text-primary">
              <h3 class="text-primary"><?php echo $result2[0]; ?></h3>
            </span><br>
            <span class="text-primary">Livres retournés</span>
          </div>
        </div>
        <div class="col-sm-4 col-md-4">
          <!-- On affiche la carte Lecteurs -->
          <div class="alert alert-succes text-center">
            <span class="fa fa-recycle fa-5x text-danger">
              <h3 class="text-danger"><?php echo $result3[0]; ?></h3>
            </span><br>
            <span class="text-danger">Lecteurs</span>
          </div>
        </div>
        <div class="col-sm-4 col-md-4">
          <!-- On affiche la carte Auteurs -->
          <div class="alert alert-succes text-center">
            <span class="fa fa-users fa-5x text-success">
              <h3 class="text-success"><?php echo $result4[0]; ?></h3>
            </span><br>
            <span class="text-success">Auteurs</span>
          </div>
        </div>
        <div class="col-sm-4 col-md-4">
          <!-- On affiche la carte Cat�gories -->
          <div class="alert alert-succes text-center">
            <span class="fa fa-bars fa-5x text-info">
              <h3 class="text-info"><?php echo $result5[0]; ?></h3>
            </span><br>
            <span class="text-info">Catégories</span>
          </div>

        </div>
      </div>
    </div>
    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('includes/footer.php'); ?>
    <!-- FOOTER SECTION END-->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
  </body>

  </html>
<?php } ?>