<?php

//phpinfo(); die();

require("config.php");

try {
    initApplication();
} catch (Exception $e) { 
    $results['errorMessage'] = $e->getMessage();
    require(TEMPLATE_PATH . "/viewErrorPage.php");
}


function initApplication()
{
    $action = isset($_GET['action']) ? $_GET['action'] : "";
    switch ($action) {
        case 'archive':
          archive();
          break;
        case 'viewArticle':
          viewArticle();
          break;
        default:
          homepage();
    }
}

function archive() 
{
    $results = [];
    
    $categoryId = ( isset( $_GET['categoryId'] ) && $_GET['categoryId'] ) ? (int)$_GET['categoryId'] : null;
    
    $results['category'] = Category::getById( $categoryId );

    $subcategoryId = ( isset( $_GET['subcategoryId'] ) && $_GET['subcategoryId'] ) ? (int)$_GET['subcategoryId'] : null;
    $results['subcategory'] = SubCategory::getById( $subcategoryId );
    
    $data = Article::getList( 100000, $results['category'] ? $results['category']->id : null, 
                                      $results['subcategory'] ? $results['subcategory']->id : null);
    
    $results['articles'] = $data['results'];
    $results['totalRows'] = $data['totalRows'];
  
  
    $data = Category::getList();
    $results['categories'] = array();
    
    foreach ( $data['results'] as $category ) {
        $results['categories'][$category->id] = $category;
    }

    $data2 = SubCategory::getList();
    $results['subcategories'] = array();
    
    foreach ( $data2['results'] as $subcategory ) {
        $results['subcategories'][$subcategory->id] = $subcategory;
    }


    $data = ArticleUser::getList();
    $results['articleuser'] = array();
    foreach ($data['results'] as $articleuser) { 
        $results['articleuser'][] =$articleuser;
    }


    $data = Users::getList();
    $results['user'] = array();
    foreach ($data['results'] as $user) { 
        $results['user'][$user->userId] = $user;
    }
    
    
    $results['pageHeading'] = $results['category'] ?  $results['category']->name : "Article Archive";
    $results['pageTitle'] = $results['pageHeading'] . " | Widget News";
    
    require( TEMPLATE_PATH . "/archive.php" );
}

function archivesubcategory() 
{
    $results = [];
    
    $categoryId = ( isset( $_GET['categoryId'] ) && $_GET['categoryId'] ) ? (int)$_GET['categoryId'] : null;
    
    $results['category'] = Category::getById( $categoryId );

    $subcategoryId = ( isset( $_GET['subcategoryId'] ) && $_GET['subcategoryId'] ) ? (int)$_GET['subcategoryId'] : null;
    $results['subcategory'] = SubCategory::getById( $subcategoryId );
    
    $data = Article::getList( 100000, $results['category'] ? $results['category']->id : null, 
                                      $results['subcategory'] ? $results['subcategory']->id : null);
    
    $results['articles'] = $data['results'];
    $results['totalRows'] = $data['totalRows'];
    
      
    $results['pageHeading'] = $results['category'] ?  $results['category']->name : "Article Archive";
    $results['pageTitle'] = $results['pageHeading'] . " | Widget News";
    
    require( TEMPLATE_PATH . "/archive.php" );
}


/**
 * Загрузка страницы с конкретной статьёй
 * 
 * @return null
 */
function viewArticle() 
{   
    if ( !isset($_GET["articleId"]) || !$_GET["articleId"] ) {
      homepage();
      return;
    }

    $results = array();
    $articleId = (int)$_GET["articleId"];
    $results['article'] = Article::getById($articleId);
    
    if (!$results['article']) {
        throw new Exception("Статья с id = $articleId не найдена");
    }
    
    $results['category'] = Category::getById($results['article']->categoryId);
    $results['subcategory'] = SubCategory::getById($results['article']->subcategoryId);

     $data = ArticleUser::getList();
    $results['articleuser'] = array();
    foreach ($data['results'] as $articleuser) { 
        if ($articleuser->article_id == $articleId ){
        $results['articleuser'][] =$articleuser;
    }
    }


    $data = Users::getList();
    $results['user'] = array();
    foreach ($data['results'] as $user) { 
        $results['user'][$user->userId] = $user;
    }

    $results['pageTitle'] = $results['article']->title . " | Простая CMS";
    
    require(TEMPLATE_PATH . "/viewArticle.php");
}

/**
 * Вывод домашней ("главной") страницы сайта
 */
function homepage() 
{
    $results = array();
    $data = Article::getList(HOMEPAGE_NUM_ARTICLES);
    $results['articles'] = $data['results'];
    $results['totalRows'] = $data['totalRows'];
    
    $data = Category::getList();
    $results['categories'] = array();
    foreach ( $data['results'] as $category ) { 
        $results['categories'][$category->id] = $category;
    } 

    $data2 = SubCategory::getList();
    $results['subcategories'] = array();
    foreach ( $data2['results'] as $subcategory ) { 
        $results['subcategories'][$subcategory->id] = $subcategory;
    } 


   $data = ArticleUser::getList();
   $results['articleuser'] = array();

   foreach ($data['results'] as $articleuser) {
    $results['articleuser'][] =$articleuser;
  }


    $data = Users::getList();
    $results['user'] = array();
    foreach ($data['results'] as $user) { 
        $results['user'][$user->userId] = $user;
    }
    
    $results['pageTitle'] = "Простая CMS & на PHP";
    
//    echo "<pre>";
//    print_r($data);
//    echo "</pre>";
//    die();
    
    require(TEMPLATE_PATH . "/homepage.php");
    
}