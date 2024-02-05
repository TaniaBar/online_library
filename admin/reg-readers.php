<?php
// On démarre ou on récupère la session courante
session_start();

// On inclue le fichier de configuration et de connexion à la base de données
include('includes/config.php');

// Si l'utilisateur n'est logué ($_SESSION['alogin'] est vide)
if(strlen($_SESSION['alogin']) == 0) {
    // On le redirige vers la page d'accueil
    header('location:../index.php');
} else {
    
    // Lors d'un click sur un bouton "inactif", on récupère la valeur de l'identifiant
    // du lecteur dans le tableau $_GET['inid']
    // et on met à jour le statut (0) dans la table tblreaders pour cet identifiant de lecteur
    if (isset($_GET['inactif'])) {
        error_log(print_r($_GET, 1));
        $id = $_GET['idLec'];

        $inactifStatus = "UPDATE tblreaders SET Status=0 WHERE id=:idLec";
        $stmti = $conn->prepare($inactifStatus);
        $stmti->bindParam(':idLec', $id);
        
        if($stmti->execute()) {
            // On informe l'utilisateur du resultat de loperation
            $msg = "Le lecteur est maintenant inactif!";
			echo '<script type="text/javascript">alert("'.$msg.'");</script>';
        } else {
            $msg = "Le lecteur est encore actif. Veuillez réessayer!";
			echo '<script type="text/javascript">alert("'.$msg.'");</script>';
        };
    }

    // Lors d'un click sur un bouton "actif", on récupère la valeur de l'identifiant
    // du lecteur dans le tableau $_GET['id']
    // et on met à jour le statut (1) dans  table tblreaders pour cet identifiant de lecteur
    if (isset($_GET['actif'])) {
        error_log(print_r($_GET, 1));
        $id = $_GET['idLec'];

        $actifStatus = "UPDATE tblreaders SET Status=1 WHERE id=:idLec";
        $stmti = $conn->prepare($actifStatus);
        $stmti->bindParam(':idLec', $id);
        
        if($stmti->execute()) {
            // On informe l'utilisateur du resultat de loperation
            $msg = "Le lecteur est maintenant actif!";
			echo '<script type="text/javascript">alert("'.$msg.'");</script>';
        } else {
            $msg = "Le lecteur est encore inactif. Veuillez réessayer!";
			echo '<script type="text/javascript">alert("'.$msg.'");</script>';
        };
    }

    // Lors d'un click sur un bouton "supprimer", on récupère la valeur de l'identifiant
    // du lecteur dans le tableau $_GET['del']
    // et on met à jour le statut (2) dans la table tblreaders pour cet identifiant de lecteur
    if (isset($_GET['supprim'])) {
        error_log(print_r($_GET, 1));
        $id = $_GET['idLec'];

        $statusSup = "UPDATE tblreaders SET Status=2 WHERE id=:idLec";
        $stmts = $conn->prepare($statusSup);
        $stmts->bindParam(':idLec', $id);
        if($stmts->execute()) {
            // On informe l'utilisateur du resultat de loperation
            $msg = "Le lecteur a été supprimé!";
			echo '<script type="text/javascript">alert("'.$msg.'");</script>';
        } else {
            $msg = "L'operation a échouée. Veuillez réessayer!";
			echo '<script type="text/javascript">alert("'.$msg.'");</script>';
        };
    }

    // Sinon on affiche la liste des lecteurs de la table tblreaders
    $affichage = "SELECT * FROM tblreaders";
    $stmt = $conn->prepare($affichage);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Gestion de bibliothèque en ligne | Reg lecteurs</title>
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
    <!-- Titre de la page (Gestion du Registre des lecteurs) -->
    <div class="container">
        <div class="row">
            <div class="col">
                <h3>GESTION DU REGISTRE DES LECTEURS</h3>
            </div>
        </div>
        <hr>
        <!--On insère ici le tableau des lecteurs. -->
        <div class="col-xs-12 col-sm-12 col-md-10 col-lg-12">
            <!-- <form method="post" action="reg-readers.php"> -->
            <div class="card border-light mb-3 ">
                    <div class="card-header text-bg-light p-3" style="background-color: lightgrey">
                        <span style="font-weight: bold">Registre Lecteurs</span>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">ID Lecteur</th>
                                    <th scope="col">Nom</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Portable</th>
                                    <th scope="col">Date de reg</th>
                                    <th scope="col">Status</th>
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
                                        $stylebtnOff = "display: none";
                                        $stylebtnOn = "display: block";
                                        $stylebtnSupprim = "display: block";
                                    } else if ($status === 1){
                                        $statDef = "Active";
                                        $style = "background-color: #49bf65; color: white; padding-left: 5px; border-radius: 5px";
                                        $stylebtnOff = "display: block";
                                        $stylebtnOn = "display: none";
                                        $stylebtnSupprim = "display: block";
                                    } else if ($status === 2) {
                                        $statDef = "Supprimé";
                                        $style = "background-color: #bf0628; color: white; padding-left: 2px; border-radius: 5px";
                                        $stylebtnOff = "display: none";
                                        $stylebtnOn = "display: none";
                                        $stylebtnSupprim = "display: none";
                                    }
                                    
                                ?>

                                <tr>
                                    <td scope="row"><?php echo $c ?></td>
                                    <td name="idLecteur"><?php echo $result['ReaderId'] ?></td>
                                    <td name="nomLecteur"><?php echo $result['FullName'] ?></td>
                                    <td name="emailLecteur"><?php echo $result['EmailId'] ?></td>
                                    <td name="numLecteur"><?php echo $result['MobileNumber'] ?></td>
                                    <td name="dateRegLecteur"><?php echo $result['RegDate'] ?></td>
                                    <td name="statusLecteur" >
                                        <div name="statusLecteur" style="<?php echo $style; ?>"><?php echo $statDef ?></div>
                                    </td>
                                    <!--On gère l'affichage des boutons Actif/Inactif/Supprimer en fonction de la valeur du statut du lecteur -->
                                    <td name="actionLecteur">
                                        <form method="get" action="reg-readers.php">
                                            <!-- <input type="hidden" name="nomLecteur" value=" < echo $result['FullName'] ?>"> -->
                                            <input type="hidden" name="idLec" value=" <?php echo $result['id'] ?>">
                                            <button name="actif" class="btn btn-info" type="submit" id="btn-actif" style="<?php echo $stylebtnOn ?>">Actif</button>
                                            <button name="inactif" class="btn btn-info" type="submit" id="btn-inactif" style="<?php echo $stylebtnOff ?>">Inactif</button>
                                        </form>
                                        
                                        <form method="get" action="reg-readers.php">
                                            <input type="hidden" name="idLec" value=" <?php echo $result['id'] ?>">
                                            <button name="supprim" class="btn btn-danger" type="submit" style="<?php echo $stylebtnSupprim ?>">Supprimer</button>
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