<?php
namespace HBOT\DB;

class Database {

  private static $db = null; // ������������ ��������� ������, ����� �� ��������� ��������� �����������
  private $mysqli; // ������������� ����������
  private $sym_query = "?"; // "������ �������� � �������"

  /* ��������� ���������� ������. ���� �� ��� ����������, �� ������������, ���� ��� �� ����, �� �������� � ������������ (������� Singleton) */
  public static function getDB() {
    if (self::$db == null) self::$db = new Database();
    return self::$db;
  }

  /* private-�����������, �������������� � ���� ������, ��������������� ������ � ��������� ���������� */
  private function __construct() 
  {	  
    require_once 'dbset.php';
	$this -> mysqli = new \mysqli( $host,  $user,  $pass,  $db);
	
	if ($this ->mysqli->connect_error) {
		/*die('������ ����������� (' . $this ->mysqli->connect_errno . ') '
            . $this ->mysqli->connect_error);*/
			 $Logger = new \Logger('Database.txt');
			 $Logger -> log('CONNECTION ERROR ('.$this ->mysqli->connect_error .')', $this ->mysqli->connect_errno);
			 die();
			}
    }

  /* ��������������� �����, ������� �������� "������ �������� � �������" �� ���������� ��������, ������� �������� ����� "������� ������������" */
  private function getQuery($query, $params) {	  
    if ($params) {
	  for ($i = 0; $i < count($params); $i++) {
        $pos = strpos($query, $this->sym_query);
		$params[$i] = htmlspecialchars($params[$i]);
        $arg = "'".$this->mysqli->real_escape_string($params[$i])."'";
        $query = substr_replace($query, $arg, $pos, strlen($this->sym_query));
		}
    }
    return $query;
  }

  /* SELECT-�����, ������������ ������� ����������� */
  public function select($query, $params = false) {
    $result_set = $this->doquery($this->getQuery($query, $params));
    if (!$result_set) return false;
    return $this->resultSetToArray($result_set);
  }

  /* SELECT-�����, ������������ ���� ������ � ����������� */
  public function selectRow($query, $params = false) {
    $result_set = $this->doquery($this->getQuery($query, $params));
    if ($result_set->num_rows != 1) return false;
    else return $result_set->fetch_assoc();
  }

  /* SELECT-�����, ������������ �������� �� ���������� ������ */
  public function selectCell($query, $params = false) {
    $result_set = $this->doquery($this->getQuery($query, $params));
    if ((!$result_set) || ($result_set->num_rows != 1)) return false;
    else {
      $arr = array_values($result_set->fetch_assoc());
      return $arr[0];
    }
  }

  /* ��-SELECT ������ (INSERT, UPDATE, DELETE). ���� ������ INSERT, �� ������������ id ��������� ����������� ������ */
  /*���� UPDATE, DELETE, �� ������������ ����� ���������� �����*/
  public function query($query, $params = false) {
    $success = $this->doquery($this->getQuery($query, $params));
    if ($success) {
      if ($this->mysqli->insert_id === 0) return true;
      else return $this->mysqli->insert_id;
    }
    else return $this->mysqli->affected_rows;
  }

  /* �������������� result_set � ��������� ������ */
  private function resultSetToArray($result_set) {
    $array = array();
    while (($row = $result_set->fetch_assoc()) != false) {
      $array[] = $row;
    }
    return $array;
  }
  
  private function doquery($query)
  {
	$result = $this->mysqli->query($query);
	if(!$result){
		 $Logger = new \Logger('Database.txt');
		 $Logger -> log('QUERY ERROR', $query);
		 return 0;
	} 
	return $result;	
  }
  /* ��� ����������� ������� ����������� ���������� � ����� ������ */
  public function __destruct() {
    if ($this->mysqli) $this->mysqli->close();
  }
}
?>