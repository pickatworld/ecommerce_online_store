<?php
    ob_start();
    session_start();
    $pageTitle = "Login";

    if (isset($_SESSION["user"])){
        header("Location: index.php");  // Redirect To Home Page
     }

     
    include 'init.php';

    // Check If Coning Form HTTP Post Request

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (isset($_POST['login'])) {

        $users           = $_POST["user"];
        $password        = $_POST["pass"];
        $hashedPass      = sha1($password);
 
        //Check If The User Exist In SQLiteDatabase
 
        $stmt = $con->prepare("SELECT
                                   UserID, Username, Password
                              FROM
                                   users
                              WHERE
                                   Username = ?
                              AND
                                   password = ?");

        $stmt->execute(array($users, $hashedPass));
        $get = $stmt->fetch();
        
        $count = $stmt->rowCount();
 
        // IF Count > 0 This Mean The Database Contain Record About This Username
 
       if ($count > 0) {
           $_SESSION["user"] = $users;   // Register Session Name
           
           $_SESSION['uid'] = $get['UserID']; // Register Session UserID
           
           header("Location: index.php"); // Redirect To Dashboard Page
           exit();
      }
      
        } else {

            $formErrors =array();

            $username     = $_POST['username'];
            $password     = $_POST['Password'];
            $password2    = $_POST['Password2'];
            $email        = $_POST['email'];

            if(isset($username )) {

                $filterUser = filter_var($username , FILTER_SANITIZE_SPECIAL_CHARS );

                if (strlen($filterUser) < 3) {
                    $formErrors[] = 'User Name Must Be Larger Than 3 Letters';
                }
            }

            if (isset($password) && isset($password2)) {

                if (empty($password)) {
                    $formErrors[] = 'Sorry Password Cant Be Empty';
                }

                if (sha1($password) !== sha1($password2)) {
                    $formErrors[] = 'Sorry Password Is Not Match';
                }
            }

            if(isset($email)) {

                $filterEmail = filter_var($email, FILTER_VALIDATE_EMAIL );

                if (filter_var($filterEmail, FILTER_VALIDATE_EMAIL) != true) {
                    $formErrors[] = 'This Email Is Not Valid';
                }
            }

            // Check If There's Error Proceed The User Add

            if (empty($formErrors)) {

            //Cheka If User Exist In Database

        $check = checkItem('Username', 'users', $username);

        if ($check == 1) {

            $formErrors[] = 'Sorry This User Is Exists';

            } else {

            // Insert User Info In Database
            $stmt = $con->prepare("INSERT INTO
                                    users(UserName, Password, Email, RegStatus,  Date)
                                    VALUES(:auser, :apss, :amail, 0,  now())");
            $stmt->execute(array(
                'auser' => $username,
                'apss'  => sha1($password),
                'amail' => $email
            ));
            $succesMag = 'Congrats You Are Now Registered User';
          }
        }
      }
    }
     
 ?>

    <div class="container login-page">
        <h1 class="text-center">
            <span class="selected" data-class="login">Login</span> | 
            <span data-class="signup">Signup</span>
        </h1>
        <!-- Start Login Form -->
        <form class="login" action="<?php echo $_SERVER['PHP_SELF']?>"method="POST">
        <div class="input-container">
        <input class="form-control" 
                type="text" 
                name="user" 
                placeholder="User name" 
                autocomplete="off"
                required="required" />
        </div>
        
        <div class="input-container">
        <input class="form-control" 
               type="password" 
               name="pass" 
               placeholder="password" 
               autocomplete="new-Pass"
               required="required" />
        </div>

        <div class="d-grid gap-2">
        <input class="btn btn-primary btn-block" name="login" type="submit" value="Login" />
      </div>
    </form>
        <!-- End Login Form -->

        <!-- Start Signup Form -->

        <form class="signup" action="<?php echo $_SERVER['PHP_SELF']?>"method="POST">

        <div class="input-container">
            <input 
                pattern=".{4,}"
                title="User Name Must Be 4 & 8 Chars"
                class="form-control" 
                type="text" 
                name="username" 
                autocomplete="off" 
                placeholder="User Name" 
                required="required" />
        </div>

        <div class="input-container">
            <input 
                minlength="4"
                class="form-control" 
                type="password"
                name="Password" 
                autocomplete="new-password"  
                placeholder="New Password" 
                required="required" />
        </div>

        <div class="input-container">
            <input 
                minlength="4"
                class="form-control" 
                type="password"
                name="Password2" 
                autocomplete="Confirm-password2"  
                placeholder="Confirm Password" 
                required="required" />
        </div>

        <div class="input-container">
            <input
                class="form-control" 
                type="email"
                name="email"   
                placeholder="Email" 
                required="required" />
        </div>

        <div class="d-grid gap-2">
        <input class="btn btn-success btn-block" name="signup" type="submit" value="Signup" />
      </div>
        </form>
        <!-- End Signup Form -->

        <div class="the-error text-center">
            
            <?php
            if (!empty($formErrors)) {
                foreach ($formErrors as $error) {
                    echo '<div class="msg error ">' . $error .'</div>';
                }
            }

            if (isset($succesMag)) {
                echo '<div class="msg success">' . $succesMag .'</div>';
            }
            ?>
        </div>
        
    </div>
    
 <?php
    include $tpl . 'footer.php';
    ob_end_flush();
  ?>