<?php 
	
// $file_path = dirname(__FILE__).'/database.json';
// $json_string = file_get_contents($file_path);
// $config = json_decode($json_string);
// $dsn = $config->dsn; //构造数据源，mysql是数据类型，localhost是主机地址，shadow_manage是数据库名称
// $db_user = $config->db_user; //数据库用户名
// $db_password = $config->db_password; //登录数据库的密码
// $table = $config->table;    //表名

// $db = new PDO($dsn,$db_user,$db_password);
// $sql = "SHOW FULL COLUMNS FROM $table";
// $query = $db->query($sql);
// $query->setfetchmode(pdo::FETCH_ASSOC); //设置数组关联方式
// $result = $query->fetchAll();
// var_dump($result);
// foreach ($result as $field) {
// 	$field = $field["Field"];
// 	if ($field != 'id') {
// 		// $db->exec("update $table set $field=replace($field,'	','') ");
// 		// preg_match("", subject)
// 		preg_replace('\s','',$project[0]->text());
// 		$db->exec("update $table set $field=replace($field,'','') ");
// 	}
// }
// $db = NULL;
echo preg_replace('#\s+#', '', '服务项目：

制服角色扮演窃窃私语蚂蚁上树恋足高跟等
');
