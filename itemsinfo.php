<?php

     ob_start();
     session_start();

     $pageTitle = 'Items';

     include "init.php";

     $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

     $stmt = $con->prepare("SELECT items.*, categories.Name AS Categories_Name, users.Username
                             FROM items
                             INNER JOIN categories
                             ON categories.ID = items.Cat_ID
                             INNER JOIN users
                             ON users.UserID = items.Users_ID
                             WHERE
                               Item_ID = ?
                              AND
                                   Approve = 1;");
     $stmt->execute(array($itemid));
     $count = $stmt->rowCount();
     if ($count > 0) {
     $item = $stmt->fetch();
     
     ?>
     <h1 class="text-center"><?php echo $item['Name'] ?></h1>
     <div class="container">
          <div class="row">
            <div class="col-md-3">
            <img class="img-responsive img-thumbnail center-block" src="layout\images\appel-13.jpg" alt="" />
            </div>

            <div class="col-md-9 item-info">
               <h2><?php echo $item['Name'] ?></h2>
               <p><?php echo $item['Description'] ?></p>
               <ul class="list-unstyled">
                    <li>
                         <i class="fa fa-calendar fa-fw"></i>
                         <span>Add_Date</span> :   <?php echo $item['Add_Date'] ?>
                    </li>
                    <li>
                    <i class="fa fa-money fa-fw"></i>
                         <span>Price </span>   : $ <?php echo $item['Price'] ?>
                    </li>
                    <li>
                    <i class="fa fa-industry fa-fw"></i>
                         <span>Made In</span>  :   <?php echo $item['Country_Made'] ?>
                    </li>
                    <li>
                         <i class="fa fa-tags fa-fw"></i>
                         <span>category</span> :  <a href="categories.php?pageid=<?php echo $item['Cat_ID'] ?>"><?php echo $item['Categories_Name'] ?></a>
                    </li>
                    <li>
                         <i class="fa fa-user fa-fw"></i>
                         <span>Added By</span> :  <a href="#"><?php echo $item['Username'] ?></a>
                    </li>
               </ul>
            </div>
        </div>
        <hr class= "custom-hr">

        <?php if(isset($_SESSION['user'])) { ?>
        <!-- Start Add Comment -->
         <div class="row">
               <div class="col-md-offset-3">
                    <div class="Add-comment">
                    <h3>Add Your Comment</h3>

                     <form action="<?php echo $_SERVER['PHP_SELF'] .'?itemid=' . $item['Item_ID'] ?>" method="POST">
                       <textarea name="comment" required></textarea>
                       <input class="btn btn-primary" type="submit" value="Add Comment">
                     </form>

                     <?php
                     if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                       $comment = filter_var($_POST['comment'], FILTER_SANITIZE_SPECIAL_CHARS);
                       $itemid  = $item['Item_ID'];
                       $userid  = $_SESSION ['uid'];

                       if (! empty($comment)) {

                         $stmt = $con->prepare("INSERT INTO 
                                   comments(comment, status, comment_date, item_id, user_id)
                                   VALUES(:acomment, 0, NOW(), :aitemid, :auserid)");

                                   $stmt->execute(array(

                                        'acomment' => $comment,
                                        'aitemid'  => $itemid,
                                        'auserid'  => $userid
                                   ));

                                   if ($stmt) {
                                        echo '<div class="alert alert-success">Comment Added</div>';
                                   }

                              }

                         }
                      ?>

                   </div>
                 </div>
               </div>

          <!-- End Add Comment -->
          <?php } else {
               echo '<strong><a href="login.php">Login</a></strong> Or <strong><a href="login.php">Register</a></strong> To Add Comment';
          } ?>

        <hr class= "custom-hr">
          
            <?php 
               $stmt = $con->prepare("SELECT
                                             comments.*, users.Username As User_comment
                                        FROM
                                             comments
                                        INNER JOIN
                                             users
                                        ON
                                             users.UserID = comments.user_id
                                        WHERE
                                             item_id = ?
                                        AND
                                             status = 1
                                        ORDER BY
                                             c_id DESC");

               $stmt->execute(array($item['Item_ID']));

               $comments = $stmt->fetchAll();
               
            ?>

           <?php
               foreach ($comments as $comment) { ?>
                 <div class="comment-box">
                    <div class="row">
                         
                    <div class="col-sm-2 text-center">
                    <img class="img-responsive img-thumbnail circular_image" src="layout\images\appel-13.jpg" alt="" />
                    <?php echo $comment['User_comment'] ?>
                    </div>
                    
                    <div class="col-sm-10">
                    <p class="lead"> <?php echo $comment['comment'] ?></p>
                  </div>
                     
                 </div>
                </div>
               <hr class= "custom-hr">
          <?php } ?>
          
     </div>
    
     

     <?php

     } else {
          echo "<div class='container'>";
          $theMsg =  '<div class = "alert alert-danger">Theres No Such ID Or This Is Waiting Approve</div>';
          redirectHome($theMsg,'back');
          echo "</div>";
     }

     include $tpl . "footer.php";
     ob_end_flush();
?>