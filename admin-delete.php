<?php

    //if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
    if(session_id() == '' || !isset($_SESSION)){session_start();}

    if($_SESSION["type"]!="admin") {
        header("location:index.php");
    }

    include 'config.php';

    $msg = '';

    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $result = $mysqli->query("SELECT * FROM products WHERE id = $id");

        $products = $result->fetch_assoc();

        // $obj = $result->fetch_object();
        if(!$result){
            exit("Product doesn't exist with that id!");
        }

        if(isset($_GET['confirm'])){
            if($_GET['confirm'] == 'yes'){
                $mysqli->query("DELETE FROM products WHERE id = $id");
                header('location: admin.php');
            } else {
                header('location: index.php');
            }
        }
    }

?>

<div class="content delete">
	<h2>Suppression du produit</h2>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php else: ?>
	<p>Êtes-vous sur de vouloir supprimer le produit : <?=$products['product_name']?>?</p>
    <div class="yesno">
        <a href="admin-delete.php?id=<?=$id?>&confirm=yes">Oui</a>
        <a href="admin-delete.php?id=<?=$id?>&confirm=no">Non</a>
    </div>
    <?php endif; ?>
</div>