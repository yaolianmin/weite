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
class mysql {
	private $db_host;
	private $db_user;
	private $db_pwd;
	private $db_database;
	private $conn;
	private $result;
	private $sql;
	private $row;
	private $coding;
	private $bulletin = false;
	private $show_error = false;
	private $is_error = false;
	private $def="";
    public $queryCount = 0;
    public $querySQLList = array();
    public $queryTimeList = array();
    public $queryTimeCount = 0;
    public $queryTime  = '';
	
	public function __construct($db_host, $db_user, $db_pwd, $db_database, $conn, $coding,$def="") {
		$this->db_host = $db_host;
		$this->db_user = $db_user;
		$this->db_pwd = $db_pwd;
		$this->db_database = $db_database;
		$this->coding = $coding;
		$this->def=$def;
        $this->queryCount = 0;
        $this->querySQLList = array();
        $this->queryTimeList = array();
        $this->queryTimeCount = 0;
        $this->queryTime  = '';
		
	}

	public function connect() {
	
			if(!$this->conn){
				$this->conn = @mysql_connect($this->db_host, $this->db_user, $this->db_pwd) or die('数据库连接出错!');
				if (!@mysql_select_db($this->db_database, $this->conn)) {
					if ($this->show_error) {
						$this->show_error("数据库不可用：", $this->db_database);
					}
				}
				@mysql_query("SET NAMES $this->coding");
				@mysql_query("SET character_set_connection=utf8,character_set_results=utf8,character_set_client=binary", $this->conn);
			}
		
	}
	
	public function query($sql) {
		$StartTime = $this->GetMicrotime5();
		$this->connect();

		if ($sql == "") {
			$this->show_error("SQL语句错误：", "SQL查询语句为空");
		}
		$this->sql = $sql;
		
		$result = mysql_query($this->sql,$this->conn);
		if (!$result){
			
			if ($this->show_error) {
				$this->show_error("错误SQL语句：", $this->sql);
			}
		} else {
			$this->result = $result;
		}
		
		return $this->result;
	}
    function GetMicrotime5(){
       
         list($usec, $sec) = explode(' ', microtime());

        return ((float)$usec + (float)$sec);
      
    }
    function GetMicrotimeDiff5($queryTime){
       
      
		$StopTime = $this->GetMicrotime5();
        $query_time = sprintf('%.4f',round(($StopTime - $queryTime) * 1000, 1));
        return $query_time;
    }
 
	function DB_query_all($sql,$type='one'){


		$query = $this->query($sql);

		if($type=='all')
		{
			 while($row=$this->fetch_array($query)){$return[]=$row;}

		}else{
			$return=$this->fetch_array($query);
		}

		return $return;
	}
   
	function DB_select_once($tablename, $where = 1, $select = "*") {
		$cachename=$tablename.$where;
		if(!$return=$this->Memcache_set($cachename)){
			$SQL = "SELECT $select FROM " . $this->def . $tablename . " WHERE $where limit 1";
			$query = $this->query($SQL);
			$return=$this->fetch_array($query);
			$this->Memcache_set($cachename,$return);
		}
		return $return;
	}
	function update_all($tablename, $value, $where = 1) {
		$cachename=$tablename.$where;
		if(!$return=$this->Memcache_set($cachename)){
			$SQL = "UPDATE `" . $this->def . $tablename . "` SET $value WHERE $where";
			$query = $this->query($SQL);
			$return=$this->fetch_array($query);
			$this->Memcache_set($cachename,$return);
		}
		return $return;
	}
	function insert_once($tablename, $value) {
		$cachename=$tablename.$value;
		if(!$return=$this->Memcache_set($cachename)){
			$SQL = "INSERT INTO `" . $this->def . $tablename . "` SET ".$value;
			$query = $this->query($SQL);
			$return=$this->fetch_array($query);
			$this->Memcache_set($cachename,$return);
		}
		return $return;
	}
	function Memcache_set($name,$value=""){

		$this->connect();

	}
   
	function select_num($tablename, $where = 1, $select = "*") {
		$cachename=$tablename.$where;
		if(!$return=$this->Memcache_set($cachename)){
			$SQL = "SELECT count($select) FROM " . $this->def . $tablename . " WHERE $where";
			$query = $this->query($SQL);
			while($rs = mysql_fetch_array($query))
			{
				$row = $rs;
			}
			$return=$row[0];
			$this->Memcache_set($cachename,$return);
		}
		return $return;
	}
	
	function select_all($tablename, $where = 1, $select = "*") {
		$row_return=array();
		$SQL = "SELECT $select FROM `" . $this->def . $tablename . "` WHERE $where";
		$query=$this->query($SQL);
        while($row=$this->fetch_array($query)){$row_return[]=$row;}
        return $row_return;
	}
   
	function select_only($tablename, $where = 1, $select = "*") {
        $row_return=array();
        $SQL = "SELECT $select FROM " .$tablename . " WHERE $where";
        $query=$this->query($SQL);
        while($row=$this->fetch_array($query)){$row_return[]=$row;}
        return $row_return;
	}
   
