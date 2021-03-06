/**
 * Lobby App object
 */

lobby.app.redirect = function(path){
  window.location = lobby.app.url + path;
};

lobby.app.ajax = function(fileName, data, callback){
  return lobby.ajax(fileName, data, callback, lobby.app.id);
};

/**
 * A Save Data frontend JS function that sends request to server to save the data
 */
lobby.app.save = function(key, value, callback){
  var appID = lobby.app.id;

  /* If the callback given is a function, use it otherwise make a simple function that is of no use */
  var callback = typeof callback == "function" ? callback : function(){};
  var requestURL = lobby.url + "/includes/lib/core/Ajax/saveData.php";
  
  if(key == "" || value == ""){
     callback("bad");
  }else{
    $.post(requestURL, {"appId": appID, "key": key, "value": value, "csrf_token": lobby.csrf_token}, function(data){
      callback(data);
    }).error(function(){
      /* AJAX Request wasn't successful, so make sure the callback is alerted of the error. */
      callback("bad");
    });
  }
};

/* A Remove Data frontend JS function that sends request to server to remove the data  */
lobby.app.remove = function(key, callback){
  var appID = lobby.app.id;

  /* If the callback given is a function, use it otherwise make a simple function that is of no use */
  var callback = typeof callback == "function" ? callback : function(){};
  var requestURL = lobby.url + "/includes/lib/core/Ajax/removeData.php";
   if(key == ""){
     callback("bad");
  }else{
    $.post(requestURL, {"appId": appID, "key": key, "csrf_token": lobby.csrf_token}, function(data){
     callback(data);
    }).error(function(){
     /* AJAX Request wasn't successful, so make sure the callback is alerted of the error. */
     callback("bad");
    });
  }
};
