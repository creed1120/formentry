<?php
$conn = mysqli_connect('localhost', 'livevertex_pizz', 'X8#K3P$w3}Sg', 'livevertex_pizzas');

if(!$conn) {
	echo "Database Error: " . mysqli_connect_error();
}
?>