<?php
require_once('../include/init.php');

$_SESSION['msg'] = false;

//   echo '<pre>';
//  print_r($_POST);
//  echo '</pre>';


if (!adminConnected()) {
  header('location: '. URL .'index.php');
}


//REQUETE DE SUPPRESSION

if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
  $data = $connect_db->prepare("DELETE FROM user WHERE id_user = :id");
  $data->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
  $data->execute();
  $_SESSION['msgValidation'] = '<div class="notification is-success">L\'utilisateur a bien été supprimé</div>';
  $_SESSION['msg'] = true;
  header('location: gestion_user.php');
}

// echo '<pre>';
// print_r($_SESSION);
// echo '</pre>';

//Affichage des utilisateurs
$resultat = executeRequete("SELECT * FROM user where roles = 'user'");

// echo '<pre>';
// print_r($resultat);
// echo '</pre>';

$userList = $resultat->fetchAll();


// echo '<pre>';
// print_r($userList);
// echo '</pre>';

// Affichage des utilisateurs avec un role = admin

$resultat = executeRequete("SELECT * FROM user WHERE roles = 'admin'");
$adminList = $resultat->fetchAll();

// echo '<pre>';
// print_r($adminList);
// echo '</pre>';

//requete d'insertion/ MODIFICATION

if (isset($_POST['submit']) && $_SERVER['REQUEST_METHOD'] === 'POST') {


  if(isset($_GET['action']) && $_GET['action'] == 'update'){
    $data = $connect_db->prepare("UPDATE user SET lastName = :lastName, firstName = :firstName, email = :email, address = :address, zipcode = :zipcode, city = :city, roles = :roles WHERE id_user = :id");
    $data->bindValue(':id', $_POST['id_user'], PDO::PARAM_INT );
    $_SESSION['msgValidation'] = '<div class="notification is-success">L\'utilisateur a bien été modifié</div>';
  
  }else{
    $data = $connect_db->prepare("INSERT INTO user ( lastName, firstName, email, address, zipcode, city, roles) VALUES (:lastName, :firstName, :email, :address, :zipcode, :city, 'roles')");
    $_SESSION['msgValidation'] = '<div class="notification is-success">L\'utilisateur a bien été ajouté</div>';
  
  
  }
  $_SESSION['msg'] = true;


  $data->bindValue(':lastName', $_POST['lastName'], PDO::PARAM_STR);
  $data->bindValue(':firstName', $_POST['firstName'], PDO::PARAM_STR);
  $data->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
  $data->bindValue(':address', $_POST['address'], PDO::PARAM_STR);
  $data->bindValue(':zipcode', $_POST['zipcode'], PDO::PARAM_INT);
  $data->bindValue(':city', $_POST['city'], PDO::PARAM_STR);
  $data->bindValue(':roles', $_POST['roles'], PDO::PARAM_STR);
  $data->execute();


  $messageModifUser = '<div class="notification is-success">Les données de l\'utilisateur ont bien été modifié</div>';
  header('location: gestion_user.php');
}  

  

$data = $connect_db->query("SELECT * FROM user");
$user = $data->fetchAll(PDO::FETCH_ASSOC);





if(isset($_GET['action']) && $_GET['action'] == 'update'){
  $data = $connect_db->prepare("SELECT * FROM user WHERE id_user = :id");
  $data->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
  $data->execute();
  $currentUser = $data->fetch(PDO::FETCH_ASSOC);

  // echo '<pre>'; print_r($currentUser); echo'</pre>';


}


