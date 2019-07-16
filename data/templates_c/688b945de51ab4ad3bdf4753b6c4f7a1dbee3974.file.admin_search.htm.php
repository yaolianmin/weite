<?php /* Smarty version Smarty-3.1.21-dev, created on 2019-03-13 11:44:29
         compiled from "/www/wwwroot/hr/app/template/admin/admin_search.htm" */ ?>
<?php /*%%SmartyHeaderCode:3769137165c887c9d6a18e9-31897290%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '688b945de51ab4ad3bdf4753b6c4f7a1dbee3974' => 
    array (
      0 => '/www/wwwroot/hr/app/template/admin/admin_search.htm',
      1 => 1517903408,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3769137165c887c9d6a18e9-31897290',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'search_list' => 0,
    'rows' => 0,
    't' => 0,
    'k' => 0,
    'rs' => 0,
    'row' => 0,
    'v' => 0,
    'r' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_5c887c9d70f026_23897000',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c887c9d70f026_23897000')) {function content_5c887c9d70f026_23897000($_smarty_tpl) {?><?php if (!is_callable('smarty_function_searchurl')) include '/www/wwwroot/hr/app/include/libs/plugins/function.searchurl.php';
?><div class="search_select">
<?php if ($_GET['keyword']!='') {?>
<a class="Search_jobs_c_a" href="<?php echo smarty_function_searchurl(array('m'=>$_GET['m'],'c'=>$_GET['c'],'untype'=>'keyword'),$_smarty_tpl);?>
">关键字：<?php echo $_GET['keyword'];?>
</a>
<?php }?>
 <?php  $_smarty_tpl->tpl_vars['rows'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['rows']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['search_list']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['rows']->key => $_smarty_tpl->tpl_vars['rows']->value) {
$_smarty_tpl->tpl_vars['rows']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['rows']->key;
?>
       <?php $_smarty_tpl->tpl_vars["t"] = new Smarty_variable($_smarty_tpl->tpl_vars['rows']->value['param'], null, 0);?>
             <?php if ($_GET[$_smarty_tpl->tpl_vars['t']->value]!==false&&$_GET[$_smarty_tpl->tpl_vars['t']->value]!='') {?>
                <?php  $_smarty_tpl->tpl_vars['rs'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['rs']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['rows']->value['value']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['rs']->key => $_smarty_tpl->tpl_vars['rs']->value) {
$_smarty_tpl->tpl_vars['rs']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['rs']->key;
?>
                    <?php if ($_GET[$_smarty_tpl->tpl_vars['t']->value]==$_smarty_tpl->tpl_vars['k']->value) {?>
                    <a class="Search_jobs_c_a" href="<?php echo smarty_function_searchurl(array('m'=>$_GET['m'],'c'=>$_GET['c'],'untype'=>$_smarty_tpl->tpl_vars['t']->value),$_smarty_tpl);?>
">
                        <?php echo $_smarty_tpl->tpl_vars['rows']->value['name'];?>
：<?php echo $_smarty_tpl->tpl_vars['rs']->value;?>

                    </a>
                    <?php }?>
                <?php } ?>
            <?php }?> 
		<?php } ?>
</div>

<div class="clear"></div>
<div class="admin_screenlist_box">
<?php $_smarty_tpl->tpl_vars["v"] = new Smarty_variable(0, null, 0);?>
<?php  $_smarty_tpl->tpl_vars['row'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['row']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['search_list']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['row']->key => $_smarty_tpl->tpl_vars['row']->value) {
$_smarty_tpl->tpl_vars['row']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['row']->key;
?>
    <?php $_smarty_tpl->tpl_vars["t"] = new Smarty_variable($_smarty_tpl->tpl_vars['row']->value['param'], null, 0);?>
    <?php $_smarty_tpl->tpl_vars["v"] = new Smarty_variable($_smarty_tpl->tpl_vars['v']->value+1, null, 0);?>
    <div class="admin_screenlist">
    <span class="admin_screenlist_name"><?php echo $_smarty_tpl->tpl_vars['row']->value['name'];?>
：</span>
    	<a href="<?php echo smarty_function_searchurl(array('m'=>$_GET['m'],'c'=>$_GET['c'],'untype'=>$_smarty_tpl->tpl_vars['t']->value),$_smarty_tpl);?>
" <?php if ($_GET[$_smarty_tpl->tpl_vars['t']->value]!==true&&$_GET[$_smarty_tpl->tpl_vars['t']->value]=='') {?>class="admin_screenlist_cur"<?php }?>>全部</a>
        <?php  $_smarty_tpl->tpl_vars['r'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['r']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['row']->value['value']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['r']->key => $_smarty_tpl->tpl_vars['r']->value) {
$_smarty_tpl->tpl_vars['r']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['r']->key;
?>
            <a href="<?php echo smarty_function_searchurl(array('m'=>$_GET['m'],'c'=>$_GET['c'],'adv'=>$_smarty_tpl->tpl_vars['k']->value,'adt'=>$_smarty_tpl->tpl_vars['t']->value,'untype'=>$_smarty_tpl->tpl_vars['t']->value),$_smarty_tpl);?>
" 
            <?php if ($_GET[$_smarty_tpl->tpl_vars['t']->value]!==false&&$_GET[$_smarty_tpl->tpl_vars['t']->value]!=''&&$_GET[$_smarty_tpl->tpl_vars['t']->value]==$_smarty_tpl->tpl_vars['k']->value) {?>
            class="admin_screenlist_cur"
            <?php }?>><?php echo $_smarty_tpl->tpl_vars['r']->value;?>
</a> 
        <?php } ?>   
    </div>
<?php } ?>
<div class="admin_screenlist_more"><a href="javascript:;" onclick="searchmore()">收起更多条件</a></div>
</div>
<?php echo '<script'; ?>
>
function searchmore(){
    var html=$(".admin_screenlist_box").toggle();
}
<?php echo '</script'; ?>
>
<div class="clear"></div><?php }} ?>
