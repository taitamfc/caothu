<?php
/**
* BaseController
*/

class BaseController
{
	/*
	1: Macauslot
	3: Crown
	4: Ladbrokes
	7: SNAI
	8: Bet365
	9: William Hill
	12: Easybets
	14: Vcbet
	17: Mansion88
	19: Interwette
	22: 10BET
	23: 188bet
	24: 12bet
	31: Sbobet
	35: Wewbet
	42: 18bet
	47: Pinnacle
	48: HK Jockey Club
	*/
	public $companyId   = 8;
	public $ajax_url    = 'http://api-asia.isportsapi.com';
    /**
     * load the view
     * @param  string $view   view name
     * @param  array  $params additional variables to pass in view
     * @return void
     */
	 
	public function get_data($url){
        $ch = curl_init($url);      
        curl_setopt($ch, CURLOPT_HEADER, 0); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);       
        $data    = curl_exec($ch);      
        curl_close($ch);
        return $data;      
    }
	
    public function resJson($data,$status = 1){
        $data['status'] = $status;
        if( isset($data['items']) ){
            $data['count']  = count($data['items']);
        }
		header("Access-Control-Allow-Origin: *");
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        die();
    }
    public function view($view, $params = [])
    {
        if (!file_exists(VIEWS . $view.'.php')) {
            die('View: ' . $view . ' file not found');
        }
        if (count($params)) {
            extract($params);
        }
        include VIEWS . $view.'.php';
    }

    /**
     * load the model
     * @param  string $model  model name
     * @return object         model class object
     */
    public function loadModel($model)
    {
        if (!file_exists(MODELS . $model.'.php')) {
            die('MODEL: ' . $model . ' file not found');
        }
        include MODELS . $model.'.php';
        return new $model;
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
	
	public function check_cache_is_expried( $file_name ){
		$file_name = CACHE.$file_name;
		if( !file_exists($file_name) ){
			return true;
		}
		$time_save = filemtime($file_name);
		$time_now  = time();
		if( ( $time_save + 1800 ) < $time_now  ){
			return true;
		}
		return false;
	}
	public function get_cache_results( $file_name ){
		$file_name = CACHE.$file_name;
		$data = json_decode(file_get_contents($file_name));
		return $data;
	}
	public function cache_results( $data,$file_name ){
		$file_name = CACHE.$file_name;
		if ( $this->check_cache_is_expried( $file_name ) ) {
			file_put_contents($file_name, json_encode($data));
		}
	}
}
