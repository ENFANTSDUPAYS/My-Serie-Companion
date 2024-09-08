<?php 
$succès = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nomcateg = $_POST['nom'];
    $agemin = (int)$_POST['age_minimum'];
    //UPLOAD VIGNETTE
    $vignette = $_FILES['vignette'];
    $vignette_name = $vignette['name'];
    $vignette_tmp_name = $vignette['tmp_name'];
    $upload_dir = '../assets/images_download/';
    $upload_file = $upload_dir . basename($vignette_name);
    if (move_uploaded_file($vignette_tmp_name, $upload_file)) {
        try {
            //REQUETE INSERT SQL
            $sql_insert = "INSERT INTO categorie (nom_cat, agemin_cat, vignette_cat) VALUES (:nom_cat, :agemin_cat, :vignette_cat)";
            $stmt = $mysqlClient->prepare($sql_insert);

            //CONTRE INJECTION
            $stmt->bindParam(':nom_cat', $nomcateg);
            $stmt->bindParam(':agemin_cat', $agemin, PDO::PARAM_INT);
            $stmt->bindParam(':vignette_cat', $upload_file);
            //EXECUTION
            if ($stmt->execute()) {
                $succès = "Une nouvelle catégorie appelée : " . htmlspecialchars($nomcateg) . " a été créée avec succès.";
                header('Location: ?page=liste');
                exit();
            } else {
                $succès = "Erreur lors de l'insertion.";
            }
        } catch (PDOException $e) {
            $succès = "Erreur : " . $e->getMessage();
        }
    } else {
        $succès = "Erreur lors de l'upload de la vignette.";
    }
}
?>
<div class="Ajouter_categ">
    <form method="POST" action="#" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nom">Nom de la catégorie :</label>
            <input type="text" id="nom" name="nom" required placeholder="Nom de la catégorie">
        </div>
        <div class="form-group">
            <label for="age_minimum">Âge minimum :</label>
            <input type="number" id="age_minimum" name="age_minimum" required placeholder="Âge minimum" min="0">
        </div>
        <div class="form-group">
            <label for="vignette">Télécharger une vignette :</label>
            <input type="file" id="vignette" name="vignette" accept="image/*" required>
            <div class="file_bouton">
                <img class="Img" src="../assets/ico/Logo_upload.png">
            </div>
        </div>
        <div class="form-group">
            <button type="submit">Ajouter la catégorie</button>
        </div>
    </form>
    <?= $succès ?>
</div>

