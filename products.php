<?php
  //if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}   || !empty($_GET['category'])
  if(session_id() == '' || !isset($_SESSION)){
    session_start();
  
    if(isset($_GET['search'])){
      if(!empty($_GET['Asearch'])){
        $_SESSION['name'] = $_GET['Asearch'];
      }
      $_SESSION['category'] = $_GET['category'];
    }
  
    include 'config.php';
  }
?>


<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Products || BOLT Sports Shop</title>
    <link rel="stylesheet" href="css/foundation.css" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" />
    <script src="js/vendor/modernizr.js"></script>
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
          <li class='active'><a href="products.php">Products</a></li>
          <li><a href="newsletter.php">Newsletter</a></li>
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
              echo '<li><a href="register.php">Register</a></li>';
            }
          ?>
        </ul>
      </section>
    </nav>

    <div class="row" style="margin-top:10px;">
      <div class="small-12">
        <div class="row">
          <div class="small-12">
            <form action="" method="get">

              <input type="search" name="Asearch">
                
              <select name="category">
                <option value="">---</option>
                <option value="Street wear">Street wear</option>
                <option value="Sportswear">Sportswear</option>
                <option value="Accessory">Accessory</option>
              </select>
              <button name="search"><i class="fas fa-search"></i></button>
            </form>
          </div>
        </div>
        <?php
          
          $product_id = array();
          $product_quantity = array();

          // Fixe the position of current page
          // First Method
          $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;

          // Second Method
          // if(isset($_GET['page']) && !empty($_GET['page'])){
          //   $currentPage = (int) strip_tags($_GET['page']);
          // }else{
          //     $currentPage = 1;
          // }

          // Recuperation of total number of articles
          $num_articles = $mysqli->query("SELECT * FROM products")->num_rows;

          // Limit article per page
          $art_per_page = 3;
          // initialisation du nombre d'article pour la pagination
          // que retourne la requête SQL
          // $nbArticle = 0; //- Si > 3 =>pagination sinon rien

          // Fixed number of pages
          // if( $num_articles > $art_per_page)
          //   $pages = ceil ( $num_articles / $art_per_page);
          // else
          //   $pages = 1;
          
          // Fixed the first article to screen
          // First Method
          $param = ($page-1)*$art_per_page;

          // Second Method
          // $param = ($currentPage*$art_per_page) - $art_per_page;

          // Set a null char in session->name if research per name is empty
          if(isset($_GET['search']) && empty($_GET['Asearch'])){
            $_SESSION['name'] = '';
            $search = "%".$_SESSION['name']."%";
          }

          // General request
          if (empty($_GET['Asearch']) && empty($_GET['category'])){
            $search = "%".$_SESSION['name']."%"; //-undefined on first load page 
            $query = $mysqli->query("SELECT * FROM products WHERE product_name LIKE '$search' LIMIT $param,$art_per_page");
            //$nbArticle = $mysqli->query("SELECT * FROM products WHERE Category LIKE '$search'")->num_rows;
          }
          
          // Function to screen result of request
          function bouclewhile($query){
            $i=0;
            while( $obj = $query->fetch_object() ) {
              echo '<div class="large-4 columns">';
                echo '<p><h3>'.                               $obj->product_name.'</h3></p>';
                echo '<img src="images/products/'.            $obj->product_img_name.'"/>';
                echo '<p><strong>Product Code</strong>: '.    $obj->product_code.'</p>';
                echo '<p><strong>Description</strong>: '.     $obj->product_desc.'</p>';
                echo '<p><strong>Units Available</strong>: '. $obj->qty.'</p>';
                echo '<p><strong>Price (Per Unit)</strong>: '.$obj->price.'</p>';

                if($obj->qty > 0){
                  echo '<p><a href="update-cart.php?action=add&id='.$obj->id.'"><input type="submit" value="Add To Cart" style="clear:both; background: #0078A0; border: none; color: #fff; font-size: 1em; padding: 10px;" /></a></p>';
                }
                else {
                  echo 'Out Of Stock!';
                }
              echo '</div>';
              $i++;
            }
          }

          // Research per article name
            if(!empty($_GET['Asearch']) && empty($_GET['category']))
            {
              $search = "%".$_SESSION['name']."%";
              $query = $mysqli->query("SELECT * FROM products WHERE product_name LIKE '$search' LIMIT $param,$art_per_page");
              //$nbArticle = $mysqli->query("SELECT * FROM products WHERE Category LIKE '$search'")->num_rows;
            } 
            
          // Research per article category
            elseif (empty($_GET['Asearch']) && !empty($_GET['category']))
              {
                $category = $_SESSION['category'];

                $query = $mysqli->query("SELECT * FROM products WHERE Category LIKE '$category' LIMIT $param,$art_per_page");
                //$nbArticle = $mysqli->query("SELECT * FROM products WHERE Category LIKE '$category'")->num_rows;
              }
          // Research per article name and category
            elseif(!empty($_GET['Asearch']) && !empty($_GET['category']))
            {
              $category = $_SESSION['category'];
              
              $search = "%".$_SESSION['name']."%";

              $query = $mysqli->query("SELECT * FROM products WHERE product_name LIKE '$search' AND Category LIKE '$category'  LIMIT $param,$art_per_page");
              //$nbArticle = $mysqli->query("SELECT * FROM products WHERE Category LIKE '$search' AND Category LIKE '$category'")->num_rows;
            }
          
          if($query === FALSE){
            die(mysql_error());
          }
          if($query)
          {
            // Call a function 
            bouclewhile($query);
          }
          $_SESSION['product_id'] = $product_id;
        ?>
      </div>
    </div>

    <!-- Pagination of result -->
    <div class="row">
      <?php if ($page > 1): ?>
        <a href="products.php?page=<?=$page-1?>"><i class="fas fa-angle-double-left fa-sm"></i> Previous</a>
      <?php endif; 
      //if( $nbArticle > $art_per_page):?>
      <?php if ($page*$art_per_page < $num_articles): ?>
        <a href="products.php?page=<?=$page+1?>"><i class="fas fa-angle-double-right fa-sm"></i> Next</a>
      <?php endif; //endif;?>
    </div>
    
    <div class="row" style="margin-top:10px;">
      <div class="small-12">

        <footer style="margin-top:10px;">
          <p style="text-align:center; font-size:0.8em;clear:both;">&copy; BOLT Sports Shop. All Rights Reserved.</p>
        </footer>

      </div>
    </div>
    <script src="js/vendor/jquery.js"></script>
    <script src="js/foundation.min.js"></script>
    <script>
      $(document).foundation();
    </script>
  </body>
</html>
