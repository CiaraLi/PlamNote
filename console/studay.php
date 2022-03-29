<?php
require __DIR__ . "/vendor/autoload.php";
require __DIR__ . "/service/YachMessage.php";
require __DIR__ . "/config/RssConfig.php";
require __DIR__ . "/config/AtConfig.php";
require __DIR__ . "/config/YachConfig.php";

/**
 * Class Studay
 * author Liwei
 */
class Studay
{
    const WEEK = [
        0 => "星期日",
        1 => "星期一",
        2 => "星期二",
        3 => "星期三",
        4 => "星期四",
        5 => "星期五",
        6 => "星期六",
    ];

    static $butnList = [
        2 => [
            [
                'title' => '【英语】每日一练',
                'action_url' => 'http://www.kaoyan.com/yingyu/lianxi/',
                'btn_type' => 1,
            ],
            [
                'title' => '【数学】每日一练',
                'action_url' => 'http://www.kaoyan.com/shuxue/lianxi/',
                'btn_type' => 1,
            ],
            [
                'title' => '【政治】每日一练',
                'action_url' => 'http://www.kaoyan.com/zhengzhi/lianxi/',
                'btn_type' => 1,
            ]
        ],
        1 => [

            [
                'title' => '【武忠祥】每日一练',
                'action_url' => 'https://weibo.com/u/7498971049?is_all=1',
                'btn_type' => 1,
            ],
            [
                'title' => '【英语】每日一练',
                'action_url' => 'http://www.kaoyan.com/yingyu/lianxi/',
                'btn_type' => 1,
            ],
            [
                'title' => '【数学】每日一练',
                'action_url' => 'http://www.kaoyan.com/shuxue/lianxi/',
                'btn_type' => 1,
            ]
        ],
        3 => [
            [
                'title' => '【数学】每日一练',
                'action_url' => 'http://www.kaoyan.com/shuxue/lianxi/',
                'btn_type' => 1,
            ],
            [
                'title' => '【英语】每日一练',
                'action_url' => 'http://www.kaoyan.com/yingyu/lianxi/',
                'btn_type' => 1,
            ],
            [
                'title' => '【政治】每日一练',
                'action_url' => 'http://www.kaoyan.com/zhengzhi/lianxi/',
                'btn_type' => 1,
            ]
        ],
        4 => [
            [
                'title' => '【数学】每日一练',
                'action_url' => 'http://www.kaoyan.com/shuxue/lianxi/',
                'btn_type' => 1,
            ],
            [
                'title' => '【英语】每日一练',
                'action_url' => 'http://www.kaoyan.com/yingyu/lianxi/',
                'btn_type' => 1,
            ],
            [
                'title' => '【政治】每日一练',
                'action_url' => 'http://www.kaoyan.com/zhengzhi/lianxi/',
                'btn_type' => 1,
            ]
        ]
    ];

    //获取每个消息的按钮组合
    static function getRssNew($type)
    {
        $list = [];

        $rssList = RSS_CONFIG[$type];
        foreach ($rssList as $config) {
            $detail = new RssConfig($config);

            $butnList1 = self::getNews($detail->rss, $detail->filter, 24, $detail->num);
            !empty($butnList1) && $list = array_merge($list, $butnList1);
        }
        $list = array_merge($list, self::$butnList[$type]);
        return array_slice($list, 0, 4);

    }

    static function getAlertData()
    {
        $arr = [];
        try {
            $filePath = __DIR__ . "/log.txt";
            $stream = fopen($filePath, "r");
            $stream && $json = fread($stream, 1024);
            $stream && $json && $arr = json_decode($json, true);
            $stream && fclose($stream);
        } catch (\Exception $e) {
            var_dump($e);
        }
        return $arr;
    }

    static function setAlertData($arr)
    {
        try {
            $filePath = __DIR__ . "/log.txt";
            $stream = fopen($filePath, "w+");
            $stream && $json = json_encode($arr, JSON_UNESCAPED_UNICODE);
            $stream && fputs($stream, $json, 1024);
            $stream && fclose($stream);

        } catch (\Exception $e) {
            var_dump($e);
        }
    }

    static function check($type, $arr)
    {
        $day = date('Y-m-d');
        if (empty($arr['date']) || empty($arr['done'][$type]) || $arr['date'] != $day || !isset($arr['done'][$type])) {
            return true;
        } else {
            return false;
        }
    }


    static function getImage()
    {
        $num = rand(1, 20);
        if (in_array($num, [6]) && 22 > date('H')) {
            $num = rand(1, 20);
        }
        $image = "https://static.oss-cn-beijing.aliyuncs.com/source/uploads/loops/20201010/{$num}.png?x-oss-process=image/resize,m_pad,h_250,w_500,color_000000/quality,q_90";
        return $image;
    }


