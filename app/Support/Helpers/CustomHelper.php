<?php
/**
 * Created by PhpStorm.
 * User: MONO
 * Date: 6/1/2017
 * Time: 10:44 PM
 */
use App\AuthToken;
use App\Image;
use App\Video;
use App\File;
use App\User;
use Illuminate\Http\Request;
use App\Sms\SmsSender;
use App\QcloudImage\CIClient;
use Eventviva\ImageResize;
use Illuminate\Support\Facades\Storage;

if (!function_exists('api_route')) {
    /**
     * Dingo\Api\Routing\Router
     * @param $api
     * @return \Dingo\Api\Routing\Router
     */
    function api_route($name, array $params = [])
    {
        return app('Dingo\Api\Routing\UrlGenerator')->version('v1')->route($name, $params);
    }
}

if (!function_exists('success')) {
    function success($data = ['success' => true])
    {
        return [
            'code' => SUCCESS,
            'message' => 'success',
            'data' => $data
        ];
    }
}

if (!function_exists('error')) {
    function error($message, $code = PARAMS_ILLEGAL, $data = null)
    {
        _log($message, $code, \Request::all());
        return [
            'code' => $code,
            'message' => $message,
            'data' => $data
        ];
    }
}

if (!function_exists('qr_code')) {
    /**
     * @param $t
     * @param int $s
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    function qr_code($t, $s = 150)
    {
        return response(QrCode::format('png')->encoding('UTF-8')->size($s)->generate($t), 200, ['Content-Type' => 'image/png']);
    }
}

if (!function_exists('_log')) {
    /**
     * @param $var
     */
    function _log()
    {
        $vars = func_get_args();
        Log::info(\Request::get('equ', '未知设备') . '使用' . \Request::getMethod() . '方法请求' . \Request::path());
        Log::info('请求头 -  Authorization：' . \Request::header(AUTH_HEADER));
        Log::info(Session::all());
        foreach ($vars as $var) {
            Log::info($var);
        }
    }
}

if (!function_exists('validate')) {
    function validate(array $data, array $roles, array $messages = [], array $customAttributes = [])
    {
        $v = Validator::make($data, $roles, $messages, $customAttributes);
        if ($v->fails()) {
            _log($v->messages());
            Log::info(\Request::all());
            throw new \App\Exceptions\ValidateLogException($v->messages());
        }
    }
}

if (!function_exists('generate_auth_code')) {
    /**
     * 生成验证码和消息
     * @param $message
     * @return object
     */
    function generate_auth_code()
    {
        $authCode = rand(100000, 999999);

        return $authCode;
    }
}

if (!function_exists('send_auth_code')) {
    function send_auth_code($nation, $phone, $admin = false)
    {
        $code = generate_auth_code();
        $params = array($code, 1);

        if ($admin == true)
            $resp =  send_sms_admin($phone, $params);
        else
            $resp =  send_sms($nation, $phone, $params);

        if ($resp->result != 0)
            return false;
        else
            return $code;
//        return $message->authCode;
    }
}

if (!function_exists('send_sms')) {
    function send_sms($code, $phone, $params)
    {
        $sender = new SmsSender(config('sms.appid'), config('sms.appkey'));

        if ($code = '86') {
            $tpl = config('sms.chinese.tpl');
        } else {
            $tpl = config('sms.foreign.tpl');
        }

        $result = $sender->sendWithParam($code, $phone, $tpl, $params, config('sms.sign'), "");

        $resp = json_decode($result);

//        if ($resp->result != 0) {
//            throw new \App\Exceptions\SmsException('发送短信失败。');
//        }

        return $resp;
    }
}

