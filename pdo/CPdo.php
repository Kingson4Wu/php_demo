//自己写的一个PDO类
class CPdo{
protected $_dsn = "mysql:host=localhost;dbname=test";
protected $_name = "root";
protected $_pass = "";
protected $_condition = array();
protected $pdo;
protected $fetchAll;
protected $query;
protected $result;
protected $num;
protected $mode;
protected $prepare;
protected $row;
protected $fetchAction;
protected $beginTransaction;
protected $rollback;
protected $commit;
protected $char;
private static $get_mode;
private static $get_fetch_action;
/**
*pdo construct
*/
public function __construct($pconnect = false) {
   $this->_condition = array(PDO::ATTR_PERSISTENT => $pconnect); 
   $this->pdo_connect();
}
    
/**
*pdo connect
*/
private function pdo_connect() {
   try{
    $this->pdo = new PDO($this->_dsn,$this->_name,$this->_pass,$this->_condition);
   } catch(Exception $e) {
    return $this->setExceptionError($e->getMessage(), $e->getline, $e->getFile);
   } 
  
}
/**
*self sql get value action
*/
public function getValueBySelfCreateSql($sql, $fetchAction = "assoc",$mode = null) {
  
   $this->fetchAction = $this->fetchAction($fetchAction);
   $this->result = $this->setAttribute($sql, $this->fetchAction, $mode);
   $this->AllValue = $this->result->fetchAll();
   return $this->AllValue;
}
/**
*select condition can query
*/
private function setAttribute($sql, $fetchAction, $mode) {
   $this->mode = self::getMode($mode);
   $this->fetchAction = self::fetchAction($fetchAction);
   $this->pdo->setAttribute(PDO::ATTR_CASE, $this->mode);
   $this->query = $this->base_query($sql);
   $this->query->setFetchMode($this->fetchAction);
   return $this->query;
}
/**
*get mode action
*/
private static function getMode($get_style){
  
   switch($get_style) {
    case null:
     self::$get_mode = PDO::CASE_NATURAL;
    break;
    case true:
     self::$get_mode = PDO::CASE_UPPER;
    break;
    case false;
     self::$get_mode= PDO::CASE_LOWER;
    break;
   }
   return self::$get_mode;
}
/**
*fetch value action
*/
private static function fetchAction($fetchAction) {
   switch($fetchAction) {
    case "assoc":
     self::$get_fetch_action = PDO::FETCH_ASSOC; //asso array
    break;
    case "num":
     self::$get_fetch_action = PDO::FETCH_NUM;//num array
    break;
    case "object":
     self::$get_fetch_action = PDO::FETCH_OBJ; //object array
    break;
    case "both":
     self::$get_fetch_action = PDO::FETCH_BOTH;//assoc array and num array
    break;
    default:
     self::$get_fetch_action = PDO::FETCH_ASSOC;
    break;
   }
   return self::$get_fetch_action;
}
/**
*get total num action
*/
public function rowCount($sql) {
   $this->result = $this->base_query($sql);
   $this->num = $this->result->rowCount();
   return $this->num;
  
}
/*
*simple query and easy query action
*/
public function query($table, $column = "*",$condition = array(), $group = "",$order = "", $having = "", $startSet = "",$endSet = "",$fetchAction = "assoc",$params = null){
   $sql = "select ".$column." from `".$table."` ";
   if ($condition != null) {
   
    foreach($condition as $key=>$value) {
     $where .= "$key = '$value' and ";
    }
   
    $sql .= "where $where";
    $sql .= "1 = 1 ";
   }
  
   if ($group != "") {
    $sql .= "group by ".$group." ";
   }
   if ($order != "") {
    $sql .= " order by ".$order." ";
   }
   if ($having != "") {
    $sql .= "having '$having' ";
   }
   if ($startSet != "" && $endSet != "" && is_numeric($endSet) && is_numeric($startSet)) {
    $sql .= "limit $startSet,$endSet";
   }
   $this->result = $this->getValueBySelfCreateSql($sql, $fetchAction, $params);
   return $this->result;
}
/**
*execute delete update insert and so on action
*/
public function exec($sql) {
  
   $this->result = $this->pdo->exec($sql);
   $substr = substr($sql, 0 ,6);
   if ($this->result) {
    return $this->successful($substr);
   } else {
    return $this->fail($substr);
   }
  
}
/**
*prepare action
*/
public function prepare($sql) {
   $this->prepare = $this->pdo->prepare($sql);
   $this->setChars();
   $this->prepare->execute();
   while($this->rowz = $this->prepare->fetch()) {
   
    return $this->row;
   
   }
}
/**
*USE transaction
*/
public function transaction($sql) {
  
   $this->begin();
   $this->result = $this->pdo->exec($sql);
   if ($this->result) {
    $this->commit();
   } else {
    $this->rollback();
   }
}
/**
*start transaction
*/
private function begin() {
   $this->beginTransaction = $this->pdo->beginTransaction();
   return $this->beginTransaction;
}
/**
*commit transaction
*/
private function commit() {
   $this->commit = $this->pdo->commit();
   return $this->commit;
}
/**
*rollback transaction
*/
private function rollback() {
   $this->rollback = $this->pdo->rollback();
   return $this->rollback;
}
/**
*base query
*/
private function base_query($sql) {
   $this->setChars();
   $this->query = $this->pdo->query($sql);
   return $this->query;
  
}
/**
*set chars
*/
private function setChars() {
  
   $this->char = $this->pdo->query("SET NAMES 'UTF8'");
   return $this->char;
}
    
/**
*process sucessful action 
*/
private function successful($params){
  
   return "The ".$params." action is successful";
}
/**
*process fail action
*/
private function fail($params){
   return "The ".$params." action is fail";
}
/**
*process exception action
*/
private function setExceptionError($getMessage, $getLine ,$getFile) {
   echo "Error message is ".$getMessage."<br /> The Error in ".$getLine." line <br /> This file dir on ".$getFile;
    exit();
}
}


