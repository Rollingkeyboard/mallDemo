<?php 
echo getcwd();
include_once './lib/fun.php';
$login=checkLogin();
if($login){
    $user=$_SESSION['user'];
}

//查询商品

//检查page参数
$page=isset($_GET['page'])?intval($_GET['page']):1;
$page=max(1,$page);//防止负数
//每页显示条数
$pageSize=3;

$offset=($page-1)*$pageSize;

$con=mysqlinit('localhost','root','***','mall');

$sql="SELECT COUNT(id) AS total FROM goods";
$obj=mysqli_query($con,$sql);
$res=mysqli_fetch_all($obj,MYSQLI_ASSOC);

$total=isset($res[0]['total'])?$res[0]['total']:0;

unset($sql,$res,$obj);

$sql="SELECT id,name,pic,des FROM goods ORDER BY id ASC,view DESC LIMIT {$offset},{$pageSize}";
$obj=mysqli_query($con,$sql);
$res=mysqli_fetch_all($obj,MYSQLI_ASSOC);
$goods=$res;
// echo '<pre>';
// var_dump($goods);
// echo pageUrl($page+1);

$pages=pages($total,$page,$pageSize,7);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>M-GALLARY|首页</title>
    <link rel="stylesheet" type="text/css" href="./static/css/common.css"/>
    <link rel="stylesheet" type="text/css" href="./static/css/index.css"/>
</head>
<body>
<div class="header">
    <div class="logo f1">
        <img src="./static/image/logo.png">
    </div>
    <div class="auth fr">
        <ul>
        <?php if($login): ?>
            <li><span>管理员:<?php echo $user['username']; ?></span></li>
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
    <div class="banner">
        <img class="banner-img" src="./static/image/welcome.png" width="732px" height="372" alt="图片描述">
    </div>
    <div class="img-content">
        <ul>
            <?php foreach ($goods as $key => $value):?>
            <li>
                <img class="img-li-fix" src="<?php echo $value['pic']; ?>" alt="<?php echo $value['name']; ?>">
                <div class="info">
                    <a href="detail.php?id=<?php echo $value['id']; ?>"><h3 class="img_title"><?php echo $value['name']; ?></h3></a>
                    <p>
                        <?php echo $value['des']; ?> 
                    </p>
                    <div class="btn">
                        <a href="edit.php?id=<?php echo $value['id']; ?>" class="edit">编辑</a>
                        <a href="delete.php?id=<?php echo $value['id']; ?>" class="del">删除</a>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
    <?php echo $pages; ?>
</div>


</body>
<script src="./static/js/jquery-1.10.2.min.js"></script>
<script>
    $(function () {
        $('.del').on('click',function () {
            if(confirm('确认删除该画品吗?'))
            {
               window.location = $(this).attr('href');
            }
            return false;
        })
    })
</script>
</html>
