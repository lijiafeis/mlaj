<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/2 0002
 * Time: 16:46
 */
namespace Console\Controller;
use Think\Controller;

class IndexController extends Controller{


    public function test(){   
        $model = M('user');
        $data = $model -> where(['type' => 1]) -> select();
		//遍历所有的总代级别
        foreach ($data as $k => $v){
			//计算这个人上月的业绩
			$zong1 = M('user') -> where(['sj_userid' => $v['id']]) -> sum('zongjifen');
			$zong2 = M('user') -> where(['yeji_userid' => $v['id']]) -> sum('zongjifen');
			$zong = $zong1 + $zong2;
			M('user') -> where(['id' => $v['id']]) -> setField('sy_yeji',$zong);
			
			
//              $arr = $this -> getMoney($v['id']);
//              dump($arr);
            //自己和下级的所有积分相加，
            $zongjifen = $this -> getJIfen($v['id'],$v['zongjifen']);
			//自己和直推人也就是A的业绩相加
			$zongjifen = $this -> getZtJifen($v['id'],$zongjifen);
			//升级以后和自己有关系的人的业绩相加
			$zongjifen = $this -> getYjJifen($v['id'],$zongjifen);
			$money = $this -> getFanyong($zongjifen);
			//dump($money);
            //得到直推的人的钱
            $money1 = $this -> getZtMoney($v['id']);
			$money2 = $this -> getYeJiMoney($v['id']);
            $money = $money - $money1 - $money2;
			dump($money);
            $res = $model -> where(['id' => $v['id']]) -> setInc('money',$money);
            $res = $model -> where(['id' => $v['id']]) -> setField('sy_money',$money);
            if($res){
                $data['user_id'] = $v['id'];
                $data['money'] = $money;
                $data['time'] = time();
                $data['type'] = 2;
                M('fanyong') -> add($data);
            }
            $model -> where(['id' => $v['id']]) -> setField('zongjifen',0);
//            $model -> where(['id' => $v['id']]) -> setField('old',$v['yeji']);
        }
    }
	
	//下级业绩相加
    public function getJIfen($user_id,$zongjifen){
        $a = M('user') -> where(['sj_userid' => $user_id]) -> sum('zongjifen');
      
        if($a){
            $zongjifen = $a + $zongjifen;
        }
        return $zongjifen;
    }
	
	
	//直推人A的业绩相加
	public function getZtJifen($user_id,$zongjifen){
		$model = M('user');
		$arr = M('user') -> where(['zt_userid' => $user_id]) -> select();
		if(!$arr){
			return $zongjifen;
		}
		foreach($arr as $k => $v){
			//得到每个直推人A的下级的业绩和自身的业绩
			$zongjifen += $this -> getJIfen($v['id'],$v['zongjifen']);
			$zongjifen = $this -> getZtJifen($v['id'],$zongjifen);
			$zongjifen = $this -> getYjJifen($v['id'],$zongjifen);
		}
        return $zongjifen;
	}
	
	//升级后和我有关系的人
	public function getYjJifen($user_id,$zongjifen){
		$model = M('user');
		$arr = M('user') -> where(['yeji_userid' => $user_id]) -> select();
		if(!$arr){
			return $zongjifen;
		}
		foreach($arr as $k => $v){
			//得到每个直推人A的下级的业绩和自身的业绩
			$zongjifen += $this -> getJIfen($v['id'],$v['zongjifen']);
			$zongjifen = $this -> getZtJifen($v['id'],$zongjifen);
			$zongjifen = $this -> getYjJifen($v['id'],$zongjifen);
		}
        return $zongjifen;
	}
	
    public function getZtMoney($user_id){
        $a = M('user') -> where(['zt_userid' => $user_id]) -> select();
        if(!$a){
            return 0;
        }   
        $money = 0;
        foreach ($a as $k => $v){
            if($v['type'] == 1){
                $zongjifen = $this -> getJIfen($v['id'],$v['zongjifen']);
				$zongjifen = $this -> getZtJifen($v['id'],$zongjifen);
				$zongjifen = $this -> getYjJifen($v['id'],$zongjifen);
				$money1 = $this -> getFanyong($zongjifen);
                $money2 = $this -> getZtMoney($v['id']);
				$money3 = $this -> getYeJiMoney($v['id']);
                $money = $money1 - $money2 - $money3;
            }
        }
        return $money;
    }
	
