<?php
// +----------------------------------------------------------------------
// | Quotes [其实台下的观众就我一个，其实我也看出你有点不舍]
// +----------------------------------------------------------------------
// | Created ( PhpStorm )
// +----------------------------------------------------------------------
// | Author: Jonathan <2213147257@qq.com>
// +----------------------------------------------------------------------
// | Date: 2020年03月17日
// +----------------------------------------------------------------------

namespace JonathanUp\Ele;

use GuzzleHttp\Client;

class Ele
{
    public $url;
    public $api_token;
    public $key;

    public function __construct($url, $api_token, $key)
    {
        $this->url = $url;
        $this->api_token = $api_token;
        $this->key = $key;
    }

    /**
     * 获取商品列表操作
     *
     * @return bool|string
     */
    public function getGoodsList()
    {
        $request_url = 'http://'.$this->url.'.api.94sq.cn/api/goods/list';
        $data = [
            'api_token' => $this->api_token,
            'timestamp' => time()
        ];
        $data['sign'] = $this->getSign($data, $this->key);

        return $this->httpPost($request_url, $data);
    }

    /**
     * 获取商品信息操作
     * @param $gid
     *
     * @return bool|string
     */
    public function getGoodsInfo($gid)
    {
        $request_url = 'http://'.$this->url.'.api.94sq.cn/api/goods/info';
        $data = [
            'api_token' => $this->api_token,
            'timestamp' => time(),
            'gid' => $gid
        ];
        $data['sign'] = $this->getSign($data, $this->key);
        return $this->httpPost($request_url, $data);
    }

    /**
     * 下单操作
     * @param       $gid
     * @param       $num
     * @param array $otherValue
     *
     * @return bool|string
     */
    public function orderSubmit($gid, $num, array $otherValue)
    {
        $request_url = 'http://'.$this->url.'.api.94sq.cn/api/order';
        $data = [
            'api_token' => $this->api_token,
            'timestamp' => time(),
            'gid' => $gid,
            'num' => $num,
            'value1' => $otherValue[0],
            'value2' => $otherValue[1],
            'value3' => $otherValue[2],
            'value4' => $otherValue[3],
            'value5' => $otherValue[4],
            'value6' => $otherValue[5]

        ];
        $data['sign'] = $this->getSign($data, $this->key);
        return $this->httpPost($request_url, $data);
    }

    /**
     * 订单查询操作
     * @param $ids
     *
     * @return bool|string
     */
    public function orderQuery($ids)
    {
        $request_url = 'http://'.$this->url.'.api.94sq.cn/api/order/query';
        $data = [
            'api_token' => $this->api_token,
            'timestamp' => time(),
            'ids' => $ids
        ];
        $data['sign'] = $this->getSign($data, $this->key);

        return $this->httpPost($request_url, $data);
    }

    /**
     * 获取签名
     * @param $param
     * @param $key
     *
     * @return string
     */
    public function getSign($param, $key)
    {
        $signPars = "";
        ksort($param);
        foreach ($param as $k => $v) {
            if ("sign" != $k && "" != $v) {
                $signPars .= $k . "=" . $v . "&";
            }
        }
        $signPars = trim($signPars, '&');
        $signPars .= $key;

        return md5($signPars);
    }

    /**
     * HTTP POST
     * @param $url
     * @param $data
     *
     * @return bool|string
     */
    public function httpPost($url, $data)
    {
        $client = new Client();
        $res = $client->post($url, $data);

        if ($res->getStatusCode() === 200) {
            return (string)$res->getBody();
        }

        return false;
    }
}
