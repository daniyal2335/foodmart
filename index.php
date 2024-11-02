<?php 
include('adminpanel/query.php');
include('query.php');
include('components/header.php');
// Check if the session contains userId
if(isset($_SESSION['userId'])) {
  $userId = $_SESSION['userId'];
} else {
  // Set a default value or handle the error appropriately
  $userId = null; 
}


// Add to cart logic
if (isset($_POST['addCart'])) {
    // Initialize the session cart array if it doesn't exist
    if (isset($_SESSION['finalCart'])) {
        $productId = array_column($_SESSION['finalCart'], 'p_id');
        
        // Check if the product is already in the cart
        if (in_array($_POST['p_id'], $productId)) {
            echo "<script>alert('Product is already added to the cart');</script>";
        } else {
            // Add new product to the cart
            $count = count($_SESSION['finalCart']);
            $_SESSION['finalCart'][$count] = array(
                "p_id" => htmlspecialchars($_POST['p_id']),
                "p_name" => htmlspecialchars($_POST['p_name']),
                "p_price" => htmlspecialchars($_POST['p_price']),
                "p_des" => htmlspecialchars($_POST['p_des']),
                "p_qty" => isset($_POST['p_qty']) ? (int)$_POST['p_qty'] : 1, // Default quantity to 1 if not provided
                "p_rating" => htmlspecialchars($_POST['p_rating']),
                "p_image" => htmlspecialchars($_POST['p_image'])
            );
            echo "<script>alert('Product added to cart successfully');location.assign('index.php');</script>";
           // Make sure the script stops executing after the redirect
        }

    } else {
        // First product to be added to cart
        $_SESSION['finalCart'][0] = array(
            "p_id" => htmlspecialchars($_POST['p_id']),
            "p_name" => htmlspecialchars($_POST['p_name']),
            "p_price" => htmlspecialchars($_POST['p_price']),
            "p_des" => htmlspecialchars($_POST['p_des']),
            "p_qty" => isset($_POST['p_qty']) ? (int)$_POST['p_qty'] : 1, // Default quantity to 1 if not provided
            "p_rating" => htmlspecialchars($_POST['p_rating']),
            "p_image" => htmlspecialchars($_POST['p_image'])
        );
        echo "<script>alert('Product added to cart successfully');</script>";
        header('Location: index.php'); // Redirect after first product is added
        exit();
    }
}

//remove item
if(isset($_GET['remove'])){
    $id=$_GET['remove'];
    foreach($_SESSION['finalCart'] as $key=> $value){
    if($id == $value['p_id']){
    unset($_SESSION['finalCart'][$key]);
    //reset array
    $_SESSION['finalCart']=array_values($_SESSION['finalCart']);
    echo "<script>alert('cart remove successfully')location.assign('header.php');
   </script>";
   

   }
}
}

//sum or minus
if (!isset($_SESSION['finalCart'])) {
  $_SESSION['finalCart'] = []; // Initialize cart if not set
}

// Initialize total items and price
$totalItems = count($_SESSION['finalCart']);
$totalPrice = 0;

// Calculate total price from the cart
foreach ($_SESSION['finalCart'] as $item) {
  $totalPrice += $item['p_price'] * $item['p_qty'];
}

// Handle POST request for quantity updates
if (isset($_POST['item_id'])) {
  // Get the item ID from the form submission
  $itemId = $_POST['item_id'];

  // Loop through the cart items in the session to find the correct product
  foreach ($_SESSION['finalCart'] as $index => $item) {
      if ($item['p_id'] == $itemId) {
          // If the "increase" button was clicked
          if (isset($_POST['increase'])) {
              $_SESSION['finalCart'][$index]['p_qty'] += 1; // Increase quantity by 1
          }

          // If the "decrease" button was clicked and quantity is greater than 1
          if (isset($_POST['decrease']) && $_SESSION['finalCart'][$index]['p_qty'] > 1) {
              $_SESSION['finalCart'][$index]['p_qty'] -= 1; // Decrease quantity by 1
          }

          // Recalculate total price after quantity change
          $totalPrice = 0; // Reset total price
          foreach ($_SESSION['finalCart'] as $cartItem) {
              $totalPrice += $cartItem['p_price'] * $cartItem['p_qty']; // Calculate total price
          }

          // Refresh the page to reflect the changes in quantity
          // header("Location: " . $_SERVER['PHP_SELF']);
          // exit;
      }
  }
}

