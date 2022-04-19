<?php
class SourceBongDaLu {
	//public $identify_url 	= 'https://www.bongdalu.com/news';
	public $identify_url 	= 'https://www.bongdalu.com/OddsData.aspx?companyid=24';
	public $fixture_url 	= 'https://bongdaplus.vn/tags/nhan-dinh-bong-da';
	public $bet_url 		= 'https://bongdaplus.vn/soi-keo-bong-da/';
	public $start 			= 0;
	public function findCompOdds($match_id){
		$odds_raw = file_get_contents('https://www.bongdalu.com/AjaxCenter/SoccerAjax.ashx?action=getCompOdds&sid='.$match_id.'&t='.time());

		$odds_raw 	= str_replace('{"ErrCode":0,"Data":"',"",$odds_raw);
		$odds_raw 	= str_replace('"}',"",$odds_raw);
		$record 	= explode("#",$odds_raw);
		$compId 	= explode(",",$record[0])[1];
		$odds 		= $record[1];
		$sOdds   	= explode(',',$odds);

		$ht = [
			'hdp_som_1' 	=> $sOdds[3],
			'hdp_som_2' 	=> $sOdds[4],
			'hdp_som_3' 	=> $sOdds[5],
			'hdp_live_1' 	=> $sOdds[6],
			'hdp_live_2' 	=> $sOdds[7],
			'hdp_live_3' 	=> $sOdds[8],
			'tx_som_1' 		=> $sOdds[15],
			'tx_som_2' 		=> $sOdds[16],
			'tx_som_3' 		=> $sOdds[17],
			'tx_live_1' 	=> $sOdds[18],
			'tx_live_2' 	=> $sOdds[19],
			'tx_live_3' 	=> $sOdds[20],
			'one_2_som_1' 	=> $sOdds[27],
			'one_2_som_2' 	=> $sOdds[28],
			'one_2_som_3' 	=> $sOdds[29],
			'one_2_live_1' 	=> $sOdds[30],
			'one_2_live_2' 	=> $sOdds[31],
			'one_2_live_3' 	=> $sOdds[32],
		];
		$ft = [
			'hdp_som_1' 	=> $sOdds[9],
			'hdp_som_2' 	=> $sOdds[10],
			'hdp_som_3' 	=> $sOdds[11],
			'hdp_live_1' 	=> $sOdds[12],
			'hdp_live_2' 	=> $sOdds[13],
			'hdp_live_3' 	=> $sOdds[14],
			'tx_som_1' 		=> $sOdds[21],
			'tx_som_2' 		=> $sOdds[22],
			'tx_som_3' 		=> $sOdds[23],
			'tx_live_1' 	=> $sOdds[24],
			'tx_live_2' 	=> $sOdds[25],
			'tx_live_3' 	=> $sOdds[26],
			'one_2_som_1' 	=> $sOdds[33],
			'one_2_som_2' 	=> $sOdds[34],
			'one_2_som_3' 	=> $sOdds[35],
			'one_2_live_1' 	=> $sOdds[36],
			'one_2_live_2' 	=> $sOdds[37],
			'one_2_live_3' 	=> $sOdds[38]
		];

		$data = [
			'ht' => $ht,
			'ft' => $ft,
		];

		return $data;
	}
	public function find_odds($match_id){
		$odds_raw = file_get_contents('https://www.bongdalu.com/Ajax.aspx?type=23&ID='.$match_id);

		$ll = explode("^",$odds_raw);
		$allCompOdds = [];
	    for ($i = 0; $i < count($ll); $i++) {
	        $allCompOdds[] = explode(";", $ll[$i]);
	    }

	    $odds_data = null;
	    foreach( $allCompOdds as $allCompOdd){
	    	if( $allCompOdd[0] == 8 ){
	    		$odds_data = $allCompOdd;
	    	}
	    }

	    if(!$odds_data){
	    	$odds_data = end($allCompOdds);
	    }

	    $o1 = explode(",",$odds_data[2]);
	    $o2 = explode(",",$odds_data[3]);

	    $odds = [
	    	'som_chau_a_chu' 		=> $o1[7],
    		'som_chau_a_HDP' 		=> $o1[8],
    		'som_chau_a_khach' 		=> $o1[9],
    		'som_tai' 				=> $o1[11],
    		'som_tl' 				=> $o1[12],
    		'som_xiu' 				=> $o1[13],
    		'som_chau_au_chu' 		=> $o1[0],
    		'som_chau_au_HDP' 		=> $o1[1],
    		'som_chau_au_khach' 	=> $o1[2],
    		'cuoi_chau_a_chu' 		=> $o2[7],
    		'cuoi_chau_a_HDP' 		=> $o2[8],
    		'cuoi_chau_a_khach' 	=> $o2[9],
    		'cuoi_tai' 				=> $o2[11],
    		'cuoi_tl' 				=> $o2[12],
    		'cuoi_xiu' 				=> $o2[13],
    		'cuoi_chau_au_chu' 		=> $o2[0],
    		'cuoi_chau_au_HDP' 		=> $o2[1],
    		'cuoi_chau_au_khach' 	=> $o2[2]
	    ];

	    return $odds;
	}
	public function find($match_id){
		$this->findCompOdds($match_id);
		$h2h 	= 'https://www.bongdalu.com/match/h2h-'.$match_id;
		$respon = file_get_contents($h2h);
		if(!$respon){
			return false;
		}
		include_once 'simple_html_dom.php';



		$html = str_get_html($respon);
		$es = $html->find('div[id="info"]', 0);
		$content = $es->innertext;

		$html 		= str_get_html($content);


		/*
		Sớm: tr_o_1_24
		Live: tr_o_2_24
		Run: tr_o_3_24 => Đang đá
		*/

		$data = [];

		$data['league_name'] 	= $html->find('span.LName a', 0)->innertext;
		$data['match_time'] 	= $html->find('span[id="match_time"] script', 0)->innertext;
		$data['match_time'] 	= $this->_format_date($data['match_time']);
		$data['match_time'] 	= '';

		$data['teams'] 			= [];
		foreach( $html->find('span.sclassName') as $hteam ){
			$data['teams'][] = $hteam->find('a',0)->innertext;
		}

		$data['home_logo'] = $html->find('div#homeImg img',0)->attr['src'];
		$data['away_logo'] = $html->find('div#guestImg img',0)->attr['src'];
		

		//Đội hình gần đây - Home
		$h_players = $html->find('div[id="porletP14"] div.home-div div.player-list',0)->find('a');
		$data['home_main_players'] = [];
		foreach( $h_players as $h_player ){
			$data['home_main_players'][] = $this->_find_player_info($h_player);
		}
		$s_players = $html->find('div[id="porletP14"] div.home-div div.player-list',1)->find('a');
		$data['home_sub_players'] = [];
		foreach( $s_players as $h_player ){
			$data['home_sub_players'][] = $this->_find_player_info($h_player);
		}

		//Đội hình gần đây - Away
		$h_players = $html->find('div[id="porletP14"] div.guest-div div.player-list',0)->find('a');
		$data['away_main_players'] = [];
		foreach( $h_players as $h_player ){
			$data['away_main_players'][] = $this->_find_player_info($h_player);
		}
		$s_players = $html->find('div[id="porletP14"] div.guest-div div.player-list',1)->find('a');
		$data['away_sub_players'] = [];
		foreach( $s_players as $h_player ){
			$data['away_sub_players'][] = $this->_find_player_info($h_player);
		}

		//Chấn thương - Home
		$h_players = $html->find('div[id="porletP13"] div.home-div div.player-list',0)->find('a');
		$data['home_injury_players'] = [];
		foreach( $h_players as $h_player ){
			$data['home_injury_players'][] = $this->_find_player_info($h_player);
		}
		//Treo giò - Home
		$s_players = $html->find('div[id="porletP13"] div.home-div div.player-list',1)->find('a');
		$data['home_hanging_players'] = [];
		foreach( $s_players as $h_player ){
			$data['home_hanging_players'][] = $this->_find_player_info($h_player);
		}

		//Chấn thương - Away
		$h_players = $html->find('div[id="porletP13"] div.guest-div div.player-list',0)->find('a');
		$data['away_injury_players'] = [];
		foreach( $h_players as $h_player ){
			$data['away_injury_players'][] = $this->_find_player_info($h_player);
		}
		//Treo giò - Away
		$s_players = $html->find('div[id="porletP13"] div.guest-div div.player-list',1)->find('a');
		$data['away_hanging_players'] = [];
		foreach( $s_players as $h_player ){
			$data['away_hanging_players'][] = $this->_find_player_info($h_player);
		}


		//Thanh tich doi dau
		$tt_arr = [];
		foreach( $html->find('table[id="table_v3"]',0)->find('tr') as $k => $h_tr ){
			$td = [];
			if( $k < 3 ) continue;
			foreach( $h_tr->find('td') as $h_td ){
				$td[] = strip_tags($h_td->innertext);
			}
			$tt_arr[] = $td;
		}

		$data['text_doi_dau'] = current(array_pop($tt_arr));
		$data['text_doi_dau'] = $html->find('td[id="td_stat3"]',0)->innertext;
		$data['talking_numbers'][] = $this->find_talking_numbers(3,$html,$data['teams'][0]);

		$fn_tt_arr = [];
		foreach( $tt_arr as $dt ){
			$fn_tt_arr[] = [
				'league_name' 	=> $dt[0],
				'game_date' 	=> $this->_format_date($dt[1]),
				'home_name' 	=> $dt[2],
				'result' 		=> $dt[3],
				'HT' 			=> $dt[4],
				'pgoc' 			=> $dt[5],
				'away_name' 	=> $dt[6],
			];
		}

		$data['h2h'] = $fn_tt_arr;

		//Thanh tich gan day home
		$tt_arr = [];
		foreach( $html->find('table[id="table_v1"]',0)->find('tr') as $k => $h_tr ){
			$td = [];
			if( $k < 3 ) continue;
			foreach( $h_tr->find('td') as $h_td ){
				$td[] = strip_tags($h_td->innertext);
			}
			$tt_arr[] = $td;
		}
		$data['text_tt_home'] = current( array_pop($tt_arr) );
		$data['text_tt_home'] = $html->find('td[id="td_stat1"]',0)->innertext;
		$data['talking_numbers'][] = $this->find_talking_numbers(1,$html,$data['teams'][0]);

		$fn_tt_arr = [];
		foreach( $tt_arr as $dt ){
			$fn_tt_arr[] = [
				'league_name' 	=> $dt[0],
				'game_date' 	=> $this->_format_date($dt[1]),
				'home_name' 	=> $dt[2],
				'result' 		=> $dt[3],
				'HT' 			=> $dt[4],
				'pgoc' 			=> $dt[5],
				'away_name' 	=> $dt[6],
				'TBH' 			=> $dt[13],
			];
		}

		$data['home_recent'] = $fn_tt_arr;

		//Thanh tich gan day away
		$tt_arr = [];
		foreach( $html->find('table[id="table_v2"]',0)->find('tr') as $k => $h_tr ){
			$td = [];
			if( $k < 3 ) continue;
			foreach( $h_tr->find('td') as $h_td ){
				$td[] = strip_tags($h_td->innertext);
			}
			$tt_arr[] = $td;
		}


		$data['text_tt_away'] = current( array_pop($tt_arr) );
		$data['text_tt_away'] = $html->find('td[id="td_stat2"]',0)->innertext;
		$data['talking_numbers'][] = $this->find_talking_numbers(2,$html,$data['teams'][1]);

		$fn_tt_arr = [];
		foreach( $tt_arr as $dt ){
			$fn_tt_arr[] = [
				'league_name' 	=> $dt[0],
				'game_date' 	=> $this->_format_date($dt[1]),
				'home_name' 	=> $dt[2],
				'result' 		=> $dt[3],
				'HT' 			=> $dt[4],
				'pgoc' 			=> $dt[5],
				'away_name' 	=> $dt[6],
				'TBH' 			=> $dt[13],
			];
		}


		$data['away_recent'] 	= $fn_tt_arr;
	    $data['odds'] 			= $this->find_odds($match_id);
	    $data['comp_odds'] 		= $this->findCompOdds($match_id);

	    $tt_numbers = [];
	    foreach( $data['talking_numbers'] as $talking_numbers ){
	    	foreach( $talking_numbers as $talking_number ){
	    		$tt_numbers[] = $talking_number;
	    	}
	    }
	    $data['talking_numbers'] = $tt_numbers;

		return $data;
	}