if (!function_exists('send_sms_admin')) {
    function send_sms_admin($phone, $params)
    {
        $sender = new SmsSender(config('sms.appid'), config('sms.appkey'));
        $tpl = config('sms.chinese.tpl');
        $code = '86';

        $result = $sender->sendWithParam($code, $phone, $tpl, $params, config('sms.sign'), "");

        $resp = json_decode($result);

//        if ($resp->result != 0) {
//            throw new \App\Exceptions\SmsException('发送短信失败。');
//        }

        return $resp;
    }
}

if (!function_exists('send_notice_sms')) {
    function send_notice_sms($code, $phone, $params)
    {
        $sender = new SmsSender(config('sms.appid'), config('sms.appkey'));

        $tpl = config('notice.sms.tpl');

        $result = $sender->sendWithParam($code, $phone, $tpl, $params, config('notice.sms.sign'), "");

        $resp = json_decode($result);

//        if ($resp->result != 0) {
//            throw new \App\Exceptions\SmsException('发送短信失败。');
//        }

        return $resp;
    }
}

if (!function_exists('get_jwt_token')) {
    /**
     * @return null|string
     */
    function get_jwt_token()
    {
        $token = \Request::header(AUTH_HEADER);
        if (is_null($token)) {
            return null;
        }
        $token = str_replace('Bearer ', '', $token);
        if ($token == 'Bearer') {
            return null;
        }
        return $token;
    }
}

if (!function_exists('jwt_to_user')) {
    /**
     * @param $token
     * @return User|null
     */
    function jwt_to_user($token)
    {
        $self = AuthToken::find($token);
        if ($self instanceof User) {
            \Auth::login($self);
            return $self;
        }
        return null;
    }
}

if (!function_exists('user')) {
    /**
     * @return User|null
     */
    function user(Request $request = null)
    {
        $self = Auth::user();
        if (is_null($self)) {
            $token = get_jwt_token();
            return jwt_to_user($token);
        }
        return $self;

    }
}

if (!function_exists('doctor')) {
    /**
     * @return User|null
     */
    function doctor(Request $request = null)
    {

        $id = auth()->guard('admin')->id();
        if(!empty($id)){
            $admin = \App\admin::find($id);
            return $admin;
        }else{
            $id = auth()->guard('doctor')->id();
            $doctor = \App\doctor::find($id);
            return $doctor;
        }

    }
}

if (!function_exists('jwt_login')) {
    /**
     * @param User|array $cred
     * @param string $errMsg
     * @return array
     */
    function jwt_login($cred, $errMsg = '登录失败，请检查手机和验证码。')
    {
        if ($cred instanceof User) {
            Auth::login($cred);
        }
        if ($cred instanceof User or Auth::attempt($cred)) {
            $user = Auth::user();
            if ($cred instanceof User) {
                $user = $cred;
            }
            $userInfo = user_info($user);
            $token = AuthToken::login($user);
            return success([
                'user' => $userInfo,
                'authToken' => $token,
            ]);
        } else {
            return error($errMsg, LOGIN_ERROR);
        }
    }
}

if (!function_exists('jwt_refresh_token')) {
    function jwt_refresh_token()
    {
        $token = Session::get(JWT_AUTH_TOKEN);
        if (is_null($token)) {
            $token = JWTAuth::getToken();
        }
        return $token;
    }
}

if (!function_exists('jwt_user')) {
    function jwt_user($user = null)
    {
        if (is_null($user)) {
            $user = user();
        }
        $authToken = get_jwt_token();
        if (is_null($authToken) and $user instanceof User) {
            $authToken = AuthToken::login($user);
        }
        if (is_null($user)) {
            return null;
        } else {
            $userInfo = user_info($user);
            return [
                'user' => $userInfo,
                'authToken' => $authToken,
            ];
        }
    }
}

if (!function_exists('article_url')) {
    function article_url($id)
    {
        return asset('/article/' . $id);
    }
}

