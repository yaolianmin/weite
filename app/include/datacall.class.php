<?php
/*
 * $Author ：PHPYUN开发团队
 * 官网: http://www.phpyun.com
 * 版权所有 2009-2018 宿迁鑫潮信息技术有限公司，并保留所有权利。
 * 软件声明：未经授权前提下，不得用于商业运营、二次开发以及任何形式的再次发布。
 */
class datacall{
    public $cachedir;
    public $obj;
    public $data;
    public $charset;
    public function __construct($cachedir,$obj){
        $this->cachedir = $cachedir;
        $this->obj=$obj;
        include_once CONFIG_PATH."/db.data.php";
        $this->data=$arr_data['datacall'];
        global $db_config;
        $this->charset=$db_config['charset'];
    }
    public function editcache($id){
        $row=$this->obj->DB_select_once("outside","id='".$id."'");
        if($row['type']){
            $row=$this->$row['type']($row);
        }
        return $row;
    }
    public function get_data($id){
        $row=$this->obj->DB_select_once("outside","id='".$id."'","id,code,edittime,lasttime,type,urltype");
        if($row['edittime']*60<mktime()-$row['lasttime']){
            $this->editcache($id);
            return $this->get_content($row);
        }else{
            return $this->get_content($row);
        }
    }
    public function get_content($row){
        $code=str_replace(array('&lt;','&gt;','&quot;'),array("<",">",'"'),$row['code']);
        $preg='/^(.*)<loop>(.*)<\/loop>(.*)$/isU';
        $matches = array();
        preg_match($preg,$code,$matches);
        include($this->cachedir.$row['id'].".php");
        
        $data_cont="";
        if(@is_array($data)){
            foreach($data as $val){
                $cont=$matches[2];
                foreach($this->data[$row['type']]['field'] as $key=>$va){
                    $cont=str_replace("{".$key."}",$val[$key],$cont);
                }
                $data_cont.=$cont;
            }
        }
        $target='';
        if($row['urltype']==1){$target=" target=\"_blank\"";}
        $data_cont=str_replace("{target}",$target,$data_cont);
        $matches[2]=$data_cont;
        return $new=$matches[1].$matches[2].$matches[3];
    }
    
