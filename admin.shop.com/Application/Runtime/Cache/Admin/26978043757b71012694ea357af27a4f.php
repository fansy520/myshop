<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>京东 管理中心--<?php echo ($meta_title); ?></title>
    <meta name="robots" content="noindex, nofollow">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="http://admin.shop.com/Public/Admin/Css/general.css" rel="stylesheet" type="text/css" />
    <link href="http://admin.shop.com/Public/Admin/Css/main.css" rel="stylesheet" type="text/css" />
    
    <link rel="stylesheet" type="text/css" href="http://admin.shop.com/Public/Admin/ext/treegrid/css/jquery.treegrid.css" />

</head>
<body>

<h1>
    <span class="action-span"><a href="<?php echo U('add');?>">添加商品分类</a></span>
    <span class="action-span1"><a href="<?php echo U('Index/index');?>">京东 管理中心</a></span>
    <span id="search_id" class="action-span1">--<?php echo ($meta_title); ?> </span>
    <div style="clear:both"></div>
</h1>
<div class="form-div">
    <form action="<?php echo U('');?>" name="searchForm">
    <img src="http://admin.shop.com/Public/Admin/Images/icon_search.gif" width="26" height="22" border="0" alt="search" />
    <input type="text" name="name" size="15" value="<?php echo I('get.name');?>" />
    <input type="submit" value=" 搜索 " class="button" />
    </form>
</div>
<form method="post" action="" name="listForm">
    <div class="list-div" id="listDiv">
        <table cellpadding="3" cellspacing="1" class="tree">
            <tr>
                <th>商品分类名称</th>
                <th>商品分类简介</th>
                <th>操作</th>
            </tr>
            <?php if(is_array($rows)): $i = 0; $__LIST__ = $rows;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$row): $mod = ($i % 2 );++$i;?><tr align="center" class='treegrid-<?php echo ($row["id"]); ?> <?php if(($row["level"]) != "1"): ?>treegrid-parent-<?php echo ($row["parent_id"]); endif; ?>'>
                <td align="center"><b><?php echo ($row["name"]); ?></b></td>
                <td align="center"><?php echo ($row["intro"]); ?></td>
                <!--<td align="center"><?php echo ($row["sort"]); ?></td>-->
                <td align="center">
                    <a href="<?php echo U('edit',['id'=>$row['id']]);?>" title="编辑">编辑</a> |
                    <a href="<?php echo U('delete',['id'=>$row['id']]);?>" title="移除">移除</a>
                </td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>

        </table>
    </div>
</form>

<div id="footer">
    共执行 <?php echo N('db_query');?> 个查询，用时  <?php echo G('viewStartTime','viewEndTime');?>s，内存占用 <?php echo number_format((memory_get_usage() - $GLOBALS['_startUseMems'])/1024,2);?> KB<br />
    版权所有 &copy; 2012-<?php echo date('Y');?> 京东商城，并保留所有权利。
</div>

    <script type="text/javascript" src="http://admin.shop.com/Public/Admin/Js/jquery.min.js"></script>
    <script type="text/javascript" src="http://admin.shop.com/Public/Admin/ext/treegrid/js/jquery.treegrid.js"></script>
        <script type="text/javascript">
            $(function(){
                $('.tree').treegrid();
            })
        </script>

</body>
</html>