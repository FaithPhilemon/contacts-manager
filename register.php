<?php 
  // initiate variables to be used for error and success messages
  $usernameError = $emailError = $requiredFieldError = "";

  // checking for incoming query string requests
  if(isset($_GET['error'])){
    if($_GET['error'] == 'userExists'){
      $usernameError = 'This username already exists, please choose a different username';
    }
  }


  if(isset($_GET['error'])){
    if($_GET['error'] == 'emailExists'){
      $emailError = 'This email address already exists, please choose a different email';
    }
  }


  if(isset($_GET['error'])){
    if($_GET['error'] == 'requiredFields'){
      $requiredFieldError = 'One or more fields empty, please fill all required fields';
    }
  }



  include 'config/config.php'; 
  $pageTitle = "Register";
  include_once APPPATH.'/includes/header.php'; 
?>
<body>

<div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card-group mb-0">
          <div class="card p-4">
            <div class="card-body">
              <h1>Register</h1>
              <p class="text-muted">Create a new account</p>

              <?php 

                if($requiredFieldError != ""){
                  echo '<div class="alert alert-danger" role="alert">
                          <strong>'.$requiredFieldError.'</strong>
                        </div>';
                }


                if($usernameError != ""){
                  echo '<div class="alert alert-danger" role="alert">
                          <strong>'.$usernameError.'</strong>
                        </div>';
                }


                if($emailError != ""){
                  echo '<div class="alert alert-danger" role="alert">
                          <strong>'.$emailError.'</strong>
                        </div>';
                }
              
              ?>
              <form action="includes/lib.php" method="post">
                <div class="input-group mb-3">
                  <input type="text" name="username" class="form-control" placeholder="Username" required>
                </div>

                <div class="input-group mb-3">
                  <input type="email" name="email" class="form-control" placeholder="Email" required>
                </div>

                <div class="input-group mb-4">
                  <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>
                <div class="row">
                  <div class="col-6">
                    <button type="submit" name="btnSubmitSignup" class="btn btn-primary px-4">SIGN UP</button>
                  </div>
                  <div class="col-6 text-right">
                    <button type="button" class="btn btn-link px-0">Forgot password?</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
          <div class="card text-white bg-primary py-5 d-md-down-none" style="width:44%">
            <div class="card-body text-center">
              <div>
                <h2>Login</h2>
                <p>Already have an account with us? Click the button below to login to your account.</p>
                <a href="login.php" class="btn btn-primary active mt-3">Login Now!</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php include 'includes/footer.php'; ?>