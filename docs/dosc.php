<?php

    /*
    ** Title function That Echo page Title
    ** Has The Variable $pageTitle Adn Echo Default Title
    */
      function getTitle() {
        global $pageTitle;
        if(isset($pageTitle)){
          echo "$pageTitle";
        } else {
          echo "Default";
        }

      }

    /*
    ** Home Redirect Function v1.0
    **[This Function Accept Parameters]
    ** $errorMsg = Echo The Error Massage
    ** $seconds = Seconds Before Redirecting
    */
      function redirectHome($errorMsg, $seconds = 3){
        echo "<div class='alert alert-danger'>$errorMsg</div>";
        echo"<div class = 'alert alert-info'>You Will Be Redirect To Homepage After $seconds Seconds.</div>";
        header("refresh:$seconds;url=index.php");

      }
  /*
  ** Check Items Function v1.0
  ** Function To Check Item In Database [ Function Accept Parameters]
  ** $select = The Item To Select [Example: user, item, category]
  ** $from = The Table To Select From [Example: Users, item, categories]
  **$value = The Value Of Select [Example: Amr, Box, Electronics]
  */
    function checkItem($select, $from, $value) {
      global $con;
      $statement = $con->prepare("SELECT $select FROM $from WHERE $select = ?");
      $statement ->execute(array($value));
      $count = $statement->rowCount();
      return $count;
    }

    /*
    ** Home Redirect Function v2.0
    **This Function Accept Parameters
    ** $theMsg = Echo The Massage [ Error | Success | Waring]
    ** $url = The Link You Want To Redirect To
    ** $seconds = Seconds Before Redirecting
    */
    function redirectHome($theMsg, $url = null, $seconds = 3){
      if ($url === null) {

        $url = 'index.php';
        $link = 'Home Page';

      } else {
        if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== ''){

          $url =$_SERVER['HTTP_REFERER'];
          $link = 'Previous Page';

        } else {
          $url = 'index.php';
          $link = 'Home Page';
        }

      }
      echo $theMsg;
      echo"<div class = 'alert alert-info'>You Will Be Redirect To $link After $seconds Seconds.</div>";
      header("refresh:$seconds;url=$url");

    }

    /*
  ** GEt Latest Records Function v1.0
  ** Function To Get Latest Items From Database [ Users, Items, Comments ]
  **$select = Field To Select
  ** $table = The Table To Choose From
  ** $limit = Number Of Records To Get
  */

  function getLatest($select, $table, $order, $limit = 5){

    global $con;
    $getStmt = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");
    $getStmt->execute();
    $rows = $getStmt->fetchALL();
    return $rows;
  }



 ?>
