doApp.modules.user = {
  index: function(aHash){
    $('#content').html(
      '<h1>Привет на странице пользователей!</h1>'+
      '<table><tr> <td><a href="/">Главная</a></td> <td><a href="/user">Пользователи</a></td> <td><a href="/index/about">About</a></td> </tr></table>'
    );
  }
}