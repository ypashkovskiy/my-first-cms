$(function(){
    
    console.log('Привет, это страый js ))');
    init_get();
    init_post();
    ajax_post();
    ajax_get ();
});

function init_get() 
{
    $('a.ajaxArticleBodyByGet').one('click', function(e){
       
        e.preventDefault();

        var contentId = $(this).attr('data-contentId');
        console.log('ID статьи = ', contentId); 
        showLoaderIdentity();
        $.ajax({
            url:'/ajax/showContentsHandler.php?articleId=' + contentId, 
            dataType: 'json'
        })
        .done (function(obj){
            hideLoaderIdentity();
            console.log('Ответ получен');
            $('li.' + contentId).append(obj);
        })
        .fail(function(xhr, status, error){
            hideLoaderIdentity();
    
            console.log('ajaxError xhr:', xhr); // выводим значения переменных
            console.log('ajaxError status:', status);
            console.log('ajaxError error:', error);
    
            console.log('Ошибка соединения при получении данных (GET)');
        });
        
        return false;
        
    });  
}






function init_post() 
{
    $('a.ajaxArticleBodyByPost').one('click', function(e){
         e.preventDefault();
        var content = $(this).attr('data-contentId');
        showLoaderIdentity();
        $.ajax({
            url:'/ajax/showContentsHandler.php', 
            dataType: 'text',
            data: { articleId: content},
//            converters: 'json text',
            method: 'POST'
        })
        .done (function(obj){
            hideLoaderIdentity();
            console.log('Ответ получен', obj);
            $('li.' + content).append(obj);
        })
        .fail(function(xhr, status, error){
            hideLoaderIdentity();
            console.log('Ошибка соединения с сервером (POST)');
            console.log('ajaxError xhr:', xhr); // выводим значения переменных
            console.log('ajaxError status:', status);
            console.log('ajaxError error:', error);
        });
        
        return false;
        
    });  
}


function ajax_post(){
   $('a.ajaxPost').on('click', function(e) {
    e.preventDefault(); // Предотвращаем переход по ссылке
    
    var $this = $(this);
    var contentId = $this.attr('data-contentId');
    
    showLoaderIdentity();
    
    $.ajax({
        url: '/ajax/showContentsHandler.php',
        method: 'POST',
        dataType: 'text',
        data: { articleId: contentId } // ПЕРЕДАЕМ ID на сервер
    })
    .done(function(obj) {
        hideLoaderIdentity();
        console.log('Ответ получен');
        
        // Вставляем ответ в элемент с классом, соответствующим ID
        $('li.' + contentId).append(obj);
        
        // Опционально: отключаем ссылку после загрузки, если нужно подобие .one()
        $this.off('click').css('cursor', 'default'); 
    })
    .fail(function(xhr, status, error) {
        hideLoaderIdentity();
        console.error('Ошибка:', error);
    });

    return false;
});
};


function ajax_get (){
        $('a.ajaxGet').on('click', function(e) {
    e.preventDefault(); // Предотвращаем переход по ссылке

    var $this = $(this);
    var contentId = $this.data('contentid'); // Получаем data-contentId
    
    console.log('ID статьи = ', contentId);
    showLoaderIdentity();

    $.ajax({
        url: '/ajax/showContentsHandler.php',
        method: 'GET',
        data: { articleId: contentId }, // Передаем параметры объектом (так чище)
        dataType: 'json'
    })
    .done(function(obj) {
        console.log('Ответ получен');
        // Вставляем полученный контент в элемент с классом, соответствующим ID
        $('li.' + contentId).append(obj.html || obj); 
        $this.off('click').css('cursor', 'default'); 
    })
    .fail(function(xhr, status, error) {
        console.log('Ошибка соединения:', error);
    })
    .always(function() {
        hideLoaderIdentity(); // Скрываем лоадер в любом случае
    });
});


}
