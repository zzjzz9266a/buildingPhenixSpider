<?php 
require 'vendor/autoload.php';
use DiDom\Document;
use DiDom\Query;

$file_path = dirname(__FILE__).'/database.json';
$json_string = file_get_contents($file_path);
$config = json_decode($json_string);
$dsn = $config->dsn; //构造数据源，mysql是数据类型，localhost是主机地址，shadow_manage是数据库名称
$db_user = $config->db_user; //数据库用户名
$db_password = $config->db_password; //登录数据库的密码

for ($i=1; $i <= 5; $i++) { 
  $url = 'http://www.weike27.com/class.asp?page='.$i.'&typeid=&areaid=1'; 
  echo $url."\n";
  listPage($url);
}

function listPage($pageUrl)
{ 
  $document = new Document($pageUrl, true);

  $titles = $document->find('//div[@class="main dq1"]/ul[@class="list"]/li[@class="dq7 wd4"]/a', Query::TYPE_XPATH);
  foreach ($titles as $title) {
    $href = $title->getAttribute('href');
    echo $title->text()."\n";
    echo $href."\n";
    echo "------------------\n";

    if (strpos($href, "show.asp") !== false) {
      $detail = new Document("http://www.weike27.com".$href, true);
      $phone = $detail->xpath('//div[@class="guize2"]/li[15]');
      $member_id = substr($href, strpos($href, "id=")+3, 5);
      $connection = $phone[0]->text();
      echo trim($phone[0]->text())."\n";
      echo ">>>>>>>>>>\n";
      if (!checkExist($member_id)) {
        update($member_id, $title->text(), $connection);
      }
    }
    sleep(2);
  }
}

function checkExist($member_id)
{
  global $dsn,$db_user,$db_password;

  $db = new PDO($dsn,$db_user,$db_password);

  $sql = "select * from members where member_id = $member_id";
  $query = $db->query($sql);
  $query->setfetchmode(pdo::FETCH_ASSOC); //设置数组关联方式
  $result = $query->fetchAll();
  return $result;
}

function update($member_id, $title, $connection)
{
  global $dsn,$db_user,$db_password;

  $db = new PDO($dsn,$db_user,$db_password);
  $db->query("SET NAMES utf8"); 
  $currentTime = "'".date('Y-m-d H:i:s',time())."'";
  $values = $member_id.','."'$title'".','."'$connection'".','.$currentTime;
  $insert = "insert into members(member_id, title, connection, date_time) values($values)";
  $db->exec($insert);
  $db = NULL;
}