if (!function_exists('asset_url')) {
    function asset_url($filename, $mode)
    {
        if ($mode == 'image') {
            $image = Image::findByName($filename);
            return !is_null($image) ? $image->base_url . '/' . $filename : null;
        }
        if ($mode == 'thumb_image') {
            $image = Image::where('thumb',$filename)->first();
            return !is_null($image) ? $image->base_url . '/' . $filename : null;
        }
        else if ($mode == 'video' || $mode == 'audio') {
            $video = Video::findByName($filename);
            return !is_null($video) ? $video->base_url . '/' . $filename : null;
        }
        else if ($mode == 'file') {
            $file = File::findByName($filename);
            return !is_null($file) ? $file->base_url . '/' . $filename : null;
        }
    }
}

if (!function_exists('origin_url')) {
    function origin_url($filename)
    {
        if (str_contains($filename, '://')) {
            return $filename;
        } else if (is_null($filename) || $filename == '') {
            return asset(DEFAULT_IMAGE);
        } else {
            return asset(config('asset.origin_path') . '/' . $filename);
        }
    }
}


if (!function_exists('save_thumb_image')) {
    function save_thumb_image($path, $filename)
    {
        $crop = new ImageResize($path . '/' . $filename);
        $crop->resizeToWidth(config('asset.share_img_size.width'));
        $crop->save(config('asset.thumb_path') . '/' . $filename);
    }
}

if (!function_exists('save_medium_image')) {
    function save_medium_image($path, $filename)
    {
        $crop = new ImageResize($path . '/' . $filename);
        $crop->resizeToWidth(config('asset.medium_img_size.width'));
        $crop->save(config('asset.medium_path') . '/' . $filename);
    }
}

if (!function_exists('download_image')) {
    function download_image($url)
    {
        $file_contents = file_get_contents($url);

        $image = sha1($url . time()) . handle_extension('png');
        $md5 = md5($file_contents);
        save_file_as($file_contents, config('asset.image_path'), $image);

        return [
            'filename' => $image,
            'md5' => $md5
        ];
    }
}

if (!function_exists('handle_extension')) {
    /**
     * 处理图片后缀
     * @param $extension
     * @return string
     */
    function handle_extension($extension)
    {
        if (in_array($extension, ['png', 'gif', 'jpg', 'jpeg'])) {
            return '.' . $extension;
        } else {
            return '.png';
        }
    }
}

if (!function_exists('save_file_as')) {
    /**
     * @param \Illuminate\Http\UploadedFile $file
     * @param $path
     * @param $filename
     * @return bool|mixed|string
     */
    function save_file_as($file, $path, $filename)
    {
        if ($file instanceof \Illuminate\Http\UploadedFile) {
            return $file->storeAs($path, $filename, 'local');
        } else {
            return Storage::disk('local')->put($path . '/' . $filename, $file);
//            return Storage::disk('local')->put($path . '/' . $filename, transform_base64($file));
        }
    }
}

if (!function_exists('delete_file')) {
    function delete_file($path, $filename)
    {
        Storage::disk('local')->delete($path . $filename);
    }
}

if (!function_exists('transform_base64')) {
    /**
     * @param $base64
     * @param bool $isAndroid
     * @return string
     * @throws \Exception
     */
    function transform_base64($base64)
    {
        if (\Request::get('equ', 'web') != 'web') {
            return base64_decode($base64);
        } else {
            if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64, $result)) {
                return base64_decode(str_replace($result[1], '', $base64));
            }
            throw new \Exception('base64转换文件流失败。请检查base64是否正确。');
        }
    }
}

