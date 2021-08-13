<?php session_start()?>
<?php require "config.php"?>
<?php 
    $_SESSION['LANG'] = isset($_GET['lang'])?$_GET['lang']:"en";
    if($_SESSION['LANG'] == 'ar'){
        include "lang/ar.php";
    }else{
        include "lang/en.php";
    }
    
?>
<?php 
$action= isset($_GET['do'])?$_GET['do']:'index';
?>
<!--include files operation-->
<?php include "includes/header.php"?>
<?php include "includes/navbar.php"?>

<!--Start CRUD operation-->
<?php if($action == 'index'):?>
<!-- Show all users page-->
<?php 
   if(isset($_GET['admin']) && $_GET['admin'] == 'admins'){
       $user = ' !=3 ';
   }else{
         $user=' =3';
   }
   
   $stmt= $connection->prepare('SELECT * FROM users WHERE role '.$user);
   $stmt->execute();
   $users= $stmt->fetchAll();  
   ?>
<div class="container">
    <table class="table">
        <thead>
            <tr>
                <th>profile picture</th>
                <th>username</th>
                <th>email</th>
                <th>created at</th>
                <th>role</th>
                <th>action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($users as $user):?>
            <tr>
                <td>
                    <img src="public/image/<?= $user['image']?>" style="height: 10vh;">
                </td>
                <td><?= $user['username']?></td>
                <td><?= $user['email']?></td>
                <td><?= $user['created_at']?></td>

                <?php if($user['role'] == '1'):?>
                    <td>Admin</td>
                <?php elseif($user['role'] == '2'):?>   
                    <td>moderator</td>
                <?php else:?>
                    <td>user</td>
                <?php endif?>    
                
                <td>
                    <a class="btn btn-info" href="members.php?do=show&selection=<?= $user['user_id']?>">show</a>
                    <?php
                       $isAdmin = 1 ; 
                       if($_SESSION['ROLE'] == $isAdmin):
                       ?>
                    <a class="btn btn-warning" href="members.php?do=edit&selection=<?= $user['user_id']?>">edit</a>
                    <a class="btn btn-danger" href="members.php?do=delete&selection=<?= $user['user_id']?>">delete</a>
                    <?php endif?>
                </td>
            </tr>
            <?php endforeach?>
        </tbody>
    </table>
    <a class="btn btn-primary" href="members.php?do=create">add user</a>
</div>
<?php elseif($action == 'create'):?>
<!-- this is form display to end user-->
<div class="container">
    <h1>Add user</h1>
    <form method="post" action="members.php?do=store" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">username</label>
            <input type="text" class="form-control" name="username">
        </div>
        <div class="mb-3">
            <label class="form-label">Email address</label>
            <input type="email" class="form-control" name="useremail">
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" class="form-control" name="userpassword">
        </div>
        <div class="mb-3">
            <label class="form-label">Fullname</label>
            <input type="text" class="form-control" name="userfullname">
        </div>
        <div class="mb-3">
            <input class="form-check-input" type="radio" name="role" value="1">
            <label class="form-check-label">
                admin
            </label>
            <input class="form-check-input" type="radio" name="role" value="2">
            <label class="form-check-label">
                moderator
            </label>
            <input class="form-check-input" type="radio" name="role" value="3">
            <label class="form-check-label">
                user
            </label>
        </div>
        <div class="mb-3">
            <label class="form-label">image</label>
            <input type="file" class="form-control" name="avatar">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
