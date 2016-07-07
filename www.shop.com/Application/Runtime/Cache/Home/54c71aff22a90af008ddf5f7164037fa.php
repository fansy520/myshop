<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
        <title>京东商城</title>
        
    <link rel="stylesheet" href="http://www.shop.com/Public/Home/css/home.css" type="text/css">
    <link rel="stylesheet" href="http://www.shop.com/Public/Home/css/address.css" type="text/css">
    <link rel="stylesheet" href="http://www.shop.com/Public/Home/css/bottomnav.css" type="text/css">

        <link rel="stylesheet" href="http://www.shop.com/Public/Home/css/base.css" type="text/css"/>
        <link rel="stylesheet" href="http://www.shop.com/Public/Home/css/global.css" type="text/css"/>
        <link rel="stylesheet" href="http://www.shop.com/Public/Home/css/header.css" type="text/css"/>
        <link rel="stylesheet" href="http://www.shop.com/Public/Home/css/footer.css" type="text/css"/>
    </head>
    <body>
        <!-- 顶部导航 start -->
        <div class="topnav">
            <div class="topnav_bd w1210 bc">
                <div class="topnav_left">

                </div>
                <div class="topnav_right fr">
                    <ul>
                        <li id='userinfo'>
                        <!--<li>您好，欢迎来到京西！[<a href="<?php echo U('Member/login');?>">登录</a>] [<a href="<?php echo U('Member/register');?>">免费注册</a>] </li>-->
                        <!--<li>您好<?php echo ($user_info["username"]); ?>，欢迎来到京西！-->
                            <!--<?php if(isset($user_info)): ?>-->
                                <!--<?php else: ?>-->
                                <!--[<a href="<?php echo U('Member/login');?>">登录</a>] [<a href="<?php echo U('Member/register');?>">免费注册</a>]-->
                            <!--<?php endif; ?>-->

                        <!--</li>-->
                        </li>
                        <li class="line">|</li>
                        <li>我的订单</li>
                        <li class="line">|</li>
                        <li>客户服务</li>

                    </ul>
                </div>
            </div>
        </div>
        <!-- 顶部导航 end -->

        <div style="clear:both;"></div>

        <!-- 头部 start -->
        <div class="header w1210 bc mt15">
            <!-- 头部上半部分 start 包括 logo、搜索、用户中心和购物车结算 -->
            <div class="logo w1210">
                <h1 class="fl"><a href="<?php echo U('Index/index');?>"><img src="http://www.shop.com/Public/Home/images/jd.png" alt="京东商城"></a></h1>
                <!-- 头部搜索 start -->
                <div class="search fl">
                    <div class="search_form">
                        <div class="form_left fl"></div>
                        <form action="" name="serarch" method="get" class="fl">
                            <input type="text" class="txt" value="请输入商品关键字" /><input type="submit" class="btn" value="搜索" />
                        </form>
                        <div class="form_right fl"></div>
                    </div>

                    <div style="clear:both;"></div>

                    <div class="hot_search">
                        <strong>热门搜索:</strong>
                        <a href="">D-Link无线路由</a>
                        <a href="">休闲男鞋</a>
                        <a href="">TCL空调</a>
                        <a href="">耐克篮球鞋</a>
                    </div>
                </div>
                <!-- 头部搜索 end -->

                <!-- 用户中心 start-->
                <div class="user fl">
                    <dl>
                        <dt>
                            <em></em>
                            <a href="">用户中心</a>
                            <b></b>
                        </dt>
                        <dd>
                            <div class="prompt">
                                您好，请<a href="<?php echo U('Member/login');?>">登录</a>
                            </div>
                            <div class="uclist mt10">
                                <ul class="list1 fl">
                                    <li><a href="">用户信息></a></li>
                                    <li><a href="">我的订单></a></li>
                                    <li><a href="">收货地址></a></li>
                                    <li><a href="">我的收藏></a></li>
                                </ul>

                                <ul class="fl">
                                    <li><a href="">我的留言></a></li>
                                    <li><a href="">我的红包></a></li>
                                    <li><a href="">我的评论></a></li>
                                    <li><a href="">资金管理></a></li>
                                </ul>

                            </div>
                            <div style="clear:both;"></div>
                            <div class="viewlist mt10">
                                <h3>最近浏览的商品：</h3>
                                <ul>
                                    <li><a href=""><img src="http://www.shop.com/Public/Home/images/view_list1.jpg" alt="" /></a></li>
                                    <li><a href=""><img src="http://www.shop.com/Public/Home/images/view_list2.jpg" alt="" /></a></li>
                                    <li><a href=""><img src="http://www.shop.com/Public/Home/images/view_list3.jpg" alt="" /></a></li>
                                </ul>
                            </div>
                        </dd>
                    </dl>
                </div>
                <!-- 用户中心 end-->

                <!-- 购物车 start -->
                <div class="cart fl">
                    <dl>
                        <dt>
                            <a href="<?php echo U('cart/flow1');?>">去购物车结算</a>
                        </dt>
                        <dd>
                            <div class="prompt">
                                购物车中还没有商品，赶紧选购吧！
                            </div>
                        </dd>
                    </dl>
                </div>
                <!-- 购物车 end -->
            </div>
            <!-- 头部上半部分 end -->

            <div style="clear:both;"></div>

            <!-- 导航条部分 start -->
            <div class="nav w1210 bc mt10">
                <!--  商品分类部分 start-->
                <div class="category fl <?php if(!$is_show_cat): ?>cat1<?php endif; ?>"> <!-- 非首页，需要添加cat1类 -->
                    <div class="cat_hd <?php if(!$is_show_cat): ?>off<?php endif; ?>">  <!-- 注意，首页在此div上只需要添加cat_hd类，非首页，默认收缩分类时添加上off类，鼠标滑过时展开菜单则将off类换成on类 -->
                        <h2>全部商品分类</h2>
                        <em></em>
                    </div>

                    <div class="cat_bd <?php if(!$is_show_cat): ?>none<?php endif; ?>">
                        <?php if(is_array($goods_categories)): foreach($goods_categories as $key=>$top_category): if(($top_category["parent_id"]) == "0"): ?><div class="cat item1">
                                    <h3><a href=""><?php echo ($top_category["name"]); ?></a> <b></b></h3>
                                    <div class="cat_detail">
                                        <?php if(is_array($goods_categories)): foreach($goods_categories as $key=>$second_category): if(($second_category["parent_id"]) == $top_category["id"]): ?><dl class="dl_1st">
                                                    <dt><a href=""><?php echo ($second_category["name"]); ?></a></dt>
                                                    <dd>
                                                        <?php if(is_array($goods_categories)): foreach($goods_categories as $key=>$third_category): if(($third_category["parent_id"]) == $second_category["id"]): ?><a href="<?php echo U('Goods/List',['id'=>$third_category['id']]);?>"><?php echo ($third_category["name"]); ?></a><?php endif; endforeach; endif; ?>						
                                                    </dd>
                                                </dl><?php endif; endforeach; endif; ?>
                                    </div>
                                </div><?php endif; endforeach; endif; ?>
                    </div>

                </div>
                <!--  商品分类部分 end--> 

                <div class="navitems fl">
                    <ul class="fl">
                        <li class="current"><a href="">首页</a></li>
                        <li><a href="">电脑频道</a></li>
                        <li><a href="">家用电器</a></li>
                        <li><a href="">品牌大全</a></li>
                        <li><a href="">团购</a></li>
                        <li><a href="">积分商城</a></li>
                        <li><a href="">夺宝奇兵</a></li>
                    </ul>
                    <div class="right_corner fl"></div>
                </div>
            </div>
            <!-- 导航条部分 end -->
        </div>
        <!-- 头部 end-->

        <div style="clear:both;"></div>
        
    <!-- 页面主体 start -->
    <div class="main w1210 bc mt10">
        <div class="crumb w1210">
            <h2><strong>我的XX </strong><span>> 我的订单</span></h2>
        </div>

        <!-- 左侧导航菜单 start -->
        <div class="menu fl">
            <h3>我的XX</h3>
            <div class="menu_wrap">
                <dl>
                    <dt>订单中心 <b></b></dt>
                    <dd><b>.</b><a href="">我的订单</a></dd>
                    <dd><b>.</b><a href="">我的关注</a></dd>
                    <dd><b>.</b><a href="">浏览历史</a></dd>
                    <dd><b>.</b><a href="">我的团购</a></dd>
                </dl>

                <dl>
                    <dt>账户中心 <b></b></dt>
                    <dd class="cur"><b>.</b><a href="">账户信息</a></dd>
                    <dd><b>.</b><a href="">账户余额</a></dd>
                    <dd><b>.</b><a href="">消费记录</a></dd>
                    <dd><b>.</b><a href="">我的积分</a></dd>
                    <dd><b>.</b><a href="">收货地址</a></dd>
                </dl>

                <dl>
                    <dt>订单中心 <b></b></dt>
                    <dd><b>.</b><a href="">返修/退换货</a></dd>
                    <dd><b>.</b><a href="">取消订单记录</a></dd>
                    <dd><b>.</b><a href="">我的投诉</a></dd>
                </dl>
            </div>
        </div>
        <!-- 左侧导航菜单 end -->

        <!-- 右侧内容区域 start -->
        <div class="content fl ml10">
            <h3>新增收货地址</h3>
            <div class="address_bd mt10 address_hd">
                <form action="<?php echo U();?>" name="address_form" method='post'>
                    <ul>
                        <li>
                            <label for=""><span>*</span>收 货 人：</label>
                            <input type="text" name="name" class="txt" value='<?php echo ($row["name"]); ?>'/>
                        </li>
                        <li>
                            <label for=""><span>*</span>所在地区：</label>
                            <?php echo selects($provinces,'id','name','province_id',$row['province_id']);?>
                            <input type="hidden" name='province_name' class='province_name' value='<?php echo ($row["province_name"]); ?>'/>
                            
                            <?php echo selects([],'id','name','city_id');?>
                            <input type="hidden" name='city_name' class='city_name' value='<?php echo ($row["city_name"]); ?>'/>

                            <?php echo selects([],'id','name','area_id');?>
                            <input type="hidden" name='area_name' class='area_name' value='<?php echo ($row["area_name"]); ?>'/>
                        </li>
                        <li>
                            <label for=""><span>*</span>详细地址：</label>
                            <input type="text" name="detail_address" class="txt address"  value='<?php echo ($row["detail_address"]); ?>' />
                        </li>
                        <li>
                            <label for=""><span>*</span>手机号码：</label>
                            <input type="text" name="tel" class="txt"  value='<?php echo ($row["tel"]); ?>'/>
                        </li>
                        <li>
                            <label for="">&nbsp;</label>
                            <input type="checkbox" name="is_default" class="check" value='1'/>设为默认地址
                        </li>
                        <li>
                            <label for="">&nbsp;</label>
                            <input type='hidden' name='id' value='<?php echo ($row["id"]); ?>' />
                            <input type="submit" name="" class="btn" value="保存" />
                        </li>
                    </ul>
                </form>
            </div>	

        </div>
        <!-- 右侧内容区域 end -->
    </div>
    <!-- 页面主体 end-->



        <div style="clear:both;"></div>

        <!-- 底部导航 start -->
        <div class="bottomnav w1210 bc mt10">
            <?php if(is_array($article_categories)): foreach($article_categories as $key=>$article_cat): ?><div class="bnav<?php echo ($key); ?>">
                <h3><b></b> <em><?php echo ($article_cat); ?></em></h3>
                <ul>
                    <?php if(is_array($article_list[$key])): foreach($article_list[$key] as $key=>$article): ?><li><a href="<?php echo U('Article/show',['id'=>$article['id']]);?>"><?php echo ($article["name"]); ?></a></li><?php endforeach; endif; ?>
                </ul>
            </div><?php endforeach; endif; ?>
        </div>
        <!-- 底部导航 end -->

        <div style="clear:both;"></div>
        <!-- 底部版权 start -->
        <div class="footer w1210 bc mt10">
            <p class="links">
                <a href="">关于我们</a> |
                <a href="">联系我们</a> |
                <a href="">人才招聘</a> |
                <a href="">商家入驻</a> |
                <a href="">千寻网</a> |
                <a href="">奢侈品网</a> |
                <a href="">广告服务</a> |
                <a href="">移动终端</a> |
                <a href="">友情链接</a> |
                <a href="">销售联盟</a> |
                <a href="">京东论坛</a>
            </p>
            <p class="copyright">
                © 2005-2013 京东网上商城 版权所有，并保留所有权利。  ICP备案证书号:京ICP证070359号 
            </p>
            <p class="auth">
                <a href=""><img src="http://www.shop.com/Public/Home/images/xin.png" alt="" /></a>
                <a href=""><img src="http://www.shop.com/Public/Home/images/kexin.jpg" alt="" /></a>
                <a href=""><img src="http://www.shop.com/Public/Home/images/police.jpg" alt="" /></a>
                <a href=""><img src="http://www.shop.com/Public/Home/images/beian.gif" alt="" /></a>
            </p>
        </div>
        <!-- 底部版权 end -->
        <script type="text/javascript" src="http://www.shop.com/Public/Home/js/jquery.min.js"></script>
        <script type="text/javascript" src="http://www.shop.com/Public/Home/js/header.js"></script>
        <script type='text/javascript'>
            /**
             * [username:'fansy2']
             * 
             * 您好<?php echo ($user_info["username"]); ?>，欢迎来到京东！
                                    [<a href="<?php echo U('Member/login');?>">登录</a>] [<a href="<?php echo U('Member/register');?>">免费注册</a>]
             * @param {type} param1
             * @param {type} param2
             */
            $.getJSON('<?php echo U("Member/getUserInfo");?>',function(response){
                if(response){
                    var html = '您好'+response+'，欢迎来到京东！[<a href="<?php echo U('Member/logout');?>">退出</a>]';
                }else{
                    var html = '您好,欢迎来到京东![<a href="<?php echo U('Member/login');?>">登录</a>] [<a href="<?php echo U('Member/register');?>">免费注册</a>]';
                }
                $('#userinfo').html(html);
            });
        </script>
        
    <script type="text/javascript" src="http://www.shop.com/Public/Home/js/home.js"></script>
    <script type="text/javascript">
        var url,city_node,area_node,province_name_node,city_name_node,area_name_node;
        $(function(){
            //绑定事件--改编省份的时候获取对应的市级菜单
            url = '<?php echo U("getListByParentId");?>';
            city_node = $('.city_id');
            area_node = $('.area_id');
            province_name_node = $('.province_name');//省名隐藏域
            city_name_node = $('.city_name');//市名隐藏域
            area_name_node = $('.area_name');//区名隐藏域
            $('.province_id').change(function(){
                var data={id:$(this).val()};
                province_name_node.val($(this).find('option:selected').text());//省名
                city_name_node.val('');
                area_name_node.val('');
                //获取到市级菜单
                $.getJSON(url,data,function(cities){
                    //遍历市级菜单展示在city_id
                    //找到要放市级菜单的节点
                    city_node[0].length = 1;//市级清空
                    area_node[0].length = 1;//地区清空
                    $(cities).each(function(i,city){
                        var html = '<option value="'+city.id+'">'+city.name+'</option>';
                        $(html).appendTo(city_node)
                    });
                });
            });
            
            //绑定--改市级的时候获取对应的地区菜单
            $('.city_id').change(function(){
                var data={id:$(this).val()};
                city_name_node.val($(this).find('option:selected').text());//市级
                area_name_node.val('');//区县清空
                //获取到市级菜单
                $.getJSON(url,data,function(areas){
                    //遍历市级菜单展示在city_id
                    //找到要放市级菜单的节点
                    area_node[0].length = 1;
                    $(areas).each(function(i,area){
                        var html = '<option value="'+area.id+'">'+area.name+'</option>';
                        $(html).appendTo(area_node)
                    });
                });
            });
            
            //切换区县
            $('.area_id').change(function(){
                area_name_node.val($(this).find('option:selected').text());//区县
            });
            
            
            
        });
        
        //------------------------三级联动分割线---------------------------------
        //回显数据--是否默认
        <?php if(isset($row)): ?>$(function(){
                $('.check').val([<?php echo ($row["is_default"]); ?>]);


                //回显三级联动地址
                //1.读取市级菜单列表
                data = {id:<?php echo ($row["province_id"]); ?>};
                $.getJSON(url,data,function(cities){
                    //遍历市级菜单展示在city_id
                    //找到要放市级菜单的节点
                    city_node[0].length = 1;//市级清空
                    area_node[0].length = 1;//地区清空
                    //1.1展示市级菜单列表
                    $(cities).each(function(i,city){
                        var html = '<option value="'+city.id+'">'+city.name+'</option>';
                        $(html).appendTo(city_node)
                    });
                    city_node.val([<?php echo ($row["city_id"]); ?>]);
                    
                    //2.读取区县列表
                    var data = {id:<?php echo ($row["city_id"]); ?>};
                    $.getJSON(url,data,function(areas){
                        //遍历市级菜单展示在city_id
                        //找到要放市级菜单的节点
                        area_node[0].length = 1;
                        //2.1展示区县列表
                        $(areas).each(function(i,area){
                            var html = '<option value="'+area.id+'">'+area.name+'</option>';
                            $(html).appendTo(area_node)
                        });
                        area_node.val([<?php echo ($row["area_id"]); ?>]);
                    });
                    
                });
            });<?php endif; ?>
    </script>

    </body>
</html>