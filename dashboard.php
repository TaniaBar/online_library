<?php
// On recupere la session courante
session_start();

// On inclue le fichier de configuration et de connexion a la base de donn�es
include('includes/config.php');

if (strlen($_SESSION['rdid']) == 0) {
     // Si l'utilisateur est déconnecté
     // L'utilisateur est renvoyé vers la page de login : index.php
     header('location:index.php');
} else {
     // On récupère l'identifiant du lecteur dans le tableau $_SESSION
     $userId = $_SESSION['rdid'];
     // On veut savoir combien de livres ce lecteur a emprunte
     // On construit la requete permettant de le savoir a partir de la table tblissuedbookdetails
     $recherche = "SELECT COUNT(ReaderID) FROM tblissuedbookdetails WHERE ReaderID=:rdid";
     $stmt = $dbh->prepare($recherche);
     $stmt->bindParam(':rdid', $userId, PDO::PARAM_STR);
     $stmt->execute();
     // On stocke le résultat dans une variable
     $find = $stmt->fetch(PDO::FETCH_ASSOC);
     error_log('fonctionne'); 
     error_log(print_r($find, 1));
     
     // On veut savoir combien de livres ce lecteur n'a pas rendu
     // On construit la requete qui permet de compter combien de livres sont associ�s � ce lecteur avec le ReturnStatus � 0 
     $nonRendus = "SELECT COUNT(ReturnStatus) FROM tblissuedbookdetails WHERE ReaderID=:rdid AND ReturnStatus=0";
     $query = $dbh->prepare($nonRendus);
     $query->bindParam(':rdid', $userId, PDO::PARAM_STR);
     $query->execute();
     // On stocke le résultat dans une variable
     $livresNonRendus = $query->fetch(PDO::FETCH_ASSOC);
     error_log(print_r($livresNonRendus, 1));

?>

     <!DOCTYPE html>
     <html lang="FR">

     <head>
          <meta charset="utf-8" />
          <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
          <title>Gestion de librairie en ligne | Tableau de bord utilisateur</title>
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
          <!-- On affiche le titre de la page : Tableau de bord utilisateur-->
          <div class="container">
               <div class="row">
                    <div class="col">
                         <h3 class="text-start">TABLEAU DE BORD</h3><br>
                    </div>
               </div>
               <hr>
               <br>
               <!-- On affiche la carte des livres emprunt�s par le lecteur-->
               <div class="container text-center">
                    <div class="row">
                         <div class="col">
                              <div class="border border-primary"><br>
                                   <span style="color: blue" class="imgC1">&#9776;</span>
                                   <br><br>
                                   <div style="color: blue" class="nombre"><?php echo $find['COUNT(ReaderID)'] ?></div>
                                   <span style="color: blue">Livres empruntés</span><br>
                                   <br>
                              </div>
                         </div>
                    </div> <br>
                    <!-- On affiche la carte des livres non rendus le lecteur-->
                    <div class="col">
                         <div class="border border-warning"><br>
                              <span class="text-warning imgC2">&#9850;</span>
                              <br><br>
                              <div class="text-warning"><?php echo $livresNonRendus['COUNT(ReturnStatus)'] ?><br>
                                   <span class="text-warning">Livres non encore rendus</span>
                                   <br><br>
                              </div>
                         </div>
                    </div>
               </div>
          </div>
          
          <br>
           <?php 
           include('includes/footer.php'); 
           ?> 
          <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
          <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
     </body>

     </html>
<?php } ?>