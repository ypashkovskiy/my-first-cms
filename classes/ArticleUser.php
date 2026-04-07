<?php


/**
 * Класс для обработки статей
 */
class ArticleUser
{
    // Свойства
    /**
    * @var int ID статей из базы данны
    */
    public $article_id= null;

     // Свойства
    /**
    * @var int ID пользлвателя из базы данны
    */
    public $user_id= null;

   
    
    /**
     * Создаст объект статьи
     * 
     * @param array $data массив значений (столбцов) строки таблицы статей и пользователей
     */
    public function __construct($data=array())
    {
        
      if (isset($data['article_id'])) {
          $this->article_id = (int) $data['article_id'];
      }
      
      if (isset( $data['user_id'])) {
          $this->user_id = (string) $data['user_id'];     
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


   
    public static function getById($article_id, $user_id=null) {
        $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );

        $fromtable ="FROM article_user";
        if ($article_id && $user_id){
            $condition = "WHERE article_id = :article_id and user_id =:user_id";
        }else {
            $condition = "WHERE article_id = :article_id";
        }

        
        $sql = "SELECT *  $fromtable $condition";
        $st = $conn->prepare($sql);
        $st->bindValue( ":article_id", $article_id, PDO::PARAM_INT );
        if ($user_id) {
                    $st->bindValue( ":user_id", $user_id, PDO::PARAM_INT );
        }

        $st->execute();

        $list = []; 
         while ($row = $st->fetch()) {
           $article = new ArticleUser($row);
           // $article = $row;
            $list[] = $article;
        }
        $conn = null; 

        if ($list){
          return (array(
            "results" => $list, 
           ) 
          
        );}
    }


   
    public static function getList() 
    {
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $fromPart = "FROM article_user";
        
       
        
            //$articles_user = "WHERE articles_user";

            $sql ="SELECT *  $fromPart";
                      
        
        $st = $conn->prepare($sql);
               
        $st->execute(); // выполняем запрос к базе данных
        $list = array();

        
        

        while ($row = $st->fetch()) {
            $article = new ArticleUser($row);
            $list[] = $article;
        }
         
        $conn = null; 

        
          return (array(
            "results" => $list, 
           ) 
        );
        
    }

   
    public function insert() {

        // Есть уже у объекта Article ID?
        if ( is_null( $this->article_id ) ) trigger_error ( "Article::insert(): Attempt to insert an Article object that already has its ID property set (to $this->article_id).", E_USER_ERROR );
 
        // Есть уже у объекта User ID?
        if ( is_null( $this->user_id ) ) trigger_error ( "User::insert(): Attempt to insert an User object that already has its ID property set (to $this->user_id).", E_USER_ERROR );
  

        // Вставляем статью
        $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
        $sql = "INSERT INTO article_user( article_id, user_id  )
                VALUES ( :article_id, :user_id )";
        $st = $conn->prepare ( $sql );
        $st->bindValue( ":article_id", $this->article_id, PDO::PARAM_INT );
        $st->bindValue( ":user_id", $this->user_id, PDO::PARAM_INT );
        
        $st->execute();
        $conn->lastInsertId();
        $conn = null;
    }

    /**
    * Обновляем текущий объект статьи в базе данных
    */
   /* public function update($value) {

      
      if ( is_null( $this->article_id ) ) trigger_error ( "ArticleUser::update(): "
              . "Attempt to update an ArticleUser object "
              . "that does not have its ID property set.", E_USER_ERROR );

      // Обновляем статью
      $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
      $sql = "UPDATE article_user SET user_id=:value WHERE article_id = :article_id and user_id = :user_id"; 
      $st = $conn->prepare( $sql );
      $st->bindValue( ":user_id", $this->user_id, PDO::PARAM_INT );
      $st->bindValue( ":article_id", $this->article_id, PDO::PARAM_INT );
      $st->bindValue( ":value", $value, PDO::PARAM_INT );
      $st->execute();
      $conn = null;
    }*/


    /**
    * Удаляем текущий объект статьи из базы данных
    */
    public function delete() {

      // Есть ли у объекта статьи ID?
      if (( is_null( $this-> article_id) )&&( is_null( $this-> user_id) )) trigger_error ( "ArticleUser::delete(): Attempt to delete an Article object that does not have its ID property set.", E_USER_ERROR );

      // Удаляем статью
      $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
      $st = $conn->prepare ( "DELETE FROM article_user WHERE  article_id= :article_id and user_id=:user_id" );
      $st->bindValue( ":article_id", $this->article_id, PDO::PARAM_INT );
      $st->bindValue( ":user_id", $this->user_id, PDO::PARAM_INT );
      $st->execute();
      $conn = null;
    }

}
