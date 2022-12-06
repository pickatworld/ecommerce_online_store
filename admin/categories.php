<?php

    /*
    ==============================================
    == Manage Categories page
    == You Can Add | Edit | Delete Categories From Here
    ==============================================
    */

    session_start();

    $pageTitle = "Categories";

    if(isset($_SESSION["Username"])){

    include "init.php";

    $do = isset($_GET['do']) ? $_GET['do'] :'Manage';

    // Start Manage Page

    if ($do == 'Manage') {

        $sort = 'ASC';
        $sort_array = array('ASC','DESC');
        if(isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)) {
          $sort = $_GET['sort'];
        }

      $stmt2 = $con->prepare("SELECT * FROM categories ORDER BY Ordering $sort");
      $stmt2->execute();
      $cats = $stmt2->fetchAll(); ?>

      <h1 class= "text-center"> Manage Categories </h1>
        <div class ="container Categories">
            <div class="panel panel-default">
                <div class="panel-heading">
                <i class="fa fa-edit"></i> Manage Categories
                      <div class="option pull-right">
                        <i class="fa fa-sort"></i> Ordering :
                        <a class="<?php if('sort == ACS') {echo 'active';}?>" href="?sort=ASC">Asc</a> |
                        <a class="<?php if('sort == DESC') {echo 'active';}?>" href="?sort=DESC">Desc</a>
                        <i class="fa fa-eye"></i> View :
                        <span class="active" data-view="full">Full</span> |
                        <span data-view="classic">Classic</span>
                      </div>
                </div>
                <div class="panel-body">
                  <?php
                      foreach ($cats as $cat ) {
                        echo "<div class='cat'>";
                          echo "<div class='hidden-buttons'>";
                              echo "<a href='categories.php?do=Edit&catid=" . $cat['ID'] . "' class='btn btn-xs btn-primary'><i class='fa fa-edit'></i> Edit</a>";
                              echo "<a href='categories.php?do=Delete&catid=" . $cat['ID'] . "' class='confirm btn btn-xs btn-danger'><i class='fa fa-close'></i> Delete</a>";
                          echo "</div>";
                        echo '<h3>' . $cat['Name'] . '</h3>';
                         echo "<div class='full-view'>";
                            echo "<p>"; if($cat['Description'] == '') {echo 'This Is Empty';} else {echo $cat['Description'];}echo "</p>";
                            if($cat['Visibility'] == 1) { echo '<span class = "Visibility"><i class="fa fa-eye"></i>Hidden</span>';}
                            if ($cat['Allow_Comment'] == 1) {echo '<span class = "commenting"><i class="fa fa-close"></i> Comment Disabled</span>';}
                            if ($cat['Allow_Ads'] == 1) {echo '<span class = "advertises"><i class="fa fa-close"></i> Ads Disabled</span>';}
                         echo "</div>";
                        echo "</div>";
                        echo "<hr>";
                      }
                   ?>
                </div>
            </div>
            <a class="add-Category btn btn-primary" href="categories.php?do=Add"><i class="fa fa-plus"></i> Add New Category</a>
        </div>
      <?php

    } elseif ($do == 'Add') { ?>

        <h1 class= "text-center"> Add New Category </h1>
          <div class ="container">
            <form class="row g-3" action="?do=Insert" method="POST">
              <div class="col-md-8">
                <label  class="form-label">Name</label>
                <input type="text" name="name"  class="form-control" placeholder="Name Of The Category" autocomplete="off"   required="required">
              </div>
              <div class="col-md-8">
                <label  class="form-label">Description</label>
                <input type="text" name="description"  class="form-control" placeholder="Description The Category">
              </div>
              <div class="col-md-8">
                <label  class="form-label">Ordering</label>
                <input type="text" name="ordering" class="form-control"  placeholder="Number To Arrange The Category">
              </div>
              <div class="col-md-8">
                <label  class="form-label">Visible</label>
                      <div>
                        <input id="vis-yes" type="radio" name="visibility" value="0" checked />
                        <label for="vis-yes">Yes</label>
                      </div>
                      <div>
                        <input id="vis-no" type="radio" name="visibility" value="1" />
                        <label for="vis-no">No</label>
                      </div>
              </div>
              <div class="col-md-8">
                <label  class="form-label">Allow Commenting</label>
                      <div>
                        <input id="com-yes" type="radio" name="commenting" value="0" checked />
                        <label for="Com-yes">Yes</label>
                      </div>
                      <div>
                        <input id="com-no" type="radio" name="commenting" value="1" />
                        <label for="com-no">No</label>
                      </div>
              </div>
              <div class="col-md-8">
                <label  class="form-label">Allow Ads</label>
                      <div>
                        <input id="ads-yes" type="radio" name="ads" value="0" checked />
                        <label for="ads-yes">Yes</label>
                      </div>
                      <div>
                        <input id="ads-no" type="radio" name="ads" value="1" />
                        <label for="ads-no">No</label>
                      </div>
              </div>
              <div class="col-md-8">
                <button type="submit" class="btn btn-primary">Add Category</button>
              </div>
         </form>
      </div>
      <?php

    } elseif ($do == 'Insert') {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

          echo "<h1 class= 'text-center'> Insert Category </h1>";
          echo "<div class='container'>";

          $name         = $_POST['name'];
          $desc         = $_POST['description'];
          $order        = $_POST['ordering'];
          $visible      = $_POST['visibility'];
          $comment      = $_POST['commenting'];
          $ads          = $_POST['ads'];

          if (empty($formErrors)) {

            //Cheka If User Exist In Database
            $check = checkItem('Name', 'categories', $name);

            if ($check == 1) {

              $theMsg =  '<div class="alert alert-danger">Sorry This User Is Exist</div>';
              redirectHome($theMsg, 'back');

              } else {

                // Insert User Info In Database
                $stmt = $con->prepare("INSERT INTO
                  categories(Name, Description, Ordering, Visibility, Allow_Comment,  Allow_Ads)
                    VALUES(:aname, :adesc, :aorder, :avisible, :acomment, :aads)");
                $stmt->execute(array(
                    'aname'      => $name,
                    'adesc'      => $desc,
                    'aorder'     => $order,
                    'avisible'   => $visible,
                    'acomment'   => $comment,
                    'aads'       => $ads
               ));
                $theMsg =  '<div class="alert alert-success" role="alert">' . $stmt->rowCount() ." " . "Record Inserted</div>";
                redirectHome($theMsg, 'back');
            }
          }

          } else {
            echo "<div class= 'container'>";
            $theMsg = '<div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div>';
            redirectHome($theMsg, 'back');
            echo "</div>";
         }

         echo "</div>";



    } elseif ($do == 'Edit') {

      $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;
      $stmt = $con->prepare("SELECT * FROM categories WHERE ID = ?");
      $stmt->execute(array($catid));
      $cat = $stmt->fetch();
      $count = $stmt->rowCount();
      if($count > 0) { ?>
        <h1 class= "text-center"> Edit Category </h1>
          <div class ="container">
            <form class="row g-3" action="?do=Update" method="POST">
              <input type="hidden" name="catid" value="<?php echo $catid ?>" />
              <div class="col-md-8">
                <label  class="form-label">Name</label>
                <input type="text" name="name"  class="form-control" placeholder="Name Of The Category" required="required" value="<?php echo $cat['Name']; ?>" />
              </div>
              <div class="col-md-8">
                <label  class="form-label">Description</label>
                <input type="text" name="description"  class="form-control" placeholder="Description The Category" value="<?php echo $cat['Description']; ?>" />
              </div>
              <div class="col-md-8">
                <label  class="form-label">Ordering</label>
                <input type="text" name="ordering" class="form-control"  placeholder="Number To Arrange The Category" value="<?php echo $cat['Ordering']; ?>" />
              </div>
              <div class="col-md-8">
                <label  class="form-label">Visible</label>
                      <div>
                        <input id="vis-yes" type="radio" name="visibility" value="0" <?php if($cat['Visibility'] == 0) {echo 'checked'; } ?> />
                        <label for="vis-yes">Yes</label>
                      </div>
                      <div>
                        <input id="vis-no" type="radio" name="visibility" value="1" <?php if($cat['Visibility'] == 1) {echo 'checked'; } ?> />
                        <label for="vis-no">No</label>
                      </div>
              </div>
              <div class="col-md-8">
                <label  class="form-label">Allow Commenting</label>
                      <div>
                        <input id="com-yes" type="radio" name="commenting" value="0" <?php if($cat['Allow_Comment'] == 0) {echo 'checked'; } ?>  />
                        <label for="Com-yes">Yes</label>
                      </div>
                      <div>
                        <input id="com-no" type="radio" name="commenting" value="1" <?php if($cat['Allow_Comment'] == 1) {echo 'checked'; } ?> />
                        <label for="com-no">No</label>
                      </div>
              </div>
              <div class="col-md-8">
                <label  class="form-label">Allow Ads</label>
                      <div>
                        <input id="ads-yes" type="radio" name="ads" value="0" <?php if($cat['Allow_Ads'] == 0) {echo 'checked'; } ?>  />
                        <label for="ads-yes">Yes</label>
                      </div>
                      <div>
                        <input id="ads-no" type="radio" name="ads" value="1" <?php if($cat['Allow_Ads'] == 1) {echo 'checked'; } ?> />
                        <label for="ads-no">No</label>
                      </div>
              </div>
              <div class="col-md-8">
                <button type="submit" class="btn btn-primary">Save Category</button>
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


    } elseif($do == 'Update') {
        echo "<h1 class= 'text-center'> Update User </h1>";
        echo "<div class='container'>";

          if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $id        = $_POST['catid'];
            $name      = $_POST['name'];
            $desc      = $_POST['description'];
            $order     = $_POST['ordering'];
            $visible   = $_POST['visibility'];
            $comment   = $_POST['commenting'];
            $ads       = $_POST['ads'];

              // Update The Database With this Info

              $stmt = $con->prepare("UPDATE
                                            categories
                                      SET
                                            name = ?,
                                            Description = ?,
                                            Ordering = ?,
                                            Visibility = ?,
                                            Allow_Comment=?,
                                            Allow_Ads=?
                                    WHERE
                                            ID = ?");

              $stmt->execute (array($name, $desc, $order, $visible, $comment, $ads, $id));
              $theMsg = '<div class="alert alert-success" role="alert">' . $stmt->rowCount() ." " . "Record Updated</div>";
              redirectHome($theMsg, 'back');
            } else {
            $theMsg = '<div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div>';
            redirectHome($theMsg);
           }
         echo "</div>";

    } elseif ($do == 'Delete') {
      echo "<h1 class='text-center'>Delete Category</h1>";
      echo "<div class='container'>";

      $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;
      $check = checkItem('ID', 'categories', $catid);

      if($check > 0){
        $stmt = $con->prepare("DELETE FROM categories WHERE ID = :zid");
        $stmt->bindParam(":zid", $catid);
        $stmt->execute();
        $theMsg = "<div class='alert alart-Success'>" . $stmt->rowCount() . ' Record Deleted </div>';
        redirectHome($theMsg, 'back');

      } else{
        $theMsg = '<div class="alert alert-danger"> This Is Not Exist</div>';
        redirectHome($theMsg);
      }

      echo"</div>";


    }


    include $tpl . "footer.php";

  } else {

    header("Location: index.php");
    exit();
  }
?>
