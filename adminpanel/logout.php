<?php
session_start();
unset($_SESSION['adminEmail']);
echo "<script>
location.assign('../index.php');
</script>";
?>