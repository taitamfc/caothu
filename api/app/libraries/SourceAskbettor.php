<?php
class SourceAskbettor {
	public $games_url 			= 'https://askbettor.com/api/v1/menu/games';
	public $league_url 			= 'https://askbettor.com/api/v2/league/premier_league';
	public $filter_url 			= 'https://askbettor.com/api/v1/menu/games';
	public $predictions_url  	= 'https://askbettor.com/api/v1/apt_data/19';
	public $prediction_url  	= 'https://askbettor.com/api/v2/dashboard/';
	public $card_url  			= 'https://askbettor.com/api/v2/dashboard/card/';
	public $card_league_url  	= 'https://askbettor.com/api/v1/card_data/league_table_points/';
	public $similar_url  		= 'https://askbettor.com/api/v2/similar_games/';
	public $start 				= 0;

	public function getAllFilters(){
		$objCurl = new cUrl();
		$respon = $objCurl->custom_curl($this->filter_url);
		echo $respon;
		die();
		
	}
	public function getAll(){
		$objCurl = new cUrl();
		$respon = $objCurl->custom_curl($this->predictions_url,false,'cookies-askbettor-com.txt');
		return json_decode($respon);		
	}

	public function find($link){

		$objCurl = new cUrl();
		$respon = $objCurl->custom_curl($this->prediction_url.$link.'?t='.time(),false,'cookies-askbettor-com.txt');
		return json_decode($respon);		
	}
	public function get_card_detail($card_id){
		$objCurl = new cUrl();
		$respon = $objCurl->custom_curl($this->card_url.'/'.$card_id,false,'cookies-askbettor-com.txt');
		return json_decode($respon);		
	}
	public function get_card_league_table($card_id){
		$objCurl = new cUrl();
		$respon = $objCurl->custom_curl($this->card_league_url.'/'.$card_id,false,'cookies-askbettor-com.txt');
		return json_decode($respon);		
	}
	public function get_filter_semilars($card_id){
		$objCurl = new cUrl();
		$respon = $objCurl->custom_curl($this->similar_url.'/'.$card_id,false,'cookies-askbettor-com.txt');
		return json_decode($respon);		
	}

}