require_once('include/header.php');
  ?>
    

    <section class="section is-title-bar">
      <div class="level">
        <div class="level-left">
          <div class="level-item">
            <ul>
              <li>Admin</li>
              <li>Utilisateurs</li>
            </ul>
          </div>
        </div>

      </div>
    </section>
    <section class="section is-main-section">
    <?php if(isset($_SESSION['msgValidation'])):?>
      <div class="notification is-primary">
        <button class="delete"></button>
        <?= $_SESSION['msgValidation'];?>
        <?php endif;?>

       
      </div>
      <div class="card has-table">
        <header class="card-header">
          <p class="card-header-title">
            <span class="icon"><i class="mdi mdi-account-multiple"></i></span>
            Clients
          </p>
          <a href="#" class="card-header-icon">
            <span class="icon"><i class="mdi mdi-reload"></i></span>
          </a>
        </header>
        <div class="card-content">
          <div class="b-table has-pagination">
            <div class="table-wrapper has-mobile-cards">
              <table
                class="table is-fullwidth is-striped is-hoverable is-fullwidth has-text-centered">
                <thead>
                  <tr>
                    <th class="is-checkbox-cell">
                      <label class="b-checkbox checkbox">
                        <input type="checkbox" value="false" />
                        <span class="check"></span>
                      </label>
                    </th>
                    <th class="has-text-centered">LastName</th>
                    <th class="has-text-centered">FirstName</th>
                    <th class="has-text-centered">Email</th>
                    <th class="has-text-centered">Adress</th>
                    <th class="has-text-centered">Zipcode</th>
                    <th class="has-text-centered">City</th>
                    <th class="has-text-centered">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Affichage de tous les utilisateurs avec roles user -->

                  <?php foreach ($userList as $user) : ?>
                    <tr>
                      <td class="is-checkbox-cell">
                        <label class="b-checkbox
                          checkbox">
                          <input type="checkbox" value="false" />
                          <span class="check"></span>
                        </label>
                      </td>
                      <td data-label="LastName"><?= $user['lastName'] ?></td>
                      <td data-label="FirstName"><?= $user['firstName'] ?></td>
                      <td data-label="Email"><?= $user['email'] ?></td>
                      <td data-label="Adress"><?= $user['address'] ?></td>
                      <td data-label="Zipcode"><?= $user['zipcode'] ?></td>
                      <td data-label="City"><?= $user['city'] ?></td>
                      <td class="is-actions
                        cell ">
                        <div class="buttons is-centered">
                          <a href="?action=update&id=<?= $user['id_user'] ?>" class="button is-small is-primary">
                            <span class="icon"><i class="mdi mdi-pencil"></i></span>
                          </a>
                          <button
                            class="button is-small is-danger jb-modal"
                            data-target="sample-modal-<?= $user['id_user'] ?>"
                            type="button">
                            <span class="icon"><i class="mdi mdi-trash-can"></i></span>
                          </button>
                        </div>
                      </td>
                    </tr>
                    <!-- Modal supression user -->
                    <div id="sample-modal-<?= $user['id_user'] ?>" class="modal">
                    <div class="modal-background jb-modal-close"></div>
                    <div class="modal-card">
                      <header class="modal-card-head">
                        <p class="modal-card-title">Veuillez confirmez la suppression.</p>
                        <button class="delete jb-modal-close" aria-label="close"></button>
                      </header>
                      <section class="modal-card-body">
                        <p>Voulez-vous réelement supprimer cet utilisateur ?</p>
                      </section>
                      <footer class="modal-card-foot">
                        <button class="button jb-modal-close">Annuler</button>
                        <a href='?action=delete&id=<?= $user['id_user'] ?>' class="button is-danger jb-modal-close">Supprimer</a>
                      </footer>
                    </div>
                    <button
                      class="modal-close is-large jb-modal-close"
                      aria-label="close"></button>
                  </div>










                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
                
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="section is-main-section">
      <div class="card has-table">
        <header class="card-header">
          <p class="card-header-title">
            <span class="icon"><i class="mdi mdi-account-multiple"></i></span>
            Administrateurs
          </p>
          <a href="#" class="card-header-icon">
            <span class="icon"><i class="mdi mdi-reload"></i></span>
          </a>
        </header>
        <div class="card-content">
          <div class="b-table has-pagination">
            <div class="table-wrapper has-mobile-cards">
              <table
                class="table is-fullwidth is-striped is-hoverable is-fullwidth has-text-centered">
                <thead>
                  <tr>
                    <th class="is-checkbox-cell">
                      <label class="b-checkbox checkbox">
                        <input type="checkbox" value="false" />
                        <span class="check"></span>
                      </label>
                    </th>
                    <th class="has-text-centered">LastName</th>
                    <th class="has-text-centered">FirstName</th>
                    <th class="has-text-centered">Email</th>
                    <th class="has-text-centered">Adress</th>
                    <th class="has-text-centered">Zipcode</th>
                    <th class="has-text-centered">City</th>
                    <th class="has-text-centered">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>

                    <?php foreach ($adminList as $admin) : ?>
                      <tr>
                        <td class="is-checkbox-cell">
                          <label class="b-checkbox
                            checkbox">
                            <input type="checkbox" value="false" />
                            <span class="check"></span>
                          </label>
                        </td>
                        <td data-label="LastName"><?= $admin['lastName'] ?></td>
                        <td data-label="FirstName"><?= $admin['firstName'] ?></td>
                        <td data-label="Email"><?= $admin['email'] ?></td>
                        <td data-label="Adress"><?= $admin['address'] ?></td>
                        <td data-label="Zipcode"><?= $admin['zipcode'] ?></td>
                        <td data-label="City"><?= $admin['city'] ?></td>
                        <td class="is-actions
                          cell">
                          <div class="buttons is-centered">
                          <a href="?action=update&id=<?= $admin['id_user'] ?>" class="button is-small is-primary">
                            <span class="icon"><i class="mdi mdi-pencil"></i></span>
                          </a>
                          <button
                            class="button is-small is-danger jb-modal"
                            data-target="sample-modal-<?= $admin['id_user'] ?>"
                            type="button">
                            <span class="icon"><i class="mdi mdi-trash-can"></i></span>
                          </button>
                        </div>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                </tbody>
              </table>
            </div>
               <!-- <div class="notification">
                <div class="level">
                  <div class="level-left">
                    <div class="level-item">
                      <div class="buttons has-addons">
                        <button type="button" class="button is-active">
                          1
                        </button>
                        <button type="button" class="button">2</button>
                        <button type="button" class="button">3</button>
                      </div>
                    </div>
                  </div>
                  <div class="level-right">
                    <div class="level-item">
                      <small>Page 1 of 3</small>
                    </div>
                  </div>
                </div>
              </div> -->
          </div>
        </div>
      </div>
    </section>      
    <?php if(isset($_GET['action']) && $_GET['action'] == 'update'):?>
    <section class="section is-main-section">
      <div class="card">
        <header class="card-header">
          <p class="card-header-title">
            <span class="icon"><i class="mdi mdi-ballot"></i></span>
            Modification utilisateur
          </p>
        </header>


        <div class="card-content">
        <form method="post" action="?action=update&id=<?= $currentUser['id_user'] ?>">
        <div class="field is-horizontal">
              <div class="field-label is-normal">
              <input type="hidden" name="id_user" value="<?php if(isset($currentUser['id_user'])) echo $currentUser['id_user']?>">

                <label class="label">Nom / Prénom</label>
              </div>
              <div class="field-body">
                <div class="field">
                  <p class="control is-expanded has-icons-left">
                    <input class="input" type="text" name="lastName" placeholder="Nom" value="<?php if(isset($currentUser['lastName'])) echo $currentUser['lastName'];?>" />
                    <span class="icon is-small is-left"><i class="mdi mdi-account"></i></span>
                  </p>
                </div>
                <div class="field">
                  <p class="control is-expanded has-icons-left">
                    <input class="input" type="text" name="firstName" placeholder="Prénom" value="<?php if(isset($currentUser['firstName'])) echo $currentUser['firstName'];?>" />
                    <span class="icon is-small is-left"><i class="mdi mdi-account"></i></span>
                  </p>
                </div>
              </div>
            </div>
            <div class="field is-horizontal">
              <div class="field-label is-normal">
                <label class="label">Email</label>
              </div>
              <div class="field-body">
                <div class="field">
                  <p
                    class="control is-expanded has-icons-left has-icons-right">
                    <input
                      class="input is-success"
                      type="email" name="email"
                      placeholder="Email"
                      value="<?php if(isset($currentUser['email'])) echo $currentUser['email'];?>" />
                    <span class="icon is-small is-left"><i class="mdi mdi-mail"></i></span>
                  </p>
                </div>
              </div>
              
            </div>

            <div class="field is-horizontal">
              <div class="field-label is-normal">
                <label class="label">Coordonnées</label>
              </div>
              <div class="field-body">
                <div class="field">
                  <p class="control is-expanded has-icons-left">
                    <input class="input" type="text" name="address" placeholder="Adresse" value="<?php if(isset($currentUser['address'])) echo $currentUser['address'];?>" />
                    <span class="icon is-small is-left"><i class="fa-solid fa-location-dot"></i></span>
                  </p>
                </div>
                <div class="field">
                  <p class="control is-expanded has-icons-left">
                    <input class="input" type="text" name="zipcode" placeholder="C.P" value="<?php if(isset($currentUser['zipcode'])) echo $currentUser['zipcode'];?>" />
                    <span class="icon is-small is-left"><i class="fa-solid fa-location-dot"></i></span>
                  </p>
                </div>
                <div class="field">
                  <p class="control is-expanded has-icons-left">
                    <input class="input" type="text" name="city" placeholder="Ville" value="<?php if(isset($currentUser['city'])) echo $currentUser['city'];?>" />
                    <span class="icon is-small is-left"><i class="fa-solid fa-location-dot"></i></span>
                  </p>
                </div>
              </div>
            </div>

            <div class="field is-horizontal">
              <div class="field-label is-normal">
                <label class="label">Admin / User</label>
              </div>
              <div class="field-body">
                <div class="field is-narrow">
                  <div class="control">
                    <div class="select is-fullwidth">
                      <select name="roles">

                        <option value="admin"<?php if(isset($currentUser['roles']) && $currentUser['roles'] == 'admin') echo 'selected'?>>admin</option>
                        <option value="user"<?php if(isset($currentUser['roles']) && $currentUser['roles'] == 'user') echo 'selected'?>>user</option>
     
                        
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>


            <!-- <div class="field is-horizontal">
              <div class="field-label"></div>
              <div class="field-body">
                <div class="field is-expanded">
                  <div class="field has-addons">
                    <p class="control">
                      <a class="button is-static">+33</a>
                    </p>
                    <p class="control is-expanded">
                      <input
                        class="input"
                        type="tel"
                        placeholder="Your phone number" />
                    </p>
                  </div>
                  <p class="help">Do not enter the first zero</p>
                </div>
              </div>
            </div> -->


            <!--          
            <div class="field is-horizontal">
              <div class="field-label">
                <label class="label">Switch</label>
              </div>
              <div class="field-body">
                <div class="field">
                  <label class="switch is-rounded"><input type="checkbox" value="false" />
                    <span class="check"></span>
                    <span class="control-label">Default</span>
                  </label>
                </div>
              </div>
            </div> -->
            <hr />
            <div class="field is-horizontal">
              <div class="field-label">
                <!-- Left empty for spacing -->
              </div>
              <div class="field-body">
                <div class="field">
                  <div class="field is-grouped">
                    <div class="control">
                      <button type="submit" name="submit" class="button is-primary">
                        <span>Submit</span>
                      </button>
                    </div>
                    <?php endif; ?>

                    <!-- <div class="control">
                      <button
                        type="button"
                        class="button is-primary is-outlined">
                        <span>Reset</span>
                      </button>
                    </div> -->
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>


      </div>
    </section>
    <?php
    require_once('include/footer.php');
    if ($_SESSION['msg'] === false) {
      unset($_SESSION['msgValidation']);
    }

     ?>

