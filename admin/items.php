<?php

/*
    ==============================================
    == Manage Categories page
    == You Can Add | Edit | Delete Users From Here
    ==============================================
    */

ob_start();   //Output Buffering Start

session_start();

$pageTitle = "Items";

if (isset($_SESSION["Username"])) {

    include "init.php";

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    // Start Manage Page

    if ($do == 'Manage') {


      $stmt = $con->prepare("SELECT items.*, categories.Name AS Categories_Name, users.Username
                             FROM items
                             INNER JOIN categories
                             ON categories.ID = items.Cat_ID
                             INNER JOIN users
                             ON users.UserID = items.Users_ID;");
      $stmt->execute();
      $items = $stmt->fetchAll();

      if(! empty($items)) {

   ?>
      <h1 class= "text-center"> Manage Items </h1>
        <div class ="container">
          <div class="table-responsive">
            <table class="main-table text-center table table-bordered">
              <tr>
                <td>#ID</td>
                <td>Name</td>
                <td>Description</td>
                <td>Price</td>
                <td>Adding Date</td>
                <td>Categories</td>
                <td>User_Name</td>
                <td>Control</td>
              </tr>
              <?php
                  foreach ($items as $item) {
                    echo "<tr>";
                     echo "<td>" . $item['Item_ID'] . "</td>";
                     echo "<td>" . $item['Name'] . "</td>";
                     echo "<td>" . $item['Description'] . "</td>";
                     echo "<td>" . $item['Price'] . "</td>";
                     echo "<td>" . $item['Add_Date']."</td>";
                     echo "<td>" . $item['Categories_Name']."</td>";
                     echo "<td>" . $item['Username']."</td>";
                     echo "<td>
                     <a href='items.php?do=Edit&itemid=" . $item['Item_ID'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                     <a href='items.php?do=Delete&itemid=" . $item['Item_ID'] . "' class='btn btn-danger confirm'><i class = 'fa fa-close'></i> Delete</a>";

                     if($item['Approve'] == 0){
                      echo "<a
                       href='items.php?do=Approve&itemid=" . $item['Item_ID'] . "'
                       class='btn btn-info'><i class = 'fa fa-check'></i> Approve</a>";
                     }

                     echo"</td>";

                    echo "</tr>";
                  }
               ?>

              </tr>
            </table>
          </div>
          <a href="items.php?do=Add" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Item</a>
        </div>

        <?php } else {
          echo '<div class="container">';
        echo '<div class=empty-mags> There\'s No Item To Show</div>';
        echo'<a href="items.php?do=Add" class="btn btn-sm btn-primary">
        <i class="fa fa-plus"></i> New Item
        </a>';
          echo '</div>';
        } ?>

      <?php

    } elseif ($do == 'Add') { ?>

        <h1 class="text-center"> Add New Items </h1>
        <div class="container">
            <form class="row g-3" action="?do=Insert" method="POST">
                <div class="col-md-8">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" placeholder="Name Of The Item" required="required">
                </div>
                <div class="col-md-8">
                    <label class="form-label">Description</label>
                    <input type="text" name="description" class="form-control" placeholder="Description The Item">
                </div>
                <div class="col-md-8">
                    <label class="form-label">Price</label>
                    <input type="text" name="price" class="form-control" placeholder="Price Of The Item">
                </div>
                <div class="col-md-8">
                    <label class="form-label">Country</label>
                    <input type="text" name="country" class="form-control" placeholder="Country Of Made">
                </div>
                <div class="col-md-8">
                    <label class="form-label">Status</label>
                    <select name="status">
                        <option value="0">...</option>
                        <option value="1">New</option>
                        <option value="2">Like New</option>
                        <option value="1">Used</option>
                        <option value="1">Very Old</option>
                    </select>
                </div>

                <div class="col-md-8">
                    <label class="form-label">User</label>
                    <select name="user">
                        <option value="0">...</option>
                        <?php
                            $stmt = $con->prepare("SELECT * FROM users");
                            $stmt->execute();
                            $users = $stmt->fetchAll();
                            foreach ($users as $user) {
                              echo "<option value='" . $user['UserID'] . "'>" . $user['Username'] . "</option>";
                            }
                         ?>
                    </select>
                </div>

                <div class="col-md-8">
                    <label class="form-label">Category</label>
                    <select name="Category">
                        <option value="0">...</option>
                        <?php
                            $stmt2 = $con->prepare("SELECT * FROM categories");
                            $stmt2->execute();
                            $cats = $stmt2->fetchAll();
                            foreach ($cats as $cat) {
                              echo "<option value='" . $cat['ID'] . "'>" . $cat['Name'] . "</option>";
                            }
                         ?>
                    </select>
                </div>

                <div class="col-md-8">
                    <button type="submit" class="btn btn-primary">Add Item</button>
                </div>
             </form>
         </div>
        <?php


    } elseif ($do == 'Insert') {

      if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        echo "<h1 class= 'text-center'> Insert Item </h1>";
        echo "<div class='container'>";

        $name      = $_POST['name'];
        $desc      = $_POST['description'];
        $price     = $_POST['price'];
        $country   = $_POST['country'];
        $status    = $_POST['status'];
        $user      = $_POST['user'];
        $cat       = $_POST['Category'];

        // Validate The Form
        $formErrors = array();

        if (empty($user)) {
          $formErrors[] = 'UserName Cant Be Empty';
        }

        if (empty($desc)) {
          $formErrors[] = 'Description Cant\'t Be Empty';
        }

        if (empty($price)) {
          $formErrors[] = 'Price Cant\'t Be Empty';
        }

        if (empty($country)) {
          $formErrors[] = 'Country Cant\'t Be Empty';
        }

        if ($status == 0) {
          $formErrors[] = 'Yous Must Choose The Status';
        }

        if ($user == 0) {
          $formErrors[] = 'Yous Must Choose The User';
        }

        if ($cat == 0) {
          $formErrors[] = 'Yous Must Choose The category';
        }

        foreach($formErrors as $error) {
          echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
        }

        if (empty($formErrors)) {

              // Insert User Info In Database
              $stmt = $con->prepare("INSERT INTO
                                items(Name, Description, Price, Country_Made, Status,  Add_Date, Users_ID, Cat_ID)

                                VALUES(:aname, :adesc, :aprice, :acountry, :astatus,  now(), :auser, :acat)");
              $stmt->execute(array(
                  'aname'     => $name,
                  'adesc'     => $desc,
                  'aprice'    => $price,
                  'acountry'  => $country,
                  'astatus'   => $status,
                  'auser'     => $user,
                  'acat'      => $cat
             ));
              $theMsg =  '<div class="alert alert-success" role="alert">' . $stmt->rowCount() ." " . "Record Inserted</div>";
              redirectHome($theMsg, 'back');

        }

        } else {
          echo "<div class= 'container'>";
          $theMsg = '<div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div>';
          redirectHome($theMsg);
          echo "</div>";
       }

       echo "</div>";


    } elseif ($do == 'Edit') {

      $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

      $stmt = $con->prepare("SELECT * FROM items WHERE Item_ID = ?");
      $stmt->execute(array($itemid));
      $item = $stmt->fetch();
      $count = $stmt->rowCount();
      if($count > 0) { ?>

          <h1 class="text-center"> Edit Items </h1>
          <div class="container">
              <form class="row g-3" action="?do=Update" method="POST">

                <input type="hidden" name="itemid" value="<?php echo $itemid ?>"  />

                  <div class="col-md-8">
                      <label class="form-label">Name</label>
                      <input type="text" name="name" class="form-control" placeholder="Name Of The Item" required="required" value="<?php echo $item['Name'] ?>" />
                  </div>
                  <div class="col-md-8">
                      <label class="form-label">Description</label>
                      <input type="text" name="description" class="form-control" placeholder="Description The Item" value="<?php echo $item['Description'] ?>" />
                  </div>
                  <div class="col-md-8">
                      <label class="form-label">Price</label>
                      <input type="text" name="price" class="form-control" placeholder="Price Of The Item" value="<?php echo $item['Price'] ?>" />
                  </div>
                  <div class="col-md-8">
                      <label class="form-label">Country</label>
                      <input type="text" name="country" class="form-control" placeholder="Country Of Made" value="<?php echo $item['Country_Made'] ?>" />
                  </div>
                  <div class="col-md-8">
                      <label class="form-label">Status</label>
                      <select name="status">
                          <option value="1" <?php if ($item['Status'] == 1) { echo 'selected';} ?>>New</option>
                          <option value="2" <?php if ($item['Status'] == 2) { echo 'selected';} ?>>Like New</option>
                          <option value="3" <?php if ($item['Status'] == 3) { echo 'selected';} ?>>Used</option>
                          <option value="4" <?php if ($item['Status'] == 4) { echo 'selected';} ?>>Very Old</option>
                      </select>
                  </div>

                  <div class="col-md-8">
                      <label class="form-label">User</label>
                      <select name="user">
                          <?php
                              $stmt = $con->prepare("SELECT * FROM users");
                              $stmt->execute();
                              $users = $stmt->fetchAll();
                              foreach ($users as $user) {
                                echo "<option value='" . $user['UserID'] . "'";
                                if ($item['Users_ID'] == $user['UserID']) { echo 'selected';}
                                echo ">" . $user['Username'] . "</option>";
                              }
                           ?>
                      </select>
                  </div>

                  <div class="col-md-8">
                      <label class="form-label">Category</label>
                      <select name="Category">
                          <?php
                              $stmt2 = $con->prepare("SELECT * FROM categories");
                              $stmt2->execute();
                              $cats = $stmt2->fetchAll();
                              foreach ($cats as $cat) {
                                echo "<option value='" . $cat['ID'] . "'";
                                if ($item['Cat_ID'] == $cat['ID']) { echo 'selected';}
                                echo ">" . $cat['Name'] . "</option>";
                              }
                           ?>
                      </select>
                  </div>

                  <div class="col-md-8">
                      <button type="submit" class="btn btn-primary">Save Item</button>
                  </div>
               </form>
                  
                    <?php
                      $stmt = $con->prepare("SELECT
                                          comments.*, users.Username As User_comment
                                    FROM
                                          comments
                                    INNER JOIN
                                          users
                                    ON
                                          users.UserID = comments.user_id
                                    WHERE item_id = ?");
              $stmt->execute(array($itemid));
              $rows = $stmt->fetchAll();

              if (! empty($rows)) {

          ?>
              <h1 class= "text-center"> Manage <?php echo $item['Name'] ?> Comments </h1>

                  <div class="table-responsive">
                    <table class="main-table text-center table table-bordered">
                      <tr>
                        <td>Comment</td>
                        <td>User Comment</td>
                        <td>Added Date</td>
                        <td>Control</td>
                      </tr>
                      <?php
                          foreach ($rows as $row) {
                            echo "<tr>";
                            echo "<td>" . $row['comment'] . "</td>";
                            echo "<td>" . $row['User_comment'] . "</td>";
                            echo "<td>" . $row['comment_date']."</td>";
                            echo "<td>
                            <a href='comments.php?do=Edit&comid=" . $row['c_id'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                            <a href='comments.php?do=Delete&comid=" . $row['c_id'] . "' class='btn btn-danger confirm'><i class = 'fa fa-close'></i> Delete</a>";

                            if($row['status'] == 0){
                            echo "<a href='comments.php?do=Approve&comid=" .
                              $row['c_id'] . "' class='btn btn-info'>
                              <i class = 'fa fa-check'></i> Approve</a>";
                            }
                            echo"</td>";
                            echo "</tr>";
                          }
                      ?>
                      </tr>
                    </table>
                  </div>
                  <?php } ?>
           </div>

   <?php

    } else {
      echo "<div class='container'>";
      $theMsg =  '<div class = "alert alert-danger">Theres No Such ID</div>';
      redirectHome($theMsg);
      echo "</div>";
   }


    } elseif ($do == 'Update') {

      echo "<h1 class= 'text-center'>Update Items</h1>";
      echo "<div class='container'>";

      if ($_SERVER['REQUEST_METHOD'] == 'POST'){

        $id       =  $_POST['itemid'];
        $name     =  $_POST['name'];
        $desc     =  $_POST['description'];
        $price    =  $_POST['price'];
        $country  =  $_POST['country'];
        $status   =  $_POST['status'];
        $cat      =  $_POST['Category'];
        $user     =  $_POST['user'];

        $formErrors = array();

        if (empty($name)) {
          $formErrors[] = 'Name can\'t Be Empty';
        }
        if (empty($desc)) {
          $formErrors[] = 'Description can\'t Be Empty';
       }
       if (empty($price)) {
         $formErrors[] = 'Price can\'t Be Empty';
       }
       if (empty($country)) {
         $formErrors[] = 'Country can\'t Be Empty';
       }
       if ($status == 0) {
         $formErrors[] = 'Yous Must Choose The Status';
       }
       if ($user == 0) {
         $formErrors[] = 'Yous Must Choose The Member';
      }
      if ($cat == 0) {
        $formErrors[] = 'Yous Must Choose The Category';
     }
     foreach($formErrors as $error) {
       echo '<div class="alert alert-dander">' . $error . '</div>';
     }

     if (empty($formErrors)) {
       $stmt = $con->prepare("UPDATE
                                   items
                              set
                                  Name = ?,
                                  Description = ?,
                                  Price = ?,
                                  Country_Made = ?,
                                  Status = ?,
                                  Cat_ID = ?,
                                  Users_ID = ?
                              WHERE
                                   Item_ID = ?");
       $stmt->execute(array($name, $desc, $price, $country, $status, $cat, $user, $id));

       $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . 'Record Update</div>';
       redirectHome($theMsg,'back');
     }

   } else {
     $theMsg = '<div class="alert alert-denger">Sorry You can\'t Browse This Page Directly </div>';
     redirectHome($theMsg);
   }
      echo"</div>";


    } elseif ($do == 'Delete') {

      echo "<h1 class= 'text-center'> Delete Item </h1>";
      echo "<div class='container'>";
          $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

          $check = checkItem('Item_ID', 'items', $itemid);


          if($check > 0) {
            $stmt = $con->prepare("DELETE FROM items WHERE Item_ID = :aid");
            $stmt->bindParam(":aid", $itemid);
            $stmt->execute();

            $theMsg =  '<div class="alert alert-success" role="alert">' . $stmt->rowCount() ." " . "Record Deleted</div>";
            redirectHome($theMsg, 'back');

          }else {
            $theMsg =  '<div class="alert alert-danger">This ID Is Not Exist</div>';
            redirectHome($theMsg);
          }
     echo '</div>';

    } elseif ($do == 'Approve') {

      echo "<h1 class= 'text-center'> Approve Item </h1>";
      echo "<div class='container'>";
          $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
          $check = checkItem('Item_ID', 'items', $itemid);


          if($check > 0) {
            $stmt = $con->prepare("UPDATE items SET Approve = 1 WHERE Item_ID = ?");

            $stmt->execute(array($itemid));

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

ob_end_flush(); // Release The Output
