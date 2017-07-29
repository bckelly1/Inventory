<?php
/**
 * Created by IntelliJ IDEA.
 * User: Brian
 * Date: 7/11/2015
 * Time: 8:18 AM
 */
$title="Register";
include "header.php";

if(isset($_GET['user']) && isset($_GET['random'])){
    $conn=connectDB();
    $user=$_GET['user'];
    $GETkey=$_GET['random'];
    $rIP = $_SERVER['REMOTE_ADDR'];

    $query = "SELECT key FROM registering_users WHERE username='$user';";
    $oKey=$conn->query($query);
    $key = '';
    foreach($oKey as $k){
        $key = $k[0];
    }

    $query = "SELECT ip FROM registering_users WHERE username='$user';";
    $oIP=$db->query($query);
    $ip = '';
    foreach($oIP as $i){
        $ip = $i[0];
    }

    if(($key == $_GET['random']) && ($ip == $_SERVER['REMOTE_ADDR'])){
        $query = "UPDATE registering_users SET verified=1 WHERE username='$user';";
        $db->exec($query);
        echo "Your account has been verified.";
    }
    else{
        echo "The key you provided or IP you are coming from is incorrect. </br>";
        echo "key: $key vs:$GETkey</br>";
        echo "IP: $ip vs: $rIP</br>";
    }
}


if((isset($_POST['first_name'])) && isset( $_POST['last_name'])&& isset( $_POST['username'])&& isset( $_POST['email'])&& isset( $_POST['password'])){
	$conn=connectDB();
	$sql="select username from users where username='".$_POST['username']."'";
	$sqlStatement = $conn->query($sql);
	if($sqlStatement->num_rows > 0){
		echo "<p>Error! That username is already taken</p>";
	}
	else{
		if(strlen($_POST['password']) <= 8){
			echo '<p>ERROR: password must be more than 8 characters.</p>';
		}
		else {
			$first_name = $_POST['first_name'];
			$last_name = $_POST['last_name'];
			$username = $_POST['username'];
			$email = $_POST['email'];
			$password = hash('sha512', $_POST['password']);
			if(isset( $_POST['phone'])){
				$phone = $_POST['phone'];
				register($first_name, $last_name, $username, $phone, $email, $password);
			}
			else{
				register($first_name, $last_name, $username, "", $email, $password);
			}
		}
	}

}
else {
    ?>
    <form method="post" action="register.php">
        <table>
            <tr>
                <td>First Name:</td>
                <td><label for="first_name"></label><input type="text" id="first_name" name="first_name" size="20" required/></td>
            </tr>
            <tr>
                <td>Last Name:</td>
                <td><label for="last_name"></label><input type="text" id="last_name" name="last_name" size="20" required/></td>
            </tr>
            <tr>
                <td>Username:</td>
                <td><label for="username"></label><input type="text" id="username" name="username" size="20" required/>
                </td>
            </tr>
            <tr>
                <td>Phone Number:</td>
                <td><label for="phone"></label><input type="text" id="phone" name="phone" size="20"/></td>
            </tr>
            <tr>
                <td>Email:</td>
                <td><label for="email"></label><input type="email" id="email" name="email" size="20"/></td>
            </tr>
            <tr>
                <td>Password:</td>
                <td><label for="password"></label><input type="password" id="password" name="password" size="20" required/></td>
            </tr>
            <tr>
                <td><input type="submit" value="Login"/></td>
            </tr>
        </table>
    </form>
    <?php
}
?>