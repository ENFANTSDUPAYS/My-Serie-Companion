<?php 
try {
    // REQUETE SQL
    $sql_select = "SELECT id_cat, nom_cat, agemin_cat, vignette_cat FROM categorie";
    $stmt = $mysqlClient->prepare($sql_select);
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Erreur : " . $e->getMessage();
}
?>
<div class="table-container">
    <div class="ligne">
        <p class="title_categ">Mes catégories</p>
    </div>
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Âge Minimum</th>
                <th>Vignette</th>
                <th>Détails</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($categories)) : ?>
                <?php foreach ($categories as $categorie) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($categorie['nom_cat']); ?></td>
                        <td><?php echo htmlspecialchars($categorie['agemin_cat']); ?></td>
                        <td>
                            <?php if (!empty($categorie['vignette_cat'])) : ?>
                                <img src="<?php echo htmlspecialchars($categorie['vignette_cat']); ?>" alt="Vignette" style="width: 50px; height: auto;">
                            <?php else : ?>
                                Pas de vignette
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="?page=modifier_categ&idcat=<?php echo urlencode($categorie['id_cat']); ?>">
                                <p class="Bouton_modifsupp">Détails</p>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="5">Aucune catégorie trouvée</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
