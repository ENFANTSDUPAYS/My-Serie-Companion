<?php
$succès = "";
$categorie = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update'])) {
        $id_cat = (int)$_POST['id_cat'];
        $nom_cat = $_POST['nom_cat'];
        $agemin_cat = (int)$_POST['agemin_cat'];
        $vignette_cat = $_POST['vignette_cat'];
        // MODIFICATION
        try {
            $sql_update = "UPDATE categorie SET nom_cat = :nom_cat, agemin_cat = :agemin_cat, vignette_cat = :vignette_cat WHERE id_cat = :id_cat";
            $stmt = $mysqlClient->prepare($sql_update);
            $stmt->bindParam(':nom_cat', $nom_cat);
            $stmt->bindParam(':agemin_cat', $agemin_cat, PDO::PARAM_INT);
            $stmt->bindParam(':vignette_cat', $vignette_cat);
            $stmt->bindParam(':id_cat', $id_cat, PDO::PARAM_INT);

            if ($stmt->execute()) {
                $succès = "Catégorie mise à jour avec succès.";
                header('location: ?page=liste');
                exit();
            } else {
                $succès = "Erreur lors de la mise à jour de la catégorie.";
            }
        } catch (PDOException $e) {
            $succès = "Erreur : " . $e->getMessage();
        }

    } elseif (isset($_POST['delete'])) {
        $id_cat = (int)$_POST['id_cat'];

        try {
            // Vérifier si la catégorie est utilisée dans une série
            $sql_check = "SELECT COUNT(*) FROM serie WHERE categorie_serie = :id_cat";
            $stmt = $mysqlClient->prepare($sql_check);
            $stmt->bindParam(':id_cat', $id_cat, PDO::PARAM_INT);
            $stmt->execute();
            $NbElement = $stmt->fetchColumn();

            if ($NbElement > 0) {
                // Afficher l'erreur mais permettre l'affichage des infos
                $succès = "<p class='erreur'>Suppression impossible : cette catégorie est liée à au moins une série.</p>";
            } else {
                // Si la catégorie n'est liée à aucune série, on peut la supprimer
                $sql_delete = "DELETE FROM categorie WHERE id_cat = :id_cat";
                $stmt = $mysqlClient->prepare($sql_delete);
                $stmt->bindParam(':id_cat', $id_cat, PDO::PARAM_INT);

                if ($stmt->execute()) {
                    $succès = "Catégorie supprimée avec succès.";
                    header("Location: ?page=liste");
                    exit();
                } else {
                    $succès = "Erreur lors de la suppression de la catégorie.";
                }
            }
        } catch (PDOException $e) {
            $succès = "Erreur : " . $e->getMessage();
        }
    }
} else {
    if (isset($_GET['idcat']) && is_numeric($_GET['idcat'])) {
        $idcat = (int)$_GET['idcat'];
        try {
            // VERIF
            $sql_select = "SELECT id_cat, nom_cat, agemin_cat, vignette_cat FROM categorie WHERE id_cat = :id_cat";
            $stmt = $mysqlClient->prepare($sql_select);
            $stmt->bindParam(':id_cat', $idcat, PDO::PARAM_INT);
            $stmt->execute();
            $categorie = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$categorie) {
                $succès = "Catégorie non trouvée.";
                exit();
            }

            // Vérifier si la catégorie est utilisée dans une série
            $sql_check = "SELECT COUNT(*) FROM serie WHERE categorie_serie = :id_cat";
            $stmt = $mysqlClient->prepare($sql_check);
            $stmt->bindParam(':id_cat', $idcat, PDO::PARAM_INT);
            $stmt->execute();
            $NbElement = $stmt->fetchColumn();

            if ($NbElement > 0) {
                $succès = "<p class='erreur'>Cette catégorie est liée à au moins une série. Suppression impossible.</p>";
            }
        } catch (PDOException $e) {
            $succès = "Erreur : " . $e->getMessage();
            exit();
        }
    } else {
        $succès = "ID de catégorie invalide.";
        exit();
    }
}
?>

<div class="Ajouter_categ">
    <form method="POST" action="" enctype="multipart/form-data">
        <input type="hidden" name="id_cat" value="<?php echo htmlspecialchars($categorie['id_cat']); ?>">
        <div class="form-group">
            <label for="nom_cat">Nom de la catégorie :</label>
            <input type="text" id="nom_cat" name="nom_cat" value="<?php echo htmlspecialchars($categorie['nom_cat']); ?>" required placeholder="Nom de la catégorie">
        </div>
        <div class="form-group">
            <label for="agemin_cat">Âge minimum :</label>
            <input type="number" id="agemin_cat" name="agemin_cat" value="<?php echo htmlspecialchars($categorie['agemin_cat']); ?>" required placeholder="Âge minimum" min="0">
        </div>
        <div class="form-group">
            <label for="vignette_cat">Vignette :</label>
            <input type="text" id="vignette_cat" name="vignette_cat" value="<?php echo htmlspecialchars($categorie['vignette_cat']); ?>" placeholder="URL de la vignette">
            <?php if (!empty($categorie['vignette_cat'])): ?>
            <img src="<?php echo htmlspecialchars($categorie['vignette_cat']); ?>" alt="Vignette actuelle" style="max-width: 100px; height: auto; margin-top: 10px;">
            <?php endif; ?>
        </div>
        <div class="form-group">
            <button type="submit" name="update">Modifier la catégorie</button>
        </div>
        <?php if(!$NbElement > 0 ){ ?>
            <div class="form-group">
                <button type="submit" name="delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?');">Supprimer</button>
            </div>
       <?php } ?>
    </form>
    <?= $succès ?>
</div>
