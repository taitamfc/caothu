<?php
// Include base controller to support basic framework fuctions
include SYSTEM . 'BaseController.php';

/**
* Default Controller
*/
class CronController extends BaseController
{
	private $_table_odds = 'odds';
	private $_table_matches = 'matches';
	private $_table_handicaps = 'handicaps';
	private $_table_europeodds = 'europeodds';
	private $_table_overunders = 'overunders';
	private $_table_handicap_halfs = 'handicap_halfs';
	private $_table_overunder_halfs = 'overunder_halfs';

    public function index()
    {
      	$this->resJson($res,1);
    }
	public function matches()
    {
		global $AppDB;
      	$date   = ( isset($_GET['date']) && $_GET['date'] !='' ) ? $_GET['date'] : date('Y-m-d',strtotime('+3days'));

		//lich thi dau
		$schedule_url   = API_URL."/sport/football/schedule/basic?api_key=".API_KEY.'&date='.$date;
		$json_data      = file_get_contents($schedule_url);
		$all_schedules      = json_decode($json_data)->data;
		usort($all_schedules, function($a, $b) {
			if($a->matchTime == $b->matchTime) return 0;
			return $a->matchTime > $b->matchTime ? 1 : -1;
		});
		foreach ($all_schedules as $key => $value) {
			$matchIds[] = $value->matchId;
			$matches[$value->matchId] = $value;
		}
		
		//delete same matchIds
		$AppDB->where('matchId', $matchIds,'IN' );
		$AppDB->delete($this->_table_matches);
		
		//insert $matches
		foreach($matches as $match){
			$match = (array)$match;
			unset($match['extraExplain']);
			unset($match['explain']);
			unset($match['neutral']);
			
			$AppDB->insert($this->_table_matches, $match);
		}
		$res['msg'] = 'OK';
      	$this->resJson($res,1);
		
    }

	public function getOdds()
    {
		global $AppDB;
        $AppDB->where('status',0,'>=' );
		$matches = $AppDB->get($this->_table_matches,null,['matchId']);
		$matchIds_arr = [];
		if( count($matches) ){
			$matchIds = [];
			foreach( $matches as $match){
				$matchIds[] = $match['matchId'];
			}
			$matchIds_arr = array_chunk($matchIds,500);
		}
		if( count($matchIds_arr) ){
			foreach( $matchIds_arr as $matchIds ){
				$this->getOddsByMatchIds($matchIds);
			}
		}
		
		$res['msg'] = __METHOD__;
      	$this->resJson($res,1);
    }

