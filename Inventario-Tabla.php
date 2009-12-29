<?php
include ("conexion.php");
$hola="hola";
echo '<table width="200" border="0">';
				echo "<tr>";
				echo "<td>".$hola."</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td>".$_GET["q"]."</td>";
				echo "</tr>";
echo '</table>';
?>