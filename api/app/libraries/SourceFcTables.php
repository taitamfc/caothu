<?php
class SourceFcTables {
	public $all_url 	= 'https://www.fctables.com/h2h';
	public $detail_url 	= 'https://www.fctables.com';
	public $start 			= 0;
	public function find($link){

		$objCurl = new cUrl();
		$respon = $objCurl->custom_curl($this->detail_url.$link,false,'cookies-www-fctables-com.txt');

		include_once 'simple_html_dom.php';

		$html = str_get_html($respon);
		
		$home_name 		= $html->find('.gnbox.home span',0)->innertext;
		$away_name 		= $html->find('.gnbox.away span',0)->innertext;


		//kết quả đối đầu của 2 đội
		$h2h_games = [];
		$h2h_home_win 	= 0;
		$h2h_away_win 	= 0;
		$h2h_draw 		= 0;
		$the_key_game = 0;
		foreach( $html->find('div#direct-matches .box_last_matches .game_table_hover') as $key_game => $game_html ){
			$home_result = $game_html->find('div.game_hover_info a span.score span',0)->innertext;
			$away_result = $game_html->find('div.game_hover_info a span.score span',2)->innertext;
			
			if($home_result == '') continue;
			if($the_key_game == 7) break;
			$the_key_game++;
			
			
			$home = trim( strip_tags( $game_html->find('div.game_hover_info a span.home',0)->innertext) );
			$away = trim( strip_tags( $game_html->find('div.game_hover_info a span.away',0)->innertext) );


			
			$home = $this->_format_team($home);
			$away = $this->_format_team($away);
			
			if( $home_name == $home ){
				if( $home_result > $away_result ){
					$home_gres = 'W';
					$away_gres = 'L';
					$h2h_home_win ++;
				}elseif ($home_result < $away_result) {
					$home_gres = 'L';
					$away_gres = 'W';
					$h2h_away_win ++;
				}else{
					$home_gres = 'D';
					$away_gres = 'D';
					$h2h_draw ++;
				}
			}else{
				if( $home_result < $away_result ){
					$home_gres = 'W';
					$away_gres = 'L';
					$h2h_home_win ++;
				}elseif ($home_result > $away_result) {
					$home_gres = 'L';
					$away_gres = 'W';
					$h2h_away_win ++;
				}else{
					$home_gres = 'D';
					$away_gres = 'D';
					$h2h_draw ++;
				}
			}
			
			
			$game = [
				'home_gres' => $home_gres,
				'away_gres' => $away_gres,
				'home' => $home,
				'away' => $away,
				'home_result' => trim($home_result),
				'away_result' => trim($away_result),
				'game_date' => $game_html->find('time[itemprop="startDate"]',0)->attr['datetime']
			];

			foreach( $game as $gk => $gv ){
				$game[$gk] = strip_tags($gv);
			}

			$h2h_games[] = $game;
		}
		
		$txt_tk_h2h = 'Vaobo.com thống kê '.count($h2h_games).' trận gần nhất; ';
		$txt_tk_h2h .= '<strong>'.$home_name.'</strong> thắng '.$h2h_home_win.', ';
		$txt_tk_h2h .= '<strong>'.$away_name.'</strong> thắng '.$h2h_away_win.', ';
		$txt_tk_h2h .= 'Hòa '.$h2h_draw.'. ';
		

		//phong độ gần đây của đội nhà và đội khách
		$recent_games = [];
		
		$h2h_home_recent_win 	= 0;
		$h2h_home_recent_draw 	= 0;
		$h2h_home_recent_lost 	= 0;
		
		$h2h_away_recent_win 	= 0;
		$h2h_away_recent_draw 	= 0;
		$h2h_away_recent_lost 	= 0;
		
		foreach( $html->find('div#last-games .box_last_matches') as $key => $last_matches ){
			$recent_games[$key] = [];
			$the_key_game = 0;
			foreach( $last_matches->find('.game_table_hover') as $key_game => $game_html ){

				$home_result = trim($game_html->find('div.game_hover_info a span.score span',0)->innertext);
				$away_result = trim($game_html->find('div.game_hover_info a span.score span',2)->innertext);
				
				if($home_result == '') continue;
				if($the_key_game == 7) break;
				$the_key_game++;
				

				$home = $game_html->find('div.game_hover_info a span.home',0)->innertext;
				$away = $game_html->find('div.game_hover_info a span.away',0)->innertext;

				$home = $this->_format_team($home);
				$away = $this->_format_team($away);
				
				$gres = $game_html->find('div.gres .btn',0)->innertext;
				$gres = trim($gres);
				
				if( $key == 0 ){
					switch( $gres ){
						case 'W':
							$h2h_home_recent_win ++;
							break;
						case 'L':
							$h2h_home_recent_lost ++;
							break;
						case 'D':
							$h2h_home_recent_draw ++;
							break;
					}
				}
				
				if( $key == 1 ){
					switch( $gres ){
						case 'W':
							$h2h_away_recent_win ++;
							break;
						case 'L':
							$h2h_away_recent_lost ++;
							break;
						case 'D':
							$h2h_away_recent_draw ++;
							break;
					}
				}
				
				$game_date = $game_html->find('time[itemprop="startDate"]',0)->attr['datetime'];
				$game = [
					
					'gres' => $gres,
					'home' => $home,
					'away' => $away,
					'home_result' => $home_result,
					'away_result' => $away_result,
					'game_date' => $game_date,
					'link' => $this->_generate_link($home,$away)
				];

				foreach( $game as $gk => $gv ){
					$game[$gk] = strip_tags($gv);
				}

				$recent_games[$key][] = $game;
			}
		}
		

		$txt_tk_home_recent = 'Tổng số trận đấu: '.count($recent_games[0]).'; ';
		$txt_tk_home_recent = 'Vaobo.com thống kê '.count($recent_games[0]).' trận gần nhất; ';
		$txt_tk_home_recent .= '<strong>'.$home_name.'</strong> thắng '.$h2h_home_recent_win.'; ';
		$txt_tk_home_recent .= 'Hòa '.$h2h_home_recent_draw.'; ';
		$txt_tk_home_recent .= 'Thua '.$h2h_home_recent_lost.'; ';
		$txt_tk_home_recent .= 'Tỉ lệ thắng: '.round( $h2h_home_recent_win / count($recent_games[0]) * 100   ) .'%';

		$txt_tk_away_recent = 'Tổng số trận đấu: '.count($recent_games[1]).'; ';
		$txt_tk_away_recent = 'Vaobo.com thống kê '.count($recent_games[1]).' trận gần nhất; ';
		$txt_tk_away_recent .= '<strong>'.$away_name.'</strong> thắng '.$h2h_away_recent_win.'; ';
		$txt_tk_away_recent .= 'Hòa '.$h2h_away_recent_draw.'; ';
		$txt_tk_away_recent .= 'Thua '.$h2h_away_recent_lost.'; ';
		$txt_tk_away_recent .= 'Tỉ lệ thắng: '.round( $h2h_away_recent_win / count($recent_games[1]) * 100   ).'%';


		//Số liệu thống kê của 2 đội
		$stats_arr = $html->find('div.profile-detail.card-box.team_profile');
		$team_stats = [];
		foreach( $stats_arr as $key => $stats ){
			$team_stats[$key] = [];
			foreach( $stats->find('li') as $stat ){
				$team_stats[$key][] = $stat->find('div',0)->innertext;
			}
		}

		
		$start_date 	= $html->find('.game_date.date_unix',0)->attr['data-unixtime'];
		$home_logo 		= $html->find('.gnbox.home img',0)->attr['src'];
		$away_logo 		= $html->find('.gnbox.away img',0)->attr['src'];
		$league_name 	= $html->find('.h2h_league_name a',0)->innertext;
		$trang_thai 	= $html->find('.round-cd span',0)->innertext;
		$ti_so 			= $html->find('.game-score',0)->innertext;
		

		$data = [
			'txt_tk_h2h' => $txt_tk_h2h,
			'txt_tk_home_recent' => $txt_tk_home_recent,
			'txt_tk_away_recent' => $txt_tk_away_recent,
			'h2h_games' 	=> json_encode($h2h_games),
			'recent_games' 	=> json_encode($recent_games),
			'team_stats' 	=> json_encode($team_stats),
			'home_name' 	=> $home_name,
			'away_name' 	=> $away_name,
			'start_date' 	=> $start_date,
			'home_logo' 	=> $home_logo,
			'away_logo' 	=> $away_logo,
			'league_name' 	=> $league_name,
			'trang_thai' 	=> $trang_thai,
			'ti_so' 		=> $ti_so,
		];
		

		return $data;

	}

