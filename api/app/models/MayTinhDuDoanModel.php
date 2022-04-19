<?php
include_once SYSTEM . 'BaseModel.php';
class MayTinhDuDoanModel extends BaseModel
{
    private $_add_time 	= 7*60*60;
    private $_table = 'app_final_maytindudoan';
	
    public function __construct(){
        parent::__construct();
        $this->setTable($this->_table);
    }
    /* MODEL */
    public function getAll(){
    	$cols = [];
        //$this->_db->where('hw_odd','-','!=');
        $this->_db->where('game_date',date('Y-m-d H:i:s'),'>=');
        $this->_db->orderBy('game_date','ASC');
		$items = $this->_db->ObjectBuilder()->get ($this->_table,null,$cols);
        foreach ($items as $item) {
            $item->cards        = json_decode($item->cards);            
            $item->game_date_d  = date('d/m',strtotime($item->game_date) );            
            $item->game_date_f  = date('Y-m-d',strtotime($item->game_date) );            
            $item->game_date_h  = date('H:i',strtotime($item->game_date) );
			$item->hw_odd = ( $item->hw_odd == '-' ) ? 0 : $item->hw_odd;
			$item->dr_odd = ( $item->dr_odd == '-' ) ? 0 : $item->dr_odd;
			$item->aw_odd = ( $item->aw_odd == '-' ) ? 0 : $item->aw_odd;
			$item->o2_odd = ( $item->o2_odd == '-' ) ? 0 : $item->o2_odd;
			$item->u2_odd = ( $item->u2_odd == '-' ) ? 0 : $item->u2_odd;
			$item->by_odd = ( $item->by_odd == '-' ) ? 0 : $item->by_odd;
			$item->bn_odd = ( $item->bn_odd == '-' ) ? 0 : $item->bn_odd;
        }
		return $items;	
    }
    public function findByMatchId($match_id){
    	$this->_db->where ('system_id', $match_id);
        $item = $this->_db->ObjectBuilder()->getOne($this->_table);
		$item->cards        = json_decode($item->cards);
		$semilars     = $this->findSemilarsByMatchId($item);
		$item->cards->cards_2_0 = $semilars[0];
		$item->cards->cards_2_1 = $semilars[1];
		$item->cards->cards_2_2 = $semilars[2];
		$item->cards->cards_2_3 = $semilars[3];
		
        return $item;
    }
    public function findSemilarsByMatchId($item){
		
    	$this->_db->where ('system_id', $item->system_id,'!=');
    	$this->_db->where ('league_id', $item->league_id);
    	$this->_db->where ('post_link', '','!=');
		//$this->_db->where('game_date',date('Y-m-d 00:00:00'),'>=');		
		$this->_db->orderBy("game_date",'DESC');
		$string_cols 	= 'post_link,aw_odd as away_odds,away_team,aw_prc as away_win,dr_odd as draw_odds,dr_prc as draw_win,hw_odd as home_odds,home_team,hw_prc as home_win';
        $cols 			= explode(',',$string_cols);
		$items = $this->_db->ObjectBuilder()->get ($this->_table,4,$cols);
        return $items;
    }
    public function saveAll($items){

    	foreach ($items as $data) {

            $data = (array)$data;
            $data['system_id'] = $data['id'];
            $data['game_date'] = $this->_converZTTime($data['game_date']);
            $e = $this->_getItemBySourceId($data['id']);

            unset($data['id']);
            if( $e ){
                $this->update($e->id,$data);
            }else{
                $this->store($data);
            }
    	}
    }
    public function resetNotStarted(){
		
		$this->_db->where('crawled',0);
		$check   = $this->_db->ObjectBuilder()->getOne($this->_table,"id");
		
		if( !$check ){
			$cols = ['id'];
			$this->_db->where('game_date',date('Y-m-d 00:00:00'),'>=');
			$this->_db->where('m_status','NOT STARTED');
			$this->_db->where('crawled',1);
			$items = $this->_db->ObjectBuilder()->get ($this->_table,null,$cols);
			foreach( $items as $item ){
				$id = $item->id;
				$data = [
					'crawled' => 0
				];
				$this->update($id,$data);
			}
		}
		
	}
    public function pushToWeb($id){
		$this->_db->where ('id', $id);
        $item 		= $this->_db->ObjectBuilder()->getOne($this->_table,"id,system_id,home_team,away_team");
		$web_url 	= 'https://vaobo.com/wp-json/vbapi/v1/push_maytinhdudoan';
		$url 		= $web_url.'?match_id='.$item->system_id.'&home_team='.urlencode($item->home_team).'&away_team='.urlencode($item->away_team);
		
		$res = $this->get_data($url);
		$res = json_decode($res);

		return $res->post_link;
	}

    private function _converZTTime($time){
        //$time = str_replace('T',' ',$time);
        //$time = str_replace('.000Z','',$time);
        $time = date_create($time);
        $time = date_format($time, 'Y-m-d H:i:s');
        $time = date('Y-m-d H:i:s', strtotime($time) + $this->_add_time);
        
        return $time;
    }

    private function _getItemBySourceId($id){
        $this->_db->where ('system_id', $id);
        $item = $this->_db->ObjectBuilder()->getOne($this->_table,"id, system_id");
        return $item;
    }