	private function getOddsByMatchIds($matchIds){
		$matchIds = implode(',',$matchIds);
		$odds_url = API_URL."/sport/football/odds/main?api_key=".API_KEY.'&matchId='.$matchIds;
		$json_data = file_get_contents($odds_url);
		$json_data = json_decode($json_data);
		
		$matches_odds = [];

		foreach ($json_data->data as $odd_type => $odd_items) {
			foreach ($odd_items as $odd_item) {
				$matchId = current( explode(',',$odd_item) );
				$orr_arr = explode(',',$odd_item);
				switch($odd_type){
					case 'handicap':
						$odd_type_arr = [
							'matchId' 			=> $orr_arr[0],
							'companyId' 		=> $orr_arr[1],
							'initialHandicap' 	=> $orr_arr[2],
							'initialHome' 		=> $orr_arr[3],
							'initialAway' 		=> $orr_arr[4],
							'instantHandicap' 	=> $orr_arr[5],
							'instantHome' 		=> $orr_arr[6],
							'instantAway' 		=> $orr_arr[7],
							'maintenance' 		=> $orr_arr[8],
							'inPlay' 			=> $orr_arr[9],
							'changeTime' 		=> $orr_arr[10],
							'close' 			=> $orr_arr[11],
							'odd_type' 			=> $orr_arr[12],
						];
						break;
					case 'europeOdds':
						$odd_type_arr = [
							'matchId' 			=> $orr_arr[0],
							'companyId' 		=> $orr_arr[1],
							'initialHome' 		=> $orr_arr[2],
							'initialDraw' 		=> $orr_arr[3],
							'initialAway' 		=> $orr_arr[4],
							'instantHome' 		=> $orr_arr[5],
							'instantDraw' 		=> $orr_arr[6],
							'instantAway' 		=> $orr_arr[7],
							'changeTime' 		=> $orr_arr[8],
							'close' 			=> $orr_arr[9],
							'odd_type' 			=> $orr_arr[10]
						];
						break;
					case 'overUnder':
						$odd_type_arr = [
							'matchId' 			=> $orr_arr[0],
							'companyId' 		=> $orr_arr[1],
							'initialHandicap' 	=> $orr_arr[2],
							'initialOver' 		=> $orr_arr[3],
							'initialUnder' 		=> $orr_arr[4],
							'instantHandicap' 	=> $orr_arr[5],
							'instantOver' 		=> $orr_arr[6],
							'instantUnder' 		=> $orr_arr[7],
							'changeTime' 		=> $orr_arr[8],
							'close' 			=> $orr_arr[9],
							'odd_type' 			=> $orr_arr[10]
						];
						break;
					case 'handicapHalf':
						$odd_type_arr = [
							'matchId' 			=> $orr_arr[0],
							'companyId' 		=> $orr_arr[1],
							'initialHandicap' 	=> $orr_arr[2],
							'initialHome' 		=> $orr_arr[3],
							'initialAway' 		=> $orr_arr[4],
							'instantHandicap' 	=> $orr_arr[5],
							'instantHome' 		=> $orr_arr[6],
							'instantAway' 		=> $orr_arr[7],
							'changeTime' 		=> $orr_arr[8],
							'odd_type' 			=> $orr_arr[9],
						];
						break;
					case 'overUnderHalf':
						$odd_type_arr = [
							'matchId' 			=> $orr_arr[0],
							'companyId' 		=> $orr_arr[1],
							'initialHandicap' 	=> $orr_arr[2],
							'initialOver' 		=> $orr_arr[3],
							'initialUnder' 		=> $orr_arr[4],
							'instantHandicap' 	=> $orr_arr[5],
							'instantOver' 		=> $orr_arr[6],
							'instantUnder' 		=> $orr_arr[7],
							'changeTime' 		=> $orr_arr[8],
							'odd_type' 			=> $orr_arr[9]
						];
						break;
				}
				$matches_odds[$odd_type][$matchId][] = $odd_type_arr;
			}
		}
		if( count($matches_odds) ){
			if( isset($matches_odds['handicap']) ){
				$this->handleData($matches_odds['handicap'],$this->_table_handicaps);
			}
			if( isset($matches_odds['europeOdds']) ){
				$this->handleData($matches_odds['europeOdds'],$this->_table_europeodds);
			}
			if( isset($matches_odds['overUnder']) ){
				$this->handleData($matches_odds['overUnder'],$this->_table_overunders);
			}
			if( isset($matches_odds['handicapHalf']) ){
				$this->handleData($matches_odds['handicapHalf'],$this->_table_handicap_halfs);
			}
			if( isset($matches_odds['overUnderHalf']) ){
				$this->handleData($matches_odds['overUnderHalf'],$this->_table_overunder_halfs);
			}
		}
	}

	private function handleData($items,$table){
		global $AppDB;
		//pre insert items
		$compares = [];
		foreach( $items as $matchId => $orr_arr ){
			foreach( $orr_arr as $odd_type_arr ){
				$compares[] = $odd_type_arr['matchId'].'_'.$odd_type_arr['companyId'].'_'.$odd_type_arr['changeTime'];
			}
		}

		//remove duplicate items
		if( count($compares) ){
			$AppDB->where('compare', $compares,'IN' );
			$AppDB->delete($table);
		}

		//insert new items
		foreach( $items as $matchId => $orr_arr ){
			foreach( $orr_arr as $odd_type_arr ){
				$odd_type_arr['compare'] = $odd_type_arr['matchId'].'_'.$odd_type_arr['companyId'].'_'.$odd_type_arr['changeTime'];
				$AppDB->insert($table, $odd_type_arr);
			}
		}
	}

}
