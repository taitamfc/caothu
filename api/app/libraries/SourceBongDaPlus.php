<?php
class SourceBongDaPlus {
	public $identify_url 	= 'https://bongdaplus.vn/tags/nhan-dinh-bong-da';
	public $fixture_url 	= 'https://bongdaplus.vn/tags/nhan-dinh-bong-da';
	public $bet_url 		= 'https://bongdaplus.vn/soi-keo-bong-da/';
	public $start 			= 0;
	public function find($link){
		
		$objCurl = new cUrl();
		$respon = $objCurl->get($link);

				include_once 'simple_html_dom.php';

		$html = str_get_html($respon);
		$es = $html->find('div[id="postContent"]', 0);
		$content = $es->innertext;

		$data['content'] = trim($content);
		return $data;

	}
	public function findRank($link){

	
		$objCurl = new cUrl();
		$respon = $objCurl->get($link);
		if(!$respon){
			return [];
		}
		include_once 'simple_html_dom.php';
		$html 		= str_get_html($respon);
		if(!$html){
			return [];
		}
		
		$es 		= $html->find('div[class="fixtbl rank1"]',0);
		$content 	= $es->innertext;

		if(!$content){
			return [];
		}

		$html 		= str_get_html($content);
		$fcols 		= $html->find('div.fcol');

		$cols = [];
		foreach ($fcols as $fcol) {
			$cols[] = $fcol->innertext;
		}

		$cols = array_chunk($cols,11);

		unset($cols[0]);

		$data = [];
		foreach ($cols as $key => $col) {
			$data[$key] = [
				'name' 		 => strip_tags($col[1]),
				'stt' 		 => $col[0],
				'tran' 		 => $col[2],
				'thang' 	 => $col[3],
				'hoa' 	 	 => $col[4],
				'thua' 	 	 => $col[5],
				'ban_thang'  => $col[6],
				'ban_thua'   => $col[7],
				'ti_le'   	 => $col[8],
				'diem'   	 => $col[9],
				'recent_records'  => strip_tags($col[10]),
			];
		}

		return $data;

	}
	public function getAll(){
		$objCurl = new cUrl();
		$respon = $objCurl->get($this->identify_url.'/'.$this->start);
		

		preg_match_all('/class="title" href="([^"]*)"/', $respon, $links);
		preg_match_all('/alt="([^"]*)"/', $respon, $titles);
		preg_match_all('/src="([^"]*)"/', $respon, $images);


		$links 	= $links[1];
		$titles = $titles[1];
		$images = $images[1];

		$data = [];
		foreach ($links as $key => $link) {
		 	$source_id          = end( explode('-', $link) );
        	$source_id          = str_replace('.html','',$source_id);

			$data[$key] = [
				'name' 		=> $titles[$key],
				'link' 		=> 'https://bongdaplus.vn'.$links[$key],
				'image' 	=> $images[$key],
				'source' 	=> 'bongdaplus.vn',
				'source_id' => $source_id,
			];
		}
		return $data;
	}

	public function getAllFixtures(){
		$objCurl = new cUrl();
		$respon = $objCurl->get($this->fixture_url.'/'.$this->start);
		

		preg_match_all('/class="title" href="([^"]*)"/', $respon, $links);
		preg_match_all('/alt="([^"]*)"/', $respon, $titles);
		preg_match_all('/src="([^"]*)"/', $respon, $images);


		$links 	= $links[1];
		$titles = $titles[1];
		$images = $images[1];

		$data = [];
		foreach ($links as $key => $link) {
			$data[$key] = [
				'name' 		=> $titles[$key],
				'link' 		=> 'https://bongdaplus.vn'.$links[$key],
				'image' 	=> $images[$key],
				'source' 	=> 'bongdaplus.vn',
			];
		}
		return $data;
	}

	public function getAllBets(){
		$objCurl = new cUrl();
		$respon = $objCurl->get($this->bet_url);


		include_once 'simple_html_dom.php';

		$html = str_get_html($respon);
		$es = $html->find('div[class="newslst"]', 0);
		$content = $es->innertext;

		$html = str_get_html($content);

		$html_links = $html->find('a');
		$links = [];
		foreach ($html_links as $link) 
		{
		    $links[] = $link->href;
		}

		$html_titles = $html->find ('h2[class="title"]');
		$titles = [];
		foreach ($html_titles as $html_title) 
		{
		    $titles[] = $html_title->innertext;
		}

		$html_images = $html->find ('img[class="lzl"]');
		$images = [];
		foreach ($html_images as $html_image) 
		{
		    $images[] = $html_image->attr['data-src'];
		}

		$data = [];
		foreach ($links as $key => $link) {
			$data[$key] = [
				'name' 		=> $titles[$key],
				'link' 		=> 'https://bongdaplus.vn'.$links[$key],
				'image' 	=> $images[$key],
				'source' 	=> 'bongdaplus.vn',
			];
		}

		return $data;
	}
}