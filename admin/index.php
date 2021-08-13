<?php session_start();?>
<?php 
    $_SESSION['LANG'] = isset($_GET['lang'])?$_GET['lang']:"en";
    if($_SESSION['LANG'] == 'ar'){
        include "lang/ar.php";
    }else{
        include "lang/en.php";
    }
    
?>
<?php require "config.php"?>
<?php 
// echo  $_SERVER['REQUEST_METHOD'];
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $username = $_POST['adminusername'];
    $password =sha1($_POST['adminpassword']);
    $stmt = $connection->prepare("SELECT * FROM users WHERE username=? AND password=? AND role !=3");
    $stmt->execute(array($username , $password));
    $row= $stmt->fetch();
    // echo "<pre>";
    //  print_r($row);
    // echo "</pre>";
    $count =$stmt->rowCount();
    if($count == 1){
         $_SESSION['USERNAME'] = $username ;
         $_SESSION['EMAIL'] = $row['email'];
         $_SESSION['FULLNAME'] = $row['fullname'];
         $_SESSION['ROLE'] = $row['role'];
         header('location:dashboard.php');
    }else{
        echo "sorry";
    }
    
}
?>
<?php include "includes/header.php"?>
<!-- Start admin -->
<div class="container">
    <div class="lang-choice">
        <a href="?lang=en">English</a>
        <a href="?lang=ar">اللغه العربيه</a>
    </div>
    <form method="post" action="<?php $_SERVER['PHP_SELF']?>" class="mt-5">
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label"><?= $lang['username']?></label>
            <input type="text" class="form-control" name="adminusername">
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label"><?= $lang['password']?></label>
            <input type="password" class="form-control" name="adminpassword">
        </div>
        <button type="submit" class="btn btn-primary"><?= $lang['submit']?></button>
    </form>
</div>

<!-- End admin -->
<?php include "includes/footer.php"?>