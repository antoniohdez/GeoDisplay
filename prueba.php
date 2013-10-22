<?php
include("driver.php");
if(isset($_GET["id"])){
	$id = $_GET["id"];
	$driver = new dbDriver();
	$driver->getTagsByUser($id);
}
?>