<?php
require_once('include/init.php');

// echo  '<pre>'; print_r($_SESSION); echo'</pre>';

if(!userConnected()){
  header('location:index.php');

}




// exo créer une fiche utilisateur qui regroupe les informations personnelles de l'utilisateur en passant par le fichier session




require_once('include/header.php');




?>
    <!-- end header section -->
  <!-- inner page section -->
  <section class="inner_page_head">
    <div class="container_fuild">
      <div class="row">
        <div class="col-md-12">
          <div class="full">
            <h3>Mes informations personnelles</h3>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- end inner page section -->
  <!-- why section -->
  <section class="layout_padding">
    <div class="container">
      <div class="row">
        <div class="col-lg-8 offset-lg-2">
          <div class="full ">

          <div class="card border-primary bg-primary text-white  mb-3 text-center">

          <div class="card-header d-flex bg-primary text-white text-center justify-content-between">
              <h4 class="card-title">Pseudo :</h4>
              <p> <?php echo $_SESSION['member']['pseudo'] ?></p>
            </div>
            <div class="card-header d-flex bg-primary text-white text-center justify-content-between">
              <h4 class="card-title">Civilité :</h4>
              <p> <?php echo $_SESSION['member']['civilite'] ?></p>
            </div>
            <div class="card-header d-flex bg-primary text-white text-center justify-content-between">
              <h4 class="card-title">Prénom :</h4>
              <p> <?php echo $_SESSION['member']['firstName'] ?></p>
            </div>
            <div class="card-header d-flex bg-primary text-white text-center justify-content-between">
              <h4 class="card-title">Numéro de téléphone :</h4>
              <p> <?php echo $_SESSION['member']['phone_number'] ?></p>
            </div>
            <div class="card-header d-flex bg-primary text-white text-center justify-content-between">
              <h4 class="card-title">Email :</h4>
              <p> <?php echo $_SESSION['member']['email'] ?></p>
            </div>

            <?php if(memberConnected()):?>
              <p> <?php echo "Vous êtes membre"?></p>

              <?php endif; ?>
          </div>



          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- end why section -->
  <!-- arrival section -->
  <!-- end arrival section -->
  <!-- footer section -->
  <?php
require_once('include/footer.php');

?>

</body>

</html>