<?php
require_once "../path.php";

require_once "../dict/turbo_split.php";
require_once "../redis/function.php";

if (isset($argv[1])) {
	$start = (int)$argv[1];
}
else{
	$start=1;
}

if (isset($argv[2])) {
	$end = (int)$argv[2];
}
else{
	$end=1000000;
}

global $result;
$myfile = fopen(_DIR_TEMP_DICT_TEXT_ . "/comp.csv", "a");
$filefail = fopen(_DIR_TEMP_DICT_TEXT_ . "/comp_fail.txt", "a");
$iMax = 2;//输出前三个结果
/*
$dns = "" . _FILE_DB_WORD_INDEX_;
$dbh_word = new PDO($dns, "", "", array(PDO::ATTR_PERSISTENT => true));
$dbh_word->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

$query = "SELECT * from wordindex where 1";
$stmt = $dbh_word->query($query);

while ($word = $stmt->fetch(PDO::FETCH_ASSOC))
 */
$redis = redis_connect();
if ($redis == false) {
    echo "no redis connect\n";
    exit;
}
$i = null;

while($word = $redis->hGet("pali://wordindex.hash",$start))
{
	# code...

	if($start>$end){
		echo "all done";
		exit;
	}

	{
        # code...
        $arrword = split_diphthong($word);
        if (count($arrword) > 1) {
			$data = array($start,$word,'.comp.','','','','',implode("+", $arrword),'',1,50,6,'comp','en');
            fputcsv($myfile, $data);
        }

        foreach ($arrword as $oneword) {
			$result = array(); //全局变量，递归程序的输出容器
			mySplit2($oneword, 0, false, 0.2, 0.9, 0, true, false);
			if(count($result)<3){
				mySplit2($oneword, 0, false, 0.2, 0.8, 0, true, true);
				if (isset($_POST["debug"])) {
					echo "正切：" . count($result) . "\n";
				}
				if(count($result)<2){
					mySplit2($oneword, 0, false, 0.2, 0.8, 0, false, true);
					if (isset($_POST["debug"])) {
						echo "反切：" . count($result) . "\n";
					}					
				}
			}

            /*
            #正向切分
            mySplit2($oneword, 0, false);
            if (count($result) == 0) {
            #如果没有 逆向切分
            mySplit2($oneword, 0, false, 0, 0.8, 0.8, true);
            }
             */
            echo "{$start}-{$oneword}:" . count($result) . "\n";
            if (count($result) > 0) {
                arsort($result); //按信心指数排序
                $iCount = 0;
                foreach ($result as $row => $value) {
					$data = array($start,$oneword,'.comp.','','','','',$row,'',1,round($value*70),6,'comp','en');
					fputcsv($myfile, $data);

								//后处理 进一步切分没有意思的长词
					$new = split2($row);
					if($new!==$row){
						$data = array($start,$oneword,'.comp.','','','','',$new,'',1,round($value*70),6,'comp','en');
						fputcsv($myfile, $data);
						#再处理一次
						$new2 = split2($new);
						if($new2!==$new){
							$data = array($start,$oneword,'.comp.','','','','',$new2,'',1,round($value*70),6,'comp','en');
							fputcsv($myfile, $data);				
						}				
					}
					$iCount++;
                    if ($iCount > $iMax) {
                        break;
                    }
                }
            } else {
                fwrite($filefail, $oneword . "\n");
            }

        }

	}
	$start++;
}