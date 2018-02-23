<?php 
class getImage
{
	public $data  = [];//暂时用来保存资源的中间变量
	public $urls  = [];//所要找的资源的集合
	public $start = 0;//开始页面
	public $end   = 5;//结束页面 这里可以设定的多一点 就可以 找到 更多的资源
	public $name  = '初音未来';
	public function getContent($url){
		$save_file = pathinfo($url, PATHINFO_BASENAME);
		file_put_contents('image/01.jpg', file_get_contents($url));
	}


	public function downloadImage($url,$name = '',$path='image/')
    {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);//需要请求的url
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//将curl_exec()获取的信息以文件流的形式返回，而不是直接输出。  
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//避免对https进行检查
		$file = curl_exec($ch);
		curl_close($ch);
		$this->saveAsImage($url, $file, $path,$name);
    }
 
    private function saveAsImage($url, $file, $path,$name='')
    {
    	$filename = pathinfo($url, PATHINFO_BASENAME);
    	if($name){
			$name = iconv('UTF-8', 'GBK', $name);//创建中文目录乱码
			is_dir($path)?:mkdir($path);
			is_dir($path.$name)?:mkdir($path.$name);
			$resource = fopen($path . $name .'/'. $filename, 'a');
    	}else{
    		$resource = fopen($path . $filename, 'a');
    	}
    	fwrite($resource, $file);
    	fclose($resource);
    }

    public function getImgApi($url){
    	$ch = curl_init();
    	curl_setopt($ch,CURLOPT_URL,$url);
    	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
    	$data = curl_exec($ch);
    	curl_close($ch);
    	return $data;
    }

    public function getContentByf($url){
    	return file_get_contents($url);
    }

    public function getImgByName($name){
    	foreach ($this->data as $k=> $v) {
    		if(strpos($v['item']['title'],$name) !== FALSE){
    			foreach ($v['item']['pictures'] as $key => $value) {
    				$this->urls[] = $value['img_src'];
    			}
    		}
    	}
    }

}
echo base64_encode('1309104549');die;
ini_set('max_execution_time', '0');//设置脚本的最大执行时间为无限
$obj = new getImage;
//$url = 'https://i0.hdslb.com/bfs/album/aede7adfb5012ef5096dc941015eb68cc6b534a5.jpg';
//$obj -> downloadImage($url);die;
//$obj ->getContent($url);
// echo "<pre>";
// 根据分析找到初始的url
for ($i = $obj->start;$i <= $obj->end; $i++){
	$url = "https://api.vc.bilibili.com/link_draw/v2/Photo/list?category=cos&type=hot&page_num=".$i."&page_size=20";
	//对拿到的信息进行处理,方便下一步筛选
	$res = $obj->getContentByf($url);
	$res = json_decode($res,true);
	if($res['msg'] === 'success'){
		$obj->data = $res['data']['items'];//拿到当前页面的所有图片与作者信息
	}

	// 对信息进行筛选
	// 根据图片名字进行筛选
	$obj->getImgByName($obj->name);
	//根据图片url地址进行下载
	if($obj->urls){
		foreach ($obj->urls as $k => $v) {
			$obj->downloadImage($v,$obj->name);
		}
	}else{
		echo "<script>alert('已经下载完成关键词相关的图片')</script>";
	}
	//目前是110秒跑完5个下拉页面 就是100个图片资源  下载与蕾姆相关图片520M
	//目前是10秒跑完5个下拉页面  就是100个图片资源  下载与妖刀姬相关图片3.7M
	//结论是效率可以再次改善但是下载速度取决与网速.没法
	//目前先做到这里 之后再来改善
}

?>