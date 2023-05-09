<?php 
session_start();
if(isset($_POST['connexion']) || isset($_POST['enregistrement'])){
        $serveur = "localhost";
        $username = "root";
        $password = "#";  /* Mot de passe changé pour github */
        $nom_base_de_donnees = "Blog-site";
    
        try {

            $connexion = new PDO("mysql:host=$serveur;dbname=$nom_base_de_donnees", $username, $password);
            $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Connexion réussie !";
            if(isset($_POST['connexion'])){
                $nom = $_POST['log-nom'];
                $mdp = $_POST['log-mdp'];
                $requete = "SELECT * FROM membre WHERE nom = '$nom' AND password = '$mdp'";
                $rqt = $connexion->query($requete);
                if(!empty($row = $rqt->fetch())){
                    $_SESSION['id'] = $row['id'];
                    $_SESSION['nom'] = $row['nom'];
                    header("Location:./index.php");
                    exit;
                }
            }
            if(isset($_POST['enregistrement'])){
                $nom = $_POST['nom'];
                $mdp = $_POST['mdp'];
                $mail = $_POST['mail'];
                $requete = "SELECT * FROM membre WHERE nom = '$nom' AND password = '$mdp' AND mail = '$mail' ";
                $rqt = $connexion->query($requete);
                $r = $rqt->fetch();
                if(empty($r)){
                    $requete = "INSERT INTO membre ( nom , password , mail) VALUES ( '$nom' , '$mdp' , '$mail' )";
                    $reqt = $connexion->query($requete);
                    echo "test";
                }

            }
        } catch(PDOException $e) {
            echo "Echec de la connexion à la base de données : " . $e->getMessage();

        }
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
    <h4>Sign in</h4>
    <form method="POST" >
        <input type="text" placeholder="nom" name="nom">
        <input type="password" placeholder="mot de passe" name="mdp">
        <input type="mail" placeholder="Mail" name="mail">
        <button type="submit" name="enregistrement">Sumbit</button>
    </form>
    <br>
    <h4>Log in</h4>
    <form method="POST">
        <input type="text" placeholder="nom" name="log-nom">
        <input type="password" placeholder="mot de passe" name="log-mdp">
        <button type="submit" name="connexion">Submit</button>
    </form>
</body>
</html>