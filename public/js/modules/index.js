doApp.modules.index = {
  index: function(aHash){
    $('#content').html('<h1>Привет на главной странице!</h1>'+
      '<table><tr> <td><a href="/">Главная</a></td> <td><a href="/user">Пользователи</a></td> <td><a href="/index/about">About</a></td> </tr></table>'
    );
  },
  about: function(aHash){
    $('#content').html('<h1>И о проекте...</h1>'+
      '<table><tr> <td><a href="/">Главная</a></td> <td><a href="/user">Пользователи</a></td> <td><a href="/index/about">About</a></td> </tr></table>'
    );
  }
}