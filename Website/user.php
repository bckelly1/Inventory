<?php
$title="User Home";
include "header.php";
if(ISSET($_SESSION['username'])){

    echo "<p>This page lists the user's personal inventory.</p>";

    list_user_inventory();
}
else{
    echo "<p>ERROR: Unauthorized Access</p>";
    header('LOCATION: index.php');
}

?>
    </body>
</html>