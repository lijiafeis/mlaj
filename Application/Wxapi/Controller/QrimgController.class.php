<?php
namespace Wxapi\Controller;
use Think\Controller;

class QrimgController extends Controller{
	function index($user_id,$nickname){
		
		if($user_id == 0){
			$erweima_img='Public/2.jpg';
			$head_img="Public/head.jpg";
		}else{
			$erweima_img='Public/qrimg/'.$user_id.'.jpg';
			$head_img="Public/head_pic/".$user_id.".jpg";
		}
		//相关参数
		$info = M('qrset')->select();
		$head_height=$head_width=$info[0]['head_size'];
		$erweima_height=$erweima_width=$info[0]['qr_size'];
		$dst_path=$info[0]['pic_url'];
		$str=$nickname;
		$font_size=$info[0]['font_size'];
		$fnt_x=$info[0]['font_x'];
		$fnt_y=$info[0]['font_y'];
		//载入字体zt.ttf 
		$fnt = "Public/msyh.ttf"; 
		//头像缩小
		$src1=$this->img_suo($head_img,$head_width,$head_height);
		//二维码缩小
		$src=$this->img_suo($erweima_img,$erweima_width,$erweima_height);
		//创建图片的实例
		$dst = imagecreatefromstring(file_get_contents($dst_path));
		//$src = imagecreatefromstring(file_get_contents($src_path));
		//获取水印图片的宽高
		//将水印图片复制到目标图片上，最后个参数50是设置透明度，这里实现半透明效果
		imagecopymerge($dst, $src1, $info[0]['head_x'], $info[0]['head_y'], 0, 0, $head_width, $head_height, 100);
		imagecopymerge($dst, $src, $info[0]['qr_x'], $info[0]['qr_y'], 0, 0, $erweima_width, $erweima_height, 100);
		//如果水印图片本身带透明色，则使用imagecopy方法
		//imagecopy($dst, $src, 10, 10, 0, 0, $src_w, $src_h);
		//创建颜色，用于文字字体的白和阴影的黑 
		$white=imagecolorallocate($dst,222,229,207); 
		$black=imagecolorallocate($dst,50,50,50);
		imagettftext($dst,$font_size, 0, $fnt_x+1, $fnt_y+1, $black, $fnt, $str); 
		imagettftext($dst,$font_size, 0, $fnt_x, $fnt_y, $white, $fnt, $str); 
		if(!is_dir('Public/qr_path/')){
			mkdir('Public/qr_path/');
		}
		ImageJPEG($dst,'Public/qr_path/'.$user_id.'.jpg'); // 保存图片,但不显示 
		//销毁对象 
		ImageDestroy($dst);
		return 'Public/qr_path/'.$user_id.'.jpg';
	}
	
