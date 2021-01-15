<?php
  //if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
  if(session_id() == '' || !isset($_SESSION)){session_start();}

  include 'config.php';

  // For use PHPMailer
  // In terminal insert command
  // composer require PHPMailer/PHPMailer

  use PHPMailer\PHPMailer\PHPMailer;

  require 'vendor/autoload.php';

  $mail = new PHPMailer;

  //Server settings                                           
  $mail->isSMTP();                                                  // Send using SMTP
  $mail->Host       = 'smtp.gmail.com';                             // Set the SMTP server to send through
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;               // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
  $mail->Port       = 587;                                          // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
  $mail->SMTPAuth   = true;                                         // Enable SMTP authentication
  $mail->Username   = 'yourmail@gmail.com';                         // SMTP username
  $mail->Password   = $passe;                                       // SMTP password

  //$message = "" ;

  if(isset($_SESSION['cart'])) {

    $total = 0;

    foreach($_SESSION['cart'] as $product_id => $quantity) {

      $result = $mysqli->query("SELECT * FROM products WHERE id = ".$product_id);

      if($result){

        if($obj = $result->fetch_object()) {

          $cost = $obj->price * $quantity;

          $user = $_SESSION["username"];

          $mail->setFrom('mail@gmail.com');
          $mail->addAddress('mail@gmail.com',''); 

          $mail->addEmbeddedImage('images/bolt.jpg','background','bolt.jpg');

          // Content
          $mail->isHTML(true);

          $req = $mysqli->query(
            "INSERT INTO orders (product_code, product_name, product_desc, price, units, total, email) 
             VALUES ('$obj->product_code', '$obj->product_name','$obj->product_desc', $obj->price, $quantity, $cost, '$user')");

          if($req){
            $newqty = $obj->qty - $quantity;
            if($mysqli->query("UPDATE products SET qty = ".$newqty." WHERE id = ".$product_id)){

            }
          }
          //send mail script
          $mail->Subject = "Your Order command";
          $message = "<html><body>";
          $message .= "<img src='cid:background'>";

          $query = $mysqli->query("SELECT * FROM orders WHERE email = '$user' AND date = now() ORDER BY date DESC");

          if($query){
            while ($obj = $query->fetch_object()){
              $message .= ' <p><h4>Order ID ->'.$obj->id.'</h4></p>';
              $message .= ' <p><strong>Product Code</strong>: '.    $obj->product_code.'</p>';
              $message .= ' <p><strong>Product Name</strong>: '.    $obj->product_name.'</p>';
              $message .= ' <p><strong>Price Per Unit</strong>: '.  $obj->price.'</p>';
              $message .= ' <p><strong>Units Bought</strong>: '.    $obj->units.'</p>';
            }
            $message .= '<p><strong>Total Cost</strong>: '.         $cost.'</p>';
            $message .= "</body></html>";
            $mail->Body = <<< EOT
                                 $message 
                              EOT ;
                              
            $mail->send();
          }
        }
      }
    }
  }
  unset($_SESSION['cart']);  //- Session deconnexion
  header("location:success.php");  //- Redirect to success.php
?>
