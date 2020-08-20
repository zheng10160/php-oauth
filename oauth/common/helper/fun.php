<?php
/**
+------------------------------------------------------------------------------
 * 文件描述： 基础函数封装...
+------------------------------------------------------------------------------
 */
/*
* @todo   UTF-8转GB2312
* @param  字符串
* @return 转换后的字符
*/
function str_u2g($str){
    return iconv('UTF-8', 'GB2312//IGNORE', $str);
}
/*
* @todo   GB2312转UTF-8
* @param  字符串
* @return 转换后的字符
*/
function str_g2u($str){
    return iconv('GB2312', 'UTF-8//IGNORE', $str);
}

/**
 * jsonp 格式
 */
function jsonp_data($data)
{
    $callback = $_GET['callback'];

    return $callback."(".json_encode($data).")";
}

function v_print($data){
    echo '<pre>';
    print_r($data);
    die;
}
/*
* @todo   格式化打印变量
* @param  变量 标签 输出返回
* @return 变量信息
*/
function str_dump($var, $label='', $echo=true){
    ob_start();
    var_dump($var);
    $output = ob_get_clean();
    $output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
    $output = '<pre>' . $label .' '. htmlspecialchars($output, ENT_QUOTES) . '</pre>';
    if ($echo){
        echo($output);
    } else{
        return $output;
    }
}


function _initSess() {
    if(!session_id()){
        @session_start();
    }

}

/**
 * 返回json数据
 * @param $code
 * @param string $mess
 * @param array $data
 * @return string
 */
function return_json($code, $mess='', $data=array()) {
    $json = array('code'=>$code, 'message'=>$mess, 'data'=>$data);
    exit(json_encode($json));
}

/*
* @todo   返回json数据
* @param  编码 信息 数据
* @return 格式化的json
*/
function show_json($code, $mess='', $data=array()) {
    header('Content-Type: application/json; charset=utf-8');
    $json = array('code'=>$code, 'message'=>$mess, 'data'=>$data);
    $json = json_encode($json);
    exit($json);
}

/*
* 页面跳转 获取值基于session
* @todo   返回json数据
* @param  编码 信息 数据
* @return 格式化的json
*/
function set_ses_data($code,$data='') {
    $json = array('code'=>$code, 'data'=>$data);
    // $json = json_encode($json);
    if(!session_id()){
        @session_start();//start session
    }
    $_SESSION['global_status'] = $json;
}

/**
 * 获取session 用一次就删除
 * @param $code
 * @param string $mess
 * @param array $data
 */
function get_ses_data($key) {
    if(!session_id()){
        @session_start();//start session
    }

    if(!in_array($key,['code','data'])){
        return false;
    }
    if(isset($_SESSION['global_status']) && !empty($_SESSION['global_status'])){
        $data = $_SESSION['global_status'][$key];
        unset($_SESSION['global_status']);
        return $data;
    }

    return false;
}
/**
 * [encryptedPwd description]
 * @param  [type] $pwd    [description]
 * @param  [type] $string [description]
 * @return [type]         [description]
 * @descripe对密码进行加密 都转小写
 */
function encryptedPwd ($pwd = NULL, $string = NULL)
{
    return strtolower(md5($pwd.$string));
}
/**
 * [delData description]
 * @param  [type] $value [description]
 * @return [type]        [description]
 * @descripe 过滤数据
 */
function filterData ($data,$type='string',$Maxlength=32,$MinLength=0)
{
    if (is_array($data)) {
        foreach ($data as $key => $value) {

            checkLengthParams($data,$Maxlength,$MinLength);
            checkTypeParams($data,$type);

            $value = input_str_check($value);

          //  $value = trim($value);//移除字符串两侧的字符
            $value = htmlspecialchars($value);//过滤字符 单引号等
           // $data[$key] = strip_tags($value);//剥去字符串中的 HTML、XML 以及 PHP 的标签
        }
    } else {

        checkLengthParams($data,$Maxlength,$MinLength);
        checkTypeParams($data,$type);

        $data = trim($data);
      //  $data = htmlspecialchars($data);
       // $data = strip_tags($data);

        $data = input_str_check($data);

    }

    return $data;
}

