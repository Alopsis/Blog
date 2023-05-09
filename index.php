<?php
session_start();
if (isset($_POST['deco'])) {
    session_destroy();
    header("Location:./index.php");
    exit();
}
try {


    /* On met dans le blog ici ! */
    $serveur = "localhost";
    $username = "root";
    $password = "#";  /* Mot de passe changé pour github */
    $nom_base_de_donnees = "Blog-site";

    $connexion = new PDO("mysql:host=$serveur;dbname=$nom_base_de_donnees", $username, $password);
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $nom = $_SESSION['nom'];
    if (isset($_POST['creation-blog'])) {

        $requete_createur = "SELECT * FROM membre WHERE nom = '$nom'";
        $reqt = $connexion->query($requete_createur);
        $row = $reqt->fetch();

        $id_createur = $row['id'];
        $titre = $_POST['cr-titre'];

        $requete = "INSERT INTO blog ( id_createur ,titre ) VALUES ( '$id_createur' , '$titre' ) ";
        $reqt = $connexion->query($requete);
        $id_blog = $connexion->lastInsertId();
        $message = $_POST['cr-mes'];
        echo "L'id blog = $id_blog";
        $insere_blog = "INSERT INTO message ( id_blog , id_membre , date_parution ,contenu ) VALUES ( '$id_blog' , '$id_createur' , NOW() , '$message' );";
        $rqt = $connexion->query($insere_blog);
        echo " tout est bon ! ";
        /* fin de la creation */

        header("Location:./index.php");
        exit;
    }
    /* contient tout les titres */
    $requete = "SELECT * from blog ";
    $rqt = $connexion->query($requete);
    while (!empty($row = $rqt->fetch())){
        $titre_indice_blog[] = $row['id'];
        $titre_blog[] = $row['titre'];
    }
    /* fin de tout les titres */

    $requete = "SELECT * FROM message" ;
    $rqt = $connexion->query($requete);
    while(!empty($row = $rqt->fetch())){
        $message_indice_blog[] = $row['id_blog'];
        $message_blog[] = $row['contenu'];
    }
} catch (PDOException $e) {
    echo "Echec de la connexion à la base de données : " . $e->getMessage();
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./index.css">
    <title>Document</title>
</head>

<body>

<!-- Pas besoin de le mettre dans l'integ ! --> 
<?php
    if (isset($_SESSION['nom'])) {
        echo "<form method='post'><button type='submit' name='deco' >vous deconnectez</button></form\n";
    } else {
        echo "<a href='login.php'>vous connecter</a>\n";
    }
    ?>
<!-- -->
<section class="discussion">
    <h1 class="blog-titre">Les discussions</h1>
    <div class="galerie">
    <?php 
        for($i=0;$i<count($titre_blog);$i++){
        echo "<div class='item'><form action='blog.php' method='get'><button type='submit' name='titre' class='blog-input' value='$titre_blog[$i]'>" ;
        echo  $titre_blog[$i] ;
        echo  "</button></form></div><br>";
       

    }
        ?>
    </div>
    </section>
    <section class="creation">
    <div>
        <h4 class="creation-blog-titre">crée un blog</h4>
        <form method="POST" >
            <input type="text" placeholder="sujet" name="cr-titre"class="creation-blog-input sujet ">
            <textarea  placeholder="message" name="cr-mes" maxlength="1000" class="creation-blog-input message"></textarea>
            <button type="submit" name="creation-blog" class="creation-blog-bouton">Submit</button>
        </form>
    </div>
    </section>

</body>

</html>