<?php

    //if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
    if(session_id() == '' || !isset($_SESSION)){session_start();}

    if($_SESSION["type"]!="admin") {
    header("location:index.php");
    }

    include 'config.php';

    if(!empty($_POST["pcode"]) && !empty($_POST["pname"]) && !empty($_POST["pdesc"]) && !empty($_POST["pPrice"]) && !empty($_POST["pqty"])){
        $code = $_POST["pcode"];
        $name = $_POST["pname"];
        $desc = $_POST["pdesc"];
        $price = $_POST["pPrice"];
        $qty = $_POST["pqty"];
        $cat = $_POST['cat'];
        $img = $_FILES['img']['name'];

        if (isset($_FILES['img']) AND $_FILES['img']['error'] == 0)
            {
                // Testons si le fichier n'est pas trop gros
                if ($_FILES['img']['size'] <= 1000000)
                {
                    // Testons si l'extension est autorisée
                    $infosfichier = pathinfo($_FILES["img"]['name']);
                    $extension_upload = $infosfichier['extension'];
                    $extensions_autorisees = array('jpg', 'jpeg', 'gif', 'png');
                    if (in_array($extension_upload,
                    $extensions_autorisees))
                    {
                        // On peut valider le fichier et le stocker définitivement dans le répertoire (images/products)
                        move_uploaded_file($_FILES['img']['tmp_name'], 'images/products/' .basename($_FILES['img']['name']));
                        // Add to database request
                        if($mysqli->query("INSERT INTO products (product_code,product_name,product_desc,product_img_name,qty,price,Category) VALUES ('$code','$name','$desc','$img','$qty','$price','$cat')")){
                            echo "Product added successfully !";
                            header ("location:products.php");
                        }
                    }
                //Si l'image est trop grande on affiche le message
                }else {echo 'Image trop grand';}
        }
    }    

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <link rel="stylesheet" href="css/foundation.css" />
        <script src="js/vendor/modernizr.js"></script>
        <title>Add</title>
    </head>
    <body>
        <nav class="top-bar" data-topbar role="navigation">
            <ul class="title-area">
                <li class="name">
                <h1><a href="index.php">BOLT Sports Shop</a></h1>
                </li>
                <li class="toggle-topbar menu-icon"><a href="#"><span></span></a></li>
            </ul>

            <section class="top-bar-section">
            <!-- Right Nav Section -->
                <ul class="right">
                <li><a href="about.php">About</a></li>
                <li><a href="products.php">Products</a></li>
                <li><a href="cart.php">View Cart</a></li>
                <li><a href="orders.php">My Orders</a></li>
                <li><a href="contact.php">Contact</a></li>
                <?php

                if(isset($_SESSION['username'])){
                    echo '<li><a href="account.php">My Account</a></li>';
                    echo '<li><a href="logout.php">Log Out</a></li>';
                }
                else{
                    echo '<li><a href="login.php">Log In</a></li>';
                    echo '<li class="active"><a href="register.php">Register</a></li>';
                }
                ?>
                </ul>
            </section>
        </nav>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="small-8">

                    <div class="row">
                        <div class="small-4 columns">
                            <label for="right-label" class="right inline">Product Code</label>
                        </div>
                        <div class="small-8 columns">
                            <input type="text" id="right-label" placeholder="BOLT1" name="pcode">
                        </div>
                    </div>
                    <div class="row">
                        <div class="small-4 columns">
                            <label for="right-label" class="right inline">Product name</label>
                        </div>
                        <div class="small-8 columns">
                            <input type="text" id="right-label" placeholder="Sport Shoes" name="pname">
                        </div>
                    </div>
                    <div class="row">
                        <div class="small-4 columns">
                            <label for="right-label" class="right inline">Product description</label>
                        </div>
                        <div class="small-8 columns">
                            <input type="text" id="right-label" placeholder="description of product..." name="pdesc">
                        </div>
                    </div>
                    <div class="row">
                        <div class="small-4 columns">
                            <label for="right-label" class="right inline">Quantity</label>
                        </div>
                        <div class="small-8 columns">
                            <input type="number" id="right-label" placeholder="" name="pqty">
                        </div>
                    </div>
                    <div class="row">
                        <div class="small-4 columns">
                            <label for="right-label" class="right inline">Price</label>
                        </div>
                        <div class="small-8 columns">
                            <input type="number" id="right-label" placeholder="500.00" name="pPrice">
                        </div>
                    </div>
                    <div class="row">
                        <div class="small-4 columns">
                            <label for="right-label" class="right inline">Category</label>
                        </div>
                        <div class="small-8 columns">
                            <select id="right-label" name="cat">
                                <option value="Sportswear">Sportswear</option>
                                <option value="Street wear">Street wear</option>
                                <option value="Accessory">Accessory</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="small-4 columns">
                            <label for="right-label" class="right inline">Image</label>
                        </div>
                        <div class="small-8 columns">
                            <input type="file" id="right-label" name="img">
                        </div>
                    </div>
                    <div class="row">
                        <div class="small-4 columns">

                        </div>
                        <div class="small-8 columns">
                            <input type="submit" id="right-label" value="Add" style="background: #0078A0; border: none; color: #fff; font-family: 'Helvetica Neue', sans-serif; font-size: 1em; padding: 10px;">
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <script src="js/vendor/jquery.js"></script>
        <script src="js/foundation.min.js"></script>
        <script>
            $(document).foundation();
        </script>
    </body>
</html>