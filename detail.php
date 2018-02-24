<?php 
date_default_timezone_set("PRC");
include_once './lib/fun.php';
if(!checkLogin()){
    msg(2,'请登录','login.php');
}

$user=$_SESSION['user'];

$goodsId=isset($_GET['id'])&&is_numeric($_GET['id'])?intval($_GET['id']):'';
if(!$goodsId){
    msg(2,'参数非法','index.php');
}

$con=mysqlinit('localhost','root','***','mall');
$sql="SELECT * FROM goods WHERE id= {$goodsId}";
$obj=mysqli_query($con,$sql);
$res=mysqli_fetch_all($obj,MYSQLI_ASSOC);
if(!$res){
    msg(2,'商品不存在','index.php');

}

$res[0]['view']++;
unset($sql,$obj);
$updateSql="view={$res[0]['view']}";
$sql="UPDATE goods SET {$updateSql} WHERE id={$goodsId}";
$obj=mysqli_query($con,$sql);

unset($sql,$obj);
$sql="SELECT * FROM user WHERE id={$res[0]['user_id']}";
$obj=mysqli_query($con,$sql);
$pgt=mysqli_fetch_all($obj,MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>M-GALLARY|<?php echo $res[0]['name']; ?></title>
    <link rel="stylesheet" type="text/css" href="./static/css/common.css" />
    <link rel="stylesheet" type="text/css" href="./static/css/detail.css" />
</head>
<body class="bgf8">
<div class="header">
    <div class="logo f1">
        <img src="./static/image/logo.png">
    </div>
    <div class="auth fr">
        <ul>
        <?php if($user): ?>
            <li><a href="#"><?php echo $pgt[0]['username']; ?></a></li>
            <li><a href="publish.php">发布</a></li>
            <li><a href="login_out.php">退出</a></li>
        <?php else: ?>
            <li><a href="login.php">登录</a></li>
            <li><a href="register.php">注册</a></li>
        <?php endif; ?>
        </ul>
    </div>
</div>
<div class="content">
    <div class="section" style="margin-top:20px;">
        <div class="width1200">
            <div class="fl"><img src="<?php echo $res[0]['pic']; ?>" width="720px" height="432px"/></div>
            <div class="fl sec_intru_bg">
                <dl>
                    <dt><?php echo $res[0]['name']; ?></dt>
                    <dd>
                        <p>发布人：<span><?php echo $_SESSION['user']['username']; ?></span></p>
                        <p>发布时间：<span><?php echo date("Y-m-d H:i:s",$res[0]['create_time']); ?></span></p>
                        <p>修改时间：<span><?php echo date("Y-m-d H:i:s",$res[0]['update_time']); ?></span></p>
                        <p>浏览次数：<span><?php echo $res[0]['view']; ?></span></p>
                    </dd>
                </dl>
                <ul>
                    <li>售价：<br/><span class="price"><?php echo $res[0]['price']; ?></span>元</li>
                    <li class="btn"><a href="javascript:;" class="btn btn-bg-red" style="margin-left:38px;">立即购买</a></li>
                    <li class="btn"><a href="javascript:;" class="btn btn-sm-white" style="margin-left:8px;">收藏</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="secion_words">
        <div class="width1200">
            <div class="secion_wordsCon">
                <?php echo $res[0]['content']; ?>
            </div>
        </div>
    </div>
</div>
</div>
</body>
</html>

