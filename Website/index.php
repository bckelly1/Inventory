<?php
$title="Home";
include "header.php";

?>
        <p>This web page is a web interface for the inventory project.</p>
<?php
$conn = connectDB();
$sql = "select distinct super_class from classes order by super_class";
$result = $conn->query($sql);
echo "<table>";
    echo "<tr><th>Super Class</th><th>Sub-Classes</th></tr>";
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {//get all super classes and go through one by one
        $super_class = $row["super_class"];
        $line = "<tr id=\"$super_class\"><td class=\"super_class\">$super_class</td>";
        $sql = "select description from classes where super_class like '$super_class'";
        $classes = $conn->query($sql);
        if ($classes->num_rows > 0) {
            while ($classrow = $classes->fetch_assoc()) {
                $class = $classrow["description"];
                $line=$line."<td>".$class."</td>";
            }
        }
        else{
            echo "0 results";
        }
        $line=$line."</tr>";
        echo $line;
    }
}
echo "</table>";

?>
    </body>
</html>