if (!function_exists('save_to_cos')) {
    function save_to_cos($object, $type)
    {
        $cosClient = new \Qcloud\Cos\Client(
            array(
                'region' => config('qcloud.cos.region'),
                'schema' => 'https', //协议头部，默认为http
                'credentials'=> array(
                    'secretId'  => config('qcloud.cos.secretid'),
                    'secretKey' => config('qcloud.cos.secretkey')
                )
            )
        );

        $bucketName = ($type == 'image' ? config('qcloud.cos.image_bucket') : ($type == 'video' ? config('qcloud.cos.video_bucket') : config('qcloud.cos.file_bucket')));
        $base_path = public_path() . ($type == 'image' ? config('asset.image_path') : ($type == 'video' ? config('asset.video_path') : config('asset.file_path')));

        try {
            $tengXunResult = $cosClient->upload(
                $bucket = $bucketName, //格式：BucketName-APPID
                $key = $object,//uploaded file name
                $body = fopen($base_path . $object, 'rb')
            );

            return $tengXunResult;

        } catch (\Exception $e) {
            // 请求失败
            echo($e);
        }

    }
}
if(!function_exists('remove_from_cos')){
    function remove_from_cos($object,$type)
    {
        $cosClient = new \Qcloud\Cos\Client(
            array(
                'region' => config('qcloud.cos.region'),
                'schema' => 'https', //协议头部，默认为http
                'credentials'=> array(
                    'secretId'  => config('qcloud.cos.secretid'),
                    'secretKey' => config('qcloud.cos.secretkey')
                )
            )
        );
        $bucketName = ($type == 'image' ? config('qcloud.cos.image_bucket') : ($type == 'video' ? config('qcloud.cos.video_bucket') : config('qcloud.cos.file_bucket')));
        try {
            $result = $cosClient->deleteObject(array(
                'Bucket' => $bucketName, //格式：BucketName-APPID
                'Key' => $object,
            ));
        } catch (\Exception $e) {
            // 请求失败
            echo($e);
        }

    }
}
if(!function_exists('file_exist_in_cos')){
    function file_exist_in_cos($file_name,$type)
    {
        $subPath = ($type == 'image' ? config('asset.image_path') : ($type == 'video' ? config('asset.video_path') : config('asset.file_path')));
        $md5 = md5_file(public_path() . $subPath . $file_name);
        return Image::findByMD5($md5);
    }
}


if (!function_exists('getAppIdFromHeader')) {
    function getAppIdFromHeader()
    {
        $appid = \Request::header(APPID);
        if (!is_null($appid))
            return $appid;
        else
            throw new \Exception('没有找到App。');
    }
}

if (!function_exists('getAppId')) {
    function getAppId()
    {
        if (\Request::session()->has('appid'))
            return \Request::session()->get('appid');
        else
            throw new \Exception('没有找到App。');
    }
}

if (!function_exists('businessCardOcr')) {
    function businessCardOcr($url)
    {
        $client = new CIClient(config('qcloud.ocr.appid'), config('qcloud.ocr.secretid'), config('qcloud.ocr.secretkey'), 'haichao');
        $client->setTimeout(30);
        $resp = $client->businesscardDetect(array('urls'=>array($url)), 0);
        $resp =  json_decode($resp)->result_list[0];

        if ($resp->code == 0) {
            $data = $resp->data;

            $key = array_search('姓名', array_column($data, 'item'));

            if ( !isset($key) ) {
                $key = array_search('英文姓名', array_column($data, 'item'));
            }

            $name = $resp->data[$key]->value;

            $key = array_search('职位', array_column($data, 'item'));
            if ( !isset($key) ) {
                $key = array_search('英文职位', array_column($data, 'item'));
            }

            $position = $resp->data[$key]->value;

            $key = array_search('公司', array_column($data, 'item'));
            if ( !isset($key) ) {
                $key = array_search('英文公司', array_column($data, 'item'));
            }

            $company = $resp->data[$key]->value;

            $key = array_search('手机', array_column($data, 'item'));
            if ( !isset($key) ) {
                $key = array_search('电话', array_column($data, 'item'));
            }

            $phone = $resp->data[$key]->value;

            $key = array_search('邮箱', array_column($data, 'item'));
            if ( !isset($key) ) {
                $key = array_search('网址', array_column($data, 'item'));
            }

            $email = $resp->data[$key]->value;

            return [
                'name' => $name,
                'company' => $company,
                'position' => $position,
                'phone' => $phone,
                'email' => $email
            ];
        } else {
            throw new \Exception($resp->message);
        }
    }
}

