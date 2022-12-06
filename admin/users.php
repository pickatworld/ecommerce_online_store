<?php

    /*
    ==============================================
    == Manage Users page
    == You Can Add | Edit | Delete Users From Here
    ==============================================
    */

    session_start();

    $pageTitle = "Users";

    if(isset($_SESSION["Username"])){

    include "init.php";

    $do = isset($_GET['do']) ? $_GET['do'] :'Manage';

    // Start Manage Page

    if ($do == 'Manage') { //Manage Page

      $query = "";
      if(isset($_GET['page']) && $_GET['page'] == 'pending'){
        $query = 'AND RegStatus = 0';
      }

      $stmt = $con->prepare("SELECT * FROM users WHERE GroupID !=1 $query");
      $stmt->execute();
      $rows = $stmt->fetchAll();

       if(! empty($rows)) {
   ?>

      <h1 class= "text-center"> Manage User </h1>
        <div class ="container">
          <div class="table-responsive">
            <table class="main-table text-center table table-bordered">
              <tr>
                <td>#ID</td>
                <td>UserName</td>
                <td>Email</td>
                <td>Full Name</td>
                <td>Registers Date</td>
                <td>Control</td>
              </tr>
              <?php
                  foreach ($rows as $row) {
                    echo "<tr>";
                     echo "<td>" . $row['UserID'] . "</td>";
                     echo "<td>" . $row['Username'] . "</td>";
                     echo "<td>" . $row['Email'] . "</td>";
                     echo "<td>" . $row['FullName'] . "</td>";
                     echo "<td>" . $row['Date']."</td>";
                     echo "<td>
                     <a href='users.php?do=Edit&UserID=" . $row['UserID'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                     <a href='users.php?do=Delete&UserID=" . $row['UserID'] . "' class='btn btn-danger confirm'><i class = 'fa fa-close'></i> Delete</a>";

                    if($row['RegStatus'] == 0){
                     echo "<a href='users.php?do=Activate&UserID=" . $row['UserID'] . "' class='btn btn-info'><i class = 'fa fa-check'></i> Activate</a>";

                    }

                     echo"</td>";

                    echo "</tr>";
                  }
               ?>

              </tr>
            </table>
          </div>
          <a href="users.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> New User</a>
        </div>
              <?php } else {

                echo '<div class="container">';
                 echo '<div class=empty-mags> There\'s No User To Show</div>';
                 echo '<a href="users.php?do=Add" class="btn btn-primary">
                 <i class="fa fa-plus"></i> New User</a>';
                echo '</div>';
              } ?>

  <?php } elseif ($do == 'Add') { //Add Users Page ?>

        <h1 class= "text-center"> Add New User </h1>
          <div class ="container">
        <form class="row g-3" action="?do=Insert" method="POST">
          <div class="col-md-8">
            <label  class="form-label">UserName</label>
            <input type="text" name="username"  class="form-control" placeholder="UserName" autocomplete="off"   required="required">
          </div>
          <div class="col-md-8">
            <label  class="form-label">Password</label>
            <input type="password" name="password"  class="password form-control" autocomplete="new-password" placeholder="New Password">
            <i class="show-pass fa fa-eye-slash" aria-hidden='true'></i>
          </div>
          <div class="col-md-8">
            <label  class="form-label">Email</label>
            <input type="email" name="email" class="form-control"  placeholder="Exampl@gmail.com" required="required">
          </div>
          <div class="col-md-8">
            <label  class="form-label">FullName</label>
            <input type="text" name="FullName" class="form-control" placeholder="Full Name" required="required">
          </div>
          <div class="col-md-8">
            <button type="submit" class="btn btn-primary">Add User</button>
          </div>
       </form>
      </div>
  <?php

} elseif ($do == 'Insert') { //Insert Page

      if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        echo "<h1 class= 'text-center'> Insert User </h1>";
        echo "<div class='container'>";

        $user   = $_POST['username'];
        $pass   = $_POST['password'];
        $email  = $_POST['email'];
        $name   = $_POST['FullName'];

        $hashPass = sha1($_POST['password']);

        // Validate The Form
        $formErrors = array();

        if (empty($user)) {
          $formErrors[] = 'UserName Can\'t Be Empty';
        }

        if (strlen($user) < 3) {
          $formErrors[] = 'UserName Can\'t Be Less Than 4 characters';
        }

        if (strlen($user) > 20) {
          $formErrors[] = 'UserName Can\'t Be More Then 20 characters';
        }

        if (empty($pass)) {
          $formErrors[] = 'password Can\'t Be Empty';
        }

        if (empty($email)) {
          $formErrors[] = 'Email Can\'t Be Empty';
        }

        if (empty($name)) {
          $formErrors[] = 'Full Name Can\'t Be Empty';
        }

        foreach($formErrors as $error) {
          echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
        }

        if (empty($formErrors)) {

          //Cheka If User Exist In Database
          $check = checkItem('Username', 'users', $user);

          if ($check == 1) {

            $theMsg =  '<div class="alert alert-danger">Sorry This User Is Exist</div>';
            redirectHome($theMsg, 'back');

            } else {

              // Insert User Info In Database
              $stmt = $con->prepare("INSERT INTO
                                      users(UserName, Password, Email, FullName, RegStatus,  Date)
                                      VALUES(:auser, :apss, :amail, :aname, 1,  now())");
              $stmt->execute(array(
                  'auser' => $user,
                  'apss'  => $hashPass,
                  'amail' => $email,
                  'aname' => $name,
             ));
              $theMsg =  '<div class="alert alert-success" role="alert">' . $stmt->rowCount() ." " . "Record Inserted</div>";
              redirectHome($theMsg, 'back');
          }
        }

        } else {
          echo "<div class= 'container'>";
          $theMsg = '<div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div>';
          redirectHome($theMsg);
          echo "</div>";
       }

       echo "</div>";

    } elseif ($do == 'Edit') { // Edit Page

      $userid = isset($_GET['UserID']) && is_numeric($_GET['UserID']) ? intval($_GET['UserID']) : 0;

      $stmt = $con->prepare("SELECT * FROM users WHERE UserID = ?  LIMIT 1");
      $stmt->execute(array($userid));
      $row = $stmt->fetch();
      $count = $stmt->rowCount();
      if($count > 0) { ?>

         <h1 class= "text-center"> Edit User </h1>
        <div class ="container">
        <form class="row g-3" action="?do=Update" method="POST">

             <input type="hidden" name="userid" value="<?php echo $userid ?>"  />

            <div class="col-md-12">
              <label  class="form-label">UserName</label>
              <input type="text" name="username"  class="form-control" value="<?php echo $row["Username"]?>" placeholder="UserName" autocomplete="off"   required="required">
            </div>
            <div class="col-md-12">
              <label  class="form-label">Password</label>
              <input type="hidden"  name="oldpassword" value="<?php echo $row["Password"]?>"  placeholder="Password">
              <input type="password" name="newpassword"  class="form-control" autocomplete="new-password" placeholder="Password">
            </div>
            <div class="col-md-12">
              <label  class="form-label">Email</label>
              <input type="email" name="email" class="form-control" value="<?php echo $row["Email"]?>"  placeholder="Exampl@gmail.com" required="required">
            </div>
            <div class="col-md-12">
              <label  class="form-label">FullName</label>
              <input type="text" name="FullName" class="form-control"  value="<?php echo $row["FullName"]?>"  placeholder="Full Name" required="required">
            </div>
            <div class="d-grid gap-2">
              <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
        </div>
   <?php

    } else {
      echo "<div class='container'>";
      $theMsg =  '<div class = "alert alert-danger">Theres No Such ID</div>';
      redirectHome($theMsg);
      echo "</div>";
   }

  } elseif($do == 'Update') {  // Update Page
    echo "<h1 class= 'text-center'> Update User </h1>";
    echo "<div class='container'>";

      if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $id     = $_POST['userid'];
        $user   = $_POST['username'];
        $email  = $_POST['email'];
        $name   = $_POST['FullName'];

        // Password Trick

        $pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword'])  ;

        // Validate The Form
        $formErrors = array();

        $formErrors = array();

        if (empty($user)) {
          $formErrors[] = 'UserName Cant Be Empty';
        }

        if (empty($user) < 3) {
          $formErrors[] = 'UserName Cant Be Less Than 4 characters';
        }

        if (empty($user) > 20) {
          $formErrors[] = 'UserName Cant Be More Then 20 characters';
        }

        $formErrors = array();
        if (empty($email)) {
          $formErrors[] = 'Email Cant Be Empty';
        }

        $formErrors = array();
        if (empty($name)) {
          $formErrors[] = 'Full Name Cant Be Empty';
        }

        foreach($formErrors as $error) {
          echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
        }


        if (empty($formErrors)) {


          $stmt2 = $con->prepare("SELECT * FROM users WHERE Username = ? AND UserID != ?");
          $stmt2->execute(array($user, $id));
          $count = $stmt2->rowCount();
          if($count == 1) {

          $theMsg = '<div class="alert alert-danger" role="alert">' .  " " . "Sorry This User Is Exist</div>";
          redirectHome($theMsg, 'back', 5); 
          
          } else {

          // Update The Database With this Info

          $stmt = $con->prepare("UPDATE users SET UserName = ?, Email = ?, FullName = ?, Password = ? WHERE UserID = ?  ");

          $stmt->execute (array($user, $email, $name, $pass, $id));

          $theMsg = '<div class="alert alert-success" role="alert">' . $stmt->rowCount() ." " . "Record Updated</div>";

          redirectHome($theMsg, 'back', 5);

        }
      }

        } else {

        $theMsg = '<div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div>';
        redirectHome($theMsg);
       }

     echo "</div>";

   } elseif ($do == 'Delete') { //Delete User Page

     echo "<h1 class= 'text-center'> Delete User </h1>";
     echo "<div class='container'>";
         $userid = isset($_GET['UserID']) && is_numeric($_GET['UserID']) ? intval($_GET['UserID']) : 0;
         $check = checkItem('UserID', 'users', $userid);


         if($check > 0) {
           $stmt = $con->prepare("DELETE FROM users WHERE UserID = :auser");
           $stmt->bindParam(":auser", $userid);
           $stmt->execute();

           $theMsg =  '<div class="alert alert-success" role="alert">' . $stmt->rowCount() ." " . "Record Deleted</div>";
           redirectHome($theMsg, 'back');

         }else {
           $theMsg =  '<div class="alert alert-danger">This ID Is Not Exist</div>';
           redirectHome($theMsg);
         }
    echo '</div>';
   } elseif ($do == 'Activate'){ //Activate User Page

    echo "<h1 class= 'text-center'> Activate User </h1>";
    echo "<div class='container'>";
        $userid = isset($_GET['UserID']) && is_numeric($_GET['UserID']) ? intval($_GET['UserID']) : 0;
        $check = checkItem('UserID', 'users', $userid);


        if($check > 0) {
          $stmt = $con->prepare("UPDATE users SET RegStatus = 1 WHERE UserID = ?");

          $stmt->execute(array($userid));

          $theMsg =  '<div class="alert alert-success" role="alert">' . $stmt->rowCount() ." " . "Record Activate</div>";
          redirectHome($theMsg, 'back');

        }else {
          $theMsg =  '<div class="alert alert-danger">This ID Is Not Exist</div>';
          redirectHome($theMsg);
        }
   echo '</div>';
   }

    include $tpl . "footer.php";

  } else {

    header("Location: index.php");
    exit();
  }


?>
