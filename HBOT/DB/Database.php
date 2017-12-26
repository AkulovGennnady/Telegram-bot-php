<?php
namespace HBOT\DB;

class Database {

  private static $db = null; // Единственный экземпляр класса, чтобы не создавать множество подключений
  private $mysqli; // Идентификатор соединения
  private $sym_query = "?"; // "Символ значения в запросе"

  /* Получение экземпляра класса. Если он уже существует, то возвращается, если его не было, то создаётся и возвращается (паттерн Singleton) */
  public static function getDB() {
    if (self::$db == null) self::$db = new Database();
    return self::$db;
  }

  /* private-конструктор, подключающийся к базе данных, устанавливающий локаль и кодировку соединения */
  private function __construct() 
  {	  
    require_once 'dbset.php';
	$this -> mysqli = new \mysqli( $host,  $user,  $pass,  $db);
	
	if ($this ->mysqli->connect_error) {
		/*die('Ошибка подключения (' . $this ->mysqli->connect_errno . ') '
            . $this ->mysqli->connect_error);*/
			 $Logger = new \Logger('Database.txt');
			 $Logger -> log('CONNECTION ERROR ('.$this ->mysqli->connect_error .')', $this ->mysqli->connect_errno);
			 die();
			}
    }

  /* Вспомогательный метод, который заменяет "символ значения в запросе" на конкретное значение, которое проходит через "функции безопасности" */
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

  /* SELECT-метод, возвращающий таблицу результатов */
  public function select($query, $params = false) {
    $result_set = $this->doquery($this->getQuery($query, $params));
    if (!$result_set) return false;
    return $this->resultSetToArray($result_set);
  }

  /* SELECT-метод, возвращающий одну строку с результатом */
  public function selectRow($query, $params = false) {
    $result_set = $this->doquery($this->getQuery($query, $params));
    if ($result_set->num_rows != 1) return false;
    else return $result_set->fetch_assoc();
  }

  /* SELECT-метод, возвращающий значение из конкретной ячейки */
  public function selectCell($query, $params = false) {
    $result_set = $this->doquery($this->getQuery($query, $params));
    if ((!$result_set) || ($result_set->num_rows != 1)) return false;
    else {
      $arr = array_values($result_set->fetch_assoc());
      return $arr[0];
    }
  }

  /* НЕ-SELECT методы (INSERT, UPDATE, DELETE). Если запрос INSERT, то возвращается id последней вставленной записи */
  /*Если UPDATE, DELETE, то ворзващается число затронутых строк*/
  public function query($query, $params = false) {
    $success = $this->doquery($this->getQuery($query, $params));
    if ($success) {
      if ($this->mysqli->insert_id === 0) return true;
      else return $this->mysqli->insert_id;
    }
    else return $this->mysqli->affected_rows;
  }

  /* Преобразование result_set в двумерный массив */
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
  /* При уничтожении объекта закрывается соединение с базой данных */
  public function __destruct() {
    if ($this->mysqli) $this->mysqli->close();
  }
}
?>