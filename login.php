<?php
include('query.php');
include('components/header.php');
?>

<div class="container p-5">
    <div class="col-md-8 mt-5">
        <form action="" method="post">
            <div class="form-group">
              <label for="">Email</label>
              <input type="text" name="uEmail" id="" class="form-control" placeholder="" aria-describedby="helpId" required>
     
            </div>
            
            <div class="form-group">
              <label for="">Password</label>
              <input type="text" name="uPassword" id="" class="form-control" placeholder="" aria-describedby="helpId" required>
     
            </div>
            <button type="submit" class="btn btn-info" name="login">login</button>
        </form>
    </div>
</div>
<?php
include('components/footer.php');
?>