    public function resume($row){
        include PLUS_PATH."city.cache.php";
        include PLUS_PATH."user.cache.php";
        include PLUS_PATH."industry.cache.php";
        include PLUS_PATH."job.cache.php";
        include PLUS_PATH."config.php";
        $where="a.def_job=b.id";
        global $views;
        $row['byorder']=str_replace("lastedit","lastupdate",$row['byorder']);
        if($row['byorder']){
            $order="order by b.".$row['byorder'];
        }
        $limit_num=$row['num']?$row['num']:10;
        $limit=" limit ".$limit_num;
        
        $field="b.id,b.name as resumename,a.name,a.birthday,a.edu,b.lastupdate,b.hits,a.resume_photo,a.photo,a.email,a.telhome,a.telphone,b.hy,b.job_classid,b.report,b.salary,b.type,b.provinceid as qw_provinceid,b.cityid as qw_cityid,b.cityid,a.exp,a.address,a.description,a.homepage,a.idcard,a.living,a.domicile";
        $rows=$this->obj->DB_select_alls("resume","resume_expect","$where $order $limit",$field);
        if(@is_array($rows)){
            foreach($rows as $key=>$va){
                if(strstr($row['code'],"{resumename}"))$data[$key]['resumename']=mb_substr($va['resumename'],0,$row['titlelen'],$this->charset);
                if(strstr($row['code'],"{name}"))$data[$key]['name']=mb_substr($va['name'],0,$row['titlelen'],$this->charset);
                if(strstr($row['code'],"{url}"))$data[$key]['url']=Url('resume',array("c"=>"show","id"=>$va['id']));
                if(strstr($row['code'],"{birthday}"))$data[$key]['birthday']=date("Y")-date('Y',strtotime($va['birthday']));
                if(strstr($row['code'],"{edu}"))$data[$key]['edu']=$userclass_name[$va['edu']];
                if(strstr($row['code'],"{lastedit}"))$data[$key]['lastedit']=date($row['timetype'],$va['lastupdate']);
                if(strstr($row['code'],"{hits}"))$data[$key]['hits']=$va['hits'];
                if(strstr($row['code'],"{big_pic}")){
                    if($va['photo']!=""){
                        $data[$key]['big_pic']=str_replace("./","/",$config['sy_weburl'].$va['photo']);
                    }else{
                        $data[$key]['big_pic']=$config['sy_weburl']."/".$config['sy_member_icon'];
                    }
                }
                if(strstr($row['code'],"{small_pic}")){
                    if($va['photo']!=""){
                        $data[$key]['small_pic']=str_replace("./","/",$config['sy_weburl'].$va['photo']);
                    }else{
                        $data[$key]['small_pic']=$config['sy_weburl']."/".$config['sy_member_icon'];
                    }
                }
                if(strstr($row['code'],"{email}"))$data[$key]['email']=$va['email'];
                if(strstr($row['code'],"{tel}"))$data[$key]['tel']=$va['telhome'];
                if(strstr($row['code'],"{moblie}"))$data[$key]['moblie']=$va['telphone'];
                if(strstr($row['code'],"{hy}"))$data[$key]['hy']=$industry_name[$va['hy']];
                if(strstr($row['code'],"{hyurl}"))$data[$key]['hyurl']=Url('company',array("hy"=>$va['hy']));
                if(strstr($row['code'],"{job_classid}")){
                    $job = array();
                    $arr_job=explode(',',$va['job_classid']);
                    if(@is_array($arr_job)){
                        foreach($arr_job as $val){
                            $job[]=$job_name[$val];
                        }
                    }
                    $data[$key]['job_classid']=@implode(',',$job);
                }
                if(strstr($row['code'],"{report}"))$data[$key]['report']=$userclass_name[$va['report']];
                if(strstr($row['code'],"{salary}"))$data[$key]['salary']=$userclass_name[$va['salary']];
                if(strstr($row['code'],"{type}"))$data[$key]['type']=$userclass_name[$va['type']];
                if(strstr($row['code'],"{gz_city}"))$data[$key]['gz_city']=$city_name[$va['qw_provinceid']]."-".$city_name[$va['qw_cityid']];
                if(strstr($row['code'],"{domicile}"))$data[$key]['domicile']=$va['domicile'];
                if(strstr($row['code'],"{living}"))$data[$key]['living']=$va['living'];
                if(strstr($row['code'],"{exp}"))$data[$key]['exp']=$userclass_name[$va['exp']];
                if(strstr($row['code'],"{address}"))$data[$key]['address']=$va['address'];
                if(strstr($row['code'],"{description}"))$data[$key]['description']=mb_substr($va['description'],0,$row['infolen'],$this->charset);				if(strstr($row['code'],"{idcard}"))$data[$key]['idcard']=$va['idcard'];
                if(strstr($row['code'],"{homepage}"))$data[$key]['homepage']=$va['homepage'];
            }
        }
        
        $this->cache($row['id'],$data);
    }
    
    public function member($row){
        include_once(LIB_PATH."public.url.php");
        $where="`status`='1'";
        $row['byorder']=str_replace("lastedit","lastupdate",$row['byorder']);
        if($row['byorder']){
            $order=" order by ".$row['byorder'];
        }
        $new_where=$this->data['member']['where'];
        $w=@explode(",",$row['where']);
        if(is_array($w)){
            foreach($w as $key=>$va){
                $arr=@explode("_",$va);
                $t[$arr[0]]=$arr[1];
            }
        }
        if(@is_array($new_where)){
            foreach($new_where as $key=>$va){
                if($t[$key]){
                    $where.=" and ".$key."=".$t[$key];
                }
            }
        }
        $limit_num=$row['num']?$row['num']:10;
        $limit=" limit ".$limit_num;
        $rows=$this->obj->DB_select_all("member","$where $order $limit");
        if(is_array($rows)){
            foreach($rows as $key=>$va){
                
                if(strstr($row['code'],"{name}"))$data[$key]['name']=mb_substr($va['username'],0,$row['titlelen'],$this->charset);
                if(strstr($row['code'],"{email}"))$data[$key]['email']=$va['email'];
                
                if(strstr($row['code'],"{url}"))$data[$key]['url']=Url("ask",array("c"=>'friend',"a"=>"myquestion","uid"=>$va['uid']));
                
                if(strstr($row['code'],"{moblie}"))$data[$key]['moblie']=$va['moblie'];
                if(strstr($row['code'],"{usertype}"))$data[$key]['usertype']=$new_where["usertype"][$va['usertype']];
                if(strstr($row['code'],"{hits}"))$data[$key]['hits']=$va['login_hits'];
                if(strstr($row['code'],"{reg_date}"))$data[$key]['reg_date']=date("$row[timetype]",$va['reg_date']);
                if(strstr($row['code'],"{login_date}"))$data[$key]['login_date']=date("$row[timetype]",$va['login_date']);
            }
        }
        
        $this->cache($row['id'],$data);
    }
    