// Checkout
if (isset($_GET['checkout'])) {
  $uId = $_SESSION['userId'];
  $uName = $_SESSION['userName'];
  $uEmail = $_SESSION['userEmail'];
  
  $totalQty = 0;
  $totalPrice = 0;

  foreach ($_SESSION['finalCart'] as $value) {
      $pId = $value['p_id'];
      $pName = $value['p_name'];
      $pPrice = $value['p_price'];
      $pRating = $value['p_rating'];
      $pQty = $value['p_qty'];

      // Insert order
      $query = $pdo->prepare("INSERT INTO orders (u_id, u_name, u_email, p_id, p_name, p_price, p_qty, p_rating) VALUES (:u_id, :u_name, :u_email, :p_id, :p_name, :p_price, :p_qty, :pRating)");
      $query->bindParam('u_id', $uId);
      $query->bindParam('u_name', $uName);
      $query->bindParam('u_email', $uEmail);
      $query->bindParam('p_id', $pId);
      $query->bindParam('p_name', $pName);
      $query->bindParam('p_price', $pPrice);
      $query->bindParam('p_qty', $pQty);
      $query->bindParam('pRating', $pRating);
      $query->execute();

      // Update product quantity
      $updateQuery = $pdo->prepare("UPDATE products SET quantity = quantity - :p_qty WHERE id = :p_id AND quantity >= :p_qty");
      $updateQuery->bindParam(':p_qty', $pQty);
      $updateQuery->bindParam(':p_id', $pId);
      $updateQuery->execute();

      // Calculate total for invoice
      $totalQty += $pQty;
      $totalPrice += $pPrice * $pQty;
  }

  // Insert into invoice table
  $invoice_query = $pdo->prepare("INSERT INTO invoice (u_id, u_name, u_email, total_Qty, total_amount) VALUES (:u_id, :u_name, :u_email, :total_products, :total_amount)");
  $invoice_query->bindParam('u_id', $uId);
  $invoice_query->bindParam('u_name', $uName);
  $invoice_query->bindParam('u_email', $uEmail);
  $invoice_query->bindParam('total_products', $totalQty);
  $invoice_query->bindParam('total_amount', $totalPrice);
  $invoice_query->execute();

  // Clear the cart
  unset($_SESSION['finalCart']);

  echo "<script>alert('Order placed successfully'); location.assign('index.php');</script>";
}

   ?>
     
     <section id="brand" class="py-3" style="background-image: url('images/background-pattern.jpg');background-repeat: no-repeat;background-size: cover;">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">

            <div class="banner-blocks">
            
              <div class="banner-ad large bg-info block-1">

                <div class="swiper main-swiper">
                  <div class="swiper-wrapper">
                    
                    <div class="swiper-slide">
                      <div class="row banner-content p-5">
                        <div class="content-wrapper col-md-7">
                          <div class="categories my-3">100% natural</div>
                          <h3 class="display-4">Fresh Smoothie & Summer Juice</h3>
                          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Dignissim massa diam elementum.</p>
                          <a href="#" class="btn btn-outline-dark btn-lg text-uppercase fs-6 rounded-1 px-4 py-3 mt-3">Shop Now</a>
                        </div>
                        <div class="img-wrapper col-md-5">
                          <img src="images/product-thumb-1.png" class="img-fluid">
                        </div>
                      </div>
                    </div>
                    
                    <div class="swiper-slide">
                      <div class="row banner-content p-5">
                        <div class="content-wrapper col-md-7">
                          <div class="categories mb-3 pb-3">100% natural</div>
                          <h3 class="banner-title">Fresh Smoothie & Summer Juice</h3>
                          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Dignissim massa diam elementum.</p>
                          <a href="#" class="btn btn-outline-dark btn-lg text-uppercase fs-6 rounded-1">Shop Collection</a>
                        </div>
                        <div class="img-wrapper col-md-5">
                          <img src="images/product-thumb-1.png" class="img-fluid">
                        </div>
                      </div>
                    </div>
                    
                    <div class="swiper-slide">
                      <div class="row banner-content p-5">
                        <div class="content-wrapper col-md-7">
                          <div class="categories mb-3 pb-3">100% natural</div>
                          <h3 class="banner-title">Heinz Tomato Ketchup</h3>
                          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Dignissim massa diam elementum.</p>
                          <a href="#" class="btn btn-outline-dark btn-lg text-uppercase fs-6 rounded-1">Shop Collection</a>
                        </div>
                        <div class="img-wrapper col-md-5">
                          <img src="images/product-thumb-2.png" class="img-fluid">
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <div class="swiper-pagination"></div>

                </div>
              </div>
              
              <div class="banner-ad bg-success-subtle block-2" style="background:url('images/ad-image-1.png') no-repeat;background-position: right bottom">
                <div class="row banner-content p-5">

                  <div class="content-wrapper col-md-7">
                    <div class="categories sale mb-3 pb-3">20% off</div>
                    <h3 class="banner-title">Fruits & Vegetables</h3>
                    <a href="#" class="d-flex align-items-center nav-link">Shop Collection <svg width="24" height="24"><use xlink:href="#arrow-right"></use></svg></a>
                  </div>

                </div>
              </div>

              <div class="banner-ad bg-danger block-3" style="background:url('images/ad-image-2.png') no-repeat;background-position: right bottom">
                <div class="row banner-content p-5">

                  <div class="content-wrapper col-md-7">
                    <div class="categories sale mb-3 pb-3">15% off</div>
                    <h3 class="item-title">Baked Products</h3>
                    <a href="#" class="d-flex align-items-center nav-link">Shop Collection <svg width="24" height="24"><use xlink:href="#arrow-right"></use></svg></a>
                  </div>

                </div>
              </div>

            </div>
            <!-- / Banner Blocks -->
              
          </div>
        </div>
      </div>
    </section>

 <section id="Category" class="py-5 overflow-hidden">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="section-header d-flex flex-wrap justify-content-between mb-5">
          <h2 class="section-title">Category</h2>
          <div class="d-flex align-items-center">
            <a href="#" class="btn-link text-decoration-none">View All Categories →</a>
            <div class="swiper-buttons">
              <button class="swiper-prev category-carousel-prev btn btn-yellow">❮</button>
              <button class="swiper-next category-carousel-next btn btn-yellow">❯</button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="category-carousel swiper">
          <div class="swiper-wrapper">
            <?php 
              $query = $pdo->query("SELECT * FROM category");
              $allCategories = $query->fetchAll(PDO::FETCH_ASSOC);
              
              if (!empty($allCategories)) {
                foreach ($allCategories as $cat) {
            ?>
              <a href="index.php" class="nav-link category-item swiper-slide">
                <img src="adminpanel/img/<?php echo $cat['image']; ?>" alt="<?php echo $cat['name']; ?>" class="img-fluid">
                <h3 class="category-title"><?php echo $cat['name']; ?></h3>
              </a>
            <?php 
                }
              } else { 
            ?>
              <p>No categories available at the moment.</p>
            <?php 
              } 
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


