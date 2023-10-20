<?php 
  // initiate variables to be used for error and success messages
  $registrationSuccess = $passwordError = $userError = "";

  // checking for incoming query string requests
  if(isset($_GET['registration'])){
    if($_GET['registration'] == 'success'){
      $registrationSuccess = 'Your account has been successfully registered';
    }
  }


  if(isset($_GET['error'])){
    if($_GET['error'] == 'incPass'){
      $passwordError = 'Incorrect Password';
    }
  }

  if(isset($_GET['error'])){
    if($_GET['error'] == 'userNF'){
      $userError = 'User not found';
    }
  }

  include 'config/config.php'; 
  $pageTitle = "Login";
  include_once APPPATH.'/includes/header.php'; 
?>
<body>

<div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card-group mb-0">
          <div class="card p-4">
            <div class="card-body">
              <?php 

                if($registrationSuccess != ""){
                  echo '<div class="alert alert-success" role="alert">
                          <strong>'.$registrationSuccess.'</strong>
                        </div>';
                }
              
              ?>


              <h1>Login</h1>
              <p class="text-muted">Sign In to your account</p>

              <?php 

                if($passwordError != ""){
                  echo '<div class="alert alert-danger" role="alert">
                          <strong>'.$passwordError.'</strong>
                        </div>';
                }


                if($userError != ""){
                  echo '<div class="alert alert-danger" role="alert">
                          <strong>'.$userError.'</strong>
                        </div>';
                }

              
              ?>


              <form action="includes/lib.php" method="post">

                <div class="input-group mb-3">
                  <span class="input-group-addon"><i class="fa fa-user"></i></span>
                  <input type="text" name="usernameOrEmail" class="form-control" placeholder="Your Username or Email">
                </div>
                <div class="input-group mb-4">
                  <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                  <input type="password" name="password" class="form-control" placeholder="Password">
                </div>
                <div class="row">
                  <div class="col-6">
                    <button type="submit" name="btnSubmitLogin" class="btn btn-primary px-4">Login</button>
                  </div>
                  <div class="col-6 text-right">
                    <button type="button" class="btn btn-link px-0">Forgot password?</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <div class="card text-white bg-primary py-5 d-md-down-none" style="width:44%">
            <div class="card-body text-center">
              <div>
                <h2>Sign up</h2>
                <p>Dont have an account with us? Click the button below to sign up now.</p>
                <a href="register.php" class="btn btn-primary active mt-3">Register Now!</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php include 'includes/footer.php'; ?>