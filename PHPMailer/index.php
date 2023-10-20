<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;



require "../PHPMailer/src/PHPMailer.php";
require "../PHPMailer/src/SMTP.php";
require "../PHPMailer/src/Exception.php";


// intialize variables 
$err_invalid_email = $err_required = $success_message = '';
// check if form has been submitted
if(isset($_POST['btnSubmit'])){
    // retrieve submitted data

    if(empty($_POST['name']) || empty($_POST['email']) || empty($_POST['message'])){
        $err_required = "One or more field(s) missing, please complete all fields before proceeding";

    }else{
        $name    = $_POST['name'];
        $email   = $_POST['email'];
        $message = $_POST['message'];


        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $err_invalid_email = "email is not valid, please provide a valid email address";
        }else{

          include "../database.php";


          $sql = "INSERT INTO contacts (`name`, `email`, `message`) VALUES('$name', '$email', '$message')";
          if(!mysqli_query($connection, $sql)){
            echo "Error occurred while creating record: " . mysqli_error($connection);
          }

          //Create an instance; passing `true` enables exceptions
          $mail = new PHPMailer(true);
          
          try {
              //Server settings
              $mail->isSMTP();                                            //Send using SMTP
              $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
              $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
              $mail->Username   = 'philemonfaith@gmail.com';                     //SMTP username
              $mail->Password   = 'xzjhtkcgtljkfvwe';                               //SMTP password
              $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
              $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
          
              //Recipients
              $mail->setFrom('philemonfaith@gmail.com', 'Contact Form');
              $mail->addAddress('rocketsoftwareltd@gmail.com', 'Website Admin');     //Add a recipient
              $mail->addReplyTo('no-reply@example.com', 'Information');
              
              $emailBody = "Name: $name <br> Email: $email <br> Message: $message";
              
              //Content
              $mail->isHTML(true);                                  //Set email format to HTML
              $mail->Subject = 'Message from contact form';
              $mail->Body    = $emailBody;
          
              $mail->send();
              $success_message = "form submitted successfully!";

          } catch (Exception $e) {
              echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
          }

            
        }
    }

}



?>

<html>

<head>

  <meta charset="UTF-8">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:100,200,300,400,500,600,700,800,900" rel="stylesheet">
  <link rel="stylesheet" href="style.css">

</head>

<body>

  <section class="contact-us" id="contact-section">
    <form id="contact" action="index.php" method="post">
     
      <div class="section-heading">
        <h4>Contact us</h4>
      </div>

      <?php 
        echo "<span style='color:red'>$err_required</span>";
        echo "<span style='color:red'>$err_invalid_email</span>";
        echo "<span style='color:green'>$success_message</span>";
      ?>
      <div class="inputField">
        <input type="text" name="name" id="name" placeholder="Your name" autocomplete="on" required>
        <span class="valid_info_name"></span>
      </div>

      <div class="inputField">
        <input type="email" name="email" id="email" placeholder="Your email" required>
        <span class="valid_info_email"></span>
      </div>

      <div class="inputField">
        <textarea name="message" id="message" placeholder="Your message" required></textarea>
        <span class="valid_info_message"></span>
      </div>

      <div class="inputField btn">
        <button type="submit" name="btnSubmit" id="form-submit" class="main-gradient-button">Send a message</button>
      </div>

      

    </form>
  </section>

  <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>

</body>

</html>