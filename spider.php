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
$table = $config->table;    //表名

for ($i=1; $i <= 10; $i++) { 
  $url = 'http://www.weike27.com/class.asp?page='.$i.'&typeid=4&areaid=23'; 
  echo $url."\n";
  listPage($url);
}

function listPage($pageUrl)
{ 
  $document = new Document($pageUrl, true);

  $titles = $document->find('//div[@class="main dq1"]/ul[@class="list"]/li[@class="dq7 wd4"]/a', Query::TYPE_XPATH);
  foreach ($titles as $title) {
    $baseUrl = "http://www.weike27.com";

    $href = $title->getAttribute('href');
    if (strpos($href, "show.asp") !== false) {
      echo $title->text()."\n";
      // echo $href."\n";
      echo ">>>>>>>>>>\n";

      $detailPage = new Document($baseUrl.$href, true);

      $phone = $detailPage->xpath('//div[@class="guize2"]/li[contains(text(),"联系方式")]');
      $connection = preg_replace('#\s+#','',$phone[0]->text());

      $date = $detailPage->xpath('//div[@class="guize1"]/font[contains(text(), "发布时间")]');
      $date = substr($date[0]->text(), strpos($date[0]->text(), "发布时间")+15);

      $area = $detailPage->xpath('//div[@class="guize2"]/li[contains(text(),"所属地区")]');
      $area = preg_replace('#\s+#','',$area[0]->text());

      $age = $detailPage->xpath('//div[@class="guize2"]/li[contains(text(),"小姐年龄")]');
      $age = preg_replace('#\s+#','',$age[0]->text());

      $project = $detailPage->xpath('//div[@class="guize2"]/li[contains(text(),"服务项目")]');
      $project = preg_replace('#\s+#','',$project[0]->text());

      $price = $detailPage->xpath('//div[@class="guize2"]/li[contains(text(),"价格一览")]');
      $price = preg_replace('#\s+#','',$price[0]->text());

      $security = $detailPage->xpath('//div[@class="guize2"]/li[contains(text(),"安全评估")]');
      $security = preg_replace('#\s+#','',$security[0]->text());

      $judge = $detailPage->xpath('//div[@class="guize2"]/li[contains(text(),"综合评价")]');
      $judge = preg_replace('#\s+#','',$judge[0]->text());

      $detail = $detailPage->xpath('//div[@class="guize2"]/li[@class="neirong3"]');
      $detail = preg_replace('#\s+#','',$detail[0]->text());

      $images = $detailPage->xpath('//div[@class="guize2"]/div/a/img');
      $array = [];
      foreach ($images as $image) {
        $array[] = $baseUrl.$image->getAttribute('src');
      }
      $images = json_encode($array);

      $member_id = substr($href, strpos($href, "id=")+3, 5);

      if (!checkExist($member_id)) {
        update($member_id, $title->text(), $connection, $date, $age, $area, $project, $price, $security, $judge, $detail, $images);
      }
    }
  }
}

function checkExist($member_id)
{
  global $dsn,$db_user,$db_password, $table;

  $db = new PDO($dsn,$db_user,$db_password);

  $sql = "select * from $table where member_id = $member_id";
  $query = $db->query($sql);
  $query->setfetchmode(pdo::FETCH_ASSOC); //设置数组关联方式
  $result = $query->fetchAll();
  $db = NULL;
  return $result;
}

function update($member_id, $title, $connection, $date, $area, $age, $project, $price, $security, $judge, $detail, $images)
{
  global $dsn,$db_user,$db_password, $table;

  $db = new PDO($dsn,$db_user,$db_password);
  $db->query("SET NAMES utf8"); 
  $currentTime = "'".date('Y-m-d H:i:s',time())."'";
  $values = $member_id.','."'$title'".','."'$connection'".','.$currentTime.','."'$date'".','."'$area'".','."'$age'".','."'$project'".','."'$price'".','."'$security'".','."'$judge'".','."'$detail'".','."'$images'";
  $insert = "insert into $table(member_id, title, connection, date_time, public_date, area, age, project, price, security, judge, detail, images) values($values)";
  // echo $insert;
  $db->exec($insert);
  $db = NULL;
}