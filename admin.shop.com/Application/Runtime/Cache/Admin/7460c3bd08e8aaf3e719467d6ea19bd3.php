<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>京东 管理中心--<?php echo ($meta_title); ?></title>
    <meta name="robots" content="noindex, nofollow">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="http://admin.shop.com/Public/Admin/Css/general.css" rel="stylesheet" type="text/css" />
    <link href="http://admin.shop.com/Public/Admin/Css/main.css" rel="stylesheet" type="text/css" />
    
    <link rel="stylesheet" type="text/css" href="http://admin.shop.com/Public/Admin/Css/page.css" />

</head>
<body>

<h1>
    <span class="action-span"><a href="<?php echo U('add');?>">添加新商品</a></span>
    <span class="action-span1"><a href="<?php echo U('Index/index');?>">京东 管理中心</a></span>
    <span id="search_id" class="action-span1">--商品列表 </span>
    <div style="clear:both"></div>
</h1>
<div class="form-div">
    <form action="<?php echo U('');?>" name="searchForm">
        <img src="http://admin.shop.com/Public/Admin/Images/icon_search.gif" width="26" height="22" border="0" alt="search" />
        <!-- 分类 -->
        所有分类：
        <?php echo selects($goods_categories,'id','name','goods_category_id',I('goods_category_id'));?>
        <!--<select name="cat_id">-->
            <!--<option value="0">所有分类</option>-->
            <!--<?php if(is_array($cat_list)): foreach($cat_list as $key=>$val): ?>-->
            <!--<option value="<<?php echo ($val["cat_id"]); ?>>"><<?php echo (str_repeat('&nbsp;&nbsp;',$val["lev"])); ?>><<?php echo ($val["cat_name"]); ?>></option>-->
            <!--<?php endforeach; endif; ?>-->
        <!--</select>-->
        <!-- 品牌 -->
        品牌：
        <?php echo selects($brands,'id','name','brands_id',I('brands_id'));?>

        <!-- 推荐 -->
        推荐：
        <!--:onearr2select($goods_statuses,'goods_status',I('get.goods_status'))}-->
        <?php echo select($goods_statuses,'goods_status',I('get.goods_status'));?>

        <!-- 上架 -->
        是否上架：
        <?php echo select($is_on_sales,'is_on_sale',I('get.is_on_sale'));?>
        <!-- 关键字 -->
        关键字 <input type="text" name="keyword" size="15" value="<?php echo I('get.keyword');?>" />
        <input type="submit" value=" 搜索 " class="button" />
    </form>
</div>

<!-- 商品列表 -->
<form method="post" action="" name="listForm" onsubmit="">
    <div class="list-div" id="listDiv">
        <table cellpadding="3" cellspacing="1">
            <tr>
                <th>编号</th>
                <th>商品名称</th>
                <th>货号</th>
                <th>商品LOGO</th>
                <!--<th>商品品牌</th>-->
                <!--<th>供货商</th>-->
                <!--<th>商品分类</th>-->
                <th>本店价格/市场价格</th>
                <!--<th>商品数量</th>-->
                <th>是否上架</th>
                <th>精品</th>
                <th>新品</th>
                <th>热销</th>
                <th>推荐排序</th>
                <th>库存</th>
                <!--<th>详细介绍</th>-->
                <!--<th>商品相册</th>-->
                <th>操作</th>
            </tr>
            <?php if(is_array($rows)): foreach($rows as $key=>$row): ?><tr>
                <td align="center"><?php echo ($row["id"]); ?></td>
                <td align="center" class="first-cell"><?php echo ($row["name"]); ?></td>
                <td align="center"><?php echo ($row["sn"]); ?></td>
                <td align="center"><img src="<?php echo ($row["logo"]); ?>" style="margin-top:2px;width:36px;"/></td>
                <!--<td align="center"><?php echo ($row["brand_id"]); ?></td>-->
                <!--<td align="center"><?php echo ($row["supplier_id"]); ?></td>-->
                <!--<td align="center"><?php echo ($row["goods_category_id"]); ?></td>-->
                <td align="center"><?php echo ($row["shop_price"]); ?>/<?php echo ($row["market_price"]); ?></td>
                <!--<td align="center"><?php echo ($row["num"]); ?></td>-->
                <td align="center"><img src="http://admin.shop.com/Public/Admin/Images/<?php echo ($row["is_on_sale"]); ?>.gif"/></td>
                <td align="center"><img src="http://admin.shop.com/Public/Admin/Images/<?php echo ($row["is_best"]); ?>.gif"/></td>
                <td align="center"><img src="http://admin.shop.com/Public/Admin/Images/<?php echo ($row["is_new"]); ?>.gif"/></td>
                <td align="center"><img src="http://admin.shop.com/Public/Admin/Images/<?php echo ($row["is_hot"]); ?>.gif"/></td>
                <td align="center"><?php echo ($row["sort"]); ?></td>
                <td align="center"><?php echo ($row["stock"]); ?></td>
                <!--<td align="center"><?php echo ($row["intro"]); ?></td>-->
                <!--<td align="center"><?php echo ($row["path"]); ?></td>-->
                <td align="center">
                    <a href="<?php echo U('edit',['id'=>$row['id']]);?>" title="编辑"><img src="http://admin.shop.com/Public/Admin/Images/icon_edit.gif" width="16" height="16" border="0" /></a>
                    <a href="<?php echo U('delete',['id'=>$row['id']]);?>" onclick="" title="移除"><img src="http://admin.shop.com/Public/Admin/Images/icon_trash.gif" width="16" height="16" border="0" /></a>
                </td>
            </tr><?php endforeach; endif; ?>
        </table>

    <!-- 分页开始 -->
        <table id="page-table" cellspacing="0">
            <tr>
                <!--<td width="80%">&nbsp;</td>-->
                <td align="center" nowrap="true" colspan="10">
                    <div class="page"><?php echo ($page_html); ?></div>
                </td>
            </tr>
        </table>
    <!-- 分页结束 -->
    </div>
</form>

<div id="footer">
    共执行 <?php echo N('db_query');?> 个查询，用时  <?php echo G('viewStartTime','viewEndTime');?>s，内存占用 <?php echo number_format((memory_get_usage() - $GLOBALS['_startUseMems'])/1024,2);?> KB<br />
    版权所有 &copy; 2012-<?php echo date('Y');?> 京东商城，并保留所有权利。
</div>

</body>
</html>