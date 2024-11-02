<?php
include('query.php');
include('header.php');
if (isset($_GET['abid'])){
    $id=$_GET['abid'];
    $query=$pdo->prepare("select * from `arrived brands` where id=:cid");
  $query->bindParam('cid',$id);
  $query->execute();
  $arrive=$query->fetch(PDO::FETCH_ASSOC);
}

?>

            <!-- Blank Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="row vh-100 bg-light rounded align-items-center justify-content-center mx-0">
                    <div class="col-md-6 text-center">
                        <h3>This is Edit arrive  Brand Page</h3>
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                              <label for="">Name</label>
                              <input type="text" name="abName" value="<?php echo $arrive['name']?>" id="" class="form-control" placeholder="" aria-describedby="helpId">
                         
                            </div>
                            <div class="form-group">
                              <label for="">Des</label>
                              <input type="text" name="abDes" value="<?php echo $arrive['des']?>" id="" class="form-control" placeholder="" aria-describedby="helpId">
                         
                            </div>
                           
                            <div class="form-group">
                              <label for="">image</label>
                              <input type="file" name="abImage" value="<?php echo $arrive['image']?>" id="" class="form-control" placeholder="" aria-describedby="helpId">
                         
                            </div>
                            <button type="submit" name="editArriveBrands" class="btn btn-danger">Update Arrive Brands</button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Blank End -->

<?php
include('footer.php');
?>