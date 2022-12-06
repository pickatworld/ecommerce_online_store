<?php
session_start();

if (isset($_SESSION["Username"])) {

  $pageTitle = "Dashboard";

  include "init.php";

  /* Start Dashboard*/

  $numUsers = 5; // Number Of Latest Users
  $latestUser = getLatest("*", "users", "UserID", $numUsers); //Latest Users Array

  $numItems = 5; // Number Of Latest items
  $latesItems = getLatest("*", 'items', 'Item_ID', $numItems);

  $numComments = 5;
  

?>
  <div class="container home-stats text-center">
    <h1 class="text-center"> Dashboard </h1>
    <div class="row">
      <div class="col-md-3">
        <div class="stat st-users">
          <i class="fa fa-users"></i>
          <div class="info">
            Total Users
            <span><a href="users.php?do=Manage">
                <?php echo countItems('UserID', 'users') ?>
              </a>
            </span>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="stat st-pending">
          <i class="fa fa-user-plus"></i>
          <div class="info">
            Pending Users
            <span><a href="users.php?do=Manage&page=pending">
                <?php echo checkItem('RegStatus', 'users', 0) ?>
              </a>
            </span>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="stat st-item ">
          <i class="fa fa-tag"></i>
          <div class="info">
            Total Items
            <span><a href="items.php?do=Manage">
                <?php echo countItems('Item_ID', 'items') ?>
              </a>
            </span>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="stat st-comments">
          <i class="fa fa-comments"></i>
          <div class="info">
            Total Comments
            <span><a href="comments.php?do=Manage">
                <?php echo countItems('c_id', 'comments') ?>
              </a>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="container latest">
    <div class="row">
      <div class="col-sm-6">
        <div class="panel panel-default">
          <div class="panel-heading">
            <i class="fa fa-users">
            </i> Latest <?php echo $numUsers ?> Registered Users
            <span class="toggle-info pull-right">
              <i class="fa fa-plus fa-lg"></i>
            </span>
          </div>
          <div class="panel-body">
            <ul class="list-unstyled latest-users">
              <?php
                  if(! empty($latestUser)) {
              foreach ($latestUser as $user) {
                echo  '<li>';
                echo $user['Username'];
                echo '<a href="users.php?do=Edit&UserID=' . $user['UserID'] . '">';
                echo '<span class ="btn btn-success pull-right">';
                echo '<i class ="fa fa-edit"> </i> Edit';
                if ($user['RegStatus'] == 0) {
                  echo "<a href='users.php?do=Activate&UserID="
                    . $user['UserID']
                    . "' class='btn btn-info pull-right activate'>
                    <i class = 'fa fa-check'></i> Activate</a>";
                }
                echo '</span>';
                echo '</a>';
                echo '</li>';
                  }
              } else {
                echo'There\'s No Record To Show';
              }
              ?>
            </ul>
          </div>
        </div>
      </div>

      <div class="col-sm-6">
        <div class="panel panel-default">
          <div class="panel-heading">
            <i class="fa fa-tag"></i>
            </i> Latest <?php echo $numItems ?> Items
            <span class="toggle-info pull-right">
              <i class="fa fa-plus fa-lg"></i>
          </div>
          <div class="panel-body">

            <ul class="list-unstyled latest-users">
              <?php
                if (!empty($latesItems)) {
              foreach ($latesItems as $item) {
                echo  '<li>';
                echo $item['Name'];
                echo '<a href="items.php?do=Edit&itemid=' . $item['Item_ID'] . '">';
                echo '<span class ="btn btn-success pull-right">';
                echo '<i class ="fa fa-edit"> </i> Edit';
                if ($item['Approve'] == 0) {
                  echo "<a href='items.php?do=Approve&itemid="
                    . $item['Item_ID']
                    . "' class='btn btn-info pull-right activate'>
                    <i class = 'fa fa-check'></i> Approve</a>";
                }
                echo '</span>';
                echo '</a>';
                echo '</li>';
              }
            } else {
               echo 'There\'s No Item To Show';
            }
              ?>
            </ul>

          </div>
        </div>
      </div>
    </div>
  </div>




  <div class="container latest">
    <div class="row">
      <div class="col-sm-6">
        <div class="panel panel-default">
          <div class="panel-heading">
            <i class="fa fa-comments-o">
            </i> Latest <?php echo $numComments ?> Comments
            <span class="toggle-info pull-right">
              <i class="fa fa-plus fa-lg"></i>
            </span>
          </div>
          <div class="panel-body">
            <ul class="list-unstyled latest-users">
              <?php

              $stmt = $con->prepare("SELECT
                                              comments.*, users.Username As User_comment
                                            FROM
                                                  comments
                                            INNER JOIN
                                                  users
                                            ON
                                                  users.UserID = comments.user_id
                                            ORDER BY 
                                                  c_id DESC
                                            LIMIT $numComments");
              $stmt->execute();
              $comments = $stmt->fetchAll();

              if(! empty($comments)) {
              foreach ($comments as $comment) {
                echo '<div class="comment-box">';
                echo '<span class="user-n">' . $comment['User_comment'] . '</span>';
                echo '<p class= "user-c">' . $comment['comment'] . '</p>';
                echo '</div>';
              }
              } else{
                echo '<div class="container">';
                echo '<div class=empty-mags> There\'s No Comments To Show</div>';
                echo '</div>';
              }
              ?>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>










<?php


  /* End Dashboard*/


  include $tpl . "footer.php";
} else {

  header("Location: index.php");
  exit();
}
