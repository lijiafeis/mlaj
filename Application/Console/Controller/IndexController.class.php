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
		//�������е��ܴ�����
        foreach ($data as $k => $v){
			//������������µ�ҵ��
			$zong1 = M('user') -> where(['sj_userid' => $v['id']]) -> sum('zongjifen');
			$zong2 = M('user') -> where(['yeji_userid' => $v['id']]) -> sum('zongjifen');
			$zong = $zong1 + $zong2;
			M('user') -> where(['id' => $v['id']]) -> setField('sy_yeji',$zong);
			
			
//              $arr = $this -> getMoney($v['id']);
//              dump($arr);
            //�Լ����¼������л�����ӣ�
            $zongjifen = $this -> getJIfen($v['id'],$v['zongjifen']);
			//�Լ���ֱ����Ҳ����A��ҵ�����
			$zongjifen = $this -> getZtJifen($v['id'],$zongjifen);
			//�����Ժ���Լ��й�ϵ���˵�ҵ�����
			$zongjifen = $this -> getYjJifen($v['id'],$zongjifen);
			$money = $this -> getFanyong($zongjifen);
			//dump($money);
            //�õ�ֱ�Ƶ��˵�Ǯ
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
	
	//�¼�ҵ�����
    public function getJIfen($user_id,$zongjifen){
        $a = M('user') -> where(['sj_userid' => $user_id]) -> sum('zongjifen');
      
        if($a){
            $zongjifen = $a + $zongjifen;
        }
        return $zongjifen;
    }
	
	
	//ֱ����A��ҵ�����
	public function getZtJifen($user_id,$zongjifen){
		$model = M('user');
		$arr = M('user') -> where(['zt_userid' => $user_id]) -> select();
		if(!$arr){
			return $zongjifen;
		}
		foreach($arr as $k => $v){
			//�õ�ÿ��ֱ����A���¼���ҵ���������ҵ��
			$zongjifen += $this -> getJIfen($v['id'],$v['zongjifen']);
			$zongjifen = $this -> getZtJifen($v['id'],$zongjifen);
			$zongjifen = $this -> getYjJifen($v['id'],$zongjifen);
		}
        return $zongjifen;
	}
	
	//����������й�ϵ����
	public function getYjJifen($user_id,$zongjifen){
		$model = M('user');
		$arr = M('user') -> where(['yeji_userid' => $user_id]) -> select();
		if(!$arr){
			return $zongjifen;
		}
		foreach($arr as $k => $v){
			//�õ�ÿ��ֱ����A���¼���ҵ���������ҵ��
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

	
	
	
	//�޸ĺ���߼�  ���� = ���ܴ����½������ + �ܴ�����������ܴ��ĵ��½����� /  ����   
	
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
			//�������µĶ��������ܶ�
			// $da = M('order') -> field('time,shopmoney') ->  where(['user_id' => $v['id'],'state' => 1,'type' => 1]) -> select();
			// foreach($da as $k1 => $v1){
				// $ye = date('Y',$v1['time']);
				// $mo = date('m',$v1['time']);
				// if($y == $ye && $m == $mo){
					// $money += $v1['shopmoney'];
				// }
			// }
			//file_put_contents('E:/lijiafei1.txt',$money);
			//���������¼��ĵ��µĶ�������
			$money = $v['dy_yeji'];
			$dat = M('user') -> field('sum(dy_yeji) as yeji') -> where(" zt_userid = {$v['id']} or yeji_userid = {$v['id']} and type=1") -> select();
			
			// //file_put_contents('E:/lijiafei15634543.txt',$v['id']);
			// foreach($dat as $k2 => $v2){
				// if($v2['type'] == 1){
					// //�������µĶ��������ܶ�
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
	
	
	//A�ķ��� = �Ŷ���ҵ����A+B+C��*����1 - �ܴ�B��ҵ�� * ����2 - �ܴ�C��ҵ�� * ����3
	public function test2(){
		$model = M('user');
		//�õ����е����ܴ�����
        $data = $model -> field('id,dy_yeji') ->  where(['type' => 1]) -> select();
		//�����ܴ�
		foreach($data as $k => $v){
			$money = 0;
			//�Լ��ĵ������ҵ��
			$money = $v['dy_yeji'];
			//�õ����Լ��йص������˵����ҵ��
			$dat = M('user') -> field('sum(dy_yeji) as yeji') -> where(" zt_userid = {$v['id']} or yeji_userid = {$v['id']} or sj_userid = {$v['id']}") -> select();
			$money += $dat[0]['yeji'];
			M('user') -> where(['id' => $v['id']]) -> setField('sy_yeji',$money);
			$money = $this -> getFanyong($money);
			//�����¼���ҵ��
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
		//�¼��Ƿ����ܴ�����
	$data = M('user') -> field('id,dy_yeji') -> where("zt_userid = {$user_id} or yeji_userid = {$user_id} and type = 1") -> select();
		if(!$data){
			return 0;
		}
		//�¼����ܴ���������¼���Ǯ��
		foreach($data as $k => $v){
			$money = 0;
			//�Լ��ĵ������ҵ��
			$money = $v['dy_yeji'];
			//�õ����Լ��йص������˵����ҵ��
			$dat = M('user') -> field('sum(dy_yeji) as yeji') -> where(" zt_userid = {$v['id']} or yeji_userid = {$v['id']} or sj_userid = {$v['id']}") -> select();
			$money += $dat[0]['yeji'];
			// M('user') -> where(['id' => $v['id']]) -> setField('sy_yeji',$money);
			//�����¼���ҵ��
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
			//�����Լ���ҵ�����¼���ҵ��
			$money = $this -> getFanyong($v['js_yeji']);
			//�����¼���ҵ��
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
	
	//�ܴ������Լ��ĺ��Լ��йص�  �����ļ����Լ��ĺ��¼���
	private function getDYYj1($user_id){
		$type = M('user') -> getFieldById($user_id,'type');
		if($type == 1){
			$yeji = M('user') -> getFieldById($user_id,'dy_yeji');
			//������Լ��йص��ܴ���ҵ��
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