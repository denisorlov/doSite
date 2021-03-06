if(!window.jQuery){
  throw new Error( 'jQuery is required');
}

var Application = function(setting) {
  this.setting = setting;
  this.modules = {};
};
Application.prototype = {
  route: function(_hash){
    var app = this, _hash = _hash || '',
      pHash = this.parseHash(_hash),
      module = pHash.module,
      action = pHash.action
    ;
    if(!app.modules[module]){
      var scrpath = this.setting.js_root+this.setting.modules_dir+module+'.js';
      $.getScript( scrpath )
        .done(function( script, textStatus ) {
          app.runAction(_hash);
        })
        .fail(function( jqxhr, settings, exception ) {
          throw new Error( 'Failed getScript ' + scrpath);
        });
    }else{
      app.runAction(_hash);
    }
  },
  runAction: function(_hash){
    var pHash = this.parseHash(_hash);
    if(!this.modules[pHash.module]){
      throw new Error( 'Application has not module "'+pHash.module+'"');
    }else
    if(typeof this.modules[pHash.module][pHash.action] !== 'function'){
      throw new Error( 'Module "'+pHash.module+'" has not method '+pHash.action);
    }else{
      this.modules[pHash.module][pHash.action](_hash);
    }
  },
  parseHash: function(_hash){
    var i, res = {},
      aHash = _hash.split('/');
      res.module = aHash[0] || 'index';
      res.action = aHash[1] || 'index';
    for (i=2;i<aHash.length;i+=2){
      res[aHash[i]] = aHash[i+1] || null;
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
      if(link.hash.length>0){
        var _hash = link.hash.substr(1); // without #
        if($('a[name="'+_hash+'"]').length < 1){
          doApp.route(_hash);
        }
      }
    }
  });
  doApp.route(window.location.hash.substr(1)); // without #
});