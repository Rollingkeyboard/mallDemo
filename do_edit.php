<?php 
include_once './lib/fun.php';

if(!checkLogin()){
	msg(2,'请登录','index.php');
}
if(!empty($_POST['name'])){
	$con=mysqlinit('localhost','root','***','mall');
    $name=mysqli_real_escape_string($con,trim($_POST['name']));

    $goodId=intval($_POST['id']);
    if(!$goodId){
    	msg(2,'参数非法');
    }
    $sql="SELECT * FROM goods WHERE id ={$goodId} LIMIT 1";
    $obj=mysqli_query($con,$sql);
    $res=mysqli_fetch_all($obj,MYSQLI_ASSOC);
    if(!$res){
    	msg(2,'商品信息不存在','index.php');
    }

    $price=intval($_POST['price']);
    $des=mysqli_real_escape_string($con,trim($_POST['des']));
    $content=mysqli_real_escape_string($con,trim($_POST['content']));
    $userId=$user['id'];
    $nameLength=mb_strlen($name,'utf-8');
    if($nameLength<=0||$nameLength>30){
        msg(2,'画品名称应在1-30字符内');
    }
    $desLength=mb_strlen($des,'utf-8');
    if($desLength<=0||$desLength>100){
        msg(2,'画品简介应在1-100字符之内');
    }
    if($price<=0||$price>999999999){
        msg(2,'注意画品价格');
    }
    if(empty($content)){
        msg(2,'画品详情不能为空');
    }
    $pic=imgUpload($_FILES['file']);
    if($pic===-1){
        msg(2,'请上传合法图像');
    }elseif($pic===-2){
        msg(2,'请上传png,gif,jpg类型文件');
    }elseif ($pic===-3) {
        msg(2,'服务器繁忙,请稍后再试');
    }

    $update=array(
    	'name'=>$name,
    	'price'=>$price,
    	'des'=>$des,
        'pic'=>$pic,
    	'content'=>$content,
    	'update_time'=>$_SERVER['REQUEST_TIME']
    );


    //校验商品图片 当用户没有选择商品
    if($_FILES['files']['size']>0){
    	$pic=imgUpload($_FILES['file']);
    	$update['pic']=$pic;
    }
	if(empty($update)){
    	msg(2,'操作成功','edit.php?id='.$goodId);
    }

    //只更新被更改的信息
    foreach ($update as $key => $value) {
    	if($res[0][$key]==$value){
    		unset($update[$key]); 
    	}
    }
    //更新sql
    $updateSql='';
    foreach ($update as $key => $value) {
    	$updateSql.="{$key}='{$value}',";  
    }

    //去除多余,
    $updateSql=rtrim($updateSql,',');
    var_dump($updateSql);
    unset($sql,$obj,$res);
    $sql="UPDATE goods SET {$updateSql} WHERE id={$goodId}";
    $obj=mysqli_query($con,$sql);
    
    //当更新成功
    if($obj){
    	// mysql_affected_rows(); 影响行数
    	msg(1,'操作成功','index.php');
    }else{
    	msg(2,'操作失败','edit.php?id='.$goodId);
    }

    

}else{
	msg(2,'访问非法','index.php');
}




 ?>