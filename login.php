<?php
/**
 * Created by PhpStorm.
 * User: steijlen
 * Date: 18-07-18
 * Time: 13:59
 */

// attach the session
session_start();

// Check if the session Token is set, if not generate a new token
if (empty($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}
$sessiontoken = $_SESSION['token'];

// check the login
if (isset($_SESSION['username']) && strlen(trim($_SESSION['username'])) > 0) {
    // a login is present, kick to the main page
    header('Location:index.php');
    exit;
}

// check if we're processing the form and if the form token is set
if (isset($_POST['username']) && isset($_POST['password']) && !empty($_POST['token'])) {
    // read the form fields
    $posttoken = $_POST['token'];
    $username = trim(strtolower($_POST['username']));
    $password = $_POST['password'];

    // Check if the $_POST token equals the session token to prevent CSRF attack
    if (hash_equals($sessiontoken, $posttoken)) {
        // Proceed to process the form data
        // read the vendor class autoloader
        require 'vendor/autoload.php';

        // read the config file
        require_once 'config.php';

        // instantiate the adLDAP object
        try {
            $adldap = new adLDAP\adLDAP($ad_options);
        } catch (adLDAPException $e) {
            echo 'Error! ' . $e . "<br/>";
            exit;
        }

        // attempt to authenticate the user
        if ($adldap->authenticate($username, $password)) {
            // check if the user is authorised to manage this server
            if ($adldap->user()->inGroup($username, "webadmins_full")) {
                // register the user in the session
                $_SESSION['username'] = $username;
                $_SESSION['access'] = 'w';
            } else if ($adldap->user()->inGroup($username, "webadmins_read")) {
                // register the user as read-only in the session
                $_SESSION['username'] = $username;
                $_SESSION['access'] = 'r';
            } else {
                // user is correct but not an admin, kick back to the login form
                header('Location:login.php');
                exit;
            }

            // login success, forward the user to the main page
            header('Location:index.php');
            exit;

        } else {
            // Authentication failed, kick back to the login form
            header('Location:login.php');

            exit;
        }
    } else {
        // Login attempt did not come from this page, invalid token possible CSRF attack
        header('Location:login.php');

        exit;
    }
}
?>
<!doctype html>
<html lang="nl">

<head>
    <!-- meta -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- title -->
    <title>Login | Create Site</title>
    
    <!-- css -->
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/login.css">
</head>

<body>

    <!-- main container -->
    <div class="container">
    
        <form class="form-signin" action="login.php" method="post">
            <h2 class="form-signin-heading">Create Site login</h2>
            <p><?php echo $error; ?></p>
            <label for="username" class="sr-only">Gebruiker</label>
            <input type="text" id="username" name="username" class="form-control" placeholder="Gebruiker" required autofocus>
            
            <label for="password" class="sr-only">Wachtwoord</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="Wachtwoord" required>
    
            <!-- hidden token to prevent CSRF attack -->
            <input type="hidden" name="token" value="<?php echo $sessiontoken; ?>">

            <button class="btn btn-lg btn-primary btn-block" type="submit">Inloggen</button>
        </form>
    
    </div><!-- /main container -->

    <!-- js --> 
    <script src="js/jquery-1.11.2.min.js"></script> 
    <script src="js/bootstrap.min.js"></script> 
    <script src="js/ie10-viewport-bug-workaround.js"></script>

</body>
</html>
