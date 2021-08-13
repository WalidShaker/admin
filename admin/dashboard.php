<?php session_start()?>
<?php 
    $_SESSION['LANG'] = isset($_GET['lang'])?$_GET['lang']:"en";
    if($_SESSION['LANG'] == 'ar'){
        include "lang/ar.php";
    }else{
        include "lang/en.php";
    }
    
?>
<?php require "config.php"?>
<?php include "includes/header.php"?>
<?php include "includes/navbar.php"?>
 <div class="container">
     <div class="row">
         <div class="col-md-6">
             <div class="members">
                 <?php 
                    $stmt= $connection->prepare('SELECT COUNT(user_id) FROM users WHERE role=3');
                    $stmt->execute();
                    $count = $stmt->fetchColumn();
                 ?>
                  <a href="members.php"> <?= $count?> Members</a>
             </div>
         </div>
     </div>
 </div>
<?php include "includes/footer.php"?>