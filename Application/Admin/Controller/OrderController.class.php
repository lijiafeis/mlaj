<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/17 0017
 * Time: 10:04
 */
namespace Admin\Controller;
use Think\Controller;
use Think\Pageajax;

class OrderController extends Controller{

    //订单列表展示
    public function orderList(){
        $this -> display();
    }

    public function orderbb(){
        $order_sn = $_GET['order_sn'];
        $state = $_GET['state'];
        $type = $_GET['type'];
        $model = M('order');
     
        $where = array();
        if($order_sn){
            $where["order_sn"] = $order_sn;
        }
        if($state >= 0){
            $where["state"] = $state;
        }
        if($type >= 0){
            $where["type"] = $type;
        }
        $count= $model->where($where)->count();
        $Page = $this -> setPage($count);
        $arr = $model
            -> alias("a")
            -> field("a.*,a.id as order_id,c.name,c.tel,c.address,c.address1,c.youbian")
            -> join("left join __ADDRESS__ as c on a.address_id = c.id")
//            -> join("left join __USER__ as b on a.user_id = b.id")
            ->limit($Page->firstRow.','.$Page->listRows)
            -> where($where)
            -> order("a.time desc")
            -> select();
        $is_fh = $_GET['order'];
        //获取上级姓名
        foreach ($arr as $k => $v){
            $sj_userid = M('user') -> getFieldById($v['user_id'],'sj_userid');
                if($sj_userid == 0){
                    $arr[$k]['sj_name'] = "平台";
                }else{
                    $arr[$k]['sj_name'] = M('user') -> getFieldById($sj_userid,'name');
                }
				if(!$v['name']){
					$arr[$k]['name'] = M('user') -> getFieldById($v['user_id'],'name');
				}
        }
		//dump($arr);exit;
        $this->assign('arr',$arr);
        // 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();
        $this->assign('count',$count);
        $this->assign('page',$show);
        $this->assign('empty','<tr><td colspan="9" style="text-align:center;line-height:40px;">没有查询到相关信息</td></tr>');
        $this->display();
    }

