<?php
/**
 * 商品类信息
 * User: Administrator
 * Date: 2017/4/1 0001
 * Time: 17:35
 */
namespace Admin\Controller;
use Think\Controller;
use Think\Image;
use Think\Pageajax;
use Think\Upload;

class ShopController extends Controller{
    public function shop(){
        $this -> display();
    }

    public function shopbb(){
        $model = M('shop');
        $count=$model->count();
        $Page = $this -> setPage($count);
        $arr = $model
            ->limit($Page->firstRow.','.$Page->listRows)
            -> order("time desc")
            -> select();
        $this->assign('arr',$arr);
        // 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();
        $this->assign('count',$count);
        $this->assign('page',$show);
        $this->assign('empty','<tr><td colspan="9" style="text-align:center;line-height:40px;">没有查询到相关信息</td></tr>');
        $this->display();
    }

    //删除商品
    public function delete(){
        $id = $_POST['id'];
        $url_path = M('shop') -> getFieldById($id,'img_url');
        $path = dirname(dirname(dirname(__DIR__)));
        $filepath = $path . $url_path;
        @unlink($filepath);
        $res = M('shop') -> delete($id);
        if($res){
            echo 0;
        }else{
            echo 1;
        }
    }

    //新增产品
    public function create(){
        if(IS_GET){
//            $chicun = M('size')  ->  select();
//            $this -> assign('ccList',$chicun);
            $this -> display();
        }else{
            $data = $_POST;
            if($_FILES['file']['error'] != 0){
                $this -> error('请上传图片','create');
                exit;
            }
            $data['img_url'] = $this -> uploadPic('shop');
            //判断有没有上传图片
            $res = M('shop') -> add($data);
            $data1['user_id'] = 0;
            $data1['shop_id'] = $res;
            $data1['kucun'] = $data['number'];
            M('dl_kucun') -> add($data1);
            if($res){
                $this -> success('添加商品成功','shop');
            }else{
                $this -> success('添加商品成功','create');
            }
        }
    }

    public function update(){
        if(IS_GET){
            $id = $_GET['id'];
            $data = M('shop') -> find($id);
//            $chicun = M('size')  -> select();
            $a = M('dl_kucun') -> where(['user_id' => 0,'shop_id' => $id]) -> select();
           // $data['number'] = $a[0]['kucun'];
           // $sizeList = explode(',',$data['size_id']);
//            foreach($sizeList as $k => $v){
//                foreach ($chicun as $key => $val){
//                    if($v == $val['id']){
//                        $chicun[$k]['check'] = 1;
//                    }
//                }
//            }
//            $this -> assign('ccList',$chicun);
            $this -> assign('data',$data);
            $this -> display();
        }else{
            $data = $_POST;
            $file = $_FILES;
            $file = $file['file']['error'];
            if($file != 0){
                //没有上传图片
                $res = M('shop') -> where(['id' => $data['id']]) -> save($data);
            }else{
                //修改图片，删除原来的。
                $path = dirname(dirname(dirname(__DIR__)));
                $filepath = $path . $data['url'];
                @unlink($filepath);
                $data['img_url'] = $this -> uploadPic('shopimg');
                $res = M('shop') -> where(['id' => $data['id']]) -> save($data);
            }
            M('dl_kucun') -> where(['user_id' =>0,'shop_id' => $data['id']]) -> setField('kucun',$data['number']);
            if($res){
                $this -> success('更新成功','shop');
            }else{
                $this -> error('未发现数据改变','shop');
            }
        }


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
            $image = new Image();
            $image -> open('.' . $xw_img);
            $image -> thumb(720,540) -> save('.' . $xw_img);
            return $xw_img;
        }
    }

    public function setPage($count){
        $pagecount = 10;
        $Page = new Pageajax($count,$pagecount);
        $Page->setConfig('first','首页');
        $Page->setConfig('prev','上一页');
        $Page->setConfig('next','下一页');
        $Page->setConfig('last','尾页');
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% 第 '.I('p',1).' 页/共 %TOTAL_PAGE% 页 ( '.$pagecount.' 条/页 共 %TOTAL_ROW% 条)');
        return $Page;
    }


}