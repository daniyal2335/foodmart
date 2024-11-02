<?php
include('query.php');
include('header.php');
?>

 <!-- Blank Start -->
 <div class="container-fluid pt-4 px-4">
                <div class="row vh-100 bg-light rounded align-items-center justify-content-center mx-0">
                    <div class="col-md-6">
                        <h3>product view</h3>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Des</th>
                                    <th>image</th>
                                    <th>price</th>
                                    <th>Offer</th>
                                    <th>qty</th>
                                    <th>c-id</th>
                                    <th>Action</th>
                                    <th>Action</th>
                                  
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                               $query=$pdo->query("select products .*,category.name as cName,c_id as catId from products inner join category on 
                               products.c_id=category.id");
                               $allproducts=$query->fetchAll(PDO::FETCH_ASSOC);
                               foreach ($allproducts as $pdt) {
                                
                               ?>
                                <tr>
                                    <td scope="row"><?php echo $pdt['name']?></td>
                                    <td><?php echo $pdt['des']?></td>
                                    <td><img height="50px" src="img/<?php echo $pdt['image']?>" alt=""></td>
                                    <td><?php echo $pdt['prize']?></td>
                                    <td><?php echo $pdt['offer']?></td>
                                    <td><?php echo $pdt['quantity']?></td>
                                    <td><?php echo $pdt['cName']?></td>
                                    <td><a href="editProduct.php?pid=<?php echo $pdt['id']?>" class="btn btn-info">Edit</a></td>
                                    <td><a href="?pdid=<?php echo $pdt['id']?>" class="btn btn-danger">Delete</a></td>
                                    
                                </tr>
                                <?php
                               }
                               ?>
                            </tbody>
                        </table>
                        </div>
                            </div>
                            </div>
                        <?php
                        include('footer.php');
                        ?>