if (!function_exists('dateDiff')) {
    /**
     * @param $base64
     * @param bool $isAndroid
     * @return string
     * @throws \Exception
     */
    function dateDiff($start, $end)
    {
        $start_ts = strtotime($start);

        $end_ts = strtotime($end);

        $diff = $end_ts - $start_ts;

//        return round($diff / 3600);

        return $diff;
    }
}

if (!function_exists('juhecurl')) {
    /**
     * 请求接口返回内容
     * @param  string $url [请求的URL地址]
     * @param  string $params [请求的参数]
     * @param  int $ipost [是否采用POST形式]
     * @return  string
     */
    function juhecurl($url,$params=false,$ispost=0){
        $httpInfo = array();
        $ch = curl_init();

        curl_setopt( $ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1 );
        curl_setopt( $ch, CURLOPT_USERAGENT , 'JuheData' );
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 60 );
        curl_setopt( $ch, CURLOPT_TIMEOUT , 60);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        if( $ispost )
        {
            curl_setopt( $ch , CURLOPT_POST , true );
            curl_setopt( $ch , CURLOPT_POSTFIELDS , $params );
            curl_setopt( $ch , CURLOPT_URL , $url );
        }
        else
        {
            if($params){
                curl_setopt( $ch , CURLOPT_URL , $url.'?'.$params );
            }else{
                curl_setopt( $ch , CURLOPT_URL , $url);
            }
        }
        $response = curl_exec( $ch );
        if ($response === FALSE) {
            //echo "cURL Error: " . curl_error($ch);
            return false;
        }
        $httpCode = curl_getinfo( $ch , CURLINFO_HTTP_CODE );
        $httpInfo = array_merge( $httpInfo , curl_getinfo( $ch ) );
        curl_close( $ch );
        return $response;
    }
}

if (!function_exists('random_str')) {
    function random_str($type = 'alphanum', $length = 8)
    {
        switch($type)
        {
            case 'basic'    : return mt_rand();
                break;
            case 'alpha'    :
            case 'alphanum' :
            case 'num'      :
            case 'nozero'   :
                $seedings             = array();
                $seedings['alpha']    = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $seedings['alphanum'] = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $seedings['num']      = '0123456789';
                $seedings['nozero']   = '123456789';

                $pool = $seedings[$type];

                $str = '';
                for ($i=0; $i < $length; $i++)
                {
                    $str .= substr($pool, mt_rand(0, strlen($pool) -1), 1);
                }
                return $str;
                break;
            case 'unique'   :
            case 'md5'      :
                return md5(uniqid(mt_rand()));
                break;
        }
    }
}

if (!function_exists('curl_post')) {
    function curl_post($url, $data)
    {
        $headers = array("Content-type: application/json;charset=UTF-8","Accept: application/json","Cache-Control: no-cache", "Pragma: no-cache");

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // POST数据
        curl_setopt($ch, CURLOPT_POST, 1);
        // 把post的变量加上
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $output = curl_exec($ch);
        curl_close($ch);
        return json_decode($output);
    }
}

if (!function_exists('sortImage')) {
    function sortImage($a, $b)
    {
        $a = $a->is_primary;
        $b = $b->is_primary;

        if ($a == $b) return 0;
        return ($a > $b) ? -1 : 1;
    }
}

