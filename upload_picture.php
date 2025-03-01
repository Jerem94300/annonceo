<?php
include_once('include/init.php');
echo '<pre>'; print_r($_GET); echo '</pre>';
echo '<pre>'; print_r($_POST); echo '</pre>';
echo '<pre>'; print_r($_FILES); echo '</pre>';


// if (!memberConnected()) {
//     header('location:connexion.php');
//     exit();
// }

if (!isset($_GET['id_annonce']) || empty($_GET['id_annonce'])) {
    die("Erreur : ID annonce manquant.");
}

$id_annonce = (int) $_GET['id_annonce'];

if ($_POST) {
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
    $photos = ['photo1', 'photo2', 'photo3', 'photo4'];

    foreach ($photos as $photo) {
        if (!empty($_FILES[$photo]['name'])) {
            // Récupérer l'extension du fichier
            $fileInfo = new SplFileInfo($_FILES[$photo]['name']);
            $fileExtension = strtolower($fileInfo->getExtension());

            // Vérifier si l'extension est autorisée
            if (!in_array($fileExtension, $allowedExtensions)) {
                echo "<div class='alert alert-danger'>L'extension de {$photo} n'est pas autorisée ! (jpg, jpeg, png, webp uniquement)</div>";
                continue;
            }

            // Générer un nom unique pour éviter les doublons
            $photoName = $_SESSION['member']['id_member'] . '_' . time() . '_' . str_replace(' ', '_', $_FILES[$photo]['name']);
            $photoPath = 'assets/images-famma/' . $photoName;

            // Déplacer le fichier uploadé
            if (move_uploaded_file($_FILES[$photo]['tmp_name'], $photoPath)) {
                echo "Upload réussi : " . $photoPath . "<br>";

                if (!move_uploaded_file($_FILES[$photo]['tmp_name'], $photoPath)) {
                    echo "<div class='alert alert-danger'>❌ Échec de la copie du fichier : " . $_FILES[$photo]['name'] . "</div>";
                    echo "Erreur PHP : " . $_FILES[$photo]['error'] . "<br>";
                } else {
                    echo "<div class='alert alert-success'>✅ Fichier copié avec succès : " . $photoPath . "</div>";
                }
                

                // Insertion en base de données
                $photoInsert = $connect_db->prepare("INSERT INTO photo (title_photo, annonce_id) VALUES (:title_photo, :annonce_id)");
                $photoInsert->bindValue(':title_photo', $photoName, PDO::PARAM_STR);
                $photoInsert->bindValue(':annonce_id', $id_annonce, PDO::PARAM_INT);

                if (!$photoInsert->execute()) {
                    echo "<div class='alert alert-danger'>Erreur MySQL : " . implode(" | ", $photoInsert->errorInfo()) . "</div>";
                } else {
                    echo "<div class='alert alert-success'>Photo enregistrée avec succès dans la BDD : " . $photoName . "</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>Erreur lors du déplacement de l'image : " . $_FILES[$photo]['name'] . "</div>";
            }
        }
    }
}

include_once('include/header.php');
?>

<section class="layout_padding">
    <div class="container">
        <h3>Ajouter des photos</h3>
        <form method="post" enctype="multipart/form-data">
            <?php for ($i = 1; $i <= 4; $i++) : ?>
                <div class="mb-3">
                    <label>Photo <?= $i ?></label>
                    <input type="file" name="photo<?= $i ?>" class="form-control">
                </div>
            <?php endfor; ?>

            <button type="submit" name="submit" class="btn btn-primary">Ajouter les photos</button>
        </form>
    </div>
</section>

<?php
include_once('include/footer.php');
?>