    public function getDataForDate(){
        $startTime = $_GET['starttime'];
        $endTime = $_GET['endtime'];
        $startTime = strtotime($startTime);
        $endTime = strtotime($endTime);
        $model = M('order');
        $where['time'] = ['between',[$startTime,$endTime]];
        $count= $model->where($where)->count();
        $Page = $this -> setPage($count);
        $arr = $model
            -> alias("a")
            -> field("a.*,a.id as order_id,c.*")
            -> join("left join __ADDRESS__ as c on a.address_id = c.id")
            ->limit($Page->firstRow.','.$Page->listRows)
            -> where($where)
            -> order("a.time desc")
            -> select();
        $is_fh = $_GET['order'];
        //获取上级姓名
        foreach ($arr as $k => $v){
            $sj_userid = M('user') -> getFieldById($v['user_id'],'sj_userid');
//            dump($sj_userid);
            if($is_fh){
                if($sj_userid == 0){
                    $arr[$k]['sj_name'] = "平台";
                }else{
                    unset($arr[$k]);
                }
            }else{
                if($sj_userid == 0){
                    $arr[$k]['sj_name'] = "平台";
                }else{
                    $arr[$k]['sj_name'] = M('user') -> getFieldById($sj_userid,'name');
                }
            }
        }


        $this->assign('arr',$arr);
        // 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();
        $this->assign('count',$count);
        $this->assign('page',$show);
        $this->assign('empty','<tr><td colspan="9" style="text-align:center;line-height:40px;">没有查询到相关信息</td></tr>');
        $this->display('orderbb');
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

    //删除订单
    public function delete(){
        $id = $_POST['id'];
        $res = M('order') -> delete($id);
        if($res){
            echo 0;
        }else{
            echo 1;
        }
    }

    //订单详情
    public function xiangqing(){
        if(IS_GET){
            $pay_id = $_GET['id'];
            //订单详情
//            dump($pay_id);
            $order = M('order') -> getById($pay_id);
            $sj_userid = M('user') -> getFieldById($order['user_id'],'sj_userid');
            $user_address = M('address') -> find($order['address_id']);
            $order_info = M('shopping') -> where(" order_id = '$pay_id' ") -> select();
            //$this->assign("hexiao_info",$hexiao_info);
            $this->assign("order",$order);
            $this->assign("user_address",$user_address);
            $this->assign("order_info",$order_info);
            $this->assign("sj_userid",$sj_userid);
            $this -> display();
        }else{
            $order_id = $_POST['id'];
            $order_sn = $_POST['order_sn'];
            //修改订单表中的状态
            $data['type'] = 1;
            $data['order_sn'] = $order_sn;
            $data['kd_number'] = $_POST['kd'];
            $res = M('order') -> where(['id' => $order_id]) -> save($data);
            if($res){
                //记录到发货表中
                $info['user_id'] = 0;
                $info['order_id'] = $order_id;
                M('order_info') -> add($info);

                $this -> sendTemplate($order_id);
                $this -> success('修改订单状态成功','orderList');
            }else{
                $this -> error('修改订单状态失败','orderList');
            }
        }

    }

    public function sendTemplate($order_id){
        /* 发送模板消息通知 */
        //获取订单号
        $order_sn = M('order') -> getFieldById($order_id,'order_sn');
        $address_id = M('order') -> getFieldById($order_id,'address_id');
        $address = M('address') -> getFieldById($address_id,'address');
        //获取openid
        $user_id = M('order') -> getFieldById($order_id,'user_id');
        $u_id = M('user') -> getFieldById($user_id,'u_id');
        $openid = M('users') -> getFieldByUser_id($u_id,'openid');
        //获取收货信息
        $address_id = M('order') -> getFieldById($order_id,'address_id');
        $address_info = M('address') -> find($address_id);
        //获取快递名
        $kd_number = M('order') -> getFieldById($order_id,'kd_number');
        $kd_name = '';
        switch ($kd_number){
            case 'SF':
                $kd_name = '顺丰快递';
                break;
            case 'STO':
                $kd_name = '申通快递';
                break;
            case 'YD':
                $kd_name = '韵达快递';
                break;
            case 'YTO':
                $kd_name = '圆通速递';
                break;
            case 'HHTT':
                $kd_name = '天天快递';
                break;
            case 'ZTO':
                $kd_name = '中通速递';
                break;
            case 'AMAZON':
                $kd_name = '亚马逊物流';
                break;
        }
        $template = A("Pay/Template");
        $url = 'http://'.$_SERVER['SERVER_NAME'].U('/Home/Center/getWuliu')."?order_sn=$order_sn&kd=$kd_number";
//        file_put_contents('b.txt',$kd_name . ',' . $order_sn);
        $template->send_shop($kd_name,$order_sn,$address_info['name'],$address_info['tel'],$address_info['address'],$openid,$url,$address);
    }

    public function dchuExcel(){

		//得到当前是在导出的那种的类型。
		$flag = $_GET['flag'];
		$filename = $_GET['filename'];
		$time1 = $_GET['time1'];
		$time2 = $_GET['time2'];
		//dump($time1);
		//dump($time2);
		$time1 = strtotime($time1);
		$time2 = strtotime($time2);
		//dump($flag);
		if(!$flag){
			//$time1 = $_GET['time1'];
			//$time2 = $_GET['time2'];
			$type = $_GET['type'];
			//导数据库中查找数据
			
			$where['a.time'] = ['between',[$time1,$time2]];
			$where['a.state'] = 1;
			if($type >= 0){
				$where['a.type'] = $type;
			}
			$data = M('order')
				-> alias("a")
				-> field("a.*,a.time as or_time,b.*,a.type as or_type,c.address as add1,c.address as add2")
				-> join("left join __USER__ as b on a.user_id = b.id")
				-> join("left join __ADDRESS__ as c on a.address_id = c.id")
				-> where($where)
				-> order("a.time desc")
				-> select();
		}else{
			$data = M('tihuo')
				-> alias('d')
				-> field("a.*,a.time as or_time,b.*,a.type as or_type,c.address as add1,c.address as add2")
				-> join("left join __ORDER__ as a on d.order_id = a.id")
				-> join("left join __USER__ as b on a.user_id = b.id")
				-> join("left join __ADDRESS__ as c on a.address_id = c.id")
				-> where(['d.type' => 0,'a.time' => ['between',[$time1,$time2]]])
				-> order("d.time desc")
				-> select();
		}
        //dump($data);
		//exit;
        //导入Excel使用的类库
        $flag = $this -> push($data,$filename);
        echo $flag;
    }
    /* 导出excel函数*/
    public function push($data,$name='Excel'){

        $name = iconv("UTF-8","GBK",$name);
        error_reporting(E_ALL);
        date_default_timezone_set('Europe/London');
        require_once "Xigua/Library/Vendor/PHPExcel/PHPExcel.php";
        require_once "Xigua/Library/Vendor/PHPExcel/PHPExcel/IOFactory.php";
        require_once "Xigua/Library/Vendor/PHPExcel/PHPExcel/Reader/Excel5.php";
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel -> setActiveSheetIndex(0);
        $objSheet = $objPHPExcel -> getActiveSheet();
        $objSheet -> setCellValue("A1","用户id")
            -> setCellValue("B1","姓名")
           // -> setCellValue("C1","微信号")
            -> setCellValue("C1","电话")
            -> setCellValue("D1","地址")
            -> setCellValue("E1","订单号")
            -> setCellValue("F1","价格")
            -> setCellValue("G1","是否发货")
            -> setCellValue("H1","发货地址")
            -> setCellValue("I1","时间")
            -> setCellValue("J1","备注")
            -> setCellValue("K1","商品");
        $j = 2;
        foreach ($data as $k => $v){
            $shop_order = M('shopping') -> where("order_id = {$v['id']}") -> select();
            $str = '';
            foreach ($shop_order as $k1 => $v1){
                $str = "【{$v['shop_name']} 数量：{$v1['gmnumber']} 规格：{$v1['size']} 单位：{$v1['danwei']}】";
            }
			switch($v['or_type']){
				case 0:
					$type = '未发货';
					break;
				case 1:
					$type = '已发货';
					break;
			}
            $objSheet -> setCellValue("A".$j,$v['id'])
                -> setCellValue("B".$j,$v['name'])
               // -> setCellValue("C".$j,$v['wxname'])
                -> setCellValue("C".$j,$v['tel'])
                -> setCellValue("D".$j,$v['address'])
                -> setCellValue("E".$j,$v['order_sn'])
                -> setCellValue("F".$j,$v['shopmoney'] . '积分')
                -> setCellValue("G".$j,$type)
                -> setCellValue("H".$j,$v['add1'] . $v['add2'])
                -> setCellValue("I".$j,date("Y-m-d H:i:s",$v['or_time']))
                -> setCellValue("J".$j,$v['content'])
                -> setCellValue("K".$j,$str);
            $j++;
        }
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $path = "Uploads/xls";
        if(!is_dir($path)){
            mkdir($path);
        }
        $res = $objWriter->save("$path/$name.xls");
//        file_put_contents('3423.txt',$res);
        $file_xls = dirname(dirname(dirname(__DIR__))) . "\\" . $path . "\\" . $name.".xls";    //   文件的保存路径

        $example_name=basename($file_xls);  //获取文件名


        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.mb_convert_encoding($example_name,"gb2312","utf-8"));  //转换文件名的编码
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_xls));
        ob_clean();
        flush();
        readfile($file_xls);
        return 1;
    }

    public function isFH(){
        $order_id = $_POST;
        $type = M('order') -> find($order_id);
        $type = $type['type'];
        file_put_contents('type.txt',$type);
        if($type == 0){
            echo 0;
        }else{
            echo 1;
        }
    }

    //显示物流信息
    public function showWuliu(){
        $order_id = $_GET['order_id'];
        $res = M('order') -> find($order_id);
        if($res['type'] == 0){
            $this -> error('该订单还没有发货','orderList');
            exit;
        }
        $kd = A("Wxapi/Kuaidi");
        $data = $kd -> getMessage($res['kd_number'],$res['order_sn']);
		//dump($data);
		//exit;
        $data = json_decode($data);
        $arr = $this -> infoToArray($data);
//        dump($arr);

        $this -> assign('data',$arr[0]);
        $this -> assign('info',$arr[1]);
        $this -> display();
    }

    public function infoToArray($data){
        $arr = array();
        $arr['ddh'] = $data -> LogisticCode;
        $ress = array();
        switch ($data -> ShipperCode){
            case 'SF':
                $arr['kd'] = '顺丰快递';
                break;
            case 'STO':
                $arr['kd'] = '申通快递';
                break;
            case 'YD':
                $arr['kd'] = '韵达快递';
                break;
            case 'YTO':
                $arr['kd'] = '圆通速递';
                break;
            case 'ZJS':
                $arr['kd'] = '宅急送';
                break;
            case 'ZTO':
                $arr['kd'] = '中通速递';
                break;
            case 'AMAZON':
                $arr['kd'] = '亚马逊物流';
                break;
        }
        foreach ($data -> Traces as $k => $v){
            $info['time'] = $v -> AcceptTime;
            $info['info'] = $v -> AcceptStation;
            $ress[$k] = $info;
        }
        $re[0] = $arr;
        $re[1] = $ress;
        return $re;
    }

    //转换成数组
    private function objectToArray($e){
        $e=(array)$e;
        foreach($e as $k=>$v){
            if( gettype($v)=='resource' ) return;
            if( gettype($v)=='object' || gettype($v)=='array' )
                $e[$k]=(array)$this->objectToArray($v);
        }
        return $e;
    }
	
	public function findWuliu(){
		if(IS_GET){
			$this -> display();
		}else{
			$order_sn = $_POST['order_sn'];
			$kd_number = $_POST['kd_number'];
			 $kd = A("Wxapi/Kuaidi");
			if($kd_number == 'HHTT'){
				$arr = $kd -> getData($kd_number,$order_sn);
			}else{
				$data = $kd -> getMessage($kd_number,$order_sn);
				$data = json_decode($data);
				$arr = $this -> infoToArray($data);
			}
            $this -> assign('data',$arr[0]);
            $this -> assign('info',$arr[1]);
            $this -> display('showWuliu');
		}
		
	}

    //代理订单查询
    public function dailiList(){
	    $this -> display();
    }

    public function dailibb(){
		//显示不全,在购买后,没有选择收货地址,而是在.
		
        $user_id = $_GET['user_id'];
        $name = $_GET['name'];
        $model = M('order_info');
        //获取所有的试题信息
//        $where = array();
        if($user_id){
            $count= $model-> where(['user_id' => $user_id]) -> count();
            $Page = $this -> setPage($count);
            $arr = $model
                -> alias("a")
                -> field("a.*,b.*,a.time as dalitime,c.name,d.name as dlname,a.user_id as dlid,e.name as ename")
                -> join("left join __ORDER__ as b on a.order_id = b.id")
               -> join('left join __ADDRESS__ as c on b.address_id = c.id')
               -> join('left join __USER__ as e on b.user_id = e.id')
                -> join('left join __USER__ as d on a.user_id = d.id')
                -> limit($Page->firstRow.','.$Page->listRows)
                -> where(['a.user_id' => $user_id])
                -> order("a.time desc")
                -> select();
        }else{
            if(!$name){
                $count= $model -> count();
                $Page = $this -> setPage($count);
                $arr = $model
                    -> alias("a")
                    -> field("a.*,b.*,a.time as dalitime,c.name,d.name as dlname,a.user_id as dlid,e.name as ename")
                    -> join("left join __ORDER__ as b on a.order_id = b.id")
                    -> join('left join __ADDRESS__ as c on b.address_id = c.id')
                    -> join('left join __USER__ as d on a.user_id = d.id')
                    -> join('left join __USER__ as e on b.user_id = e.id')
                    -> limit($Page->firstRow.','.$Page->listRows)
                    -> order("a.time desc")
                    -> select();
            }else{
//            $count= $model -> count();
                $count = $model
                    -> alias("a")
                    -> join('left join __USER__ as d on a.user_id = d.id')
                    -> where(['d.name' => $name])
                    -> order("a.time desc")
                    -> count();
                $Page = $this -> setPage($count);
                $arr = $model
                    -> alias("a")
                    -> field("a.*,b.*,a.time as dalitime,c.name,d.name as dlname,a.user_id as dlid,e.name as ename")
                    -> join("left join __ORDER__ as b on a.order_id = b.id")
                    -> join('left join __ADDRESS__ as c on b.address_id = c.id')
                    -> join('left join __USER__ as d on a.user_id = d.id')
					 -> join('left join __USER__ as e on b.user_id = e.id')
                    -> limit($Page->firstRow.','.$Page->listRows)
                    -> where(['d.name' => $name])
                    -> order("a.time desc")
                    -> select();
            }
        }


       // dump($arr);
		
        $this->assign('arr',$arr);
        // 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();
        $this->assign('count',$count);
        $this->assign('page',$show);
        $this->assign('empty','<tr><td colspan="9" style="text-align:center;line-height:40px;">没有查询到相关信息</td></tr>');
        $this->display();
    }
	
	public function dailiKuCunList(){
		$this -> display();
	}
	
	public function dailiKuCunbb(){
		$user_id = $_GET['user_id'];
        $name = $_GET['name'];
        $model = M('dl_kucun');
        //获取所有的试题信息
//        $where = array();
        if($user_id){
            $count= $model-> where(['user_id' => $user_id]) -> count();
            $Page = $this -> setPage($count);
            $arr = $model
                -> alias("a")
                -> field("a.*,b.name,c.title,c.type")
				-> join("left join __USER__ as b on a.user_id = b.id")
				-> join("left join __SHOP__ as c on a.shop_id = c.id")
				-> order('a.user_id')
				-> where(['user_id' => $user_id,'a.user_id' => ['neq',0]])
				-> limit($Page->firstRow.','.$Page->listRows)
				-> select();
        }else{
            if(!$name){
                $count= $model -> count();
                $Page = $this -> setPage($count);
                $arr = $model
                -> alias("a")
                -> field("a.*,b.name,c.title,c.type")
				-> join("left join __USER__ as b on a.user_id = b.id")
				-> join("left join __SHOP__ as c on a.shop_id = c.id")
				-> order('a.user_id desc')
				-> where(['a.user_id' => ['neq',0]])
				-> limit($Page->firstRow.','.$Page->listRows)
				-> select();
            }else{
//            $count= $model -> count();
                $count = $model
                    -> alias("a")
                    -> join('left join __USER__ as d on a.user_id = d.id')
                    -> where(['d.name' => $name])
                    -> order("a.user_id desc")
                    -> count();
                $Page = $this -> setPage($count);
                $arr = $model
                    -> alias("a")
                    -> field("a.*,b.name,c.title,c.type")
                   -> join("left join __USER__ as b on a.user_id = b.id")
					-> join("left join __SHOP__ as c on a.shop_id = c.id")
                    -> limit($Page->firstRow.','.$Page->listRows)
                    -> where(['b.name' => $name,'a.user_id' => ['neq',0]])
                    -> order("a.user_id desc")
                    -> select();
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

    public function tihuo(){
        $this -> display();
    }

    public function tihuobb(){
        $model = M('tihuo');
        $where = array();
        $count= $model-> where(['type' => 0]) -> count();
        $Page = $this -> setPage($count);
        $arr = $model
            -> alias("b")
            -> field("b.type as btype,a.*,a.id as order_id,c.name,c.tel,c.address,c.address1,c.youbian")
            -> join("left join __ORDER__ as a on a.id = b.order_id")
            -> join("left join __ADDRESS__ as c on a.address_id = c.id")
//            -> join("left join __USER__ as b on a.user_id = b.id")
            ->limit($Page->firstRow.','.$Page->listRows)
            -> where(['b.type' => 0])
            -> order("b.time desc")
            -> select();
        $is_fh = $_GET['order'];
        //获取上级姓名
        foreach ($arr as $k => $v) {
            $sj_userid = M('user')->getFieldById($v['user_id'], 'sj_userid');
            if ($sj_userid == 0) {
                $arr[$k]['sj_name'] = "平台";
            } else {
                $arr[$k]['sj_name'] = M('user')->getFieldById($sj_userid, 'name');
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

    //提货的详情商品信息
    public function tihuoxiangqing(){
        if(IS_GET){
            $pay_id = $_GET['id'];
            //订单详情
//            dump($pay_id);
            $order = M('order') -> getById($pay_id);
            $sj_userid = M('user') -> getFieldById($order['user_id'],'sj_userid');
            $user_address = M('address') -> find($order['address_id']);
            $order_info = M('shopping') -> where(" order_id = '$pay_id' ") -> select();
            //$this->assign("hexiao_info",$hexiao_info);
			$user_name = M('user') -> getFieldById($order['user_id'],'name');
			$this->assign("user_name",$user_name);
            $this->assign("order",$order);
            $this->assign("user_address",$user_address);
            $this->assign("order_info",$order_info);
            $this->assign("sj_userid",$sj_userid);
            $this -> display();
        }else{
            $order_id = $_POST['id'];
            $order_sn = $_POST['order_sn'];
            //修改订单表中的状态
            $data['type'] = 1;
            $data['order_sn'] = $order_sn;
            $data['kd_number'] = $_POST['kd'];
            $res = M('order') -> where(['id' => $order_id]) -> save($data);
            if($res){
                //记录到发货表中
                $info['user_id'] = 0;
                $info['order_id'] = $order_id;
                M('order_info') -> add($info);

                $this -> sendTemplate($order_id);
                $this -> success('修改订单状态成功','orderList');
            }else{
                $this -> error('修改订单状态失败','orderList');
            }
        }
    }

    public function tihuofahuo(){
        $order_id = $_POST['order_id'];
        $kd_number = $_POST['kd_number'];
        $order_sn = $_POST['order_sn'];
        M('tihuo') -> where(['order_id' => $order_id]) -> setField('type',1);
		//把金额记录到当前用户的表中
		$shopmoney = M('order') -> getFieldById($order_id,'shopmoney');
		$user_id = M('order') -> getFieldById($order_id,'user_id');
		
        $data['type'] = 1;
        $data['kd_number'] = $kd_number;
        $data['order_sn'] = $order_sn;
        $res = M('order') -> where(['id' => $order_id]) -> save($data);
        if($res){
			//M('user') -> where(" id = {$user_id}") -> setInc('dy_yeji',$shopmoney);
			//发送模板消息通知
			$this -> sendTemplate($order_id);
            echo 0;
        }else{
            echo -1;
        }
    }
	
}