	public function getVideo($match_id){
		//$match_id = 2053185;
		$url 	= 'https://www.bongdalu.com/match/live-'.$match_id;
		$respon = file_get_contents($url);
		

		include_once 'simple_html_dom.php';
		$html 	= str_get_html($respon);

		//Thông tin trận đấu
		$data['league_name'] = $html->find('span.LName',0)->innertext;
		$data['league_name'] = trim(strip_tags($data['league_name']));
		$data['league_name'] = str_replace('&nbsp;','', $data['league_name']);
		$data['home_logo'] = $html->find('div#homeImg img',0)->attr['src'];
		$data['away_logo'] = $html->find('div#guestImg img',0)->attr['src'];
	
		//Thống kê kỹ thuật
		$stats = [];
		foreach( $html->find('div#teamTechDiv_detail li') as $stat_c ){
			$stats[] = [
				'val_name' => $stat_c->find('.stat-title',0)->innertext,
				'val_left' => $stat_c->find('.stat-c',0)->innertext,
				'val_right' => $stat_c->find('.stat-c',1)->innertext,
			];
		}

		

		foreach( $data as $key => $data_v ){
			$data[$key] = trim($data_v);
			$data[$key] = strip_tags($data_v);
		}
		//Sự kiện
		$events = $html->find('div#teamEventDiv_detail table.team-table-other',0)->innertext;
		$data['events'] = $events;

		$data['stats'] = json_encode($stats);
		
		return $data;
	}
	public function getAllVideos(){
		$data = file_get_contents('https://www.bongdalu.com/gf/data/finish/bf_vn.js?'.time());
		$data = explode(']=[',$data);
		unset($data[0]);
		$games = [];
		foreach( $data as $row ){
			$row = str_replace('];','',$row);
			$cols = explode(',',$row);
			if( count($cols) > 30 ){
				//$games[] = $cols;
				$start_date = str_replace("'","",$cols[6]);
				$games[] = [
					'match_id' 		=> $cols[0],
					'home_name' 	=> strip_tags(str_replace("'","",$cols[4])),
					'away_name' 	=> strip_tags(str_replace("'","",$cols[5])),
					'home_result' 	=> $cols[9],
					'away_result' 	=> $cols[10],
					'start_date' 	=> $start_date,
				];
			}
		}
		return $games;

	}

