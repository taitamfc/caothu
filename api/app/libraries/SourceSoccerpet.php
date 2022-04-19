<?php
class SourceSoccerpet {
	public $predictions_url  = 'https://soccerpet.com/prediction';
	//public $predictions_url  = 'https://soccerpet.com/prediction?date=2021-11-16';
	public $prediction_url   = 'https://soccerpet.com/prediction/1x2/';
	public $ah_url   		 = 'https://soccerpet.com/prediction/ah/';
	public $over_url   		 = 'https://soccerpet.com/prediction/over-under/';
	public $analysis_url   	 = 'https://soccerpet.com/analysis/';
	public $start 			 = 0;

	public function find($link){
		
		$links = explode('/',$link);
		$source_id = $links[ count($links) - 2 ];
		//$source_id = 893170;

		$objCurl = new cUrl();
		$respon = $objCurl->custom_curl($this->prediction_url.$source_id,false,'cookies-soccerpet-com.txt');

		if(!$respon) return [];

		include_once 'simple_html_dom.php';
		$html 		= str_get_html($respon);

		//predict-detail 1x2
		$predict_1x2 = [];
		foreach( $html->find('.prob ul li') as $k => $predict_li ){
			$predict_1x2[] = [
				'percent' 	=> $predict_li->find('font',0)->innertext,
				'yes' 		=> trim(strip_tags($predict_li->find('p',0)->innertext)),
			];
		}

		$predict_ah 	= $this->_getPredictDetail($source_id,'ah');
		$predict_over 	= $this->_getPredictDetail($source_id,'over');
		
		$system_id 		= $source_id;
		$league_id 		= 0;
		$league_name 	= '';
		$league_logo 	= '';
		$home_team 		= $html->find('.home-team .team-name',0)->innertext;
		$home_logo 		= $html->find('.home-team .team-logo',0)->attr['src'];
		$away_team 		= $html->find('.guest-team .team-name',0)->innertext;
		$away_logo 		= $html->find('.guest-team .team-logo',0)->attr['src'];
		$game_date 		= $html->find('.vs-info .flask-moment',0)->attr['data-timestamp'];

		$data = [
			'source_id' 	=> $system_id,
			'league_id' 	=> $league_id,
			'league_name' 	=> $league_name,
			'league_logo' 	=> $league_logo,
			'home_team' 	=> $home_team,
			'home_logo' 	=> $home_logo,
			'away_team' 	=> $away_team,
			'away_logo' 	=> $away_logo,
			'game_date' 	=> $game_date,
			'link' 			=> $link
		];
		$data['predict_1x2'] 	= $predict_1x2;
		$data['predict_ah'] 	= $predict_ah;
		$data['predict_over'] 	= $predict_over;
		$data['ranking'] 		= $this->_getAnalysisDetail($source_id,'ranking');

		//predict-detail Asian Handicap
		//https://soccerpet.com/prediction/ah/893170/

		return $data;
		
	}

