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
class statis_user_controller extends adminCommon{

 
	private $chartType = 'pie';

 
	private $typeMapping = array(
		1 => '会员充值（购买会员）',
		2 => '积分充值',
		3 => '银行转帐',
		4 => '金额充值',
		5 => '购买增值包',
		6 => '课程订购',
		7 => '购买小程序',
		8 => '分享红包推广',
		9 => '悬赏红包',
		10 => '职位置顶',
		11 => '职位紧急',
		12 => '职位推荐',
		13 => '自动刷新',
		14 => '简历置顶',
		15 => '委托简历'
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
		$this->getUser(2);

		$this->yuntpl(array('admin/statis_user'));
	}

 
	public function user_action()
	{
		$this->getUser(1);

		$this->yuntpl(array('admin/statis_user'));
	}

 
	public function lt_action()
	{
		$this->getUser(3);

		$this->yuntpl(array('admin/statis_user'));
	}

 
	public function getUser($userType)
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

		if($userType == 2){
			$user = '企业';
		}
		else if($userType == 1){
			$user = '个人';
		}
		else if($userType == 3){
			$user = '猎头';
		}
		$this->yunset('user', $user);
		
	 
		if($isAllTime != 1){
			$timeBegin = strtotime($time_begin);
			$timeEnd = strtotime($time_end);

			$dateBegin = date('Y-m-d', $timeBegin);
	    $dateEnd = date('Y-m-d', $timeEnd);
	    $title = "消费最多{$user}统计 - {$dateBegin}~{$dateEnd}";
		}
		else{
			$title = "消费最多{$user}统计 - 全部数据";
		}

    $names = array(); 
		$values = array(); 
		
		$topNum = 10; 

		$where = 'order_state = 2'; 
		if($isAllTime != 1){
			$where .= " and order_time >= {$timeBegin} and order_time <= {$timeEnd}";
		}
		$limit = $topNum * 6;
		$where .= " group by `uid` order by `num` desc limit {$limit}";
		$field = 'sum(order_price) as `num`, `uid`';
			
		$row = $this->obj->DB_select_all('company_order', $where, $field);
		 

		$uidArr = array();
		$uidValue = array();
		foreach($row as $r){
			$uidArr [] = $r['uid'];
			$uidValue[$r['uid']] = $r['num'];
		}
		$uidStr = implode(',', $uidArr);

		if($userType == 2){
			$data = $this->obj->DB_select_all('company', "`uid` in ($uidStr) order by field(`uid`,$uidStr) limit {$topNum}",
			'`uid`,`name`');
		}
		else if($userType == 1){
			$data = $this->obj->DB_select_all('resume', "`uid` in ($uidStr) order by field(`uid`,$uidStr) limit {$topNum}",
			'`uid`,`name`');
		}
		else if($userType == 3){
			$data = $this->obj->DB_select_all('lt_info', "`uid` in ($uidStr) order by field(`uid`,$uidStr) limit {$topNum}",
			'`uid`,`realname` as `name`');
		}

		$total = 0;
		foreach($data as $r){
			$names [] = "id:{$r['uid']} {$r['name']}";
			$rr['value'] = $uidValue[$r['uid']];
			$rr['name'] = "id:{$r['uid']} {$r['name']}";

			$values [] = $rr;

			$total += $uidValue[$r['uid']];
		}

		$this->yunset('total', $total);

		$data = array('title' => $title,'names' => $names, 'values' => $values );

		$c = isset($_GET['c']) ? $_GET['c'] : '';
		$this->yunset('gourl', "index.php?m={$_GET['m']}&c={$c}");
		
		$this->yunset($data);
	}

}
?>