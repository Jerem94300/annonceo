<?php
  $page = basename($_SERVER['PHP_SELF']);


  ?>
<!DOCTYPE html>
<html
  lang="en"
  class="has-aside-left has-aside-mobile-transition has-navbar-fixed-top has-aside-expanded">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Annonceo Admin Dashboard</title>

  <!-- Bulma is included -->
  <link rel="stylesheet" href="../assets/css/main.min.css" />

  <!-- Fonts -->
  <link rel="dns-prefetch" href="https://fonts.gstatic.com" />
  <link
    href="https://fonts.googleapis.com/css?family=Nunito"
    rel="stylesheet"
    type="text/css" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
  <div id="app">
    <nav id="navbar-main" class="navbar is-fixed-top">
      <div class="navbar-brand">
        <a class="navbar-item is-hidden-desktop jb-aside-mobile-toggle">
          <span class="icon"><i class="mdi mdi-forwardburger mdi-24px"></i></span>
        </a>
      </div>
      <div class="navbar-menu fadeIn animated faster" id="navbar-menu">
        <div class="navbar-end">
          <div
            class="navbar-item has-dropdown has-dropdown-with-icons has-divider has-user-avatar is-hoverable">
            <a class="navbar-link is-arrowless">
              <div class="is-user-avatar">
                <img
                  src="https://avatars.dicebear.com/v2/initials/john-doe.svg"
                  alt="John Doe" />
              </div>
              <div class="is-user-name"><span><?php echo ucfirst($_SESSION['member']['firstName']); ?></span></div>
              <!-- <span class="icon"><i class="mdi mdi-chevron-down"></i></span> -->
            </a>
            <!-- <div class="navbar-dropdown">
                <a href="profile.php" class="navbar-item">
                  <span class="icon"><i class="mdi mdi-account"></i></span>
                  <span>My Profile</span>
                </a>
                <a class="navbar-item">
                  <span class="icon"><i class="mdi mdi-settings"></i></span>
                  <span>Settings</span>
                </a>
                <a class="navbar-item">
                  <span class="icon"><i class="mdi mdi-email"></i></span>
                  <span>Messages</span>
                </a>
                <hr class="navbar-divider" />
                <a class="navbar-item">
                  <span class="icon"><i class="mdi mdi-logout"></i></span>
                  <span>Log Out</span>
                </a>
              </div> -->
          </div>
          <a href="../index.php" title="Log out" class="navbar-item is-desktop-icon-only">
            <span class="icon"><i class="mdi mdi-logout"></i></span>
            <span>Log out</span>
          </a>
        </div>
      </div>
    </nav>
    <aside class="aside is-placed-left is-expanded">
      <div class="aside-tools">
        <div class="aside-tools-label">
          <span><b>Boutique</b> Admin</span>
        </div>
      </div>
      <div class="menu is-menu-main">
        <p class="menu-label">General</p>
        <ul class="menu-list">
          <li >
            <a href="index.php" class="has-icon  <?php if($page == 'index.php') echo 'is-active'?>">
              <span class="icon"><i class="mdi mdi-desktop-mac"></i></span>
              <span class="menu-item-label">Dashboard</span>
            </a>
          </li>
        </ul>
        <p class="menu-label">MENU</p>
        <ul class="menu-list">
          <li class="">
            <a href="gestion_boutique.php" class="has-icon <?php if($page == 'gestion_annonces.php') echo 'is-active'?>">
              <span class="icon <?php if($page == 'gestion_annonces.php') echo 'has-update-mark'?>"><span class="mdi mdi-store"></span>
              </span>
              <span class="menu-item-label">Annonces</span>
            </a>
          </li>
          <li>
            <a href="gestion_commande.php" class="has-icon <?php if($page == 'gestion_commande.php') echo 'is-active'?>">
              <span class="icon <?php if($page == 'gestion_commande.php') echo 'has-update-mark'?>"><span class="mdi mdi-sheep"></span> </span>
              <span class="menu-item-label">Commandes</span>
            </a>
          </li>
          <li>
            <a href="gestion_user.php" class="has-icon <?php if($page == 'gestion_member.php') echo 'is-active'?>">
              <span class="icon <?php if($page == 'gestion_member.php') echo 'has-update-mark'?>"><i class="mdi mdi-account-circle"></i></span>
              <span class="menu-item-label">Membres</span>
            </a>
          </li>
          <li>
            <a href="../index.php" title="Log out" class="has-icon">
              <span class="icon"><i class="mdi mdi-logout"></i></span>
              <span>Quitter</span>
            </a>
          </li>
        </ul>
      </div>
    </aside>
