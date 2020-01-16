<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuthToken extends Model
{
    protected $fillable = [
        'user_id', 'client_ip', 'ip_location', 'token'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * @param $token
     * @return User|null
     */
    public static function find($token)
    {
        $token = self::whereToken($token)->first();
        if (is_null($token)) {
            return null;
        }
        return $token->user;
    }

    /**
     * @param User $user
     * @return bool|int|null
     */
    public static function logout(User $user)
    {
//        return self::whereUserId($user->id)->where('client_ip', \Request::getClientIp())->delete();
        return self::whereUserId($user->id)->delete();
    }

    public static function login(User $user)
    {
        $ip = \Request::getClientIp();
        $str = $user->mobile . date('YmdHis') . uniqid() . $ip;
        $token = \Hash::make($str);

        $params = array('ip' => $ip, 'key' => config('juhe.ip.appkey'), "dtype" => "");
        $paramstring = http_build_query($params);
        $content = juhecurl(config('juhe.ip.url'),$paramstring);
        $result = json_decode($content,true);
        if($result){
            if ($result['error_code'] != '0') {
                return error($result['reason']."(".$result['error_code'].")");
            }
        }

        if (self::where('user_id', $user->id)->count() > 0) {
            self::where('user_id', $user->id)->update([
                'token' => $token,
                'client_ip' => $ip,
                'ip_location' => $result['result']['area'] . ' / ' . $result['result']['location']
            ]);
        } else {
            self::create([
                'user_id' => $user->id,
                'token' => $token,
                'client_ip' => $ip,
                'ip_location' => $result['result']['area'] . ' / ' . $result['result']['location']
            ]);
        }

        return $token;
    }
}