    public function company($row){
        include PLUS_PATH."city.cache.php";
        include PLUS_PATH."com.cache.php";
        include PLUS_PATH."industry.cache.php";
        include PLUS_PATH."job.cache.php";
        include PLUS_PATH."config.php";
        global $views;
        $where="`name`<>'' and `hy`<>''";
        $row['byorder']=str_replace("lastedit","lastupdate",$row['byorder']);
        if($row['byorder']){
            $order="order by ".$row['byorder'];
        }
        $limit_num=$row['num']?$row['num']:10;
        $limit=" limit ".$limit_num;
        $field="uid,name,hy,pr,provinceid,cityid,mun,sdate,money,address,zip,linkman,linkjob,linkqq,linkphone,linktel,linkmail,website,logo";
        $rows = $this->obj->DB_select_all("company","$where $order $limit",$field);
        if(is_array($rows)){
            foreach($rows as $key=>$va){
                if(strstr($row['code'],"{companyname}"))$data[$key]['companyname']=mb_substr($va['name'],0,$row['titlelen'],$this->charset);
                if(strstr($row['code'],"{url}"))$data[$key]['url']=Url("company",array("c"=>'show',"id"=>$va['uid']));
                if(strstr($row['code'],"{hy}"))$data[$key]['hy']=$industry_name[$va['hy']];
                if(strstr($row['code'],"{hy_url}"))$data[$key]['hy_url']=Url('company',array("hy"=>$va['hy']));
                if(strstr($row['code'],"{pr}"))$data[$key]['pr']=$comclass_name[$va['pr']];
                if(strstr($row['code'],"{city}")){
                    if ($city_name[$va['provinceid']]&& $city_name[$va['cityid']]){
                        $data[$key]['city']=$city_name[$va['provinceid']]."-".$city_name[$va['cityid']];
                    }
                }
                if(strstr($row['code'],"{mun}"))$data[$key]['mun']=$comclass_name[$va['mun']];
                if(strstr($row['code'],"{address}"))$data[$key]['address']=$va['address'];
                if(strstr($row['code'],"{linkphone}"))$data[$key]['linkphone']=$va['linkphone'];
                if(strstr($row['code'],"{linkmail}"))$data[$key]['linkmail']=$va['linkmail'];
                if(strstr($row['code'],"{sdate}"))$data[$key]['sdate']=$va['sdate'];
                if(strstr($row['code'],"{money}"))$data[$key]['money']=$va['money'];
                if(strstr($row['code'],"{zip}"))$data[$key]['zip']=$va['zip'];
                if(strstr($row['code'],"{linkman}"))$data[$key]['linkman']=$va['linkman'];
                if(strstr($row['code'],"{job_num}")){
                    $job_num= $this->obj->DB_select_num("company_job","`uid`='".$va['uid']."'");
                    $data[$key]['job_num']=$job_num;
                }
                if(strstr($row['code'],"{linkqq}"))$data[$key]['linkqq']=$va['linkqq'];
                if(strstr($row['code'],"{linktel}"))$data[$key]['linktel']=$va['linktel'];
                if(strstr($row['code'],"{website}"))$data[$key]['website']=$va['website'];
                if(strstr($row['code'],"{logo}"))$data[$key]['logo']=str_replace("./","/",$config['sy_weburl'].$va['logo']);
            }
        }
        $this->cache($row['id'],$data);
    }
    
