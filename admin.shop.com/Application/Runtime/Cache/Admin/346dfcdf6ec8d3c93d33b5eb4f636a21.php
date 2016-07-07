<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>京东 管理中心--<?php echo ($meta_title); ?></title>
    <meta name="robots" content="noindex, nofollow">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="http://admin.shop.com/Public/Admin/Css/general.css" rel="stylesheet" type="text/css" />
    <link href="http://admin.shop.com/Public/Admin/Css/main.css" rel="stylesheet" type="text/css" />
    
    <link rel="stylesheet" type="text/css" href="http://admin.shop.com/Public/Admin/ext/ztree/css/zTreeStyle/zTreeStyle.css" />
    <link rel="stylesheet" type="text/css" href="http://admin.shop.com/Public/Admin/ext/uploadify/common.css" />
    <style type="text/css">
        /*上传文件的css*/
        .myupload-pre-item img{
            width:150px;
        }

        .myupload-pre-item{
            display:inline-block;
        }

        .myupload-pre-item a{
            position:relative;
            top:5px;
            right:15px;
            float:right;
            color:red;
            font-size:16px;
            text-decoration:none;
        }

        /*商品分类的ztree样式,需要css样式，js引入*/
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
    <span class="action-span"><a href="<?php echo U('index');?>">商品列表</a>
    </span>
    <span class="action-span1"><a href="<?php echo U('Index/index');?>">京东 管理中心</a></span>
    <span id="search_id" class="action-span1">--<?php echo ($meta_title); ?> </span>
    <div style="clear:both"></div>
</h1>
<div class="tab-div">
    <div id="tabbar-div">
        <p>
            <span class="tab-front" id="general-tab">通用信息</span>
        </p>
    </div>
    <div id="tabbody-div">
        <form enctype="multipart/form-data" action="<?php echo U('');?>" method="post">
            <table width="90%" id="general-table" align="center">
                <tr>
                    <td class="label">商品名称：</td>
                    <td><input type="text" name="name" value="<?php echo ($row["name"]); ?>" size="30" />
                    <span class="require-field">*</span></td>
                </tr>
                <tr>
                    <td class="label">商品货号： </td>
                    <td>
                        <input type="text" name="sn" value="<?php echo ($row["sn"]); ?>" size="20"/>
                        <span id="goods_sn_notice"></span>
                        <span class="notice-span" id="noticeGoodsSN">如果您不输入商品货号，系统将自动生成一个唯一的货号。</span>
                    </td>
                </tr>
                <tr>
                    <td class="label">商品LOGO：</td>
                    <td>
                        <input type="hidden" name="logo" value="<?php echo ($row["logo"]); ?>" id="logo-data"/>
                        <input type="file" id="logo"/>
                        <img src="<?php echo ($row["logo"]); ?>" id="logo-preview" style="margin-top: 5px;width:80px;"/>
                    </td>
                </tr>
                <tr>
                    <td class="label">商品品牌：</td>
                    <td>
                        <?php echo selects($brands,'id','name','brand_id',$row['brand_id']);?>
                        <!--<select name="brand_id">-->
                            <!--<option value="0">请选择...</option>-->
                            <!--<?php if(is_array($brands)): foreach($brands as $key=>$brand): ?>-->
                            <!--&lt;!&ndash;<option value="<<?php echo ($brand["brand_id"]); ?>>"><?php echo ($brand["name"]); ?></option>&ndash;&gt;-->
                            <!--<option value="<?php echo ($brand["id"]); ?>"><?php echo ($brand["name"]); ?></option>-->
                            <!--<?php endforeach; endif; ?>-->
                        <!--</select>-->
                    </td>
                </tr>
                <tr>
                    <td class="label">供货商：</td>
                    <td>
                        <?php echo selects($suppliers,'id','name','supplier_id',$row['supplier_id']);?>
                        <!--<select name="supplier_id">-->
                            <!--<option value="0">请选择...</option>-->
                            <!--<?php if(is_array($suppliers)): foreach($suppliers as $key=>$supplier): ?>-->
                                <!--<option value="<?php echo ($supplier["id"]); ?>"><?php echo ($supplier["name"]); ?></option>-->
                            <!--<?php endforeach; endif; ?>-->
                        <!--</select>-->
                    </td>
                </tr>
                <tr>
                    <td class="label">商品分类：</td>
                    <td>
                        <input type="hidden" name='goods_category_id' id="goods_category_id" value=""/>
                        <input type="text" id="goods_category_name" value="请选择" disabled="disabled" style="padding-left:5px;"/>
                        <ul id='goods-category-tree' class='ztree' style='height:auto;'></ul>
                    </td>
                </tr>
                <tr>
                    <td class="label">本店售价：</td>
                    <td>
                        <input type="text" name="shop_price" value="<?php echo ($row["shop_price"]); ?>" size="20"/>
                    </td>
                </tr>
                <tr>
                    <td class="label">市场售价：</td>
                    <td>
                        <input type="text" name="market_price" value="<?php echo ($row["market_price"]); ?>" size="20" />
                    </td>
                </tr>
                <tr>
                    <td class="label">商品数量：</td>
                    <td>
                        <!--<input type="text" name="num" size="8" value="<?php echo ((isset($row["num"]) && ($row["num"] !== ""))?($row["num"]):100); ?>"/>-->
                        <input type="text" name="stock" size="8" value="<?php echo ((isset($row["stock"]) && ($row["stock"] !== ""))?($row["stock"]):100); ?>"/>
                    </td>
                </tr>
                <tr>
                    <td class="label">是否上架：</td>
                    <td>
                        <label><input type="radio" name="is_on_sale" value="1" class='is_on_sale'/> 是</label>
                        <label><input type="radio" name="is_on_sale" value="0" class='is_on_sale'/> 否</label>
                    </td>
                </tr>
                <tr>
                    <td class="label">加入推荐：</td>
                    <td>
                        <label><input type="checkbox" name="goods_status[]" value="1" class='goods_status'/> 精品</label>
                        <label><input type="checkbox" name="goods_status[]" value="2" class='goods_status'/> 新品</label>
                        <label><input type="checkbox" name="goods_status[]" value="4" class='goods_status'/> 热销</label>
                    </td>
                </tr>
                <tr>
                    <td class="label">推荐排序：</td>
                    <td>
                        <input type="text" name="sort" size="5" value="<?php echo ((isset($row["sort"]) && ($row["sort"] !== ""))?($row["sort"]):20); ?>"/>
                    </td>
                </tr>
                <!--<tr>-->
                    <!--<td class="label">商品关键词：</td>-->
                    <!--<td>-->
                        <!--<input type="text" name="keywords" value="" size="40" /> 用空格分隔-->
                    <!--</td>-->
                <!--</tr>-->

                <tr>
                    <td class="label">商品详细描述：</td>
                    <td>
                        <textarea name="content" cols="40" rows="3" id='editor'><?php echo ($row["content"]); ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td class="label">商品相册：</td>
                    <td>
                        <div class="myupload-img-box">
                            <?php if(is_array($row["paths"])): foreach($row["paths"] as $key=>$path): ?><div class="myupload-pre-item">
                                    <img src="<?php echo ($path); ?>"/>
                                    <a href="#" data="<?php echo ($key); ?>" class="img_del">×</a>
                                </div><?php endforeach; endif; ?>
                        </div>

                        <div>
                            <input type="file" id='gallery' />
                        </div>
                    </td>
                </tr>
            </table>
            <div class="button-div">
                <input type='hidden' name='id' value='<?php echo ($row["id"]); ?>'/>
                <input type="submit" value=" 确定 " class="button"/>
                <input type="reset" value=" 重置 " class="button" />
            </div>
        </form>
    </div>
