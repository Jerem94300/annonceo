<?php
include_once('include/init.php');

// if (!memberConnected()) {
//     header('location:connexion.php');
//     exit();
// }

if ($_POST) {
    // 🔹 Insertion de l'annonce SANS les photos
    $result = $connect_db->prepare("INSERT INTO annonce (title, description_short, description_long, price, category_id, country, city, adress, zipcode, member_id, date) 
    VALUES (:title, :description_short, :description_long, :price, :category_id, :country, :city, :adress, :zipcode, :member_id, NOW())");

    $result->bindValue(':title', $_POST['title'], PDO::PARAM_STR);
    $result->bindValue(':description_short', $_POST['description_short'], PDO::PARAM_STR);
    $result->bindValue(':description_long', $_POST['description_long'], PDO::PARAM_STR);
    $result->bindValue(':price', $_POST['price'], PDO::PARAM_INT);
    $result->bindValue(':category_id', $_POST['category'], PDO::PARAM_INT);
    $result->bindValue(':country', $_POST['country'], PDO::PARAM_STR);
    $result->bindValue(':city', $_POST['city'], PDO::PARAM_STR);
    $result->bindValue(':adress', $_POST['adress'], PDO::PARAM_STR);
    $result->bindValue(':zipcode', $_POST['zipcode'], PDO::PARAM_STR);
    $result->bindValue(':member_id', $_SESSION['member']['id_member'], PDO::PARAM_INT);

    if ($result->execute()) {
        // ✅ Récupération de l'ID de l'annonce insérée
        $id_annonce = $connect_db->lastInsertId();

        // ✅ Redirection vers upload_photos.php avec l'ID de l'annonce
        header("Location: upload_picture.php?id_annonce=$id_annonce");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Erreur lors de l'insertion de l'annonce.</div>";
    }
}
include_once('include/header.php');

?>

<section class="inner_page_head">
    <div class="container_fuild">
      <div class="row">
        <div class="col-md-12">
          <div class="full">
            <h3>Déposer une annonce</h3>
          </div>
        </div>
      </div>
    </div>
</section>

<section class="layout_padding">
    <div class="container">
        <form method="post" action="" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="category" class="form-label d-block">Catégorie</label>
                        <select name="category" class="form-select form-control-sm" id="category" required>
                            <option value="">Choisir une catégorie</option>
                            <option value="1">Véhicules</option>
                            <option value="2">Téléphones</option>
                            <option value="3">Appartements</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Titre</label>
                        <input type="text" name="title" class="form-control" placeholder="Saisir un titre" id="title" required>
                    </div>

                    <div class="mb-3">
                        <label for="description_short" class="form-label">Description Courte</label>
                        <textarea name="description_short" class="form-control" placeholder="Description courte de votre annonce" id="description_short"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="description_long" class="form-label">Description Longue</label>
                        <textarea name="description_long" class="form-control" placeholder="Description longue de votre annonce" id="description_long"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label">Prix</label>
                        <input type="text" name="price" class="form-control" placeholder="Saisir un prix" id="price" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="country" class="form-label d-block">Pays</label>
                        <select name="country" class="form-select form-control-sm" id="country">
                            <option selected>Choisir un pays</option>
                            <option value="France">France</option>
                            <option value="Belgique">Belgique</option>
                            <option value="Suisse">Suisse</option>
                            <option value="Canada">Canada</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="city" class="form-label">Ville</label>
                        <input type="text" name="city" class="form-control" placeholder="Saisir une ville" id="city">
                    </div>

                    <div class="mb-3">
                        <label for="adress" class="form-label">Adresse</label>
                        <input type="text" name="adress" class="form-control" placeholder="Saisir une adresse" id="adress">
                    </div>

                    <div class="mb-3">
                        <label for="zipcode" class="form-label">Code postal</label>
                        <input type="text" name="zipcode" class="form-control" placeholder="Saisir un code postal" id="zipcode">
                    </div>
                </div>
            </div>

            <div class="text-center mt-3">
                <button type="submit" class="btn btn-primary">Enregistrer et ajouter des photos</button>
            </div>
        </form>
    </div>
</section>

<?php
include_once('include/footer.php');
?>
