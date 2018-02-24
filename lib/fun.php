<?php 

session_start();
/**
 * 数据库连接初始化
 * @Author   Ryziii
 * @DateTime 2018-02-17
 */
function mysqlinit($host,$username,$password,$dbname){

    //数据库操作
    $con=mysqli_connect($host,$username,$password,$dbname);
    if(!$con){
        return false;
    }
    mysqli_query($con,'set names utf8');
    return $con;
}

/**
 * 密码加密
 * @Author   Ryziii
 * @DateTime 2018-02-17
 * @param    [type]     $password [description]
 * @return   [type]               [description]
 */
function createPassword($password){
	if(!$password){
		return false;
	}
	return md5(md5($password).'mall');
}

/**
 * 消息跳转
 * @Author   Ryziii
 * @DateTime 2018-02-17
 */
function msg($type,$msg=null,$url=null){
    $tourl="location:msg.php?type={$type}";
    $tourl.=$msg?"&msg={$msg}":'';
    $tourl.=$url?"&url={$url}":'';
    header($tourl);
    exit();

}

/**
 * 上床图片
 * @Author   Ryziii
 * @DateTime 2018-02-21
 * @param    [type]     $file [description]
 * @return   imgUrl|-1|-2|-3         文件上传不合法-1|图片类型不合法-2|上传失败-3
 */
function imgUpload($file){
    //检查上传文件是否合法
    if(!is_uploaded_file($file['tmp_name'])){
        return -1;
    }

    //图像类型验证
    $type=$file['type'];
    if(!in_array($type,array("image/png","image/gif","image/jpeg"))) {
        return -2;
    }

    //上传目录
    $uploadPath='./static/file/';
    //上传目录url
    $uploadUrl='/static/file/';
    //上传文件夹
    $fileDir=date('Y/m/d/',$_SERVER['REQUEST_TIME']);
    if(!is_dir($uploadPath.$fileDir)){
        mkdir($uploadPath.$fileDir,0755,true);//递归创建子目录
    }

    $ext=strtolower(pathinfo($file['name'],PATHINFO_EXTENSION));
    //唯一性
    $img=uniqid().mt_rand(1000,9999).'.'.$ext;
    $imgPath=$uploadPath.$fileDir.$img;//物理地址
    
    $imgUrl='http://localhost/studyPHP/mall'.$uploadUrl.$fileDir.$img;//url地址

    //操作失败查看上传目录权限
    if(!move_uploaded_file($file['tmp_name'], $imgPath)){
        return -3;
    }else{
        return $imgUrl;
    }
}

function checkLogin(){
    session_start();
    if(!isset($_SESSION['user'])||empty($_SESSION['user'])){
        return false;
    }
    return true;
}

function getUrl(){
    $url='';
    $url.=$_SERVER['SERVER_PORT']==443?'https://':'http://';
    $url.=$_SERVER['HTTP_HOST'];
    $url.=$_SERVER['REQUEST_URI'];
    return $url;
}

function pageUrl($page,$url=''){
    $url=empty($url)?getUrl():$url;
    $pos=strpos($url,'?');//查询url中的？
    if($pos===false){
        $url.='?page='.$page;
    }else{
        $queryString=substr($url, $pos+1);
        parse_str($queryString,$queryArr);
        if(isset($queryArr)){
            unset($queryArr['page']);
        }
        $queryArr['page']=$page;
        $queryStr=http_build_query($queryArr);
        $url=substr($url,0,$pos).'?'.$queryStr;
    }
    return $url;

}

function pages($total,$currentPage,$pageSize,$show=6){
    $pageStr='';
    //仅当总数达于每页显示条数 才进行分页处理
    if($total>$pageSize){
        $totalPage=@ceil($total/$pageSize);//向上取整
        $currentPage=$currentPage>$totalPage?$totalPage:$currentPage;

        $from=max(1,$currentPage-intval($show/2));
        $to=$from+$show-1;
        if($to>$totalPage){
            $to=$totalPage;
            $from=max(1,$to-$show+1);
        }
        

        $pageStr.='<div class="page-nav">';
        $pageStr.='<ul>';

        //仅当 当前页大于1的时候 存在首页和上一页按钮
        if($currentPage>1){
            $pageStr.="<li><a href='".pageUrl(1)."'>首页</a></li>";
            $pageStr.="<li><a href='".pageUrl($currentPage-1)."'>上一页</a></li>";
        }
        if($from>1){
            $pageStr.='<li>...</li>';
        }

        for($i=$from;$i<=$to;$i++){
            if($i!=$currentPage){
                $pageStr.="<li><a href='".pageurl($i)."'>{$i}</a></li>";
            }else{
                $pageStr.="<li><span class='curr-page'>{$i}</span></li>";
            }
        }
        if($to<$totalPage){
            $pageStr.='<li>...</li>';
        }
        if($currentPage<$totalPage){
            $pageStr.="<li><a href='".pageUrl($totalPage)."'>尾页</a></li>";
            $pageStr.="<li><a href='".pageUrl($currentPage+1)."'>下一页</a></li>";
        }

        $pageStr.='</ul>';
        $pageStr.='</div>';
    }
    return $pageStr;

}

