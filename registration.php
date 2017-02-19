<?php
    require 'includes/header.php';

    $countries = $db_connect->query('SELECT * FROM country')->fetchAll();

    $errors = array();
    $form_data = [
        'login' => '',
        'name' => '',
        'email' => '',
        'date' => '',
        'country' => '',
    ];



    if (count($_POST) > 0) {
        if (!empty($_POST['login'])) {
            $login = clear_input($_POST['login']);
            $form_data['login'] = $login;
            $db_users = $db_connect->query("SELECT * FROM users WHERE login = '$login'")->fetchAll();
            if (count($db_users) > 0) {
                $errors[] = "User with this login already exist";
            }
        } else {
            $errors[] = "Please, check login";
        }
        if (!empty($_POST['name'])) {
            $name = clear_input($_POST['name']);
            $form_data['name'] = $name;
        } else {
            $errors[] = "Please, check name";
        }
        if (!empty($_POST['email'])) {
            $email = clear_input($_POST['email']);
            $form_data['email'] = $email;
            $db_users = $db_connect->query("SELECT * FROM users WHERE email = '$email'")->fetchAll();
            if (count($db_users) > 0) {
                $errors[] = "User with this email already exist";
            }
        } else {
            $errors[] = "Please, check email";
        }
        if (!empty($_POST['date'])) {
            $date = clear_input($_POST['date']);
            $form_data['date'] = $date;
        } else {
            $errors[] = "Please, check date";
        }
        if (!empty($_POST['country']) && $_POST['country'] != 'Select country') {
            $country = clear_input($_POST['country']);
            $form_data['country'] = $country;
        } else {
            $errors[] = "Please, check country";
        }
        if (!empty($_POST['password'])) {
            $password = clear_input($_POST['password']);
            $password = sha1($password);
        } else {
            $errors[] = "Please, check password";
        }
        if (!empty($_POST['agree'])) {
            $agree = clear_input($_POST['agree']);
        } else {
            $errors[] = "Please, check agree";
        }
        if (count($errors) < 1) {
            $stmt = $db_connect->prepare("INSERT INTO users (name, email, password, login, country, date, session_id, date_registration) VALUES (:name, :email, :password, :login, :country, :date, :session_id, :date_registration)");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':login', $login);
            $stmt->bindParam(':country', $country);
            $stmt->bindParam(':date', $date);
            $stmt->bindParam(':session_id', $_COOKIE["PHPSESSID"]);
            $stmt->bindParam(':date_registration', time());

            $stmt->execute();
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
        <label for="login">Login</label>
        <input type="text" name="login" class="form-control" placeholder="Login" value="<?=$form_data['login']?>">
    </div>
    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" name="name" class="form-control" placeholder="Name" value="<?=$form_data['name']?>">
    </div>
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" name="email" class="form-control" placeholder="Email" value="<?=$form_data['email']?>">
    </div>
    <div class="form-group">
        <label for="date">Birth date</label>
        <input type="date" name="date" class="form-control" placeholder="Birth date" value="<?=$form_data['date']?>">
    </div>
    <div class="form-group">
        <label for="country">Country</label>
        <select name="country" class="form-control">
            <option>Select country</option>
            <?php foreach ($countries as $country):?>
                <option value="<?=$country['id']?>" <?= (intval($form_data['country']) == $country['id']) ? "selected='selected'" : ""?>>
                    <?= $country['name']?>
                </option>
            <?php endforeach;?>
        </select>
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" class="form-control" placeholder="Password">
    </div>
    <div class="checkbox">
        <label>
            <input type="checkbox" name="agree"> Agree ....
        </label>
    </div>
    <button type="submit" class="btn btn-default">Submit</button>
</form>
<?php
    require 'includes/footer.php';