创建参数数组
$db = array(
    'dsn'=>'pgsql:host=localhost;port=5432;dbname=test',
    'user'=>'root',
    'password'=>'',
    );
注意 尽量用单引号。
pdo类内容
<?php
class DAL {
    public $dsn;
    public $user;
    public $password;
    private static $__queries=0;
    private static $__PDO=null;
    private static $__PaDO=null;
    function __construct($dsn,$user,$password){
       
        try {
            self::$__PDO = new PDO($dsn, $user, $password);
        } catch(PDOException $e) {
            print_r($e);
        }
    }
    /**
     *
     * @param <type> $sql
     * @param <type> $params
     * @return <type> $stmt
     *
     *
     * $stmt = $dbh->prepare("INSERT INTO REGISTRY (name, value) VALUES (?, ?)");
     * $stmt->bindParam(1, $name);
     * $stmt->bindParam(2, $value);
     * // insert one row
     * $name = 'one';
     * $value = 1;
     * $stmt->execute();
     *
     * $sql = "select * from SampleMarry where UserId=?";
     * $arr[0] = $userId;
     * return DAL::getall($sql,$arr);
     */
    private static function execute($sql,$params) {
        try {
            $stmt=self::$__PDO->prepare($sql);
            if($params!==null) {
                if(is_array($params) || is_object($params)) {
                    $i=1;
                    foreach($params as $param) {
                        $stmt->bindValue($i++,$param);
                    }
                } else {
                    $stmt->bindValue(1,$params);
                }
            }
            if($stmt->execute()) {
                self::$__queries++;
                return $stmt;
            } else {
                $err=$stmt->errorInfo();
                throw new PDOException($err[2],$err[1]);
            }
        } catch(PDOException $e) {
            print_r($e);
        }
    }
    /************************************************************************
    public function start
     ************************************************************************/
    //for insert start
    public static function insert($sql,$params=null) {
        $stmt=self::execute($sql,$params);
        return self::$__PDO->lastInsertId();
    }
    /**
     *
     * @param <type> $table
     * @param <type> $entity
     * @return <type>
    $data=new stdClass;
    $data->SampleBook_Id=$bookid;
    $data->UserId=$userId;
    $data->EducationType="School";
    $data->Name=$scoolname;
    $data->Subject="";
    $data->AttendedDate=strtotime($startdate);
    $data->FinishedDate=strtotime($enddate);
    return DAL::createRecord("SampleEducation",$item);
     */
    public static function createRecord($table,$entity) {
        $f=array();
        $v=array();
        $c=0;
        foreach($entity as $prop=>$val) {
            $f[]=$prop;
            $v[]=$val;
            $c++;
        }
        $sql="INSERT INTO $table (".implode(",",$f).") VALUES(".trim(str_repeat('?,',$c),",").")";
        return self::insert($sql,$v);
    }
    //for insert end
    //for delete start
    /**
     *
     * @param <type> $sql
     * @param <type> $params
     * @return <type>
    $sql="delete from SampleLived where id=?";
    $arr[] = $id;
    DAL::delete($sql,$arr);
     */
    public static function delete($sql,$params=null) {
        $stmt=self::execute($sql,$params);
        return $stmt->rowCount();
    }
    public static function remove($sql,$params=null) {
        $stmt=self::execute($sql,$params);
        return $stmt->rowCount();
    }
    //for delete end
    //for update start
    public static function update($sql,$params=null) {
        $stmt=self::execute($sql,$params);
        return $stmt->rowCount();
    }
    public static function updateRecord($table,$entity,$pkdfiledname) {
        $f=array();
        $v=array();
        foreach($entity as $prop=>$val) {
            if($prop==$pkdfiledname)continue;
            $f[]=$prop."=?";
            $v[]=$val;
        }
        $v[]=$entity->$pkdfiledname;
        $sql="UPDATE $table SET ".trim(implode(',',$f),',')." WHERE $pkdfiledname=?";
        return self::update($sql,$v);
    }
    //for update end
    //for query start
    public static function query($sql,$params=null) {
        $stmt=self::execute($sql,$params);
        return new DBRecordset($stmt);
    }
    public static function getOne($sql,$params=null) {
        $stmt=self::execute($sql,$params);
        return $stmt->fetchColumn();
    }
    public static function getRow($sql,$params=null) {
        $stmt=self::execute($sql,$params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public static function getAll($sql,$params=null) {
        $stmt=self::execute($sql,$params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    //for query end
    //for Transaction start
    public static function beginTransaction() {
        return self::$__PDO->beginTransaction();
    }
    public static function rollBack() {
        return self::$__PDO->rollBack();
    }
    public static function commit() {
        return self::$__PDO->commit();
    }
    //for Transaction end
    public static function pagination($countSql,$selectSql,$params,&$pageNow,$pageSize,&$count) {
        if($pageNow<=0)$pageNow=1;
        $count=self::getOne($countSql,$params);
        $pageCount=ceil($count/$pageSize);
        if($pageNow>$pageCount)$pageNow=$pageCount;
        if($pageNow<=0)$pageNow=1;
        $offset=($pageNow-1)*$pageSize;
        $sql=$selectSql.' LIMIT '.$offset.','.$pageSize;
        return self::query($sql,$params);
    }
    /************************************************************************
    public function end
     ************************************************************************/
}
class DBRecordset {
    private $PDOStatement;
    public function __construct(&$stmt) {
        $this->PDOStatement=&$stmt;
        $this->PDOStatement->setFetchMode(PDO::FETCH_OBJ);
    }
    public function next() {
        return $this->PDOStatement->fetch();
    }
    public function count() {
        //for mysql PDOStatement will return the number of rows in the resultset
        return $this->PDOStatement->rowCount();
    }
    public function getAllRows() {
        return $this->PDOStatement->fetchAll();
    }
    public function columnCount() {
        return $this->PDOStatement->columnCount();
    }
    public function free() {
        $this->PDOStatement=null;
    }
    public function __destruct() {
        $this->free();
    }
    
    <http://blog.csdn.net/qq635785620/article/details/11284591>
}
?>
3
调用pdo类连接数据库
include config
include pdo
$link = new DAL($db['dsn'],$db['user'],$db['password']);
