<?php
include('dbcon.php');
// session_start();

//signup
if(isset($_POST['signUp'])){
    $name=$_POST['uName'];
    $email=$_POST['uEmail'];
    $pass=$_POST['uPassword'];
   $query=$pdo->prepare("insert into users(name,email,password)values(:sName, :sEmail,:sPassword)");
            $query->bindParam('sName',$name);
            $query->bindParam('sEmail',$email);
            $query->bindParam('sPassword',$pass);
            $query->execute();
            $user=$query->fetch(PDO::FETCH_ASSOC);
            // print_r($user);
            echo "<script>alert('Register added successfully');
        location.assign('signin.php');
        </script>";
        }
    


//add category
if(isset($_POST['addCategory'])){
    $cName=$_POST['cName'];
    $cImageName=$_FILES['cImage']['name'];
    $cImageTmpName=$_FILES['cImage']['tmp_name'];
    $extension=pathinfo($cImageName,PATHINFO_EXTENSION);
    $destination="img/".$cImageName;
    if($extension == "jpg"|| $extension == "png"|| $extension == "jpeg"){
        if(move_uploaded_file($cImageTmpName,$destination)){
            $query=$pdo->prepare("insert into category(name,image)values(:cName, :cImage)");
            $query->bindParam('cName',$cName);
            $query->bindParam('cImage',$cImageName);
            $query->execute();
            echo "<script>alert('Category added successfully');
        location.assign('index.php');
        </script>";
        }
    }

}
//add brands
if(isset($_POST['addBrands'])){
    $cName=$_POST['bName'];
    $cDes=$_POST['bDes'];
    $sDes=$_POST['bsDes'];
    $cImageName=$_FILES['bImage']['name'];
    $cImageTmpName=$_FILES['bImage']['tmp_name'];
    $extension=pathinfo($cImageName,PATHINFO_EXTENSION);
    $destination="img/".$cImageName;
    if($extension == "jpg"|| $extension == "png"|| $extension == "jpeg"|| $extension == "avif"){
        if(move_uploaded_file($cImageTmpName,$destination)){
            $query=$pdo->prepare("insert into brands(name,des,subdes,image)values(:cName, :cDes,:sDes, :cImage)");
            $query->bindParam('cName',$cName);
            $query->bindParam('cDes',$cDes);
            $query->bindParam('sDes',$sDes);
            $query->bindParam('cImage',$cImageName);
            $query->execute();
            echo "<script>alert('Brands added successfully');
        location.assign('index.php');
        </script>";
        }
    }


}
// Add Arrive Brands
if (isset($_POST['addArriveBrands'])) {
    $abName = $_POST['abName'];
    $abDes = $_POST['abDes'];
    $abImageName = $_FILES['abImage']['name'];
    $abImageTmpName = $_FILES['abImage']['tmp_name'];
    $extension = pathinfo($abImageName, PATHINFO_EXTENSION);
    $destination = "img/" . $abImageName;

    if ($extension == "jpg" || $extension == "png" || $extension == "jpeg" || $extension == "avif") {
        if (move_uploaded_file($abImageTmpName, $destination)) {
            $query = $pdo->prepare("INSERT INTO `arrived brands` (name, des, image) VALUES (:abName, :abDes, :abImage)");
            $query->bindParam(':abName', $abName);
            $query->bindParam(':abDes', $abDes);
            $query->bindParam(':abImage', $abImageName); 

            if ($query->execute()) {
                echo "<script>alert('Arrive Brands added successfully');
                location.assign('index.php');
                </script>";
            } else {
                echo "<script>alert('Error adding Arrive Brands.');</script>";
            }
        } 
    } 
}

//update category
if(isset($_POST['updateCategory'])){
    $id=$_GET['cid'];
    $cName=$_POST['cName'];
    $query=$pdo->prepare("update category set name=:uName where id=:cid");
    if(isset($_FILES['cImage'])){
        $cImageName=$_FILES['cImage']['name'];
        $cImageTmpName=$_FILES['cImage']['tmp_name'];
        $extension=pathinfo($cImageName,PATHINFO_EXTENSION);
        $destination="img/".$cImageName;
        if($extension == "jpg"|| $extension == "png"|| $extension == "jpeg"){
            if(move_uploaded_file($cImageTmpName,$destination)){
                $query=$pdo->prepare("update category set name=:uName,image=:uImage where id=:cid");
                $query->bindParam('uImage',$cImageName);  
    }
}
   
            $query->bindParam('cid',$id);
            $query->bindParam('uName',$cName);
            $query->execute();
            echo "<script>alert('Category update successfully');
        location.assign('viewCategory.php');
        </script>";
        }
    }
    