/**
 * @param $data
 * @param int $Maxlength 最大长度
 * @param int $Min 最小长度
 * @return string
 */
function checkLengthParams($data,$Maxlength,$Min)//默认长度32位
{
    $data = trim($data);
    if(strlen($data) > $Maxlength){
        show_json(100000,'The parameters are log');//参数长度超长
    }

    if(strlen($data) < $Min){
        show_json(100000,'The parameters are short');//参数长度过短
    }

    return $data;
}

/**
 * @integer 整形
 * @string  字符串
 * @double   双精度
 * @boolean  布尔
 * @NULL    空值
 *
 * @param $data
 * @param string $type
 * @return mixed
 */
function checkTypeParams($data,$type = 'string')//默认字符串
{
    $i_type = gettype($data);

    $type = strtolower($type);

    if(is_numeric($data)){
        return $data;
    }

    if(strtolower($i_type) != $type){
        show_json(100000,'Data type error');//参数长度过短
    }

    return $data;
}
/**
 *
 * @param  int  随机数
 * @return string
 * @descripe 生成唯一的32位字符串 截取四位
 */
function uuid ($limit = 4)
{
    $str = time();
    $str = md5($str . mt_rand());
    $str = md5(substr($str . time() . md5(mt_rand()), mt_rand(0, 30)));
    $str = substr($str, 0, intval($limit));
    return $str;
}

/**
 * 随机获取四位整数
 * @param int $limit
 * @return bool|string
 */
function getIntCode($limit=4)
{
    $str = str_shuffle(time());
    $str = substr($str, 0, intval($limit));
    return $str;
}
/**
 *
 * @param url 获取数据的地址
 * @return array 服务器返回的结果
 * @descripe 使用post方式 到指定的url 下采集数据
 */
function postXml ($xml_data, $url)
{
    $header[] = "Content-type: text/xml";  //定义content-type为xml,注意是数组
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_data);
    $result = curl_exec($ch);
    return $result;
}
/**
 *
 * @param url 获取数据的地址
 * @return array 服务器返回的结果
 * @descripe 使用get方式 到指定的url 下采集数据
 */
function getCurl ($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}
/**
 *
 * @param url 推送数据的地址
 * @return array  服务器返回的结果
 * @descripe  使用post方式 发送数据到指定的url下  采集数据
 */
function postCurl ($url, $data)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $back_info = curl_exec($ch);
    if (curl_errno($ch)) {
        $back_info = curl_error($ch);
        writeLog($back_info);
        $back_info = json_encode(array('status' => false, 'msg' => $back_info));
    }
    curl_close($ch);
    return $back_info;
}

/**
 * @param data 导出的数据
 * @param fileNme  导出文件的文件名
 */
function exportCsv ($filename, $data)
{
    header("Content-type:text/csv");
    header("Content-Disposition:attachment;filename=" . $filename);
    header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
    header('Expires:0');
    header('Pragma:public');
    echo $data;
}

/**
 * 程序中 非重要的数据 调试写log
 * @param string $str需要写入日志的内容
 * @return string
 */
function writeLog ($str = 'is null',$dir='public')
{
    $trace = current(debug_backtrace());
    /* $log_dir = __DIR__ . '/../Public/Log';//日志文件目录*/
    $log_dir = '/log/'.$dir;//绝对地址
    if (!is_dir($log_dir)) {
        mkdir($log_dir, 0755, true);
    }
    $day_path = $log_dir . '/' . date('Y-m-d');
    if (!is_dir($day_path)) {
        mkdir($day_path, 0755, true);
    }
    $log_path = $day_path . '/' . date('Y-m-d H') . '.txt';//sql 日志文件路径
    $time = date('Y-m-d H:i:s');
    if (is_array($str)) {
        $str = var_export($str, true);
    }
    $msg = "In {$trace['file']},Line:{$trace['line']},Output:" . $str;
    // $msg = "{$time}:message:" . PHP_EOL . $str . PHP_EOL;
    $handel = fopen($log_path, 'a+');
    fwrite($handel, $msg);
    fclose($handel);
    return 'msg' . $str;
}

