<?php
require_once('include/init.php'); // Connexion PDO

// Vérification des annonces et debug
// echo '<pre>'; print_r($_POST); echo '</pre>';

if (!$connect_db) {
    die("Erreur : Connexion à la base de données non établie.");
}

// Requête SQL pour récupérer toutes les annonces avec les photos associées
$dataAnnonce = $connect_db->query("
    SELECT annonce.*, category.title AS category_title, member.pseudo, 
           GROUP_CONCAT(photo.title_photo SEPARATOR ',') AS photos
    FROM annonce 
    INNER JOIN category ON annonce.category_id = category.id_category 
    INNER JOIN member ON annonce.member_id = member.id_member
    LEFT JOIN photo ON annonce.id_annonce = photo.annonce_id
    GROUP BY annonce.id_annonce
");
$annonces = $dataAnnonce->fetchAll(PDO::FETCH_ASSOC);

include_once('include/header.php'); // Inclure le header
?>

<!-- ✅ Affichage des annonces en HTML -->
<section class="annonces">
    <div class="container">
        <div class="row">
            <?php if (!empty($annonces)): ?>
                <?php foreach($annonces as $annonce) : ?>
                    <?php 
                        // Gestion des photos : on prend la première photo de la liste ou une image par défaut
                        $photoArray = !empty($annonce['photos']) ? explode(',', $annonce['photos']) : [];
                        $photoURL = !empty($photoArray) ? "assets/images-famma/" . htmlspecialchars($photoArray[0]) : "assets/images-famma/default.jpg";
                    ?>

                    <div class="col-12 mb-3">
                        <div class="card mb-3" style="max-width: auto; margin: auto;">
                            <div class="row g-0">
                                <div class="col-md-4 text-center">
                                    <img class="figure-img img-fluid p-1 w-100 photo-annonce " 
                                         src="<?php echo $photoURL; ?>" 
                                         alt="Annonce Image" 
                                         onerror="this.onerror=null; this.src='assets/images-famma/default.jpg';" />
                                </div>
                                <div class="col-md-4">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($annonce['title']); ?></h5>
                                        <p class="card-text">Catégorie : <?php echo strtoupper(htmlspecialchars($annonce['category_title'])); ?></p>
                                        <p class="card-text"><small class="text-body-secondary">Pays : <?php echo strtoupper(htmlspecialchars($annonce['country'])); ?></small></p>
                                        <p class="card-text"><small class="text-body-secondary">Description : <?php echo htmlspecialchars($annonce['description_short']); ?></small></p>
                                    </div>
                                </div>
                                <div class="col-md-4 align-self-center text-center">
                                    <a href="annonce_details.php?id=<?php echo htmlspecialchars($annonce['id_annonce']); ?>" class="btn btn-primary btn-lg">Voir plus</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center">Aucune annonce trouvée.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php
require_once('include/footer.php');
?>
