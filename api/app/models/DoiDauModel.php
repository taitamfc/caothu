<?php
include_once SYSTEM . 'BaseModel.php';
class DoiDauModel extends BaseModel
{
    private $_add_time 	= 6*60*60;
    private $_table = 'app_final_doidau';

    public function __construct(){
        parent::__construct();
        $this->setTable($this->_table);
    }
    /* MODEL */
    public function getAll(){
    	$cols = [];
		
		//hot
        $this->_db->where('start_date',date('Y-m-d H:i:s', strtotime('-1 hour')),'>=');
        $this->_db->where('start_date',date('Y-m-d H:i:s', strtotime('+ 1 day')),'<=');
        $this->_db->where('showHome',1);
        $this->_db->where('home_name','','!=');
        $this->_db->where('away_name','','!=');
        $this->_db->where('post_link',NULL,'IS NOT');
		$this->_db->where('hot',1);
        $this->_db->orderBy('start_date','ASC');
		$hot_items = $this->_db->ObjectBuilder()->get ($this->_table,null,$cols);
		
		//regular
        $this->_db->where('start_date',date('Y-m-d H:i:s', strtotime('-1 hour')),'>=');
		$this->_db->where('start_date',date('Y-m-d H:i:s', strtotime('+ 1 day')),'<=');
        $this->_db->where('showHome',1);
        $this->_db->where('home_name','','!=');
        $this->_db->where('away_name','','!=');
        $this->_db->where('post_link',NULL,'IS NOT');
		$this->_db->where('hot',0);
        $this->_db->orderBy('start_date','ASC');
		$regular_items = $this->_db->ObjectBuilder()->get ($this->_table,null,$cols);
		
		
		$items = array_merge($hot_items,$regular_items);
		
        foreach ($items as $item) {
            $item->home_name 	= trim($item->home_name);
            $item->away_name 	= trim($item->away_name);
            $item->h2h_games 	= json_decode($item->h2h_games);
            $item->recent_games = json_decode($item->recent_games);
            $item->team_stats 	= json_decode($item->team_stats);
            $item->start_d 		= date('d/m',strtotime($item->start_date));
            $item->start_t 		= date('H:i',strtotime($item->start_date));
			
			$item->home_logo = str_replace('18x18','70x70',$item->home_logo);
			$item->away_logo = str_replace('18x18','70x70',$item->away_logo);
			$item->home_logo = $this->save_image_to_folder($item->home_logo);
			$item->away_logo = $this->save_image_to_folder($item->away_logo);
        }
		
		//regular
		
		return $items;	
    }
	public function findByLink($link){
        $this->_db->where ('link', $link);
		
		
		
        $item = $this->_db->ObjectBuilder()->getOne($this->_table);
		
		if( !$item ){
			$data = $this->apiGetDetail( $link );
			
			$data['data']['link'] = $link;
		
			$this->store($data['data']);
			
			$this->_db->where ('link', $link);
			$item = $this->_db->ObjectBuilder()->getOne($this->_table);
		}else{
			/*re-update*/
			$re_update = false;
			if(!$item->updated_at){
				$re_update = true;
			}
			if( $item->updated_at ){
				if( date('Y-m-d',strtotime($item->updated_at)) < date('Y-m-d') ){
					$re_update = true;
				}
			}
			if($re_update){
				$data = $this->apiGetDetail( $link );
				$update = $data['data'];
				$update['updated_at'] = date('Y-m-d H:i:s');
				$this->update($item->id,$update);
			}
			
			/*re-update*/
		}
		
		
		$item->home_name 	= trim($item->home_name);
        $item->away_name 	= trim($item->away_name);
        
		$item->h2h_games 	= json_decode($item->h2h_games);
		$item->recent_games = json_decode($item->recent_games);
		$item->team_stats 	= json_decode($item->team_stats);
		$item->start_d 		= date('d/m',strtotime($item->start_date));
		$item->start_t 		= date('H:s',strtotime($item->start_date));
		
		foreach( $obj->h2h_games as $key => $h2h_games ){
			foreach($h2h_games as $h2h_key => $h2h_game){
				if($h2h_key == 'game_date'){
					$obj->h2h_games[$key]->$h2h_key = date('d/m/Y',strtotime($h2h_game));
				}
				if($h2h_key == 'game_date'){
					$obj->h2h_games[$key]->$h2h_key = date('d/m/Y',strtotime($h2h_game));
				}
				
			}
		}

		// foreach( $obj->recent_games[0] as $key => $h2h_games ){
			// foreach($h2h_games as $h2h_key => $h2h_game){
				// if($h2h_key == 'game_date'){
					// $obj->recent_games[0][$key]->$h2h_key = date('d/m/Y',strtotime($h2h_game));
				// }
				
			// }
		// }

		// foreach( $obj->recent_games[1] as $key => $h2h_games ){
			// foreach($h2h_games as $h2h_key => $h2h_game){
				// if($h2h_key == 'game_date'){
					// $obj->recent_games[1][$key]->$h2h_key = date('d/m/Y',strtotime($h2h_game));
				// }
				
			// }
		// }
		
		
		foreach( $item->h2h_games as $k => $h2h_game ){
			$item->h2h_games[$k]->game_date 	= date('d/m/Y',strtotime($h2h_game->game_date));
		}
		
		$recent_links = [];
		foreach( $item->recent_games as $k => $recent_game_arr ){
			foreach( $recent_game_arr as $j => $recent_game ){
				$item->recent_games[$k][$j]->game_date 	= date('d/m/Y',strtotime($recent_game->game_date));
				$item->recent_games[$k][$j]->post_link 	= $this->get_post_link_by_link($recent_game->link);
				$recent_links[$k][$j] = $recent_game->link;
			}
		}
		
		// if( count( $recent_links ) ){
			// $this->_db->where('post_link',NULL,'IS NOT');
			// $this->_db->orderBy('start_date','ASC');
			// $items = $this->_db->ObjectBuilder()->get ($this->_table,null,$cols);
		// }
		

		
		$item->home_logo = str_replace('18x18','70x70',$item->home_logo);
        $item->away_logo = str_replace('18x18','70x70',$item->away_logo);
		$item->home_logo = $this->save_image_to_folder($item->home_logo);
		$item->away_logo = $this->save_image_to_folder($item->away_logo);
		$item->away_logo = $this->save_image_to_folder($item->away_logo);
			
        return $item;
    }
	public function get_post_link_by_link($link){
		$this->_db->where ('link', $link);
		$item = $this->_db->ObjectBuilder()->getOne($this->_table, "post_link");
		return @$item->post_link;
	}
	public function findById($id){
        $this->_db->where ('id', $id);

        $item = $this->_db->ObjectBuilder()->getOne($this->_table);
		/*re-update*/
		$re_update = false;
		if(!$item->updated_at){
			$re_update = true;
		}
		if( $item->updated_at ){
			if( date('Y-m-d',strtotime($item->updated_at)) < date('Y-m-d') ){
				$re_update = true;
			}
		}
		if($re_update){
			$data = $this->apiGetDetail( $link );
			$update = $data['data'];
			$update['updated_at'] = date('Y-m-d H:i:s');
			$this->update($item->id,$update);
		}
		
		/*re-update*/
		
		
		$item->home_name 	= trim($item->home_name);
        $item->away_name 	= trim($item->away_name);
		$item->h2h_games 	= json_decode($item->h2h_games);
		$item->recent_games = json_decode($item->recent_games);
		$item->team_stats 	= json_decode($item->team_stats);
		$item->start_d 		= date('d/m',strtotime($item->start_date));
		$item->start_t 		= date('H:s',strtotime($item->start_date));
		
		$item->home_logo = str_replace('18x18','70x70',$item->home_logo);
        $item->away_logo = str_replace('18x18','70x70',$item->away_logo);
		$item->home_logo = $this->save_image_to_folder($item->home_logo);
		$item->away_logo = $this->save_image_to_folder($item->away_logo);
			
        return $item;
    }
	