/**
 * v0.1
 * @param array $data log数据 数组或者字符串
 * @param $filename 文件名称 / 默认为日期名称
 * @return string
 */
function var_log($data=array(),$filename=''){

    $trace = current(debug_backtrace());

    $log_dir = LOG_PATH;//绝对地址目录
    if (!is_dir($log_dir)) {
        mkdir($log_dir, 0755, true);
    }
    $day_path = $log_dir . '/' . date('Y-m-d');
    if (!is_dir($day_path)) {
        mkdir($day_path, 0755, true);
    }
    $filename = ($filename)? $filename.'.txt':date('Y-m-d H'). '.txt';//sql 日志文件路径
    $log_path = $day_path . '/' . $filename;

    $msg = json_encode(array('data'=>$data,'time'=>date('Y-m-d H:i:s'),'ip'=>getIp()));

    $msg = "In {$trace['file']},Line:{$trace['line']},Output:" . $msg."\n";
    //$msg = "{$time}:message:" . PHP_EOL . $msg . PHP_EOL;
    $handel = fopen($log_path, 'a+');
    fwrite($handel, $msg);
    fclose($handel);
    return 'msg' . $msg;
}

/**
 * 仅适用dblog记录
 * @param $uid 操作者 uid
 * @param $table 表明
 * @param $act  操作事件 如delete update...
 * @param array $data  操作的记录数据
 * @return string
 */
function var_db_log($uid,$act,$table,$data=array()){

    $trace = current(debug_backtrace());
    $log_dir = '/data/logs/oauth';//绝对地址目录
    if (!is_dir($log_dir)) {
        mkdir($log_dir, 0755, true);
    }
    $day_path = $log_dir . '/' . date('Y-m');
    if (!is_dir($day_path)) {
        mkdir($day_path, 0755, true);
    }
    $log_path = $day_path . '/' .$table. '.log';//sql 日志文件路径
    if (is_array($data)) {
        $msg = json_encode(array('uid'=>$uid,'ts'=>time(),'ip'=>getIp(),'act'=>$act,'table'=>$table,'data'=>$data,));
    }else{
        $msg = json_encode(array('uid'=>$uid,'ts'=>time(),'ip'=>getIp(),'act'=>$act,'table'=>$table,'data'=>$data,));
    }
    $r_msg = "Output:" . $msg.PHP_EOL;
    //$msg = "{$time}:message:" . PHP_EOL . $msg . PHP_EOL;
    $handel = fopen($log_path, 'a+');
    fwrite($handel, $r_msg);
    fclose($handel);
    return $msg;
}
/**
 * [getIp description]
 * @return [type] [description]
 * @descripe 获取用户的ip
 */
function getIp ()
{
    $onlineip = '';
    if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
        $onlineip = getenv('HTTP_CLIENT_IP');
    } elseif (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
        $onlineip = getenv('HTTP_X_FORWARDED_FOR');
    } elseif (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
        $onlineip = getenv('REMOTE_ADDR');
    } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
        $onlineip = $_SERVER['REMOTE_ADDR'];
    }
    return $onlineip;
}

/**
 * 时间规定格式
 * @return string
 */
function setDateTime()
{
    //return date('Y-m-d').date('H:i:s');
    return date('Y-m-d H:i:s');
}

/**
 * 返回毫秒级的时间戳
 */