if (!function_exists('sortByVisits')) {
    function sortByVisits($a, $b)
    {
        $a = $a['visits'];
        $b = $b['visits'];

        if ($a == $b) return 0;
        return ($a > $b) ? -1 : 1;
    }
}
if (!function_exists('getImageUrlsFromContent')) {
    function getImageUrlsFromContent($content)
    {
        $originalContent = $content;
        $firstString = 'src="data:image/';
        $endString = '" data-filename="';
        $firstIndex = strpos($content,$firstString);
        $images = [];
        while($firstIndex!==false){//while find img tags
            $content = substr($content,$firstIndex+5);
            $endIndex = strpos($content,$endString);
            $data = substr($content,0,$endIndex);
            $imageData = $data;
            list($type, $data) = explode(';', $data);
            list(, $data)      = explode(',', $data);
            $data = base64_decode($data);
            $type = str_replace('data:image/','',$type);
            if(!is_null($type)){
                $fileName = sha1($data) . '.' . $type;
                file_put_contents(config('asset.base_path').config('asset.image_path').$fileName, $data);
                $url =config('asset.server_url'). config('asset.image_path').$fileName;
                $originalContent = str_replace($imageData,$url,$originalContent);
                array_push($images,$url);
                $content = substr($content,$endIndex);
                $firstIndex = strpos($content,$firstString);
            }
        }
        $result = array(
          "images"=>$images,
          "content"=>$originalContent
        );
        return $result;
    }
}

