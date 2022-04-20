<?php
// Include base controller to support basic framework fuctions
include SYSTEM . 'BaseController.php';

/**
* Default Controller
*/
class OddsController extends BaseController
{
	public function __construct(){
		$this->companyId = 24;
	}
	public function european_half(){
		/*
		European Odds (Halftime) 
		Path: /sport/football/odds/european/half
		Method: GET
		Calls: Unlimited calls for paid plans, 200 calls per day for free trial.
		Recommend Calls: 15 second/call
		*/
		$companyId = $this->companyId;
		// $companyId = 8;
		
		$matchIds   = ( isset($_GET['matchIds']) ) ? $_GET['matchIds'] : '';
		
		if(!$matchIds){
			$res['status'] = 0;
			$this->resJson($res,1);
		}
		
		$odds_url = API_URL."/sport/football/odds/european/half?api_key=".API_KEY.'&matchId='.$matchIds.'&companyId='.$companyId;
		
		$json_data = file_get_contents($odds_url);
		$json_data = json_decode($json_data);

		$matches_odds = [];
		foreach ($json_data->data as $odd_item) {
			
			$oddsDetail = $odd_item->odds[0]->oddsDetail;
			$oddsDetail_arr = explode(',',$oddsDetail);
			$oddsDetail = [
				'initialHome' => $oddsDetail_arr[2],
				'initialDraw' => $oddsDetail_arr[3],
				'initialAway' => $oddsDetail_arr[4],
				'instantHome' => $oddsDetail_arr[5],
				'instantDraw' => $oddsDetail_arr[6],
				'instantAway' => $oddsDetail_arr[7],
			];
			$matches_odds[$odd_item->matchId] = $oddsDetail;
		}
		
		$res['items'] 	= $matches_odds;
		$res['status'] 	= 1;
      	$this->resJson($res,1);
		
	}

	public function index(){
		/*
		Pre-match and In-play Odds (Main) 
		Path: /sport/football/odds/main
		Method: GET
		Calls: Unlimited calls for paid plans, 200 calls per day for free trial.
		Recommend Calls: 1 minute/call
		Parameters: 
		*/
        $companyId = $this->companyId;
		$matchIds   = ( isset($_GET['matchIds']) ) ? $_GET['matchIds'] : '';
		if(!$matchIds){
			$res['status'] = 0;
			$this->resJson($res,1);
		}
		
		$odds_url = API_URL."/sport/football/odds/main?api_key=".API_KEY.'&matchId='.$matchIds.'&companyId='.$companyId;

		$json_data = file_get_contents($odds_url);
		$json_data = json_decode($json_data);
		$matches_odds = $this->format_single_odds($json_data);

		$res['items'] 	= $matches_odds;
		$res['status'] 	= 1;
      	$this->resJson($res,1);
    }
	