function msectime() {
    list($msec, $sec) = explode(' ', microtime());
    $msectime =  (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
    return $msectime;
}

/**
 * 生成GUID（UUID）
 * @access public
 * @return string
 * @author knight
 */
function createGuid()
{

    mt_srand ( ( double ) microtime () * 10000 ); //optional for php 4.2.0 and up.随便数播种，4.2.0以后不需要了。
    $charid = strtoupper ( md5 ( uniqid ( rand (), true ) ) ); //根据当前时间（微秒计）生成唯一id.
//    $hyphen = chr ( 45 ); // "-"
    $uuid = '' . //chr(123)// "{"
        substr ( $charid, 0, 8 )  . substr ( $charid, 8, 4 )  . substr ( $charid, 12, 4 )  . substr ( $charid, 16, 4 )  . substr ( $charid, 20, 12 );
    //.chr(125);// "}"
    return $uuid;

}

/**
 * 函数名称：inject_check()
 * 函数作用：检测提交的值是不是含有SQL注射的字符，防止注射，保护服务器安全
 * 参　　数：$sql_str: 提交的变量
 * 返 回 值：返回检测结果，ture or false
 */
function inject_check($sql_str) {
    return preg_match('/select|insert|and|or|update|delete|\'|\/\*|\*|\.\.\/|\.\/|union|into|load_file|outfile/', $sql_str); // 进行过滤
}

/**
 * 过滤单引号及其他敏感符号
 * @param $sql_str
 * @return int
 */
function v_check($sql_str) {
    return preg_match('/\'|\/\*|\*|\.\.\/|\.\/|/', $sql_str); // 进行过滤
}
/**
 * 函数名称：verify_id()
 * 函数作用：校验提交的ID类值是否合法
 * 参　　数：$id: 提交的ID值
 * 返 回 值：返回处理后的ID
 */
function verify_id($id=null) {
    if (!$id) { exit('没有提交参数！'); } // 是否为空判断
    elseif (inject_check($id)) { exit('提交的参数非法！'); } // 注射判断
    elseif (!is_numeric($id)) { exit('提交的参数非法！'); } // 数字判断
    $id = intval($id); // 整型化

    return $id;
}

/**
 * 函数名称：str_check()
 * 函数作用：对提交的字符串进行过滤
 * 参　　数：$var: 要处理的字符串
 * 返 回 值：返回过滤后的字符串
 */
function str_check( $str ) {
    if (!get_magic_quotes_gpc()) { // 判断magic_quotes_gpc是否打开
        $str = addslashes($str); // 进行过滤
    }
    $str = str_replace("_", "\_", $str); // 把 '_'过滤掉
    $str = str_replace("%", "\%", $str); // 把 '%'过滤掉

    return $str;
}

/**
 * 函数名称：input_str_check()
 * 函数作用：对提交的字符串进行过滤
 * 参　　数：$var: 要处理的字符串
 * 返 回 值：返回过滤后的字符串
 */
function input_str_check( $str ) {
    $str = str_replace("%", "", $str); // 把 '%'过滤掉
    $str = str_replace("/", "", $str); // 把 '%'过滤掉
    if (!get_magic_quotes_gpc()) { // 判断magic_quotes_gpc是否打开
        $str = addslashes($str); // 进行过滤
        // return $str;
    }
//    $str = str_replace("\'", "\\\\\'", $str); // 把 '_'过滤掉

    //$str = str_replace("\"", "", $str); // 把 '"'过滤掉
    $str = str_replace("*", "", $str); // 把 '*'过滤掉*/
    return $str;
}

/**
 * 函数名称：post_check()
 * 函数作用：对提交的编辑内容进行处理
 * 参　　数：$post: 要提交的内容
 * 返 回 值：$post: 返回过滤后的内容
 */
function post_check($post) {
    if (!get_magic_quotes_gpc()) { // 判断magic_quotes_gpc是否为打开
        $post = addslashes($post); // 进行magic_quotes_gpc没有打开的情况对提交数据的过滤
    }
    $post = str_replace("_", "\_", $post); // 把 '_'过滤掉
    $post = str_replace("%", "\%", $post); // 把 '%'过滤掉
    $post = nl2br($post); // 回车转换
    $post = htmlspecialchars($post); // html标记转换

    return $post;
}


/**
 * combineURL
 * 拼接url
 * @param string $baseURL   基于的url
 * @param array  $keysArr   参数列表数组
 * @return string           返回拼接的url
 */
function combineURL($baseURL,$keysArr){
    /*$combined = $baseURL."&";*/
    $combined = $baseURL."?";
    $valueArr = array();

    foreach($keysArr as $key => $val){
        $valueArr[] = "$key=$val";
    }

    $keyStr = implode("&",$valueArr);
    $combined .= ($keyStr);

    return $combined;
}

?>