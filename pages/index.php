<?php 
include_once('../admin/pages/bdd.php');
$page = isset($_GET["page"]) ? $_GET["page"] : 'default';
//CONDITION POUR IMPORTER LES DIFFÉRENTS MORCEAUX DE PAGE
ob_start();

switch ($page) {
    //MENU SLIDE
    case 'liste':
        include_once('liste_categorie.php');
       $title = "Liste catégorie";
        break;
    case 'liste_serie':
        include_once('liste_serie.php');
       $title = "Liste série";
        break;
    case 'ajouter_serie':
        include_once('ajouter_serie.php');
        $title = "Ajouter une série";
        break;
    case 'ajouter_categ':
        include_once('ajout_categ.php');
        $title = "Ajouter une catégorie";
        break;
    case 'modifier_categ':
        include_once('modifier_categ.php');
        $title = "Modifier une catégorie";
        break;
    case 'modifier_serie':
        include_once('modifier_serie.php');
        $title = "Modifier une série";
        break;
    default:
    include_once('liste_serie.php');
    $title = "My Série Companion";
    break;
 }

$content = ob_get_clean();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="icon" href="../assets/ico/Logo_upload.png">
    <title><?php echo $title ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <div class="categ">
            <a href="?index.php"><p class="Catégorie">Mes Series Companion</p></a>&nbsp;&nbsp;&nbsp;
            <a href="?page=liste"><p class="Catégorie">Mes catégories</p></a>&nbsp;&nbsp;&nbsp;
            <a href="?page=ajouter_categ"><p class="Catégorie">Ajouter une catégorie</p></a>&nbsp;&nbsp;&nbsp;
            <a href="?page=ajouter_serie"><p class="Catégorie">Ajouter une série</p></a>&nbsp;&nbsp;&nbsp;
        </div>
        <div class="connect_contain">
            <img class="png_demerde" src="../assets/ico/profil-de-lutilisateur.png" alt="photo profil">&nbsp;&nbsp;&nbsp;
            <div class="profil">
                <p class="Catégorie">Mon profil</p>&nbsp;&nbsp;&nbsp;
            </div>
            <div class="Connexion">
                <p class="Catégorie">Déconnexion</p>
            </div>
        </div>
    </header>
    <section class="Global_container">
        <?php echo $content ?>
    </section>
    <footer></footer>
</body>
</html>