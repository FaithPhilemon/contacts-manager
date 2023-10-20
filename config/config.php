<?php 

    // app configuration
    $appName = "Contacts Manager";
    $appUrl = "http://localhost/contacts-manager/";
    $pageTitle = "";
    define("APPPATH", dirname(__DIR__));


    // database configuration
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "contacts-manager";


    // email configuration
    $smtpHost = "smtp.gmail.com";
    $smtpPort = 587;
    $smtpUsername = "philemonfaith@gmail.com";
    $smtpPassword = "xzjhtkcgtljkfvwe";
    

    // connection to the database
    $conn = mysqli_connect($servername, $username, $password, $database);
    if(!$conn) {
        echo "Error connecting ". mysqli_connect_error();
    }



?>