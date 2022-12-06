<?php

     ob_start();
     session_start();

     $pageTitle = 'Profile';

     include "init.php";

     if (isset($_SESSION['user'])) {

      $getUser = $con->prepare("SELECT * FROM users WHERE Username =?");

      $getUser->execute(array($sessionUser));

      $info = $getUser->fetch();

     ?>
     <h1 class="text-center"><?php echo 'Welcome' . ' ' . $sessionUser; ?></h1>
        <div class="container">
          <div class= "information block">   
           <div class="card">
            <div div class="card-header text-white bg-primary">My Information</div>
             <div class="card-body">
              <ul class="list-unstyled">
                <li>
                   <i class="fa fa-unlock-alt fa-fw"></i>
                  <span>Login Name</span> : <?php echo $info['Username'] ?>
                </li>
                <li>
                  <i class="fa fa-envelope-o fa-fw"></i>
                  <span>Email</span> : <?php echo $info['Email'] ?>
                </li>
                <li>
                  <i class="fa fa-user-o fa-fw"></i>
                  <span>Full Name</span> : <?php echo $info['FullName'] ?>
                 </li>
                <li>
                  <i class="fa fa-calendar fa-fw"></i>
                  <span>Register Date</span> : <?php echo $info['Date'] ?>
                 </li>
                <li>
                  <i class="fa fa-tags fa-fw"></i>
                  <span>Favourite Items</span> :
                </li>  
               </ul>
               <a href="#" class="btn btn-primary">Edit Info</a>
           </div>
          </div>
        </div>
      </div>

      <div class="container">
          <div id="my-items" class= "my-Ads block">   
           <div class="card">
            <div div class="card-header text-white bg-primary">My Items</div>
             <div class="card-body">
                    <?php
                        if(! empty(getItems('Users_ID', $info['UserID']))) {
                          echo '<div class= "row">';
                        foreach(getItems('Users_ID', $info['UserID'], 1) as $item) {
                            echo '<div class="col-sm-6 col-md-3">';
                                echo '<div class="thumbnail item-box">';
                                  if($item['Approve'] == 0) {
                                    echo '<span class="approve-status">Waiting Approved</span>';
                                   }
                                echo '<span class="price-tag">$' . $item['Price'] . '</span>';
                                echo '<img img class="img-responsive" src="layout\images\appel-13.jpg" alt="" />';
                                echo '<div class="caption">';
                                echo '<h3><a href="itemsinfo.php?itemid='. $item['Item_ID'] .'">' . $item['Name'] . '</a></h3>';
                                echo '<p>' . $item['Description'] .'</p>';
                                echo '<div class="date">' . $item['Add_Date'] .'</div>';
                                echo'</div>';
                                echo'</div>';
                            echo'</div>';
                         }
                          echo '</div>';
                      }else {
                        echo 'Sorry There\'s No Ads To Show, create <a href="newad.php">New Ad</a>';
                      }
                    ?>
                </div>
           </div>
          </div>
        </div>
      </div>

      <div class="container">
          <div class= "my-comment block">   
           <div class="card">
            <div div class="card-header text-white bg-primary">Latest Comments</div>
             <div class="card-body">
               <?php
                 $stmt = $con->prepare("SELECT comment FROM comments WHERE user_id = ?");
                 $stmt->execute(array($info['UserID']));
                 $comments = $stmt->fetchALL();
                 if (! empty($comments)) {
                  foreach ($comments as $comment) {
                    echo '<p>' . $comment['comment'] . '</p>';
                  }

                 } else {
                  echo 'There\'s No Comments To Show';
                 }
               ?>
           </div>
          </div>
        </div>
      </div>
     <?php


     } else {
      header('Location: login.php');
      exit();
     }
     include $tpl . "footer.php";
     ob_end_flush();
?>
