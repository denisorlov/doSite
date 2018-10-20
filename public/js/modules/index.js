doApp.modules.index = {
  index: function(aHash){
    doApp.ajax('/',
      {obj: {num:25.25, bool:false, str:'строка'}},
      null,null, function (data, textStatus, jqXHR) { // вешаем свой обработчик на функцию success
      $('#content').html('from frontend '+(new Date().toLocaleString())+data.result);
    });
  },
  about: function(aHash){
    $('#content').html('<h1>И о проекте...</h1>'+
      '<table><tr> <td><a href="/">Главная</a></td> <td><a href="/user">Пользователи</a></td> <td><a href="/index/about">About</a></td> </tr>'+
      '</table>'+
      '<div id="phpInfo"></div>'
    );

    doApp.ajax('/index/about',null,'text',null, function (data, textStatus, jqXHR) {
      $('#phpInfo').html(data);
    });
  }
}