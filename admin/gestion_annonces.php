<?php
require_once('../include/init.php');

$_SESSION['msg'] = false;

if (!adminConnected()) {
  header('location: '. URL .'index.php');
}

//REQUETE DE SUPPRESSION

if(isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])){
  $data = $connect_db->prepare("DELETE FROM product WHERE id_product = :id");
  $data->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
  $data->execute();
  $_SESSION['msg'] = true;

  $_SESSION['msgValidation'] = "Le produit a bien été supprimé.";
  header('location: gestion_boutique.php');


}




if (isset($_POST['submit']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
  // echo '<pre>'; print_r($_FILES); echo'</pre>';
  // echo '<pre>'; print_r($_POST); echo'</pre>';

$pictureUrlDb = null;
//gestion de l'affichage de l'image en cas de modification de produits
  if(isset($_GET['action']) && $_GET['action'] == 'update'){

    $pictureUrlDb = $_POST['current_picture'];
  }



  // $ FILES est une superglobale permettant de stocker les données d'un fichier uploadé (nom, extension, taile etc...)
  // Si une image a bien été uploadé

  if(!empty($_FILES['picture']['name'])){

    //Controle de l'extension
    $currentExtension = ['jpg','jpeg','png','webp'];
    $filesUpLoaded = new SplFileInfo($_FILES['picture']['name']);

    // SplFileInfo : classe prédéfinie en PHP permettant de traiter les données d'un fichier uploadé, elle contient ses propres méthodes (fonction)
    // echo '<pre>'; print_r($filesUpLoaded); echo'</pre>';
    // echo '<pre>'; print_r(get_class_methods($filesUpLoaded)); echo'</pre>';

    // getExtension() est une méthode issue de la classe SplFileInfo qui retourne l'extension du fichier uploadé
    $filesUpLoadedExtension = $filesUpLoaded->getExtension();

    // echo $filesUpLoadedExtension . '<br>';

    // array search() : fonction prédéfinie qui returne la position d'un element (indice) dans un tableau Array
    $positionExtension = array_search($filesUpLoadedExtension,$currentExtension);

    // echo "Position :". $positionExtension . '<br>';

    // Si array search retourne false, cela veut dire que l'extension n'a pas été trouvé dans le tableau Arrray $currentExtension, alors on entre dans le IF
    if($positionExtension === false){
      $errorPicture = "<small class='has-text-danger'>Extension non valide (jpg,jpeg,png,webp uniquement).</small>";
      
    }else{
      // On concatène la référence saisie dans le formulaire avec le nom de l'image pour éviter les doublons
      $pictureName = $_POST['reference']. '-' . $_FILES['picture']['name'];
      // echo $pictureName. '<br>';
  
      $pictureUrlDb = URL . "assets/images-produits/$pictureName";
  
      // echo $pictureUrlDb . '<br>';
  
      $pictureFolder = RACINE_SITE ."assets/images-produits/$pictureName";
  
      // echo $pictureFolder;
  
      copy($_FILES['picture']['tmp_name'],$pictureFolder);
    }
       
  }

      //requete d'insertion/ MODIFICATION

      if(isset($_GET['action']) && $_GET['action'] == 'update'){
        $data = $connect_db->prepare("UPDATE product SET reference = :reference, category = :category, title = :title, description = :description, color = :color, size = :size, public = :public, picture = :picture, price = :price, stock = :stock WHERE id_product = :id");
        $data->bindValue(':id', $_GET['id'],PDO::PARAM_INT);
        $_SESSION['msgValidation'] = "Les modifications ont bien été enregistés.";


      }else{
      $data = $connect_db->prepare("INSERT INTO product (reference, category, title, description, color, size, public, picture, price, stock) VALUES (:reference, :category, :title, :description, :color, :size, :public, :picture, :price, :stock)");
      $_SESSION['msgValidation'] = "Le produit a bien été enregisté.";

      }
      $_SESSION['msg'] = true;

      $data->bindValue(':reference', $_POST['reference'], PDO::PARAM_STR);
      $data->bindValue(':category', $_POST['category'], PDO::PARAM_STR);
      $data->bindValue(':title', $_POST['title'], PDO::PARAM_STR);
      $data->bindValue(':description', $_POST['description'], PDO::PARAM_STR);
      $data->bindValue(':color', $_POST['color'], PDO::PARAM_STR);
      $data->bindValue(':size', $_POST['size'], PDO::PARAM_STR);
      $data->bindValue(':public', $_POST['public'], PDO::PARAM_STR);
      $data->bindValue(':picture', $pictureUrlDb, PDO::PARAM_STR);
      $data->bindValue(':price', $_POST['price']);
      $data->bindValue(':stock', $_POST['stock'], PDO::PARAM_INT);
      $data->execute();


      header('location: gestion_boutique.php');


}

$data = $connect_db->query("SELECT * FROM product");
$products = $data->fetchAll(PDO::FETCH_ASSOC);
// echo '<pre>'; print_r($products); echo'</pre>';

$nbProducts = $data->rowCount();
if($nbProducts <= 1)
$txt = "$nbProducts produit";
else 
  $txt = "$nbProducts produits";


  if(isset($_GET['action']) && $_GET['action'] == 'update'){
    $data = $connect_db->prepare("SELECT *FROM product WHERE id_product = :id");
    $data->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
    $data->execute();

    $currentProduct = $data->fetch(PDO::FETCH_ASSOC);
  // echo '<pre>'; print_r($currentProduct); echo'</pre>';
}







require_once('include/header.php');
  ?>
    
    <section class="section is-title-bar">
      <div class="level">
        <div class="level-left">
          <div class="level-item">
            <ul>
              <li>Admin</li>
              <li>Boutique</li>
            </ul>
          </div>
        </div>
        <!-- <div class="level-right">
            <div class="level-item">
              <div class="buttons is-right">
                <a
                  href="https://github.com/vikdiesel/admin-one-bulma-dashboard"
                  target="_blank"
                  class="button is-primary"
                >
                  <span class="icon"
                    ><i class="mdi mdi-github-circle"></i
                  ></span>
                  <span>GitHub</span>
                </a>
              </div>
            </div>
          </div> -->
      </div>
    </section>
    <section class="section is-main-section">

    <?php if(isset($_SESSION['msgValidation'])):?>

      <div class="notification is-primary">
        <button class="delete"></button>
        <?= $_SESSION['msgValidation'];?>
      </div>
      <?php endif;?>

      <div class="card has-table">
        <header class="card-header">
          <p class="card-header-title">
            <span class="icon"><span class="mdi mdi-shopping-outline"></span>
            </span>
            <?= $txt ?>
          </p>
          <a href="#" class="card-header-icon">
            <span class="icon"><i class="mdi mdi-reload"></i></span>
          </a>
        </header>
        <div class="card-content">
          <div class="b-table has-pagination">
            <div class="table-wrapper has-mobile-cards">
              <table
                class="table is-fullwidth is-striped is-hoverable is-fullwidth">
                <thead>
                  <tr>
                    <th class="is-checkbox-cell">
                      <label class="b-checkbox checkbox">
                        <input type="checkbox" value="false" />
                        <span class="check"></span>
                      </label>
                    </th>
                    <?php for ($i=0; $i < $data->columnCount(); $i++): 
                        $dataColumn = $data->getColumnMeta($i);
                        // echo '<pre>'; print_r($dataColumn); echo'</pre>';
                        if ($dataColumn['name'] != 'id_product'):
                  
                    ?>
                    <th><?= ucFirst($dataColumn['name'])?></th>

                    <?php endif; endfor; ?>
                    <th>Actions</th>
                    
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($products as $arrayProduct): ?>
                  <tr>
                    <td class="is-checkbox-cell">
                      <label class="b-checkbox checkbox">
                        <input type="checkbox" value="false" />
                        <span class="check"></span>
                      </label>
                    </td>


                    <?php foreach ($arrayProduct as $key => $value): 
                    if ($key != 'id_product'): 
                  
                 
                    ?>
                    <td data-label="<?= ucFirst($key)?>">
                      <?php if($key == 'picture'): ?>
                        <img src="<?= $value ?>" class="picture_product" alt="<?= $arrayProduct['title'] ?>">
                      <?php elseif($key == 'price'): ?>
                        <?= $value .'€' ?>

                        <?php else: ?>
                          <?= $value ?>

                        <?php endif; ?>                      
                      </td>

                    <?php endif; endforeach; ?>

                
                    <td class="is-actions-cell">
                      <div class="buttons is-right">
                        <a href="?action=update&id=<?= $arrayProduct['id_product'] ?>"
                          class="button is-small is-primary"
                          type="">
                          <span class="icon"><i class="mdi mdi-pencil"></i></span>
                        </a>
                        <button
                          class="button is-small is-danger jb-modal"
                          data-target="sample-modal-<?= $arrayProduct['id_product'] ?>"
                          type="button">
                          <span class="icon"><i class="mdi mdi-trash-can"></i></span>
                        </button>
                      </div>
                    </td>
                  </tr>

                  <div id="sample-modal-<?= $arrayProduct['id_product'] ?>" class="modal">
                    <div class="modal-background jb-modal-close"></div>
                    <div class="modal-card">
                      <header class="modal-card-head">
                        <p class="modal-card-title">Veuillez confirmez la suppression.</p>
                        <button class="delete jb-modal-close" aria-label="close"></button>
                      </header>
                      <section class="modal-card-body">
                        <p>Voulez-vous réelement supprimer ce produit?</p>
                      </section>
                      <footer class="modal-card-foot">
                        <button class="button jb-modal-close">Annuler</button>
                        <a href='?action=delete&id=<?= $arrayProduct['id_product'] ?>' class="button is-danger jb-modal-close">Supprimer</a>
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

    <section class="section is-main-section">
      <div class="card">
        <header class="card-header">
          <p class="card-header-title">
            <span class="icon"><span class="mdi mdi-shopping-outline"></span></span>
           <?php if(isset($_GET['action']) && $_GET['action'] == 'update'):?>
            Modification
            <?php else :?>
              Ajout
              <?php endif;?>
              Produit
          </p>
        </header>

        <!-- enctype="multipart/form-data" : permet de récuperer en php des données des  fichiers  uploadés-->
        <div class="card-content" >
          <form method="post" enctype="multipart/form-data">
            <div class="field is-horizontal">
              <div class="field-label is-normal">
                <label class="label">Référence / catégorie</label>
              </div>
              <div class="field-body">
                <div class="field">
                    <input class="input" type="text" name="reference" placeholder="Entrer une référence produit" value="<?php if(isset($currentProduct['reference'])) echo $currentProduct['reference'];?>" />
                  </p>
                </div>
                <div class="field">
                    <input class="input" type="text" name="category" placeholder="Entrer une catégorie produit" value="<?php if(isset($currentProduct['category'])) echo $currentProduct['category'];?>" />
                  </p>
                </div>
              
              </div>
            </div>

            <div class="field is-horizontal">
              <div class="field-label is-normal">
                <label class="label">Titre / Couleur</label>
              </div>
              <div class="field-body">
                <div class="field">
                    <input class="input" type="text" name="title" placeholder="Entrer un titre produit" value="<?php if(isset($currentProduct['title'])) echo $currentProduct['title'];?>" />
                  </p>
                </div>
                <div class="field">
                    <input class="input" type="text" name="color" placeholder="Entrer une couleur produit" value="<?php if(isset($currentProduct['color'])) echo $currentProduct['color'];?>" />
                  </p>
                </div>
              
              </div>
            </div>

            <div class="field is-horizontal">
              <div class="field-label is-normal">
                <label class="label">Taille / Genre</label>
              </div>
              <div class="field-body">
                <div class="field is-narrow">
                  <div class="control">
                    <div class="select is-fullwidth">
                      <select name="size">
                        <option value="S">S</option>


                        <option value="M"<?php if(isset($currentProduct['size']) && $currentProduct['size'] == 'M') echo 'selected'?>>M</option>
                        <option value="L"<?php if(isset($currentProduct['size']) && $currentProduct['size'] == 'L') echo 'selected'?>>L</option>
                        <option value="XL"<?php if(isset($currentProduct['size']) && $currentProduct['size']  =='XL') echo 'selected'?>>XL</option>
                        
                      </select>
                    </div>
                  </div>
                </div>

                <div class="field is-narrow">
                  <div class="control">
                    <div class="select is-fullwidth">
                      <select name="public">
                        <option value="homme" <?php if(isset($currentProduct['public']) && $currentProduct['public']  == 'homme') echo 'selected'?>>Homme</option>
                        <option value="femme" <?php if(isset($currentProduct['public']) && $currentProduct['public']  =='femme') echo 'selected'?>>Femme</option>
                        <option value="mixte" <?php if(isset($currentProduct['public']) && $currentProduct['public']  =='mixte') echo 'selected'?>>Mixte</option>
                        
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>



            <div class="field is-horizontal">
              <div class="field-label is-normal">
                <label class="label">Photo produit</label>
              </div>
              <div class="field-body">
                <div class="field">
                <div class="file has-name">
                  <label class="file-label">
                    <input class="file-input <?php if (isset($errorPicture))echo "has-border-danger" ;?> " type="file" name="picture" />
                    <span class="file-cta">
                      <!-- <span class="file-icon">
                        <i class="fas fa-upload"></i>
                      </span> -->
                      <span class="file-label">Choisir un fichier </span>
                    </span>
                    <span class="file-name"> Parcourir </span>
                  </label>
                </div>
                <?php if (isset($errorPicture))echo $errorPicture ;?>
                </div>
              </div>
            </div>

            <input type="hidden" name="current_picture" value="<?php if(isset($currentProduct['picture'])) echo $currentProduct['picture']?>">

            <?php if(isset($currentProduct['picture']) && !empty($currentProduct['picture'])): ?>
              <div class="field is-horizontal">
                  <div class="field-label is-normal">
                    <label class="label">Photo actuelle</label>
                  </div>
                <div class="field-body">
                  <div class="field">
                    <img src="<?= $currentProduct['picture'] ?>" class="picture_product" alt="<?php if(isset($currentProduct['title'])) echo $currentProduct['title']; ?>">
            <?php endif; ?>
              </div>
                </div>
              </div>


            <div class="field is-horizontal">
              <div class="field-label is-normal">
                <label class="label">Description</label>
              </div>
              <div class="field-body">
                <div class="field">
                  <div class="control">
                    <textarea
                      class="textarea" name="description"
                      placeholder="Entrer une description du produit"><?php if(isset($currentProduct['description'])) echo $currentProduct['description'];?></textarea>
                  </div>
                </div>
              </div>
            </div>


            <div class="field is-horizontal">
              <div class="field-label is-normal">
                <label class="label">Prix / Stock</label>
              </div>
              <div class="field-body">
                <div class="field">
                    <input class="input" type="text" name="price" placeholder="Entrer un prix produit" value="<?php if(isset($currentProduct['price'])) echo $currentProduct['price'];?>" />
                  </p>
                </div>
                <div class="field">
                    <input class="input" type="text" name="stock" placeholder="Entrer le stock produit" value="<?php if(isset($currentProduct['stock'])) echo $currentProduct['stock'];?>"/>
                  </p>
                </div>
              
              </div>
            </div>
          

      
            <!-- <div class="field is-horizontal">
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
                        <span>Enregistrer</span>
                      </button>
                    </div>
                    <div class="control">
                      <button
                        type="button"
                        class="button is-primary is-outlined">
                        <span>Reset</span>
                      </button>
                    </div>
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

