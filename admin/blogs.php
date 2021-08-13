<?php 
$action= isset($_GET['do'])?$_GET['do']:'index';
?>
<!--include files operation-->
<?php require "config.php"?>
<?php session_start()?>
<?php include "includes/header.php"?>
<?php include "includes/navbar.php"?>

<!--Start CRUD operation-->
<?php if($action == 'index'):?>
<!-- Show all blogs page-->
<?php
   $stmt= $connection->prepare('SELECT * FROM blogs');
   $stmt->execute();
   $blogs= $stmt->fetchAll();  
   ?>
<div class="container">
    <table class="table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Author</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($blogs as $blog):?>
            <tr>
                <td><?= $blog['title']?></td>
                <td><?= $blog['descrip']?></td>
                <td><?= $blog['author']?></td>
                <td><?= $blog['created_at']?></td>
                <td> 
                    <a class="btn btn-info" href="blogs.php?do=show&selection=<?= $blog['blog_id']?>">show</a>
                    <?php
                       $isAdmin = 1 ; 
                       if($_SESSION['ROLE'] == $isAdmin):
                       ?>
                        <a class="btn btn-warning" href="blogs.php?do=edit&selection=<?= $blog['blog_id']?>">edit</a>
                        <a class="btn btn-danger" href="blogs.php?do=delete&selection=<?= $blog['blog_id']?>">delete</a>
                    <?php endif?>    
                </td>
            </tr>
            <?php endforeach?>
        </tbody>
    </table>
    <a class="btn btn-primary" href="blogs.php?do=create">Add Blog</a>
</div>
<?php elseif($action == 'create'):?>
<!-- this is form display to end user-->
<div class="container">
    <h1>Add Blog</h1>
    <form method="post" action="blogs.php?do=store">
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" class="form-control" name="title">
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <input type="text" class="form-control" name="description">
        </div>
        <div class="mb-3">
            <label class="form-label">author</label>
            <input type="number" class="form-control" name="author">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
<?php elseif($action == 'store'):?>
<!-- this form for coding operation-->
<?php 
  if($_SERVER['REQUEST_METHOD']=='POST'){
     $title = $_POST['title'];
     $descrip = $_POST['description'];
     $authorname =$_POST['author'];
     $stmt= $connection->prepare("INSERT INTO blogs (title , descrip , author , created_at) 
                                  VALUES (? ,  ? , ? , now())
                                ");
     $stmt->execute(array($title ,  $descrip , $authorname ));
     header('location:blogs.php?do=create');
  }

?>

<?php elseif($action == 'edit'):?>
    <?php 
           //for security wiase 
           $blogid = isset($_GET['selection']) && is_numeric($_GET['selection'])?intval($_GET['selection']):0;
           $stmt=$connection->prepare('SELECT * FROM blogs WHERE blog_id=?');
           $stmt->execute(array($blogid));
           $blog = $stmt->fetch();
           $count = $stmt->rowCount(); 
    ?>
        <!-- if condition that display author if in DB-->
        <?php
          $inDB = 1;
          if($count == $inDB):
          ?>
        <div class="container">
            <h1>Edit Blog</h1>
            <form method="post" action="blogs.php?do=update">
                <input type="hidden" class="form-control" value="<?= $blog['blog_id']?>" name="blog_id">
                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" class="form-control" value="<?= $blog['title']?>" name="title">
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <input type="text" class="form-control" value="<?= $blog['descrip']?>" name="descrip">
                </div>
                <div class="mb-3">
                    <label class="form-label">Author</label>
                    <input type="number" class="form-control" value="<?= $blog['author']?>" name="author">
                </div>
                <button type="submit"  class="btn btn-primary">Update</button> 
                <a class="btn btn-dark" href="blogs.php">back</a>
            </form>
        </div>
        <?php  else:?>
        <?php header('location:blogs.php')?>
        <?php endif?>    

<?php elseif($action == 'update'):?>
    <?php 
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $blog_id = $_POST['blog_id'] ;
        $title = $_POST['title'];
        $descrip = $_POST['descrip'];
        $author =  $_POST['author'];

        $stmt = $connection->prepare('UPDATE blogs SET title=? , descrip=? , author=? WHERE  blog_id=?');
        $stmt->execute(array($title , $descrip , $author , $blog_id ));
        header('location:blogs.php?do=edit');
      }     
    ?>
<?php elseif($action == 'show'):?>
    <?php 
        //for security wiase 
        $blog_id = isset($_GET['selection']) && is_numeric($_GET['selection'])?intval($_GET['selection']):0;
        $stmt = $connection->prepare('SELECT * FROM blogs WHERE blog_id =? ');
        $stmt->execute(array($blog_id));
        $blog=$stmt->fetch();
        $count = $stmt->rowCount();
    ?>
    <!-- if condition that display author if in DB-->
    <?php if($count == 1):?>
    <div class="container">
        <h1>Show Blog</h1>
        <form method="post" action="blogs.php?do=store">
            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" class="form-control" value="<?= $blog['title']?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <input type="text" class="form-control" value="<?= $blog['descrip']?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Author</label>
                <input type="text" class="form-control" value="<?= $blog['author']?>">
            </div>
            <a class="btn btn-dark" href="blogs.php">back</a>
        </form>
    </div>
    <?php  else:?>
    <?php header('location:blogs.php')?>
    <?php endif?>
<?php elseif($action == 'delete'):?>
    <?php 
        $blog_id = isset($_GET['selection']) && is_numeric($_GET['selection'])?intval($_GET['selection']):0;
        $stmt= $connection->prepare('DELETE FROM blogs WHERE blog_id=?');
        $stmt->execute(array($blog_id));
        header('location:blogs.php');
        
     ?>
    
<?php else:?>
<h1>404 Page not found</h1>
<?php endif?>

<!--include file operation-->
<?php include "includes/footer.php"?>