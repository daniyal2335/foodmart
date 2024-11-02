<?php
include('query.php');
include('header.php');
if (isset($_GET['cid'])){
    $id=$_GET['cid'];
    $query=$pdo->prepare("select * from category where id=:cid");
  $query->bindParam('cid',$id);
  $query->execute();
  $cat=$query->fetch(PDO::FETCH_ASSOC);
}
?>

            <!-- Blank Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="row vh-100 bg-light rounded align-items-center justify-content-center mx-0">
                    <div class="col-md-6 text-center">
                        <h3>This is Edit Category Page</h3>
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                              <label for="">Name</label>
                              <input type="text" name="cName" value="<?php echo $cat['name']?>" id="" class="form-control" placeholder="" aria-describedby="helpId">
                         
                            </div>
                            <!-- <div class="form-group">
                              <label for="">Des</label>
                              <input type="text" name="cDes" id="" class="form-control" placeholder="" aria-describedby="helpId">
                         
                            </div> -->
                            <div class="form-group">
                              <label for="">image</label>
                              <input type="file" name="cImage" value="<?php echo $cat['image']?>" id="" class="form-control" placeholder="" aria-describedby="helpId">
                         
                            </div>
                            <button type="submit" name="updateCategory" class="btn btn-danger">Add Category</button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Blank End -->

<?php
include('footer.php');
?>