</div>

<div id="footer">
    共执行 <?php echo N('db_query');?> 个查询，用时  <?php echo G('viewStartTime','viewEndTime');?>s，内存占用 <?php echo number_format((memory_get_usage() - $GLOBALS['_startUseMems'])/1024,2);?> KB<br />
    版权所有 &copy; 2012-<?php echo date('Y');?> 京东商城，并保留所有权利。
</div>

    <script type="text/javascript" src="http://admin.shop.com/Public/Admin/Js/jquery.min.js"></script>
    <script type="text/javascript" src="http://admin.shop.com/Public/Admin/ext/ueditor/ueditorpersonal.config.js"></script>
    <script type="text/javascript" src="http://admin.shop.com/Public/Admin/ext/ueditor/ueditor.all.min.js"></script>
    <script type="text/javascript" src="http://admin.shop.com/Public/Admin/ext/ueditor/lang/zh-cn/zh-cn.js"></script>
    <script type="text/javascript" src="http://admin.shop.com/Public/Admin/ext/ztree/js/jquery.ztree.core.min.js"></script>
    <script type="text/javascript" src="http://admin.shop.com/Public/Admin/ext/uploadify/jquery.uploadify.min.js"></script>
    <script type="text/javascript" src="http://admin.shop.com/Public/Admin/ext/layer/layer.js"></script>
    <script type="text/javascript">
        $(function(){
            //实例化编辑器
            var ue = UE.getEditor('editor');
            goods_category_ztree_show();  //调用对应的方法

            $('.status').val([<?php echo ((isset($row["status"]) && ($row["status"] !== ""))?($row["status"]):1); ?>]);
            $('.is_on_sale').val([<?php echo ((isset($row["is_on_sale"]) && ($row["is_on_sale"] !== ""))?($row["is_on_sale"]):1); ?>]);   //回显是否上架

            //回显商品促销状态
            <?php if(isset($row)): ?>var goods_status = <?php echo ($row["goods_status"]); ?>;
            var goods_status_checked = [];
            if(goods_status & 1){
                goods_status_checked.unshift(1);
            }
            if(goods_status & 2){
                goods_status_checked.unshift(2);
            }
            if(goods_status & 4){
                goods_status_checked.unshift(4);
            }
            $('.goods_status').val(goods_status_checked);<?php endif; ?>;

            //上传logo
            up_logo();
            //上传相册
            up_gallery();

        //绑定删除事件 点击 x 删除图片
        $('.myupload-img-box').on('click','.img_del',function(){
//            alert(1);
//            var obj_node=$(this);
            var gallery_id=$(this).attr('data');
            var flag=true;
            var msg='删除成功！';
            if(gallery_id){    //若是数据表已经有的图片，则执行ajax方式
//                console.debug(data);
                var url='<?php echo U("Gallery/delete");?>';
                var data={id:gallery_id};
                $.getJSON(url,data,function(response){
                    flag=response.status;
                    msg =response.info;
                    console.debug(response);
                });

            }
            if(flag){
                $(this).parent().remove();   //删除父节点就直接删除
                layer.msg('已删除');
            }
            return false;

        });

        });
        //ztree方式展示商品分类
        function goods_category_ztree_show(){
            var setting = {
                data: {
                    simpleData: {
                        enable: true,
                        pIdKey: "parent_id",
                    }
                },
                callback: {
                    onClick: function(event,tree_id,tree_node){
                        //通过第三个参数获取到点击的节点
                        var goods_category_name = tree_node.name;
                        var goods_category_id = tree_node.id;
                        console.debug(goods_category_name,goods_category_id);
                        $('#goods_category_id').val(goods_category_id);
                        $('#goods_category_name').val(goods_category_name);
                    },
                }
            };

            var zNodes = <?php echo ($goods_categories); ?>;
            var goods_category_ztree = $.fn.zTree.init($("#goods-category-tree"), setting, zNodes);
                goods_category_ztree.expandAll(true);
                $('.status').val([<?php echo ((isset($row["status"]) && ($row["status"] !== ""))?($row["status"]):1); ?>]);

                <?php if(isset($row)): ?>var goods_category_node = goods_category_ztree.getNodeByParam('id',<?php echo ($row["goods_category_id"]); ?>);
                goods_category_ztree.selectNode(goods_category_node);
                $('#goods_category_id').val(goods_category_node.id);
                $('#goods_category_name').val(goods_category_node.name);<?php endif; ?>
        }

        function up_logo(){
            $('#logo').uploadify({
                swf: 'http://admin.shop.com/Public/Admin/ext/uploadify/uploadify.swf',//上传的flash程序
                uploader: '<?php echo U("Upload/upload");?>',//上传给那个文件处理
                buttonText:' 选择文件 ',//上传按钮的文字提示
                fileObjName:'logo',//上传文件的控件名称
                fileSizeLimit:'1024KB',//文件大小
                fileTypeExts:'*.jpg;*.png;*.gif',//允许上传的文件后缀
                removeTimeout:1,//上传完成后进度条停留时间
                onUploadSuccess:function(file,data,response){
                    data = $.parseJSON(data);
                    //上传成功
                    if(data.status){
                        //把地址放到隐藏域中
                        $('#logo-data').val(data.file_url);
                        $('#logo-preview').attr('src',data.file_url);
                        layer.msg('上传成功',{icon:6,time:1000});
                    }else{
                        layer.msg(data.msg,{icon:5,time:1000});
                    }
                },
            });
        }


        function up_gallery(){
            $('#gallery').uploadify({
                swf: 'http://admin.shop.com/Public/Admin/ext/uploadify/uploadify.swf',     //上传的flash程序
                uploader: '<?php echo U("Upload/upload");?>',      //上传给那个文件处理
                buttonText:'选择文件',                  //上传按钮的文字提示
                fileObjName:'logo',                     //上传文件的控件名称
                fileSizeLimit:'1024KB',                 //文件大小
                fileTypeExts:'*.jpg;*.jpeg;*.png;*.gif',//允许上传的文件后缀
                removeTimeout:2,                        //上传完成后进度条停留时间
                onUploadSuccess:function(file,data,response){
                    data = $.parseJSON(data);
                    //上传成功
                    if(data.status){
                        //把地址放到隐藏域中
                        //添加一个图片div,用来展示
                        var html = '<div class="myupload-pre-item">';
                        html += '<input type="hidden" name="path[]" value="' + data.file_url + '"/>';
                        html += '<img src="'+data.file_url+'"/>';
                        html += '<a href="#" class="img_del">×</a>';
                        html += '</div>';
                        $(html).appendTo($('.myupload-img-box'));
                        layer.msg('上传成功',{icon:6,time:1000});
                    }else{
                        layer.msg(data.msg,{icon:5,time:1000});
                    }
                },
            });
        }
    </script>


</body>
</html>