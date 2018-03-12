<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/10 0010
 * Time: 09:56
 */

namespace Admin\Controller;
use Think\Controller;
use Think\Pageajax;

class MemberController extends Controller{
function __construct(){
		parent::__construct();
		//echo session('admin_id');
		if(!session('admin_id')){
			$this->error('请登录',U('User/index'));
		}

	}
    //显示会员信息
    public function memberList(){
		$syz = M('shop') -> where(['type' => 2]) -> select();
        $this -> assign('syz',$syz);
		$this -> display();
    }
	
    public function memberbb(){
        $name = $_GET['name'];
        $id = $_GET['id'];
        $jibie = $_GET['jibie'];
        $model = M('user');
        $where['state'] = 1;
        if($name){
            $where['name'] = $name;
        }
        if($id){
            $where['id'] = $id;
        }
        if($jibie != 0){
            $where['type'] = $jibie;
        }
        $count=$model->where($where)->count();
        $Page = $this -> setPage($count);
        $arr= $model
            -> where($where)
            -> limit($Page -> firstRow . ',' . $Page -> listRows)
            -> order('time desc')
            -> select();
        foreach($arr as $k => $v){
            if($v['sj_userid'] == 0){
                $arr[$k]['sj_name'] = '平台';
            }else{
                $arr[$k]['sj_name'] = M('user') -> getFieldById($v['sj_userid'],'name');
            }
			if($v['zt_userid'] == 0){
				$arr[$k]['zt_userid'] = '无';
			}else{
				$arr[$k]['zt_userid'] = M('user') -> getFieldById($v['zt_userid'],'name');
			}
        }
        $this->assign('arr',$arr);
        // 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();
        $this->assign('count',$count);
		$syz = M('shop') -> where(['type' => 2]) -> select();
        $this -> assign('syz',$syz);
        $this->assign('page',$show);
        $this->assign('empty','<tr><td colspan="9" style="text-align:center;line-height:40px;">没有查询到相关信息</td></tr>');
        $this->display();
    }

    public function daoru(){
        $jifen = $_POST['jifen'];
        $user_id = $_POST['user_id'];
        $res = M('user') -> where(['id' => $user_id]) -> setInc('jifen',$jifen);
			
		$data['user_id'] = $user_id;
		$data['number'] = $jifen;
		$data['time'] = time();
		$data['type'] = 1;
		M('jifen') -> add($data);
		//发送积分充值成功的模板提醒
		//$this -> sendTemplateJifen($user_id,$jifen);
        if($res){
            echo 0;
        }else{
            echo -1;
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

    //清除个人的佣金
    public function clearFee(){
        $user_id = $_POST['user_id'];
        $res = M('user') -> where(['id' => $user_id]) -> setField('money',0);
        if($res){
            echo 0;
        }else{
            echo -1;
        }
    }

    //清除所有的佣金
    public function clearAllFee(){
        $data['money'] = 0;
        //$res = M('user') -> execute('Update wx_user set money = 0');
		//得到上月的佣金数
		$b = M('user') -> select();
		foreach($b as $k => $v){
			$fee = $v['money'] - $v['sy_money'] <= 0 ? 0 : $v['money'] - $v['sy_money'];
			$a = M('user') -> where(['id' => $v['id']]) -> setField('money',$fee);
			$a = M('user') -> where(['id' => $v['id']]) -> setField('sy_money',0);
		}	
		echo 0;
        
    }

    //审核用户信息
    public function shMember(){
        $this -> display();
    }

    public function shMemberbb(){
        $model = M('user');
        $count = $model -> where(['state' => 0]) -> count();
        $Page = $this -> setPage($count);
        $arr = $model
            -> alias("a")
            -> field("a.*")
            -> where(['state' => 0])
            -> limit($Page->firstRow.','.$Page->listRows)
            -> select();
        //得到上级姓名
        foreach ($arr as $k => $v){
            if($v['sj_userid'] != 0){
                $arr[$k]['sj_name'] = M('user') -> where(['id' => $v['sj_userid']]) -> getField('name');
            }else{
                $arr[$k]['sj_name'] = "平台";
            }

        }
        $this->assign('arr',$arr);
        // 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();
        $this->assign('count',$count);
        $this->assign('page',$show);
        $this->assign('empty','<tr><td colspan="9" style="text-align:center;line-height:40px;">没有查询到相关信息</td></tr>');
        $this->display();
    }

    //改变审核状态
    public function shType(){
        $id = $_GET['id'];
        $type = $_GET['type'];
        if($type == 1){
            //审核通过
            M('user') -> where(['id' => $id]) -> setField('state',1);
            //审核通过，发送模板消息
            $this -> sendTemplate($id);
            $this -> success('审核通过','memberList');
        }else{
            //审核不通过
            M('user') -> delete($id);
            $this -> error('审核不通过','shMember');
        }

    }

    public function sendTemplate($user_id){
        $u_id = M('user') -> getFieldById($user_id,'u_id');
        $openid = M('users') -> getFieldByUserId($u_id,'openid');
        $template = A("Pay/Template");
		file_put_contents('b.txt',$openid . ',' . $user_id);
        $template->send_shenHe($user_id,$openid);
    }

    public function setUserId(){
		$sj_userid = $_POST['sj_userid'];
		$zt_userid = $_POST['zt_userid'];
		$user_id = $_POST['user_id'];
		if($sj_userid){
			$res = M('user') -> where(['id' => $user_id]) -> setField('sj_userid',$sj_userid);
		}
		if($zt_userid){
			$res = M('user') -> where(['id' => $user_id]) -> setField('zt_userid',$zt_userid);
		}
		if($res){
			echo 0;
		}else{
			echo -1;
		}
	}
	
	//设置库存
	public function setSyz(){
		$user_id = $_POST['user_id'];
		$number = $_POST['number'];
		$shop_id = $_POST['shop_id'];
		//file_put_contents('syz.txt',$shop_id . ',' . $user);
		$res = M('dl_kucun') -> where(['user_id' => $user_id,'shop_id' => $shop_id]) -> select();
		if($res){
			$b = M('dl_kucun') -> where(['user_id' => $user_id,'shop_id' => $shop_id]) -> setInc('kucun',$number);
		}else{
			$da['user_id'] = $user_id;
			$da['kucun'] = $number;
			$da['shop_id'] = $shop_id;
			$b = M('dl_kucun') -> add($da);
		}
		if($b){
			echo 0;
		}else{
			echo -1;
		}
	}

}