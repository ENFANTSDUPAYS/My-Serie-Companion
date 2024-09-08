<?php 
try{
    //REQUETE SQL
    $sql_select = "SELECT id_serie, nom_serie, resume_serie, vignette_serie, dateSortie_serie, episodeActuel_serie, saisonActuelle_serie, terminee_serie, categorie_serie FROM serie";
    $stmt = $mysqlClient->prepare($sql_select);
    $stmt->execute();
    //RECUPERATION RESULTAT
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Erreur : " . $e->getMessage();
}
?>
<div class="table-container">
    <div class="ligne">
        <p class="title_categ">Mes séries</p>
    </div>
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>vignette</th>
                    <th>Date de sortie</th>
                    <th>Episode actuelle</th>
                    <th>Saison actuelle</th>
                    <th>Terminée</th>
                    <th>Catégorie</th>
                    <th>Détails</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!empty($categories)) : ?>
                <?php foreach ($categories as $categorie) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($categorie['nom_serie']); ?></td>
                        <td><?php echo htmlspecialchars($categorie['resume_serie']); ?></td>
                        <td>
                            <?php if (!empty($categorie['vignette_serie'])) : ?>
                                <img src="<?php echo htmlspecialchars($categorie['vignette_serie']); ?>" alt="Vignette" style="width: 50px; height: auto;">
                            <?php else : ?>
                                Pas de vignette
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($categorie['dateSortie_serie']); ?></td>
                        <td><?php echo htmlspecialchars($categorie['episodeActuel_serie']); ?></td>
                        <td><?php echo htmlspecialchars($categorie['saisonActuelle_serie']); ?></td>
                        <td><?php echo htmlspecialchars($categorie['terminee_serie']); ?></td>
                        <td><?php echo htmlspecialchars($categorie['categorie_serie']); ?></td>
                        <td>
                            <a href="?page=modifier_serie&idserie=<?php echo urlencode($categorie['id_serie']); ?>">
                                <p class="Bouton_modifsupp">Détails</p>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="3">Aucune catégorie trouvée</td>
                </tr>
            <?php endif; ?>
        </tbody>
        </table>
    </div>