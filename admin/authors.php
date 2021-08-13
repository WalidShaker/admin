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
<!-- Show all Authors page-->
<?php
   $stmt= $connection->prepare('SELECT * FROM authors');
   $stmt->execute();
   $authors= $stmt->fetchAll();  
   ?>
<div class="container">
    <table class="table">
        <thead>
            <tr>
                <th>Author name</th>
                <th>Blogs type</th>
                <th>Nationality</th>
                <th>Added At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($authors as $author):?>
            <tr>
                <td><?= $author['author_name']?></td>
                <td><?= $author['blogs_type']?></td>
                <td><?= $author['nationality']?></td>
                <td><?= $author['added_at']?></td>
                <td> 
                    <a class="btn btn-info" href="authors.php?do=show&selection=<?= $author['author_id']?>">show Blogs</a>
                    <?php
                       $isAdmin = 1 ; 
                       if($_SESSION['ROLE'] == $isAdmin):
                       ?>
                        <a class="btn btn-warning" href="authors.php?do=edit&selection=<?= $author['author_id']?>">edit</a>
                        <a class="btn btn-danger" href="authors.php?do=delete&selection=<?= $author['author_id']?>">delete</a>
                    <?php endif?>    
                </td>
            </tr>
            <?php endforeach?>
        </tbody>
    </table>
    <a class="btn btn-primary" href="authors.php?do=create">add author</a>
</div>
<?php elseif($action == 'create'):?>
<!-- this is form display to end user-->
<div class="container">
    <h1>Add author</h1>
    <form method="post" action="authors.php?do=store">
        <div class="mb-3">
            <label class="form-label">author name</label>
            <input type="text" class="form-control" name="author_name">
        </div>
        <div class="mb-3">
            <label class="form-label">Blogs type</label>
            <input type="text" class="form-control" name="blogs_type">
        </div>
        <div class="mb-3">
            <label class="form-label">Nationality</label>
            <input type="text" class="form-control" name="nationality">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
<?php elseif($action == 'store'):?>
<!-- this form for coding operation-->
<?php 
  if($_SERVER['REQUEST_METHOD']=='POST'){
     $authorname = $_POST['author_name'];
     $blogstype = $_POST['blogs_type'];
     $nationality =$_POST['nationality'];
     $stmt= $connection->prepare("INSERT INTO authors (author_name , blogs_type , nationality , added_at) 
                                  VALUES (? ,  ? , ? , now())
                                ");
     $stmt->execute(array($authorname ,  $blogstype , $nationality ));
     header('location:authors.php?do=create');
  }

?>

<?php elseif($action == 'edit'):?>
    <?php 
           //for security wiase 
           $authorid = isset($_GET['selection']) && is_numeric($_GET['selection'])?intval($_GET['selection']):0;
           $stmt=$connection->prepare('SELECT * FROM authors WHERE author_id=?');
           $stmt->execute(array($authorid));
           $author = $stmt->fetch();
           $count = $stmt->rowCount(); 
    ?>
        <!-- if condition that display author if in DB-->
        <?php
          $inDB = 1;
          if($count == $inDB):
          ?>
        <div class="container">
            <h1>Edit Author</h1>
            <form method="post" action="authors.php?do=update">
                <input type="hidden" class="form-control" value="<?= $author['author_id']?>" name="author_id">
                <div class="mb-3">
                    <label class="form-label">Author name</label>
                    <input type="text" class="form-control" value="<?= $author['author_name']?>" name="author_name">
                </div>
                <div class="mb-3">
                    <label class="form-label">Blog type</label>
                    <input type="text" class="form-control" value="<?= $author['blogs_type']?>" name="blogs_type">
                </div>
                <div class="mb-3">
                    <label class="form-label">nationality</label>
                    <input type="text" class="form-control" value="<?= $author['nationality']?>" name="nationality">
                </div>
                <button type="submit"  class="btn btn-primary">Update</button> 
                <a class="btn btn-dark" href="authors.php">back</a>
            </form>
        </div>
        <?php  else:?>
        <?php header('location:authors.php')?>
        <?php endif?>    

<?php elseif($action == 'update'):?>
    <?php 
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $author_id = $_POST['author_id'] ;
        $author_name = $_POST['author_name'];
        $blogs_type = $_POST['blogs_type'];
        $nationality =  $_POST['nationality'];

        $stmt = $connection->prepare('UPDATE authors SET author_name=? , blogs_type=? , nationality=? WHERE  author_id=?');
        $stmt->execute(array($author_name , $blogs_type , $nationality , $author_id ));
        header('location:authors.php?do=edit');
      }     
    ?>
<?php elseif($action == 'show'):?>
    <?php 
        //for security wiase 
        $author_id = isset($_GET['selection']) && is_numeric($_GET['selection'])?intval($_GET['selection']):0;
        $stmt = $connection->prepare('SELECT * FROM authors WHERE author_id =? ');
        $stmt->execute(array($author_id));
        $author=$stmt->fetch();
        $count = $stmt->rowCount();
    ?>
    <!-- if condition that display author if in DB-->
    <?php if($count == 1):?>
    <div class="container">
        <h1>Author</h1>
        <table class="table">
    <thead>
        <tr>
            <th>Author name</th>
            <th>Blogs type</th>
            <th>Nationality</th>
            <th>Added At</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><?= $author['author_name']?></td>
            <td><?= $author['blogs_type']?></td>
            <td><?= $author['nationality']?></td>
            <td><?= $author['added_at']?></td>
        </tr>
    </tbody>
</table>
   <h1>Author Blogs</h1>
   <?php
   $same_author= $author['author_id'];
   $stmt= $connection->prepare('SELECT * FROM blogs WHERE author ='.$same_author);
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
   <a class="btn btn-dark" href="authors.php">back</a>
    </div>

    <?php  else:?>
    <?php header('location:authors.php')?>
    <?php endif?>
<?php elseif($action == 'delete'):?>
    <?php 
        $author_id = isset($_GET['selection']) && is_numeric($_GET['selection'])?intval($_GET['selection']):0;
        $stmt= $connection->prepare('DELETE FROM authors WHERE author_id=?');
        $stmt->execute(array($author_id));
        header('location:authors.php');
        
     ?>
    
<?php else:?>
<h1>404 Page not found</h1>
<?php endif?>

<!--include file operation-->
<?php include "includes/footer.php"?>