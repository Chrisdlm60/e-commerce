<?php
    //if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
    if(session_id() == '' || !isset($_SESSION)){session_start();}

    if($_SESSION["type"]!="admin") {
        header("location:index.php");
    }

    include 'config.php';

    use PHPMailer\PHPMailer\PHPMailer;

    require 'vendor/autoload.php';

    $mail = new PHPMailer;

    //Server settings                                           
    $mail->isSMTP();                                                                        // Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                                                   // Set the SMTP server to send through
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;                                     // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port       = 587;                                                                // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
    $mail->SMTPAuth   = true;                                                               // Enable SMTP authentication
    $mail->Username   = 'chrisdlm.jeu@gmail.com';                                           // SMTP username
    $mail->Password   = $passe;                                                             // SMTP password

    //Recipients
    if(isset($_POST['submit'])){
        if(!empty($_POST['subject']) && !empty($_POST['msg'])){
            $subject = $_POST['subject'];
            $msg = $_POST['msg'];

            $mail->setFrom("chrisdlm.jeu@gmail.com", 'Bolt Shop');                          // Origine du mail
        
            $mail->addAddress('delmotte.christophe@hotmail.fr','Chris Dlm');                // Destinataire 
            $mail->addAddress('chrisdlm.jeu@gmail.com','');
            $query = $mysqli->query("SELECT * FROM users WHERE type='user' AND newsletter='oui'");
            
            if($query)
            {
                while($obj = $query->fetch_object()){
                    $mail->addAddress($obj->email,'');
                }
            }
            

            // Content
            $mail->isHTML(true);                                                            // On précise que le mail est au format HTML
            $mail->addEmbeddedImage('images/bolt.jpg','background','bolt.jpg');             // Préparation de l'image

            // Set email format to HTML
            $mail->Subject = $subject;                                                      // Objet du mail
            
            //$decode = utf8_decode($msg); 
            $message = "<html>
                            <body>
                                <img src='cid:background'><br><br>
                                <h1>Bolt Shop</h1>
                                <h3>Nous avons un message pour vous !!</h3><br>".
                                utf8_decode($msg)."
                            </body>
                        </html>";
                        
            $mail->Body = <<< EOT
                $message
            EOT;                                                            // Corps du mail
                
            $mail->send();

            // Sauvegarde en base de données
            $mysqli->query("INSERT INTO newsletter (date,message) VALUES (now(), '$msg')");
            
        } else {
            print_r("Vous avez oublié de renseigné un champ merci de le remplir avant soumission !!");
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin || BOLT Newsletter</title>
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
            <li><a href="newsletter.php">Newsletter</a></li>
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
                echo '<li><a href="register.php">Register</a></li>';
            }
            ?>
            </ul>
        </section>
        </nav>

        <form action="" method="post">
            <div class="row" style="margin-top:50px">
                <div class="small-8">
                    <div class="row">
                        <div class="small-4 columns">
                            <label for="right-label" class="right inline">Newsletter subject</label>
                        </div>
                        <div class="small-8 columns">
                            <input type="text" placeholder="subject" name="subject">
                        </div>
                    </div>
                    <div class="row">
                        <div class="small-4 columns">
                            <label for="right-label" class="right inline">Newsletter message</label>
                        </div>
                        <div class="small-8 columns">
                            <textarea type="text" placeholder="message..." name="msg"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="small-4 columns">

                        </div>
                        <div class="small-8 columns">
                            <input type="submit" name="submit" id="right-label" value="Envoyer" style="background: #0078A0; border: none; color: #fff; font-family: 'Helvetica Neue', sans-serif; font-size: 1em; padding: 10px;">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </body>
</html>