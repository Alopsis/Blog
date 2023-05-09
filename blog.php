<?php
/* NE RENVOIE PAS LE TITRE A VOIR PQ ! */
session_start();

try {
    $serveur = "localhost";
    $username = "root";
    $password = "#";  /* Mot de passe changé pour github */
    $nom_base_de_donnees = "Blog-site";
    $connexion = new PDO("mysql:host=$serveur;dbname=$nom_base_de_donnees", $username, $password);
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $titre = $_GET['titre'];
    $requete = "SELECT * FROM blog WHERE titre = '$titre'";
    $rqt = $connexion->query($requete);
    $row = $rqt->fetch();
    $id_blog = $row['id'];
    $id_membre = $_SESSION['id'];    
    if(isset($_GET['new_mess'])){
        $contenu = $_GET['nouveau_message'];
        $requete = "INSERT INTO message ( id_blog  , id_membre , date_parution , contenu ) VALUES ( '$id_blog' , '$id_membre' , NOW(), '$contenu' )";
        $rqt = $connexion->query($requete);
    }
    $requete = "SELECT * FROM message WHERE id_blog = '$id_blog'";
    $rqt = $connexion->query($requete);
    while(!(empty($row = $rqt->fetch()))){
        $auteur_id[] = $row['id_membre'];
        $message[] = $row['contenu'];
    }
    /* on recupere les noms ! */
    for($i=0;$i<count($auteur_id);$i++){
        $requete = "SELECT * FROM compte WHERE id = '$auteur_id[$i]'";
        $rqt = $connexion->query($requete);
        $row = $rqt->fetch();
        $auteur[] = $row['nom'];
        echo "boucle";
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
    <title>Document</title>
</head>

<body>

    <?php 
    include("header.php");
    ?>
    <?php 
    echo "<h4> $titre </h4>";
    for($i=0;$i<count($message);$i++){
        echo "<p>$auteur[$i] </p>";
        echo "<p>$message[$i] </p>";
    }
    ?>

    <form method="GET" action="./blog.php">
        <input type="text" name="nouveau_message" placeholder="Nouveau message" required>
        <input type="hidden" name="titre" value="<?php echo $titre; ?>">
        <button type="submit" name="new_mess" >Nouveau message </button>
    </form>


    <?php 
    include("footer.php");
    ?>
</body>

</html>