	private function format_single_odds($json_data,$changes = false){
		$matches_odds = [];
		foreach ($json_data->data as $odd_type => $odd_items) {
			foreach ($odd_items as $odd_item) {
				$matchId = current( explode(',',$odd_item) );
				$orr_arr = explode(',',$odd_item);
				switch($odd_type){
					case 'handicap':
						$odd_type_arr = [
							'initialHandicap' 	=> $orr_arr[2],
							'initialHome' 		=> $orr_arr[3],
							'initialAway' 		=> $orr_arr[4],
							'instantHandicap' 	=> $orr_arr[5],
							'instantHome' 		=> $orr_arr[6],
							'instantAway' 		=> $orr_arr[7]
						];
						break;
					case 'europeOdds':
						$odd_type_arr = [
							'initialHome' 		=> $orr_arr[2],
							'initialDraw' 		=> $orr_arr[3],
							'initialAway' 		=> $orr_arr[4],
							'instantHome' 		=> $orr_arr[5],
							'instantDraw' 		=> $orr_arr[6],
							'instantAway' 		=> $orr_arr[7]
						];
						break;
					case 'overUnder':
						$odd_type_arr = [
							'initialHandicap' 	=> $orr_arr[2],
							'initialOver' 		=> $orr_arr[3],
							'initialUnder' 		=> $orr_arr[4],
							'instantHandicap' 	=> $orr_arr[5],
							'instantOver' 		=> $orr_arr[6],
							'instantUnder' 		=> $orr_arr[7]
						];
						break;
					case 'handicapHalf':
						$odd_type_arr = [
							'initialHandicap' 	=> $orr_arr[2],
							'initialHome' 		=> $orr_arr[3],
							'initialAway' 		=> $orr_arr[4],
							'instantHandicap' 	=> $orr_arr[5],
							'instantHome' 		=> $orr_arr[6],
							'instantAway' 		=> $orr_arr[7]
						];
						break;
					case 'overUnderHalf':
						$odd_type_arr = [
							'initialHandicap' 	=> $orr_arr[2],
							'initialOver' 		=> $orr_arr[3],
							'initialUnder' 		=> $orr_arr[4],
							'instantHandicap' 	=> $orr_arr[5],
							'instantOver' 		=> $orr_arr[6],
							'instantUnder' 		=> $orr_arr[7]
						];
						break;
				}
				$matches_odds[$matchId][$odd_type] = $odd_type_arr;
			}
		}
		return $matches_odds;
	}
	private function format_all_odds($json_data,$changes = false){
	
		$matches_odds = [];
		foreach ($json_data->data as $odd_type => $odd_items) {
			foreach ($odd_items as $odd_item) {
				$matchId = current( explode(',',$odd_item) );
				$orr_arr = explode(',',$odd_item);
				switch($odd_type){
					case 'handicap':
						$odd_type_arr = [
							'initialHandicap' 	=> $orr_arr[2],
							'initialHome' 		=> $orr_arr[3],
							'initialAway' 		=> $orr_arr[4],
							'instantHandicap' 	=> $orr_arr[5],
							'instantHome' 		=> $orr_arr[6],
							'instantAway' 		=> $orr_arr[7]
						];
						break;
					case 'europeOdds':
						$odd_type_arr = [
							'initialHome' 		=> $orr_arr[2],
							'initialDraw' 		=> $orr_arr[3],
							'initialAway' 		=> $orr_arr[4],
							'instantHome' 		=> $orr_arr[5],
							'instantDraw' 		=> $orr_arr[6],
							'instantAway' 		=> $orr_arr[7]
						];
						break;
					case 'overUnder':
						$odd_type_arr = [
							'initialHandicap' 	=> $orr_arr[2],
							'initialOver' 		=> $orr_arr[3],
							'initialUnder' 		=> $orr_arr[4],
							'instantHandicap' 	=> $orr_arr[5],
							'instantOver' 		=> $orr_arr[6],
							'instantUnder' 		=> $orr_arr[7]
						];
						break;
					case 'handicapHalf':
						$odd_type_arr = [
							'initialHandicap' 	=> $orr_arr[2],
							'initialHome' 		=> $orr_arr[3],
							'initialAway' 		=> $orr_arr[4],
							'instantHandicap' 	=> $orr_arr[5],
							'instantHome' 		=> $orr_arr[6],
							'instantAway' 		=> $orr_arr[7]
						];
						break;
					case 'overUnderHalf':
						$odd_type_arr = [
							'initialHandicap' 	=> $orr_arr[2],
							'initialOver' 		=> $orr_arr[3],
							'initialUnder' 		=> $orr_arr[4],
							'instantHandicap' 	=> $orr_arr[5],
							'instantOver' 		=> $orr_arr[6],
							'instantUnder' 		=> $orr_arr[7]
						];
						break;
				}
				$matches_odds[$matchId][$odd_type][] = $odd_type_arr;
			}
		}
		

		return $matches_odds;
	}
}