<?php elseif($action == 'store'):?>
<!-- this form for coding operation-->
<?php 
  if($_SERVER['REQUEST_METHOD']=='POST'){
     $avatar = $_FILES['avatar'];
     echo "<pre>";
     print_r($avatar);
     echo "</pre>";
      $avatarName=$_FILES['avatar']['name'];
      $avatarTmp= $_FILES['avatar']['tmp_name'];
      $avatarType=$_FILES['avatar']['type'];
      //that array check your file type
      $avatarAllowedExtension=array('image/jpeg', 'image/png' , 'image/jpg');
      if(in_array($avatarType ,$avatarAllowedExtension)){
        $randomName=rand(0 , 100). "_".$avatarName;  
        $destination = "public\image\\". $randomName;
        move_uploaded_file($avatarTmp , $destination);
      }
      //end upload picture
     $username = $_POST['username'];
     $email = $_POST['useremail'];
     $password= sha1($_POST['userpassword']);
     $fullname =$_POST['userfullname'];
     //make backend validation 
     $erorrs = array();
     //valid username 
     if(empty($username)){
        $erorrs[] = "Sorry fill the username";
     }
     if(strlen($username) < 4){
        $erorrs[] = "username must more than 4 letter";
     }
     //valid fullname

     if(empty($fullname)){
        $erorrs[] = "Sorry fill the fullname";
     }
     //check if username& email is exsit
     $stmt= $connection->prepare('SELECT username  FROM users WHERE username=?');
     $stmt->execute(array($username));
     $check = $stmt->rowCount();
     $inDB = 1 ;
     if($check == $inDB ){
        $erorrs[] = 'user is aleardy exist'; 
     }

     if(empty($erorrs)){
        $stmt= $connection->prepare("INSERT INTO users (username , password , email , fullname , created_at , role , image) 
        VALUES (? ,  ? , ? , ? ,now() , 3 , ?)");
        $stmt->execute(array($username ,  $password , $email ,$fullname ,  $randomName));
        header('location:members.php?do=create');
     }else{
        foreach($erorrs as $error){
            echo "<div class='alert alert-danger'>" . $error. "</div>"; 
            header('Refresh:3; url:members.php?do=create');
        }
     }
  }

?>

<?php elseif($action == 'edit'):?>
<?php 
           //for security wiase 
           $userid = isset($_GET['selection']) && is_numeric($_GET['selection'])?intval($_GET['selection']):0;
           $stmt=$connection->prepare('SELECT * FROM users WHERE user_id=?');
           $stmt->execute(array($userid));
           $user = $stmt->fetch();
           $count = $stmt->rowCount(); 
    ?>
<!-- if condition that display user if in DB-->
<?php
          $inDB = 1;
          if($count == $inDB):
          ?>
<div class="container">
    <h1>Edit user</h1>
    <form method="post" action="members.php?do=update">
        <input type="hidden" value="<?= $user['user_id']?>" name="userid">
        <div class="mb-3">
            <label class="form-label">username</label>
            <input type="text" class="form-control" value="<?= $user['username']?>" name="username">
        </div>
        <div class="mb-3">
            <label class="form-label">Email address</label>
            <input type="email" class="form-control" value="<?= $user['email']?>" name="email">
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" class="form-control" value="<?= $user['password']?>" name="password">
        </div>
        <div class="mb-3">
            <label class="form-label">Fullname</label>
            <input type="text" class="form-control" value="<?= $user['fullname']?>" name="fullname">
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a class="btn btn-dark" href="members.php">back</a>
    </form>
</div>
<?php  else:?>
<?php header('location:members.php')?>
<?php endif?>

<?php elseif($action == 'update'):?>
<?php 
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $userid = $_POST['userid'] ;
        $username = $_POST['username'];
        $password =$_POST['password'];
        $email =  $_POST['email'];
        $fullname = $_POST['fullname'];

        $stmt = $connection->prepare('UPDATE users SET username=? , password=? , email=? , fullname=? WHERE  user_id=?');
        $stmt->execute(array($username , $password  , $email , $fullname , $userid ));
        header('location:members.php?do=edit');

      }     
    ?>
<?php elseif($action == 'show'):?>
<?php 
        //for security wiase 
        $userid = isset($_GET['selection']) && is_numeric($_GET['selection'])?intval($_GET['selection']):0;
        $stmt = $connection->prepare('SELECT * FROM users WHERE user_id =? ');
        $stmt->execute(array($userid));
        $user=$stmt->fetch();
        $count = $stmt->rowCount();
    ?>
<!-- if condition that display user if in DB-->
<?php if($count == 1):?>
<div class="container">
    <h1>Show user</h1>
    <form method="post" action="members.php?do=store">
        <div class="mb-3">
            <label class="form-label">username</label>
            <input type="text" class="form-control" value="<?= $user['username']?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Email address</label>
            <input type="email" class="form-control" value="<?= $user['email']?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" class="form-control" value="<?= $user['password']?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Fullname</label>
            <input type="text" class="form-control" value="<?= $user['fullname']?>">
        </div>
        <a class="btn btn-dark" href="members.php">back</a>
    </form>
</div>
<?php  else:?>
<?php header('location:members.php')?>
<?php endif?>
<?php elseif($action == 'delete'):?>
<?php 
        $userid = isset($_GET['selection']) && is_numeric($_GET['selection'])?intval($_GET['selection']):0;
        $stmt= $connection->prepare('DELETE FROM users WHERE user_id=?');
        $stmt->execute(array($userid));
        header('location:members.php');
        
     ?>

<?php else:?>
<h1>404 Page not found</h1>
<?php endif?>

<!--include file operation-->
<?php include "includes/footer.php"?>