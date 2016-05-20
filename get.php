<?php
/**
 * Created by PhpStorm.
 * User: leif
 * Date: 16/1/26
 * Time: 10:17
 * email: leiflyy@outlook.com
 */

/**
 *  实现传入宝贝的id,返回宝贝的链接,支持淘宝
 * @param $id 宝贝的id
 * @return string 返回的宝贝的链接
 */
function getTbLink($id){
    $url="https://item.taobao.com/item.htm?spm=a1z10.4-c.w5003-12641268955.30.0lDnKZ&id=".$id."&scene=taobao_shop";
    return $url;
}


/**
 * 实现传入宝贝的id,获取宝贝的商品名,支持淘宝和天猫
 * @param $id  宝贝的id
 * @return mixed  宝贝的商品名
 */
function getNameById($id){
    $url="http://hws.m.taobao.com/cache/wdetail/5.0/?id=".$id;
    $content=file_get_contents($url);
    $content_ori=strip_tags($content);
    $content_arr=json_decode($content_ori,true);
    $detail=json_decode($content_arr['data']['apiStack']['0']['value'],true);
    $success_sym=$detail['ret']['0'];//成功则返回"SUCCESS::调用成功";
    if($success_sym=="SUCCESS::调用成功"){
        $name=$content_arr['data']['itemInfoModel']['title'];
        return $name;
    }else{
        return "<script type='text/javascript'>alert('宝贝不存在!');</script>";
    }

}

/**
 * 实现传入宝贝id,获取宝贝价格,支持淘宝和天猫
 * @param $id   宝贝的id
 * @return mixed 返回的宝贝的价格或价格区间
 */
function getPriceById($id){
    $url="http://hws.m.taobao.com/cache/wdetail/5.0/?id=".$id;
    $content=file_get_contents($url);
    $content_ori=strip_tags($content);
    $content_arr=json_decode($content_ori,true);
    $pro_detail=json_decode($content_arr['data']['apiStack']['0']['value'],true);
    $success_sym=$pro_detail['ret']['0'];//成功则返回"SUCCESS::调用成功";
    if($success_sym=="SUCCESS::调用成功"){
        $pro_price=$pro_detail['data']['itemInfoModel']['priceUnits']['0']['price'];
        return $pro_price;
    }else{
        return "<script type='text/javascript'>alert('宝贝不存在!');</script>";
    }
}

/**
 *  实现传入宝贝id,获取宝贝的收藏人数(人气),支持淘宝
 * @param $id  宝贝id
 * @return mixed   返回的宝贝的收藏人数(人气)
 */
function getPopById($id){
    $url=getTbLink($id);
    $urlinfo = parse_url($url);
    parse_str($urlinfo['query'], $query);
    $id = $query['id'];
    $data = file_get_contents($url);
    $start = strpos($data, 'counterApi');
    $start = strpos($data, ": ", $start);
    $end = strpos($data, "',", $start);
    $api = 'https:' . substr($data, $start + 3, $end - $start - 3) . '&callback=jsonp107';
    $response = file_get_contents($api);
    $response = substr($response, 9, -2);
    $arr = json_decode($response, true);
    $popularity=$arr['ICCP_1_'.$id];
    return $popularity;
}

/**   实现传入宝贝id，获取宝贝图片url
 * @param $id    宝贝id
 * @return mixd   宝贝图片url
 */
function getPicById($id){
    $url="http://hws.m.taobao.com/cache/wdetail/5.0/?id=".$id;
    $content=file_get_contents($url);
    $content_ori=strip_tags($content);
    $content_arr=json_decode($content_ori,true);
    $detail=json_decode($content_arr['data']['apiStack']['0']['value'],true);
    $success_sym=$detail['ret']['0'];//成功则返回"SUCCESS::调用成功";
    if($success_sym=="SUCCESS::调用成功"){
        $name=$content_arr['data']['itemInfoModel']['picsPath'];
        return $name;
    }else{
        return "<script type='text/javascript'>alert('宝贝不存在!');</script>";
    }

}