	private function _generate_link($home,$away){
		$home_slug = sanitize_title($home);
		$away_slug = sanitize_title($away);
		return '/h2h/'.$home_slug.'/'.$away_slug.'/';
	}
	private function _format_team($team){
		$team = strip_tags($team);
		$team = str_replace('pen','',$team);
		$team = str_replace('-','',$team);
		
		if( stripos($team, 'U23') === false ){
			$team = preg_replace('/[0-9]+/', '', $team);
		}
		$team = trim($team);
		return $team;
	}

	public function getMatches($h2h_html){
		$home 	=  $h2h_html->find('a[data-toggle="tooltip"] span.home',0)->innertext;
		$away 	=  $h2h_html->find('a[data-toggle="tooltip"] span.away',0)->innertext;
		$slug 	= $h2h_html->find('a[data-toggle="tooltip"]',0)->attr['href'];
		$start_date = $h2h_html->find('div.game_date',0)->attr['data-unixtime'];
		

		$h2h_game = [
			'showHome' => 1,
			'start_date' => $start_date,
			'link' 		=> $slug,
			'source' => $slug,
			'home_name' => strip_tags($home),
			'home_logo' => $h2h_html->find('a[data-toggle="tooltip"] span.home img',0)->attr['src'],
			'away_name' => strip_tags($away),
			'away_logo' => $h2h_html->find('a[data-toggle="tooltip"] span.away img',0)->attr['src']
		];
		return $h2h_game;
	}
	public function getAll(){
		$objCurl = new cUrl();
		$respon = $objCurl->custom_curl($this->all_url,false,'cookies-www-fctables-com.txt');
		include_once 'simple_html_dom.php';
		$html = str_get_html($respon);

		$h2h_games 	 = [];
		$all_matches = [];

		foreach( $html->find('.row .col-md-6',0)->find('div.h2h_games') as $h2h_html){
			$all_matches['hot'][] = $this->getMatches($h2h_html);
		}
		foreach( $html->find('.row .col-md-6',1)->find('div.h2h_games') as $h2h_html){
			$all_matches['next'][] = $this->getMatches($h2h_html);
		}
		$all_matches['hot'] = array_values($all_matches['hot']);
		$all_matches['next'] = array_values($all_matches['next']);

		$hots = [];
		foreach( $all_matches['hot'] as $game ){
			$hots[] = $game['link'];
		}

		$h2h_games = array_merge($all_matches['hot'],$all_matches['next']);
		foreach( $h2h_games as $key => $h2h_game ){
			if( in_array( $h2h_game['link'], $hots ) ){
				$h2h_games[$key]['hot'] = 1;
			}
		}

		return $h2h_games;
		
	}

}