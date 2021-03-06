<?php

class RecordStatistics
{

# View Records
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

# Edit Records
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

#Edit Count
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

#Editor Count
static function getRecentEditorCountOnWikiSite($wikiSite,$periodType)
{
	return self::curl_post('getRecentEditorCountOnWikiSite',array('wikiSite'=>$wikiSite,'periodType'=>$periodType));

}
static function getEditorCountOnWikiSite($wikiSite,$fromTime,$toTime)
{
	return self::curl_post('getEditorCountOnWikiSite',array('wikiSite'=>$wikiSite,'fromTime'=>$fromTime,'toTime'=>$toTime));
}

static function getEditorCountGroupByWikiSite($fromTime,$toTime)
{

	return self::curl_post('getEditorCountGroupByWikiSite',array('fromTime'=>$fromTime,'toTime'=>$toTime));
}


#View Count
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

#Visitor Count
static function getRecentVisitorCountOnWikiSite($wikiSite,$periodType)
{
	return self::curl_post('getRecentVisitorCountOnWikiSite',array('wikiSite'=>$wikiSite,'periodType'=>$periodType));

}
static function getVisitorCountOnWikiSite($wikiSite,$fromTime,$toTime)
{
	return self::curl_post('getVisitorCountOnWikiSite',array('wikiSite'=>$wikiSite,'fromTime'=>$fromTime,'toTime'=>$toTime));
}




# Faked Edit Record
static function upsertFakedPageEditRecord($userId,$count,$date)
{
	$data = json_encode(array(
					'userId' => $userId,
					'count' => $count,
					'targetDate' => $date
					)
				);

	return self::curl_post_json('upsertFakedPageEditRecord',$data);
}

static function getFakedPageEditCountFromUserId($userId,$fromTime,$toTime){
	$data = json_encode(array(
				'userId' => $userId,
				'fromTime' => $fromTime,
				'toTime' => $toTime
				)
			);
	$out = self::curl_post_json('getFakedPageEditCountFromUserId',$data);
	$out->result = $out->result[0]->value;
	return $out;

}

static function getFakedPageEditRecordsFromUserIdGroupByDay($userId,$fromTime,$toTime){
	$data = json_encode(array(
				'userId' => $userId,
				'fromTime' => $fromTime,
				'toTime' => $toTime
				)
			);

	return self::curl_post_json('getFakedPageEditRecordsFromUserIdGroupByDay',$data);


}

static function getAllPageEditRecordsFromUserIdGroupByDay($userId,$fromTime,$toTime)
{
	$faked =RecordStatistics::getFakedPageEditRecordsFromUserIdGroupByDay($userId,$fromTime,$toTime);
	$real = RecordStatistics::getEditRecordsFromUserIdGroupByDay($userId,$fromTime,$toTime);
	
	$out = new stdClass();
	$result = array();
	$temp = array();
	if($faked->status == 'success' && $real->status == 'success'){
		foreach($real->result as $object){
			if($object->_id != NULL){	
			$result[$object->_id]=$object->value;
			}
		}	
		
		foreach($faked->result as $object){
			if($object->_id == NULL) break;
			if(isset($result[$object->_id])){
				$result[$object->_id] += $object->value;
			}else{
				$result[$object->_id] = $object->value;
			}
		}
		foreach($result as $key=>$value){
			$ob = new stdClass();
			$ob->_id = $key;
			$ob->value = $value;
			array_push($temp,$ob);
		}

		$result = $temp;
		$out->status = 'success';
	}else if($faked->status == 'success'){
		$result = $faked->result;
		$out->status = 'success';		
	}else if($real->status == 'success'){
		$result = $real->result;
		$out->status = 'success';
	}else{
		$out->status = 'fail';
	}
	
	$out->result = $result;
	
	return $out;
}

static function getAllPageEditCountFromUserId($userId,$fromTime,$toTime)
{
	$faked =RecordStatistics::getFakedPageEditCountFromUserId($userId,$fromTime,$toTime);
	$real = RecordStatistics::getPageEditCountOnWikiSiteFromUserId($userId,'',$fromTime,$toTime);
	if($faked->status == 'success' && $real->status == 'success'){
		$real->result += $faked->result;
		return $real;
	}else if($faked->status == 'success'){
		return $faked;
	}else if($real->status == 'success'){
		return $real;
	}else{
		return $real;
	}
}	

#CURL
static function curl_post($functionName,$data)
{
	$url =  'http://huijidata.com:50007/'.$functionName;

	$curl_opt_a = array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_TIMEOUT => 300,
		CURLOPT_POST => 1,
		CURLOPT_POSTFIELDS => http_build_query($data),
	);
	$ch = curl_init();
	curl_setopt_array($ch,$curl_opt_a);
	$out = curl_exec($ch);
	$count = 0;
	while($out === false && $count < 4){
		$out = curl_exec($ch);
		$count++;
	}
	if($out === false){
	 	$out = '{"status":"fail"}';
	}
	curl_close($ch);
	return json_decode($out); 
}

static function curl_post_json($path,$data_string)
{
	$url =  'http://huijidata.com:50008/'.$path;
	$header = array(
		'Content-Type: application/json',
		'Content-Length: '.strlen($data_string),
		);
	$curl_opt_a = array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_TIMEOUT => 300,
		CURLOPT_POST => 1,
		CURLOPT_POSTFIELDS =>$data_string,
		CURLOPT_HTTPHEADER =>$header,
	);
	$ch = curl_init();
	curl_setopt_array($ch,$curl_opt_a);
	$out = curl_exec($ch);
	$count = 0;
	while($out === false && $count < 4){
		$out = curl_exec($ch);
		$count++;
	}
	if($out === false){
		$out = '{"status":"fail"}';
	}
	curl_close($ch);
	return json_decode($out); 
}



}

//var_dump(RecordStatistics::getEditRecordsFromUserIdGroupByWikiSite(131,'2016-03-03','2016-03-03'));
//var_dump(RecordStatistics::getAllPageEditRecordsFromUserIdGroupByDay(131,'2016-02-01','2016-04-05'));
//var_dump(RecordStatistics::getAllPageEditCountFromUserId(543,'2016-03-06','2016-03-06'));
//var_dump(RecordStatistics::getViewRecordsFromUserIdGroupByWikiSite("131",'2016-03-03','2016-03-03'));

//var_dump(RecordStatistics::getFakedPageEditRecordsFromUserIdGroupByDay(1,"2001-7-01","2001-12-12"));
//var_dump(RecordStatistics::getFakedPageEditCountFromUserId(543,"2016-03-06","2016-03-06"));
//var_dump( RecordStatistics::getPageViewCountOnWikiSiteFromUserId(-1,"","",""));
//var_dump( RecordStatistics::getRecentVisitorCountOnWikiSite("","year"));
//var_dump( RecordStatistics::getVisitorCountOnWikiSite("","",""));

//var_dump( RecordStatistics::getRecentPageViewCountOnWikiSiteFromUserId(543,"","year"));
//var_dump(RecordStatistics::getEditorCountGroupByWikiSite("",""));
//var_dump(RecordStatistics::getEditRecordsFromUserIdGroupByWikiSite(-1,"",""));


//var_dump(RecordStatistics::upsertFakedPageEditRecord(543,13,"2016-03-06"));

?>
