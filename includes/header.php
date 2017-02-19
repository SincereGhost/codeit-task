<?php require_once 'config.php'?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">

    <title>CodeIT personal page</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/navbar.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<?php
    session_start();

    function loginCheck() {
        global $db_connect;

        if (isset($_COOKIE['PHPSESSID']) && $_COOKIE['PHPSESSID']) {
            $user = $db_connect->query("SELECT * FROM users WHERE session_id = '" . $_COOKIE['PHPSESSID'] . "'")->fetchAll();
            if (count($user) > 0) {
                return true;
            }
        }
        return false;
    }

    function clear_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        $data = preg_replace('/\s+/','',$data);
        return $data;
    }

    if (isset($_REQUEST['logout']) && $_REQUEST['logout'] == 'yes') {
        $stmt = $db_connect->prepare("UPDATE users SET session_id = '' WHERE session_id = '" . $_COOKIE['PHPSESSID'] . "' ");
        $stmt->execute();
        session_destroy();
        header('Location: login.php');
    }

?>
<body>

<div class="container">

    <!-- Static navbar -->
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">CodeIT Task</a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li><a href="index.php">Home</a></li>
                    <?php if (!loginCheck()):?>
                        <li><a href="registration.php">Registration</a></li>
                    <?php endif;?>
                </ul>
                <?php if (loginCheck()):?>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="?logout=yes">Logout</a></li>
                    </ul>
                <?php endif;?>
            </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
    </nav>
