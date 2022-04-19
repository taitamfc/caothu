<?php
class SourceSoccertips {
	public $predictions_url  = 'https://soccertips.net/wp-admin/admin-ajax.php';
	public $start 			 = 0;

	public function find($link){
		
		$objCurl = new cUrl();
		$respon = $objCurl->custom_curl('https://soccerpet.com'.$link,false,'cookies-soccerpet-com.txt');
		if(!$respon) return [];

		include_once 'simple_html_dom.php';
		$html 		= str_get_html($respon);

		$es 		= $html->find('div.content-wrap',0);
		$content 	= $es->innertext;

		$data['content'] = $content;
		return $data;
		
	}
	public function getAll($start){
		$objCurl = new cUrl();

		$respon = $objCurl->custom_curl('https://soccertips.net/free-soccer-tips/',false,'cookies-soccertips-net.txt');

		preg_match('/"nonce":"([^"]*)",/', $respon, $matches);

		$sct_nonce 	= $matches[1];
		$page 		= $start;

		$data = [
			'action' 	=> "pagination",
			'type' 		=> "free",
			'p' 		=> $page,
			'limit' 	=> "20",
			'hide' 		=> "tipster,share,source_tips_rating,tips_status",
			'template' 	=> "table",
			'sct_nonce' => $sct_nonce
		];

		$respon = $objCurl->custom_curl_2($page);


		if(!$respon) return [];

		include_once 'simple_html_dom.php';
		$html 		= str_get_html($respon);

		if(!$html) return [];

		$lis = $html->find ('tr');

		$data = [];
		foreach ($lis as $li) {
			$date 	= $li->find('span.date',0)->innertext;
			$time 	= $li->find('span.time',0)->innertext;
			$league = $li->find('div.league',0)->innertext;
			$home 	= $li->find('span.home',0)->innertext;
			$away 	= $li->find('span.away',0)->innertext;
			$tips 	= $li->find('div.tips',0)->innertext;
			$odds_in_tips 	= $li->find('div.odds_in_tips',0)->innertext;
			$tipster 		= $li->find('div.tipster_in_tips a',0)->innertext;
			$tipster_link 	= $li->find('div.tipster_in_tips a',0)->attr['href'];
			$odds 	= $li->find('span.odds',0)->innertext;
			$home_result 	= $li->find('span.home_result',0)->innertext;
			$away_result 	= $li->find('span.away_result',0)->innertext;
			$source_tips 	= $li->find('td#source_tips a',0)->innertext;
			$source_tips_link = $li->find('td#source_tips a',0)->attr['href'];

			$the_date = $date.'/'.date('Y');
			$the_date = str_replace('/','-',$the_date);
			$the_date = date('Y-m-d',strtotime( $the_date) );

			$data[] = [
				'game_date' 	=> $date,
				'game_time' 	=> $time,
				'game_start' 	=> date('Y-m-d h:i:s',strtotime( $the_date.' '.$time )),
				'league' 		=> $league,
				'home' 			=> $home,
				'away' 			=> $away,
				'tips' 			=> $tips,
				'odds_in_tips' 	=> $odds_in_tips,
				'tipster_link' 	=> $tipster_link,
				'odds' 			=> $odds,
				'home_result' 	=> $home_result,
				'away_result' 	=> $away_result,
				'source_tips' 	=> strip_tags($source_tips),
				'source_tips_link' 	=> $source_tips_link,
				'source_id' 	=> md5($home.'-'.$away)
			];
		}

		return $data;
	}

}