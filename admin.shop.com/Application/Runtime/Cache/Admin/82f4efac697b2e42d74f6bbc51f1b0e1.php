<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>京东 管理中心--<?php echo ($meta_title); ?></title>
    <meta name="robots" content="noindex, nofollow">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="http://admin.shop.com/Public/Admin/Css/general.css" rel="stylesheet" type="text/css" />
    <link href="http://admin.shop.com/Public/Admin/Css/main.css" rel="stylesheet" type="text/css" />
    
    <link rel="stylesheet" type="text/css" href="http://admin.shop.com/Public/Admin/ext/uploadify/common.css" />
    <link rel="stylesheet" type="text/css" href="http://admin.shop.com/Public/Admin/ext/ztree/css/zTreeStyle/zTreeStyle.css" />
    <style type='text/css'>
        .ztree{
            width:220px;
            height:auto;
            margin-top: 10px;
            border: 1px solid #617775;
            background: #f0f6e4;
            overflow-y: scroll;
            overflow-x: auto;
        }
    </style>

</head>
<body>

    <h1>
        <span class="action-span"><a href="<?php echo U('index');?>">管理员管理</a></span>
        <span class="action-span1"><a href="#">京东 管理中心</a></span>
        <span id="search_id" class="action-span1"> - <?php echo ($meta_title); ?> </span>
    </h1>
    <div style="clear:both"></div>
    <div class="main-div">
        <form method="post" action="<?php echo U('');?>" enctype="multipart/form-data" >
            <table cellspacing="1" cellpadding="3" width="100%">
                <tr>
                    <td class="label">管理员名称</td>
                    <td>
                    <?php if(isset($row)): echo ($row["username"]); ?>
                    <?php else: ?>
                        <input type="text" name="username" maxlength="60"/><?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td class="label">关联角色</td>
                    <td>
                        <?php if(is_array($roles)): foreach($roles as $key=>$role): ?><label><input type='checkbox' name='role_id[]' value='<?php echo ($role["id"]); ?>' class='role_id' /><?php echo ($role["name"]); ?></label><?php endforeach; endif; ?>
                    </td>
                </tr>
                <tr>
                    <td class="label">密码</td>
                    <td>
                        <?php if(isset($row)): ?><input type="password" name="password" placeholder='如需修改请输入密码'/>
                        <?php else: ?>
                            <input type="password" name="password" placeholder='请输入密码'/><?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td class="label">确认密码</td>
                    <td>
                        <input type="password" name="repassword" placeholder='请再次输入密码'/>
                    </td>
                </tr>
                <tr>
                    <td class="label">邮箱</td>
                    <td>
                        <?php if(isset($row)): echo ($row["email"]); ?>
                        <?php else: ?>
                            <input type="text" name="email"/><?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td class="label">额外权限</td>
                    <td>
                        <div id='permission-ids'></div>
                        <ul id='permission-tree' class='ztree' style='height:auto;'></ul>
                    </td>
                </tr>
                
                <tr>
                    <td colspan="2" align="center"><br />
                        <input type="hidden" name="id" value="<?php echo ($row["id"]); ?>"/>
                        <input type="submit" class="button" value=" 确定 " />
                        <input type="reset" class="button" value=" 重置 " />
                    </td>
                </tr>
            </table>
        </form>
    </div>

<div id="footer">
    共执行 <?php echo N('db_query');?> 个查询，用时  <?php echo G('viewStartTime','viewEndTime');?>s，内存占用 <?php echo number_format((memory_get_usage() - $GLOBALS['_startUseMems'])/1024,2);?> KB<br />
    版权所有 &copy; 2012-<?php echo date('Y');?> 京东商城，并保留所有权利。
</div>

    <script type="text/javascript" src="http://admin.shop.com/Public/Admin/Js/jquery.min.js"></script>
    <script type="text/javascript" src="http://admin.shop.com/Public/Admin/ext/uploadify/jquery.uploadify.min.js"></script>
    <script type="text/javascript" src="http://admin.shop.com/Public/Admin/ext/ztree/js/jquery.ztree.core.min.js"></script>
    <script type="text/javascript" src="http://admin.shop.com/Public/Admin/ext/ztree/js/jquery.ztree.excheck.js"></script>
    <script type="text/javascript" src="http://admin.shop.com/Public/Admin/ext/layer/layer.js"></script>
    <script type="text/javascript">
        var setting = {
            check: {
                enable: true,
                chkboxType: { "Y" : "s", "N" : "s" },
            },
            data: {
                simpleData: {
                    enable: true,
                    pIdKey: "parent_id",
                },
            },
            callback: {
                onCheck: function(){
                    //获取所有的已勾选的节点列表
                    var nodes = permission_tree.getCheckedNodes(true);
                    
                    permission_ids_node.empty();
                    $(nodes).each(function(){
                        //添加节点到div中
                        var html = "<input type='hidden' name='permission_id[]' value='"+this.id+"'/>";
                        $(html).appendTo(permission_ids_node)
                    });
                },
            }
        };

        var zNodes = <?php echo ($permissions); ?>;
        var permission_tree=null;//为了在各种函数中使用,这里创建了一个全局变量.
        var permission_ids_node = $('#permission-ids');
        $(function () {
            //回显角色
            $('.role_id').val(<?php echo ($row["role_ids"]); ?>);

            
            //初始化额外权限
            permission_tree = $.fn.zTree.init($("#permission-tree"), setting, zNodes);
            permission_tree.expandAll(true);
            //回显状态
            <?php if(isset($row)): ?>//回显所拥有权限
                var permission_ids = <?php echo ($row["permission_ids"]); ?>;
                $(permission_ids).each(function(i,e){
                    //获取到这个节点
                    var node = permission_tree.getNodeByParam('id',e);
                    permission_tree.checkNode(node,true);
                    //添加节点到div中
                    var html = "<input type='hidden' name='permission_id[]' value='"+e+"'/>";
                    $(html).appendTo(permission_ids_node);
                });<?php endif; ?>
        });
    </script>

</body>
</html>