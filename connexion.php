<?php
require_once('include/init.php');

//si l'indice action a pour valeur logout cela veut dire que l'internaute a cliqué sur deconnexion, on supprime le tableau Array de données user dans la session

if(isset($_GET['action']) && $_GET['action'] == 'logout'){
  //unset ne supprime pas le fichier de session mais seulement l'indice 'member'
  unset($_SESSION['member']);
}

//si l'utilisateur est conneté, il n'a rien a faire sur la page identifiez vous on le redirige sur index.php
// if (userConnected()) {
//   header('location: index.php');
// }
// echo  '<pre>'; print_r($_POST); echo'</pre>';


if (isset($_POST['submit']) && $_SERVER['REQUEST_METHOD']=== 'POST') {
  $data = $connect_db->prepare("SELECT * FROM member WHERE email = :email");
  $data->bindValue(':email', $_POST['email'],PDO::PARAM_STR);
  $data->execute();

  if ($data->rowCount()) {
    // echo "email existant";

    $member = $data->fetch(PDO::FETCH_ASSOC);
    // echo '<pre>';print_r($user); echo'</pre>';

    if (password_verify($_POST['password'],$member['password'])) {

      foreach ($member as $key => $value) {
        $_SESSION['member'][$key] = $value;
      }
      // echo '<pre>';print_r($_SESSION); echo'</pre>';

      header("location: index.php");


    }else {
      $error = '<div class="background-danger p-3 mb-3 text-white text-center">Email ou mot de passe invalide</div>';
    }




  }else{
    // echo "email inexistant";
    $error = '<div class="background-danger p-3 mb-3 text-white text-center">Email ou mot de passe invalide</div>';
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
            <h3>Identifiez-vous</h3>
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

          <?php if (isset($_SESSION['msgRegisterValidate'])) echo $_SESSION['msgRegisterValidate'];?>
          <?php if (isset($error)) echo $error;?>

          <div class="full">
            <form method="post" action="">
              <fieldset>
                <input
                  type="text"
                  placeholder="Entrez votre adresse e-mail"
                  name="email" class="<?php if (isset($error)) echo 'border-danger';?>"
                  value="<?php if (isset($_POST['email'])) echo $_POST['email'];?>"
                   />
                <input
                  type="password"
                  placeholder="Enter votre mot de passe"
                  name="password" class="<?php if (isset($error)) echo 'border-danger';?>"
                   />
                <input type="submit"  name="submit" value="Continuer" />
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

// on supprime le message de validation d'inscription dans la session, afin qu'il ne sopit plus affiché

unset($_SESSION['msgRegisterValidate']);

?>
  <!-- footer end -->

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