<?php
/*
* $Author ：PHPYUN开发团队
*
* 官网: http://www.phpyun.com
*
* 版权所有 2009-2018 宿迁鑫潮信息技术有限公司，并保留所有权利。
*
* 软件声明：未经授权前提下，不得用于商业运营、二次开发以及任何形式的再次发布。
 */
class statis_income_controller extends adminCommon{

	 

 
	private $typeMapping = array(
		1 => '会员充值（购买会员）',
		2 => '积分充值',
		3 => '银行转帐',
		4 => '金额充值',
		5 => '购买增值包',
		7 => '购买小程序',
		8 => '分享红包推广',
		9 => '悬赏红包',
		10 => '职位置顶',
		11 => '职位紧急',
		12 => '职位推荐',
		13 => '自动刷新',
		14 => '简历置顶',
		15 => '委托简历',
		16 => '刷新职位',
		17 => '刷新兼职',
		18 => '刷新猎头职位',
		19 => '下载简历',
		20 => '发布职位',
		21 => '发布兼职',
		22 => '发布猎头职位',
		23 => '面试邀请',
		24 => '兼职推荐'
	);

 
	private function getTotal($timeBegin = '', $timeEnd = '')
	{
	 
		$where = 'order_state = 2'; 
		if($timeBegin != ''){
			$where .= " and order_time >= {$timeBegin} and order_time <= {$timeEnd}";
		}
		$field = 'sum(order_price) as `num`';
		
		$row = $this->obj->DB_select_all('company_order', $where, $field);
		$in = isset($row[0]['num']) && $row[0]['num'] > 0 ? $row[0]['num'] : 0;
		
		 
		$where = 'order_state = 1'; 
		if($timeBegin != ''){
			$where .= " and `time` >= {$timeBegin} and `time` <= {$timeEnd}";
		}
		$field = 'sum(order_price) as `num`';
		
		$row = $this->obj->DB_select_all('member_withdraw', $where, $field);
		$out = isset($row[0]['num']) && $row[0]['num'] > 0 ? $row[0]['num'] : 0;

	 
		$net_income = $in - $out;

		return array($in, $out, $net_income);
	}

 
	public function index_action()
	{
		 
		$timeEnd = strtotime(date('Y-m-d 00:00:00', time()));
		list($in, $out, $net_income) = $this->getTotal( strtotime('-30 day', $timeEnd), $timeEnd );
		$data [] = array('time' => '近30', 'in' => $in, 'out' => $out, 'net_income' => $net_income);
		$this->yunset('data', $data);

		if(isset($_GET['radio_time'])){
			$this->yunset('radio_time', $_GET['radio_time']);
		}

	 
		$time_begin = isset($_GET['time_begin']) ? $_GET['time_begin'] : 
			date('Y-m-d 00:00:00', strtotime('-30 day'));
		$time_end = isset($_GET['time_end']) ? $_GET['time_end'] :
			date('Y-m-d H:i:s');
		$this->yunset('defaultTimeBegin', $time_begin);
		$this->yunset('defaultTimeEnd', $time_end);

		$isAllTime = isset($_GET['isAllTime']) ? $_GET['isAllTime'] : 0; 

		 
		if($isAllTime != 1){
			$timeBegin = strtotime($time_begin);
			$timeEnd = strtotime($time_end);

			$dateBegin = date('Y-m-d', $timeBegin);
	    $dateEnd = date('Y-m-d', $timeEnd);
	    $title = "收益渠道统计 - {$dateBegin}~{$dateEnd}";
		}
		else{
			$title = "收益渠道统计 - 全部数据";
		}

		$names = array(); 
		$values = array(); 
		
		$where = 'order_state = 2'; 
		if($isAllTime != 1){
			$where .= " and order_time >= {$timeBegin} and order_time <= {$timeEnd}";
		}
		$where .= ' group by `type` order by `num`';
		$field = 'sum(order_price) as `num`, `type`';
			
		$row = $this->obj->DB_select_all('company_order', $where, $field);

		$total = 0;
		foreach($row as $r){
			if(isset($this->typeMapping[$r['type']])){
				$names [] = $this->typeMapping[$r['type']];
				
				$rr['value'] = $r['num'];
				$rr['name'] = $this->typeMapping[$r['type']];

				$values [] = $rr;

				$total += $r['num'];
			}
		}

		$data = array('title' => $title,'names' => $names, 'values' => $values );
		$this->yunset($data);

		$this->yunset('total', $total);

		$this->yuntpl(array('admin/statis_income'));
	}
}
?>