//update Arrive Brands
if(isset($_POST['editArriveBrands'])){
    $id=$_GET['abid'];
    $cName=$_POST['abName'];
    $cDes=$_POST['abDes'];
    $query=$pdo->prepare("update `arrived brands` set name=:uName,des=:udes where id=:cid");
    if(isset($_FILES['abImage'])){
        $cImageName=$_FILES['abImage']['name'];
        $cImageTmpName=$_FILES['abImage']['tmp_name'];
        $extension=pathinfo($cImageName,PATHINFO_EXTENSION);
        $destination="img/".$cImageName;
        if($extension == "jpg"|| $extension == "png"|| $extension == "jpeg"){
            if(move_uploaded_file($cImageTmpName,$destination)){
                $query=$pdo->prepare("update `arrived brands` set name=:uName,des=:udes,image=:uImage where id=:cid");
                $query->bindParam('uImage',$cImageName);  
    }
}
   
            $query->bindParam('cid',$id);
            $query->bindParam('uName',$cName);
            $query->bindParam('udes',$cDes);
            $query->execute();
            echo "<script>alert('Arrived update successfully');
        location.assign('viewArriveBrand.php');
        </script>";
        }
    }
    //delete catgeory
if(isset($_GET['cdid'])){
    $did=$_GET['cdid'];
    $query=$pdo->prepare("Delete from category where id=:did");
    $query->bindParam('did',$did);
    $query->execute();
    echo "<script>alert('delete category successfully');
    location.assign('viewCategory.php');
    </script>";

}
    //delete arrive brand
    if(isset($_GET['abdid'])){
        $did=$_GET['abdid'];
        $query=$pdo->prepare("Delete from `arrived brands` where id=:did");
        $query->bindParam('did',$did);
        $query->execute();
        echo "<script>alert('Delete arrive brand  successfully');
        location.assign('viewArriveBrand.php');
        </script>";
    
    }
//update category
if(isset($_POST['updateBrands'])){
    $id=$_GET['bid'];
    $cName=$_POST['bName'];
    $cDes=$_POST['bDes'];
    $bsDes=$_POST['bsDes'];
    $query=$pdo->prepare("update brands set name=:uName,des=:uDes,subdes=:sbdes where id=:cid");
    if(isset($_FILES['bImage'])){
        $cImageName=$_FILES['bImage']['name'];
        $cImageTmpName=$_FILES['bImage']['tmp_name'];
        $extension=pathinfo($cImageName,PATHINFO_EXTENSION);
        $destination="img/".$cImageName;
        if($extension == "jpg"|| $extension == "png"|| $extension == "jpeg"){
            if(move_uploaded_file($cImageTmpName,$destination)){
                $query=$pdo->prepare("update brands set name=:uName,des=:uDes,subdes=:sbdes,image=:uImage where id=:cid");
                $query->bindParam('uImage',$cImageName);  
    }
}
   
            $query->bindParam('cid',$id);
            $query->bindParam('uName',$cName);
            $query->bindParam('uDes',$cDes);
            $query->bindParam('sbdes',$bsDes);

            $query->execute();
            echo "<script>alert('Brands update successfully');
        location.assign('viewBrand.php');
        </script>";
        }
    }
    //delete brands
