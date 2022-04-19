<?php
include_once SYSTEM . 'BaseModel.php';
class SoiKeoModel extends BaseModel
{
    private $_add_time 	= 7*60*60;
    private $_table = 'app_final_soikeo';

    public function __construct(){
        parent::__construct();
        $this->setTable($this->_table);
    }
    /* MODEL */
    public function getAll(){
    	$cols = ['id','source_id','league_id','league_name','league_logo','home_logo','away_logo','game_date','home_team','away_team','link','post_link','game_date_origin'];

        $this->_db->where('game_date_origin',date('Y-m-d H:00:00' ,strtotime('-1 hour') ),'>=');
        $this->_db->orderBy('game_date_origin','ASC');
		$items = $this->_db->ObjectBuilder()->get ($this->_table,null,$cols);

        foreach ($items as $item) {
            $item->body = json_decode($item->body);
			$item->home_logo = $this->save_image_to_folder($item->home_logo);
			$item->away_logo = $this->save_image_to_folder($item->away_logo);
        }
		return $items;	
    }
    public function find($id){
        $this->_db->where ('id', $id);
        $item = $this->_db->ObjectBuilder()->getOne($this->_table);
        $item->body = json_decode($item->body);
        return $item;
    }
    public function findByMatchId($source_id){
        $this->_db->where ('source_id', $source_id);
        $item = $this->_db->ObjectBuilder()->getOne($this->_table);
        $item->body = json_decode($item->body);
		

		$item->body->ranking->home_ranking = str_replace('TOTAL','TỔNG',$item->body->ranking->home_ranking);
		$item->body->ranking->home_ranking = str_replace('HOME','SÂN NHÀ',$item->body->ranking->home_ranking);
		$item->body->ranking->home_ranking = str_replace('AWAY','SÂN KHÁCH',$item->body->ranking->home_ranking);

		$item->body->ranking->guest_ranking = str_replace('TOTAL','TỔNG',$item->body->ranking->guest_ranking);
		$item->body->ranking->guest_ranking = str_replace('HOME','SÂN NHÀ',$item->body->ranking->guest_ranking);
		$item->body->ranking->guest_ranking = str_replace('AWAY','SÂN KHÁCH',$item->body->ranking->guest_ranking);
		
		$item->body->ranking->h2h_table 	= str_replace('League','Giải',$item->body->ranking->h2h_table);
		$item->body->ranking->h2h_table 	= str_replace('Date','Ngày',$item->body->ranking->h2h_table);
		$item->body->ranking->h2h_table 	= str_replace('Home','Chủ nhà',$item->body->ranking->h2h_table);
		$item->body->ranking->h2h_table 	= str_replace('Scores','Tỉ số',$item->body->ranking->h2h_table);
		$item->body->ranking->h2h_table 	= str_replace('Away','Khách',$item->body->ranking->h2h_table);
		
		$item->body->ranking->home_recent_game 	= str_replace('League','Giải',$item->body->ranking->home_recent_game);
		$item->body->ranking->home_recent_game 	= str_replace('Date','Ngày',$item->body->ranking->home_recent_game);
		$item->body->ranking->home_recent_game 	= str_replace('Home','Chủ nhà',$item->body->ranking->home_recent_game);
		$item->body->ranking->home_recent_game 	= str_replace('Scores','Tỉ số',$item->body->ranking->home_recent_game);
		$item->body->ranking->home_recent_game 	= str_replace('Away','Khách',$item->body->ranking->home_recent_game);
		
		$item->body->ranking->guest_recent_game 	= str_replace('League','Giải',$item->body->ranking->guest_recent_game);
		$item->body->ranking->guest_recent_game 	= str_replace('Date','Ngày',$item->body->ranking->guest_recent_game);
		$item->body->ranking->guest_recent_game 	= str_replace('Home','Chủ nhà',$item->body->ranking->guest_recent_game);
		$item->body->ranking->guest_recent_game 	= str_replace('Scores','Tỉ số',$item->body->ranking->guest_recent_game);
		$item->body->ranking->guest_recent_game 	= str_replace('Away','Khách',$item->body->ranking->guest_recent_game);
		
        return $item;
    }
    public function findByLink($link){
        $this->_db->where ('link', $link);
        $item = $this->_db->ObjectBuilder()->getOne($this->_table);
		if(!$item){
			$data = $this->apiGetDetail($link);
			$this->createNewData($data);
			
			$this->_db->where ('link', $link);
			$item = $this->_db->ObjectBuilder()->getOne($this->_table);
		}
        $item->body = json_decode($item->body);
        return $item;
    }
    public function saveAll($items){
    	foreach ($items['items'] as $data) {
            $e = $this->_getItemBySourceId($data['source_id']);
            if( $e ){
                $this->update($e->id,$data);
            }else{
                $this->store($data);
            }
    	}
    }
    public function createNewData($data){
		
		$data['data']['body'] = [
			'predict_1x2' 	=> $data['data']['predict_1x2'],
			'predict_ah' 	=> $data['data']['predict_ah'],
			'predict_over' 	=> $data['data']['predict_over'],
			'ranking' 		=> $data['data']['ranking'],
		];
		
		$data['data']['body'] = json_encode($data['data']['body']);
		
		unset($data['data']['predict_1x2']);
		unset($data['data']['predict_ah']);
		unset($data['data']['predict_over']);
		unset($data['data']['ranking']);
	
		if( $data['id'] ){
			$this->update($data['id'],$data['data']);
		}else{
			$this->store($data['data']);
		}
    	
    }
	public function pushToWeb($id){
		$this->_db->where ('id', $id);
        $item 		= $this->_db->ObjectBuilder()->getOne($this->_table,"id,home_team,game_date_origin,home_logo,away_team,away_logo,league_name,source_id,link");
		
		/*
		$home_team      = $_REQUEST['home_team'];
        $game_date      = $_REQUEST['game_date_origin'];
        $home_logo      = $_REQUEST['home_logo'];
        $away_team      = $_REQUEST['away_team'];
        $away_logo      = $_REQUEST['away_logo'];
        $league_name    = $_REQUEST['league_name'];
        $source_id    	= $_REQUEST['source_id'];	
		*/
		
		$url_query = [
			'link' 		=> $item->link,
			'home_team' => $item->home_team,
			'game_date_origin' => $item->game_date_origin,
			'home_logo' => $item->home_logo,
			'away_team' => $item->away_team,
			'away_logo' => $item->away_logo,
			'league_name' => $item->league_name,
			'source_id' => $item->source_id,
			't' => time(),
		];
		
		$url_query = http_build_query($url_query);
		

		$web_url 	= 'https://vaobo.com/wp-json/vbapi/v1/push_soikeo';
		
		
		$url 		= $web_url.'?'.$url_query;

		$res = $this->get_data($url);
		$res = json_decode($res);

		return $res->post_link;

    }

