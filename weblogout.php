<?php
include('query.php');
unset($_SESSION['userId']);
session_destroy();

    echo "<script>location.assign('index.php')</script>";

?>