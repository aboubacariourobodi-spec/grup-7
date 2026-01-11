<?php
   
    
    $host = "localhost";
    $user = "root";
    $password = "";
    $database = "oğrenci_db";

    try {
        //code...
        $conn = mysqli_connect($host,$user,$password,$database);
    } catch (mysqli_sql_exception) {
        //throw $th;
        echo "You are not connected";
    }

    if(isset($_POST[submit])){
        $stmt = $conn->prepare("insert into oğrenci_detaylar(e_posta,sifre.sifreOnayli) 
        values (?,?,?)");
        $stmt ->bind_param("SSS",$e_posta,$sifre,$sifreOnayli);
        $stmt ->execute();
        echo"Registration Succesfully";
        $stmt ->close();
        $con->close();

    }

    

   /* if($conn){
        
    }

    */

?>