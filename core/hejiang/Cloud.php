<?php

namespace app\hejiang;

use Curl\Curl;
use yii\helpers\VarDumper;
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/10/9
 * Time: 17:43
 * Update Time: 2017年10月9日17点45分
 * Version: 1.7.0
 */
class Cloud
{
	public static $api_key = 'odhjqowdja8u298yhqd9qwydasyioh230912doj238';
	public static $cloud_server_prefix = 'http://';
	public static $cloud_server_host = 'cloud.zjhejiang.com';
	//public static $cloud_server_host = 'localhost/php/cloud_zjhejiang/web';
	private static $curl;
	/**
	 * @return Curl
	 */
	public static function getCurl()
	{
		if (self::$curl) {
			return self::$curl;
		}
		self::$curl = new Curl();
		self::$curl->setOpt(CURLOPT_SSL_VERIFYHOST, false);
		self::$curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
		return self::$curl;
	}
	public static function getSiteInfo()
	{
		$version_file = \Yii::$app->basePath . '/version.json';
		if (file_exists($version_file) && ($version = json_decode(file_get_contents($version_file), true))) {
			$version = isset($version['version']) ? $version['version'] : null;
		} else {
			$version = null;
		}
		$host = \Yii::$app->request->hostName;
		$site_info = ['version' => $version, 'host' => $host];
		return $site_info;
	}
	public static function apiGet($url, $data = array())
	{
		$site_info = self::getSiteInfo();
		$get_data = base64_encode(json_encode(['host' => $site_info['host'], 'current_version' => $site_info['version'], 'from_url' => \Yii::$app->request->absoluteUrl]));
		$data = array_merge($data, ['data' => $get_data]);
		$curl = self::getCurl();
		$curl->get($url, $data);
		return $curl;
	}
	public static function apiPost($url, $data = array())
	{
		$site_info = self::getSiteInfo();
		$get_data = base64_encode(json_encode(['host' => $site_info['host'], 'current_version' => $site_info['version'], 'from_url' => \Yii::$app->request->absoluteUrl]));
		$url = $url . '?data=' . $get_data;
		$curl = self::getCurl();
		$curl->post($url, $data);
		return $curl;
	}
}