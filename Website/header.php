<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <?php session_start();?>
    <head>
        <?php echo "<title> $title </title>\n" ?>
        <link href="style.css" rel="stylesheet"/>
        <title>Inventory</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="author" content="Brian Kelly" />
        <meta name="description" content="Inventory" />
        <meta name="keywords" content="HTML,CSS,PHP,Inventory" />
    </head>
    <body>
        <div id="header">
            <div id="navbar">
                <ul>
                    <li class="list_item"><?php                 //Home list item
                        if($title=="Home"){
                            echo "Home";
                        } else{
                            echo '<a href="index.php">Home</a>';
                        }?>
                    </li>
                    <?php                                       //register list item
                        if(!ISSET($_SESSION['username'])){
                            echo "<li class=\"list_item\"><a href=\"register.php\">Register</a></li>";
                        }?>
                    <li class="list_item"><?php                 //login/logout list item
                        if(ISSET ($_SESSION['username'])){
                            echo '<a href="logout.php">Logout</a>';
                        } else{
                            if($title == 'Login'){
                                echo 'Login';
                            }else{
                                echo "<a href=\"login.php\">Login</a>";
                            }
                        } ?>
                    </li>
                    <?php                                       //User's page list item
                        if(ISSET($_SESSION['username'])){
                            echo "<li class=\"list_item\"><a href=\"user.php\">User's page</a></li>";
                        }
                    ?>
                </ul>
            </div>
            <h2><?php echo $title ?></h2>
        </div>

<?php
//session_start();


/**
 * Various PHP function calls. Should be available on every page as they all include header.php
 * @param $user_id
 * @param $conn
 * @return bool
 */
function checkbrute($user_id, $conn){
    $result = $conn->query("select attempts from login_attempts");
    $result->fetch_assoc();
    if($result->num_rows == 4){
        $conn->query("UPDATE users SET is_active=1 where user_id=$user_id");
        return true;//they are locked
    }
    else{
        return false;//not locked
    }
}

function connectDB(){
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "Inventory";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    return $conn;
}

//include_once 'psl-config.php';
function sec_session_start() {
    $session_name = 'sec_session_id';   // Set a custom session name
    $secure = 'SECURE';
    // This stops JavaScript being able to access the session id.
    $httponly = true;
    // Forces sessions to only use cookies.
    if (ini_set('session.use_only_cookies', 1) === FALSE) {
        header("Location: ../error.php?err=Could not initiate a safe session (ini_set)");
        exit();
    }
    // Gets current cookies params.
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params($cookieParams["lifetime"],
        $cookieParams["path"],
        $cookieParams["domain"],
        $secure,
        $httponly);
    // Sets the session name to the one set above.
    session_name($session_name);
    session_start();            // Start the PHP session
    session_regenerate_id(true);    // regenerated the session, delete the old one.
}

function login($username_in, $password){
    $username_in = preg_replace("/[^a-zA-Z0-9]+/", "", $username_in);//strips out unnecessary characters
    $conn = connectDB();
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    if($sqlStatement = $conn->prepare("SELECT username, password, user_id FROM users where username = ? LIMIT 1")){//this prevents SQL injection attacks

        $sqlStatement->bind_param('s', $username_in);
        $sqlStatement->execute();
        $sqlStatement->store_result();

        // get variables from result.
        $sqlStatement->bind_result($username, $db_password, $user_id);
        $sqlStatement->fetch();

        // hash the password with the unique salt.
        $password = hash('sha512', $password);
        if ($sqlStatement->num_rows == 1) {
            // If the user exists we check if the account is locked
            // from too many login attempts

            if (checkbrute($user_id, $conn) == true) {
                // Account is locked
                // Send an email to user saying their account is locked
                return false;
            } else {
                // Check if the password in the database matches
                // the password the user submitted.
                if ($db_password == $password) {
                    // Password is correct!
                    // Get the user-agent string of the user.
                    $user_browser = $_SERVER['HTTP_USER_AGENT'];
                    $username = preg_replace("/[^a-zA-Z0-9_\\-]+/",
                        "",
                        $username);
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['username'] = $username;
                    $_SESSION['login_string'] = hash('sha512',
                        $password . $user_browser);
                    // Login successful.
                    echo "Successful login!";
//                    return true;
                } else {
                    echo "failed login!";
                    echo "<p>Error! Invalid username or password.</p>";
                    // Password is not correct
                    // We record this attempt in the database
                    $now = time();
                    $results = $conn->query("SELECT attempts from login_attempts where user_id='$user_id'");
                    $results->fetch_assoc();
                    if($results->num_rows > 0){//already has an entry, update it
                        $conn->query("UPDATE login_attempts SET attempts = attempts + 1, time =$now WHERE user_id='$user_id'");
                    }
                    else{
                        $conn->query("INSERT INTO login_attempts(user_id, time) VALUES ('$user_id', '$now')");
                    }
                    return false;
                }
            }
        }
        else {
            // No user exists.
            echo "<p>Error! Invalid username or password.</p>";
            return false;
        }
    }
    else{
        echo "<p>Prepared statement returned error</p>";
    }
    $conn->close();
    //echo $_SESSION['username'];
    header("LOCATION: user.php");
    return true;
}

