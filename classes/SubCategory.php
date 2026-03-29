<?php

/**
 * Класс для обработки категорий статей
 */

class SubCategory
{
    // Свойства

    /**
    * @var int ID подкатегории из базы данных
    */
    public $id = null;


    /**
    * @var string Название категории
    */

    public $categories_name = null;

      /**
    * @var string id категории
    */

    public $categories_id = null;

    /**
    * @var string Название подкатегории
    */
    public $name = null;

    /**
    * @var string Короткое описание подкатегории
    */
    public $description = null;


    /**
    * Устанавливаем свойства объекта с использованием значений в передаваемом массиве
    *
    * @param assoc Значения свойств
    */

   

    public function __construct( $data=array() ) {
      if ( isset( $data['id'] ) ) $this->id = (int) $data['id'];
      if ( isset( $data['categories_id'] ) ) 
        $this->categories_id = $data['categories_id'];
      if ( isset( $data['cat_name'] ) ) $this->categories_name = $data['cat_name'];
      if ( isset( $data['name'] ) ) $this->name = $data['name'];
      if ( isset( $data['description'] ) ) $this->description = $data['description'];
    }

    /**
    * Устанавливаем свойства объекта с использованием значений из формы редактирования
    *
    * @param assoc Значения из формы редактирования
    */

    public function storeFormValues ( $params ) {

      // Store all the parameters
      $this->__construct( $params );
    }


    /**
    * Возвращаем объект Category, соответствующий заданному ID
    *
    * @param int ID категории
    * @return Category|false Объект Category object или false, если запись не была найдена или в случае другой ошибки
    */

    public static function getById( $id ) 
    {
        $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
        $sql = "SELECT * FROM subcategories WHERE id = :id";
        $st = $conn->prepare( $sql );
        $st->bindValue(":id", $id, PDO::PARAM_INT);
        $st->execute();
        $row = $st->fetch();
        $conn = null;
        if ($row) 
            return new SubCategory($row);
    }


    /**
    * Возвращаем все (или диапазон) объектов Category из базы данных
    *
    * @param int Optional Количество возвращаемых строк (по умолчаниюt = all)
    * @param string Optional Столбец, по которому сортируются категории(по умолчанию = "name ASC")
    * @return Array|false Двух элементный массив: results => массив с объектами Category; totalRows => общее количество категорий
    */
    public static function getList( $numRows=1000000, $order="name ASC" ) 
    { 
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD);


    $fromPart  = "FROM subcategories";
    $sql = "SELECT subcategories.*, categories.name AS cat_name
             $fromPart 
             LEFT JOIN 
             categories ON subcategories.categories_id = categories.id
             ORDER BY $order LIMIT :numRows";

    $st = $conn->prepare( $sql );
    $st->bindValue( ":numRows", $numRows, PDO::PARAM_INT );
    $st->execute();
    $list = array();

    while ( $row = $st->fetch() ) {
      $subcategory = new SubCategory( $row );
      $list[] = $subcategory;
    }

    // Получаем общее количество категорий, которые соответствуют критериям
    $sql = "SELECT COUNT(*) AS totalRows $fromPart";
    $totalRows = $conn->query( $sql )->fetch();
    $conn = null;
    return ( array ( "results" => $list, "totalRows" => $totalRows[0] ) );
    }


    /**
    * Вставляем текущий объект Category в базу данных и устанавливаем его свойство ID.
    */

    public function insert() {

      // У объекта Category уже есть ID?
      if ( !is_null( $this->id ) ) trigger_error ( "SubCategory::insert(): Attempt to insert a SubCategory object that already has its ID property set (to $this->id).", E_USER_ERROR );

      // Вставляем категорию
      $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
      $sql = "INSERT INTO subcategories ( categories_id, name, description ) VALUES ( :categories_id, :name, :description )";
      $st = $conn->prepare ( $sql );
      $st->bindValue( ":categories_id", $this->categories_id, PDO::PARAM_INT );
      $st->bindValue( ":name", $this->name, PDO::PARAM_STR );
      $st->bindValue( ":description", $this->description, PDO::PARAM_STR );
      $st->execute();
      $this->id = $conn->lastInsertId();
      $conn = null;
    }


    /**
    * Обновляем текущий объект Category в базе данных.
    */

    public function update() {

      // У объекта Category  есть ID?
      if ( is_null( $this->id ) ) trigger_error ( "Category::update(): Attempt to update a Category object that does not have its ID property set.", E_USER_ERROR );

      // Обновляем категорию
      $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
      $sql = "UPDATE subcategories SET categories_id=:categories_id, name=:name, description=:description WHERE id = :id";
      $st = $conn->prepare ( $sql );
      $st->bindValue( ":categories_id", $this->categories_id, PDO::PARAM_INT );
      $st->bindValue( ":name", $this->name, PDO::PARAM_STR );
      $st->bindValue( ":description", $this->description, PDO::PARAM_STR );
      $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
      $st->execute();
      $conn = null;
    }


    /**
    * Удаляем текущий объект Category из базы данных.
    */

    public function delete() {

      // У объекта Category  есть ID?
      if ( is_null( $this->id ) ) trigger_error ( "SubCategory::delete(): Attempt to delete a SubCategory object that does not have its ID property set.", E_USER_ERROR );

      // Удаляем категорию
      $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
      $st = $conn->prepare ( "DELETE FROM subcategories WHERE id = :id LIMIT 1" );
      $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
      $st->execute();
      $conn = null;
    }

}
	  
	

