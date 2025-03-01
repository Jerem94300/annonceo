<?php
include_once('include/init.php');

// echo '<pre>'; print_r($_POST); echo '</pre>';


// echo '<pre>'; print_r($_SESSION); echo '</pre>';

//recuperer les annonces

$dataAnnonce = $connect_db->query("SELECT annonce.*, category.title, member.pseudo  FROM annonce INNER JOIN category ON annonce.category_id = category.id_category INNER JOIN member ON annonce.member_id = member.id_member");
$annonces = $dataAnnonce->fetchAll(PDO::FETCH_ASSOC);

//  echo '<pre>'; print_r($annonces); echo '</pre>';

//recuperer les 3 dernières annonces

$selectNewdata = $connect_db->query("
    SELECT a.*, c.title, m.pseudo, p.title_photo 
    FROM annonce a
    INNER JOIN category c ON a.category_id = c.id_category
    INNER JOIN member m ON a.member_id = m.id_member
    LEFT JOIN photo p ON a.id_annonce = p.annonce_id
    GROUP BY a.id_annonce
    ORDER BY a.id_annonce DESC
    LIMIT 3
");
$newAnnonces = $selectNewdata->fetchAll(PDO::FETCH_ASSOC);


// echo '<pre>'; print_r($annonces); echo '</pre>';
// echo '<pre>'; print_r($newAnnonces); echo '</pre>';

//recuperer tous les membres

$dataMembre = $connect_db->query("SELECT * FROM member WHERE role = 'member'");
$membres = $dataMembre->fetchAll(PDO::FETCH_ASSOC);

// echo '<pre>'; print_r($membres); echo '</pre>';



if (isset($_POST['submit']) && $_SERVER['REQUEST_METHOD']=== 'POST') {

}


include_once('include/header.php');

?>

<section class="inner_page_head">
    <div class="container_fuild">
      <div class="row">
        <div class="col-md-12">
          <div class="full">
            <h3>Recherchez une annonce</h3>
          </div>
        </div>
      </div>
    </div>
  </section>

<section class="arrival_section layout_padding"> 
    <div class="container p-0">
        <div class="content_annonce d-flex justify-content-between">
            <div class="left_block">
            <form class="form" method="post" action="annonce.php">
                <div class="heading_container remove_line_bt d-flex align-items-center">
                    <h2>Recherche</h2>
                </div>
            
                <input class="form-control" type="text" placeholder="Recherche">
                <hr>
            <div class="heading_container remove_line_bt d-flex ">
                    <h2>Catégorie</h2>
                </div>
                    <select class="form-select-lg mb-3" name="category">
                        <option selected value="vehicule">Véhicules</option>
                        <option value="telephone">Téléphones</option>
                        <option value="appartements">Appartements</option>
                    </select> 
                    <div class="heading_container remove_line_bt  mt-4">
                    <h2>Pays</h2>
                    </div>
                    <select class="form-select select is-info" name="country">
                        <option selected value="france">France</option>
                        <option value="belgique">Belgique</option>
                        <option value="suisse">Suisse</option>
                        <option value="canada">Canada</option>
                    </select>  
                    <div class="heading_container remove_line_bt  mt-4">
                    <h2>Membres</h2>
                    </div>
                
                    <select class=" select is-info" name="member">
                    <?php foreach($membres as $membre) : ?>
                        <option selected value="<?php echo $membre['pseudo'];?>"><?php echo $membre['pseudo'];?></option>

                        <?php endforeach; ?>  
                    </select>
                    <hr>

                    <!-- Ajout d'un bouton submit -->
                    
                    <button type="submit" class="btn btn-primary mt-4">Rechercher</button>
                </form>
                
            </div>

            <?php foreach($newAnnonces as $annonce) : ?>
            <div class="bloc_right w-100 d-flex flex-column justify-content-between ml-4">
                <div class="details d-flex">
                    <div class="picture_detail"></div>
                    <img class="figure-img img-fluid p-1 w-50 ml-4 thumbnail-photo " src="assets/images-famma/<?php echo $annonce['title_photo'] ?? 'default.jpg'; ?>" alt="Image annonce" />
                    <div class="paragraph_details w-75 ml-4">
                        <h2><?php echo $annonce['title'] ;?></h2>
                        <h5><?php echo $annonce['description_short'] ;?></h5>
                        <p class="paragraph__content"><?php echo $annonce['price'] ;?> €</p>
                    </div>
                </div>
                <?php endforeach; ?>

                <a href="annonce.php" class="btn btn-success btn-lg">Voir toutes les annonces</a>

            </div>
        </div>

      
    </div>
  </section>



<?php


include_once('include/footer.php');

?>