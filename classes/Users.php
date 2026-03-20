<?php


/**
 * Класс для обработки пользователей
 */
class Users
{
    // Свойства
    /**
    * @var int ID пользователей из базы данны
    */
    public $userId = null;

   
    /**
    * @var string Полное название пользователя
    */
    public $userName = null;

    /**
    * @var string Пароль пользователя
    */
    public $password = null;

    
    
     /**
    * boolean показывает разрешение доступа пользователя
    */

    public $active = null;
    
    /**
     * Создаст объект статьи
     * 
     * @param array $data массив значений (столбцов) строки таблицы статей
     */
    public function __construct($data=array())
    {
        
      if (isset($data['UserID'])) {
          $this->userId = $data['UserID'];
      }

      if (isset($data['Username'])) {
          $this->userName = $data['Username'];
      }

      if (isset($data['Password'])) {
          $this->password = $data['Password'];
      }
      
      if (isset($data['Active'])) {
          $this->active =(int) $data['Active'];  
      }
    }


    /**
    * Устанавливаем свойства с помощью значений формы редактирования записи в заданном массиве
    *
    * @param assoc Значения записи формы
    */
    public function storeFormValues ( $params ) {

      // Сохраняем все параметры
      $this->__construct( $params );

   }


    /**
    * Возвращаем объект пользователя соответствующий заданному ID пользователя
    *
    */
    public static function getById($id) {
        $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
        $sql = "SELECT * FROM Users WHERE UserID = :id";
        $st = $conn->prepare($sql);
        $st->bindValue(":id", $id, PDO::PARAM_INT);
        $st->execute();

        $row = $st->fetch();
        $conn = null;
        
        if ($row) { 
            return new Users($row);
        }
    }


   
    public static function getList() 
    {
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $fromPart = "FROM Users";
        

        $sql = "SELECT * $fromPart 
                ORDER BY  Username ASC ";

      
        
        $st = $conn->prepare($sql);
        
        $st->execute(); // выполняем запрос к базе данных
        $list = array();

        while ($row = $st->fetch()) {
            $users = new Users($row);
            $list[] = $users;
        }

        // Получаем общее количество пользователей, которые соответствуют критерию
        $sql = "SELECT COUNT(*) AS totalRows $fromPart";
	    $st = $conn->prepare($sql);
	    $st->execute(); // выполняем запрос к базе данных                    
        $totalRows = $st->fetch();
        $conn = null;
        
        return (array(
            "results" => $list, 
            "totalRows" => $totalRows[0]
            ) 
        );
    }

      public static function userVerification($username, $password) 
    {
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $fromPart = "FROM Users";
        

        $sql = "SELECT * $fromPart 
                 WHERE Username = :username AND Password = :password AND Active = 1 
                 ORDER BY Username ASC";

        $st = $conn->prepare($sql);

        // 2. Привязываем значения
        $st->bindValue(':username', $username, PDO::PARAM_STR);
        $st->bindValue(':password', $password, PDO::PARAM_STR);

        // 3. ОБЯЗАТЕЛЬНО выполняем запрос 
        $st->execute(); //Выполняем запрос к базе данных
      
        $row = $st->fetch();
        
         if ($row) { 
            return new Users($row);
        }
        
    }


    /**
    * Вставляем текущий объект User в базу данных, устанавливаем его ID
    */
    public function insert() {

        // Есть уже у объекта Article ID?
        if ( !is_null( $this->userId ) )
             trigger_error ( "User::insert(): Attempt to insert an User object that already has its ID property set (to $this->userId ).", E_USER_ERROR );

        // Вставляем статью
        $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
        $sql = "INSERT INTO Users ( Username, Password, Active )
                VALUES ( :username, :password, :active )";
        $st = $conn->prepare ( $sql );
        $st->bindValue( ":username", $this->userName, PDO::PARAM_STR );
        $st->bindValue( ":password", $this->password, PDO::PARAM_STR );
        $st->bindValue( ":active", $this->active, PDO::PARAM_INT );
        $st->execute();
        $this->userId  = $conn->lastInsertId();
        $conn = null;
    }

    /**
    * Обновляем текущий объект пользователя в базе данных
    */
    public function update() {

      // Есть ли у объекта статьи ID?
      if ( is_null( $this->userId) ) trigger_error ( "Users::update(): "
              . "Attempt to update an Users object "
              . "that does not have its ID property set.", E_USER_ERROR );

      // Обновляем статью
      $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
      $sql = "UPDATE Users SET Username =:username, Password =:password, Active =:active  WHERE UserID = :id";
      
      $st = $conn->prepare ( $sql );
      $st->bindValue( ":username", $this->userName, PDO::PARAM_STR );
      $st->bindValue( ":password", $this->password, PDO::PARAM_STR );
      $st->bindValue( ":active", $this->active, PDO::PARAM_INT );
      $st->bindValue( ":id", $this->userId, PDO::PARAM_INT );
      $st->execute();
      $conn = null;
    }


    /**
    * Удаляем текущий объект пользователя из базы данных
    */
    public function delete() {

      // Есть ли у объекта статьи ID?
      if ( is_null( $this->userId ) ) trigger_error ( "User::delete(): Attempt to delete an User object that does not have its ID property set.", E_USER_ERROR );

      // Удаляем статью
      $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
      $st = $conn->prepare ( "DELETE FROM Users WHERE UserID = :id LIMIT 1" );
      $st->bindValue( ":id", $this->userId, PDO::PARAM_INT );
      $st->execute();
      $conn = null;
    }

}