	public function getYeJiMoney($user_id){
		 $a = M('user') -> where(['yeji_userid' => $user_id]) -> select();
        if(!$a){
            return 0;
        }   
        $money = 0;
        foreach ($a as $k => $v){
            if($v['type'] == 1){
                $zongjifen = $this -> getJIfen($v['id'],$v['zongjifen']);
				$zongjifen = $this -> getZtJifen($v['id'],$zongjifen);
				$zongjifen = $this -> getYjJifen($v['id'],$zongjifen);
				$money1 = $this -> getFanyong($zongjifen);
                $money2 = $this -> getZtMoney($v['id']);
                $money3 = $this -> getYeJiMoney($v['id']);
                $money = $money1 - $money2 - $money3;
            }
        }
        return $money;
	}

	
	
	
	//修改后的逻辑  返利 = （总代当月进货金额 + 总代下面的所有总代的当月进货金额） /  比例   
	
	public function test1(){
		$model = M('user');
        $data = $model -> field('id,dy_yeji') ->  where(['type' => 1]) -> select();
		// $year = date('Y',time());
		// $month = date('m',time());
		// if($month == 1){
			// $m = 12;
			// $year = $year - 1;
		// }else{
			// $m = $month - 1;
		// }
		// $y = $year;
		
		foreach($data as $k => $v){
			$money = 0;
			//计算上月的订单进货总额
			// $da = M('order') -> field('time,shopmoney') ->  where(['user_id' => $v['id'],'state' => 1,'type' => 1]) -> select();
			// foreach($da as $k1 => $v1){
				// $ye = date('Y',$v1['time']);
				// $mo = date('m',$v1['time']);
				// if($y == $ye && $m == $mo){
					// $money += $v1['shopmoney'];
				// }
			// }
			//file_put_contents('E:/lijiafei1.txt',$money);
			//计算他的下级的当月的订货订单
			$money = $v['dy_yeji'];
			$dat = M('user') -> field('sum(dy_yeji) as yeji') -> where(" zt_userid = {$v['id']} or yeji_userid = {$v['id']} and type=1") -> select();
			
			// //file_put_contents('E:/lijiafei15634543.txt',$v['id']);
			// foreach($dat as $k2 => $v2){
				// if($v2['type'] == 1){
					// //计算上月的订单进货总额
					// $da1 = M('order') -> field('time,shopmoney') ->  where(['user_id' => $v2['id'],'state' => 1,'type' => 1]) -> select();
					// foreach($da1 as $k3 => $v3){
						// $ye = date('Y',$v3['time']);
						// $mo = date('m',$v3['time']);
						// if($y == $ye && $m == $mo){
							// $money += $v3['shopmoney'];
						// }
					// }
				// }
				
			// }
			$money += $dat[0]['yeji'];
			M('user') -> where(['id' => $v['id']]) -> setField('sy_yeji',$money);
			//file_put_contents('E:/lijiafei.txt',$money);
			$money = $this -> getFanyong($money);
			$res = $model -> where(['id' => $v['id']]) -> setField('sy_money',$money);
			$res = $model -> where(['id' => $v['id']]) -> setField('zongjifen',0);
			$res = $model -> where(['id' => $v['id']]) -> setField('is_fandian',0);
		}
		$sql = "update wx_user set dy_yeji = 0";
		$ml = M();
		$ml -> query($sql);
	}
	
	
	//A的返利 = 团队总业绩（A+B+C）*比例1 - 总代B的业绩 * 比例2 - 总代C的业绩 * 比例3
	public function test2(){
		$model = M('user');
		//得到所有的是总代的人
        $data = $model -> field('id,dy_yeji') ->  where(['type' => 1]) -> select();
		//便利总代
		foreach($data as $k => $v){
			$money = 0;
			//自己的当月提货业绩
			$money = $v['dy_yeji'];
			//得到和自己有关的所有人的提货业绩
			$dat = M('user') -> field('sum(dy_yeji) as yeji') -> where(" zt_userid = {$v['id']} or yeji_userid = {$v['id']} or sj_userid = {$v['id']}") -> select();
			$money += $dat[0]['yeji'];
			M('user') -> where(['id' => $v['id']]) -> setField('sy_yeji',$money);
			$money = $this -> getFanyong($money);
			//计算下级的业绩
			$money -= $this -> getYeji($v['id']);
			if($money > 0){
				$res = $model -> where(['id' => $v['id']]) -> setField('sy_money',$money);
				$res = $model -> where(['id' => $v['id']]) -> setField('zongjifen',0);
				$res = $model -> where(['id' => $v['id']]) -> setField('is_fandian',0);
			}else{
				$res = $model -> where(['id' => $v['id']]) -> setField('sy_money',0);
				$res = $model -> where(['id' => $v['id']]) -> setField('zongjifen',0);
				$res = $model -> where(['id' => $v['id']]) -> setField('is_fandian',0);
			}
		}
		$sql = "update wx_user set dy_yeji = 0";
		$ml = M();
		$ml -> query($sql);
	}
	
