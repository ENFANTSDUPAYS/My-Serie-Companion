<?php
$succès = "";
$serie = null;
$categories = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update'])) {
        $id_serie = (int)$_POST['id_serie'];
        $nom_serie = $_POST['nom_serie'];
        $resume_serie = $_POST['resume_serie'];
        $vignette_serie = $_POST['vignette_serie'];
        $dateSortie_serie = $_POST['dateSortie_serie'];
        $episodeActuel_serie = (int)$_POST['episodeActuel_serie'];
        $saisonActuelle_serie = (int)$_POST['saisonActuelle_serie'];
        $terminee_serie = isset($_POST['terminee_serie']) ? 1 : 0;
        $categorie_serie = (int)$_POST['categorie_serie'];

        try {
            $sql_update = "UPDATE serie SET 
                nom_serie = :nom_serie, 
                resume_serie = :resume_serie, 
                vignette_serie = :vignette_serie, 
                dateSortie_serie = :dateSortie_serie, 
                episodeActuel_serie = :episodeActuel_serie, 
                saisonActuelle_serie = :saisonActuelle_serie, 
                terminee_serie = :terminee_serie, 
                categorie_serie = :categorie_serie 
                WHERE id_serie = :id_serie";

            $stmt = $mysqlClient->prepare($sql_update);
            $stmt->bindParam(':nom_serie', $nom_serie);
            $stmt->bindParam(':resume_serie', $resume_serie);
            $stmt->bindParam(':vignette_serie', $vignette_serie);
            $stmt->bindParam(':dateSortie_serie', $dateSortie_serie);
            $stmt->bindParam(':episodeActuel_serie', $episodeActuel_serie, PDO::PARAM_INT);
            $stmt->bindParam(':saisonActuelle_serie', $saisonActuelle_serie, PDO::PARAM_INT);
            $stmt->bindParam(':terminee_serie', $terminee_serie, PDO::PARAM_BOOL);
            $stmt->bindParam(':categorie_serie', $categorie_serie, PDO::PARAM_INT);
            $stmt->bindParam(':id_serie', $id_serie, PDO::PARAM_INT);

            if ($stmt->execute()) {
                $succès = "Série mise à jour avec succès.";
                header('location: ?page=liste');
                exit();
            } else {
                $succès = "Erreur lors de la mise à jour de la série.";
            }
        } catch (PDOException $e) {
            $succès = "Erreur : " . $e->getMessage();
        }

    } elseif (isset($_POST['delete'])) {
        $id_serie = (int)$_POST['id_serie'];

        try {
            $sql_delete = "DELETE FROM serie WHERE id_serie = :id_serie";
            $stmt = $mysqlClient->prepare($sql_delete);
            $stmt->bindParam(':id_serie', $id_serie, PDO::PARAM_INT);

            if ($stmt->execute()) {
                $succès = "Série supprimée avec succès.";
                header("Location: ?page=liste");
                exit();
            } else {
                $succès = "Erreur lors de la suppression de la série.";
            }
        } catch (PDOException $e) {
            $succès = "Erreur : " . $e->getMessage();
        }
    }
} else {
    if (isset($_GET['idserie']) && is_numeric($_GET['idserie'])) {
        $idserie = (int)$_GET['idserie'];
        try{
            $sql_categories = "SELECT id_cat, nom_cat FROM categorie";
            $stmt = $mysqlClient->prepare($sql_categories);
            $stmt->execute();
            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch (PDOException $e) {
            $succès = "Erreur lors de la récupération des catégories : " . $e->getMessage();
        }
        try {
            
            $sql_select = "SELECT * FROM serie WHERE id_serie = :id_serie";
            $stmt = $mysqlClient->prepare($sql_select);
            $stmt->bindParam(':id_serie', $idserie, PDO::PARAM_INT);
            $stmt->execute();
            $serie = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$serie) {
                $succès = "Série non trouvée.";
                exit();
            }
            
        } catch (PDOException $e) {
            $succès = "Erreur : " . $e->getMessage();
            exit();
        }
    } else {
        $succès = "ID de série invalide.";
        exit();
    }
}
?>

<div class="Ajouter_categ">
    <form method="POST" action="" enctype="multipart/form-data">
        <input type="hidden" name="id_serie" value="<?php echo htmlspecialchars($serie['id_serie']); ?>">
        <div class="form-group">
            <label for="nom_serie">Nom de la série :</label>
            <input type="text" id="nom_serie" name="nom_serie" value="<?php echo htmlspecialchars($serie['nom_serie']); ?>" required>
        </div>
        <div class="form-group">
            <label for="resume_serie">Résumé :</label>
            <textarea id="resume_serie" name="resume_serie" required><?php echo htmlspecialchars($serie['resume_serie']); ?></textarea>
        </div>
        <div class="form-group">
            <label for="vignette_serie">Vignette :</label>
            <input type="text" id="vignette_serie" name="vignette_serie" value="<?php echo htmlspecialchars($serie['vignette_serie']); ?>" placeholder="URL de la vignette">
            <?php if (!empty($serie['vignette_serie'])): ?>
            <img src="<?php echo htmlspecialchars($serie['vignette_serie']); ?>" alt="Vignette actuelle" style="max-width: 100px; height: auto; margin-top: 10px;">
            <?php endif; ?>
        </div>
        <div class="form-group">
            <label for="dateSortie_serie">Date de sortie :</label>
            <input type="date" id="dateSortie_serie" name="dateSortie_serie" value="<?php echo htmlspecialchars($serie['dateSortie_serie']); ?>" required>
        </div>
        <div class="form-group">
            <label for="episodeActuel_serie">Épisode actuel :</label>
            <input type="number" id="episodeActuel_serie" name="episodeActuel_serie" value="<?php echo htmlspecialchars($serie['episodeActuel_serie']); ?>" required>
        </div>
        <div class="form-group">
            <label for="saisonActuelle_serie">Saison actuelle :</label>
            <input type="number" id="saisonActuelle_serie" name="saisonActuelle_serie" value="<?php echo htmlspecialchars($serie['saisonActuelle_serie']); ?>" required>
        </div>
        <div class="form-group">
            <label for="terminee_serie">Série terminée :</label>
            <input type="checkbox" id="terminee_serie" name="terminee_serie" <?php echo $serie['terminee_serie'] ? 'checked' : ''; ?>>
        </div>
        <div class="form-group">
            <label for="categorie_serie">Catégorie :</label>
            <select id="categorie_serie" name="categorie_serie" required>
                <option value="" disabled>Choisissez une catégorie</option>
                <?php foreach ($categories as $categorie) : ?>
                    <option value="<?php echo htmlspecialchars($categorie['id_cat']); ?>"
                        <?php if ($categorie['id_cat'] == $serie['categorie_serie']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($categorie['nom_cat']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <button type="submit" name="update">Modifier la série</button>
        </div>
        <div class="form-group">
            <button type="submit" name="delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette série ?');">Supprimer</button>
        </div>
    </form>
    <?= $succès ?>
</div>


