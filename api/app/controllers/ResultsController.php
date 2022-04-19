<?php
// Include base controller to support basic framework fuctions
include SYSTEM . 'BaseController.php';

/**
* Default Controller
*/
class OddsController extends BaseController
{
    public function index()
    {
        $res['msg'] = __METHOD__;
      	$this->resJson($res,1);
    }
	
	public function getOdds()
    {
        $res['msg'] = __METHOD__;
      	$this->resJson($res,1);
    }
}