    private function _getItemBySourceId($source_id){
        $this->_db->where ('source_id', $source_id);
        $item = $this->_db->ObjectBuilder()->getOne($this->_table,"id, source_id");
        return $item;
    }

    /* API */
    public function apiGetAll(){
    	$objLibrary = $this->loadLibrary('SourceSoccerpet');
    	
        $items = $objLibrary->getAll(); 

    	$leagues = [];
        foreach( $items as $key => $item ){
            $objLeague = new stdClass();
            $objLeague->league_id   = $item['league_id'];
            $objLeague->league_name = $item['league_name'];
            $leagues[$objLeague->league_id] = $objLeague;
			
			$items[$key]['game_date_origin'] = date('Y-m-d H:i',strtotime( $items[$key]['game_date'] ));
            $items[$key]['game_date'] = date('d/m H:i',strtotime( $items[$key]['game_date_origin'] ) );			
        }
        $return = [
            'leagues'   => $leagues,
            'items'     => $items,
        ];

        return $return;
    }

    public function apiGetDetail( $link = '' ){
    	if( !$link ){
            $this->_db->where('crawled',0);
            $item    = $this->_db->ObjectBuilder()->getOne($this->_table);
			if(!$item) return false;
            $link   = $item->link;
        }

        

        $objLibrary = $this->loadLibrary('SourceSoccerpet');
        $data = $objLibrary->find($link);
		
		$data['game_date_origin'] = date('Y-m-d H:i',strtotime( $data['game_date'] ));
		$data['game_date'] = date('d/m H:i',strtotime( $data['game_date_origin'] ) );	
        
        $return['data'] = $data;
        $return['id']   = $item->id;

        return $return;
    }
}
