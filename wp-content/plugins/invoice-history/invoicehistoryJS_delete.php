<?php  
	//$connect = mysqli_connect("localhost", "root", "", "testing");
	$connect = mysqli_connect("localhost","plankeye_mudhut","Proverbs35!","plankeye_newest");
	$sql = "DELETE FROM invoice WHERE invoice_id = '".$_POST["id"]."'";  
	if(mysqli_query($connect, $sql))  
	{  
		echo 'Data Deleted';  
		//echo $sql;  
	}  
 ?>