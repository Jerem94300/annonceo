<?php
require_once('../include/init.php');

if (!adminConnected()) {
    header('location: ' . URL . 'index.php');
    exit;
}

//Requête de suppression d'une commande


if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $stmt = $connect_db->prepare("DELETE FROM `order` WHERE id_order = :id");
    $stmt->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        $_SESSION['msg_success'] = "La commande #".$_GET['id']." a été supprimée avec succès.";
    } else {
        $_SESSION['msg_error'] = "Erreur lors de la suppression.";
    }

    header('location: ' . URL . 'admin/gestion_commande.php');
    exit;
}



// Récupération des commandes et produits
$dataOrder = $connect_db->query("
    SELECT `order`.id_order AS 'Order', user.lastName, user.firstName, user.email, user.city, `order`.state, `order`.date FROM user INNER JOIN `order` ON user.id_user = `order`.user_id");
$order = $dataOrder->fetchAll(PDO::FETCH_ASSOC);

//  Mise à jour du state uniquement pour la commande concernée
// $POST['update_state'] : Si le formulaire a été soumis et correspond au champ caché 'update_state'
// $POST['id_order'] : Si le champ caché 'id_order' est défini
if (isset($_POST['update_state']) && isset($_POST['id_order']) && isset($_POST['state'])) {
    $stmt = $connect_db->prepare("UPDATE `order` SET state = :state WHERE id_order = :id_order");
    $stmt->bindValue(':state', $_POST['state'], PDO::PARAM_STR);
    $stmt->bindValue(':id_order', $_POST['id_order'], PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        $_SESSION['msg_success'] = "L'état de la commande #".$_POST['id_order']." a été mis à jour avec succès.";
    } else {
        $_SESSION['msg_error'] = "Erreur lors de la mise à jour.";
    }

    header('location: ' . URL . 'admin/gestion_commande.php');
    exit;
}

//  Récupération des détails de la commande sélectionnée
$orderDetails = [];
if (isset($_GET['action']) && $_GET['action'] == 'update' && isset($_GET['id'])) {
    $dataDetails = $connect_db->prepare("
        SELECT product.reference, product.category, product.title, product.description, product.color, product.size, order_details.quantity, product.price FROM order_details INNER JOIN product ON product.id_product = order_details.product_id WHERE order_details.order_id = :id");
    $dataDetails->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
    $dataDetails->execute();
    $orderDetails = $dataDetails->fetchAll(PDO::FETCH_ASSOC);
}

$stateClass = [
  'pending' => 'has-background-warning',   // Jaune pour "En attente"
  'treatment' => 'has-background-info',   // Bleu pour "En traitement"
  'send' => 'has-background-primary',     // Bleu clair pour "Envoyé"
  'completed' => 'has-background-success', // Vert pour "Terminé"
  'cancelled' => 'has-background-danger'  // Rouge pour "Annulé"
];




require_once('include/header.php');
?>

<!--  Affichage du message de succès ou d'erreur -->
<?php if (isset($_SESSION['msg_success'])): ?>
    <div class="notification is-success">
        <?= $_SESSION['msg_success']; ?>
    </div>
    <?php unset($_SESSION['msg_success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['msg_error'])): ?>
    <div class="notification is-danger">
        <?= $_SESSION['msg_error']; ?>
    </div>
    <?php unset($_SESSION['msg_error']); ?>
<?php endif; ?>

<section class="section is-main-section">
    <div class="card has-table">
        <header class="card-header">
            <p class="card-header-title">
                <span class="icon"><span class="mdi mdi-cart-outline"></span></span>
                Liste des commandes
            </p>
        </header>
        <div class="card-content">
            <table class="table is-fullwidth is-striped is-hoverable">
                <thead>
                    <tr>
                        <th>N° Commande</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Ville</th>
                        <th>État</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($order as $arrayOrder): ?>
                        <?php
                      $selectedClass = isset($stateClass[$arrayOrder['state']]) ? $stateClass[$arrayOrder['state']] : '';
                      ?>
                            <td>FAMMS <?= $arrayOrder['Order']; ?></td>
                            <td><?= $arrayOrder['lastName']; ?></td>
                            <td><?= $arrayOrder['firstName']; ?></td>
                            <td><?= $arrayOrder['email']; ?></td>
                            <td><?= $arrayOrder['city']; ?></td>
                            <td>
                                <!-- Formulaire pour mettre à jour uniquement l'état de la commande -->
                                <form method="post">
                                    <input type="hidden" name="id_order" value="<?= $arrayOrder['Order']; ?>">
                                    <div class="select is-primary">

                                    <!-- onchange : Cet événement détecte tout changement de sélection dans la liste. -->
                                     <!-- "this.form.submit()" : this représente ici l’élément <select>, et this.form.submit() signifie "Soumettre automatiquement le formulaire auquel appartient ce <select>" dès qu’un nouvel état est choisi. -->
                                        <select name="state" class="select <?= $selectedClass ?>" onchange="this.form.submit()">
                                            <option value="pending" <?= ($arrayOrder['state'] == 'pending') ? 'selected' : ''; ?>>En attente</option>
                                            <option value="treatment" <?= ($arrayOrder['state'] == 'treatment') ? 'selected' : ''; ?>>En traitement</option>
                                            <option value="send" <?= ($arrayOrder['state'] == 'send') ? 'selected' : ''; ?>>Envoyé</option>
                                            <option value="completed" <?= ($arrayOrder['state'] == 'completed') ? 'selected' : ''; ?>>Terminé</option>
                                            <option value="cancelled" <?= ($arrayOrder['state'] == 'cancelled') ? 'selected' : ''; ?>>Annulé</option>
                                        </select>
                                    </div>
                                    <input type="hidden" name="update_state" value="1">
                                </form>
                            </td>
                            <td><?= $arrayOrder['date']; ?></td>
                            <td>
                                <div class="buttons is-center">
                                    <a href="?action=update&id=<?= $arrayOrder['Order']; ?>" class="button is-small is-primary">
                                        <span class="icon"><i class="mdi mdi-eye"></i></span>
                                    </a>
                                    <!-- <button class="button is-small is-danger jb-modal" data-target="sample-modal-<?= $arrayOrder['Order']; ?>" type="button">
                                        <span class="icon"><i class="mdi mdi-trash-can"></i></span>
                                    </button> -->
                                </div>
                            </td>
                        </tr>
                        <!-- Modal supression commande -->
                        <div id="sample-modal-<?= $arrayOrder['Order']; ?>" class="modal jb-modal">
                            <div class="modal-background jb-modal-close"></div>
                            <div class="modal-card">
                            <header class="modal-card-head">
                            <p class="modal-card-title">Veuillez confirmez la suppression.</p>
                                <button class="delete jb-modal-close" aria-label="close"></button>
                            </header>
                            <section class="modal-card-body">
                                <p>Voulez-vous réelement supprimer cette commande ?</p>
                            </section>
                            <footer class="modal-card-foot">
                                <button class="button jb-modal-close">Annuler</button>
                                <a href='?action=delete&id=<?= $arrayOrder['Order'] ?>' class="button is-danger jb-modal-close">Supprimer</a>
                            </footer>
                        </div>
                        <button class="modal-close is-large jb-modal-close"     aria-label="close"></button>
                        </div>








                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Affichage des détails de la commande sélectionnée -->
    <?php if (!empty($orderDetails)): ?>
        <div class="card has-table">
            <header class="card-header">
                <p class="card-header-title">
                    <span class="icon"><span class="mdi mdi-cart-arrow-down"></span></span>
                    Détails de la commande #<?= $_GET['id']; ?>
                </p>
            </header>
            <div class="card-content">
                <table class="table is-fullwidth is-striped is-hoverable">
                    <thead>
                        <tr>
                            <th>Référence</th>
                            <th>Catégorie</th>
                            <th>Produit</th>
                            <th>Description</th>
                            <th>Couleur</th>
                            <th>Taille</th>
                            <th>Quantité</th>
                            <th>Prix Unitaire</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orderDetails as $product): ?>
                            <tr>
                                <td><?= $product['reference']; ?></td>
                                <td><?= $product['category']; ?></td>
                                <td><?= $product['title']; ?></td>
                                <td><?= $product['description']; ?></td>
                                <td><?= $product['color']; ?></td>
                                <td><?= $product['size']; ?></td>
                                <td><?= $product['quantity']; ?></td>
                                <td><?= number_format($product['price'], 2, ',', ' '); ?> €</td>
                                <td><?= number_format($product['quantity'] * $product['price'], 2, ',', ' '); ?> €</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</section>

<?php require_once('include/footer.php'); ?>
