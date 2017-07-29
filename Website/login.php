<?php
$title="Login";
include "header.php";
//sec_session_start();

/**
 * Created by IntelliJ IDEA.
 * User: Brian
 * Date: 7/5/2015
 * Time: 7:19 PM
 */
if((isset($_POST['user'])) && isset( $_POST['password'])){
    login($_POST['user'], $_POST['password']);
}
?>

<form method="post" action="login.php">
    <table>
        <tr><td>Username:</td><td><label for="user"><input type="text" id="user" name="user" size="20"/></label></td></tr>
        <tr><td>Password:</td><td><label for="password"></label><input type= "password" id="password" name="password" size="20"/></td></tr>
        <tr><td><input type= "submit" value="Login"/></td></tr>
    </table>
</form>
