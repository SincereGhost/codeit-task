<?php
    require 'includes/header.php';

    $errors = array();

    global $db_connect;

    $form_data = [
        'login' => '',
        'password' => '',
    ];

    if (count($_POST) > 0) {
        if (!empty($_POST['login'])) {
            $login = clear_input($_POST['login']);
            $users = $db_connect->query("SELECT * FROM users WHERE login ='$login' OR email = '$login'")->fetchAll();
            if (count($users) > 0) {
                if (empty($_POST['password'])) {
                    $errors[] = "Please, check password";
                } else {
                    $password = clear_input($_POST['password']);
                    $password = sha1($password);
                    $users = $db_connect->query("SELECT * FROM users WHERE (login ='$login' OR email = '$login') AND password = '$password'")->fetchAll();
                    if(count($users) > 0) {
                        $stmt = $db_connect->prepare("UPDATE users SET session_id = '" . $_COOKIE['PHPSESSID'] . "' WHERE (login ='$login' OR email = '$login') AND password = '$password'");
                        $stmt->execute();
                        header('Location: index.php');
                    } else {
                        $errors[] = "Incorrect password";
                    }
                }
            } else {
                $errors[] = "User not found";
            }
        } else {
            $errors[] = "Please, check login email";
        }
    }

    if (loginCheck()) {
        header('Location: index.php');
    }
?>
<form method="post">
    <?php if (count($errors)):?>
        <div>
            <p>Errors:</p>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?=$error?></li>
                <?php endforeach;?>
            </ul>
        </div>
    <?php endif;?>
    <div class="form-group">
        <label for="login">Login\Email</label>
        <input type="text" name="login" class="form-control" placeholder="Login\Email">
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" class="form-control" placeholder="Password">
    </div>
    <button type="submit" class="btn btn-default">Submit</button>
</form>
<?php
    require 'includes/footer.php';
?>
