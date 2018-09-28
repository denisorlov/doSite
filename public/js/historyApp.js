if(!window.jQuery){
  throw new Error( 'jQuery is required');
}

var Application = function(setting) {
  this.setting = setting;
  this.modules = {};
};
Application.prototype = {
  route: function(_path){
    var app = this, path = _path || '',
      pPath = this.parsePath(path),
      module = pPath.module,
      action = pPath.action
    ;
    if(!app.modules[module]){
      var scrpath = this.setting.js_root+this.setting.modules_dir+module+'.js';
      $.getScript( scrpath )
        .done(function( script, textStatus ) {
          app.runAction(path);
        })
        .fail(function( jqxhr, settings, exception ) {
          throw new Error( 'Failed getScript ' + scrpath);
        });
    }else{
      app.runAction(path);
    }
  },
  runAction: function(path){
    var pPath = this.parsePath(path);
    if(!this.modules[pPath.module]){
      throw new Error( 'Application has not module "'+pPath.module+'"');
    }else
    if(typeof this.modules[pPath.module][pPath.action] !== 'function'){
      throw new Error( 'Module "'+pPath.module+'" has not method '+pPath.action);
    }else{
      this.modules[pPath.module][pPath.action](path);
    }
  },
  parsePath: function(path){
    var i, res = {},
      aPath = path.split('/');
    res.module = aPath[0] || 'index';
    res.action = aPath[1] || 'index';
    for (i=2;i<aPath.length;i+=2){
      res[aPath[i]] = aPath[i+1] || null;
    }
    return res;
  }
};
/// create application
var doApp = new Application({
  js_root:      '/public/js',
  modules_dir:  '/modules/'
});

$( document ).ready(function() {
  $( document ).bind( 'click', function( event ) {
    if(event.target.tagName === 'A'){
      var link = event.target;
      if(window.location.host === link.host){
        history.pushState(null, null, link.href);
        // тут можете вызвать подгрузку данных и т.п.
        var _pathname = link.pathname.substr(1); // without /
        doApp.route(_pathname);
        // не даем выполнить действие по умолчанию
        return false;
      }
    }
  });
  // вешаем событие на popstate которое срабатывает при нажатии back/forward в браузере
  $(window).on('popstate', function(e) {
    // тут можете вызвать подгрузку данных и т.п.
    var _pathname = location.pathname.substr(1); // without /
    doApp.route(_pathname);
  });

  doApp.route(window.location.pathname.substr(1)); // without /
});