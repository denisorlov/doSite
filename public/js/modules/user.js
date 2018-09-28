doApp.modules.user = {
  index: function(aHash){
    $('#content').html(
      '<h1>Привет на странице пользователей!</h1>'+
      '<table><tr> <td><a href="/">Главная</a></td> <td><a href="/user">Пользователи</a></td> <td><a href="/index/about">About</a></td> </tr></table>'+
      '<pre id="user_list"></pre>'
    );

    $.ajax({
      url: '/user/list?page=30&sort=desc&only_girl=0',             // указываем URL и
      dataType : 'json',                     // тип загружаемых данных
      success: function (data, textStatus) { // вешаем свой обработчик на функцию succes
        console.dir(arguments);
        var res = '';
        for(var k in data[1])
          res+= k+': '+data[1][k]+'<br/>'
        $('#user_list').html(res);
      }
    });
  }
}