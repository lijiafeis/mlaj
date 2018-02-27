<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/7 0007
 * Time: 09:42
 */
namespace Admin\Controller;
use Think\Controller;

class CanshuController extends Controller{





    public function bili(){
        if(IS_GET){
            $bili = M('bili') -> select();
            $bili = $bili['0'];
            $this -> assign('bili',$bili);
            $this -> display();
        }else{
            $id = $_POST['id'];
            $arr['number'] = $_POST['number'];
            if($id){
                $res = M('bili') -> where(['id' => $id]) -> setField('number',$_POST['jifen']);
            }else{
                $res = M('bili') -> add($arr);
            }
            if($res){
                $this -> success('修改成功','bili');
            }else{
                $this -> success('修改失败','bili');
            }
        }

    }

    public function feeBili(){
        if(IS_GET){
            $bili = M('feebili') -> select();
            $bili = $bili['0'];
            $this -> assign('bili',$bili);
            $this -> display();
        }else{
            $id = $_POST['id'];
            $data['one'] = $_POST['two'] / $_POST['one'];
//            $data['two'] = $_POST['four'] / $_POST['three'];
            if($id){
                $res = M('feebili') -> where(['id' => $id]) -> save($data);
            }else{
                $res = M('feebili') -> add($data);
            }
            if($res){
                $this -> success('修改成功','feeBili');
            }else{
                $this -> success('修改失败','feeBili');
            }
        }

    }

    public function tuijian(){
        if(IS_GET){
            $tuijian = M('tuijian') -> select();
            $tuijian = $tuijian['0'];
            $this -> assign('tuijian',$tuijian);
            $this -> display();
        }else{
            $id = $_POST['id'];
            $data['fee1'] = $_POST['fee1'];
            $data['fee2'] = $_POST['fee2'];
            $data['fee3'] = $_POST['fee3'];
//            $data['two'] = $_POST['four'] / $_POST['three'];
            if($id){
                $res = M('tuijian') -> where(['id' => $id]) -> save($data);
            }else{
                $res = M('tuijian') -> add($data);
            }
            if($res){
                $this -> success('修改成功','tuijian');
            }else{
                $this -> success('修改失败','tuijian');
            }
        }

    }

}