<section id="arrivebrands" class="py-5 overflow-hidden">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="section-header d-flex flex-wrap flex-wrap justify-content-between mb-5">
                    <h2 class="section-title">Newly Arrived Brands</h2>
                    <div class="d-flex align-items-center">
                        <a href="#" class="btn-link text-decoration-none">View All Categories →</a>
                        <div class="swiper-buttons">
                            <button class="swiper-prev brand-carousel-prev btn btn-yellow">❮</button>
                            <button class="swiper-next brand-carousel-next btn btn-yellow">❯</button>
                        </div>  
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="brand-carousel swiper">
                    <div class="swiper-wrapper">
                        <?php 
                        $query = $pdo->query("SELECT image, name, des FROM `arrived brands`");
                        $allArrivedBrands = $query->fetchAll(PDO::FETCH_ASSOC);

                        if (!empty($allArrivedBrands)) {
                            foreach ($allArrivedBrands as $brand) {
                                ?>
                                <div class="swiper-slide">
                                    <div class="card mb-3 p-3 rounded-4 shadow border-0">
                                        <div class="row g-0">
                                            <div class="col-md-4">
                                                <img src="adminpanel/img/<?php echo htmlspecialchars($brand['image']); ?>" class="img-fluid rounded" alt="<?php echo htmlspecialchars($brand['name']); ?>">
                                            </div>
                                            <div class="col-md-8">
                                                <div class="card-body py-0">
                                                    <p class="text-muted mb-0"><?php echo htmlspecialchars($brand['name']); ?></p>
                                                    <h5 class="card-title"><?php echo htmlspecialchars($brand['des']); ?></h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        } else {
                            echo '<p>No brands available at the moment.</p>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="shop" class="py-5">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="bootstrap-tabs product-tabs">
                    <div class="tabs-header d-flex justify-content-between border-bottom my-5">
                        <h3>Trending Products</h3>
                        
                        <nav>
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <!-- All Products Tab -->
                                <a href="index.php" class="nav-link text-uppercase fs-6 <?php echo !isset($_GET['c_id']) ? 'active' : ''; ?>" id="nav-all-tab" data-bs-toggle="tab" data-bs-target="#nav-all">All</a>
                                

                                <!-- Dynamic Category Tabs -->
                                <?php 
                                // Fetch all categories dynamically
                                $query = $pdo->query("SELECT * FROM category");
                                $allCategories = $query->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($allCategories as $cat) {
                                    ?>
                                    <a href="index.php?c_id=<?php echo $cat['id']; ?>" class="nav-link text-uppercase fs-6 <?php echo (isset($_GET['c_id']) && $_GET['c_id'] == $cat['id']) ? 'active' : ''; ?>" id="nav-<?php echo $cat['id']; ?>-tab" data-bs-toggle="tab" data-bs-target="#nav-<?php echo $cat['id']; ?>">
                                        <?php echo $cat['name']; ?>
                                    </a>
                                <?php } ?>
                            </div>
                        </nav>

     </div>
   <div class="tab-content" id="nav-tabContent">

<!-- All Products Tab -->
    <div class="tab-pane fade <?php echo !isset($_GET['c_id']) ? 'show active' : ''; ?>" id="nav-all" role="tabpanel" aria-labelledby="nav-all-tab">
    <div class="product-grid row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5">
        <?php
        // Fetch all products if no category is selected
        $query = $pdo->query("SELECT * FROM products");
        $products = $query->fetchAll(PDO::FETCH_ASSOC);

        // Display products
        foreach ($products as $product) : ?>
        <div class="col">
            <div class="product-item">
                <?php if (!empty($product['offer'])) : ?>
                    <span class="badge bg-success position-absolute m-3"><?php echo $product['offer']; ?></span>
                <?php endif; ?>
                <form method="post">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <button type="submit" name="addWishlist" class="btn-wishlist">
                        <svg width="24" height="24"><use xlink:href="#heart"></use></svg>
                    </button>
                </form>
                <figure>
                    <a href="index.php" title="<?php echo $product['name']; ?>">
                        <img src="adminpanel/img/<?php echo $product['image']; ?>" class="tab-image" alt="<?php echo $product['name']; ?>">
                    </a>
                </figure>
                <h3><?php echo $product['name']; ?></h3>
                <span class="qty"><?php echo $product['des']; ?></span>
                <span class="rating">
                    <svg width="24" height="24" class="text-primary"><use xlink:href="#star-solid"></use></svg> <?php echo $product['rating']; ?>
                </span>
                <span class="price">$<?php echo $product['prize']; ?></span>
                <form method="post">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="input-group product-qty">
                            <span class="input-group-btn">
                                <button type="button" class="quantity-left-minus btn btn-danger btn-number" data-type="minus">
                                    <input type="hidden" name="p_name" value="<?php echo $product['name']?>">
                                    <input type="hidden" name="p_des" value="<?php echo $product['des']?>">
                                    <input type="hidden" name="p_price" value="<?php echo $product['prize']?>">
                                    <input type="hidden" name="p_image" value="<?php echo $product['image']?>">
                                    <input type="hidden" name="p_rating" value="<?php echo $product['rating']?>">
                                    <input type="hidden" name="p_id" value="<?php echo $product['id']?>">
                                    <svg width="16" height="16"><use xlink:href="#minus"></use></svg>
                                </button>
                            </span>
                            <input type="text" id="quantity" name="p_qty" class="form-control input-number" value="1">
                            <span class="input-group-btn">
                                <button type="button" class="quantity-right-plus btn btn-success btn-number" data-type="plus">
                                    <svg width="16" height="16"><use xlink:href="#plus"></use></svg>
                                </button>
                            </span>
                        </div>

                        <button type="submit" name="addCart" class="nav-link">Add to Cart <iconify-icon icon="uil:shopping-cart"></iconify-icon></button>
                    </div>
                </form>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    </div>

<!-- Dynamic Category Tabs -->
    <?php foreach ($allCategories as $cat) : ?>
   <div class="tab-pane fade <?php echo (isset($_GET['c_id']) && $_GET['c_id'] == $cat['id']) ? 'show active' : ''; ?>" id="nav-<?php echo $cat['id']; ?>" role="tabpanel" aria-labelledby="nav-<?php echo $cat['id']; ?>-tab">
    <div class="product-grid row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5">
        <?php
        // Fetch products for the selected category
        $c_id = $cat['id'];
        $query = $pdo->prepare("SELECT * FROM products WHERE c_id = :c_id");
        $query->bindParam(':c_id', $c_id, PDO::PARAM_INT);
        $query->execute();
        $categoryProducts = $query->fetchAll(PDO::FETCH_ASSOC);

        // Display products
        foreach ($categoryProducts as $product) : ?>
        <div class="col">
            <div class="product-item">
                <?php if (!empty($product['offer'])) : ?>
                    <span class="badge bg-success position-absolute m-3"><?php echo $product['offer']; ?></span>
                <?php endif; ?>
                <form method="post">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <button type="submit" name="addWishlist" class="btn-wishlist">
                        <svg width="24" height="24"><use xlink:href="#heart"></use></svg>
                    </button>
                </form>
                <figure>
                    <a href="index.php" title="<?php echo $product['name']; ?>">
                        <img src="adminpanel/img/<?php echo $product['image']; ?>" class="tab-image" alt="<?php echo $product['name']; ?>">
                    </a>
                </figure>
                <h3><?php echo $product['name']; ?></h3>
                <span class="qty"><?php echo $product['des']; ?></span>
                <span class="rating">
                    <svg width="24" height="24" class="text-primary"><use xlink:href="#star-solid"></use></svg> <?php echo $product['rating']; ?>
                </span>
                <span class="price">$<?php echo $product['prize']; ?></span>
                <form method="post">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="input-group product-qty">
                            <span class="input-group-btn">
                                <button type="button" class="quantity-left-minus btn btn-danger btn-number" data-type="minus">
                                    <input type="hidden" name="p_name" value="<?php echo $product['name']?>">
                                    <input type="hidden" name="p_des" value="<?php echo $product['des']?>">
                                    <input type="hidden" name="p_price" value="<?php echo $product['prize']?>">
                                    <input type="hidden" name="p_image" value="<?php echo $product['image']?>">
                                    <input type="hidden" name="p_rating" value="<?php echo $product['rating']?>">
                                    <input type="hidden" name="p_id" value="<?php echo $product['id']?>">
                                    <svg width="16" height="16"><use xlink:href="#minus"></use></svg>
                                </button>
                            </span>
                            <input type="text" id="quantity" name="p_qty" class="form-control input-number" value="1">
                            <span class="input-group-btn">
                                <button type="button" class="quantity-right-plus btn btn-success btn-number" data-type="plus">
                                    <svg width="16" height="16"><use xlink:href="#plus"></use></svg>
                                </button>
                            </span>
                        </div>

                        <button type="submit" name="addCart" class="nav-link">Add to Cart <iconify-icon icon="uil:shopping-cart"></iconify-icon></button>
                    </div>
                </form>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
      </div>
<?php endforeach; ?>

</div>
           
 </div>

</div>
 </div>
</section>

    <section class="py-5">
      <div class="container-fluid">
        <div class="row">
          
          <div class="col-md-6">
            <div class="banner-ad bg-danger mb-3" style="background: url('images/ad-image-3.png');background-repeat: no-repeat;background-position: right bottom;">
              <div class="banner-content p-5">

                <div class="categories text-primary fs-3 fw-bold">Upto 25% Off</div>
                <h3 class="banner-title">Luxa Dark Chocolate</h3>
                <p>Very tasty & creamy vanilla flavour creamy muffins.</p>
                <a href="#" class="btn btn-dark text-uppercase">Show Now</a>

              </div>
            
            </div>
          </div>
          <div class="col-md-6">
            <div class="banner-ad bg-info" style="background: url('images/ad-image-4.png');background-repeat: no-repeat;background-position: right bottom;">
              <div class="banner-content p-5">

                <div class="categories text-primary fs-3 fw-bold">Upto 25% Off</div>
                <h3 class="banner-title">Creamy Muffins</h3>
                <p>Very tasty & creamy vanilla flavour creamy muffins.</p>
                <a href="#" class="btn btn-dark text-uppercase">Show Now</a>

              </div>
            
            </div>
          </div>
             
        </div>
      </div>
    </section>


<section id="Contact" class="py-5">
    <div class="container-fluid">
        <div class="bg-secondary py-5 my-5 rounded-5" style="background: url('images/bg-leaves-img-pattern.png') no-repeat;">
            <div class="container my-5">
                <div class="row">
                    <div class="col-md-6 p-5">
                        <div class="section-header">
                            <h2 class="section-title display-4">Get <span class="text-primary">25% Discount</span> on your first purchase</h2>
                        </div>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Dictumst amet, metus, sit massa posuere maecenas. At tellus ut nunc amet vel egestas.</p>
                    </div>
                    <div class="col-md-6 p-5">
                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger">
                                <?php echo implode('<br>', $errors); ?>
                            </div>
                        <?php elseif (!empty($success_message)): ?>
                            <div class="alert alert-success">
                                <?php echo $success_message; ?>
                            </div>
                        <?php endif; ?>
                        <form  method="POST">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control form-control-lg" name="name" id="name" placeholder="Name">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control form-control-lg" name="email" id="email" placeholder="abc@mail.com">
                            </div>
                            <div class="form-check form-check-inline mb-3">
                                <label class="form-check-label" for="subscribe">
                                    <input class="form-check-input" type="checkbox" id="subscribe" name="subscribe" value="1">
                                    Subscribe to the newsletter
                                </label>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-dark btn-lg">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



    <section id="blog" id="latest-blog" class="py-5">
      <div class="container-fluid">
        <div class="row">
          <div class="section-header d-flex align-items-center justify-content-between my-5">
            <h2 class="section-title">Our Recent Blog</h2>
            <div class="btn-wrap align-right">
              <a href="#" class="d-flex align-items-center nav-link">Read All Articles <svg width="24" height="24"><use xlink:href="#arrow-right"></use></svg></a>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <article class="post-item card border-0 shadow-sm p-3">
              <div class="image-holder zoom-effect">
                <a href="#">
                  <img src="images/post-thumb-1.jpg" alt="post" class="card-img-top">
                </a>
              </div>
              <div class="card-body">
                <div class="post-meta d-flex text-uppercase gap-3 my-2 align-items-center">
                  <div class="meta-date"><svg width="16" height="16"><use xlink:href="#calendar"></use></svg>22 Aug 2021</div>
                  <div class="meta-categories"><svg width="16" height="16"><use xlink:href="#category"></use></svg>tips & tricks</div>
                </div>
                <div class="post-header">
                  <h3 class="post-title">
                    <a href="#" class="text-decoration-none">Top 10 casual look ideas to dress up your kids</a>
                  </h3>
                  <p>Lorem ipsum dolor sit amet, consectetur adipi elit. Aliquet eleifend viverra enim tincidunt donec quam. A in arcu, hendrerit neque dolor morbi...</p>
                </div>
              </div>
            </article>
          </div>
          <div class="col-md-4">
            <article class="post-item card border-0 shadow-sm p-3">
              <div class="image-holder zoom-effect">
                <a href="#">
                  <img src="images/post-thumb-2.jpg" alt="post" class="card-img-top">
                </a>
              </div>
              <div class="card-body">
                <div class="post-meta d-flex text-uppercase gap-3 my-2 align-items-center">
                  <div class="meta-date"><svg width="16" height="16"><use xlink:href="#calendar"></use></svg>25 Aug 2021</div>
                  <div class="meta-categories"><svg width="16" height="16"><use xlink:href="#category"></use></svg>trending</div>
                </div>
                <div class="post-header">
                  <h3 class="post-title">
                    <a href="#" class="text-decoration-none">Latest trends of wearing street wears supremely</a>
                  </h3>
                  <p>Lorem ipsum dolor sit amet, consectetur adipi elit. Aliquet eleifend viverra enim tincidunt donec quam. A in arcu, hendrerit neque dolor morbi...</p>
                </div>
              </div>
            </article>
          </div>
          <div class="col-md-4">
            <article class="post-item card border-0 shadow-sm p-3">
              <div class="image-holder zoom-effect">
                <a href="#">
                  <img src="images/post-thumb-3.jpg" alt="post" class="card-img-top">
                </a>
              </div>
              <div class="card-body">
                <div class="post-meta d-flex text-uppercase gap-3 my-2 align-items-center">
                  <div class="meta-date"><svg width="16" height="16"><use xlink:href="#calendar"></use></svg>28 Aug 2021</div>
                  <div class="meta-categories"><svg width="16" height="16"><use xlink:href="#category"></use></svg>inspiration</div>
                </div>
                <div class="post-header">
                  <h3 class="post-title">
                    <a href="#" class="text-decoration-none">10 Different Types of comfortable clothes ideas for women</a>
                  </h3>
                  <p>Lorem ipsum dolor sit amet, consectetur adipi elit. Aliquet eleifend viverra enim tincidunt donec quam. A in arcu, hendrerit neque dolor morbi...</p>
                </div>
              </div>
            </article>
          </div>
        </div>
      </div>
    </section>

    <section class="py-5 my-5">
      <div class="container-fluid">

        <div class="bg-warning py-5 rounded-5" style="background-image: url('images/bg-pattern-2.png') no-repeat;">
          <div class="container">
            <div class="row">
              <div class="col-md-4">
                <img src="images/phone.png" alt="phone" class="image-float img-fluid">
              </div>
              <div class="col-md-8">
                <h2 class="my-5">Shop faster with foodmart App</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sagittis sed ptibus liberolectus nonet psryroin. Amet sed lorem posuere sit iaculis amet, ac urna. Adipiscing fames semper erat ac in suspendisse iaculis. Amet blandit tortor praesent ante vitae. A, enim pretiummi senectus magna. Sagittis sed ptibus liberolectus non et psryroin.</p>
                <div class="d-flex gap-2 flex-wrap">
                  <img src="images/app-store.jpg" alt="app-store">
                  <img src="images/google-play.jpg" alt="google-play">
                </div>
              </div>
            </div>
          </div>
        </div>
        
      </div>
    </section>

    <section class="py-5">
      <div class="container-fluid">
        <h2 class="my-5">People are also looking for</h2>
        <a href="#" class="btn btn-warning me-2 mb-2">Blue diamon almonds</a>
        <a href="#" class="btn btn-warning me-2 mb-2">Angie’s Boomchickapop Corn</a>
        <a href="#" class="btn btn-warning me-2 mb-2">Salty kettle Corn</a>
        <a href="#" class="btn btn-warning me-2 mb-2">Chobani Greek Yogurt</a>
        <a href="#" class="btn btn-warning me-2 mb-2">Sweet Vanilla Yogurt</a>
        <a href="#" class="btn btn-warning me-2 mb-2">Foster Farms Takeout Crispy wings</a>
        <a href="#" class="btn btn-warning me-2 mb-2">Warrior Blend Organic</a>
        <a href="#" class="btn btn-warning me-2 mb-2">Chao Cheese Creamy</a>
        <a href="#" class="btn btn-warning me-2 mb-2">Chicken meatballs</a>
        <a href="#" class="btn btn-warning me-2 mb-2">Blue diamon almonds</a>
        <a href="#" class="btn btn-warning me-2 mb-2">Angie’s Boomchickapop Corn</a>
        <a href="#" class="btn btn-warning me-2 mb-2">Salty kettle Corn</a>
        <a href="#" class="btn btn-warning me-2 mb-2">Chobani Greek Yogurt</a>
        <a href="#" class="btn btn-warning me-2 mb-2">Sweet Vanilla Yogurt</a>
        <a href="#" class="btn btn-warning me-2 mb-2">Foster Farms Takeout Crispy wings</a>
        <a href="#" class="btn btn-warning me-2 mb-2">Warrior Blend Organic</a>
        <a href="#" class="btn btn-warning me-2 mb-2">Chao Cheese Creamy</a>
        <a href="#" class="btn btn-warning me-2 mb-2">Chicken meatballs</a>
      </div>
    </section>

    <section class="py-5">
      <div class="container-fluid">
        <div class="row row-cols-1 row-cols-sm-3 row-cols-lg-5">
          <div class="col">
            <div class="card mb-3 border-0">
              <div class="row">
                <div class="col-md-2 text-dark">
                  <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"><path fill="currentColor" d="M21.5 15a3 3 0 0 0-1.9-2.78l1.87-7a1 1 0 0 0-.18-.87A1 1 0 0 0 20.5 4H6.8l-.33-1.26A1 1 0 0 0 5.5 2h-2v2h1.23l2.48 9.26a1 1 0 0 0 1 .74H18.5a1 1 0 0 1 0 2h-13a1 1 0 0 0 0 2h1.18a3 3 0 1 0 5.64 0h2.36a3 3 0 1 0 5.82 1a2.94 2.94 0 0 0-.4-1.47A3 3 0 0 0 21.5 15Zm-3.91-3H9L7.34 6H19.2ZM9.5 20a1 1 0 1 1 1-1a1 1 0 0 1-1 1Zm8 0a1 1 0 1 1 1-1a1 1 0 0 1-1 1Z"/></svg>
                </div>
                <div class="col-md-10">
                  <div class="card-body p-0">
                    <h5>Free delivery</h5>
                    <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipi elit.</p>
                  </div>
                </div>
              </div>
              </div>
          </div>
          <div class="col">
            <div class="card mb-3 border-0">
              <div class="row">
                <div class="col-md-2 text-dark">
                  <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"><path fill="currentColor" d="M19.63 3.65a1 1 0 0 0-.84-.2a8 8 0 0 1-6.22-1.27a1 1 0 0 0-1.14 0a8 8 0 0 1-6.22 1.27a1 1 0 0 0-.84.2a1 1 0 0 0-.37.78v7.45a9 9 0 0 0 3.77 7.33l3.65 2.6a1 1 0 0 0 1.16 0l3.65-2.6A9 9 0 0 0 20 11.88V4.43a1 1 0 0 0-.37-.78ZM18 11.88a7 7 0 0 1-2.93 5.7L12 19.77l-3.07-2.19A7 7 0 0 1 6 11.88v-6.3a10 10 0 0 0 6-1.39a10 10 0 0 0 6 1.39Zm-4.46-2.29l-2.69 2.7l-.89-.9a1 1 0 0 0-1.42 1.42l1.6 1.6a1 1 0 0 0 1.42 0L15 11a1 1 0 0 0-1.42-1.42Z"/></svg>
                </div>
                <div class="col-md-10">
                  <div class="card-body p-0">
                    <h5>100% secure payment</h5>
                    <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipi elit.</p>
                  </div>
                </div>
              </div>
              </div>
          </div>
          <div class="col">
            <div class="card mb-3 border-0">
              <div class="row">
                <div class="col-md-2 text-dark">
                  <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"><path fill="currentColor" d="M22 5H2a1 1 0 0 0-1 1v4a3 3 0 0 0 2 2.82V22a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1v-9.18A3 3 0 0 0 23 10V6a1 1 0 0 0-1-1Zm-7 2h2v3a1 1 0 0 1-2 0Zm-4 0h2v3a1 1 0 0 1-2 0ZM7 7h2v3a1 1 0 0 1-2 0Zm-3 4a1 1 0 0 1-1-1V7h2v3a1 1 0 0 1-1 1Zm10 10h-4v-2a2 2 0 0 1 4 0Zm5 0h-3v-2a4 4 0 0 0-8 0v2H5v-8.18a3.17 3.17 0 0 0 1-.6a3 3 0 0 0 4 0a3 3 0 0 0 4 0a3 3 0 0 0 4 0a3.17 3.17 0 0 0 1 .6Zm2-11a1 1 0 0 1-2 0V7h2ZM4.3 3H20a1 1 0 0 0 0-2H4.3a1 1 0 0 0 0 2Z"/></svg>
                </div>
                <div class="col-md-10">
                  <div class="card-body p-0">
                    <h5>Quality guarantee</h5>
                    <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipi elit.</p>
                  </div>
                </div>
              </div>
              </div>
          </div>
          <div class="col">
            <div class="card mb-3 border-0">
              <div class="row">
                <div class="col-md-2 text-dark">
                  <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"><path fill="currentColor" d="M12 8.35a3.07 3.07 0 0 0-3.54.53a3 3 0 0 0 0 4.24L11.29 16a1 1 0 0 0 1.42 0l2.83-2.83a3 3 0 0 0 0-4.24A3.07 3.07 0 0 0 12 8.35Zm2.12 3.36L12 13.83l-2.12-2.12a1 1 0 0 1 0-1.42a1 1 0 0 1 1.41 0a1 1 0 0 0 1.42 0a1 1 0 0 1 1.41 0a1 1 0 0 1 0 1.42ZM12 2A10 10 0 0 0 2 12a9.89 9.89 0 0 0 2.26 6.33l-2 2a1 1 0 0 0-.21 1.09A1 1 0 0 0 3 22h9a10 10 0 0 0 0-20Zm0 18H5.41l.93-.93a1 1 0 0 0 0-1.41A8 8 0 1 1 12 20Z"/></svg>
                </div>
                <div class="col-md-10">
                  <div class="card-body p-0">
                    <h5>guaranteed savings</h5>
                    <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipi elit.</p>
                  </div>
                </div>
              </div>
              </div>
          </div>
          <div class="col">
            <div class="card mb-3 border-0">
              <div class="row">
                <div class="col-md-2 text-dark">
                  <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"><path fill="currentColor" d="M18 7h-.35A3.45 3.45 0 0 0 18 5.5a3.49 3.49 0 0 0-6-2.44A3.49 3.49 0 0 0 6 5.5A3.45 3.45 0 0 0 6.35 7H6a3 3 0 0 0-3 3v2a1 1 0 0 0 1 1h1v6a3 3 0 0 0 3 3h8a3 3 0 0 0 3-3v-6h1a1 1 0 0 0 1-1v-2a3 3 0 0 0-3-3Zm-7 13H8a1 1 0 0 1-1-1v-6h4Zm0-9H5v-1a1 1 0 0 1 1-1h5Zm0-4H9.5A1.5 1.5 0 1 1 11 5.5Zm2-1.5A1.5 1.5 0 1 1 14.5 7H13ZM17 19a1 1 0 0 1-1 1h-3v-7h4Zm2-8h-6V9h5a1 1 0 0 1 1 1Z"/></svg>
                </div>
                <div class="col-md-10">
                  <div class="card-body p-0">
                    <h5>Daily offers</h5>
                    <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipi elit.</p>
                  </div>
                </div>
              </div>
              </div>
          </div>
        </div>
      </div>
    </section>

    <?php 
    include('components/footer.php');
    ?>