	function index1($user_id,$nickname,$jibie,$tel){
		file_put_contents('c.txt',$user_id . $nickname . $jibie . $tel);
        if($user_id == 0){
            $erweima_img='Public/2.jpg';
            $head_img="Public/head.jpg";
        }else{
            $erweima_img='Public/webqr/'.$user_id.'.jpg';
            // $head_img="Public/head_pic/".$user_id.".jpg";
            $head_img='Public/webqr/'.$user_id.'.jpg';
        }
		file_put_contents('c.txt',$erweima_img);
		//$user_id = $tel;
        //相关参数
        $info = M('qrsq')->select();
        $head_height=$head_width=$info[0]['head_size'];
        $erweima_height=$erweima_width=$info[0]['qr_size'];
        $dst_path=substr($info[0]['pic_url'],1);
        $str=$nickname;
        //昵称
        $font_size=$info[0]['nicheng_size'];
        $nicheng_x=$info[0]['nicheng_x'];
        $nicheng_y=$info[0]['nicheng_y'];

        //id
        $id_size = $info[0]['id_size'];
        $id_x = $info[0]['id_x'];
        $id_y = $info[0]['id_y'];

        //级别
        $jibie_size = $info[0]['jibie_size'];
        $jibie_x = $info[0]['jibie_x'];
        $jibie_y = $info[0]['jibie_y'];
		
		
		
        //载入字体zt.ttf
        $fnt = "Public/msyh.ttf";
        //头像缩小
        $src1=$this->img_suo($head_img,$head_width,$head_height);
        //二维码缩小
        $src=$this->img_suo($erweima_img,$erweima_width,$erweima_height);
        //创建图片的实例
         $dst = imagecreatefromstring(file_get_contents($dst_path));
        //$dst = imagecreatefromstring(file_get_contents($dst_path));
        //$src = imagecreatefromstring(file_get_contents($src_path));
        //获取水印图片的宽高
        //将水印图片复制到目标图片上，最后个参数50是设置透明度，这里实现半透明效果
        imagecopymerge($dst, $src1, $info[0]['head_x'], $info[0]['head_y'], 0, 0, $head_width, $head_height, 100);
        //imagecopymerge($dst, $src, $info[0]['qr_x'], $info[0]['qr_y'], 0, 0, $erweima_width, $erweima_height, 100);
        //如果水印图片本身带透明色，则使用imagecopy方法
        imagecopy($dst, $src, 10, 10, 0, 0, $src_w, $src_h);
        //创建颜色，用于文字字体的白和阴影的黑
        $black=imagecolorallocate($dst,222,229,207);
        $white=imagecolorallocate($dst,50,50,50);
        imagettftext($dst,$font_size, 0, $nicheng_x+1, $nicheng_y+1, $black, $fnt, $str);
        imagettftext($dst,$font_size, 0, $nicheng_x, $nicheng_y, $white, $fnt, $str);

        imagettftext($dst,$id_size, 0, $id_x+1, $id_y+1, $black, $fnt, $tel);
        imagettftext($dst,$id_size, 0, $id_x, $id_y, $white, $fnt, $tel);

        imagettftext($dst,$jibie_size, 0, $jibie_x+1, $jibie_y+1, $black, $fnt, $jibie);
        imagettftext($dst,$jibie_size, 0, $jibie_x, $jibie_y, $white, $fnt, $jibie);
		
		//imagettftext($dst,$shijian_size, 0, $shijian_x+1, $shijian_y+1, $black, $fnt, $time);
        imagettftext($dst,$shijian_size, 0, $shijian_x, $shijian_y, $white, $fnt, $time);

        if(!is_dir('Public/qr_shouquan/')){
            mkdir('Public/qr_shouquan/');
        }
		if(file_exists('Public/qr_shouquan/'.$user_id.'.jpg')){
			unlink('Public/qr_shouquan/'.$user_id.'.jpg');
		}
        ImageJPEG($dst,'Public/qr_shouquan/'.$user_id.'.jpg'); // 保存图片,但不显示
        //销毁对象
        ImageDestroy($dst);
        return 'Public/qr_shouquan/'.$user_id.'.jpg';
    }
	
