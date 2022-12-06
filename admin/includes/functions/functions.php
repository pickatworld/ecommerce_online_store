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
  ** Count Number Of Items Function v1.0
  ** Function To Count Number Of Items Rows
  ** $item = The Item To Count
  ** $table = The Table To Choose From
  */

  function countItems($item , $table){
    global $con;
    $stmt2 = $con->prepare("SELECT Count($item) FROM $table");
    $stmt2->execute();
    return $stmt2->fetchColumn();
  }

  /*
  ** GEt Latest Records Function v1.0
  ** Function To Get Latest Items Forme Database [ Users, Items, Comments ]
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
