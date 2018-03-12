<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/20 0020
 * Time: 17:33
 */
namespace Admin\Controller;
use Think\Controller;
use Think\Upload;

class MessageController extends Controller{
function __construct(){
		parent::__construct();
		//echo session('admin_id');
		if(!session('admin_id')){
			$this->error('请登录',U('User/index'));
		}

	}
    //公司简介
    public function gsjj(){
        if(IS_GET){
            $data = M('gsjj') -> select();
            $data = $data[0];
            $this -> assign('data',$data);
            $this -> display();
        }else{
            $id = $_POST['id'];
            $data['content'] = $_POST['content'];
            if($id){
                $res = M('gsjj') -> where(['id' => $id]) -> setField('content',$_POST['content']);
            }else{
                $res = M('gsjj') -> add($data);
            }
            if($res){
                $this -> success('保存成功','gsjj');
            }else{
                $this -> error('保存失败','gsjj');
            }
        }
    }

    //客服咨询
    public function kfzx(){
        if(IS_GET){
            $data = M('kfzx') -> select();
            $data = $data[0];
            $this -> assign('data',$data);
            $this -> display();
        }else{
            $id = $_POST['id'];
//            dump($_POST);
//            dump($_FILES);
//            exit;
//            $data['img_url'] = $_POST['img_url'];
            if($id){
                if($_POST['file']['error'] == 0){
                    //上传图片
                    $path = dirname(dirname(dirname(__DIR__)));
                    $filepath = $path . $_POST['img_url'];
                    @unlink($filepath);
                    $data['img_url'] = $this -> uploadPic('kfzx');
                    $res = M('kfzx') -> where(['id' => $id]) -> setField('img_url',$data['img_url']);
                }else{
                    //没有上传图片
                    $this -> error('保存失败','kfzx');
                }
            }else{
                $data['img_url'] = $this -> uploadPic('kfzx');
                $res = M('kfzx') -> add($data);
            }
            if($res){
                $this -> success('保存成功','kfzx');
            }else{
                $this -> error('保存失败','kfzx');
            }
        }
    }

    //一键拨号
    public function yjbh(){
        if(IS_GET){
            $data = M('yjbh') -> select();
            $data = $data[0];
            $this -> assign('data',$data);
            $this -> display();
        }else{
            $id = $_POST['id'];
            $data['content'] = $_POST['content'];
            if($id){
                $res = M('yjbh') -> where(['id' => $id]) -> setField('content',$_POST['content']);
            }else{
                $res = M('yjbh') -> add($data);
            }
            if($res){
                $this -> success('保存成功','yjbh');
            }else{
                $this -> error('保存失败','yjbh');
            }
        }
    }

    //产品介绍
    public function cpjs(){
        if(IS_GET){
            $data = M('cpjs') -> select();
            $data = $data[0];
            $this -> assign('data',$data);
            $this -> display();
        }else{
            $id = $_POST['id'];
            $data['content'] = $_POST['content'];
            if($id){
                $res = M('cpjs') -> where(['id' => $id]) -> setField('content',$_POST['content']);
            }else{
                $res = M('cpjs') -> add($data);
            }
            if($res){
                $this -> success('保存成功','cpjs');
            }else{
                $this -> error('保存失败','cpjs');
            }
        }
    }

    //加入我们
    public function jrwm(){
        if(IS_GET){
            $data = M('jrwm') -> select();
            $data = $data[0];
            $this -> assign('data',$data);
            $this -> display();
        }else{
            $id = $_POST['id'];
            $data['content'] = $_POST['content'];
            if($id){
                $res = M('jrwm') -> where(['id' => $id]) -> setField('content',$_POST['content']);
            }else{
                $res = M('jrwm') -> add($data);
            }
            if($res){
                $this -> success('保存成功','jrwm');
            }else{
                $this -> error('保存失败','jrwm');
            }
        }
    }

    //素材库
    public function sck(){
        $url_info = M('sck');
        $info = $url_info -> select();
        $info = $info[0];
        $this ->assign("empty",'<div class="text-center">暂无数据</div>');
        $this ->assign("url",$info);
        $this -> display();
    }

    public function setSckUrl(){
        $url_info = M('sck');
        $url = $_POST['url'];
        $id = $_POST['id'];
        if($id){
            $res = $url_info -> where(['id' => $id]) -> setField('url',$url);
        }else{
            $res = $url_info -> add($_POST);
        }
        if($res){
            $this -> success('保存成功','sck');
        }else{
            $this -> error('保存失败','sck');
        }
    }

    function del_image(){
        $model = M('sck');
        $id = $_POST['id'];
        $path1 = $model -> getFieldById($id,'img_url');
//        $path = dirname(dirname(dirname(__DIR__)));
//        $filepath = $path . $path1;
//        @unlink($filepath);
        $model -> where(" id = '$id' ") -> delete();
        $arr = array();
        echo json_encode($arr);
    }


    //上传图片信息，并且保存图片到服务器
    private function uploadPic($file){
        $upload = new Upload();
        $upload -> maxSize = 3145728;
        $upload -> exts = array('jpg','png','jpeg');
        $upload -> rootPath = './Uploads/';
        $upload -> savePath = $file . '/';
        $upload -> saveName = 'time';
        $info = $upload -> upload();
        if(!$info){
            $this -> error($upload -> getError());
        }else{
            $xw_img = '/Uploads/' . $info['file']['savepath'] . $info['file']['savename'];
//            $image = new \Image();
//            $image -> open('.' . $xw_img);
//            $image -> thumb(320,540) -> save('.' . $xw_img);
            return $xw_img;
        }
    }
}