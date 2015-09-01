<?php

class RecordStatistics
{




static function getRecentViewRecordsFromUserIdGroupByWikiSite($userId,$periodType)
{
	return self::curl_post('getRecentViewRecordsFromUserIdGroupByWikiSite',array('userId'=>$userId,'periodType'=>$periodType));
}

static function getViewRecordsFromUserIdGroupByWikiSite($userId,$fromTime,$toTime)
{
	$data = array("fromTime" => $fromTime,"toTime" => $toTime,"userId" => $userId);
	return self::curl_post('getViewRecordsFromUserIdGroupByWikiSite',$data);
	
}

static function getViewRecordsFromUserIdGroupByDay($userId,$fromTime,$toTime)
{
	$data = array("userId"=>$userId,"fromTime"=>$fromTime,"toTime"=>$toTime);
	return self::curl_post('getViewRecordsFromUserIdGroupByDay',$data);
}


///////////
static function getRecentEditRecordsFromUserIdGroupByWikiSite($userId,$periodType)
{
	return self::curl_post('getRecentEditRecordsFromUserIdGroupByWikiSite',array('userId'=>$userId,'periodType'=>$periodType));
}

static function getEditRecordsFromUserIdGroupByWikiSite($userId,$fromTime,$toTime)
{	
	$data = array("userId"=>$userId,"fromTime"=>$fromTime,"toTime"=>$toTime);
	return self::curl_post('getEditRecordsFromUserIdGroupByWikiSite',$data);
}

static function getEditRecordsFromUserIdGroupByDay($userId,$fromTime,$toTime)
{
	$data = array("userId"=>$userId,"fromTime"=>$fromTime,"toTime"=>$toTime);
	return self::curl_post('getEditRecordsFromUserIdGroupByDay',$data);
}

////////
static function getRecentPageEditCountOnWikiSiteFromUserId($userId,$wikiSite,$periodType)
{
	$data = array('userId'=>$userId,'wikiSite'=>$wikiSite,'periodType'=>$periodType);
	return self::curl_post('getRecentPageEditCountOnWikiSiteFromUserId',$data);
}

static function getPageEditCountOnWikiSiteFromUserId($userId,$wikiSite,$fromTime,$toTime)
{
	$data = array('userId'=>$userId,'wikiSite'=>$wikiSite,'fromTime'=>$fromTime,'toTime'=>$toTime);
	return self::curl_post('getPageEditCountOnWikiSiteFromUserId',$data);


}
////////////////

static function getRecentEditorCountOnWikiSite($wikiSite,$periodType)
{
	return self::curl_post('getRecentEditorCountOnWikiSite',array('wikiSite'=>$wikiSite,'periodType'=>$periodType));

}
static function getEditorCountOnWikiSite($wikiSite,$fromTime,$toTime)
{
	return self::curl_post('getEditorCountOnWikiSite',array('wikiSite'=>$wikiSite,'fromTime'=>$fromTime,'toTime'=>$toTime));
}


////////
static function getRecentPageViewCountOnWikiSiteFromUserId($userId,$wikiSite,$periodType)
{
	$data = array('userId'=>$userId,'wikiSite'=>$wikiSite,'periodType'=>$periodType);
	return self::curl_post('getRecentPageViewCountOnWikiSiteFromUserId',$data);
}

static function getPageViewCountOnWikiSiteFromUserId($userId,$wikiSite,$fromTime,$toTime)
{
	$data = array('userId'=>$userId,'wikiSite'=>$wikiSite,'fromTime'=>$fromTime,'toTime'=>$toTime);
	return self::curl_post('getPageViewCountOnWikiSiteFromUserId',$data);


}
////////////////

static function getRecentVisitorCountOnWikiSite($wikiSite,$periodType)
{
	return self::curl_post('getRecentVisitorCountOnWikiSite',array('wikiSite'=>$wikiSite,'periodType'=>$periodType));

}
static function getVisitorCountOnWikiSite($wikiSite,$fromTime,$toTime)
{
	return self::curl_post('getVisitorCountOnWikiSite',array('wikiSite'=>$wikiSite,'fromTime'=>$fromTime,'toTime'=>$toTime));
}












////////
 static function curl_post($functionName,$data)
{
	$url =  'http://10.251.139.166:50007/'.$functionName;

	$curl_opt_a = array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_TIMEOUT => 3,
		CURLOPT_POST => 1,
		CURLOPT_POSTFIELDS => http_build_query($data),
	);
	$ch = curl_init();
	curl_setopt_array($ch,$curl_opt_a);
	$out = curl_exec($ch);
	curl_close($ch);
//return $out;
	//var_dump($out);
//	var_dump(json_decode($out)->result)."\n"; 
	return json_decode($out)->result; 
}
 static function curl_get($funName)
{
	$url =  'http://10.251.139.166:50007/'.$funName;
	$curl_opt_a = array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_TIMEOUT => 1,
	);
	$ch = curl_init();
	curl_setopt_array($ch,$curl_opt_a);
	$out = curl_exec($ch);
	curl_close($ch);
	echo json_decode($out)->result."\n"; 
	return json_decode($out)->result; 
}



}

//var_dump( RecordStatistics::getPageViewCountOnWikiSiteFromUserId(543,"","",""));

//var_dump( RecordStatistics::getRecentVisitorCountOnWikiSite("","year"));
//var_dump( RecordStatistics::getVisitorCountOnWikiSite("","",""));

//var_dump( RecordStatistics::getRecentPageViewCountOnWikiSiteFromUserId(543,"","year"));


?>
