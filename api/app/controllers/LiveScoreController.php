<?php
// Include base controller to support basic framework fuctions
include SYSTEM . 'BaseController.php';

/**
* Default Controller
*/
class LiveScoreController extends BaseController
{
    public function changes(){
		

		$odds_url = API_URL."/sport/football/livescores/changes?api_key=".API_KEY;
		//$odds_url = API_URL."/sport/football/livescores?api_key=".API_KEY;
		
		$json_data 		= file_get_contents($odds_url);
		$all_scores     = json_decode($json_data)->data;


		$matches = [];

		foreach ($all_scores as $key => $value) {
			if( !in_array($value->status,[1,2,3,4,5]) ){
				continue;
			}
			$value->halfStartTime 	= date('H:i',$value->halfStartTime);			
			switch($value->status){
				case 1:		
					$value->matchTime 		= $this->time_elapsed_string($value->halfStartTime,1);
					break;
				case 2:
					$value->matchTime = 'HT';
					break;
				case 3:		
					$value->matchTime 		= $this->time_elapsed_string($value->halfStartTime,46);
					break;
				case 4:
					$value->matchTime = 'Extra time';
					break;
				case -1:
					$value->matchTime = 'FT';
					break;
				case 5:
					$value->matchTime = 'Penalty';
					break;
				default:
					$value->matchTime = $this->time_elapsed_string($value->matchTime);
					break;
				
			}
			$matches[$value->matchId] = $value;

		}

		
		$res['matches']	= $matches;

		$res['status'] 	= 1;
        $res['msg'] 	= __METHOD__;
      	$this->resJson($res,1);
	}
    public function index()
    {
		$companyId = $this->companyId;
		
		
		$prev = strtotime("-90 minutes");
		$next = strtotime("+90 minutes");


		$odds_url = API_URL."/sport/football/livescores?api_key=".API_KEY;
		
		$json_data = file_get_contents($odds_url);
		$all_schedules     = json_decode($json_data)->data;
		
		usort($all_schedules, function($a, $b) {
			if($a->matchTime == $b->matchTime) return 0;
			return $a->matchTime > $b->matchTime ? 1 : -1;
		});
		
		//get matchIds to get odds
		$matchIds = [];
		//get all matches
		$matches = [];
		//get leagues for current matches
		$leagues = [];
		
		/*
		0: Not started
		1: First half
		2: Half-time break
		3: Second half
		4: Extra time
		5: Penalty
		-1: Finished
		-10: Cancelled
		-11: TBD
		-12: Terminated
		-13: Interrupted
		-14: Postponed
		*/
		$i = 0;
		foreach ($all_schedules as $key => $value) {
			
			if( in_array($value->status,[1,2,3,4,5]) ){
				if(  !in_array($value->leagueId,$sl_leagues) ){
					//continue;
				}
				$matchIds[] = $value->matchId;
				$leagues[$value->leagueId] = $value->leagueName;
				$value->halfStartTime 	= date('H:i',$value->halfStartTime);			
				switch($value->status){
					case 1:
						$value->matchTime 		= $this->time_elapsed_string($value->halfStartTime,1);
						break;
					case 2:
						$value->matchTime = 'HT';
						break;
					case 3:		
						$value->matchTime 		= $this->time_elapsed_string($value->halfStartTime,46);
						break;
					case -1:
						$value->matchTime = 'FT';
						break;
					case 4:
						$value->matchTime = 'Extra time';
						break;
					case 5:
						$value->matchTime = 'Penalty';
						break;
					default:
						$value->matchTime = $this->time_elapsed_string($value->matchTime);
						break;
				}
				$matches[$value->leagueId][] = $value;
				
				if( $i == 5 ){
					//break;
				}
				$i++;
			}
			
			
		}
		
		$res['leagues'] = $leagues;
        $res['matchIds']= $matchIds;
        $res['matches']	= $matches;
        $res['status'] 	= 1;
        $res['msg'] 	= __METHOD__;
      	$this->resJson($res,1);

    }
	
	public function getOdds()
    {
        $res['msg'] = __METHOD__;
      	$this->resJson($res,1);
    }
	
	function time_elapsed_string($datetime, $add = 0) {
		$now = new DateTime;
		$ago = new DateTime($datetime);
		$diff = $now->diff($ago);
		$final = $diff->i + $add;
		if( $final < 91 ){
			$final = $final."'";
		}else{
			$final = 90;
			$final = $final."'+";
		}
		return $final;
	}
}
