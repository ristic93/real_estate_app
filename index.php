<?php

require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT admin_id, password FROM admins WHERE username = ?";

    $run = $connect->prepare($sql);
    $run->bind_param("s", $username);
    $run->execute();

    $results = $run->get_result();

    if ($results->num_rows == 1) {
        $admin = $results->fetch_assoc();

        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['admin_id'];

            $connect->close();
            header('location: admin_dashboard.php');
        } else {
            $_SESSION['error'] = "Netacan password!";

            $connect->close();
            header('location: index.php');
            exit();
        }
    } else {
        $_SESSION['error'] = "Netacan username!";

        $connect->close();
        header('location: index.php');
        exit();
    }
}

?>

<?php

if (isset($_SESSION['error'])) {
    echo $_SESSION['error'] . "<br>";
    unset($_SESSION['error']);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    <form action="" method="POST">
        Username: <input type="text" name="username"><br>
        Password: <input type="password" name="password"><br>
        <input type="submit" value="Login">
    </form>

</body>

</html>