	public function pushToWeb($id){
		$this->_db->where ('id', $id);
        $item 		= $this->_db->ObjectBuilder()->getOne($this->_table,"link");
		

		$url_query = [
			'link' 		=> $item->link,
			'id' 		=> $id,
			't' 		=> time(),
		];
		
		$url_query = http_build_query($url_query);
		
		$web_url 	= 'https://vaobo.com/wp-json/vbapi/v1/push_doidau';
		
		
		$url 		= $web_url.'?'.$url_query;

		$res = $this->get_data($url);
		
		
		$res = json_decode($res);
	
		return $res->post_link;

    }
	
	public function resetNotStarted(){
		$cols = ['id'];
        $this->_db->where('trang_thai','Not started');
        $this->_db->where('crawled',1);
		$items = $this->_db->ObjectBuilder()->get ($this->_table,null,$cols);
		foreach( $items as $item ){
			$id = $item->id;
			$data = [
				'crawled' => 0
			];
			//$this->update($id,$data);
		}
	}

    public function saveAll($items){
    	foreach ($items as $data) {            
            $data['start_date'] = date('Y-m-d H:i:s',$data['start_date']);
            $e = $this->_getItemByLink($data['link']);
            if( $e ){
                $this->update($e->id,$data);
            }else{
                $this->store($data);
            }
    	}
    }

