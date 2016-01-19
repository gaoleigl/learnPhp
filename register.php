<?php
$user = $_GET['registerusername'];

$host = "localhost";
$username = "root";
$passwd = "****";
$db = "qlcoderTest";

$conn = mysql_connect($host, $username, $passwd);
if(!$conn){
	echo "db connect exception!";
	exit;
}

mysql_select_db($db);
mysql_query("SET NAMES UTF8");

$sql = "select * from user";
$result = mysql_query($sql);

$exist = FALSE;
while($row = mysql_fetch_assoc($result)) {
	if($user == $row['name']) {
	$exist = TRUE;
	}
}

if($exist == TRUE) {
	echo "already used";
}
else {
	$insertSql = "insert into user(name) values ('".$user."')";
	mysql_query($insertSql);
	echo "register success";
}
mysql_close($conn);
?>
