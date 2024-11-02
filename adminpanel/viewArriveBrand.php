<?php
include('query.php');
include('header.php');
?>

<!-- Blank Start -->
<div class="container-fluid pt-4 px-4">
    <div class="row vh-100 bg-light rounded align-items-center justify-content-center mx-0">
        <div class="col-md-8"> <!-- Adjusted width for better layout -->
            <h3 class="mb-4">Brands View</h3>
            <table class="table table-bordered table-hover"> <!-- Added Bootstrap classes -->
                <thead class="table-dark"> <!-- Header styling -->
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Image</th>
                        <th colspan="2" class="text-center">Action</th> 
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = $pdo->query("SELECT * FROM `arrived brands`");
                    $allArriveBrands = $query->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($allArriveBrands as $arrivebrand) {
                    ?>
                        <tr>
                            <td scope="row"><?php echo htmlspecialchars($arrivebrand['name']); ?></td> 
                            <td><?php echo htmlspecialchars($arrivebrand['des']); ?></td>
                            <td><img height="50px" src="img/<?php echo htmlspecialchars($arrivebrand['image']); ?>" alt=""></td>
                            <td class="text-center">
                                <a href="editArriveBrand.php?abid=<?php echo $arrivebrand['id']; ?>" class="btn btn-info">Edit</a>
                            </td>
                            <td class="text-center">
                                <a href="?abdid=<?php echo $arrivebrand['id']; ?>" class="btn btn-danger">Delete</a>
                            </td>
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