    private function _getItemByLink($link){
        $this->_db->where ('link', $link);
        $item = $this->_db->ObjectBuilder()->getOne($this->_table,"id, link");
        return $item;
    }

    /* API */
    public function apiGetAll(){
    	$objLibrary = $this->loadLibrary('SourceFcTables');
    	
        $items = $objLibrary->getAll();     

        return $items;
    }

    public function crawRecentGames( $link = '' ){
		$this->_db->where('importRecentGames',0);
		$item    = $this->_db->ObjectBuilder()->getOne($this->_table);
		if(!$item) return false;
		$link   = $item->link;
		$this->apiGetDetail( $link);
	}
    public function apiGetDetail( $link = '' ){
    	if( !$link ){
			//ưu tiên lấy trận hôm nay trước
			$this->_db->where('start_date',date('Y-m-d 00:00:00'),'>=');
            $this->_db->where('crawled',0);
            $this->_db->where('skip',0);

            $item    = $this->_db->ObjectBuilder()->getOne($this->_table);

			//nếu xong việc thì đi thu thập dữ liệu tiếp đi ku
			if(!$item){
				$this->_db->where('crawled',0);
				$this->_db->where('skip',0);
				$item    = $this->_db->ObjectBuilder()->getOne($this->_table);
			}
			
			if(!$item) return false;
            $link   = $item->link;
        }

        $objLibrary = $this->loadLibrary('SourceFcTables');
        $data = $objLibrary->find($link);
	
		$data['importRecentGames'] = $this->importRecentGames($item->id,$data['recent_games']);
        $data['start_date'] = date('Y-m-d H:i:s',$data['start_date']);
		
		$data['skip'] = ( $data['home_name'] ) ? 0 : 1; 
		

        $return['data'] = $data;
        $return['id']   = $item->id;

        return $return;
    }
	
	public function importRecentGames($referTo,$recent_games){
		$recent_games = json_decode($recent_games);
		if( !count($recent_games) ) return true;
		
		$recent_games_arr = $recent_games;
		
		
		
		foreach( $recent_games_arr as $recent_games){
		
			foreach( $recent_games as $recent_game){
				$this->_db->where('link',$recent_game->link);
				$item    = $this->_db->ObjectBuilder()->getOne($this->_table,"id");
				
				if( !$item ){
					$data_import = [
						'home_name' 	=> $recent_game->home,
						'away_name' 	=> $recent_game->away,
						'ti_so' 		=> $recent_game->home_result.' - '.$recent_game->away_result,
						'start_date' 	=> date('Y-m-d H:i:s',strtotime($recent_game->game_date)),
						'link' 			=> $recent_game->link,
						'crawled' 		=> 0,
						'referTo' 		=> $referTo,
					];
					if( $data_import['home_name'] && $data_import['away_name']){
						$this->store($data_import);
					}
					
				}
			}
		}
		
		return 1;
		
	}
}
