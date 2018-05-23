(function(){
  var reportContainer = document.createElement('div');
  reportContainer.setAttribute('class', 'report-hidden');
  document.body.appendChild(reportContainer);
  var reportError = function(err) {
    reportContainer.setAttribute('class', 'leit-report report-error');
    reportContainer.innerText = err;
    resetReport();
  };
  var reportSuccess = function(msg) {
    reportContainer.setAttribute('class', 'leit-report report-success');
    reportContainer.innerText = msg;
    resetReport();
  };
  var resetReport = function(){
    setTimeout(function(){
      reportContainer.setAttribute('class', 'report-hidden');
    }, 2000);
  };
  var pushUrl = '{{ route('userItemPush', $channel->getName()) }}';
  var loginCheckUrl = '{{ route('loginChekTxt') }}';
  var notif = {
    link: window.location.href,
    title: document.title || "No title",
    description: "Saved webpage"
  };
  var metaDescriptions = [].slice.call(document.getElementsByTagName('meta')).filter(function(m){
    var prop = m.getAttribute('name') || m.getAttribute('property');
    var val = m.getAttribute('name') || m.getAttribute('property');
    return ['description', 'twitter:description', 'og:description']
      .indexOf(prop) !== -1;
  });
  if (metaDescriptions.length) {
    notif.content = metaDescriptions[0].getAttribute('content');
  } else {
    notif.content = "No content provided";
  }
  var microAjax = function request(url, data, callback, errHandler) {
    var qs = data ? '?' + Object.keys(data).map(function(k){
      return k + '=' + encodeURIComponent(data[k]);
    }).join('&') : '';
    var req = new XMLHttpRequest();
    req.open("GET", url + qs, false);
    req.onreadystatechange = function () {
      console.log('this.readyState', this.readyState);
      console.log('this.status', this.status);
      if (this.readyState == 4 && this.status >= 200 && this.status < 300) {
        if (callback) {
          callback(this.responseText);
        }
      } else if (this.readyState == 4) {
        if (errHandler) {
          errHandler(this.responseText, this);
        }
      }
    };
    try {
      req.withCredentials = true;
      req.send();
    } catch(e) {
      console.log('catched !', Object.keys(e.__proto__));
      if (errHandler) {
        errHandler(e.message, req);
      }
    }
  };
  var pushSuccess = function(respTxt) {
    console.log(respTxt);
    reportSuccess("Pushed !");
  };
  var pushError = function(err) {
    reportError("Error ! " + err);
  };
  var ifLoggedIn = function(){
    microAjax(pushUrl, notif, pushSuccess, pushError);
  };
  var ifNotLoggedIn = function() {
    reportError("Not logged in LeitNotif");
  };
  microAjax(loginCheckUrl, null, function(isLogged){
    if (isLogged === 'true') {
      ifLoggedIn();
    } else {
      console.log('isLogged', isLogged);
      ifNotLoggedIn();
    }
  }, pushError);
}());