	function select_alls($tablename1,$tablename2, $where = 1, $select = "*") {
        $SQL = "SELECT $select FROM " . $this->def . $tablename1. " as a," . $this->def . $tablename2 . " as b WHERE $where";
        $query=$this->query($SQL);
        while($row=$this->fetch_array($query)){$row_return[]=$row;}
        return $row_return;
	}

	public function create_database($database_name) {
		$database = $database_name;
		$sqlDatabase = 'create database ' . $database;
		$this->query($sqlDatabase);
	}
	function cacheget(){
		include PLUS_PATH."/city.cache.php";
		include PLUS_PATH."/com.cache.php";
		include PLUS_PATH."/job.cache.php";
		include PLUS_PATH."/user.cache.php";
		include PLUS_PATH."/industry.cache.php";
		$array["comclass_name"] = $comclass_name;
		$array["city_name"] = $city_name;
		$array["user_classname"] = $userclass_name;
		$array["job_name"] = $job_name;
		$array["job_type"] = $job_type;
		$array["industry_name"] = $industry_name;
		return $array;
	}
	
	function array_action($job_info,$array=array()){
		if(!empty($array)){
			$comclass_name = $array["comclass_name"];
			$city_name = $array["city_name"];
			$industry_name = $array["industry_name"];
			$job_name = $array["job_name"];
		}else{
			include PLUS_PATH."/city.cache.php";
			include PLUS_PATH."/com.cache.php";
			include PLUS_PATH."/job.cache.php";
			include PLUS_PATH."/industry.cache.php";
		}
		$job_info[job_class_one] = $job_name[$job_info["job1"]];
		$job_info[job_class_two] = $job_name[$job_info[job1_son]];
		$job_info[job_class_three] = $job_name[$job_info[job_post]];
		$job_info[job_exp] = $comclass_name[$job_info["exp"]];
		$job_info[job_edu] = $comclass_name[$job_info[edu]];
		$job_info[job_salary] = $comclass_name[$job_info[salary]];
		$job_info[job_number] = $comclass_name[$job_info[number]];
		$job_info[job_mun] = $comclass_name[$job_info[mun]];
		
		$job_info[job_age] = $comclass_name[$job_info[age]];
		$job_info[job_type] = $comclass_name[$job_info[type]];
		$job_info[job_marriage] = $comclass_name[$job_info[marriage]];
		$job_info[job_report] = $comclass_name[$job_info[report]];
		$job_info[job_city_one] = $city_name[$job_info[provinceid]];
		$job_info[com_city] = $city_name[$job_info[com_provinceid]];
		$job_info[job_pr] = $comclass_name[$job_info[pr]];
		$job_info[job_city_two] = $city_name[$job_info[cityid]];
		$job_info[job_city_three] = $city_name[$job_info[three_cityid]];
		$job_info[job_hy] = $industry_name[$job_info[hy]];
                
		$job_info[edate]=date("Y年m月d日",$job_info[edate]);
		if($job_info[lang]!=""){
			$lang = @explode(",",$job_info[lang]);
			foreach($lang as $key=>$value){
				$langinfo[]=$comclass_name[$value];
			}
			$job_info[lang_info] = @implode(",",$langinfo);
			$job_info[lang] =$lang;
		}else{
			$job_info[lang_info] ="";
		}
		if($job_info[welfare]!=""){
			$welfare = @explode(",",$job_info[welfare]);
			foreach($welfare as $key=>$value){
				
				$welfareinfo[]=$value;
			}
			$job_info[welfare_info] = @implode(",",$welfareinfo);
			$job_info[welfare] =$welfare;
		}else{
			$job_info[welfare_info] ="";
		}
		return $job_info;
	}
	
	function lt_array_action($job_info,$array=array()){
		if(!empty($array)){
			$comclass_name = $array["comclass_name"];
			$ltclass_name = $array["ltclass_name"];
			$city_name = $array["city_name"];
			$lthy_name = $array["lthy_name"];
			$ltjob_name = $array["ltjob_name"];
		}else{
			include PLUS_PATH."/com.cache.php";
			include PLUS_PATH."/city.cache.php";
			include PLUS_PATH."/lt.cache.php";
			include PLUS_PATH."/ltjob.cache.php";
			include PLUS_PATH."/lthy.cache.php";
		}
		$job_info["cityid_info"] = $city_name[$job_info["cityid"]];
		$job_info["three_cityid_info"] = $city_name[$job_info["three_cityid"]];
	
		$job_info["edu_info"] = $ltclass_name[$job_info["edu"]];
		$job_info["exp_info"] = $ltclass_name[$job_info["exp"]];
		$job_info["mun_info"] = $comclass_name[$job_info["mun"]];
		$job_info["pr_info"] = $comclass_name[$job_info["pr"]];
		$job_info["jobone"]=$ltjob_name[$job_info["jobone"]];
		if($job_info["social"]!=""){
			$social = @explode(",",$job_info["social"]);
			foreach($social as $key=>$value){
				$social_info[]=$ltclass_name[$value];
			}
			$job_info["social_info"] = $social_info;
		}else{
			$job_info["social_info"] ="";
		}
		$welfare=@explode(',', $job_info['welfare']);
		if($welfare){
			foreach($welfare as $val){
				$job_info['welfarename'][]=$ltclass_name[$val];
			}	
		}
		return $job_info;
	}
	
