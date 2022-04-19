<?php
/**
* BaseController
*/
include_once LIBRARIES.'MysqliDb.php';
class BaseModel
{
    protected   $_db;
    private     $_table = null;
	private $headers;
	private $user_agent;
	private $compression = 'gzip';
	private $cookie_file = false;
	private $proxy = '';

    public function __construct(){
        $this->_db = new MysqliDb(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		
		$this->headers[] = 'Accept: image/gif, image/x-bitmap, image/jpeg, image/pjpeg';
		$this->headers[] = 'Connection: Keep-Alive';
		$this->headers[] = 'Content-type: application/x-www-form-urlencoded;charset=UTF-8';
		$this->user_agent = 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/28.0.1500.95 Safari/537.36';
    }


    protected function setTable($_table){
        $this->_table = $_table;
    }

    public function loadLibrary($library)
    {
        include_once LIBRARIES.'simple_html_dom.php';
        include_once LIBRARIES.'Curl.php';
        if (!file_exists(LIBRARIES . $library.'.php')) {
            die('LIBRARIES: ' . $library . ' file not found');
        }
        include_once LIBRARIES . $library.'.php';
        return new $library;
    }

    public function store($data){
        $this->_db->insert ($this->_table, $data);
    }
    public function update($id,$data){
        $this->_db->where ('id', $id);
        $this->_db->update ($this->_table, $data);
    }
    public function sanitize_title($title){
        return $title;
    }
	
	public function get_data($url,$data_string = ''){
		
		$objCurl = new cUrl();
		$respon = $objCurl->custom_curl($url,false,'cookies-vaobo-com.txt');
		
		return $respon;
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_TIMEOUT, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		if(FALSE === ($retval = curl_exec($ch))) {
			error_log(curl_error($ch));
		} else {
			return $retval;
		}
    }
	
	function save_image_to_folder($image){
		$basedir = IMG_DIR.'/logo_teams';
		$baseurl = IMG_URL.'/logo_teams';
		
		if( $image == '/images/CLB-MacDinh.svg' ){
			$file_name = 'no-logo.gif';
			return $baseurl.'/'.$file_name;
		}
		
		
		
		$file_name = end( explode('/', $image) );
		

		if( file_exists( $basedir.'/'.$file_name ) || $file_name == 'no-logo.gif' ){
			return $baseurl.'/'.$file_name;
		}else{
			$img = $basedir.'/'.$file_name;
			$check = file_put_contents($img, file_get_contents($image));
			return $baseurl.'/'.$file_name;
		}
		
		return $image;
	}
}