	private function _getAnalysisDetail($source_id,$type){
		$objCurl = new cUrl();
		$respon = $objCurl->custom_curl($this->analysis_url.$source_id,false,'cookies-soccerpet-com.txt');
		
		include_once 'simple_html_dom.php';
		$html 		= str_get_html($respon);
		
		$home_name 		= $html->find('div.league-points-ranking .home-team .team-name span',0)->innertext;
		$guest_name 	= $html->find('div.league-points-ranking .guest-team .team-name span',0)->innertext;
		$home_ranking 	= $html->find('div.league-points-ranking .home-team .data-table',0)->innertext;
		$guest_ranking 	= $html->find('div.league-points-ranking .guest-team .data-table',0)->innertext;
		$chart_home 	= $html->find('#war-history-home',0);
		$chart_away 	= $html->find('#war-history-guest',0);
		
		$home_name 	= strip_tags($home_name);
		$guest_name = strip_tags($guest_name);
		$home_name 	= str_replace('(',' (',$home_name);
		$guest_name = str_replace('(',' (',$guest_name);
		
		$data = [
			'home_name' 	=> $home_name,
			'guest_name' 	=> $guest_name,
			'home_ranking'  => $home_ranking,
			'guest_ranking' => $guest_ranking,
			'chart_home' 	=> [
				'teamname' 	=> $chart_home->attr['data-teamname'],
				'prob' 		=> $chart_home->attr['data-prob'],
				'numb_pre'  => $this->_getNumbPre($chart_home->attr['data-prob']),
			],
			'chart_away' 	=> [
				'teamname' 	=> $chart_away->attr['data-teamname'],
				'prob' 		=> $chart_away->attr['data-prob'],
				'numb_pre'  => $this->_getNumbPre($chart_away->attr['data-prob']),
			],
			'horizontal_line' 	=> $html->find('.charts-box li.all',0)->innertext,
			'h2h_table' 		=> '<table>'.$html->find('.war-history .data-table table',0)->innertext.'</table>',
			'home_recent'		=> $html->find('#recent_canvas0',0)->attr['data-result'],
			'away_recent'		=> $html->find('#recent_canvas4',0)->attr['data-result'],
			'home_recent_win_rate'	=> $html->find('#recent_canvas1',0)->attr['data-prob'],
			'guest_recent_win_rate'	=> $html->find('#recent_canvas5',0)->attr['data-prob'],
			'home_recent_game'	=> '<table>'.$html->find('.recent-record .home-team .data-table table',0)->innertext.'</table>',
			'guest_recent_game'	=> '<table>'.$html->find('.recent-record .guest-team .data-table table',0)->innertext.'</table>',
		];
		
		$horizontal_line = $data['horizontal_line'];
		$horizontal_line = str_replace('Last','Tổng',$horizontal_line);
		$horizontal_line = str_replace('Home WIn','Chủ nhà win',$horizontal_line);
		$horizontal_line = str_replace('Overrate','Tỉ lệ tài',$horizontal_line);
		$data['horizontal_line'] = $horizontal_line;
		
		$data['home_recent_win_rate_numb_pre'] = $this->_getNumbPre2($data['home_recent_win_rate']);
		$data['guest_recent_win_rate_numb_pre'] = $this->_getNumbPre2($data['guest_recent_win_rate']);
		
		return $data;
		
	}
	private function _getNumbPre2($prece){
		$rest_pre = 100 - $prece;
		$numb_pre = $prece.' '.$rest_pre;
		return $numb_pre;
	}
	private function _getNumbPre($prece){
		$rest_pre = ($prece/100)*50;
		$rest_pre = floor($rest_pre);
		$numb_pre = $rest_pre.' '.(100 - $rest_pre);
		return $numb_pre;
	}
	private function _getPredictDetail($source_id,$type){
		if( $type == 'ah' ){
			$call_url = $this->ah_url;
		}
		if( $type == 'over' ){
			$call_url = $this->over_url;
		}

		$objCurl = new cUrl();
		$respon = $objCurl->custom_curl($call_url.$source_id,false,'cookies-soccerpet-com.txt');

		include_once 'simple_html_dom.php';
		$html 		= str_get_html($respon);

		$predict = [];
		foreach( $html->find('.prob ul li') as $predict_li ){
			$predict[] = [
				'percent' 	=> $predict_li->find('font',0)->innertext,
				'tip' 		=> $predict_li->find('.bar_chart span',0)->innertext,
				'yes' 		=> trim(strip_tags($predict_li->find('p',0)->innertext)),
			];
		}

		return $predict;
	}

	public function getAll(){
		$objCurl = new cUrl();
		$respon = $objCurl->custom_curl($this->predictions_url,false,'cookies-soccerpet-com.txt');
		if(!$respon) return [];

		include_once 'simple_html_dom.php';
		$html 		= str_get_html($respon);

		if(!$html) return [];

		$es 		= $html->find('div.predict-list',0);
		$content 	= $es->innertext;
		$html 		= str_get_html($content);


		$lis = $html->find ('li');

		$data = [];
		foreach ($lis as $li) {
			$system_id 		= $li->attr['data-mid'];
			$league_id 		= $li->attr['data-lid'];
			$league_name 	= $li->find('h2 span',0)->innertext;
			$league_logo 	= $li->find('h2 img',0)->attr['src'];
			$home_team 		= $li->find('div.home .team-name',0)->innertext;
			$home_logo 		= $li->find('div.home img',0)->attr['src'];
			$away_team 		= $li->find('div.away .team-name',0)->innertext;
			$away_logo 		= $li->find('div.away img',0)->attr['src'];
			$game_date 		= $li->find('div.left span span',0)->attr['data-timestamp'];
			$link 			= $li->find('div.right a',0)->attr['href'];

			$data[] = [
				'source_id' 	=> $system_id,
				'league_id' 	=> $league_id,
				'league_name' 	=> $league_name,
				'league_logo' 	=> $league_logo,
				'home_team' 	=> $home_team,
				'home_logo' 	=> $home_logo,
				'away_team' 	=> $away_team,
				'away_logo' 	=> $away_logo,
				'game_date' 	=> $game_date,
				'link' 			=> $link
			];
		}

		return $data;
	}

}