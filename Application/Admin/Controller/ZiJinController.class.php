<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/20 0020
 * Time: 13:46
 */
namespace Admin\Controller;
use Think\Controller;
use Think\Pageajax;

class ZiJinController extends Controller{
	
	function __construct(){
		parent::__construct();
		//echo session('admin_id');
		if(!session('admin_id')){
			$this->error('请登录',U('User/index'));
		}

	}
	
    public function chongzhiList(){
        $this -> display();
    }

    public function chongzhibb(){
        $name = $_GET['name'];
        $id = $_GET['id'];
		$state = $_GET['cz'];
		$where = array();
		$w = array();
		$model = M('chongzhi');
		if($name){
			$where['name'] = $name;
			$w['a.name'] = $name;
		}
		if($id){
			$where['user_id'] = $id;
			$w['a.user_id'] = $id;
		}
		if($state != -1){
			$where['state'] = $state;
			$w['a.state'] = $state;
		}
        $where = array();
        $count=$model->where($where)->count();
        $Page = $this -> setPage($count);
        $arr= $model
            -> alias('a')
			-> field('a.*,b.type,b.sj_userid')
			-> join("left join __USER__ as b on a.user_id = b.id")
            -> where($w)
            -> limit($Page -> firstRow . ',' . $Page -> listRows)
			-> order('a.id desc')
            -> select();
		foreach($arr as $k => $v){
			$sj_name = M('user') -> getFieldById($v['sj_userid'],'name');
			$arr[$k]['sj_name'] = $sj_name;
		}
        $this->assign('arr',$arr);
        $show = $Page->show();
        $this->assign('count',$count);
        $this->assign('page',$show);
        $this->assign('empty','<tr><td colspan="9" style="text-align:center;line-height:40px;">没有查询到相关信息</td></tr>');
        $this->display();
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

    //充值积分
    public function chongzhi(){
        $jifen = $_POST['jifen'];
        $id = $_POST['user_id'];
		$user_id = M('chongzhi') -> getFieldById($id,'user_id');
        $res = M('user') -> where(['id' => $user_id]) -> setInc('jifen',$jifen);
        M('user') -> where(['id' => $user_id]) -> setInc('zongjifen',$jifen);
        if($res){
            $data['user_id'] = $user_id;
            $data['number'] = $jifen;
            $data['time'] = time();
            $data['type'] = 1;
			M('chongzhi') -> where(['id' => $id]) -> setField('state',1);
			//发送积分充值成功的模板提醒
            $this -> sendTemplateJifen($user_id,$jifen);
            M('jifen') -> add($data);
			//把充值的积分的钱充值给上级
//			$sj_userid = M('user') -> getFieldById($user_id,'sj_userid');
//			if($sj_userid != 0){
//				//把钱给上级
//				$res = M('user') -> where(['id' => $sj_userid]) -> setInc('money',$jifen);
//				if($res){
//					//保存到返佣表中
//					$a['user_id'] = $sj_userid;
//					$a['money'] = $jifen;
//					$a['time'] = time();
//					$a['type'] = 0;
//					M('fanyong') -> add($a);
//					//给上级发送模板消息
//                    $name = M('user') -> getFieldById($user_id,'name');
//                    $this -> sendTemplateSjMoney($jifen,$name,$sj_userid);
//				}
//			}
            echo 0;
        }else{
            echo -1;
        }
    }
	
	//充值积分
    public function sjchongzhi(){
        $jifen = $_POST['jifen'];
        $user_id = $_POST['user_id'];
        $res = M('user') -> where(['id' => $user_id]) -> setInc('jifen',$jifen);
        //M('user') -> where(['id' => $user_id]) -> setInc('zongjifen',$jifen);
        if($res){
            $data['user_id'] = $user_id;
            $data['number'] = $jifen;
            $data['time'] = time();
            $data['type'] = 1;
			//发送积分充值成功的模板提醒
            $this -> sendTemplateJifen($user_id,$jifen);
            M('jifen') -> add($data);
			//$this -> chongzhiFee($jifen,$user_id);
			$shengji_id = session('shengjiid');
			M('shengji') -> where(" id = {$shengji_id}") -> setField('is_true',1);
            echo 0;
        }else{
            echo -1;
        }
    }
	
	//充值积分
    public function chongzhiFee($money,$user_id){
       // $money = $_POST['money'];
       // $user_id = $_POST['user_id'];
		$sj_userid = M('user') -> getFieldById($user_id,'sj_userid');
		$type = M('user') -> getFieldById($user_id,'type');
		if($type == 1){
			//echo -2;
			//exit;
		}
		if($sj_userid == 0){
			//echo -3;
			//exit;
		}
        $res = M('user') -> where(['id' => $sj_userid]) -> setInc('money',$money);
        if($res){
			//记录数据
			$data['user_id'] = $sj_userid;
			$data['money'] = $money;
			$data['time'] = time();
			$data['type'] = 4;
			M('fanyong') -> add($data);
			$name = M('user') -> getFieldById($user_id,'name');
			 $this -> sendTemplateSjMoney1($money,$name,$sj_userid);
           // echo 0;
        }else{
           // echo -1;
        }
    }

    //给上级的钱数
    public function sendTemplateSjMoney($money,$name,$sj_userid){
        $u_id = M('user') -> getFieldById($sj_userid,'u_id');
        $openid = M('users') -> getFieldByUserId($u_id,'openid');
        $info = "{$name}充值货款返回金额";
        $template = A("Pay/Template");
        $url = 'http://'.$_SERVER['SERVER_NAME'].U('/Home/Center/shop');
        $template->send_money($money,$info,$openid,$url);
    }
	
	//给上级的钱数
    public function sendTemplateSjMoney1($money,$name,$sj_userid){
        $u_id = M('user') -> getFieldById($sj_userid,'u_id');
        $openid = M('users') -> getFieldByUserId($u_id,'openid');
        $info = "{$name}升级返回金额";
        $template = A("Pay/Template");
        $url = 'http://'.$_SERVER['SERVER_NAME'].U('/Home/Center/shop');
        $template->send_money($money,$info,$openid,$url);
    }

    //积分充值成功
    public function sendTemplateJifen($user_id,$jifen){
        $u_id = M('user') -> getFieldById($user_id,'u_id');
        $openid = M('users') -> getFieldByUserId($u_id,'openid');
        $info = M('user') -> find($user_id);
        $template = A("Pay/Template");
        $url = 'http://'.$_SERVER['SERVER_NAME'].U('/Home/Center/index');
        $template -> send_jifen($info['name'],$jifen,$info['jifen'],$openid,$url);
    }

	public function shengjiList(){
		$this -> display();
	}
	
	public function shengjibb(){
		$name = $_GET['name'];
        $id = $_GET['id'];
		$state = $_GET['cz'];
		$where = array();
		$w = array();
		$model = M('shengji');
		if($name){
			$where['name'] = $name;
			$w['a.name'] = $name;
		}
		if($id){
			$where['user_id'] = $id;
			$w['a.user_id'] = $id;
		}
		if($state != -1){
			$where['state'] = $state;
			$w['a.state'] = $state;
		}
        $where = array();
        $count=$model->where($where)->count();
        $Page = $this -> setPage($count);
        $arr= $model
            -> alias('a')
			-> field('a.*,b.type as oldtype,b.sj_userid')
			-> join("left join __USER__ as b on a.user_id = b.id")
            -> where($w)
            -> limit($Page -> firstRow . ',' . $Page -> listRows)
			-> order('a.id desc')
            -> select();
		foreach($arr as $k => $v){
			$sj_name = M('user') -> getFieldById($v['sj_userid'],'name');
			$arr[$k]['sj_name'] = $sj_name;
		}
        $this->assign('arr',$arr);
        $show = $Page->show();
        $this->assign('count',$count);
        $this->assign('page',$show);
        $this->assign('empty','<tr><td colspan="9" style="text-align:center;line-height:40px;">没有查询到相关信息</td></tr>');
        $this->display();
	}
	
	//设置升级的逻辑
	public function setShengji(){
		$shengji_id = $_POST['shengji_id'];
		session('shengjiid',$shengji_id);
		//得到要升级到的级别
		$type = M('shengji') -> getFieldById($shengji_id,'type');
		//如果升级为总代的
		$user_id = M('shengji') -> getFieldById($shengji_id,'user_id');
		
		M('user') -> where(['id' => $user_id]) -> setField('dy_yeji',0);
		
		//把直推人的id去掉，他们两个从此以后没有联系
		M('user') -> where(['id' => $user_id]) -> setField('zt_userid',0);
		$sj_userid = M('user') -> getFieldById($user_id,'sj_userid');
		//得到上级的级别
		$sj_type = M('user') -> getFieldById($sj_userid,'type');
		$type4 = M('user') -> getFieldById($user_id,'type');
		M('user') -> where("zt_userid = {$user_id} and type = {$type4}") -> setField('zt_userid',0);
		
		
		
		if($sj_type == $type){
			//上级的上级就是我的上级
			$sjsj = M('user') -> getFieldById($sj_userid,'sj_userid');
			$res = M('user') -> where(['id' => $user_id]) -> setField('sj_userid',$sjsj);
			
			if($sj_type == 1){
				//他原来的上级是总代，每月要算业绩的
				M('user') -> where(['id' => $user_id]) -> setField('yeji_userid',$sj_userid);
			}
			
		}else if($type - $sj_type > 0){
			//他升级后的等级还是比他上级低，这是，他的的上级是不变的。
			$res = 1;
		}else if($type - $sj_type < 0){
			//他升完级后等级比他上级还高
			$sjsj = $this -> setSjId($type,$sj_userid);
			$res = M('user') -> where(['id' => $user_id]) -> setField('sj_userid',$sjsj);
			if($sj_type == 1){
				//他原来的上级是总代，每月要算业绩的
				M('user') -> where(['id' => $user_id]) -> setField('yeji_userid',$sj_userid);
			}else{
				//原来的上级不是总代，找到上级的上级是否是总代
				$suser_id = $this -> setyejiUser_id($sj_userid);
				if($suser_id != 0){
					M('user') -> where(" id = {$user_id}") -> setField('yeji_userid',$suser_id);
				}
			}
		}
		if($res){
			M('shengji') -> where(['id' => $shengji_id]) -> setField('state',1);
			$type1 = M('user') -> getFieldById($user_id,'type');
			M('user') -> where(['id' => $user_id]) -> setField('type',$type);
			//发送升级模板
            $this -> sendTemplateSj($user_id,$type1,$type);
			echo 0;
		}else{
			echo -1;
		}
	}
	
	private function setyejiUser_id($user_id){
		if($user_id == 0){
			return 0;
		}
		$type = M('user') -> getFieldById($user_id,'type');
		if($type == 1){
			return $user_id;
		}else{
			$sj_userid = M('user') -> getFieldById($user_id,'sj_userid');
			return $this -> setyejiUser_id($sj_userid);
		}
	}

    //会员升级成功提醒
    public function sendTemplateSj($user_id,$type1,$type){

	    switch ($type1){
            case 1:
                $type1 = '总代';
                break;
            case 2:
                $type1 = '特级';
                break;
            case 3:
                $type1 = '一级';
                break;
            case 4:
                $type1 = '初级';
                break;
        }

        switch ($type){
            case 1:
                $type = '总代';
                break;
            case 2:
                $type = '特级';
                break;
            case 3:
                $type = '一级';
                break;
            case 4:
                $type = '初级';
                break;
        }
        $u_id = M('user') -> getFieldById($user_id,'u_id');
        $openid = M('users') -> getFieldByUserId($u_id,'openid');
        $name = M('user') -> getFieldById($user_id,'name');
        $template = A("Pay/Template");
        $url = 'http://'.$_SERVER['SERVER_NAME'].U('/Home/Center/index');
        $template->send_shengji($name,$type1,$type,$openid,$url);
    }
	
	public function setSjId($type,$sj_userid){
		$a = 0;
		if($sj_userid == 0){
			return;
		}
		$sj_type = M('user') -> getFieldById($sj_userid,'type');
		if($type - $sj_type >0 ){
			//file_put_contents('a.txt',$sj_userid);
			return $sj_userid;
		}else if($type == $sj_userid){
			$sjs = M('user') -> getFieldById($sj_userid,'sj_userid');
			//file_put_contents('a.txt',$sjs);
			return $sjs;
		}else if($type - $sj_type < 0){
			$sjsj = M('user') -> getFieldById($sj_userid,'sj_userid');
			$a = $this -> setSjId($type,$sjsj);
		}
		return $a;
	}
	
    public function jifenList(){
        $this -> display();
    }

    public function jifenbb(){
        $name = $_GET['name'];
        $id = $_GET['id'];
        $model = M('jifen');
        if(!$name && !$id){
            $count=$model->count();
            $Page = $this -> setPage($count);
            $arr= $model
                -> alias('a')
                -> field('a.*,b.*,a.time as jftime,a.type as lb')
                -> join("left join __USER__ as b on a.user_id = b.id")
                -> limit($Page -> firstRow . ',' . $Page -> listRows)
                -> order('a.time desc')
                -> select();
        }

        if($name && !$id){
            $count=$model
                -> alias("a")
                -> join("left join __USER__ as b on a.user_id = b.id")
                ->where(['b.name' => $name])->count();
            $Page = $this -> setPage($count);
            $arr= $model
                -> alias('a')
                -> field('a.*,b.*,a.time as jftime,a.type as lb')
                -> join("left join __USER__ as b on a.user_id = b.id")
                -> where(['b.name' => $name])
                -> limit($Page -> firstRow . ',' . $Page -> listRows)
                -> order('a.time desc')
                -> select();
        }

        if(!$name && $id){
            $count=$model
                -> alias("a")
                -> join("left join __USER__ as b on a.user_id = b.id")
                ->where(['b.id' => $id])->count();
            $Page = $this -> setPage($count);
            $arr= $model
                -> alias('a')
                -> field('a.*,b.*,a.time as jftime,a.type as lb')
                -> join("left join __USER__ as b on a.user_id = b.id")
                -> where(['b.id' => $id])
                -> limit($Page -> firstRow . ',' . $Page -> listRows)
                -> order('a.time desc')
                -> select();
        }

        if($name && $id){
            $count=$model
                -> alias("a")
                -> join("left join __USER__ as b on a.user_id = b.id")
                ->where(['b.id' => $id,'b.name' => $name])->count();
            $Page = $this -> setPage($count);
            $arr= $model
                -> alias('a')
                -> field('a.*,b.*,a.time as jftime,a.type as lb')
                -> join("left join __USER__ as b on a.user_id = b.id")
                -> where(['b.id' => $id,'b.name' => $name])
                -> limit($Page -> firstRow . ',' . $Page -> listRows)
                -> order('a.time desc')
                -> select();
        }



        $this->assign('arr',$arr);
//        $this->assign('bili',$bile);
        // 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();
        $this->assign('count',$count);
        $this->assign('page',$show);
        $this->assign('empty','<tr><td colspan="9" style="text-align:center;line-height:40px;">没有查询到相关信息</td></tr>');
        $this->display();
    }

    public function yongjinList(){
        $this -> display();
    }

    public function yongjinbb(){
        $name = $_GET['name'];
        $id = $_GET['id'];
        $model = M('fanyong');
        if(!$name && !$id){
            $count=$model->count();
            $Page = $this -> setPage($count);
            $arr= $model
                -> alias('a')
                -> field('a.*,a.type as atype,b.*,a.time as jftime,a.money as fy_money')
                -> join("left join __USER__ as b on a.user_id = b.id")
                -> limit($Page -> firstRow . ',' . $Page -> listRows)
                -> order('a.time desc')
                -> select();
        }

        if($name && !$id){
            $count=$model
                -> alias("a")
                -> join("left join __USER__ as b on a.user_id = b.id")
                ->where(['b.name' => $name])->count();
            $Page = $this -> setPage($count);
            $arr= $model
                -> alias('a')
                -> field('a.*,a.type as atype,b.*,a.time as jftime,a.money as fy_money')
                -> join("left join __USER__ as b on a.user_id = b.id")
                -> where(['b.name' => $name])
                -> limit($Page -> firstRow . ',' . $Page -> listRows)
                -> order('a.time desc')
                -> select();
        }

        if(!$name && $id){
            $count=$model
                -> alias("a")
                -> join("left join __USER__ as b on a.user_id = b.id")
                ->where(['b.id' => $id])->count();
            $Page = $this -> setPage($count);
            $arr= $model
                -> alias('a')
                -> field('a.*,a.type as atype,b.*,a.time as jftime,a.money as fy_money')
                -> join("left join __USER__ as b on a.user_id = b.id")
                -> where(['b.id' => $id])
                -> limit($Page -> firstRow . ',' . $Page -> listRows)
                -> order('a.time desc')
                -> select();
        }

        if($name && $id){
            $count=$model
                -> alias("a")
                -> join("left join __USER__ as b on a.user_id = b.id")
                ->where(['b.id' => $id,'b.name' => $name])->count();
            $Page = $this -> setPage($count);
            $arr= $model
                -> alias('a')
                -> field('a.*,a.type as atype,b.*,a.time as jftime,a.money as fy_money')
                -> join("left join __USER__ as b on a.user_id = b.id")
                -> where(['b.id' => $id,'b.name' => $name])
                -> limit($Page -> firstRow . ',' . $Page -> listRows)
                -> order('a.time desc')
                -> select();
        }

        $this->assign('arr',$arr);
        // 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();
        $this->assign('count',$count);
        $this->assign('page',$show);
        $this->assign('empty','<tr><td colspan="9" style="text-align:center;line-height:40px;">没有查询到相关信息</td></tr>');
        $this->display();
    }

    public function tixianList(){
        $this -> display();
    }

    public function tixianbb(){
        $name = $_GET['name'];
        $id = $_GET['id'];
        $model = M('tixian');
        if(!$name && !$id){
            $count=$model->count();
            $Page = $this -> setPage($count);
            $arr= $model
                -> alias('a')
                -> field('a.*,a.money as txmoney,a.user_id as uid,a.type as ty,b.*')
                -> join("left join __USER__ as b on a.user_id = b.id")
                -> limit($Page -> firstRow . ',' . $Page -> listRows)
                -> where(['a.type' => 1])
                -> order('a.time desc')
                -> select();
        }else if($name){
            $count=$model
                -> alias('a')
                -> join("left join __USER__ as b on a.user_id = b.id")
                -> where(['b.name' => $name])
                -> count();
            $Page = $this -> setPage($count);
            $arr= $model
                -> alias('a')
                -> field('a.*,a.money as txmoney,a.user_id as uid,a.type as ty,b.*')
                -> join("left join __USER__ as b on a.user_id = b.id")
                -> limit($Page -> firstRow . ',' . $Page -> listRows)
                -> where(['a.type' => 1,'name' => $name])
                -> order('a.time desc')
                -> select();
        }else if($id){
            $count=$model
                -> alias('a')
                -> join("left join __USER__ as b on a.user_id = b.id")
                -> where(['b.id' => $id])
                -> count();
            $Page = $this -> setPage($count);
            $arr= $model
                -> alias('a')
                -> field('a.*,a.money as txmoney,a.user_id as uid,a.type as ty,b.*')
                -> join("left join __USER__ as b on a.user_id = b.id")
                -> limit($Page -> firstRow . ',' . $Page -> listRows)
                -> where(['a.type' => 1,'b.id' => $id])
                -> order('a.time desc')
                -> select();
        }
       // dump($arr);
        $this->assign('arr',$arr);
        $show = $Page->show();
        $this->assign('count',$count);
        $this->assign('page',$show);
        $this->assign('empty','<tr><td colspan="9" style="text-align:center;line-height:40px;">没有查询到相关信息</td></tr>');
        $this->display();

    }

    public function tixianSq(){
        $this -> display();
    }

    public function tixianSqbb(){
        $model = M('tixian');
        $count=$model->count();
        $Page = $this -> setPage($count);
        $arr= $model
            -> alias('a')
            -> field('a.*,a.id as aid,a.user_id as uid,a.money as txmoney,a.type as ty,b.*,c.*,c.tel as banktel,a.state as astate,b.type as bty')
            -> join("left join __USER__ as b on a.user_id = b.id")
            -> join("left join __USER_BANK__ as c on a.user_id = c.user_id")
            -> limit($Page -> firstRow . ',' . $Page -> listRows)
            -> where(['a.type' => 0])
            -> order('a.time desc')
            -> select();
        $this->assign('arr',$arr);
        //dump($arr);
		//exit;
        $show = $Page->show();
        $this->assign('count',$count);
        $this->assign('page',$show);
        $this->assign('empty','<tr><td colspan="9" style="text-align:center;line-height:40px;">没有查询到相关信息</td></tr>');
        $this->display();
    }

    public function setType(){
        $tx_id = $_POST['tx_id'];
        $res = M('tixian') -> where(['id' => $tx_id]) -> setField('type',1);
        $user_id = M('tixian') -> getFieldById($tx_id,'user_id');
        $money = M('tixian') -> getFieldById($tx_id,'money');
        if($res){
            $this -> sendTemplateTixian($user_id,$money);
            echo 0;
        }else{
            echo -1;
        }
    }

    //提现修改状态成功
    public function sendTemplateTixian($user_id,$money){
        $u_id = M('user') -> getFieldById($user_id,'u_id');
        $openid = M('users') -> getFieldByUserId($u_id,'openid');
        $template = A("Pay/Template");
        $info = '';
        $url = 'http://'.$_SERVER['SERVER_NAME'].U('/Home/Center/index');
        $template -> send_tixian($money,$info,$openid,$url);
    }
	
	public function syFanDian(){
		$this -> display();
	}
	
	public function syFanDianbb(){
		   $model = M('user');
           $count=$model->count();
            $Page = $this -> setPage($count);
            $arr= $model
                -> limit($Page -> firstRow . ',' . $Page -> listRows)
                -> where(['type' => 1])
                -> order('sy_money desc')
                -> select();
        $this->assign('arr',$arr);
        $show = $Page->show();
        $this->assign('count',$count);
        $this->assign('page',$show);
        $this->assign('empty','<tr><td colspan="9" style="text-align:center;line-height:40px;">没有查询到相关信息</td></tr>');
        $this->display();
	}
	public function syFanDianAjax(){
		$user_id = $_POST['user_id'];
		$money = $_POST['money'];
		$is_fandian = M('user') -> getFieldById($user_id,'is_fandian');
		if($is_fandian == 1){
			echo -2;
			exit;
		}
		$res = M('user') -> where(['id' => $user_id]) -> setInc('money',$money);
		M('user') -> where(['id' => $user_id]) -> setField('is_fandian',1);
		
		if($res){
			$data['user_id'] = $user_id;
			$data['money'] = $money;
			$data['time'] = time();
			$data['type'] = 2;
			M('fanyong') -> add($data);
			echo 0;
		}else{echo -1;}
	}
}