<?php
include('query.php');
include('header.php');
?>

            <!-- Blank Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="row vh-100 bg-light rounded align-items-center justify-content-center mx-0">
                    <div class="col-md-6 text-center">
                        <h3>This is Add Product Page</h3>
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                              <label for="">Name</label>
                              <input type="text" name="pName" id="" class="form-control" placeholder="" aria-describedby="helpId">
                         
                            </div>
                            <div class="form-group">
                              <label for="">Des</label>
                              <input type="text" name="pDes" id="" class="form-control" placeholder="" aria-describedby="helpId">
                         
                            </div>
                            <div class="form-group">
                              <label for="">image</label>
                              <input type="file" name="pImage" id="" class="form-control" placeholder="" aria-describedby="helpId">
                         
                            </div>
                            <div class="form-group">
                              <label for="">Quantity</label>
                              <input type="text" name="pQty" id="" class="form-control" placeholder="" aria-describedby="helpId">
                         
                            </div>
                            <div class="form-group">
                              <label for="">Offer</label>
                              <input type="text" name="pOffer" id="" class="form-control" placeholder="" aria-describedby="helpId">
                         
                            </div>
                            <div class="form-group">
                              <label for="">Price</label>
                              <input type="text" name="pPrice" id="" class="form-control" placeholder="" aria-describedby="helpId">
                         
                            </div>
                            
                           <div class="form-group">
                             <label for="">select category</label>
                             <select class="form-control" name="cId" id="">
                               <option>select category</option>
                               <?php
                               $query=$pdo->query("select * from category");
                               $allCategories=$query->fetchAll(PDO::FETCH_ASSOC);
                               foreach ($allCategories as $cat) {
                              
                               ?>
                               <option value="<?php echo $cat['id']?>"><?php echo $cat['name']?>
                               <?php
                               }
                               ?>
                               </option>
                             </select>
                           </div>
                            <button type="submit" name="addProduct" class="btn btn-danger">Add Product</button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Blank End -->

<?php
include('footer.php');
?>