<?php

class RecordStatistics2
{




# view
static function getPageViewCountOnWikiSiteFromUserId($userId,$sitePrefix,$fromDate,$toDate)
{
	$data = json_encode(array('userId'=>$userId,'sitePrefix'=>$sitePrefix,'fromDate'=>$fromDate,'toDate'=>$toDate));
	return self::curl_post_json('view','getPageViewCountOnWikiSiteFromUserId',$data);
}


# fakedEdit
static function insertOneFakedPageEditRecord($userId,$num,$date)
{
	$data = json_encode(array(
					'userId' => $userId,
					'num' => $num,
					'targetDate' => $date
					)
				);

	return self::curl_post_json('fakedEdit','insertOneFakedPageEditRecord',$data);
}


# edit
static function getAllPageEditRecordsFromUserIdGroupByDay($userId,$fromDate,$toDate)
{

         $data = json_encode(array(
                                'userId' => $userId,
                                'fromDate' => $fromDate,
                                'toDate' => $toDate
                                )
                        );
       
	return self:: curl_post_json('edit','getAllPageEditRecordsFromUserIdGroupByDay', $data);


}

static function getAllPageEditCountFromUserId($userId,$fromDate,$toDate)
{


         $data = json_encode(array(
                                'userId' => $userId,
                                'fromDate' => $fromDate,
                                'toDate' => $toDate
                                )
                        );
       
	return self:: curl_post_json('edit','getAllPageEditCountFromUserId', $data);

}	


static function getPageEditCountOnWikiSiteFromUserId($userId, $sitePrefix, $fromDate,$toDate)
{


         $data = json_encode(array(
                                'userId' => $userId,
                                'fromDate' => $fromDate,
                                'toDate' => $toDate,
				'sitePrefix' => $sitePrefix
                                )
                        );
       
	return self:: curl_post_json('edit','getPageEditCountOnWikiSiteFromUserId', $data);

}	

static function getPageEditorRecordsGroupByWikiSite($fromDate,$toDate)
{


         $data = json_encode(array(
                                'fromDate' => $fromDate,
                                'toDate' => $toDate,
                                )
                        );
       
	return self:: curl_post_json('edit','getPageEditorRecordsGroupByWikiSite', $data);

}
#CURL

static function curl_post_json($path,$function,$data_string)
{
	$url =  'http://121.42.144.9:8080/statisticQuery/webapi/'.$path.'/'.$function;
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

//var_dump(RecordStatistics::insertOneFakedPageEditRecord(543,20,'2015-01-05'));
//var_dump(RecordStatistics::getAllPageEditRecordsFromUserIdGroupByDay(543,'2016-03-17','2016-03-18'));
//var_dump(RecordStatistics::getAllPageEditCountFromUserId(543,'2014-09-09','2014-09-09'));
//var_dump(RecordStatistics::getPageEditCountOnWikiSiteFromUserId(-1,'','',''));
//var_dump(RecordStatistics::getPageEditorRecordsGroupByWikiSite('',''));
//var_dump(RecordStatistics::getPageViewCountOnWikiSiteFromUserId(543,'lotr','2016-03-18','2016-03-18'));

?>