	function setShouQuan($user_id,$name,$wxname,$type){
		  // $type = $type . '代理';
//        if($user_id == 0){
//            $erweima_img='Public/2.jpg';
//            $head_img="Public/head.jpg";
//        }else{
//            $erweima_img='Public/webqr/'.$user_id.'.jpg';
//            // $head_img="Public/head_pic/".$user_id.".jpg";
//            $head_img='Public/webqr/'.$user_id.'.jpg';
//        }

        // //创建一个空白的画布
        // $canvas = imagecreatetruecolor(500,500);
        // //创建一个图片
        // $image = imagecreatefromjpeg('Uploads/shouquan.png');
        // //使用函数获取图片的信息，里面包括了图片的宽高。
        // $image_info = getimagesize('Uploads/shouquan.png');
        // //使用imagecopy()方法进行拷贝
        // imagecopy($canvas, $image, 0, 0, 0, 0, $image_info[0], $image_info[1]);
        // //绘制水印的
        // $paint = imagecolorallocate($canvas, 255, 0, 0);
        // //使用imagestring()函数
        // $str = 'lijiafei';
        // //这个函数第二个参数是设置字体的，只能是1-5，
        // imagestring($canvas, 5, 100, 300, $str, $paint);

        // //使用imagettftext()函数
        // $text = '这是一张中文的水印';
        // imagettftext($canvas, 30, 0, 100, 200, $paint, 'STXINGKA.TTF', $text);

        // header("content-type:image/png");
        // //以png的形式输出到浏览器上
        // imagepng($canvas);

        // //销毁图形，释放内存
        // imagedestroy($canvas);
//
       $dst_path = "Uploads/shouquan/shouquan.png";
       $str=$name;
       //昵称
       $name_size=30;
       $name_x= 445;
       $name_y= 740;

       //wx_name
       $wxname_size = 30;
       $wxname_x = 770;
       $wxname_y = 740;

       //级别
       $type_size = 30;
       $type_x = 848;
       $type_y = 818;
		
		//编号
		$bianhao = $user_id + 1000;
		$bianhao_size = 24;
		$bianhao_x = 925;
		$bianhao_y = 1564;
		
		//时间1
		$shijian = M('user') -> getFieldById($user_id,'time');
		$time = strtotime($shijian);
		$shijian1 = date('Y-m-d',$time);
		$shijian1_size = 24;
		$shijian1_x = 584 ;
		$shijian1_y = 1275;
		
		//时间1
		$shijian2 = date('Y-m-d',$time + 31536000);
		$shijian2_size = 24;
		$shijian2_x = 844;
		$shijian2_y = 1275;

       //载入字体zt.ttf
       $fnt = "Public/msyh.ttf";
       //头像缩小
       //$src1=$this->img_suo($head_img,$head_width,$head_height);
       //二维码缩小
       //$src=$this->img_suo($erweima_img,$erweima_width,$erweima_height);
       //创建图片的实例
       $dst = imagecreatefromstring(file_get_contents($dst_path));
       //$src = imagecreatefromstring(file_get_contents($src_path));
       //获取水印图片的宽高
       //将水印图片复制到目标图片上，最后个参数50是设置透明度，这里实现半透明效果
       //imagecopymerge($dst, $src1, $info[0]['head_x'], $info[0]['head_y'], 0, 0, $head_width, $head_height, 100);
//        imagecopymerge($dst, $src, $info[0]['qr_x'], $info[0]['qr_y'], 0, 0, $erweima_width, $erweima_height, 100);
       //如果水印图片本身带透明色，则使用imagecopy方法
       //imagecopy($dst, $src, 10, 10, 0, 0, $src_w, $src_h);
       //创建颜色，用于文字字体的白和阴影的黑
       $black=imagecolorallocate($dst,222,229,207);
       $white=imagecolorallocate($dst,50,50,50);
       imagettftext($dst,$name_size, 0, $name_x+1, $name_y+1, $black, $fnt, $str);
       imagettftext($dst,$name_size, 0, $name_x, $name_y, $white, $fnt, $str);

       imagettftext($dst,$wxname_size, 0, $wxname_x+1, $wxname_y+1, $black, $fnt, $wxname);
       imagettftext($dst,$wxname_size, 0, $wxname_x, $wxname_y, $white, $fnt, $wxname);

       imagettftext($dst,$type_size, 0, $type_x+1, $type_y+1, $black, $fnt, $type);
       imagettftext($dst,$type_size, 0, $type_x, $type_y, $white, $fnt, $type);

	   imagettftext($dst,$bianhao_size, 0, $bianhao_x+1, $bianhao_y+1, $black, $fnt, $bianhao);
       imagettftext($dst,$bianhao_size, 0, $bianhao_x, $bianhao_y, $white, $fnt, $bianhao);
	   
	    imagettftext($dst,$shijian1_size, 0, $shijian1_x+1, $shijian1_y+1, $black, $fnt, $shijian1);
       imagettftext($dst,$shijian1_size, 0, $shijian1_x, $shijian1_y, $white, $fnt, $shijian1);
	   
	    imagettftext($dst,$shijian2_size, 0, $shijian2_x+1, $shijian2_y+1, $black, $fnt, $shijian2);
       imagettftext($dst,$shijian2_size, 0, $shijian2_x, $shijian2_y, $white, $fnt, $shijian2);

       if(!is_dir('Public/qr_shouquan/')){
           mkdir('Public/qr_shouquan/');
       }
       ImageJPEG($dst,'Public/qr_shouquan/'.$user_id.'.jpg'); // 保存图片,但不显示
//        //销毁对象
//        ImageDestroy($dst);
//        return 'Public/qr_shouquan/'.$user_id.'.jpg';
    }
	
function web_qr($user_id,$nickname){
	
		if($user_id == 0){
			$erweima_img='Public/2.jpg';
			$head_img="Public/head.jpg";
		}else{
			$erweima_img='Public/webqr/'.$user_id.'.png';
		}
		//相关参数
		$info = M('qrset')->select();
		$erweima_height=$erweima_width=$info[0]['qr_size'];
		//$dst_path = substr($info[0]['pic_url'],1);
		$dst_path =$info[0]['pic_url'];
		$str=$nickname;
		//file_put_contents('nick.txt',$str);
		$font_size=$info[0]['font_size'];
		$fnt_x=$info[0]['font_x'];
		$fnt_y=$info[0]['font_y'];
		$head_size=$info[0][ 'head_size'];
		$head_x=$info[0]['head_x'];
		$head_y=$info[0]['head_y'];
		//载入字体zt.ttf 
		$fnt = "Public/msyh.ttf"; 
		//头像缩小
		//$src1=$this->img_suo($head_img,$head_width,$head_height);
		//二维码缩小
		//二维码缩小
		$this->img_suo_png($erweima_img,$erweima_width,$erweima_height,$user_id);
		$src ='Public/webqr/'.$user_id.'.png';
		$erweima_img = imagecreatefromstring(file_get_contents($src));
		//file_put_contents('2345.txt',$erweima_img);
		//创建图片的实例
		$dst = imagecreatefromstring(file_get_contents($dst_path));
		$src = imagecreatefromstring(file_get_contents($src_path));
		//获取水印图片的宽高
		//将水印图片复制到目标图片上，最后个参数50是设置透明度，这里实现半透明效果
		imagecopymerge($dst, $src1, $info[0]['head_x'], $info[0]['head_y'], 0, 0, $head_width, $head_height, 100);
		imagecopymerge($dst, $src, $info[0]['qr_x'], $info[0]['qr_y'], 0, 0, $erweima_width, $erweima_height, 100);
		$b = imagecopymerge($dst, $src, $info[0]['qr_x'], $info[0]['qr_y'], 0, 0, 210, 210,100);
		//如果水印图片本身带透明色，则使用imagecopy方法
		imagecopy($dst, $erweima_img, 250, 250, 0, 0, $erweima_width, $erweima_width);
		//创建颜色，用于文字字体的白和阴影的黑 
		$white=imagecolorallocate($dst,222,229,207); 
		$black=imagecolorallocate($dst,50,50,50);
		$a = imagettftext($dst,$font_size, 0, $fnt_x+1, $fnt_y+1, $black, $fnt, $str);
		//dump($a);
		imagettftext($dst,$font_size, 0, $fnt_x, $fnt_y, $white, $fnt, $str);
		$abc = '二维码有效期30分钟';
		imagettftext($dst,$head_size, 0, $head_x+1, $head_y+1, $black, $fnt, $abc);
		imagettftext($dst,$head_size, 0, $head_x, $head_y, $white, $fnt, $abc);
		
		if(!is_dir('Public/web_path/')){
			mkdir('Public/web_path/');
		}
		ImageJPEG($dst,'Public/web_path/'.$user_id.'.jpg'); // 保存图片,但不显示 
		//销毁对象 
		//ImageDestroy($dst);
		 header("content-type:image/gif");
		 imagegif($dst);
	}

	function img_suo($img='head.jpg',$new_width=100,$new_height=100){
		list($width, $height) = getimagesize($img);
		$image_p = imagecreatetruecolor($new_width, $new_height);
		$image = imagecreatefromjpeg($img);
		imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

		return $image_p;
	}
	function img_suo_png($img='head.jpg',$new_width=100,$new_height=100,$user_id){
		list($width, $height) = getimagesize($img);
		$image_p = imagecreate($new_width, $new_height);
		$image = imagecreatefrompng($img);
		imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
		ImagePNG($image_p,'Public/webqr/'.$user_id.'.png');
	}
}
	