function logout(){
    sec_session_start();

    // Unset all session values
    $_SESSION = array();

    // get session parameters
    $params = session_get_cookie_params();

    // Delete the actual cookie.
    setcookie(session_name(),
        '', time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]);

    // Destroy session
    session_destroy();
    header('Location: index.php');
}

function register($first_name, $last_name, $username, $phone, $email, $password){
    $first_name = preg_replace("/[^a-zA-Z]+/", "", $first_name);
    $last_name = preg_replace("/[^a-zA-Z]+/", "", $last_name);
	$username = preg_replace("/[^a-zA-Z0-9]+/", "", $username);
	$phone = preg_replace("/[^0-9-]+/", "", $phone);
	$email = preg_replace("/[^a-zA-Z0-9@.]+/", "", $email);
    $getVariable = rand_string(50);
    $ip = $_SERVER['REMOTE_ADDR'];
    $this_ip = $_SERVER['SERVER_ADDR'];

	$conn = connectDB();
	$sql = "INSERT INTO registering_users (first_name, last_name, email, phone_number, password, username, get_variable, ip_address)
                      VALUES ('$first_name', '$last_name', '$email', '$phone', '$password', '$username', '$getVariable', '$ip')";
    //$conn->query($sql);
	if ($conn->query($sql) === TRUE) {
		echo "Thank you for registering. An email will be appearing in your inbox shortly.";
		} else {
        echo "Error: User was not registered";
//		echo "Error: " . $sql . "<br>" . $conn->error;
	}

    $message = "Your account has been approved! Please verify your email to log into the inventory system! <a href='http://$this_ip/register?username=$username&random=$getVariable'> click here.</a> ";
    $message = wordwrap($message, 70, "\r\n");
    $subject = "Two Factor Authentication";
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    mail($email, $subject, $message, $headers);

    $conn->close();
	sec_session_start();
	header('Location: index.php');
}

function inventory_form($table_name){
	switch ($table_name) {
		case "book":
			echo "";
			break;
		case "clothing":
			break;
		case "computer":
			break;
		case "first_aid":
			break;
		case "food":
			break;
		case "food_processor":
			break;
		case "furniture":
			break;
		case "garden_tool":
			break;
		case "material":
			break;
		case "movie":
			break;
		case "music":
			break;
		case "painting":
			break;
		case "utensil":
			break;
		case "workshop_tool":
			break;


		default:
			break;
	}
}

function list_user_inventory(){
    $conn = connectDB();
    $user_id=$_SESSION['user_id'];
    echo "<table>";
    $sql = "select description from classes";
    $sqlStatement = $conn->query($sql);
    while($ClassesRow = $sqlStatement->fetch_assoc()) {
        $description = $ClassesRow['description'];
        $sql = "select A.quantity, B.title from in_stock A Inner Join $description B on A.item_id=B.item_id where A.user_id=$user_id";
        $titleFetcher = $conn->query($sql);
        //if($titleFetcher->num_rows > 0){
            echo "<tr><th>$description</th></tr>";
        //}
        while($titles = $titleFetcher->fetch_assoc()) {
            // get variables from result.
            $name = $titles['title'];
            $quantity = $titles['quantity'];
            if ($titleFetcher->num_rows > 0) {
                $line = "<tr><td>$name</td><td>$quantity</td></tr>";
                echo $line;
            }
            echo "<form method=\"post\" action=\"user.php\">
				<tr><td><input type= \"submit\" value=\"Add\"/></td></tr>
				</form>";
        }
    }
    echo "</table>";
}

function rand_string( $length ) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#^()_-{}[]|";

    $str = "";
    $size = strlen( $chars );
    for( $i = 0; $i < $length; $i++ ) {
        $str .= $chars[ rand( 0, $size - 1 ) ];
    }
    return $str;
}

?>
