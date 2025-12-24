<?php

session_start();
$con = mysqli_connect("localhost", "root", "", "task4");
if (!$con) {
    die("Database connection error");
}
$msg = $msg_login =  "";

if(isset($_POST['log_submit'])){
    $email = $_POST['email'];
    $password = $_POST['password'];
    $query_user = mysqli_query($con, "SELECT id, email, password FROM registration WHERE email = '$email'");
    $fetch_user = mysqli_fetch_assoc($query_user);
    if ($fetch_user && password_verify($password, $fetch_user['password'])) {
        $_SESSION['id'] = $fetch_user['id'];
        $_SESSION['email'] = $fetch_user['email'];
        header("Location: home.php");
        exit;
    }
    else {
        $msg_login = '<div class="msg_error">Email or password incorect</div>';
    }
}

if(isset($_POST['reg_submit'])){
    $name = trim($_POST['name']);
    $surname = trim($_POST['surname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $conpassword = $_POST['conpassword'];
    $gender = $_POST['gender'] ?? "";
    if
    (
        $name == "" ||
        $surname == "" ||
        $email == "" ||
        $password == "" ||
        $conpassword == "" ||
        $gender == ""
    )
    {
        $msg = '<div class="msg_error">Fields are empty</div>';
    }
    else if($password != $conpassword){
        $msg = '<div class="msg_error">Password no correct</div>';
    }
    else{
        $query_email = mysqli_query($con, "SELECT email FROM registration WHERE email = '$email'");
        $fetch = mysqli_fetch_assoc($query_email);
        $num_rows = mysqli_num_rows($query_email);
        if($num_rows > 0){
            $msg = '<div class="msg_error">Email already exists</div>';
        }
        else{
            $password=password_hash($password,PASSWORD_DEFAULT);
            $query_insert=mysqli_query($con,"INSERT INTO registration(name,surname,email,password,gender) VALUES('$name','$surname','$email','$password','$gender')");
            if($query_insert){
                $_POST="";
                $msg = '<div class="msg_completed">Registration successful</div>';
            }
        }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css">
    <title>Document</title>
</head>
<body>
    <section class="center">
        <div class="flex flex_between">
            <div class="registration_div">
                <div>
                    <h2>Registration</h2>
                </div>
                <form action="" method="POST">
                    <label class="labels">Name:</label>
                    <input type="text" placeholder="name" name="name" class="inputs <?= (isset($_POST['reg_submit'])) && empty($_POST['name']) ? 'border_red' : '' ?>" value="<?= (!empty($_POST['name'])) ? $_POST['name'] : "" ?>">
                    <label class="labels">surname:</label>
                    <input type="text" placeholder="firstname" name="surname" class="inputs <?= (isset($_POST['reg_submit'])) && empty($_POST['surname']) ? 'border_red' : '' ?>" value="<?= (!empty($_POST['surname'])) ? $_POST['surname'] : "" ?>">
                    <label class="labels">email:</label>
                    <input type="email" placeholder="email" name="email" class="inputs <?= (isset($_POST['reg_submit'])) && empty($_POST['email']) ? 'border_red' : '' ?>" value="<?= (!empty($_POST['email'])) ? $_POST['email'] : "" ?>">
                    <label class="labels">password:</label>
                    <input type="password" placeholder="password" name="password" class="inputs <?= (isset($_POST['reg_submit'])) && empty($_POST['password']) ? 'border_red' : '' ?>">
                    <label class="labels">conpassword:</label>
                    <input type="password" placeholder="conpassword" name="conpassword" class="inputs <?= (isset($_POST['reg_submit'])) && empty($_POST['conpassword']) ? 'border_red' : '' ?>"><br>
                    <div class="gender">
                        <label><input type="radio" name="gender" value="male" <?= (isset($_POST['gender']) && $_POST['gender'] === 'male') ? 'checked' : '' ?>> Male</label>
                        <label><input type="radio" name="gender" value="female" <?= (isset($_POST['gender']) && $_POST['gender'] === 'female') ? 'checked' : '' ?>> Female</label>
                    </div>
                    <button type="submit" name="reg_submit" class="btn_submit">Submit</button>
                    <?= $msg ?>
                </form>
            </div>
            <div class="login_div">
                <div>
                    <h2>Login</h2>
                </div>
                <form action="" method="POST">
                    <label class="labels">email:</label>
                    <input type="email" placeholder="email" name="email" class="inputs">
                    <label class="labels">password:</label>
                    <input type="password" placeholder="password" name="password" class="inputs">
                    <button type="submit" name="log_submit" class="btn_submit">Login</button>
                    <?= $msg_login ?>
                </form>
            </div>
        </div>
    </section>
</body>
</html>