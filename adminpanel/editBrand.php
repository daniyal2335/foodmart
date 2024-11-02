<?php 
include('query.php');
include('header.php');
if(isset($_GET['bid'])){
  $id=$_GET['bid'];
  $query=$pdo->prepare("select * from brands where id=:cid");
  $query->bindParam('cid',$id);
  $query->execute();
  $cate=$query->fetch(PDO::FETCH_ASSOC);
  }
?>
    <!-- Blank Start -->
    <div class="container-fluid pt-4 px-4">
                <div class="row vh-100 bg-light rounded align-items-center justify-content-center mx-0">
                    <div class="col-md-6 text-center">
                        <h3>This is Add Brands Page</h3>
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                              <label for="">Name</label>
                              <input type="text" value="<?php echo $cate['name']?>" name="bName" id="" class="form-control" placeholder="" aria-describedby="helpId">
                         
                            </div>
                            <div class="form-group">
                              <label for="">Des</label>
                              <input type="text" value="<?php echo $cate['des']?>" name="bDes" id="" class="form-control" placeholder="" aria-describedby="helpId">
                         
                            </div>
                            <div class="form-group">
                              <label for="">SubDes</label>
                              <input type="text" value="<?php echo $cate['subdes']?>" name="bsDes" id="" class="form-control" placeholder="" aria-describedby="helpId">
                         
                            </div>
                            <div class="form-group">
                              <label for="">image</label>
                              <input type="file" value="<?php echo $cate['image']?>" name="bImage" id="" class="form-control" placeholder="" aria-describedby="helpId">
                         
                         
                            </div>
                            <button type="submit" name="updateBrands" class="btn btn-danger">Update Brands</button>
                        </form>
                    </div>
                </div>
            </div>

<?php
include('footer.php');
?>