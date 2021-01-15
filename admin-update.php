<?php

//if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
if(session_id() == '' || !isset($_SESSION)){session_start();}

if($_SESSION["type"]!="admin") {
  header("location:index.php");
}

include 'config.php';

  if(isset($_GET['id']))
  {
    $id = $_GET['id'];
    if (!empty($_POST)) {

      $code   = isset($_POST["pcode"]) ? $_POST['pcode'] : NULL;
      $name   = isset($_POST["pname"]) ? $_POST['pname']: '';
      $desc   = isset($_POST["pdesc"]) ? $_POST['pdesc']: '';
      $price  = isset($_POST["pPrice"]) ? $_POST['pPrice']: '';
      $qty    = isset($_POST["pqty"]) ? $_POST['pqty']: '';
      $cat    = isset($_POST["pcat"]) ? $_POST['pcat']: '';
      $img    = $_FILES['img2']['name']; 

      if(isset($_POST['submit']) )
      {
        if (isset($_FILES['img2']) AND $_FILES['img2']['error'] == 0)
        {
          // Testons si le fichier n'est pas trop gros
          if ($_FILES['img2']['size'] <= 1000000)
          {
            // Testons si l'extension est autorisée
            $infosfichier = pathinfo($img);
            $extension_upload = $infosfichier['extension'];
            $extensions_autorisees = array('jpg', 'jpeg', 'gif', 'png');

            if (in_array($extension_upload,
                $extensions_autorisees))
            {
              // On peut valider le fichier et le stocker définitivement dans le répertoire (images/products)
              move_uploaded_file($_FILES['img2']['tmp_name'], 'images/products/' .basename($img));
              // Add to database request
              if($mysqli->query("UPDATE products SET id=$id, product_code='$code', product_name='$name', product_desc='$desc', product_img_name='$img', qty=$qty, price=$price, Category='$cat' WHERE id = $id"))
              {
                //echo "Product updated successfully !";
                header ("location:products.php");
              }
            }
          } else 
          { //Si l'image est trop grande on affiche le message       
            echo 'Image trop grand';
          }    
        }
        
        $mysqli->query("UPDATE products SET id=$id, product_code='$code', product_name='$name', product_desc='$desc', qty=$qty, price=$price, Category='$cat' WHERE id = $id");
        //echo "Product updated successfully !";
        header ("location:products.php");
        
      }
    }  
    $result = $mysqli->query("SELECT * FROM products WHERE id = $id");

    $products = $result->fetch_assoc();
  }  

  //header ("location:admin.php");

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <link rel="stylesheet" href="css/foundation.css" />
        <script src="js/vendor/modernizr.js"></script>
        <title>Update</title>
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
        <form action="admin-update.php?id=<?=$products['id']?>" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="small-8">
                    <div class="row">
                        <div class="small-4 columns">
                            <label for="right-label" class="right inline">ID</label>
                        </div>
                        <div class="small-8 columns">
                            <input type="text" id="right-label" value="<?=$products['id']?>" name="id" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="small-4 columns">
                            <label for="right-label" class="right inline">Product Code</label>
                        </div>
                        <div class="small-8 columns">
                            <input type="text" id="right-label" value="<?=$products['product_code']?>" name="pcode">
                        </div>
                    </div>
                    <div class="row">
                        <div class="small-4 columns">
                            <label for="right-label" class="right inline">Product name</label>
                        </div>
                        <div class="small-8 columns">
                            <input type="text" id="right-label" value="<?=$products['product_name']?>" name="pname">
                        </div>
                    </div>
                    <div class="row">
                        <div class="small-4 columns">
                            <label for="right-label" class="right inline">Product description</label>
                        </div>
                        <div class="small-8 columns">
                            <input type="text" id="right-label" value="<?=$products['product_desc']?>" name="pdesc">
                        </div>
                    </div>
                    <div class="row">
                        <div class="small-4 columns">
                            <label for="right-label" class="right inline">Quantity</label>
                        </div>
                        <div class="small-8 columns">
                            <input type="number" id="right-label" value="<?=$products['qty']?>" name="pqty">
                        </div>
                    </div>
                    <div class="row">
                        <div class="small-4 columns">
                            <label for="right-label" class="right inline">Price</label>
                        </div>
                        <div class="small-8 columns">
                            <input type="number" id="right-label" value="<?=$products['price']?>" name="pPrice">
                        </div>
                    </div>
                    <div class="row">
                        <div class="small-4 columns">
                            <label for="right-label" class="right inline">Category</label>
                        </div>
                        <div class="small-8 columns">
                            <input type="text" id="right-label" value="<?=$products['Category']?>" readonly>
                            <select name="pcat" id="">
                                <option value="Street wear">Street wear</option>
                                <option value="Sportswear">Sportswear</option>
                                <option value="Accessory">Accessory</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="small-4 columns">
                            <label for="right-label" class="right inline">Image</label>
                        </div>
                        <div class="small-8 columns">
                          <input type="text" value="<?=$products['product_img_name']?>" readonly>
                          <input type="file" id="right-label" name="img2">
                        </div>
                    </div>
                    <div class="row">
                        <div class="small-4 columns">

                        </div>
                        <div class="small-8 columns">
                            <input type="submit" name="submit" id="right-label" value="Update" style="background: #0078A0; border: none; color: #fff; font-family: 'Helvetica Neue', sans-serif; font-size: 1em; padding: 10px;">
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