    public function job($row){
        include PLUS_PATH."city.cache.php";
        include PLUS_PATH."com.cache.php";
        include PLUS_PATH."industry.cache.php";
        include PLUS_PATH."job.cache.php";
        include PLUS_PATH."config.php";
        global $views;
        $where="`state`=1 and `edate`>'".time()."'";
        $row['byorder']=str_replace("lastedit","lastupdate",$row['byorder']);
        if($row['byorder']){
            $order="order by ".$row['byorder'];
        }
        $limit_num=$row['num']?$row['num']:10;
        $limit=" limit ".$limit_num;
        $rows = $this->obj->DB_select_all("company_job","$where $order $limit","id,uid,name,com_name,hy,job1_son,job_post,provinceid,cityid,minsalary,maxsalary,type,number,age,exp,report,sex,edu,marriage,lang,welfare,edate,lastupdate");
        if(is_array($rows)){
            foreach($rows as $key=>$va){
                if(strstr($row['code'],"{jobname}"))$data[$key]['jobname']=mb_substr($va['name'],0,$row['titlelen'],$this->charset);
                if(strstr($row['code'],"{companyname}"))$data[$key]['companyname']=mb_substr($va['com_name'],0,$row['titlelen'],$this->charset);
                if(strstr($row['code'],"{url}"))$data[$key]['url']=Url('job',array("c"=>'comapply',"id"=>$va['id']));
                if(strstr($row['code'],"{com_url}"))$data[$key]['com_url']=Url("company",array("c"=>"show","id"=>$va['uid']));
                if(strstr($row['code'],"{hy}"))$data[$key]['hy']=$industry_name[$va['hy']];
                if(strstr($row['code'],"{hy_url}"))$data[$key]['hy_url']=Url('company',array("hy"=>$va['hy']));
                if(strstr($row['code'],"{city}"))$data[$key]['city']=$city_name[$va['provinceid']]."-".$city_name[$va['cityid']];
                if(strstr($row['code'],"{num}"))$data[$key]['num']=$comclass_name[$va['number']];
                if(strstr($row['code'],"{jobtype}"))$data[$key]['jobtype']=$job_name[$va['job1_son']]."-".$job_name[$va['job_post']];
                if(strstr($row['code'],"{edu}"))$data[$key]['edu']=$comclass_name[$va['edu']];
                if(strstr($row['code'],"{age}"))$data[$key]['age']=$comclass_name[$va['age']];
                if(strstr($row['code'],"{report}"))$data[$key]['report']=$comclass_name[$va['report']];
                if(strstr($row['code'],"{exp}"))$data[$key]['exp']=$comclass_name[$va['exp']];
                if(strstr($row['code'],"{salary}")){
                    if ($va['minsalary'] && $va['maxsalary']){
                        $salary=$va['minsalary'].'-'.$va['maxsalary'];
                    }elseif ($va['minsalary']){
                        $salary=$va['minsalary']."以上";
                    }else {
                        $salary="面议";
                    }
                    $data[$key]['salary']=$salary;
                }
                if(strstr($row['code'],"{lang}")){
                    if ($va['lang']){
                        $arr_lang=explode(',',$va['lang']);
                        if(@is_array($arr_lang)){
                            foreach($arr_lang as $v){
                                $lang[]=$comclass_name[$v];
                            }
                        }
                        $data[$key]['lang']=implode(',',$lang);
                    }
                }
                if(strstr($row['code'],"{welfare}")){
                    if ($va['welfare']){
                        $arr_job=explode(',',$va['welfare']);
                        if(@is_array($arr_job)){
                            foreach($arr_job as $v){
                                $job[]=$comclass_name[$v];
                            }
                        }
                        $data[$key]['welfare']=@implode(',',$job);
                    }
                }
                if(strstr($row['code'],"{time}"))$data[$key]['time']=date("$row[timetype]",$va['lastupdate']);
            }
        }
        $this->cache($row['id'],$data);
    }
    
