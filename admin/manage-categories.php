<?php
// On recupere la session courante
session_start();

// On inclue le fichier de configuration et de connexion a la base de donn�es
include('includes/config.php');

// Si l'utilisateur est déconnecté
if(strlen($_SESSION['alogin']) == 0) {
    // L'utilisateur est renvoyé vers la page de login : index.php
    header('location:../index.php');
} else {

    // if($_SESSION['message']) {
    //     echo '<script type="text/javascript">window.confirm("'.$_SESSION['message'].'");</script>';
    // }
    // Gestion button supprim
    if (isset($_POST['supprim'])) {
        error_log(print_r($_POST, 1));

        // On recupere l'identifiant de la catégorie a supprimer
        $idCat = $_POST['idCat'];
        error_log(print_r($idCat, 1));
        // On prepare la requete de suppression
        $changeStat = "UPDATE tblcategory SET Status=0 WHERE id=:idCat";
        $stmtc = $conn->prepare($changeStat);
        $stmtc->bindParam(':idCat', $idCat);
        
        // On execute la requete
        if($stmtc->execute()) {
            // On informe l'utilisateur du resultat de loperation
            $msg = "la catégorie est maintenant inactive!";
			echo '<script type="text/javascript">alert("'.$msg.'");</script>';
        } else {
            $msg = "la catégorie est encore active. Veuillez réessayer!";
			echo '<script type="text/javascript">alert("'.$msg.'");</script>';
        };

        
    } else if(isset($_POST['edit'])) {
        error_log("Avant redirection vers edit " . print_r($_POST, 1));
        $_SESSION['nameCat'] = $_POST['nameCat'];
        $_SESSION['idCat'] = $_POST['idCat'];
        $_SESSION['statusCat'];
        header('location: edit-category.php');
    }

        // On redirige l'utilisateur vers la page manage-categories.php
        $affiche = "SELECT * FROM tblcategory";
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

    <title>Gestion de bibliothèque en ligne | Gestion categories</title>
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
    <!-- MENU SECTION END-->
    <!-- On affiche le titre de la page-->
    <div class="container">
        <div class="row">
            <div class="col">
                <h3>GESTION DES CATEGORIES</h3>
            </div>
        </div>
        <hr>
        <!-- On prevoit ici une div pour l'affichage des erreurs ou du succes de l'operation de mise a jour ou de suppression d'une categorie-->
        <div>
            <span id="messageCategory"></span>
        </div><br>
        <!-- On affiche le formulaire de gestion des categories-->
        <div class="col-xs-12 col-sm-12 col-md-10 offset-md-1 col-lg-12">
            <!-- <form method="post" action="manage-categories.php"> -->
                <div class="card border-light mb-3 ">
                    <div class="card-header text-bg-light p-3" style="background-color: lightgrey">
                        <span style="font-weight: bold">Categories</span>
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
                                    <td name="nomCat"><?php echo $result['CategoryName'] ?></td>
                                    <td name="statusCat" >
                                        <div name="statusCat" style="<?php echo $style; ?>"><?php echo $statDef ?></div>
                                    </td>
                                    <td name="creationCat"><?php echo $result['CreationDate'] ?></td>
                                    <td name="MiseajourCat"><?php echo $result['UpdationDate'] ?></td>
                                    <td name="actionCat">
                                        <form method="post" action="manage-categories.php">
                                            <input type="hidden" name="nameCat" value=" <?php echo $result['CategoryName'] ?>">
                                            <input type="hidden" name="idCat" value=" <?php echo $result['id'] ?>">
                                            <button name="edit" class="btn btn-info" type="submit">Editer</button>&nbsp
                                        </form>
                                        
                                        <form method="post" action="manage-categories.php">
                                            <input type="hidden" name="idCat" value=" <?php echo $result['id'] ?>">
                                            <button name="supprim" class="btn btn-danger" type="submit">Supprimer</button>
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
    <?php include('includes/footer.php'); ?>
    <!-- FOOTER SECTION END-->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>