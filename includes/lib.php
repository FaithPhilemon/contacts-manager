<?php 
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;


    require_once dirname(__DIR__)."/PHPMailer/src/PHPMailer.php";
    require_once dirname(__DIR__)."/PHPMailer/src/SMTP.php";
    require_once dirname(__DIR__)."/PHPMailer/src/Exception.php";


    require_once dirname(__DIR__)."/config/config.php";

    // checking for incoming form requests
    if(isset($_POST['btnSubmitSignup'])){
        register(trim($_POST['username']), trim($_POST['email']), trim($_POST['password']));
    }


    if(isset($_POST['btnSubmitLogin'])){
        login(trim($_POST['usernameOrEmail']), trim($_POST['password']));
    }


    if(isset($_POST['btnSubmitContact'])){
        session_start();
        add_contact($_SESSION['userId'], $_POST['category'], $_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['address']);
    }

    if(isset($_POST['btnUpdateContact'])){
        update_contact($_POST['id'], $_POST['category'], $_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['address']);
    }

    if(isset($_POST['btnDeleteContact'])){
        delete_contact($_POST['id']);
    }

    if(isset($_POST['btnSendEmail'])){
        if($_POST['reciepient'] == 0){
            if(!session_start()){
                session_start();
            }

            $contacts = list_contacts($_SESSION['userId']);
            foreach($contacts as $contact){
                send_email($contact['email'], $_POST['subject'], $_POST['message']);
            }
        }else{
            send_email($_POST['reciepient'], $_POST['subject'], $_POST['message']);
        }
        
    }


    // Auth funcitons
    function register($username, $email, $password){
        global $conn, $appUrl;

        // validate username duplicate
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0){
            header("Location: ". $appUrl . "register.php?error=userExists");
            exit();
        }


        // validate email duplicate
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0){
            header("Location: ". $appUrl . "register.php?error=emailExists");
            exit();
        }



        if(!empty($username) && !empty($email) && !empty($password)){
            $password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $username, $email, $password);
            if($stmt->execute()) {
                header("Location: ". $appUrl . "login.php?registration=success");
                exit();
            }else{
                exit(mysqli_stmt_error($stmt));
            }

        }else{
            header("Location: ". $appUrl . "register.php?error=requiredFields");
        }
    }

    
    function login($usernameOrEmail, $password) {
        global $conn, $appUrl;

        $sql = "SELECT id, username, email, password FROM users WHERE username = ? OR email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $usernameOrEmail, $usernameOrEmail);
        $stmt->execute();
        $result = $stmt->get_result();


        if($result->num_rows === 1){
            $user = $result->fetch_assoc();

            if(password_verify($password, $user["password"])){
                session_start();

                $_SESSION["userId"] =  $user["id"];
                $_SESSION["username"] =  $user["username"];
                $_SESSION["email"] =  $user["email"];

                header("Location: ". $appUrl . "index.php?login=success");
                exit();

            }else{
                header("Location: ". $appUrl . "login.php?error=incPass");
            }
        }else{
            header("Location: ". $appUrl . "login.php?error=userNF");
        }
    }

    function logout() {
        session_start();
        
        global $appUrl;
        $_SESSION = [];
        session_destroy();
        header("Location: ". $appUrl . "login.php");
    }

    function check_authentication() {
        session_start();

        global $appUrl;
        if(!isset($_SESSION['userId'])){
            header("Location: ". $appUrl . "login.php");
        }
    }


    function add_contact($userId, $categoryId, $firstName, $lastName, $email, $address){
        global $conn, $appUrl;

        
        if(!empty($firstName) && !empty($email)){

            $sql = "INSERT INTO contacts (user_id, category_id, first_name, last_name, email, address) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iissss", $userId, $categoryId, $firstName, $lastName, $email, $address);
            if($stmt->execute()) {
                header("Location: ". $appUrl . "index.php?success=newContact");
            }else{
                // return mysqli_stmt_error($stmt);
                header("Location: ". $appUrl . "index.php?error=newContact");
            }

        }else{
            header("Location: ". $appUrl . "index.php?error=requiredFieldsContact");
        }
    }

    function list_contacts($userId){
        global $conn;
        $sql = "SELECT * FROM contacts WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        $contacts = [];
        while($row = $result->fetch_assoc()) {
            $contacts[] = $row;
        }

        return $contacts;

    }

    function update_contact($contactId, $categoryId, $firstName, $lastName, $email, $address){
        global $conn, $appUrl;

        $sql = "UPDATE contacts SET category_id = ?, first_name = ?, last_name = ?, email = ?, address = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);

        $stmt->bind_param("issssi", $categoryId, $firstName, $lastName, $email, $address, $contactId);
        
        if($stmt->execute()){
            header("Location: ". $appUrl . "index.php?contact=updated");
        }else{
            die("Error occured ". $stmt->error);
        }
    }

    function delete_contact($contactId){
        global $conn, $appUrl;

        $sql = "DELETE FROM contacts WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $contactId);
        
        if($stmt->execute()){
            header("Location: ". $appUrl . "index.php?contact=deleted");
        }else{
            return mysqli_stmt_error($stmt);
        }
    }

    function get_category($categoryId){
        global $conn;

        $sql = "SELECT name FROM categories WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $categoryId);
        $stmt->execute();
        $result = $stmt->get_result();
        while($category = $result->fetch_assoc()){
            return $category['name'];
        }
        
    }


    function get_categories(){
        global $conn;
    
        $sql = "SELECT id, name FROM categories";
        $result = $conn->query($sql);
    
        if (!$result) {
            // return false;
            echo $conn->connect_error;
        }
    
        $categories = [];
    
        while ($category = $result->fetch_assoc()) {
            $categories[] = $category;
        }
    
        return $categories;
    }
    

    function get_num_rows($userId){
        global $conn;

        $sql = "SELECT * FROM contacts WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows;
    }

    function send_email($reciepeintEmail, $subject, $message){
        global $smtpHost, $smtpPort, $smtpUsername, $smtpPassword;
    
        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);
        
        try {
            //Server settings
            $mail->isSMTP();                                         //Send using SMTP
            $mail->Host       = $smtpHost;                           //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                //Enable SMTP authentication
            $mail->Username   = $smtpUsername;                       //SMTP username
            $mail->Password   = $smtpPassword;                       //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         //Enable implicit TLS encryption
            $mail->Port       = $smtpPort;                           //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        
            //Recipients
            $mail->setFrom($smtpUsername, 'Contact Manager');
            $mail->addAddress($reciepeintEmail, "Reciepient Name");     //Add a recipient
            $mail->addReplyTo('no-reply@example.com', 'Information');
            
            $emailBody = $message;
            
            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $emailBody;
        
            if($mail->send()){
                return true;
            }

        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

    }

   
?>