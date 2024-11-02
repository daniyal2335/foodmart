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
                        <th>Sub Description</th>
                        <th>Image</th>
                        <th colspan="2" class="text-center">Action</th> <!-- Merged action headers -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = $pdo->query("SELECT * FROM brands");
                    $allBrands = $query->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($allBrands as $brand) {
                    ?>
                        <tr>
                            <td scope="row"><?php echo htmlspecialchars($brand['name']); ?></td> <!-- Added htmlspecialchars for security -->
                            <td><?php echo htmlspecialchars($brand['des']); ?></td>
                            <td><?php echo htmlspecialchars($brand['subdes']); ?></td>
                            <td><img height="50px" src="img/<?php echo htmlspecialchars($brand['image']); ?>" alt=""></td>
                            <td class="text-center">
                                <a href="editBrand.php?bid=<?php echo $brand['id']; ?>" class="btn btn-info">Edit</a>
                            </td>
                            <td class="text-center">
                                <a href="?bdid=<?php echo $brand['id']; ?>" class="btn btn-danger">Delete</a>
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