	private function _update_datalist($kind,$html,$datalist){
		$tds = $html->find('table[id="table_v'.$kind.'"] tr');
		
		$infos = [];
		foreach( $tds as $td ){
			$info = $td->attr['info'];
			if( $info ){
				$infos[] = $info;
			}
		}

		$win = $standoff = $ouWin = 0;
		$result = $this->getResult(floatval($dTr[1]), intval($infos[0]), intval($infos[1]), intval($infos[2]), $kind);

		echo '<pre>';
		print_r($td_infos);
		die();
	}

	private function find_talking_numbers($kind,$html,$team_name){
		$script_text 	= $html->find('table[id="table_v'.$kind.'"]',0)->next_sibling()->innertext;
		$pattern 		= '/dataArray'.$kind.'="(.*)"/';
		preg_match($pattern, $script_text, $matches);
		$datalist 		= explode('#',$matches[1]);

		//$datalist 		= $this->_update_datalist($kind,$html,$datalist);

		$intWin 		= 0;
		$intStandoff 	= 0;
		$intVictoryNum 	= 0;
		$ouCount 		= 0;
		$intBigNum 		= 0;
		$intSingleNum 	= 0;
		$intDraw 		= 0;
		$matchCount 	= 0;

		//for( $j = 0; $j < count($datalist); $j++ ){
		for( $j = 0; $j < 10; $j++ ){
			if( $datalist[$j] ){
				$subdata = explode(',',$datalist[$j]);
				$intWin += intval($subdata[1]);
				if (intval($subdata[2]) == 1){
					$intStandoff++;
				}
				$intVictoryNum += intval($subdata[3]);
				if (intval($subdata[4]) != -1) {
	                if (intval($subdata[4]) == 1){
	                	$intBigNum++;
	                }
	                $ouCount++;
	            }
	            $intSingleNum += intval($subdata[5]);
	            $intDraw += intval($subdata[6]);
	            $matchCount += 1;
			}
		}

		$win1 = ($intWin / $intStandoff) * 100;
		$resOu = 1.0 * $intBigNum / $ouCount * 100;

		$return = [
			'intWin' 		=> $intWin,
			'intStandoff' 	=> $intStandoff,
			'intVictoryNum' => $intVictoryNum,
			'ouCount' 		=> $ouCount,
			'intBigNum' 	=> $intBigNum,
			'intSingleNum' 	=> $intSingleNum,
			'intDraw' 		=> $intDraw,
			'matchCount' 	=> $matchCount,
			'win1' 			=> $win1,
			'resOu' 		=> $resOu,
		];
		if( $kind == 3 ){
			$text_1 = '{0} trận đối đầu trong quá khứ, {__team_name__} thắng {1} trận, hòa {2} trận, thua {3} trận; tỷ lệ thắng của đội nhà là {4}';
			$text_2 = '{0} trận đối đầu trong quá khứ, {__team_name__} có tỷ lệ thắng kèo Châu Á là {5}; Tỷ lệ trận đấu ra Tài là {6}.';
		}else{
			$text_1 = '{0} trận gần đây, {__team_name__} thắng {1} trận, hòa {2} trận, thua {3} trận; Tỷ lệ thắng {4}';
			$text_2 = '{0} trận gần đây, {__team_name__} có tỷ lệ thắng kèo Châu Á  là {5}; Tỷ lệ trận đấu ra Tài là {6}.';
		}
		

		$text_1 = str_replace('{__team_name__}',$team_name,$text_1);
		$text_1 = str_replace('{0}',$matchCount,$text_1);
		$text_1 = str_replace('{1}',$intVictoryNum,$text_1);
		$text_1 = str_replace('{2}',$intDraw,$text_1);
		$text_1 = str_replace('{3}',($matchCount - $intVictoryNum - $intDraw),$text_1);

		$win_rate = ($intVictoryNum / $matchCount * 100);
		$win_rate = substr($win_rate,0,min( strlen($win_rate) ,4));
		$text_1 = str_replace('{4}',$win_rate .'%',$text_1);

		// $text_2 = str_replace('{0}',$matchCount,$text_2);
		// $text_2 = str_replace('{5}',$win1,$text_2);
		// $text_2 = str_replace('{6}',$resOu,$text_2);

		return [
			'text_1' => $text_1
		];

		echo $text_1;
		echo '<br>';
		echo $text_2;
	}


