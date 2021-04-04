<?php require_once "app.php" ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Login | Pembayaran SPP</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <h1>Halaman Login</h1>
    </header>
    <div class="formbox">
        <form action="proseslogin.php" method="post">
            <p style="text-align: center;">
                <?php
                $app->pesan();
                ?>
            </p>
            <br>
            <label for="username"><b>Username</b></label>
            <input type="text" name="username" placeholder="Masukkan Username">
            <label for="password"><b>Password</b></label>
            <input type="password" name="password" placeholder="Masukkan Password">
            <input class="reset form-button" type="reset" value="Batal" style="margin-top: -5px;">
            <div class="middle" style="margin-top: -20px; align-items: baseline;">
                <input class="form-button" type="submit" name="login" value="Login">
                <a class="link" style="float: right; margin-top: 15px;" href="register.php">Daftar</a>
            </div>
    </div>
    </form>
    </div>
</body>

</html>