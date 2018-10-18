doApp.modules.index = {
  index: function(aHash){
    $.ajax({
      url: '/',             // указываем URL и
      dataType : 'json',                     // тип загружаемых данных
      success: function (data, textStatus, jqXHR) { // вешаем свой обработчик на функцию succes
        $('#content').html('from frontend'+data.result);
      }
    }).fail(function(jqXHR, textStatus, errorThrown ) {
      if(jqXHR.responseJSON && jqXHR.responseJSON.error){
        doApp.alert(jqXHR.responseJSON.error);
      }
    })
    ;
  },
  about: function(aHash){
    $('#content').html('<h1>И о проекте...</h1>'+
      '<table><tr> <td><a href="/">Главная</a></td> <td><a href="/user">Пользователи</a></td> <td><a href="/index/about">About</a></td> </tr>'+
      '</table>'+
      '<div id="phpInfo"></div>'
    );
    $.ajax({
      url: '/index/about',             // указываем URL и
      dataType : 'text',                     // тип загружаемых данных
      success: function (data, textStatus) { // вешаем свой обработчик на функцию succes
        $('#phpInfo').html(data);
      }
    });
  }
}