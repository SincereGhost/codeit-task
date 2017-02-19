<?php
    require 'includes/header.php';

    if (!loginCheck()) {
        header('Location: login.php');
    }

    if (!empty($_COOKIE["PHPSESSID"])) {
        $session = $_COOKIE["PHPSESSID"];
        $users = $db_connect->query("SELECT * FROM users WHERE session_id = '$session'")->fetchAll();
        $user = array_shift($users);
        $user['date_registration'] = (new DateTime())->setTimestamp($user['date_registration'])->format('Y-m-d');
        $country_id = $user['country'];
        $country = $db_connect->query("SELECT * FROM country WHERE id = '$country_id'")->fetchAll();
        $user['country'] = array_shift($country)['name'];
    }
?>
    <table class="table">
        <tr>
            <td>Name</td>
            <td><?=$user['name']?></td>
        </tr>
        <tr>
            <td>Login</td>
            <td><?=$user['login']?></td>
        </tr>
        <tr>
            <td>Email</td>
            <td><?=$user['email']?></td>
        </tr>
        <tr>
            <td>Country</td>
            <td><?=$user['country']?></td>
        </tr>
        <tr>
            <td>Birth Date</td>
            <td><?=$user['date']?></td>
        </tr>
        <tr>
            <td>Register Date</td>
            <td><?=$user['date_registration']?></td>
        </tr>
    </table>
<?php
    require 'includes/footer.php';
