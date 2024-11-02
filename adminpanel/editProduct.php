<?php
include('query.php');
include('header.php');
if(isset($_GET['pid'])){
  $id=$_GET['pid'];
  $query=$pdo->prepare("select products .*,category.name as cName, category.id as catId from
  products inner join category on products.c_id=category.id where products.id=:pid");
  $query->bindParam('pid',$id);
  $query->execute();
  $pdct=$query->fetch(PDO::FETCH_ASSOC);
}
?>
 <!-- Blank Start -->
 <div class="container-fluid pt-4 px-4">
                <div class="row vh-100 bg-light rounded align-items-center justify-content-center mx-0">
                    <div class="col-md-6 text-center">
                        <h3>Edit product</h3>
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                              <label for="">Name</label>
                              <input type="text" value="<?php echo $pdct['name']?>" name="pName" id="" class="form-control" placeholder="" aria-describedby="helpId">
                         
                            </div>
                            <div class="form-group">
                              <label for="">Des</label>
                              <input type="text" value="<?php echo $pdct['des']?>" name="pDes" id="" class="form-control" placeholder="" aria-describedby="helpId">
                         
                            </div>
                            <div class="form-group">
                              <label for="">price</label>
                              <input type="text" value="<?php echo $pdct['prize']?>" name="pPrice" id="" class="form-control" placeholder="" aria-describedby="helpId">
                         
                            </div>
                            <div class="form-group">
                              <label for="">Offer</label>
                              <input type="text" value="<?php echo $pdct['offer']?>" name="pOffer" id="" class="form-control" placeholder="" aria-describedby="helpId">
                         
                          </div> 
                            <div class="form-group">
                              <label for="">Quantity</label>
                              <input type="text" value="<?php echo $pdct['quantity']?>" name="pQty" id="" class="form-control" placeholder="" aria-describedby="helpId">
                         
                          </div> 
                              <div class="form-group">
                              <label for="">image</label>
                              <input type="file" value="<?php echo $pdct['image']?>"  name="pImage" id="" class="form-control" placeholder="" aria-describedby="helpId">
                              
                            </div>
                           <div class="form-group">
                             <label for="">select category</label>
                             <select class="form-control" name="cId" id="">
                               <option value="<?php echo $pdct['catId']?>"><?php echo $pdct['cName']?></option>
                               <?php 
                               $query=$pdo->prepare("select * from category where name!=:catName");
                               $query->bindParam('catName',$pdct['cName']);
                               $query->execute();
                               $allCategories=$query->fetchAll(PDO::FETCH_ASSOC);
                               foreach ($allCategories as $cat) {
                                ?>
                               <option value="<?php echo $cat['id']?>"><?php echo $cat['name']?></option>
                               <?php
                               }
                               ?>
                          
                             </select>
                            
                           </div>
                            <button type="submit" name="updateProduct" class="btn btn-danger">update Product</button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Blank End -->



<?php
include('footer.php');
?>