    public function zph($row){
        include PLUS_PATH."config.php";
        global $views;
        $where="1";
        $row['byorder']=str_replace("lastedit","lastupdate",$row['byorder']);
        if($row['byorder']){
            $order="order by ".$row['byorder'];
        }
        $limit_num=$row['num']?$row['num']:10;
        $limit=" limit ".$limit_num;
        $rows = $this->obj->DB_select_all("zhaopinhui","$where $order $limit","id,title,organizers,starttime,address,phone,user,weburl,pic");
        if(is_array($rows)){
            foreach($rows as $key=>$va){
                if(strstr($row['code'],"{title}"))$data[$key]['title']=mb_substr($va['title'],0,$row['titlelen'],$this->charset);
                if(strstr($row['code'],"{url}"))$data[$key]['url']=Url('zph',array("c"=>'show',"id"=>$va['id']));
                if(strstr($row['code'],"{organizers}"))$data[$key]['organizers']=$va['organizers'];
                if(strstr($row['code'],"{time}"))$data[$key]['time']=date("$row[timetype]",$va['starttime']);
                if(strstr($row['code'],"{address}"))$data[$key]['address']=$va['address'];
                if(strstr($row['code'],"{phone}"))$data[$key]['phone']=$va['phone'];
                if(strstr($row['code'],"{linkman}"))$data[$key]['linkman']=$va['user'];
                if(strstr($row['code'],"{website}"))$data[$key]['website']=$va['weburl'];
                if(strstr($row['code'],"{logo}"))$data[$key]['logo']=$config['sy_weburl'].$va['pic'];
                if(strstr($row['code'],"{com_num}")){
                    $job_num= $this->obj->DB_select_num("zhaopinhui_com","`zid`='".$va['id']."'");
                    $data[$key]['com_num']=$job_num;
                }
            }
        }
        $this->cache($row['id'],$data);
    }
    
    public function news($row){
        include PLUS_PATH."config.php";
        global $views;
        $where="a.nid=b.id";
        $row['byorder']=str_replace("lastedit","lastupdate",$row['byorder']);
        if($row['byorder']){
            $order="order by ".$row['byorder'];
        }
        $limit_num=$row['num']?$row['num']:10;
        $limit=" limit ".$limit_num;
        $field="a.id,a.title,a.keyword,a.author,a.datetime,a.hits,a.description,a.s_thumb,a.source,b.name";
        $rows = $this->obj->DB_select_alls("news_base","news_group","$where $order $limit",$field);
        if(is_array($rows)){
            foreach($rows as $key=>$va){
                if(strstr($row['code'],"{title}"))$data[$key]['title']=mb_substr($va['title'],0,$row['titlelen'],$this->charset);
                if(strstr($row['code'],"{url}"))$data[$key]['url']="/news/".date("Ymd",$va["datetime"])."/".$va['id'].".html";
                if(strstr($row['code'],"{keyword}"))$data[$key]['keyword']=$va['keyword'];
                if(strstr($row['code'],"{author}"))$data[$key]['author']=$va['author'];
                if(strstr($row['code'],"{time}"))$data[$key]['time']=date("$row[timetype]",$va['datetime']);
                if(strstr($row['code'],"{hits}"))$data[$key]['hits']=$va['hits'];
                if(strstr($row['code'],"{description}"))$data[$key]['description']=mb_substr($va['description'],0,$row['infolen'],$this->charset);				if(strstr($row['code'],"{thumb}"))$data[$key]['thumb']=$config['sy_weburl']."/".$va['s_thumb'];
                if(strstr($row['code'],"{source}"))$data[$key]['source']=$va['source'];
                if(strstr($row['code'],"{url}"))$data[$key]['url}']='';
            }
        }
        $this->cache($row['id'],$data);
    }
    
