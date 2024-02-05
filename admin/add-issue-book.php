<?php
session_start();

include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {

    header('location:../index.php');
} else {

    if (TRUE === isset($_POST['issue'])) {

        $idReader = $_POST['idReader'];
        $isbn = $_POST['isbn'];

        $issuebook = "INSERT INTO tblissuedbookdetails (ReaderID, BookId) VALUES (:idReader, :isbn)";

        $stmt = $conn->prepare($issuebook);
        $stmt->bindParam(':idReader', $idReader, PDO::PARAM_STR);
        $stmt->bindParam(':isbn', $isbn, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $_SESSION['messageIssueBook'] = "Le livre est sortie avec succès!";
            echo '<script type="text/javascript">window.confirm("' . $_SESSION['messageIssueBook'] . '");</script>';
            header('location: manage-issued-books.php');
        } else {
            $_SESSION['messageIssueBookError'] = "Il y a eu un problème. Veuillez réessayer!";
            echo '<script type="text/javascript">window.confirm("' . $_SESSION['messageIssueBookError'] . '");</script>';
        }

    }
}
?>
<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliotheque en ligne | Ajout de sortie</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <script>
        // On crée une fonction JS pour récuperer le nom du lecteur à partir de son identifiant

        function btnCreateDisabledTrue() {
            const btnCreate = document.getElementById("btn-create");

            btnCreate.style.opacity = "0.25";
            btnCreate.disabled = true;
            btnCreate.style.cursor = "not-allowed";
        }

        function btnCreateDisabledFalse() {
            const btnCreate = document.getElementById("btn-create");

            btnCreate.style.opacity = "1";
            btnCreate.disabled = false;
            btnCreate.style.cursor = "pointer";
        }

        async function nameReaderId(readerId){

        const inputReaderId = document.getElementById("readerId"); 
        const resultReaderId = document.getElementById("result-ReaderId"); 

        await fetch(`get_reader.php?readerId=${readerId}`)
        .then(response => response.json())
        .then(data => {
            if (inputReaderId.value !== "") {
                if (data.response === 'find') { 
                    resultReaderId.innerHTML = "Lecteur : " + data.fullName ;
                    resultReaderId.style.opacity = "70%"; 
                    inputReaderId.style.borderColor = "green";
                    btnCreateDisabledTrue(); 
                } else if (data.response === 'notFind') {
                    resultReaderId.innerHTML = "Lecteur non trouvé"; 
                    inputReaderId.style.borderColor = "red"; 
                    btnCreateDisabledTrue(); 
                }
            } else {
                resultReaderId.innerHTML = "coucou"; 
                resultReaderId.style.opacity = "0%"; 
                inputReaderId.style.borderColor = ""; 
                btnCreateDisabledTrue(); 
            }
        })
        .catch((error) => console.log(error));

        }
        // On crée une fonction JS pour recuperer le titre du livre a partir de son identifiant ISBN
        async function isbnNumber(isbn){
            const inputIsbnNumber = document.getElementById("isbn"); 
            const resultBookName = document.getElementById("bookName"); 

            await fetch(`get_book.php?isbn=${isbn}`)
			.then(response => response.json())
			.then(data => {
				if (inputIsbnNumber.value !== "") {
					if (data.response === 'find') { 
                        resultBookName.innerHTML = "Livre : " + data.bookName ;
                        resultBookName.style.opacity = "70%"; 
                        inputIsbnNumber.style.borderColor = "green";
                        btnCreateDisabledFalse(); 
					} else if (data.response === 'notFind') {
                        resultBookName.innerHTML = "livre non trouvé"; 
                        inputIsbnNumber.style.borderColor = "red"; 
                        btnCreateDisabledTrue(); 
					}
				} else {
					resultBookName.innerHTML = "coucou"; 
                    resultBookName.style.opacity = "0%"; 
                    inputIsbnNumber.style.borderColor = ""; 
                    btnCreateDisabledTrue(); 
				}
			})
			.catch((error) => console.log(error));
        }

    </script>
</head>

<body>
    <!------MENU SECTION START-->
    <?php include('includes/header.php'); ?>
    <!-- MENU SECTION END-->
    <div class="container">
        <br>
        <div class="row">
            <div class="col">
                <h3>SORTIE D'UN LIVRE</h3>
            </div>
        </div><br>
        <div class="col-xs-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3">
            <form method="post" action="add-issue-book.php">
                <div class="card border-info mb-3 ">
                    <div class="card-header text-bg-primary p-3" style="background-color: lightblue">
                        <span style="color: #135cdf">Sortie d'un livre</span>
                    </div>
                    <div class="card-body">
                        <div class="form-group required">
                            <br>
                            <div class="form-group required">
                                <label style="font-weight: bold">identifiant lecteur<span style="color:red">*</span></label>
                                <input type="text" class="form-control" name="idReader" id="readerId" onblur="nameReaderId(this.value)" required>
                                <span id="result-ReaderId" style="opacity:0%">Lecteur</span>
                            </div>
                            <br>
                            <div class="form-group required">
                                <label style="font-weight: bold">ISBN<span style="color:red">*</span></label>
                                <input type="text" class="form-control" name="isbn"id="isbn" onblur="isbnNumber(this.value)" required>
                                <span id="bookName" style="opacity:70%"></span>
                            </div>
                            <br>
                            <div class="text-center">
                                <button id="btn-create" type="submit" name="issue" class="btn btn-info">CRÉER LA SORTIE</button>
                            </div>
                        </div>
                    </div>
            </form>
            <br>
        </div>
    </div>
    <br>
    </div>
    <!-- Dans le formulaire du sortie, on appelle les fonctions JS de recuperation du nom du lecteur et du titre du livre 
 sur evenement onBlur-->

    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('includes/footer.php'); ?>
    <!-- FOOTER SECTION END-->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>