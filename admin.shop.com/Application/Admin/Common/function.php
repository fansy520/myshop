<?php
/**
 * 循环输出下拉列表的方法
 * @param array $data   结果集
 * @param string $field_value  值字段名
 * @param string $field_name   提示文字字段名（可以理解为option的字段名的值）
 * @param $name  下拉列表的属性值，用于提交表单的
 * @return string
 */
function selects(array $data, $field_value, $field_name, $name, $selected_value = ''){
//    $html='<select name="brand_id">';
    $html='<select name="'.$name.'">';
    $html.='<option value="">请选择...</option>';
        foreach($data as $val){
            if($selected_value==$val[$field_value]){
                $html.='<option value="'.$val[$field_value].'"selected="selected">'.$val[$field_name].'</option>';
            }else{
                $html.='<option value="'.$val[$field_value].'">'.$val[$field_name].'</option>';
            }
        }
    $html.='</select>';
    return $html;
}

/**
 * 输出下拉框的状态类的方法  （一维数组的值）
 * @param array $data
 * @param $name
 * @param string $selected_value
 * @return string
 */
function select(array $data,$name,$selected_value='')
{   //拼出一维数组
    $html = '<select name="' . $name . '">';
    $html .= '<option value="">请选择</option>';
    foreach ($data as $key => $value) {
        $key = (string)$key;   //强制转换，将状态为0的保存下来，否则数据库会不认
        if ($selected_value === $key) {
            $html .= '<option value="' . $key . '" selected="selected">' . $value . '</option>';
        } else {
            $html .= '<option value="' . $key . '">' . $value . '</option>';
        }
    }
    $html .= '</select>';
    return $html;

}
    /**
     * 加盐 加密
     * @param string $password 原始密码
     * @param string $salt     盐
     * @return string
     */
    function salt_string($password,$salt){
        return md5(md5($password).$salt);
    }



/**
 * 存储或者删除管理员信息   如果传递了参数,表示设置,否则表示获取
 * @param type $data
 * @return array|mixed
 */
function login($data = null){
    if ($data) {
        session('ADMIN_INFO', $data);
    }else{
        return session('ADMIN_INFO') ? session('ADMIN_INFO') : [];
    }
}


/**
 * 存储或者删除管理员授权操作路径   如果传递了参数,表示设置,否则表示获取
 * @param type $data
 * @return array|mixed
 */
function permission_pathes($data = null) {
    if ($data) {
        session('PATHES', $data);
    } else {
        return session('PATHES') ? session('PATHES') : [];
    }
}

/**
 * 存储或者删除管理员授权权限id  如果传递了参数,表示设置,否则表示获取
 * @param type $data
 * @return array|mixed
 */
function permission_ids($data = null) {
    if ($data) {
        session('PIDS', $data);
    } else {
        return session('PIDS') ? session('PIDS') : [];
    }
}