	private function _format_date( $the_date ){
		$the_date = str_replace('formatDate(','',$the_date);
		$the_date = str_replace(')','',$the_date);
		return $the_date;
	}

	private function _find_player_info($h_player){
		$pos 	= trim($h_player->find('b',0)->innertext);
		$no 	= trim($h_player->find('span',0)->innertext);
		$player = $h_player->innertext;
		$player = str_replace('<b>'.$pos.'</b>', '', $player);
		$player = str_replace('<span>'.$no.'</span>', '', $player);
		$player = trim(preg_replace('/[0-9]+/', '', $player));
		$info 	= [
			'pos' => $pos,
			'no' => $no,
			'name' => strip_tags($player)
		];
		return $info;
	}

	public function getAll(){
		$objCurl = new cUrl();
		//$respon = $objCurl->custom_curl($this->identify_url,false,'cookies-www-bongdalu-com.txt');

		$respon = file_get_contents($this->identify_url);


		$data = str_replace('0$','0;',$respon);

		$datas = explode(';',$data);

		$headers = [];
		$matches_str = [];
		foreach( $datas as $data ){
			if( strpos($data,'#') !== false ){
				$headers[] = $data;
			}else{
				$matches_str[] = $data;
			}
		}

		$leagues = [];
		foreach( $headers as $header ){
			$header_arr = explode(',',$header);
			$leagues[$header_arr[0]] = [
				'league_id' 	=> $header_arr[0],
				'col_1' 		=> $header_arr[1],
				'color' 		=> $header_arr[2],
				'league_name' 	=> $header_arr[3],
				'league_link' 	=> $header_arr[4],
				'col_2' 		=> $header_arr[5],
			];
		}

		
		$matches = [];
		foreach( $matches_str as $match_str ){
			$match_str_arr = explode(',',$match_str);

			if( isset($match_str_arr[9]) && $match_str_arr[9] != 'False'&& $match_str_arr[9] != 'True' ){

				$matches[] = [
					'match_id' 	=> $match_str_arr[0],
					'league_id' => $match_str_arr[1],
					'col_1' 	=> $this->_format_date_from_microtime($match_str_arr[2]),
					'col_2' 	=> $this->_format_date_from_microtime($match_str_arr[3]),
					'col_3' 	=> $match_str_arr[4],
					'home' 		=> $match_str_arr[5],
					'col_4' 	=> $match_str_arr[6],
					'away' 		=> $match_str_arr[7],
					'col_4' 	=> ( isset( $match_str_arr[8] ) ) ? $match_str_arr[8] : '',
					'col_5' 	=> ( isset( $match_str_arr[9] ) ) ? $match_str_arr[9] : '',
					'col_6' 	=> ( isset( $match_str_arr[10] ) ) ? $match_str_arr[10] : '',
					'col_7' 	=> ( isset( $match_str_arr[11] ) ) ? $match_str_arr[11] : ''
				];
			}
		}

	

		/*
		foreach( $leagues as $key => $league ){
			$league_matches = [];
			foreach( $matches as $match ){
				if( $match['league_id'] == $league['league_id'] ){
					$league_matches[] = $match;
				}
			}
			$leagues[$key]['league_matches'] = $league_matches;
		}
		*/

		return [
			'leagues' => $leagues,
			'matches' => $matches,
		];


		return $data;
	}

	private function _format_date_from_microtime($micro){
		return date('Y-m-d H:i:s', $micro/1000 );
	}
}