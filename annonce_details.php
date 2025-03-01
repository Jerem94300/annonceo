<?php
require_once('include/init.php');

// Vérification que l'ID de l'annonce est bien passé en GET
if (!isset($_GET['id']) || empty($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Erreur : ID annonce manquant ou invalide.");
}

$id_annonce = (int) $_GET['id'];

// Requête SQL pour récupérer les détails de l'annonce et ses photos associées
$queryAnnonce = $connect_db->prepare("
    SELECT annonce.*, category.title AS category_title, member.pseudo, 
           GROUP_CONCAT(photo.title_photo SEPARATOR ',') AS photos
    FROM annonce 
    INNER JOIN category ON annonce.category_id = category.id_category 
    INNER JOIN member ON annonce.member_id = member.id_member
    LEFT JOIN photo ON annonce.id_annonce = photo.annonce_id
    WHERE annonce.id_annonce = :id_annonce
    GROUP BY annonce.id_annonce
");

$queryAnnonce->bindValue(':id_annonce', $id_annonce, PDO::PARAM_INT);
$queryAnnonce->execute();
$annonce = $queryAnnonce->fetch(PDO::FETCH_ASSOC);

// Vérification si l'annonce existe
if (!$annonce) {
    die("Erreur : L'annonce demandée n'existe pas.");
}

// Gestion des photos (tableau)
$photoArray = !empty($annonce['photos']) ? explode(',', $annonce['photos']) : [];

include_once('include/header.php');
?>

<section class="annonce_details main-content ">
    <div class="container">
        <h2 class="text-center mb-4"><?php echo htmlspecialchars($annonce['title']); ?></h2>

        <div class="row">
            <!-- Affichage des photos en slider Bootstrap -->
            <div class="col-md-6">
                <?php if (!empty($photoArray)) : ?>
                    <div id="carouselAnnonce" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <?php foreach ($photoArray as $index => $photo) : ?>
                                <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                    <img src="assets/images-famma/<?php echo htmlspecialchars($photo); ?>" class="d-block w-100 img-fluid picture_carousel" alt="Photo annonce">
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <a class="carousel-control-prev" href="#carouselAnnonce" role="button" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Précédent</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselAnnonce" role="button" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Suivant</span>
                        </a>
                    </div>
                <?php else : ?>
                    <img src="assets/images-famma/default.jpg" class="d-block w-100 img-fluid" alt="Aucune image disponible">
                <?php endif; ?>
            </div>

            <!-- Détails de l'annonce -->
            <div class="col-md-6">
                <h4>Informations</h4>
                <p><strong>Catégorie :</strong> <?php echo htmlspecialchars($annonce['category_title']); ?></p>
                <p><strong>Prix :</strong> <?php echo number_format($annonce['price'], 2, ',', ' '); ?> €</p>
                <p><strong>Localisation :</strong> <?php echo htmlspecialchars($annonce['city']) . ', ' . htmlspecialchars($annonce['country']); ?></p>
                <p><strong>Publié par :</strong> <?php echo htmlspecialchars($annonce['pseudo']); ?></p>

                <h5>Description :</h5>
                <p><?php echo nl2br(htmlspecialchars($annonce['description_long'])); ?></p>

                <a href="contact_vendeur.php?id=<?php echo htmlspecialchars($annonce['id_annonce']); ?>" class="btn btn-primary">Contacter <?php echo htmlspecialchars($annonce['pseudo']); ?></a>
                <a href="annonce.php" class="btn btn-secondary">Retour aux annonces</a>
            </div>
        </div>
    </div>
</section>

<?php
require_once('include/footer.php');
?>
