<html>
<head>
	<meta http-equiv="Content-type" content="text/html;charset=utf-8">
</head>
<style>
	body{
		font-size:12px; FONT-FAMILY:verdana; width:100%;
	}
	div.page {
		text-align:center;
	}
	div.content{
		height:300px;
	}
	div.page a{
		border: #aaaadd 1px solid;
		text-decoration:none;
		padding : 2px 5px 2px 5px;
	}
	
	div.page span.current {
		border:#000099 1px solid;background-color:#000099; padding: 4px 6px 4px 6px;margin:2px;color:#fff;
		font-weight:bold;
	}

	div.page span.disable {
		border:#eee 1px solid; padding: 2px 5px 2px 5px;margin:2px; color:#ddd;
	}
	
	div.page form{
		display:inline;
	}
	
</style>
<body>
<?php
 /**1 传入页码，注意这里获取url中参数的方法，**/
 $page = $_GET['p'];

 /**2 根据页码取出数据，利用php处理mysql **/
 $host = "localhost";
 $username = "root";
 $password = "******";
 $db = "test";
 $pageSize = 10;
 $showPage = 5;
 // 链接数据库
 $conn = mysql_connect($host, $username, $password);
 if(!$conn) {
	echo "数据库链接失败";
	exit;
 }

 //选择所用db
 mysql_select_db($db);
 //设置db编码格式

 mysql_query("SET NAMES UTF8");

 //编写sql获取分页数据 limit a,b 从a开始b条
 $sql = "select * from page limit ".($page-1) * $pageSize .", {$pageSize}";
 //把sql传送到db
 $result = mysql_query($sql);
 //result 是个数据源，没法直接显示的
 echo "<div class = 'content'>";
 echo "<table border=1 cellspacing=0 width=30% align='center'>";
 echo "<tr><td>id</td><td>name</td></tr>";
 while($row = mysql_fetch_assoc($result)) {
	echo "<tr>";
	echo "<td>{$row['id']}</td>";
	echo "<td>{$row['name']}</td>";
 	//echo $row['id'].'---'.$row['name'].'<br>';
	echo "</tr>";
 }
 echo "</table> </div>";
 //释放结果，关闭链接
 mysql_free_result($result);
 $total_sql = "select count(*) from page";
 $total_result = mysql_fetch_array(mysql_query($total_sql));
 $total = $total_result[0];
 //计算页数
 $total_pages = ceil($total / $pageSize);
 mysql_close($conn);

 /**3 显示数据，包括数据和分页条**/
 $page_banner = "";
 $page_banner .= "<div class='page'>";
 // 计算偏移量
 $pageoffset = ($showPage - 1) / 2;
 $start = 1;
 $end = $total_pages;
 
 if($page > 1) {
 	$page_banner .= "<a href='".$_SERVER['PHP_SELF']."?p=1'>首页</a>";
 	$page_banner .= "<a href='".$_SERVER['PHP_SELF']."?p=".($page-1)."'>上一页</a>";
 }
 else {
	$page_banner .= "<span class='disable'>首页</a></span>";
	$page_banner .= "<span class='disable'>上一页</a></span>";	
 }

 //页码前后的省略号，只有总页数被showPages 大的时候才有可能出现
 if($total_pages > $showPage) {
 	if($page > $pageoffset + 1) {
		$page_banner .= "...";
	}
	//计算start 和 end的位置	
	if($page > $pageoffset) {
		$start = $page - $pageoffset;
		$end = $total_pages > $page + $pageoffset ? $page + $pageoffset : $total_pages;
	} 
	else {
		$start = 1;
		$end = $total_pages > $showPage ? $showPage:$total_pages;
	}
	
	if($page + $pageoffset > $total_pages) {
		$start = $start - ($page + $pageoffset - $end);
	}
 }
 for($i = $start; $i <=$end; $i++) {
	if($page == $i) {
		$page_banner .= "<span class = 'current'>{$i}</span>";
	}
	else {
 		$page_banner .= "<a href='".$_SERVER['PHP_SELF']."?p=".$i."'>{$i}</a>";
	}
 }

 //末尾的省略号
 if($total_pages > $showPage && $page + $pageoffset < $total_pages){
	$page_banner .= "...";
 } 

 if($page < $total_pages) {
 	$page_banner .= "<a href='".$_SERVER['PHP_SELF']."?p=".($page+1)."'>下一页</a>";
 	$page_banner .= "<a href='".$_SERVER['PHP_SELF']."?p=".($total_pages)."'>尾页</a>";
 }
 else {
	$page_banner .= "<span class='disable'>下一页</a></span>";
	$page_banner .= "<span class='disable'>尾页</a></span>";	
 }

 $page_banner .= "共{$total_pages}页, ";
 $page_banner .= "<form action='mypage.php' method='get'>";
 $page_banner .= "到第<input type='text' size='2' name='p'>页";
 $page_banner .= "<input type='submit' value = '确定'>";
 $page_banner .= "</form> </div>";
 echo $page_banner;
?>
</body>
</html>
