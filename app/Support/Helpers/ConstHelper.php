<?php
/**
 * Created by PhpStorm.
 * User: MONO
 * Date: 6/1/2017
 * Time: 10:46 PM
 */

const SUCCESS               = 0;                //成功。

/**
 * 用户级别错误
 */
const SIGN_ERROR            = 403;              //签名失败。
const PARAMS_ILLEGAL        = 417;              //参数不合法。
const NOT_FOUND_CODE        = 404;              //资源找不到。
const AUTH_CODE_ERROR       = 409;              //验证码错误。
const CAPTCHA_ERROR         = 411;              //图片验证码错误。
const PHONE_EXISTS          = 412;              //手机号已注册，不能注册。
const LOGIN_ERROR           = 413;              //登录失败。
const PHONE_NUMBER_ERROR    = 414;              //手机号码错误。
const RESOURCE_NOT_FOUND    = 415;              //资源没有找到。
const EMAIL_EXISTS          = 416;              //邮箱已经存在。
const BIND_ACCOUNT          = 303;              //第三方登录后要求绑定本站帐号。
const O_AUTH_TIMEOUT        = 418;              //第三方登录过期。
const PARAM_FORMAT_ERROR    = 423;              //请求参数格式错误。
const TOKEN_INVALID         = 420;
const INSUFFICIENT_CASH     = 421;
const BAD_REQUEST           = 422;
const TAG_ERROR             = 424;
/**
 * 自定义状态常量
 */
const REGISTER_AUTH_CODE                = 'REGISTER_AUTH_CODE'; //通过短信验证码
const REGISTER_AUTH_PHONE               = 'REGISTER_AUTH_PHONE';//通过短信验证码
const REGISTER_RESULT                   = 'REGISTER_RESULT';    //通过短信验证码
const REGISTER_AUTH_PHONE_PASSED        = 'PASSED';             //通过短信验证码
const FORGOT_PASSWORD_PHONE             = 'FORGOT_PHONE';       //忘记密码的手机
const FORGOT_PASSWORD_TARGET            = 'FORGOT_PHONE_TARGET';//忘记密码的手机
const FORGOT_PASSWORD_PASS              = 'FORGOT_PASS';        //通过手机号验证
const O_AUTH_UNKNOWN_USER               = 'UNKNOWN_USER';       //未知用户。使用第三方登录后未绑定本站用户。

const AUTH_HEADER                       = 'Authorization';          //头名称。

/**
 * 各种资源缓存的 key 前缀
 */
const DEFAULT_IMAGE = 'default.png';
const DEFAULT_AVATAR = 'default_avatar.png';

/**
 * 各种session key
 */

const FORGOT_PASSWORD_AUTH_CODE                 = 'FORGOT_AUTH_CODE';       //忘记密码验证码。
const BIND_EMAIL_AUTH_CODE                      = 'BIND_EMAIL';             //绑定邮箱的验证码key
const BIND_EMAIL_EMAIL                          = 'BIND_EMAIL_EMAIL';       //绑定邮箱的邮箱
const O_AUTH_USER_DATA                          = 'O_AUTH_USER_DATA';       //用于存储用户信息。
const JWT_AUTH_TOKEN                            = 'JWT_AUTH_TOKEN';         //用于存储用户信息。
const CHANGE_PHONE_AUTH_CODE                    = 'CHANGE_PHONE_AUTH_CODE'; //更改手机号的验证码
const CHANGE_PHONE_NUMBER                       = 'CHANGE_PHONE_NUMBER';    //更改手机号的手机号

/**
* 各种资源缓存的 key 前缀
*/

const USER_DATA_CACHE_KEY = 'user.userId:';
const TREASURE_DATA_CACHE_KEY = 'treasure.treasureId:';
const TEMP_TREASURE_DATA_CACHE_KEY = 'temp_treasure.treasureId:';

const ORDER_STATE_WAITING               = '0';          //订单状态, 等待支付。
const ORDER_STATE_SUCCESS               = '1';          //订单状态, 支付成功。
const ORDER_STATE_ERROR                 = '2';          //订单状态, 支付成功。
const ORDER_PAID_CHANNEL_WECHAT         = 'W';          //支付方式，微信。
const ORDER_PAID_CHANNEL_ALIPAY         = 'A';          //支付方式，支付宝。

const PRODUCT_STATE_ENABLED               = '1';          //产品状态，允许下单。
const PRODUCT_STATE_DISABLED               = '0';          //产品状态，不允许下单。

const _TRUE                             = '1';                      //绝对真。
const _FALSE                            = '0';                      //绝对假。

/**
 * http级别错误
 */
const UNAUTHORIZED_REQUEST  = 419;              //未授权的请求。未登录状态

const APPID = 'appid';
