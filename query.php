<?php
session_start();
include('adminpanel/dbcon.php');
?>
<?php
if(isset($_POST['login'])){
    $userEmail=$_POST['uEmail'];
    $userPassword=$_POST['uPassword'];
    $query=$pdo->prepare("select * from users where email= :userEmail AND password= :userPassword");
    $query->bindParam('userEmail',$userEmail);
    $query->bindParam('userPassword',$userPassword);
    $query->execute();
    $user=$query->fetch(PDO::FETCH_ASSOC);
   // print_r($user);
    if($user['role_id'] == 1){
        $_SESSION['adminName']=$user['name'];
        $_SESSION['adminEmail']=$user['email'];
        echo "<script>
        location.assign('adminpanel/index.php')
        </script>";
    }
elseif ($user['role_id'] == 2) {
    $_SESSION['userId']=$user['id'];
    $_SESSION['userName']=$user['name'];
    $_SESSION['userEmail']=$user['email'];
    echo "<script>location.assign('index.php')
    </script>";
}
}
?>