    public function ask($row){
        include PLUS_PATH."config.php";
        global $views;
        $where="a.`uid`=b.`uid` ";
        if($row['byorder']){
            $order="order by ".$row['byorder'];
        }
        $limit_num=$row['num']?$row['num']:10;
        $limit=" limit ".$limit_num;
        $field="id,title,content,uid,answer_num,add_time";
        $rows = $this->obj->DB_select_all("question","$where $order $limit",$field);
        if(is_array($rows)){
            foreach($rows as $key=>$va){
                if(strstr($row['code'],"{title}"))$data[$key]['title']=mb_substr($va['title'],0,$row['titlelen'],$this->charset);
                if(strstr($row['code'],"{url}"))$data[$key]['url']=Url("ask",array("c"=>'content',"id"=>$va['id']));
                if(strstr($row['code'],"{content}"))$data[$key]['content']=$va['content'];
                if(strstr($row['code'],"{name}"))$data[$key]['name']=$va['nickname'];
                if(strstr($row['code'],"{time}"))$data[$key]['time']=date("$row[timetype]",$va['add_time']);
                if(strstr($row['code'],"{answer_num}"))$data[$key]['answer_num']=$va['answer_num'];
                if(strstr($row['code'],"{img}"))$data[$key]['img']=str_replace("../","/",$config['sy_weburl'].$va['pic']);
                if(strstr($row['code'],"{user_url}"))$data[$key]['user_url']=Url("ask",array("c"=>'friend',"a"=>"myquestion","id"=>$va['uid']));
            }
        }
        $this->cache($row['id'],$data);
    }
    
    public function lt_job($row){
        include PLUS_PATH."city.cache.php";
        include PLUS_PATH."com.cache.php";
        include PLUS_PATH."industry.cache.php";
        include PLUS_PATH."lt.cache.php";
        include PLUS_PATH."ltjob.cache.php";
        include PLUS_PATH."config.php";
        include(CONFIG_PATH."db.data.php");
        unset($arr_data['sex'][3]);
        global $views;
        $where="a.`uid`=b.`uid`";
        $row['byorder']=str_replace("lastedit","lastupdate",$row['byorder']);
        if($row['byorder']){
            $order=" order by ".$row['byorder'];
        }
        $limit_num=$row['num']?$row['num']:10;
        $limit=" limit ".$limit_num;
        $field="a.id,a.uid,a.com_name,a.pr,a.hy,a.mun,a.desc,a.job_name,a.jobone,a.jobtwo,a.department,a.report,a.provinceid,a.cityid,a.edate,a.salary,a.constitute,a.desc,a.age,a.edu,a.sex,a.exp,a.full,a.language,a.eligible,a.qw_hy,a.lastupdate,b.realname";
        $rows = $this->obj->DB_select_alls("lt_job","lt_info","$where $order $limit",$field);
        if(is_array($rows)){
            foreach($rows as $key=>$va){
                if(strstr($row['code'],"{jobname}"))$data[$key]['jobname']=mb_substr($va['job_name'],0,$row['titlelen'],$this->charset);
                if(strstr($row['code'],"{url}"))$data[$key]['url']=Url('lietou',array("c"=>"job_show","id"=>$va['id']));
                if(strstr($row['code'],"{companyname}"))$data[$key]['companyname']=mb_substr($va['com_name'],0,$row['titlelen'],$this->charset);
                if(strstr($row['code'],"{com_url}"))$data[$key]['com_url']=Url("company",array("c"=>"show","id"=>$va['uid']));
                if(strstr($row['code'],"{address}"))$data[$key]['address']=$city_name[$va['provinceid']]."-".$city_name[$va['cityid']];
                if(strstr($row['code'],"{department}"))$data[$key]['department']=$va['department'];
                if(strstr($row['code'],"{hy}"))$data[$key]['hy']=$industry_name[$va['hy']];
                if(strstr($row['code'],"{hy_url}"))$data[$key]['hy_url']=Url('company',array("hy"=>$va['hy']));
                if(strstr($row['code'],"{mun}"))$data[$key]['mun']=$comclass_name[$va['mun']];
                if(strstr($row['code'],"{pr}"))$data[$key]['pr']=$comclass_name[$va['pr']];
                if(strstr($row['code'],"{report}"))$data[$key]['report']=$va['report'];
                if(strstr($row['code'],"{jobtype}"))$data[$key]['jobtype']=$ltjob_name[$va['jobone']]."-".$ltjob_name[$va['jobtwo']];
                if(strstr($row['code'],"{constitute}"))$data[$key]['constitute']=$ltclass_name[$va['constitute']];
                if(strstr($row['code'],"{sdate}"))$data[$key]['sdate']=date("$row[timetype]",$va['lastupdate']);
                if(strstr($row['code'],"{edate}"))$data[$key]['edate']=date("$row[timetype]",$va['edate']);
                if(strstr($row['code'],"{job_desc}"))$data[$key]['desc']=mb_substr($va['desc'],0,$row['infolen'],$this->charset);
                if(strstr($row['code'],"{salary}"))$data[$key]['salary']=$ltclass_name[$va['salary']];
                if(strstr($row['code'],"{edu}"))$data[$key]['edu']=$ltclass_name[$va['edu']];
                if(strstr($row['code'],"{sex}"))$data[$key]['sex']=$arr_data['sex'][$va['sex']];
                if(strstr($row['code'],"{language}"))$data[$key]['language']=$ltclass_name[$va['language']];
                if(strstr($row['code'],"{full}"))$data[$key]['full']=$ltclass_name[$va['full']];
                if(strstr($row['code'],"{age}"))$data[$key]['age']=$ltclass_name[$va['age']];
                if(strstr($row['code'],"{exp}"))$data[$key]['exp']=$ltclass_name[$va['exp']];
                if(strstr($row['code'],"{qw_hy}")){
                    $arr_qwhy=explode(',',$va['qw_hy']);
                    if(@is_array($arr_qwhy)){
                        foreach($arr_qwhy as $val){
                            $qwhy[]=$lthy_name[$val];
                        }
                    }
                    $data[$key]['qw_hy']=@implode(',',$qwhy);
                }
                if(strstr($row['code'],"{eligible}"))$data[$key]['eligible']=$va['eligible'];
                if(strstr($row['code'],"{desc}"))$data[$key]['desc']=$va['desc'];
                if(strstr($row['code'],"{name}"))$data[$key]['name']=$va['realname'];
            }
        }
        $this->cache($row['id'],$data);
    }
    
