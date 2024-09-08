<?php 
$succès = "";
try {
    //REQUETE SQL CATEGORIE
    $sql_select = "SELECT id_cat, nom_cat FROM categorie";
    $stmt = $mysqlClient->prepare($sql_select);
    $stmt->execute();
    //RECUPERATION DES CATEGORIE POUR LE SELECT
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nomSerie = $_POST['nom_serie'];
    $resumeSerie = $_POST['resume_serie'];
    $dateSortieSerie = $_POST['dateSortie_serie'];
    $episodeActuel = (int)$_POST['episodeActuel_serie'];
    $saisonActuelle = (int)$_POST['saisonActuelle_serie'];
    $termineeSerie = (isset($_POST['terminee_serie'])) ? 1 : 0; 
    $categorieSerie = $_POST['categorie_serie'];
    
    // UPLOAD VIGNETTE
    $vignette = $_FILES['vignette_serie'];
    $vignette_name = $vignette['name'];
    $vignette_tmp_name = $vignette['tmp_name'];
    $upload_dir = '../assets/images_download/';
    $upload_file = $upload_dir . basename($vignette_name);
    if (move_uploaded_file($vignette_tmp_name, $upload_file)) {
        try {
            // REQUET INSERT SQL
            $sql_insert ="INSERT INTO serie (nom_serie, resume_serie, vignette_serie, dateSortie_serie, episodeActuel_serie, saisonActuelle_serie, terminee_serie, categorie_serie)
            VALUES (:nom_serie, :resume_serie, :vignette_serie, :dateSortie_serie, :episodeActuel_serie, :saisonActuelle_serie, :terminee_serie, :categorie_serie)";
            $stmt = $mysqlClient->prepare($sql_insert);
            // CONTRE INJECTION
            $stmt->bindParam(':nom_serie', $nomSerie);
            $stmt->bindParam(':resume_serie', $resumeSerie);
            $stmt->bindParam(':vignette_serie', $upload_file);
            $stmt->bindParam(':dateSortie_serie', $dateSortieSerie);
            $stmt->bindParam(':episodeActuel_serie', $episodeActuel, PDO::PARAM_INT);
            $stmt->bindParam(':saisonActuelle_serie', $saisonActuelle, PDO::PARAM_INT);
            $stmt->bindParam(':terminee_serie', $termineeSerie, PDO::PARAM_INT);
            $stmt->bindParam(':categorie_serie', $categorieSerie);

            // EXECUTION
            if ($stmt->execute()) {
                $succès = "Une nouvelle série appelée : " . htmlspecialchars($nomSerie) . " a été créée avec succès.";
                header('Location: ?page=liste_serie');
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
            <label for="nom_serie">Nom de la série :</label>
            <input type="text" id="nom_serie" name="nom_serie" required placeholder="Nom de la série">
        </div>
        <div class="form-group">
            <label for="resume_serie">Résumé de la série :</label>
            <textarea id="resume_serie" name="resume_serie" required placeholder="Résumé de la série"></textarea>
        </div>
        <div class="form-group">
            <label for="dateSortie_serie">Date de sortie :</label>
            <input type="date" id="dateSortie_serie" name="dateSortie_serie" required>
        </div>
        <div class="form-group">
            <label for="episodeActuel_serie">Épisode actuel :</label>
            <input type="number" id="episodeActuel_serie" name="episodeActuel_serie" required placeholder="Épisode actuel" min="1">
        </div>
        <div class="form-group">
            <label for="saisonActuelle_serie">Saison actuelle :</label>
            <input type="number" id="saisonActuelle_serie" name="saisonActuelle_serie" required placeholder="Saison actuelle" min="1">
        </div>
        <div class="form-group">
            <label for="terminee_serie">Série terminée :</label>
            <input type="checkbox" id="terminee_serie" name="terminee_serie">
        </div>
        <div class="form-group">
            <label for="categorie_serie">Catégorie :</label>
            <select id="categorie_serie" name="categorie_serie" required>
                <option value="" disabled selected>Choisissez une catégorie</option>
                <?php if (!empty($categories)) : ?>
                    <?php foreach ($categories as $categorie) : ?>
                        <option value="<?php echo htmlspecialchars($categorie['id_cat']); ?>">
                            <?php echo htmlspecialchars($categorie['nom_cat']); ?>
                        </option>
                    <?php endforeach; ?>
                <?php else : ?>
                    <option value="">Aucune catégorie disponible</option>
                <?php endif; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="vignette_serie">Télécharger une vignette :</label>
            <input type="file" id="vignette_serie" name="vignette_serie" accept="image/*" required>
            <div class="file_bouton2">
                <img class="Img" src="../assets/ico/Logo_upload.png">
            </div>
        </div>
        <div class="form-group">
            <button type="submit">Ajouter la série</button>
        </div>
    </form>
    <?= $succès ?>
</div>


