<?php
include_once('include/init.php');

//print_r($_SESSION);

echo '<pre>'; print_r($_SESSION); echo '</pre>';



//recuperation des annonces de l'utilisateur connecté

$dataAnnonce = $connect_db->prepare("SELECT * FROM annonce WHERE member_id = :id_member");
$dataAnnonce->bindValue(':id_member', $_SESSION['member']['id_member'], PDO::PARAM_INT);
$dataAnnonce->execute();
$annonces = $dataAnnonce->fetchAll(PDO::FETCH_ASSOC);

echo '<pre>'; print_r($annonces); echo '</pre>';

//recuperation des photos de l'annonce de l'utilisateur connecté en utilisant l'id du membre , l'id de annonce et l'id de photo en faisant une jointure des  tables photo et annonce

$dataPhoto = $connect_db->prepare("SELECT title_photo FROM photo INNER JOIN annonce ON photo.annonce_id = annonce.id_annonce WHERE annonce.member_id = :id_member");
$dataPhoto->bindValue(':id_member', $_SESSION['member']['id_member'], PDO::PARAM_INT);
$dataPhoto->execute();
$photos = $dataPhoto->fetchAll(PDO::FETCH_ASSOC);

echo '<pre>'; print_r($photos); echo '</pre>';


//Annonce selectionnée

if(isset($_GET['action']) && $_GET['action'] == 'update'){
    $data = $connect_db->prepare("SELECT * FROM annonce WHERE member_id = :id_member");
    $data->bindValue(':id_member', $_SESSION['member']['id_member'], PDO::PARAM_INT);
    $data->execute();

    $currentAnnonce = $data->fetch(PDO::FETCH_ASSOC);


  echo '<pre>'; print_r($currentProduct); echo'</pre>';
}





include_once('include/header.php');

?>

<section class="inner_page_head">
    <div class="container_fuild">
      <div class="row">
        <div class="col-md-12">
          <div class="full">
            <h3>Mes annonces</h3>
          </div>
        </div>
      </div>
    </div>
</section>


<section class="layout_padding">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h4 class="text-center">Liste de vos annonces</h4>
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead>
                            <tr>
                                <th class="text-center" >Photo</th>
                                <th class="text-center">Titre</th>
                                <th class="text-center">Description</th>
                                <th class="text-center">Prix</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($annonces as $annonce) : ?>
                                    <tr class="align-middle">
                                        <td class="align-middle">
                                            <img src="assets/images-famma/<?= $photos[0]['title_photo'] ?>" alt="<?= $annonce['title'] ?>" class="thumbnail-photo" style="width: 200px;">
                                        </td>
                                        <td class="align-middle text-center"><?= $annonce['title'] ?></td>
                                        <td class="align-middle text-center"><?= $annonce['description_short'] ?></td>
                                        <td class="align-middle text-center"><?= $annonce['price'] ?> €</td>
                                        <td class="align-middle text-center">
                                            <a href="?id=<?= $annonce['id_annonce'] ?>&action=update" class="btn btn-warning">Modifier</a>
                                            <a href="?id=<?= $annonce['id_annonce'] ?>&action=delete" class="btn btn-danger">Supprimer</a>
                                        </td>
                                    </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<?php 
if (isset($_GET['action'], $_GET['id']) && $_GET['action'] === 'update' && is_numeric($_GET['id'])) : ?>



<section class="inner_page_head">
    <div class="container_fuild">
      <div class="row">
        <div class="col-md-12">
          <div class="full">
            <h3>Modification annonce</h3>
          </div>
        </div>
      </div>
    </div>
</section>

<section class="layout_padding">
    <div class="container">
        <form method="post" action="" enctype="multipart/form-data">
            <div class="row text-center">
                <div class="col-md-12">
                  
                    <div class="mb-3">
                        <input type="text" name="title" class="form-control" placeholder="Saisir un titre" id="title" value="<?php if(isset($currentAnnonce['title'])) echo $currentAnnonce['title'];?> ">
                    </div>

                    <div class="mb-3">
                        <label for="description_short" class="form-label">Description Courte</label>
                        <textarea name="description_short" class="form-control" placeholder="Description courte de votre annonce" id="description_short"><?php if(isset($currentAnnonce['description_short'])) echo $currentAnnonce['description_short'];?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="description_long" class="form-label">Description Longue</label>
                        <textarea name="description_long" class="form-control" placeholder="Description longue de votre annonce" id="description_long"><?php if(isset($currentAnnonce['description_long'])) echo $currentAnnonce['description_long'];?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label">Prix</label>
                        <input type="text" name="price" class="form-control" placeholder="Saisir un prix" id="price" value="<?php if(isset($currentAnnonce['price'])) echo $currentAnnonce['price']. ' €';?> "  > 
                    </div>
                </div>




                </div>
            </div>

            <div class="text-center mt-3">
                <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
            </div>
        </form>
    </div>
</section>
<?php endif; ?>











<?php
include_once('include/footer.php');
?>