    /**
     * @throws FeedException
     */
    static function getNews($url, $filter = '', $hour = 24, $num = 4)
    {
        $bth = [];
        //https://rssfeed.today/weibo/
        try {
            $rss = Feed::loadRss($url);
            $start = strtotime('-' . intval($hour) . ' hour');
            foreach ($rss->item as $item) {
                if (intval($item->timestamp) > $start && count($bth) < $num) {
                    if (!empty($filter) && !preg_match($filter, strval($item->title)) && !preg_match($filter, strval($item->description))) {
                        var_dump("【{$filter}】匹配失败: " . strval($item->title));
                        continue;
                    }
                    $title = mb_substr(preg_replace("/(【)|(】)/is", '', strval($item->title)), 0, 14) . '...';

                    $bth[] = [
                        'title' => $title,
                        'time' => intval($item->timestamp),
                        'action_url' => strval($item->link),
                        'btn_type' => 1,
                    ];
                }
//            echo 'Title: ', $item->title;
//            echo 'Link: ', $item->link;
//            echo 'Timestamp: ', $item->timestamp;
//            echo 'Description ', $item->description;
//            echo 'HTML encoded content: ', $item->{'content:encoded'};
            }
        } catch (Exception $e) {
            echo "RSS_ERROR:  $url\r\n";
        }
        return $bth;
    }

    /**
     * @return mixed
     */
    protected static function morning()
    {
        $day = date('Y') . "-12-25 0:0:0";
        $thisday = date('Y-m-d', time());
        if (strtotime($thisday) > strtotime($day)) {
            $day = date('Y-m-d', strtotime($day . '+1 year'));

        }

        $today = time();
        $daymessage = date('Y-m-d H:i:s', $today);
        $week = date('w', $today);
        $weekmessage = self::WEEK[$week];
        $start = strtotime($daymessage);
        $end = strtotime($day);
        $days = floor(($end - $start) / 86400.0);

        $endYear = date('y', strtotime($day));

        $image = self::getImage();
//
        $butnList = self::getRssNew(1);

        $messageStr = [];
        $messageStr['msgtype'] = 'action_card';
        $messageStr['action_card'] = [
            'title' => "倒计时{$days}天",
            'content_title' => "距{$endYear}考研还剩【{$days}】天",
            'markdown' => "【早上好】今天是：{$daymessage}【{$weekmessage}】\r\n 📚知识就是力量，一天不学饿得慌，今天的复习内容准备好了么？",
            'image' => $image,
            'btn_orientation' => 0,
            'btn_json_list' => $butnList,
        ];
        $messageStr = json_encode($messageStr, JSON_UNESCAPED_UNICODE);

        return $messageStr;
    }

    /**
     * 中午 12：30
     * @return mixed
     */
    protected static function noon()
    {
        $image = self::getImage();
        $butnList = self::getRssNew(2);
        $messageStr = [];
        $messageStr['msgtype'] = 'action_card';
        $messageStr['action_card'] = [
            'title' => "中午好",
            'content_title' => "中午好",
            'markdown' => "【中午好】适当的休息休是为了更好地前进，休息之后不要忘了继续复习哦。 看看有什么新鲜事吧👇👇👇👇",
            'image' => $image,
            'btn_orientation' => 0,
            'btn_json_list' => $butnList,
        ];
        $mesage = json_encode($messageStr, JSON_UNESCAPED_UNICODE);

        return $mesage;
    }

    /**
     * @return mixed
     */
    protected static function night()
    {
        $image = self::getImage();
        $butnList = self::getRssNew(3);
        $messageStr = [];
        $messageStr['msgtype'] = 'action_card';
        $messageStr['action_card'] = [
            'title' => "晚上好",
            'content_title' => "晚上好",
            'markdown' => "【晚上好】结束一天的工作，终于可以安静的复习了。赶快拿起书本吧！",
            'image' => $image,
            'btn_orientation' => 0,
            'btn_json_list' => $butnList,
        ];
        $messageStr = json_encode($messageStr, JSON_UNESCAPED_UNICODE);

        return $messageStr;
    }