	public function getYeji($user_id){
		$a = 0;
		//下级是否有总代的人
	$data = M('user') -> field('id,dy_yeji') -> where("zt_userid = {$user_id} or yeji_userid = {$user_id} and type = 1") -> select();
		if(!$data){
			return 0;
		}
		//下级有总代，则计算下级的钱数
		foreach($data as $k => $v){
			$money = 0;
			//自己的当月提货业绩
			$money = $v['dy_yeji'];
			//得到和自己有关的所有人的提货业绩
			$dat = M('user') -> field('sum(dy_yeji) as yeji') -> where(" zt_userid = {$v['id']} or yeji_userid = {$v['id']} or sj_userid = {$v['id']}") -> select();
			$money += $dat[0]['yeji'];
			// M('user') -> where(['id' => $v['id']]) -> setField('sy_yeji',$money);
			//计算下级的业绩
			$money = $this -> getFanyong($money);
			$money -=$this -> getYeji($v['id']);
			if($money > 0){
				// $res = $model -> where(['id' => $v['id']]) -> setField('sy_money',$money);
				// $res = $model -> where(['id' => $v['id']]) -> setField('zongjifen',0);
				// $res = $model -> where(['id' => $v['id']]) -> setField('is_fandian',0);
				$a += $money;
			}
		}
		return $a;
	}
	
	
	public function test3(){
		$data = M('user') -> where("type = 1") -> select();
		$model = M('user');
		foreach($data as $k => $v){
			$js_yeji = $this -> getDYYj1($v['id']);
			$model -> where("id = {$v['id']}") -> setField('js_yeji',$js_yeji);
			$model -> where("id = {$v['id']}") -> setField('sy_yeji',$js_yeji);
			$res = $model -> where(['id' => $v['id']]) -> setField('zongjifen',0);
			$res = $model -> where(['id' => $v['id']]) -> setField('is_fandian',0);
		}
		$data1 = M('user') -> where("type = 1") -> select();
		foreach($data1 as $k => $v){
			//计算自己的业绩和下级的业绩
			$money = $this -> getFanyong($v['js_yeji']);
			//计算下级的业绩
			$dat = $model -> where(" zt_userid = {$v['id']} or yeji_userid = {$v['id']} and type = 1") -> select();
			$a = 0;
			foreach($dat as $key => $val){
				$a += $this -> getFanyong($val['js_yeji']);
			}
			$money = $money - $a;
			if($money > 0){
				$res = $model -> where(['id' => $v['id']]) -> setField('sy_money',$money);
				
			}else{
				$res = $model -> where(['id' => $v['id']]) -> setField('sy_money',0);
			}
		}
		$sql = "update wx_user set dy_yeji = 0";
		$ml = M();
		$ml -> query($sql);
	}
	