if(isset($_GET['bdid'])){
    $did=$_GET['bdid'];
    $query=$pdo->prepare("delete from brands where id=:did");
    $query->bindParam('did',$did);
    $query->execute();
    echo "<script>alert('delete brands successfully');
    location.assign('viewBrand.php');
    </script>";

}
//addproduct
if(isset($_POST['addProduct'])){
    $pName=$_POST['pName'];
    $pDes=$_POST['pDes'];
    $pOffer=$_POST['pOffer'];
    $price=$_POST['pPrice'];
    $qty=$_POST['pQty'];
    $cid=$_POST['cId'];
    $pImageName=$_FILES['pImage']['name'];
    $pImageTmpName=$_FILES['pImage']['tmp_name'];
    $extension=pathinfo($pImageName,PATHINFO_EXTENSION);
    $destination="img/".$pImageName;
    if($extension == "jpg"|| $extension == "png"|| $extension == "jpeg" || $extension == "webp"){
        if(move_uploaded_file($pImageTmpName,$destination)){
            $query=$pdo->prepare("insert into products(name,des,prize,offer,quantity,image,c_id)values(:pName, :pDes, :pPrz, :pOffer,:pQty, :pImage, :cid)");
            $query->bindParam('pName',$pName);
            $query->bindParam('pDes',$pDes);
            $query->bindParam('pPrz',$price);
            $query->bindParam('pOffer',$pOffer);
            $query->bindParam('pQty',$qty);
            $query->bindParam('pImage',$pImageName);
            $query->bindParam('cid',$cid);
            $query->execute();
            echo "<script>alert('Product added successfully');
        location.assign('index.php');
        </script>";
        }
    }

}
//update product
if(isset($_POST['updateProduct'])){
    $id=$_GET['pid'];
    $Name=$_POST['pName'];
    $Des=$_POST['pDes'];
    $Price=$_POST['pPrice'];
    $pOffer=$_POST['pOffer'];
    $Qty=$_POST['pQty'];
    $cId=$_POST['cId'];
    $query=$pdo->prepare("update products set name=:uName,des=:uDes,prize=:uPrice,offer=:pOffer,quantity=:uQty,c_id=:cId where id=:pid");
    if(isset($_FILES['pImage'])){
        $pImageName=$_FILES['pImage']['name'];
        $pImageTmpName=$_FILES['pImage']['tmp_name'];
        $extension=pathinfo($pImageName,PATHINFO_EXTENSION);
        $destination="img/".$pImageName;
        if($extension == "jpg"|| $extension == "png"|| $extension == "jpeg" || $extension == "webp"){
            if(move_uploaded_file($pImageTmpName,$destination)){
                $query=$pdo->prepare("update products set name=:uName,des=:uDes,prize=:uPrice,offer=:pOffer,quantity=:uQty,c_id=:cId,
                image=:uImage where id=:pid");
                $query->bindParam('uImage',$pImageName);  
    }
}
   
            $query->bindParam('pid',$id);
            $query->bindParam('uName',$Name);
            $query->bindParam('uDes',$Des);
            $query->bindParam('pOffer',$pOffer);
            $query->bindParam('uPrice',$Price);
            $query->bindParam('uQty',$Qty);
            $query->bindParam('cId',$cId);
            $query->execute();
            echo "<script>alert(' update product successfully');
        location.assign('viewProduct.php');
        </script>";
        }
    }
     //delete catgeory
if(isset($_GET['pdid'])){
    $pdid=$_GET['pdid'];
    $query=$pdo->prepare("delete from products where id=:pdid");
    $query->bindParam('pdid',$pdid);
    $query->execute();
    echo "<script>alert('delete product successfully');
    location.assign('viewProduct.php');
    </script>";

}

// Add to Wishlist
if (isset($_POST['addWishlist'])) {
    // Session se user id lena
    if (isset($_SESSION['userId'])) {
        $user_id = $_SESSION['userId'];
        $product_id = $_POST['product_id'];
        
        // Wishlist mein insert query user_id aur product_id ke sath
        $query = $pdo->prepare("INSERT INTO wishlist (user_id, product_id) VALUES (:uId, :pId)");
        $query->bindParam('uId', $user_id);
        $query->bindParam('pId', $product_id);
        
        // Query ko execute karna
        $query->execute();
        
        // Success message ya redirect
        echo "<script>alert('Added to Wishlist successfully'); location.assign('index.php');</script>";
    } else {
        echo "<script>alert('Please login first');</script>";
    }
}


//contact form


// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   

//     // Retrieve and validate form data
//     $name = trim($_POST['name']);
//     $email = trim($_POST['email']);
//     $subscribe = isset($_POST['subscribe']) ? 1 : 0;

//     $errors = [];

//     // Basic validation
//     if (empty($name)) {
//         $errors[] = 'Name is required.';
//     }

//     if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
//         $errors[] = 'A valid email is required.';
//     }

//     // If no errors, proceed with storing the data
//     if (empty($errors)) {
//         try {
//             // Prepare SQL statement
//             $stmt = $pdo->prepare("INSERT INTO contacts (name, email, subscribe) VALUES (?, ?, ?)");
//             $stmt->execute([$name, $email, $subscribe]);

//             $success_message = "Thank you for subscribing!";

//         } catch (PDOException $e) {
//             $errors[] = "Error: " . $e->getMessage();
//         }
//     }
// }
?>






