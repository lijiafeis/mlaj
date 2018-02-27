<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/27 0027
 * Time: 13:42
 */
namespace Wxapi\Controller;
use Think\Controller;

class ImageCodeController extends Controller{

    //生成的字符的长度
    private $_len = 4;
    //干扰点的个数
    private $_pixel = 100;

    private $_width = 100;
    private $_height = 20;

    public function __set($pro,$val){
        if(property_exists($this, $pro)){
            $this -> $pro = $val;
        }
    }

    /*
    生成随机码
     */
    private function mkCode(){
        //通过类的参数获取需要的随机数的个数。这个值可以自由的指定
        $len = $this -> _len;
        //我们生成的随机数的字母喝数字就是在这里面进行随机生成。
        $str = 'ABCDEFGHIGKLMNOPQRST1234567890';
        $code = '';
        //通过循环的生成随机数进行获取
        for($i = 0; $i < $len; $i++){
            //生成随机数
            $j = mt_rand(0,strlen($str)-1);
            //把随机生成的随机数拼接起来。
            $code .= $str[$j];
        }
        //把生成的随机数，保存在session中，便于当我们输入验证码是验证是否正确。
        @session_start();
        $_SESSION['code'] = $code;
        return $code;
    }
    //生成验证码
    public function makeImage(){
        //获取随机生成的随机码
        $code = $this -> mkCode();
        //通过类的属性指定图形的大小,默认是100,20
        $canvas = imagecreatetruecolor($this -> _width, $this -> _height);
        //随机生成一个颜色的画笔
        $paint = imagecolorallocate($canvas,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
        //把背景的颜色进行改变，默认是黑色的。
        imagefill($canvas, 10, 10, $paint);
        //创建一个画随机码的笔，颜色也是随机生成的。
        $paint_str = imagecolorallocate($canvas,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
        //把随机码打印在画布上。
        imagestring($canvas, 4, 20, 2, $code, $paint_str);
        //绘制干扰点的颜色
        $paint_pixel = imagecolorallocate($canvas,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
        //通过类的属性指定需要多少个干扰点。
        for($i = 0; $i < $this -> _pixel; $i++){
            //绘制不同的干扰点，而绘制的位置也是随机生成的。
            imagesetpixel($canvas, mt_rand(0,imagesx($canvas)),  mt_rand(0,imagesy($canvas)), $paint_pixel);
        }
//        header("content-type:image/png");
//        imagepng($canvas);
//        imagedestroy($canvas);
//        $file_name = dirname(dirname(dirname(__DIR__))) . "\\Uploads\\" . time() . ".png";
        $file_name = "Uploads/code/" . time() . ".png";
        imagepng($canvas,$file_name);
        return $file_name;
    }

    //判断验证码和填写的验证码是否正确
    public function checkCode($code){
        @session_start();
        if(strtolower($code) === strtolower($_SESSION['code'])){
            return true;
        }
        return false;
    }
}