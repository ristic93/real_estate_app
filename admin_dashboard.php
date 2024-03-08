<?php

require_once 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header('location: index.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
</head>

<body>

    <?php if (isset($_SESSION['success_message'])) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php
            echo $_SESSION['success_message'];
            unset($_SESSION['success_message']);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>


    <div class="container">
        <div class="row mt-5 mb-5">
            <div class="col-md-12">
                <h2>Nekretnine</h2>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Ime nekretnine</th>
                            <th>Adresa nekretnine</th>
                            <th>Cena nekretnine</th>
                            <th>Slika</th>
                            <th>Agent</th>
                            <th>Karta nekretnine</th>
                            <th>Datum dodavanja nekretnine</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT 
                        `real_estates`.*,
                        agents.first_name AS agent_first_name,
                        agents.last_name AS agent_last_name
                        FROM `real_estates`
                        LEFT JOIN `agents` ON real_estates.agent_id = agents.agent_id;";

                        $run = $connect->query($sql);

                        $results = $run->fetch_all(MYSQLI_ASSOC);
                        $select_estates = $results;

                        foreach ($results as $result) : ?>

                            <tr>
                                <td><?php echo $result['estate_name'] ?></td>
                                <td><?php echo $result['estate_address'] ?></td>
                                <td><?php echo $result['estate_price'] ?>$</td>
                                <td><?php if (!empty($result['photo_path'])) : ?>
                                        <img src="<?php echo $result['photo_path'] ?>" alt="estate_img" width="60" height="60">
                                    <?php else : ?>
                                        Nema slike
                                    <?php endif; ?>
                                </td>
                                <td><?php

                                    if ($result['agent_first_name']) {
                                        echo $result['agent_first_name'] . " " . $result['agent_last_name'];
                                    } else {
                                        echo "Nema agenta";
                                    }

                                    ?></td>
                                <td><a href="<?php echo $result['access_card_pdf_path'] ?>" target="_blank">Kartica</a></td>
                                <td><?php
                                    $created_at = strtotime($result['created_at']);
                                    $new_date = date("d/m/Y", $created_at);
                                    echo $new_date;
                                    ?></td>
                                <td>
                                    <a href="edit_estate.php?estate_id=<?php echo $result['estate_id']; ?>" class="btn btn-warning">Izmeni</a>
                                    <form action="delete_estate.php" method="POST" style="display: inline-block;">
                                        <input type="hidden" name="estate_id" value="<?php echo $result['estate_id']; ?>">
                                        <button class="btn btn-danger">X</button>
                                    </form>
                                </td>
                            </tr>

                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="col-md-12 mt-5 mb-5">
                <h2>Agenti</h2>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Ime</th>
                            <th>Prezime</th>
                            <th>Email</th>
                            <th>Kontakt telefon</th>
                            <th>Datum registracije agenta</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM agents";
                        $run = $connect->query($sql);

                        $results = $run->fetch_all(MYSQLI_ASSOC);
                        $select_agents = $results;

                        foreach ($results as $result) : ?>

                            <tr>
                                <td><?php echo $result['first_name']; ?></td>
                                <td><?php echo $result['last_name']; ?></td>
                                <td><?php echo $result['email']; ?></td>
                                <td><?php echo $result['phone_number']; ?></td>
                                <td><?php echo date("d/m/Y", strtotime($result['created_at'])); ?></td>
                            </tr>

                        <?php endforeach; ?>

                    </tbody>
                </table>
            </div>
        </div>


        <div class="row mb-5">
            <div class="col-md-6">
                <h2>Dodaj novu nekretninu</h2>
                <form action="add_estate.php" method="POST" enctype="multipart/form-data">
                    Ime nekretnine: <input class="form-control" type="text" name="estate_name"><br>
                    Adresa nekretnine: <input class="form-control" type="text" name="estate_address"><br>
                    Cena nekretnine: <input class="form-control" type="text" name="estate_price"><br>

                    <input type="hidden" name="photo_path" id="photoPathInput">

                    <div id="dropzone-upload" class="dropzone"></div>

                    <input type="submit" class="btn btn-primary mt-3" value="Dodaj nekretninu">
                </form>
            </div>

            <div class="col-md-6">
                <h2>Dodaj agenta</h2>
                <form action="register_agent.php" method="POST">
                    Ime: <input class="form-control" type="text" name="first_name"><br>
                    Prezime: <input class="form-control" type="text" name="last_name"><br>
                    Email: <input class="form-control" type="email" name="email"><br>
                    Kontakt telefon: <input class="form-control" type="text" name="phone_number"><br>
                    <input class="btn btn-primary" type="submit" value="Dodaj agenta">
                </form>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-md-6">
                <h2>Dodeli agenta nekretnini</h2>
                <form action="assign_agent.php" method="POST">
                    <label for="">Odaberi agenta</label>
                    <select name="agent" class="form-select">
                        <?php foreach ($select_agents as $agent) : ?>
                            <option value="<?php echo $agent['agent_id']; ?>">
                                <?php echo $agent['first_name'] . " " . $agent['last_name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <label for="">Odaberi nekretninu</label>
                    <select name="estate" class="form-select">
                        <?php foreach ($select_estates as $estate) : ?>
                            <option value="<?php echo $estate['estate_id']; ?>">
                                <?php echo $estate['estate_name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <button class="btn btn-primary mt-3" type="submit">Dodeli nekretninu</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>

    <script>
        Dropzone.options.dropzoneUpload = {
            url: "upload_photo.php",
            paramName: "photo", // The name that will be used to transfer the file
            maxFilesize: 20, // MB
            acceptedFiles: "image/*",
            init: function() {
                this.on("success", function(file, response) {
                    // Parse the JSON response
                    const jsonResponse = JSON.parse(response);
                    // Chech if the file was uploaded successfully
                    if (jsonResponse.success) {
                        // Set the hidden input's value to the uploaded file's path
                        document.getElementById('photoPathInput').value = jsonResponse.photo_path;
                    } else {
                        console.error(jsonResponse.error);
                    }
                })
            }
        };
    </script>
</body>

</html>