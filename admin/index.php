<?php
require_once('../include/init.php');
// echo '<pre>';
// print_r($_SESSION);
// echo '</pre>';

if (!adminConnected()) {
  header('location: '. URL .'index.php');
}

//afficher tous les membres

$members = executeRequete("SELECT * FROM member");

$members = $members->fetchAll(PDO::FETCH_ASSOC);

// echo '<pre>';
// print_r($members);
// echo '</pre>';

//afficher toutes les annonces

$annonces = executeRequete("SELECT * FROM annonce");

$annonces = $annonces->fetchAll(PDO::FETCH_ASSOC);

// echo '<pre>';
// print_r($annonces);
// echo '</pre>';

//Afficher le nombre de membres

$nbMember = executeRequete("SELECT COUNT(*) FROM member WHERE role = 'member'");

$nbMember = $nbMember->fetchAll(PDO::FETCH_ASSOC);


// echo '<pre>';
// print_r($nbMember);
// echo '</pre>';

//Afficher le nombre d'appaartements, de telephones et de vehicules

$nbVehicules = executeRequete("SELECT COUNT(*) FROM annonce WHERE category = 'vehicules'");
$nbVehicules = $nbVehicules->fetchAll(PDO::FETCH_ASSOC);

$nbTelephones = executeRequete("SELECT COUNT(*) FROM annonce WHERE category = 'telephones'");
$nbTelephones = $nbTelephones->fetchAll(PDO::FETCH_ASSOC);

$nbAppartements = executeRequete("SELECT COUNT(*) FROM annonce WHERE category = 'appartements'");
$nbAppartements = $nbAppartements->fetchAll(PDO::FETCH_ASSOC);



//Afficher le montant total des ventes

// $resultatSell = executeRequete("SELECT SUM(price) FROM order_details");

// $montantVentes = $resultatSell->fetchAll(PDO::FETCH_ASSOC);

// echo '<pre>';
// print_r($montantVentes);
// echo '</pre>';


// $dataSell = executeRequete("SELECT*FROM order_details INNER JOIN product ON order_details.product_id = product.id_product");


// $resultatBestSell = $dataSell ->fetchAll(PDO::FETCH_ASSOC);

// echo '<pre>';
// print_r($resultatBestSell);
// echo '</pre>';

//Compter dans $resultatBestSell le nombre de vente pour chaque produit en affichant le nom du produit et la reference du produit

// $bestSell = [];
// foreach ($resultatBestSell as $key => $value) {
//   if(array_key_exists($value['product_id'], $bestSell)){
//     $bestSell[$value['product_id']]['total'] += $value['quantity'];
//   }else{
//     $bestSell[$value['product_id']]['total'] = $value['quantity'];
//     $bestSell[$value['product_id']]['product_id'] = $value['product_id'];
//   }
// }

// echo '<pre>';
// print_r($bestSell);
// echo '</pre>';


// Afficher le renseignement du produit le plus vendu

//max() permet de trouver la valeur la plus élevée dans un tableau
// $bestSell = max($bestSell);

// echo '<pre>';
// print_r($bestSell);
// echo '</pre>';

//Afficher le nom du produit le plus vendu et la reference du produit

// $bestSell = executeRequete("SELECT title, reference FROM product WHERE id_product = $bestSell[product_id]");
// $bestSell = $bestSell->fetchAll(PDO::FETCH_ASSOC);

// echo '<pre>';
// print_r($bestSell);
// echo '</pre>';