	//总代计算自己的和自己有关的  其它的计算自己的和下级的
	private function getDYYj1($user_id){
		$type = M('user') -> getFieldById($user_id,'type');
		if($type == 1){
			$yeji = M('user') -> getFieldById($user_id,'dy_yeji');
			//计算和自己有关的总代的业绩
			$data = M('user') -> where(" zt_userid = {$user_id} or yeji_userid = {$user_id} or sj_userid = {$user_id}") -> select();
			foreach($data as $k => $v){
				 if($v['type'] == 1){
					$yeji += M('user') -> getFieldById($v['id'],'dy_yeji');
					$yeji += $this -> getyeji1($v['id']);
				}
			}
			// $dat = M('user')
				// -> field('sum(dy_yeji) as yeji')
				// -> where(" zt_userid = {$this->user_id} or yeji_userid = {$this->user_id} or sj_userid = {$this->user_id} ") 
				// -> select();
		}else{
			$yeji = M('user') -> getFieldById($this->user_id,'dy_yeji');
			$data = M('user') -> where(" sj_userid = {$user_id}") -> select();
			foreach($data as $k => $v){
				$yeji += M('user') -> getFieldById($v['id'],'dy_yeji');
				$yeji += $this -> getyeji1($v['id']);
			}
			// $dat = M('user')
				// -> field('sum(dy_yeji) as yeji')
				// -> where(" sj_userid = {$this->user_id} ") 
				// -> select();
		}
			
			
			// return $dat[0]['yeji'] + $yeji;
			 return $yeji;
		// }
	}
	
	
	
	public function getyeji1($user_id){
		$type = M('user') -> getFieldById($user_id,'type');
		$data = M('user') -> where(" zt_userid = {$user_id} or yeji_userid = {$user_id} or sj_userid = {$user_id} and type = 1") -> select();
		if(!$data){
			return 0;
		}
		$money = 0;
		if($type == 1){
			$dat = M('user')
				-> field('sum(dy_yeji) as yeji')
				-> where(" zt_userid = {$user_id} or yeji_userid = {$user_id} or sj_userid = {$user_id} and type = 1") 
				-> select();
			$data = M('user') -> where(" zt_userid = {$user_id} or yeji_userid = {$user_id} or sj_userid = {$user_id} and type = 1") -> select();
		}else{
			$dat = M('user')
				-> field('sum(dy_yeji) as yeji')
				-> where(" sj_userid = {$user_id} ") 
				-> select();
			$data = M('user') -> where(" sj_userid = {$user_id}") -> select();
		}
		$money += $dat[0]['yeji'];
		foreach($data as $k => $v){
			$money += $this -> getyeji1($v['id']);
		}
		
		return $money;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
    public function getFanyong($yeji){
        $fanyong = 0;
        if($yeji >= 10000 && $yeji < 20000){
            $fanyong = $yeji * 0.01;
        }else if($yeji >= 20000 && $yeji < 30000){
            $fanyong = $yeji * 0.02;
        }else if($yeji >= 30000 && $yeji < 50000){
            $fanyong = $yeji * 0.03;
        }else if($yeji >= 50000 && $yeji < 100000){
            $fanyong = $yeji * 0.04;
        }else if($yeji >= 100000 && $yeji < 150000){
            $fanyong = $yeji * 0.05;
        }else if($yeji >= 150000 && $yeji < 200000){
            $fanyong = $yeji * 0.06;
        }else if($yeji >= 200000 && $yeji < 300000){
            $fanyong = $yeji * 0.07;
        }else if($yeji >= 300000 && $yeji < 400000){
            $fanyong = $yeji * 0.08;
        }else if($yeji >= 400000 && $yeji < 500000){
            $fanyong = $yeji * 0.09;
        }else if($yeji >= 500000 && $yeji < 800000){
            $fanyong = $yeji * 0.1;
        }else if($yeji >= 800000 && $yeji < 1000000){
            $fanyong = $yeji * 0.11;
        }else if($yeji >= 1000000){
            $fanyong = $yeji * 0.12;
        }
        return $fanyong;
    }
}