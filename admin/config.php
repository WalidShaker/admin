<?php 
//PDO => PHP DATA OBJECT

$dsn= "mysql:host=localhost;dbname=ecommerce52";
$username="root";
$password= "";


try{
   $connection =  new  PDO($dsn , $username , $password);
//    echo "connect";
}catch(PDOException $error){
  echo $error->getMessage();
}

?>