    public function link($row){
        include PLUS_PATH."config.php";
        $where="1";
        if($row['byorder']){
            $order=" order by ".$row['byorder'];
        }
        $new_where=$this->data['member']['where'];
        $w=@explode(",",$row['where']);
        if(@is_array($w)){
            foreach($w as $key=>$va){
                $arr=@explode("_",$va);
                $t[$arr[0]]=$arr[1];
            }
        }
        if(@is_array($new_where)){
            foreach($new_where as $key=>$va){
                if($t[$key]){
                    $where.=" and ".$key."=".$t[$key];
                }
            }
        }
        $limit_num=$row['num']?$row['num']:10;
        $limit=" limit ".$limit_num;
        $rows = $this->obj->DB_select_all("admin_link","$where $order $limit","link_name,link_url,pic");
        if(is_array($rows)){
            foreach($rows as $key=>$va){
                if(strstr($row['code'],"{link_name}"))$data[$key]['link_name']=mb_substr($va['link_name'],0,$row['titlelen'],$this->charset);
                if(strstr($row['code'],"{link_url}"))$data[$key]['link_url']=$va['link_url'];
                if(strstr($row['code'],"{link_src}"))$data[$key]['link_src']=$config['sy_weburl']."/".$va['pic'];
            }
        }
        $this->cache($row['id'],$data);
    }
    
    public function once($row){
        include PLUS_PATH."config.php";
        $where="1";
        $row['byorder']=str_replace("lastedit","ctime",$row['byorder']);
        if($row['byorder']){
            $order="order by ".$row['byorder'];
        }
        $limit_num=$row['num']?$row['num']:10;
        $limit=" limit ".$limit_num;
        $rows = $this->obj->DB_select_all("once_job","$where $order $limit");
        if(is_array($rows)){
            foreach($rows as $key=>$va){
                if(strstr($row['code'],"{jobname}"))$data[$key]['jobname']=mb_substr($va['title'],0,$row['titlelen'],$this->charset);
                if(strstr($row['code'],"{url}"))$data[$key]['url']=Url('once',array("c"=>'show',"id"=>$va['id']));
                if(strstr($row['code'],"{companyname}"))$data[$key]['companyname']=mb_substr($va['companyname'],0,$row['titlelen'],$this->charset);
                if(strstr($row['code'],"{mans}"))$data[$key]['mans']=$va['mans'];
                if(strstr($row['code'],"{require}"))$data[$key]['require']=$va['require'];
                if(strstr($row['code'],"{phone}"))$data[$key]['phone']=$va['phone'];
                if(strstr($row['code'],"{linkman}"))$data[$key]['linkman']=$va['linkman'];
                if(strstr($row['code'],"{address}"))$data[$key]['address']=$va['address'];
                if(strstr($row['code'],"{time}"))$data[$key]['time']=date("$row[timetype]",$va['ctime']);
            }
        }
        $this->cache($row['id'],$data);
    }
    