	function part_array_action($job_info,$array=array()){
		if(!empty($array)){
			$partclass_name = $array["partclass_name"];
			$city_name = $array["city_name"];
		}else{
			include PLUS_PATH."/city.cache.php";
			include PLUS_PATH."/part.cache.php";
		}
		$job_info['job_salary_type'] = $partclass_name[$job_info['salary_type']];
		
		$job_info['job_type'] = $partclass_name[$job_info['type']];
		$job_info['job_billing_cycle'] = $partclass_name[$job_info['billing_cycle']];
		$job_info['job_city_one'] = $city_name[$job_info['provinceid']];
		$job_info['job_city_two'] = $city_name[$job_info['cityid']];
		$job_info['job_city_three'] = $city_name[$job_info['three_cityid']];
		return $job_info;
	}
	public function show_databases() {
		$this->query("show databases");
		echo "现有数据库：" . $amount = $this->db_num_rows($rs);
		echo "<br />";
		$i = 1;
		while ($row = $this->fetch_array($rs)) {
			echo "$i $row[Database]";
			echo "<br />";
			$i++;
		}
	}

	public function databases() {
		$this->connect();
		$rsPtr = mysql_list_dbs($this->conn);
		$i = 0;
		$cnt = mysql_num_rows($rsPtr);
		while ($i < $cnt) {
			$rs[] = mysql_db_name($rsPtr, $i);
			$i++;
		}
		return $rs;
	}

	public function show_tables($database_name) {
		$this->connect();
		$this->query("show tables");
		echo "现有数据库：" . $amount = $this->db_num_rows($rs);
		echo "<br />";
		$i = 1;
		while ($row = $this->fetch_array($rs)) {
			$columnName = "Tables_in_" . $database_name;
			echo "$i $row[$columnName]";
			echo "<br />";
			$i++;
		}
	}
	
	public function mysql_result_li() {
		$this->connect();
		return mysql_result($str);
	}
	
	public function fetch_array($query="") {
		$this->connect();
		if(!$query){
			return @mysql_fetch_array($this->result);
		}else{
			return @mysql_fetch_array($query);
		}
	}

	public function fetch_assoc() {
		$this->connect();
		return mysql_fetch_assoc($this->result);
	}
	
	public function fetch_row() {
		$this->connect();
		return mysql_fetch_row($this->result);
	}
	
	public function fetch_Object() {
		$this->connect();
		return mysql_fetch_object($this->result);
	}
	
	public function insert_id() {
		$this->connect();
		return mysql_insert_id();
	}
	
	public function db_data_seek($id) {
		$this->connect();
		if ($id > 0) {
			$id = $id -1;
		}
		if (!@ mysql_data_seek($this->result, $id)) {
			$this->show_error("SQL语句有误：", "指定的数据为空");
		}
		return $this->result;
	}

	public function db_num_rows() {
		$this->connect();
		if ($this->result == null) {
			if ($this->show_error) {
				$this->show_error("SQL语句错误", "暂时为空，没有任何内容！");
			}
		} else {
			return mysql_num_rows($this->result);
		}
	}
	
	public function db_affected_rows() {
		$this->connect();
		return mysql_affected_rows();
	}
	public function show_error($message = "", $sql = "") {
      
	}
	
	public function free() {
		@ mysql_free_result($this->result);
	}
	
	public function select_db($db_database) {
		$this->connect();
		return mysql_select_db($db_database);
	}
	
	public function num_fields($table_name) {
		
		$this->connect();
		return mysql_num_fields($this->result);

	}
	
	public function mysql_server($num = '') {
		$this->connect();
		switch ($num) {
			case 1 :
				return mysql_get_server_info(); 
				break;
			case 2 :
				return mysql_get_host_info();
				break;
			case 3 :
				return mysql_get_client_info();
				break;
			case 4 :
				return mysql_get_proto_info();
				break;
			default :
				return mysql_get_client_info();
		}
	}
	
	function escape_string($string){
		
		$this->connect();
		
		return mysql_real_escape_string($string);
	}

	public function close() {

		if($this->conn){

			@mysql_close($this->conn);
		}
		
	}

	public function __destruct() {

		if($this->conn){
			if (!empty ($this->result)) {
				$this->free();
			}
			@mysql_close($this->conn);
		}
		
	}
	function getmicrotime(){
		list($usec, $sec) = @explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}
}
?>