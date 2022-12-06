<?php

/*
    ======================================================
    == Manage Comments page
    == You Can  Edit | Delete |Approve  Comment From Here
    ======================================================
    */

session_start();

$pageTitle = "Comments";

if (isset($_SESSION["Username"])) {

  include "init.php";

  $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

  // Start Manage Page

  if ($do == 'Manage') { //Manage Page

    $stmt = $con->prepare("SELECT
                                  comments.*, items.Name AS Item_Name, users.Username As User_comment
                             FROM
                                  comments
                            INNER JOIN
                                  items
                            ON
                                  items.Item_ID = Comments.item_id
                            INNER JOIN
                                   users
                            ON
                                  users.UserID = comments.user_id");
    $stmt->execute();
    $comments = $stmt->fetchAll();

    if (!empty($comments)) {

?>
      <h1 class="text-center"> Manage Comments </h1>
      <div class="container">
        <div class="table-responsive">
          <table class="main-table text-center table table-bordered">
            <tr>
              <td>#ID</td>
              <td>Comment</td>
              <td>Item Name</td>
              <td>User Comment</td>
              <td>Added Date</td>
              <td>Control</td>
            </tr>
            <?php
            foreach ($comments as $comment) {
              echo "<tr>";
              echo "<td>" . $comment['c_id'] . "</td>";
              echo "<td>" . $comment['comment'] . "</td>";
              echo "<td>" . $comment['Item_Name'] . "</td>";
              echo "<td>" . $comment['User_comment'] . "</td>";
              echo "<td>" . $comment['comment_date'] . "</td>";
              echo "<td>
                     <a href='comments.php?do=Edit&comid=" . $comment['c_id'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                     <a href='comments.php?do=Delete&comid=" . $comment['c_id'] . "' class='btn btn-danger confirm'><i class = 'fa fa-close'></i> Delete</a>";

              if ($comment['status'] == 0) {
                echo "<a href='comments.php?do=Approve&comid=" .
                  $comment['c_id'] . "' class='btn btn-info'>
                      <i class = 'fa fa-check'></i> Approve</a>";
              }
              echo "</td>";
              echo "</tr>";
            }
            ?>
            </tr>
          </table>
        </div>
      </div>

      <?php } else {

        echo '<div class="container">';
          echo '<div class=empty-mags> There\'s No Comments To Show</div>';
        echo '</div>';
      } ?>

    <?php

  } elseif ($do == 'Edit') { // Edit Page

    $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

    $stmt = $con->prepare("SELECT * FROM comments WHERE c_id = ?");
    $stmt->execute(array($comid));
    $row = $stmt->fetch();
    $count = $stmt->rowCount();
    if ($count > 0) { ?>

      <h1 class="text-center"> Edit Comment </h1>
      <div class="container">
        <form class="row g-3" action="?do=Update" method="POST">

          <input type="hidden" name="comid" value="<?php echo $comid ?>" />

          <div class="col-md-12">
            <label class="form-label">Comment</label>
            <textarea class="form-control" name="comment" placeholder="Comment" autocomplete="off"><?php echo $row["comment"] ?></textarea>
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
  } elseif ($do == 'Update') {  // Update Page
    echo "<h1 class= 'text-center'> Update User </h1>";
    echo "<div class='container'>";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

      $comid     = $_POST['comid'];
      $comment   = $_POST['comment'];

      // Update The Database With this Info

      $stmt = $con->prepare("UPDATE comments SET comment = ? WHERE c_id = ?  ");

      $stmt->execute(array($comment, $comid));

      $theMsg = '<div class="alert alert-success" role="alert">' . $stmt->rowCount() . " " . "Record Updated</div>";

      redirectHome($theMsg, 'back');
    } else {

      $theMsg = '<div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div>';
      redirectHome($theMsg);
    }

    echo "</div>";
  } elseif ($do == 'Delete') { //Delete Comment Page

    echo "<h1 class= 'text-center'> Delete Comment </h1>";
    echo "<div class='container'>";
    $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

    $check = checkItem('c_id', 'comments', $comid);


    if ($check > 0) {
      $stmt = $con->prepare("DELETE FROM comments WHERE c_id = :a_cid");
      $stmt->bindParam(":a_cid", $comid);
      $stmt->execute();

      $theMsg =  '<div class="alert alert-success" role="alert">' . $stmt->rowCount() . " " . "Record Deleted</div>";
      redirectHome($theMsg, 'back');
    } else {
      $theMsg =  '<div class="alert alert-danger">This ID Is Not Exist</div>';
      redirectHome($theMsg);
    }
    echo '</div>';
  } elseif ($do == 'Approve') { //Approve Comment Page

    echo "<h1 class= 'text-center'> Approve comment </h1>";
    echo "<div class='container'>";

    $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

    $check = checkItem('c_id', 'comments', $comid);

    if ($check > 0) {
      $stmt = $con->prepare("UPDATE comments SET status = 1 WHERE c_id = ? ");
      $stmt->execute(array($comid));
      $theMsg =  '<div class="alert alert-success" role="alert">' . $stmt->rowCount() . " " . "Record Approve</div>";
      redirectHome($theMsg, 'back');
    } else {
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