    /* API */
    public function apiGetAll(){
		$objLibrary = $this->loadLibrary('SourceAskbettor');
    	$items      = $objLibrary->getAll();
        return $items;
    }

    public function apiGetDetail( $link = '' ){
    	if( !$link ){
			/*pls remove after run done*/
			//$this->_db->where('game_date',date('Y-m-d 00:00:00'),'>=');
            $this->_db->where('crawled',0);
            //$this->_db->where('hw_odd','-','!=');
            $this->_db->orderby('id','DESC');
            $item   = $this->_db->ObjectBuilder()->getOne($this->_table);
			if(!$item) return false;
            $link   = $item->l_alias.'/'.$item->ht_alias.'_vs_'.$item->at_alias;
        }

        

        $objLibrary = $this->loadLibrary('SourceAskbettor');
        $data       = $objLibrary->find($link);

        $data->semilars 	= $objLibrary->get_filter_semilars($data->match_id);
		

		$system_id 	= $data->match_id;
		
		


        $cards = [];
        
        foreach( $data->cards as $i => $data_cards ){
            foreach( $data_cards->cards as $j => $data_card ){
                //$cards['cards_'.$i.'_'.$j] = $data_card->id;
                $cards[$data_card->type][] = $data_card->id;
            }
        }
		
		foreach( $cards as $card_type => $card_ids ){
			
			if( count($card_ids) > 1 ){
				$cards[$card_type.'_0'] = $card_ids[0];
				$cards[$card_type.'_1'] = $card_ids[1];
				unset($cards[$card_type]);
			}else{
				$cards[$card_type] = current($card_ids);
			}
		}
		
		
		

        foreach( $data->semilars->cards as $k => $semilar ){
            $cards['semilar_'.$k] = $semilar->id;
        }
		
		$ncards = [];
		foreach( $cards as $card_type => $card_id ){
			$card_type = str_replace('-','_',$card_type);
			$ncards[$card_type] = $card_id;
		}

		$cards = [
			'cards_0_0' => $ncards['main_prediction'],
			'cards_0_1' => $ncards['correct_score'],
			'cards_0_2' => $ncards['algorithm'],
			'cards_0_3' => $ncards['handicap'],
			'cards_0_4' => $ncards['btts'],
			'cards_0_5' => $ncards['over_under15'],
			'cards_0_6' => $ncards['over_under25'],
			'cards_0_7' => $ncards['over_under35'],
			'cards_1_0' => isset($ncards['leaguetablepoint']) ? $ncards['leaguetablepoint'] : 0,
			'cards_1_1' => $ncards['compare_form'],
			'cards_1_2' => $ncards['previous_games_0'],
			'cards_1_3' => $ncards['previous_games_1'],
			'cards_1_4' => $ncards['head_to_head'],
			'cards_1_5' => $ncards['compare_results'],
			'cards_1_6' => $ncards['average_goals'],
			'cards_2_0' => $ncards['semilar_0'],
			'cards_2_1' => $ncards['semilar_1'],
			'cards_2_2' => $ncards['semilar_2'],
			'cards_2_3' => $ncards['semilar_3'],
		];

		/*
		this.cards_0_0 = the_response.cards.cards_0_0;
		this.cards_0_1 = the_response.cards.cards_0_1;
		this.cards_0_2 = the_response.cards.cards_0_2;
		this.cards_0_3 = the_response.cards.cards_0_3;
		this.cards_0_4 = the_response.cards.cards_0_4;
		this.cards_0_5 = the_response.cards.cards_0_5;
		this.cards_0_6 = the_response.cards.cards_0_6;
		this.cards_0_7 = the_response.cards.cards_0_7;
		this.cards_1_0 = the_response.cards.cards_1_0;
		this.cards_1_1 = the_response.cards.cards_1_1;
		this.cards_1_2 = the_response.cards.cards_1_2;
		this.cards_1_3 = the_response.cards.cards_1_3;
		this.cards_1_4 = the_response.cards.cards_1_4;
		this.cards_1_5 = the_response.cards.cards_1_5;
		this.cards_1_6 = the_response.cards.cards_1_6;
		this.cards_2_0 = the_response.cards.cards_2_0;
		this.cards_2_1 = the_response.cards.cards_2_1;
		this.cards_2_2 = the_response.cards.cards_2_2;
		this.cards_2_3 = the_response.cards.cards_2_3;
		*/
		

        //remove info
        $data->games_links  = [];
        $data->semilars     = [];
        $data->cards        = [];
		
		

        foreach( $cards as $k_c => $card_id ){
			if( $k_c == 'cards_1_0' && $card_id){
				$data->cards[$k_c] = $objLibrary->get_card_league_table($card_id);
			}else{
				$data->cards[$k_c] = $objLibrary->get_card_detail($card_id);
			}
            
        }
		

        //$return['game_date'] 	= date('Y-m-d',strtotime($data->game_date));
        $return['league_name'] 	= $data->league_info->name;
		

        $return['game'] 		= strtolower($data->name);
        $return['m_status'] 	= $data->m_status;
        $return['cards'] 		= $data->cards;
        $return['id']   		= ($item->id) ? $item->id : $this->_getItemBySourceId($system_id)->id;

        return $return;
    }
}
