<?php
class SourceKQBD {
	public $all_url 	= 'https://bongda24h.vn/FootballResult/AjaxFootballResult?date=';

	public function getAll($date = ''){
		$respon = $this->getDataByDate( $date );
		$data = $this->handleData( $respon,$date );
		return $data;
	}
	public function handleData( $respon,$date ){
		$game_date_origin = $date;
		$html = str_get_html($respon);
		
		//get leagues
		if( !isset($_SESSION['SourceKQBD_leagues']) ){
			$leagues = [];
			foreach( $html->find('.match-football-item') as $key => $league_html ){
				$league_id 				= $league_html->attr['id'];
				$league_id 				= str_replace('ltd','',$league_id);
				$leagues[$league_id] 	= trim($league_html->find('.fhead-left a',0)->innertext);
			}			
			$_SESSION['SourceKQBD_leagues'] = $leagues;
		}else{
			$leagues = $_SESSION['SourceKQBD_leagues'];
		}
		
		$data = [];
		
		//get teams
		foreach( $html->find('.matchdetail') as $key => $team_html ){
			$league_id 				= $team_html->attr['data-leagueid'];
			
			$score 		= $team_html->find('.soccer-scores',0)->innertext;
			$game_time 	= $team_html->find('.time',0)->innertext;
			$game_date 	= $team_html->find('.date',0)->innertext;
			$game_round = $team_html->find('.vongbang',0)->innertext;
			$first_half = $team_html->find('.item_ktv',0)->innertext;
			$source_id 	= $team_html->find('.columns-number',0)->attr['id'];
			$source_id	= str_replace('_result_content','',$source_id);
			
			$home_team_html = $team_html->find('.columns-club',0);
			$away_team_html = $team_html->find('.columns-club',1);
			
			$home_name 	= $home_team_html->find('.logo-club',0)->attr['alt'];
			$home_image = $home_team_html->find('.logo-club',0)->attr['src'];
			$home_thedo = $home_team_html->find('.thedo',0)->innertext;
			$away_name 	= $away_team_html->find('.logo-club',0)->attr['alt'];
			$away_image = $away_team_html->find('.logo-club',0)->attr['src'];
			$away_thedo = $away_team_html->find('.thedo',0)->innertext;
			
			$result = [
				'league_id' 	=> $league_id,
				'source_id' 	=> $source_id,
				'league_name' 	=> $leagues[$league_id],
				'home_team' 	=> $home_name,
				'home_logo' 	=> $home_image,
				'home_thedo' 	=> $home_thedo,
				'away_team' 	=> $away_name,
				'away_logo' 	=> $away_image,
				'away_thedo' 	=> $away_thedo,
				'game_time' 	=> $game_time,
				'game_date' 	=> $game_date,
				'game_date_origin' 	=> $game_date_origin,
				'game_round' 	=> $game_round,
				'first_half' 	=> $first_half,
				'score' 		=> $score,
			];
			
			foreach( $result as $k => $v ){
				$result[$k] = trim($v);
			}
			
			$data[] = $result;
		}

		return $data;
	}
	public function getDataByDate( $date ){
		$objCurl = new cUrl();
		$respon = $objCurl->custom_curl('https://bongda24h.vn/FootballResult/AjaxFootballResult?date='.$date,false,'cookies-bongda24h-vn.txt');
		return $respon;
	}

}