require_once('include/header.php');
  ?>
    
    
    <section class="section is-title-bar">
      <div class="level">
        <div class="level-left">
          <div class="level-item">
            <ul>
              <li>Admin</li>
              <li>Dashboard</li>
            </ul>
          </div>
        </div>
      </div>
    </section>
    <section class="hero is-hero-bar">
      <div class="hero-body">
        <div class="level">
          <div class="level-left">
            <div class="level-item">
              <h1 class="title">Dashboard</h1>
            </div>
          </div>
          <div class="level-right" style="display: none">
            <div class="level-item"></div>
          </div>
        </div>
      </div>
    </section>
    <section class="section is-main-section">
      <div class="tile is-ancestor">
        
        <div class="tile is-parent">
          <div class="card tile is-child">
            <div class="card-content">
              <div class="level is-mobile">
                <div class="level-item">
                  <div class="is-widget-label has-text-centered">
                    <h3 class="subtitle is-spaced"> Membres</h3>
                    <h1 class="title mt-2"><?php echo $nbMember[0]['COUNT(*)']?></h1>
                  </div>
                </div>
                <div class="level-item has-widget-icon">
                  <div class="is-widget-icon">
                      <span class="icon has-text-primary is-large"><i class="mdi mdi-account-multiple mdi-48px"></i></span>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="tile is-parent">
          <div class="card tile is-child">
            <div class="card-content">
              <div class="level is-mobile">
                <div class="level-item">
                  <div class="is-widget-label">
                    <h3 class="subtitle is-spaced has-text-centered">Nombre de Véhicules</h3>
                    <h1 class="title mt-2"><?php echo $nbVehicules[0]['COUNT(*)']?> Véhicules</h1>
                  </div>
                </div>
                <div class="level-item has-widget-icon">
                  <div class="is-widget-icon">
                    <span class="icon has-text-info is-large"><i class="mdi mdi-car mdi-48px"></i></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="tile is-parent">
          <div class="card tile is-child">
            <div class="card-content">
              <div class="level is-mobile">
                <div class="level-item">
                  <div class="is-widget-label">
                    <h3 class="subtitle is-spaced has-text-centered">Nombre de Téléphones</h3>
                    <h1 class="title mt-2"><?php echo $nbTelephones[0]['COUNT(*)']?> Téléphones</h1>
                  </div>
                </div>
                <div class="level-item has-widget-icon">
                  <div class="is-widget-icon">
                    <span class="icon has-text-info is-large"><i class="mdi mdi mdi-cellphone mdi-48px"></i></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="tile is-parent">
          <div class="card tile is-child">
            <div class="card-content">
              <div class="level is-mobile">
                <div class="level-item">
                  <div class="is-widget-label">
                    <h3 class="subtitle is-spaced has-text-centered">Nombre d'appartements</h3>
                    <h1 class="title mt-2"><?php echo $nbAppartements[0]['COUNT(*)']?> Appartements</h1>
                  </div>
                </div>
                <div class="level-item has-widget-icon">
                  <div class="is-widget-icon">
                    <span class="icon has-text-info is-large"><i class="mdi mdi-office-building mdi-48px"></i></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>        
      </div>
      <br>
      <br>
      <br>
      <br>

    </section>

    <section class="hero is-hero-bar">
      <div class="hero-body">
        <div class="level">
          <div class="level-left">
            <div class="level-item">
              <h1 class="title">Membres le plus actif</h1>
            </div>
          </div>
          <div class="level-right" style="display: none">
            <div class="level-item"></div>
          </div>
        </div>
      </div>
    </section>

    <div class="tile is-parent">
          <div class="card tile is-child">
            <div class="card-content">
              <div class="level is-mobile">
                <div class="level-item">
                  <div class="is-widget-label has-text-centered">
                    <h3 class="subtitle is-spaced"> Membres</h3>
                    <h1 class="title mt-2"><?php echo $nbMember[0]['COUNT(*)']?></h1>
                  </div>
                </div>
                <div class="level-item has-widget-icon">
                  <div class="is-widget-icon">
                      <span class="icon has-text-primary is-large"><i class="mdi mdi-account-multiple mdi-48px"></i></span>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
    </div>

    <section class="hero is-hero-bar">
      <div class="hero-body">
        <div class="level">
          <div class="level-left">
            <div class="level-item">
              <h1 class="title">Membres le moins actif</h1>
            </div>
          </div>
          <div class="level-right" style="display: none">
            <div class="level-item"></div>
          </div>
        </div>
      </div>
    </section>

    <div class="tile is-parent">
          <div class="card tile is-child">
            <div class="card-content">
              <div class="level is-mobile">
                <div class="level-item">
                  <div class="is-widget-label has-text-centered">
                    <h3 class="subtitle is-spaced"> Membres</h3>
                    <h1 class="title mt-2"><?php echo $nbMember[0]['COUNT(*)']?></h1>
                  </div>
                </div>
                <div class="level-item has-widget-icon">
                  <div class="is-widget-icon">
                      <span class="icon has-text-danger is-large"><i class="mdi mdi-account-multiple mdi-48px"></i></span>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
    </div>


    <section class="hero is-hero-bar">
      <div class="hero-body">
        <div class="level">
          <div class="level-left">
            <div class="level-item">
              <h1 class="title">Dernier Membre enregistré</h1>
            </div>
          </div>
          <div class="level-right" style="display: none">
            <div class="level-item"></div>
          </div>
        </div>
      </div>
    </section>

    <div class="tile is-parent">
          <div class="card tile is-child">
            <div class="card-content">
              <div class="level is-mobile">
                <div class="level-item">
                  <div class="is-widget-label has-text-centered">
                    <h3 class="subtitle is-spaced"> Membres</h3>
                    <h1 class="title mt-2"><?php echo $nbMember[0]['COUNT(*)']?></h1>
                  </div>
                </div>
                <div class="level-item has-widget-icon">
                  <div class="is-widget-icon">
                      <span class="icon has-text-success is-large"><i class="mdi mdi-account-plus mdi-48px"></i></span>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
    </div>
  
  
    <footer class="footer">
      <div class="container-fluid">
        <div class="level">
          <div class="level-left">
            <div class="level-item">© 2025, Jérémy ABELARD</div>
          </div>
        </div>
      </div>
    </footer>
  </div>

  <div id="sample-modal" class="modal">
    <div class="modal-background jb-modal-close"></div>
    <div class="modal-card">
      <header class="modal-card-head">
        <p class="modal-card-title">Confirm action</p>
        <button class="delete jb-modal-close" aria-label="close"></button>
      </header>
      <section class="modal-card-body">
        <p>This will permanently delete <b>Some Object</b></p>
        <p>This is sample modal</p>
      </section>
      <?php
    require_once('include/footer.php');


    require_once('../include/functions.php');
     ?>