    public function tiny($row){
        include PLUS_PATH."config.php";
        include PLUS_PATH."user.cache.php";
        include(CONFIG_PATH."db.data.php");
        unset($arr_data['sex'][3]);
        global $views;
        $where="1";
        $row['byorder']=str_replace("lastedit","time",$row['byorder']);
        if($row['byorder']){
            $order="order by ".$row['byorder'];
        }
        $limit_num=$row['num']?$row['num']:10;
        $limit=" limit ".$limit_num;
        $rows = $this->obj->DB_select_all("resume_tiny","$where $order $limit");
        
        if(is_array($rows)){
            foreach($rows as $key=>$va){
                if(strstr($row['code'],"{name}"))$data[$key]['name']=mb_substr($va['username'],0,$row['titlelen'],$this->charset);
                if(strstr($row['code'],"{url}"))$data[$key]['url']=Url('tiny',array("c"=>'show',"id"=>$va['id']));
                if(strstr($row['code'],"{sex}"))$data[$key]['sex']=$arr_data['sex'][$va['sex']];
                if(strstr($row['code'],"{exp}"))$data[$key]['exp']=$userclass_name[$va['exp']];
                if(strstr($row['code'],"{job}"))$data[$key]['job']=$va['job'];
                if(strstr($row['code'],"{mobile}"))$data[$key]['mobile']=$va['mobile'];
                if(strstr($row['code'],"{describe}"))$data[$key]['describe']=mb_substr($va['production'],0,$row['infolen'],$this->charset);
                if(strstr($row['code'],"{time}"))$data[$key]['time']=date("$row[timetype]",$va['time']);
            }
        }
        $this->cache($row['id'],$data);
    }
    
    public function keyword($row){
        include PLUS_PATH."config.php";
        $where="1";
        if($row['byorder']){
            $order=" order by ".$row['byorder'];
        }
        $new_where=$this->data['member']['where'];
        $w=@explode(",",$row['where']);
        if(@is_array($w)){
            foreach($w as $key=>$va){
                $arr=@explode("_",$va);
                $t[$arr[0]]=$arr[1];
            }
        }
        if(@is_array($new_where)){
            foreach($new_where as $key=>$va){
                if($t[$key]){
                    $where.=" and ".$key."=".$t[$key];
                }
            }
        }
        $limit_num=$row['num']?$row['num']:10;
        $limit=" limit ".$limit_num;
        $rows = $this->obj->DB_select_all("hot_key","$where $order $limit","id,key_name,num,type");
        if(is_array($rows)){
            foreach($rows as $key=>$va){
                $url="";
                if($va['type']=='1'){
                    $url = Url("once",array('keyword'=>$va['key_name']));
                }elseif($va['type']=='3'){
                    $url = Url("job",array('keyword'=>$va['key_name']));
                }elseif($va['type']=='4'){
                    $url = Url("company",array('keyword'=>$va['key_name']));
                }elseif($va['type']=='5'){
                    $url = Url("resume",array('keyword'=>$va['key_name']));
                }elseif($va['type']=='6'){
                    $url = Url("lietou",array('c'=>"service",'keyword'=>$va['key_name']));
                }elseif($va['type']=='7'){
                    $url = Url("lietou",array('c'=>"post",'keyword'=>$va['key_name']));
                    
                }
                if(strstr($row['code'],"{name}"))$data[$key]['name']=mb_substr($va['key_name'],0,$row['titlelen'],$this->charset);
                if(strstr($row['code'],"{url}"))$data[$key]['url']=$url;
                if(strstr($row['code'],"{num}"))$data[$key]['num']=$va['num'];
            }
        }
        $this->cache($row['id'],$data);
    }
    public function cache($filename,$data){
        
        $data_new['data']=ArrayToString($data,true);
        $this->obj->DB_update_all("outside","`lasttime`='".mktime()."'","id='".$filename."'");
        
        return made_web_array($this->cachedir.$filename.".php",$data_new);
    }
}
?>