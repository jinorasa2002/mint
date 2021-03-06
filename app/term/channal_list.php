<?php
#输入句子列表查询channel列表 和计算完成度
require_once "../public/function.php";
require_once "../public/_pdo.php";
require_once "../path.php";
require_once '../channal/function.php';
require_once '../ucenter/function.php';

$_data = array();
$output = array();
if (isset($_POST["data"])) {
    $_data = json_decode($_POST["data"], true);
} else {
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}

$_userinfo = new UserInfo();
$_channal = new Channal();

$dns = "" . _FILE_DB_SENTENCE_;
$db_trans_sent = new PDO($dns, "", "", array(PDO::ATTR_PERSISTENT => true));
$db_trans_sent->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

$dns = "" . _FILE_DB_PALI_SENTENCE_;
$db_pali_sent = new PDO($dns, "", "", array(PDO::ATTR_PERSISTENT => true));
$db_pali_sent->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

$channal = array();

#查询有阅读权限的channel
$channal_list = array();
if (isset($_COOKIE["userid"])) {
    PDO_Connect(_FILE_DB_CHANNAL_);
    $query = "SELECT id from channal where owner = ?   limit 0,100";
    $Fetch_my = PDO_FetchAll($query, array($_COOKIE["userid"]));
    foreach ($Fetch_my as $key => $value) {
        # code...
        $channal_list[] = $value["id"];
    }

    # 找协作的
    $Fetch_coop = array();
    $query = "SELECT channal_id FROM cooperation WHERE  user_id = ? ";
    $coop_channal = PDO_FetchAll($query, array($_COOKIE["userid"]));
    if (count($coop_channal) > 0) {
        foreach ($coop_channal as $key => $value) {
            # code...
            $channal_list[] = $value["channal_id"];
        }
    }
    /*  创建一个填充了和params相同数量占位符的字符串 */

}
if (count($channal_list) > 0) {
    $channel_place_holders = implode(',', array_fill(0, count($channal_list), '?'));
    $channel_query = " OR channal IN ($channel_place_holders)";
} else {
    $channel_query = "";
}

# 查询有阅读权限的channel 结束

$final = array();
$article_len = 0;
foreach ($_data as $key => $value) {
    $pali_letter = array();
    # code...
    $id = $value["id"];
    $arrInfo = str_getcsv($value["data"], "@");
    $arrSent = str_getcsv($arrInfo[0], "-");
    $bookId = $arrSent[0];
    $para = $arrSent[1];
    $begin = $arrSent[2];
    $end = $arrSent[3];

    //find out translation
    $tran = "";
    try {
        # 查询句子长度
        $pali_letter["id"] = $arrInfo[0];
        $query = "SELECT length FROM pali_sent WHERE book= ? AND paragraph= ? AND begin= ? AND end= ?  ";
        $stmt = $db_pali_sent->prepare($query);
        $stmt->execute(array($bookId, $para, $begin, $end));
        $Fetch = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($Fetch) {
            $pali_letter["len"] = $Fetch["length"];
            $article_len += $Fetch["length"];
        } else {
            $pali_letter["len"] = 0;
        }

        #公开 或 channel有权限的
        $query = "SELECT channal FROM sentence WHERE book= ? AND paragraph= ? AND begin= ? AND end= ?  AND strlen >0 and (status = 30 {$channel_query} ) group by channal  limit 0 ,20 ";
        $stmt = $db_trans_sent->prepare($query);
        $parm = array($bookId, $para, $begin, $end);
        $parm = array_merge_recursive($parm, $channal_list);
        $stmt->execute($parm);
        $Fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($Fetch as $key => $value) {
            # code...
            $pali_letter[$value["channal"]] = 1;
            if (isset($channal[$value["channal"]])) {
                $channal[$value["channal"]]++;
            } else {
                $channal[$value["channal"]] = 1;
            }
        }

        $final[] = $pali_letter;
    } catch (Exception $e) {
        $tran = $e->getMessage();
        //echo 'Caught exception: ',  $e->getMessage(), "\n";
    }

}

foreach ($channal as $key => $value) {
    # 计算句子的完成分布
    $arr_sent_final = array();
    foreach ($final as $final_value) {
        # code...
        $sent_final = array();
        $sent_final["id"] = $final_value["id"];
        $sent_final["len"] = $final_value["len"];
        if (isset($final_value[$key]) && $final_value[$key] == 1) {
            $sent_final["final"] = true;
        } else {
            $sent_final["final"] = false;
        }
        $arr_sent_final[] = $sent_final;
    }
    $channalInfo = $_channal->getChannal($key);
    $name = $_userinfo->getName($channalInfo["owner"]);
    $channalInfo["username"] = $name["username"];
    $channalInfo["nickname"] = $name["nickname"];
    $channalInfo["count"] = $value;
    $channalInfo["all"] = count($_data);
    $channalInfo["final"] = $arr_sent_final;
    $channalInfo["article_len"] = $article_len;

    $output[] = $channalInfo;
}

echo json_encode($output, JSON_UNESCAPED_UNICODE);
