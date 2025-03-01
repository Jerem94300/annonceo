<?php
  require_once('include/init.php');

  if (userConnected()) {
    header('Location: index.php');
  }



if (isset($_POST['submit'])&& $_SERVER['REQUEST_METHOD'] === 'POST') {

  $data = $connect_db->prepare("SELECT * FROM member WHERE email = :email ");
  $data->bindValue(':email', $_POST['email'],PDO::PARAM_STR);
  $data->execute();
// echo $data->rowCount();
 
  if ($data->rowCount()) {
    $errorVerifEmail = '<small class="text-danger">Email déjà utilisé</small><br>';
    $error = true;
  } elseif (empty($_POST['email'])) {
    $errorVerifEmail ='<small class="text-danger">Merci de saisir une adresse email</small><br>';
    $error = true;
  } elseif (!filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)) {
    $errorVerifEmail='<small class="text-danger">Erreur Email ! Ex: exemple@gmail.com</small><br>';
    $error = true;
  }

  $password_regex = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/"; 

  if (empty($_POST['password'])) {
    $errorPassWords= '<small class="text-danger">Merci de saisir un mot de passe</small><br>';
    $error = true;
  }elseif (!preg_match($password_regex, $_POST['password'])) {
    $errorSubject= '<small class="text-danger">Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial</small><br>';
    $error = true;
  }elseif (($_POST['password']) !== ($_POST['repeat_password'])) {
    $errorRepeatPassWords= '<small class="text-danger">Les mots de passe ne correspondent pas</small><br>';
    $error = true;
  }

  //exo si l'utilisateur a rempli le formulaire correctement, executez la requete d'insertion en base de données et redirigez l'utilisateur vers la page de connexion.php

  if (!isset($error)){

    //le mot de passe n'est jamais conservé en clair dans la base de données
    //passxord_hash permet de créer une clé de hachage du mot de passe dans la bdd
    $result = $connect_db->prepare("INSERT INTO member (pseudo, password, lastName, firstName, phone_number, email, civilite, status, date,role  ) VALUES (:pseudo, :password, :lastName, :firstName, :phone_number,:email, :civilite, :status, :date, :role )");
    
    $result->bindValue(':pseudo', $_POST['pseudo'],PDO::PARAM_STR);
    $result->bindValue(':password', password_hash($_POST['password'],PASSWORD_DEFAULT),PDO::PARAM_STR);
    $result->bindValue(':lastName', $_POST['lastName'],PDO::PARAM_STR);
    $result->bindValue(':firstName', $_POST['firstName'],PDO::PARAM_STR);
    $result->bindValue(':phone_number', $_POST['phone_number'],PDO::PARAM_STR);
    $result->bindValue(':email', $_POST['email'],PDO::PARAM_STR);
    $result->bindValue(':civilite', $_POST['civilite'],PDO::PARAM_STR);
    $result->bindValue(':status', '1',PDO::PARAM_INT);
    $result->bindValue(':date', date('Y-m-d H:i:s'),PDO::PARAM_STR);
    $result->bindValue(':role', 'member',PDO::PARAM_STR);
   
    $result->execute();

    //on stock dans le fichier de session de l'utilisateur, le fichier de session est stocké coté serveur et est accessible via $_SESSION, et est accessible sur n'importe quelle page du site. On stock le message flash dans le fichier de session
    $_SESSION['msgRegisterValidate'] = '<div class="bg-success p-3 text-white text-center my-3">Formulaire conforme, vous pouvez dès à présent vous connecter.</div><br>';

    // $validateForm = '<div class="alert alert-success text-center my-3">Formulaire conforme</div><br>';

    header('location: connexion.php');
}

}


require_once('include/header.php');

?>
  <!-- inner page section -->
  <section class="inner_page_head">
    <div class="container_fuild">
      <div class="row">
        <div class="col-md-12">
          <div class="full">
            <h3>Créer votre compte</h3>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- end inner page section -->
  <!-- why section -->
  <section class="why_section layout_padding">
    <div class="container">


      <div class="row">
        <div class="col-lg-8 offset-lg-2">
          <div class="full">
            <form method="post" action="">
              <fieldset>
                <input
                  type="text"
                  placeholder="Enter votre pseudo"
                  name="pseudo"
                   />

                   <?php if (isset($errorPassWords )) {
                       echo $errorPassWords ;
                }
           
                ?>
               
                <input
                  type="password"
                  placeholder="Enter votre mot de passe"
                  name="password" class="<?php if (isset($errorPassWords )) echo "border-danger" ;?>"
                   />
                 
                   <?php if (isset($errorRepeatPassWords )) {
                       echo $errorRepeatPassWords ;
                }
           
                ?>
                
                <input
                  type="password"
                  placeholder="Répétez votre mot de passe"
                  name="repeat_password" class="<?php if (isset($errorRepeatPassWords )) echo "border-danger" ;?>"
                  />

                  <?php if (isset($validatePassWords )) {
                       echo $validatePassWords ;
                  }
           
                ?>

                <input
                  type="text"
                  placeholder="Civilité"
                  name="civilite"
                   />

                <input
                  type="text"
                  placeholder="Enter votre nom"
                  name="lastName"
                   />

                   <input
                  type="text"
                  placeholder="Enter votre prénom"
                  name="firstName"
                   />

                   <input
                  type="text"
                  placeholder="Enter votre numéro de téléphone"
                  name="phone_number"
                   />

                   <input
                  type="text"
                  placeholder="Entrez votre adresse e-mail"
                  name="email" class=" <?php if (isset($errorVerifEmail )) echo "border-danger" ;?>"value=" <?php if (isset($errorVerifEmail )) echo $_POST['email'] ;?>"
                  />
               




              

                   <?php if (isset($errorVerifEmail )) {
                       echo $errorVerifEmail ;
                  }
           
                  ?>

         
                   <?php if (isset($errorSubject )) {
                       echo $errorSubject ;
                }
           
                ?>

           
                  
                <input type="submit" name="submit" />
              </fieldset>
            </form>
            
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
  <!-- footer section -->
  <!-- jQery -->
  <script src="assets/js-famma/jquery-3.4.1.min.js"></script>
  <!-- popper js -->
  <script src="assets/js-famma/popper.min.js"></script>
  <!-- bootstrap js -->
  <script src="assets/js-famma/bootstrap.js"></script>
  <!-- custom js -->
  <!-- <script src="assets/js-famma/custom.js"></script> -->
</body>

</html>