    /**
     * @return mixed
     */
    protected static function sleep()
    {
        $image = self::getImage();
        $butnList = self::getRssNew(4);

        $index = count($butnList) >= 3 ? 3 : count($butnList);
        $butnList[$index] = [
            'title' => '每日学习打卡微信群→→→',
            "action_url" => "https://yach-doc-shimo.zhiyinlou.com/docs/Wr3DVZbYDPTLGnkJ/  <微信打卡学习群>",
            'btn_type' => 1,
        ];

        $messageStr = [];
        $messageStr['msgtype'] = 'action_card';
        $messageStr['action_card'] = [
            'title' => "晚安",
            'content_title' => "晚安",
            'markdown' => "【晚安】忙碌的一天又过去了，今天有哪些收获，一起来回顾下吧！ 考研之路，道阻且长，愿坚持不懈的你终有所得，晚安🌛考研人！",
            'image' => $image,
            'btn_orientation' => 0,
            'btn_json_list' => $butnList,
        ];
        $messageStr = json_encode($messageStr, JSON_UNESCAPED_UNICODE);

        return $messageStr;
    }

    /**
     * 所有人
     * @return mixed
     */
    protected static function atAll()
    {
        $week = date('w');
        $weekmessage = self::WEEK[$week];
        $date = date("Y年m月d日 {$weekmessage}");
        $messageStr = <<<json
{
    "msgtype": "text",
     "text": {
         "content": "每日打卡格式\\r\\n --------------------------\\r\\n【打卡日期】{$date} \\r\\n【复习内容】 背单词  \\r\\n【复习时长】 2小时 \\r\\n ------------------------\\r\\n "
     },
     "at": {
         "isAtAll": true
     }
}
json;
        return $messageStr;
    }

    /**
     * 所有人
     * @return mixed
     */
    static function atUser($user)
    {
        $week = date('w');
        $weekmessage = self::WEEK[$week];
        $date = date("Y年m月d日 {$weekmessage}");

        $str = "业精于勤荒于嬉，放下手机快来复习吧！学习之后记的打卡哦！ \r\n --------------------------  \r\n ";

        $messageStr = [];
        $messageStr['msgtype'] = 'text';
        $messageStr['text'] = ['content' => $str];
        $messageStr['at'] = [
            'atMobiles' => $user,
            'isAtAll' => false
        ];
        $mesage = json_encode($messageStr, JSON_UNESCAPED_UNICODE);

        return $mesage;
    }

    /**
     * 获取消息
     * @param $type
     * @return mixed
     */
    static function getMessage($type)
    {
        switch ($type) {
            case 1:
                return self::morning();  //早上
            case 2:
                return self::noon();   //中午
            case 3:
                return self::night();
            case 4:
                return self::sleep();
        }
    }

}

function start($num = 0)
{

    $now = time();
    $day = date('Y-m-d');
//提醒列表
    $alertArr = [
        1 => ["6:00", "6:20"],
        2 => ["13:00", "13:20"],
        3 => ["20:00", "20:20"],
        4 => ["23:30", "23:50"],
    ];
//查询上一次发送
    $logData = Studay::getAlertData();
    foreach ($alertArr as $type => $range) {
        if ($now <= strtotime($day . ' ' . $range[1]) && $now >= strtotime($day . ' ' . $range[0]) &&
            Studay::check($type, $logData)
            || ($num > 0 && $num == $type)
        ) {
            $message = Studay::getMessage($type);
            $server = new  YachMessage();
            $status = $server->send($message);

            echo "发送消息：" . $type . ' - ' . $status;
            if ($status) {
                if (empty($logData['date']) || $logData['date'] != $day) {
                    $logData = [
                        'date' => $day,
                        'done' => []
                    ];
                }
                $logData['done'][$type] = 1;
                Studay::setAlertData($logData);
            }
        }
    }
}

function atUser()
{
    //单独艾特某人
    $AT_USER = AT_USERS ;
    $now = time();
    $logData = Studay::getAlertData();
    $day = date('Y-m-d');
    foreach ($AT_USER as $time => $user) {
        $type = $time;
        if ($now >= strtotime($day . ' ' . $time) && $now <= strtotime($day . ' ' . $time . ' +10min') &&
            Studay::check($type, $logData)) {

            $message = Studay::atUser($user);
            $server = new  YachMessage();
            $status = $server->send($message);

            echo "发送消息：" . $time . ':' . implode('，', $user) . ' - ' . $status;
            if ($status) {
                if (empty($logData['date']) || $logData['date'] != $day) {
                    $logData = [
                        'date' => $day,
                        'done' => []
                    ];
                }
                $logData['done'][$type] = 1;
                Studay::setAlertData($logData);
            }
        }
    }
}


//设置时区
ini_set('date.timezone', 'Asia/Shanghai');

start(empty($argv[1]) ? 0 : $argv[1]);
atUser();
var_dump(date("Y-m-d H:i:s"));
