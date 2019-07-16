<?php /* Smarty version Smarty-3.1.21-dev, created on 2019-03-13 11:44:29
         compiled from "/www/wwwroot/hr/app/template/admin/statis_user.htm" */ ?>
<?php /*%%SmartyHeaderCode:12165331615c887c9d496505-19445245%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8958a3436225ad50a6f07cd46d31c7e7ce04d393' => 
    array (
      0 => '/www/wwwroot/hr/app/template/admin/statis_user.htm',
      1 => 1519809342,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '12165331615c887c9d496505-19445245',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'config' => 0,
    'pytoken' => 0,
    'radio_time' => 0,
    'data' => 0,
    'user' => 0,
    'values' => 0,
    'k' => 0,
    'r' => 0,
    'total' => 0,
    'defaultTimeBegin' => 0,
    'defaultTimeEnd' => 0,
    'gourl' => 0,
    'title' => 0,
    'names' => 0,
    'v' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_5c887c9d591bb4_23787798',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c887c9d591bb4_23787798')) {function content_5c887c9d591bb4_23787798($_smarty_tpl) {?><!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
  <link href="images/reset.css?v=<?php echo $_smarty_tpl->tpl_vars['config']->value['cachecode'];?>
" rel="stylesheet" type="text/css" />
  <link href="./images/system.css?v=<?php echo $_smarty_tpl->tpl_vars['config']->value['cachecode'];?>
" rel="stylesheet" type="text/css" />
  <link href="<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_weburl'];?>
/js/layui/css/layui.css?v=<?php echo $_smarty_tpl->tpl_vars['config']->value['cachecode'];?>
" rel="stylesheet" type="text/css" />
  <link href="images/statis.css?v=<?php echo $_smarty_tpl->tpl_vars['config']->value['cachecode'];?>
" rel="stylesheet" type="text/css" />
  <title>后台管理-图表分析</title>
  <style>
  .admin_new_search_time{
    width: 120px !important;
  }
  </style>
</head>
<body class="body_ifm">

<div class="infoboxp">
<div class="tabs_info">
    <ul>
      
   <li><a href="index.php?m=statis" >收支总计</a></li>
  <li><a href="index.php?m=statis_income">收益渠道</a></li>
  <li class="curr"><a href="index.php?m=statis_user">消费群体</a></li>
  
    </ul>
  </div>
<div class="admin_new_tip">
<a href="javascript:;" class="admin_new_tip_close"></a>
<a href="javascript:;" class="admin_new_tip_open" style="display:none;"></a>
<div class="admin_new_tit"><i class="admin_new_tit_icon"></i>操作提示</div>
<div class="admin_new_tip_list_cont">
<div class="admin_new_tip_list">该页面展示了消费群体统计。</div>
</div>
</div>

<div class="clear"></div>
<div class="admin_new_search_box">
<form class="layui-form">
  <input type="hidden" name="pytoken" id="pytoken" value="<?php echo $_smarty_tpl->tpl_vars['pytoken']->value;?>
">

  <!--选择时间范围-->
  <div class=" " id="time_range">
    <div class="admin_new_search_name"><label>查询范围：</label></div>
   
      <input type="text" class="admin_new_search_time" id="time_begin">
      <input type="text" class="admin_new_search_time" id="time_end">

      <button class="admin_new_search_timebth" lay-filter="query" onclick="return query();">点击查询</button>
      <button class="admin_new_search_timebth" onclick="return showDetail(this);">查看详情</button>
 

    <div class="layui-input-inline">
      <input name="radio_time" value="0" title="今天" type="radio" lay-filter="radio_time"
      <?php if ($_smarty_tpl->tpl_vars['radio_time']->value=='0') {?>
      checked
      <?php }?>
      />
      <input name="radio_time" value="1" title="昨天" type="radio" lay-filter="radio_time"
      <?php if ($_smarty_tpl->tpl_vars['radio_time']->value=='1') {?>
      checked
      <?php }?>
      />
      <input name="radio_time" value="7" title="7天内" type="radio" lay-filter="radio_time"
      <?php if ($_smarty_tpl->tpl_vars['radio_time']->value=='7') {?>
      checked
      <?php }?>
      />
      <input name="radio_time" value="30" title="30天内" type="radio" lay-filter="radio_time"
      <?php if (!$_smarty_tpl->tpl_vars['radio_time']->value||$_smarty_tpl->tpl_vars['radio_time']->value=='30') {?>
      checked
      <?php }?>
      />
      <input name="radio_time" value="90" title="90天内" type="radio" lay-filter="radio_time"
      <?php if ($_smarty_tpl->tpl_vars['radio_time']->value=='90') {?>
      checked
      <?php }?>
      />
      <input name="radio_time" value="-1" title="全部" type="radio" lay-filter="radio_time"
      <?php if ($_smarty_tpl->tpl_vars['radio_time']->value=='-1') {?>
      checked
      <?php }?>
      />
      <input type="hidden" name="isAllTime" id="isAllTime" value="0"/>
    </div>
  </div>
</form>
</div>
  <div class="clear"></div>
  
  <div class="adminstatis_box">
  


    <div class="com_pay_balance">
      <div class="com_pay_balance_data_q">
       <i class="com_pay_balance_data_q_icon"></i>
        <div class="com_pay_balance_data_n"><strong><?php echo $_smarty_tpl->tpl_vars['data']->value[0]['in'];?>
</strong></div>
        <div class="com_pay_balance_data_name">近30天收益</div>
      </div>
    </div>
    <div class="com_pay_balance">
      <div class="com_pay_balance_data_q border_blue">
       <i class="com_pay_balance_data_q_icon"></i>
        <div class="com_pay_balance_data_n color_blue"><strong><?php echo $_smarty_tpl->tpl_vars['data']->value[0]['out'];?>
</strong></div>
        <div class="com_pay_balance_data_name">支出/提现</div>
      </div>
    </div>
    <div class="com_pay_balance">
      <div class="com_pay_balance_data_q border_orange">
       <i class="com_pay_balance_data_q_icon"></i>
        <div class="com_pay_balance_data_n color_orange"><strong><?php echo $_smarty_tpl->tpl_vars['data']->value[0]['net_income'];?>
</strong></div>
        <div class="com_pay_balance_data_name">净收入</div>
      </div>
    </div>  

       </div>   
        <div class="clear"></div>
    <div class="com_pay_balance_qtbox">
    <a href="index.php?m=statis_user" class="layui-btn layui-btn-small
      <?php if ($_GET['m']=='statis_user'&&$_GET['c']=='') {?>
      layui-btn-warm
      <?php } else { ?>
      layui-btn-normal
      <?php }?>
    ">消费最多企业</a>
    <a href="index.php?m=statis_user&c=user" class="layui-btn layui-btn-small
      <?php if ($_GET['m']=='statis_user'&&$_GET['c']=='user') {?>
      layui-btn-warm
      <?php } else { ?>
      layui-btn-normal
      <?php }?>
    ">消费最多个人</a>
    <a href="index.php?m=statis_user&c=lt" class="layui-btn layui-btn-small
      <?php if ($_GET['m']=='statis_user'&&$_GET['c']=='lt') {?>
      layui-btn-warm
      <?php } else { ?>
      layui-btn-normal
      <?php }?>
    ">消费最多猎头</a>
    <a href="index.php?m=statis_city" class="layui-btn layui-btn-small
      <?php if ($_GET['m']=='statis_city') {?>
      layui-btn-warm
      <?php } else { ?>
      layui-btn-normal
      <?php }?>
    ">消费最多地区</a>
    <a href="index.php?m=statis_hy" class="layui-btn layui-btn-small
      <?php if ($_GET['m']=='statis_hy') {?>
      layui-btn-warm
      <?php } else { ?>
      layui-btn-normal
      <?php }?>
    ">消费最多行业</a>
    </div>
    <div class="clear"></div>
  
  <div class="payments_com_pay_cont">
    <div class="admin_atatic_list_rightbox">
  <div class="admin_atatic_chart " id="chart" style="height:370px;  margin-top:20px; margin-bottom:10px;">  </div>

  <div class="admin_atatic_list_right">
    <h3><?php echo $_smarty_tpl->tpl_vars['user']->value;?>
消费排行</h3>
    <ul class="admin_atatic_list_li">
    <?php  $_smarty_tpl->tpl_vars['r'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['r']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['values']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['r']->key => $_smarty_tpl->tpl_vars['r']->value) {
$_smarty_tpl->tpl_vars['r']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['r']->key;
?>
      <li>
        <?php if ($_smarty_tpl->tpl_vars['k']->value<3) {?>
        <span class="layui-badge"><?php echo $_smarty_tpl->tpl_vars['k']->value+1;?>
</span>
        <?php } else { ?>
        <span class="layui-badge layui-bg-green"><?php echo $_smarty_tpl->tpl_vars['k']->value+1;?>
</span>
        <?php }?>

        <span><?php echo $_smarty_tpl->tpl_vars['r']->value['name'];?>
</span>
        --
        <span><?php echo $_smarty_tpl->tpl_vars['r']->value['value'];?>
</span>
      </li>
    <?php } ?>
    </ul>
    </div>
  </div>
  <div class="statis_list">
  <table class="" lay-skin="nob" style="display: none;" id="detail" width="100%">
    <thead>
      <tr id="detail_thead"class="admin_table_top" style="text-align:left">
        <th>项目</th>
        <th>金额（元）</th>
      </tr> 
    </thead>
    <tbody id="detail_tbody" >
      <?php  $_smarty_tpl->tpl_vars['r'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['r']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['values']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['r']->key => $_smarty_tpl->tpl_vars['r']->value) {
$_smarty_tpl->tpl_vars['r']->_loop = true;
?>
      <tr>
        <td><?php echo $_smarty_tpl->tpl_vars['r']->value['name'];?>
</td>
        <td><?php echo $_smarty_tpl->tpl_vars['r']->value['value'];?>
</td>
      </tr>
      <?php } ?>
      <tr>
        <td>总计</td>
        <td><?php echo $_smarty_tpl->tpl_vars['total']->value;?>
</td>
      </tr>
    </tbody>
  </table>  </div> </div>

  <?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_weburl'];?>
/js/layui/layui.js?v=<?php echo $_smarty_tpl->tpl_vars['config']->value['cachecode'];?>
"><?php echo '</script'; ?>
>
  <?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_weburl'];?>
/js/layui/phpyun_layer.js?v=<?php echo $_smarty_tpl->tpl_vars['config']->value['cachecode'];?>
"><?php echo '</script'; ?>
>
  <?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_weburl'];?>
/js/echarts_plain.js?v=<?php echo $_smarty_tpl->tpl_vars['config']->value['cachecode'];?>
"><?php echo '</script'; ?>
>
  <?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_weburl'];?>
/js/jquery-1.8.0.min.js?v=<?php echo $_smarty_tpl->tpl_vars['config']->value['cachecode'];?>
"><?php echo '</script'; ?>
>
  <?php echo '<script'; ?>
>

    function getDateTimeStr(timestamp)
    {
      var d = new Date(timestamp);    //根据时间戳生成的时间对象
      var h = d.getHours(),
        m = d.getMinutes(),
        s = d.getSeconds(),
        month = d.getMonth() + 1,
        day = d.getDate();

      h = h < 10 ? '0' + h : h;
      m = m < 10 ? '0' + m : m;
      s = s < 10 ? '0' + s : s;

      month = month < 10 ? '0' + month : month;
      day = day < 10 ? '0' + day : day;

      return (d.getFullYear()) + "-" + month + "-" + day + " " + 
           h + ":" + m + ":" + s;
    }
    function getToday(){
      var today = new Date();
      today.setHours(0);
      today.setMinutes(0);
      today.setSeconds(0);
      today.setMilliseconds(0);
      return today;
    }
    
    layui.use(['form', 'laydate'], function(){
      var laydate = layui.laydate
        ,form = layui.form
        ,$ = layui.$;

      laydate.render({
        elem : '#time_begin' //指定元素
        ,type : 'datetime'
        ,value : '<?php echo $_smarty_tpl->tpl_vars['defaultTimeBegin']->value;?>
'
      });

      laydate.render({
        elem : '#time_end' //指定元素
        ,type : 'datetime'
        ,value : '<?php echo $_smarty_tpl->tpl_vars['defaultTimeEnd']->value;?>
'
      });

      form.on('radio(radio_time)', function(data){
        var gourl = '<?php echo $_smarty_tpl->tpl_vars['gourl']->value;?>
';

        if(data.value == -1){
          window.location = gourl + '&isAllTime=1&radio_time=' + data.value;;
        }
        else{
          var today = getToday();
          var diff = 1000 * 60 * 60 * 24 * data.value;
          var day = new Date(today - diff);
          window.location = gourl + '&time_begin=' + getDateTimeStr(Date.parse(day)) + '&time_end=' + getDateTimeStr(Date.parse(new Date())) + '&radio_time=' + data.value;
        }
      });

      var title = '<?php echo $_smarty_tpl->tpl_vars['title']->value;?>
';
      var names = [
        <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['names']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
          <?php if ($_smarty_tpl->tpl_vars['k']->value>0) {?>
          ,
          <?php }?>
        '<?php echo $_smarty_tpl->tpl_vars['v']->value;?>
'
        <?php } ?>
      ];

      var values = [
        <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['values']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
          <?php if ($_smarty_tpl->tpl_vars['k']->value>0) {?>
          ,
          <?php }?>
          {
            value : parseFloat('<?php echo $_smarty_tpl->tpl_vars['v']->value["value"];?>
'),
            name : '<?php echo $_smarty_tpl->tpl_vars['v']->value["name"];?>
'
          }
        <?php } ?>
      ];

      var valuesBar = [
        <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['values']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
          <?php if ($_smarty_tpl->tpl_vars['k']->value>0) {?>
          ,
          <?php }?>
          parseFloat('<?php echo $_smarty_tpl->tpl_vars['v']->value["value"];?>
')
        <?php } ?>
      ];

      refreshPieChart(title, names, values, valuesBar);
    });//end layui.use()

    Array.prototype.sum = function (){
     return this.reduce(function (partial, value){
      return partial + value;
     })
    };

    function refreshPieChart(title, names, values, valuesBar)
    {
      // var total = valuesBar.sum().toFixed(2);
      var total = '<?php echo $_smarty_tpl->tpl_vars['total']->value;?>
';
      
      option = {
        title : {
            text: title,
            subtext: '<?php echo $_smarty_tpl->tpl_vars['config']->value['sy_webname'];?>
',
            x:'center'
        },
        tooltip : {
            trigger: 'item',
            formatter: "{b} : {c} ({d}%)"
          },
        legend: {
            x : 'center',
            y : 'bottom',
            data: names
        },
        // grid: [
        //   {x: '50%', y: '7%', width: '45%', height: '80%'},
        // ],

        // xAxis: [{
        //   type: 'value',
        //   axisLabel: {
        //       show: false
        //   },
        //   axisTick: {
        //       show: false
        //   },
        //   axisLine: {
        //       show: false
        //   },
        //   splitLine: {
        //       show: false
        //   }

        // }],
        // yAxis: [{
        //   type: 'category',
        //   boundaryGap: true,
        //   axisTick: {
        //       show: true
        //   },
        //   axisLabel: {
        //       interval: null
        //   },
        //   data: names,
        //   splitLine: {
        //       show: false
        //   }
        // }],
        series : [
          {
            name: '消费最多<?php echo $_smarty_tpl->tpl_vars['user']->value;?>
统计',
            type: 'pie',
            radius : '30%',
            // center: ['22%', '40%'],
            center: ['50%', '35%'],
            data:
              values,
            labelLine:{normal:{show:false}},
            itemStyle: {
              normal: {
                label:{
                 show: true,  
                 formatter: '{b}: {c} ({d}%)',
                 textStyle:{fontSize:"10"}
                } 
              }
            },
          },
          // {
          //     name: '消费最多<?php echo $_smarty_tpl->tpl_vars['user']->value;?>
统计',
          //     type: 'bar',
              
          //     barCategoryGap: '5%',//设置条柱间距
          //     // barWidth: '15', //设置条柱宽度后，条柱间距设置无效
          //     label: {
          //         normal: {
          //             show: true,
          //             position: 'right',
          //             formatter : '{c}/' + total
          //         }
          //     },
          //     data: valuesBar,
          //     center: ['70%', '40%']
          // }
        ]
      };//end option

      // 基于准备好的dom，初始化echarts实例
      var myChart = echarts.init(document.getElementById('chart'));

      // 使用刚指定的配置项和数据显示图表。
      myChart.setOption(option);
    }//end function refreshPieChart

    
    function query()
    {
      var gourl = '<?php echo $_smarty_tpl->tpl_vars['gourl']->value;?>
';
      window.location = gourl + '&time_begin=' + $("#time_begin").val() + '&time_end=' + $('#time_end').val();
      return false;
    }//end function query

    var flag = true;
    function showDetail(o)
    {
      if(flag){
        $("#chart").hide();
        $("#detail").show();
        flag = false;

        $(o).html('查看图表');
      }
      else{
        $("#detail").hide();
        $("#chart").show();
        flag = true;

        $(o).html('查看详情');
      }
      return false;
    }
  <?php echo '</script'; ?>
>
</body>
</html><?php }} ?>
