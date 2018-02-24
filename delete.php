<?php 
include_once './lib/fun.php';
if(!checkLogin()){
	msg(2,'请登录','login.php');
}
$goodId=isset($_GET['id'])&&is_numeric($_GET['id'])?intval($_GET['id']):'';

if(!$goodId){
	msg(2,'参数非法','index.php');
}

$con=mysqlinit('localhost','root','***','mall');
$sql="SELECT id FROM goods WHERE id = {$goodId}";
$obj=mysqli_query($con,$sql);
$res=mysqli_fetch_all($obj,MYSQLI_ASSOC);
// var_dump($res);
if(!$res[0]['id']){
	msg(2,'画品不存在','index.php');
}

unset($sql,$obj,$res);
$sql="DELETE FROM goods WHERE id={$goodId} LIMIT 1";
$obj=mysqli_query($con,$sql);
if($obj){
	// mysql_affected_rows()=1
	msg(1,'删除成功','index.php');
}else{
	msg(2,'删除失败','index.php');
}

//注意
//1. 项目中不会真正删除商品 而是更新商品表中的status 1.正常操作 -1.删除操作
//2. 增加商品更新是


