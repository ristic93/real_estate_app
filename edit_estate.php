<?php

require_once 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header('location: index.php');
    exit();
}

if (isset($_GET['estate_id'])) {
    $estate_id = $_GET['estate_id'];

    $sql = "SELECT * FROM `real_estates` 
            LEFT JOIN `agents` ON real_estates.`agent_id` = `agents`.`agent_id` 
            WHERE `estate_id` = $estate_id";
    $result = $connect->query($sql);
    $estate = $result->fetch_assoc();

    $connect->close();
} else {
    header('location: admin_dahsboard.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Real Estate</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>

    <div class="container">
        <div class="row mt-5">
            <div class="col-md-6">
                <h2>Edit Real Estate</h2>
                <form action="update_estate.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="estate_id" value="<?php echo $estate['estate_id']; ?>">

                    Ime nekretnine: <input class="form-control" type="text" name="estate_name" value="<?php echo $estate['estate_name']; ?>"><br>
                    Adresa nekretnine: <input class="form-control" type="text" name="estate_address" value="<?php echo $estate['estate_address']; ?>"><br>
                    Cena nekretnine: <input class="form-control" type="text" name="estate_price" value="<?php echo $estate['estate_price']; ?>"><br>

                    <input type="submit" class="btn btn-primary mt-2" value="Potvrdi izmene">
                    <a href="admin_dashboard.php" class="btn btn-warning mt-2">Vrati se nazad</a>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>