if (!function_exists('checkAuthority')) {
    function checkAuthority($doctor_id,$url)
    {
            $doctor = \App\doctor::where('id', $doctor_id)->first();
            $authority = json_decode($doctor->authority);
            if(substr($url,0,8)==='/uploads')
                return true;
            if(substr($url,0,6)==='/admin')
                return false;
            if (substr($url, 0, 14) === '/doctor/recipe') {
                return in_array("药方", $authority) ? true : false;
            }
            if (substr($url, 0, 10) === '/doctor/qa') {
                return in_array("问答", $authority) ? true : false;
            }
            if (substr($url, 0, 16) === '/doctor/medicine') {
                return in_array("药材", $authority) ? true : false;
            }
            if (substr($url, 0, 15) === '/doctor/inquiry') {
                return in_array("问诊", $authority) ? true : false;
            }
            if(substr($url,0,14)==='/doctor/accept'){
                return in_array("挂号", $authority) ? true : false;
            }
            if(substr($url,0,14)==='/doctor/common'||$url=='/doctor/setting/change_password'||substr($url,0,15)=='/doctor/history')
                return true;
            else
                return false;
    }
}
if (!function_exists('getStateWord')) {
    function getStateWord($state)
    {
        switch ($state) {
            case 'ACCEPT':
                return '接受';
                break;
            case 'WAITING_TREATMENT':
                return '等待治疗';
                break;
            case 'TREATING':
                return '在治疗中';
                break;
            case 'BEFORE_TREATING_PAY':
                return '结束治疗';
                break;
            case 'AFTER_TREATING_PAY':
                return '结束治疗付款';
                break;
            case 'CLOSE':
                return '结束';
                break;
            default:
                return '';
        }
    }
}
if (!function_exists('getMonthData')) {
    function getMonthData(){
        $result = DB::select('SELECT MONTHNAME(treat_start) AS `month`,YEAR(treat_start) AS `year`, SUM(price) AS `sum`
                                FROM treatments
                                GROUP BY YEAR(treat_start), MONTH(treat_start)
                                ');
        return $result;
    }
}
if (!function_exists('getMedicineDatas')) {
    function getMedicineDatas($strMedicine){
        if(empty($strMedicine))
            return [];
        $split_strings = preg_split('/[\ \n\,]+/', $strMedicine);
        $recipe = [];
        foreach($split_strings as  $split_string){
            if (preg_match('/（(.*?)）/', $split_string, $match) == 1) {
                $option = $match[1];
                $item = preg_replace('/（(.*?)）/','',$split_string);
                $reg = '/((0|[1-9]\d*)(\.\d+)?)|(零|一|二|三|四|五|六|七|八|九|十)(百|十|零)?(一|二|三|四|五|六|七|八|九)?(百|十|零)?(一|二|三|四|五|六|七|八|九)?/';

                if (preg_match($reg, $item, $matches)){
                    $weight = $matches[0];
                    $medicines = explode($weight,$item);
                    $medicin = $medicines[0];
                    $unit = $medicines[1];
                    if($unit=='g'||$unit=='gram')
                        $unit = '公克';

                    $temp = array(
                        'prescription_name' => $medicin,
                        'unit' => $unit,
                        'weight' => checkNatInt($weight),
                        'option' => $option
                    );
                    array_push($recipe,$temp);
                }
            }
        }
        return $recipe;
    }
}

if (!function_exists('checkString')) {
    function checkString($var, $check = '', $default = '') {
        if(!is_string($var)){
            if(is_numeric($var)) {
                $var = (string)$var;
            }else{
                return $default;
            }
        }
        if ($check) {
            return (preg_match($check, $var, $ret) ? $ret[1] : $default);
        }
        return $var;
    }
}
if (!function_exists('checkNatInt')) {
    function checkNatInt($str)
    {
        $map = array('一' => '1', '二' => '2', '三' => '3', '四' => '4', '五' => '5', '六' => '6', '七' => '7', '八' => '8', '九' => '9',
            '壹' => '1', '贰' => '2', '叁' => '3', '肆' => '4', '伍' => '5', '陆' => '6', '柒' => '7', '捌' => '8', '玖' => '9',
            '零' => '0', '两' => '2',
            '仟' => '千', '佰' => '百', '拾' => '十',
            '万万' => '亿');
        $str = str_replace(array_keys($map), array_values($map), $str);
        $str = checkString($str, '/([\d亿万千百十]+)/u');
        $func_c2i = function ($str, $plus = false) use (&$func_c2i) {
            if (false === $plus) {
                $plus = array('亿' => 100000000, '万' => 10000, '千' => 1000, '百' => 100, '十' => 10,);
            }
            $i = 0;
            if ($plus)
                foreach ($plus as $k => $v) {
                    $i++;
                    if (strpos($str, $k) !== false) {
                        $ex = explode($k, $str, 2);
                        $new_plus = array_slice($plus, $i, null, true);
                        $l = $func_c2i($ex[0], $new_plus);
                        $r = $func_c2i($ex[1], $new_plus);
                        if ($l == 0) $l = 1;
                        return $l * $v + $r;
                    }
                }
            return (int)$str;
        };
        return $func_c2i($str);
    }
}
if (!function_exists('updateRecipesByMedicinePrice')) {
    function updateRecipesByMedicinePrice($medicine_id,$price){
        $recipes = \App\recipe::all();
        foreach($recipes as $recipe){
            $str_medicine = $recipe->medicine;
            $medicines = json_decode($str_medicine);
            $medicine_array = [];
            $found = false;
            foreach($medicines as $medicine){
                if($medicine->medicine_id==$medicine_id){
                    $found = true;
                    $medicine->price = $price;
                }
                array_push($medicine_array,$medicine);
            }
            if($found)
                \App\recipe::where('id',$recipe->id)->first()->update([
                   'medicine' => json_encode($medicine_array)
                ]);
        }
    }
}
if (!function_exists('updateRecipesByMedicineUnit')) {
    function updateRecipesByMedicineUnit($medicine_id, $unit)
    {
        $recipes = \App\recipe::all();
        foreach($recipes as $recipe){
            $str_medicine = $recipe->medicine;
            $medicines = json_decode($str_medicine);
            $medicine_array = [];
            $found = false;
            foreach($medicines as $medicine){
                if($medicine->medicine_id==$medicine_id){
                    $found = true;
                    $medicine->unit = $unit;
                }
                array_push($medicine_array,$medicine);
            }
            if($found)
                \App\recipe::where('id',$recipe->id)->first()->update([
                    'medicine' => json_encode($medicine_array)
                ]);
        }
    }
}


