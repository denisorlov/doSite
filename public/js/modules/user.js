doApp.modules.user = {
  index: function(aHash){
    if(doApp.just_loaded_from_server) return;

    $('#content').html(
      '<h1>Привет на странице пользователей!</h1>'+
      '<pre id="user_list"></pre>'
    );

    doApp.ajax('/user/list',null,'json',null, function (data, textStatus, jqXHR) {
      //console.dir(arguments);
      var res = '';
      for(var k in data[1])
        res+= k+': '+data[1][k]+'<br/>'
      $('#user_list').html(res);
    });

  }
}