
    <!DOCTYPE html>
<html>



<head>
  <!-- Basic -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <!-- Mobile Metas -->
  <meta
    name="viewport"
    content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <!-- Site Metas -->
  <meta name="keywords" content="" />
  <meta name="description" content="" />
  <meta name="author" content="" />
  <link rel="shortcut icon" href="assets/images-famma/logo_annonceo.png" type="" />
  <title>Annonceo</title>
  <!-- bootstrap core css -->
  <link
    rel="stylesheet"
    type="text/css"
    href="assets/css-famma/bootstrap.css" />

    <!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- font awesome style -->
  <link href="assets/css-famma/font-awesome.min.css" rel="stylesheet" />
  <!-- Custom styles for this template -->
  <link href="assets/css-famma/style.css" rel="stylesheet" />
  <!-- responsive style -->
  <link href="assets/css-famma/responsive.css" rel="stylesheet" />

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>



    <header class="header_section">
      <div class="container">
        <nav class="navbar navbar-expand-lg custom_nav-container">
          <a class="navbar-brand" href="index.php"><img class="logo_header" width="150" src="assets/images-famma/logo_annonceo.png" alt="logo annonceo" /></a>
          <button
            class="navbar-toggler"
            type="button"
            data-toggle="collapse"
            data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent"
            aria-expanded="false"
            aria-label="Toggle navigation">
            <span class=""> </span>
          </button>
           
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav">
            <?php if (!memberConnected() && !adminConnected()) : ?>
              <li class="nav-item">
                <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
              </li>

              <li class="nav-item ">
                <a class="nav-link" href="connexion.php">Identifiez-vous</a>
              </li>
              <li class="nav-item ">
                <a class="nav-link" href="inscription.php">Inscription</a>
              </li>
              <? endif; ?>

              <?php if (memberConnected() || adminConnected()) : ?>
                
              <li class="nav-item">
                <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
              </li>

              <li class="nav-item">
                <a class="nav-link" href="profil.php">Mon compte</a>
              </li>

              <li class="nav-item">
                <a class="nav-link" href="drop_annonce.php">Déposer une annonce</a>
              </li>


              <li class="nav-item">
                <a class="nav-link" href="connexion.php?action=logout">Déconnexion</a>
              </li>

              <li class="nav-item">
                <a class="nav-link" href="connexion.php?action=logout">Mes annonces</a>
              </li>


            
              

              <?php if (adminConnected()) : ?>
                <li class="nav-item dropdown">
                <a
                  class="nav-link dropdown-toggle"
                  href="#"
                  data-toggle="dropdown"
                  role="button"
                  aria-haspopup="true"
                  aria-expanded="true">
                  <span class="nav-label">BackOffice</span>&nbsp&nbsp&nbsp</a>
                <ul class="dropdown-menu">
                  <li><a href="admin/index.php">Dashboard</a></li>
                  <li><a href="admin/gestion_annonces.php">Annonces</a></li>
                  <li><a href="admin/gestion_commande.php">Commandes</a></li>
                  <li><a href="admin/gestion_member.php">Utilisateurs</a></li>
                </ul>
              </li>

              <? endif; ?>
             

           



       
            </ul>
            

          </div>
          <div class="h2 block_member text-end ml-5">
                <i class="fa fa-user-circle user-icon"></i>
                <a href="profil.php" class="link_member">
                <span class="span_member"><?php echo ucfirst($_SESSION['member']['pseudo']); ?></span></a>
          </div>
          <? endif; ?>
        </nav>
      </div>
    </header>