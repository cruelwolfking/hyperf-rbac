<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2019-08-27
 * Time: 14:12
 */

namespace App\Lib;
use \Firebase\JWT\JWT;

class Passport
{
    /**
     * 返回token
     * @return string
     */
    public static function getToken(array $data){
        $key = env('APP_KEY');
        $token =  array(
                "iss" => "http://example.org",//iss:
                "aud" => "http://example.com",//接收jwt的一方
                "iat" => time(),// jwt的签发时间
                "nbf" => time(),//定义在什么时间之前，某个时间点后才能访问
                "exp" => time() + 60*60*24, // 过期时间
                'data'=>$data
            );
        $jwt = JWT::encode($token, $key);
        return $jwt;
    }

    /**
     * 解密token，返回对象
     * @param $jwt
     * @return array
     */
    public static function checkToken($jwt){
        $key = env('APP_KEY');
        try {
            JWT::$leeway = 60;//当前时间减去60，把时间留点余地
            $decoded = JWT::decode($jwt, $key, ['HS256']); //HS256方式，这里要和签发的时候对应
            $arr = (array)$decoded;
            return ['code'=>200,'data'=>$arr['data']];

        } catch(\Firebase\JWT\SignatureInvalidException $e) {
            //签名不正确
            return ['code'=>500,'error'=>$e->getMessage(),'msg'=>'签名错误'];

        }catch(\Firebase\JWT\BeforeValidException $e) {  // 签名在某个时间点之后才能用

            return ['code'=>500,'error'=>$e->getMessage(),'msg'=>'token没有到使用时间'];

        }catch(\Firebase\JWT\ExpiredException $e) {  // token过期

            return ['code'=>500,'error'=>$e->getMessage(),'msg'=>'token过期'];

        }catch(\Exception $e) {  //其他错误

            return ['code'=>500,'error'=>$e->getMessage(),'msg'=>'其他错误'];
        }

    }


}