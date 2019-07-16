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
class statis_city_controller extends adminCommon{

	 
	private $chartType = 'pie';

	 
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
		$this->yunset('user', '地区');

	 
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
	    $title = "消费最多地区统计 - {$dateBegin}~{$dateEnd}";
		}
		else{
			$title = "消费最多地区统计 - 全部数据";
		}

		$names = array();
		$values = array(); 
		
		$topNum = 10; 

		$where = 'order_state = 2'; 
		if($isAllTime != 1){
			$where .= " and order_time >= {$timeBegin} and order_time <= {$timeEnd}";
		}
		$limit = $topNum * 10;
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

		$data = $this->obj->DB_select_all('company', "`uid` in ($uidStr) and cityid > 0",
		'`uid`,`cityid`,`provinceid`');
		
		$total = 0;

		foreach($data as $r){
			if(array_key_exists($r['cityid'], $values) ){
				$values [$r['cityid']]['value'] += $uidValue[$r['uid']];
			}
			else{
				$values [$r['cityid']]['value'] = $uidValue[$r['uid']];
				$values [$r['cityid']]['pid'] = $r['provinceid'];
				$values [$r['cityid']]['cid'] = $r['cityid'];
			}
		}

		usort($values,'my_sort');

		$city = $this->MODEL('cache')->GetCache(array('city'));

		$arr = array();
		$i = 0;
		foreach($values as $r){
			$names [] = $city['city_name'][$r['pid']] . '-' . $city['city_name'][$r['cid']];
			$rr['value'] = $r['value'];
			$rr['name'] = $city['city_name'][$r['pid']] . '-' . $city['city_name'][$r['cid']];

			$arr [] = $rr;

			$total += $r['value'];

			$i ++;
			if($i == $topNum){
				break;
			}
		}
		$values = $arr;
		$this->yunset('total', $total);
		
		$this->yunset( array('title' => $title,'names' => $names, 'values' => $values ) );
		
		$c = isset($_GET['c']) ? $_GET['c'] : '';
		$this->yunset('gourl', "index.php?m={$_GET['m']}&c={$c}");

		$this->yuntpl(array('admin/statis_user'));
	}

}
?>