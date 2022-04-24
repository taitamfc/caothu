<?php
// Include base controller to support basic framework fuctions
include SYSTEM . 'BaseController.php';

/**
* Default Controller
*/
class ScheduleController extends BaseController
{
	private $_table = 'schedule';

    public function index()
    {
		$date   = ( isset($_GET['date']) && $_GET['date'] !='' ) ? $_GET['date'] : date('Y-m-d');
		$sl_leagues   = ( isset($_GET['leagues']) && $_GET['leagues'] != '' ) ? explode(',',$_GET['leagues']) : [];
		$only_leagues = [];
		// if( date('w',strtotime($date)) == 6 || date('w',strtotime($date)) == 7 ){
			// $only_leagues = [
				// 'English Premier League' => 1639,
				// 'German Bundesliga' => 188,
				// 'Italian Serie A' => 1437,
				// 'Spanish La Liga' => 1134,
			// ];
		// }

		//lich thi dau
		$schedule_url   = API_URL."/sport/football/schedule/basic?api_key=".API_KEY.'&date='.$date;
		$json_data      = file_get_contents($schedule_url);
		$all_schedules      = json_decode($json_data)->data;
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
		$i=0;
		foreach ($all_schedules as $key => $value) {
			//if($i> 500) break; $i++;
			if( $value->matchTime < time() ){
				continue;
			}
			if( count($sl_leagues) && !in_array($value->leagueId,$sl_leagues) ){
				continue;
			}
			if( count($only_leagues) && !in_array($value->leagueId,$only_leagues) ){
				continue;
			}
			$matchIds[] = $value->matchId;
			$leagues[$value->leagueId] = $value->leagueName;
			//$value->matchTime = date('d/m/Y H:i',$value->matchTime + 7 * 60 * 60);
			$value->matchTime = date('d/m/Y H:i',$value->matchTime);
			$matches[$value->leagueId][] = $value;
		}

        $res['leagues'] = $leagues;
        //$res['items'] 	= $all_schedules;
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

}
