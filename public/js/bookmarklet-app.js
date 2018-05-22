;(function () {

var el = domvm.defineElement

var BookmarkletView = function(){
  return {
    render: function (vm, data) {
      if (data.apiClients.length === 0) {
        return this.renderNoClient()
      } else {
        return this.renderWidget(vm, data)
      }
    },
    renderWidget: function (vm, data) {
      return el('div', [
        el('h4', "Select an API client"),
        this.renderClientSelection(vm, data),
        el('h4', "SelectedClient"),
        this.renderClient(vm, data),
        this.renderClientWarnings(vm, data),
        el('h4', "Button"),
        this.renderButton(vm, data)
      ])
    },
    renderClientSelection: function (vm, data) {
      return data.apiClients.map(function(client, index) {
        var id = 'key' + Math.random()
        return el('div.form-check', [
          el('input.form-check-input[name=bm_client][type=radio]', {
            id: id,
            checked: data.selectedClientIndex === index,
            onchange: [setApiClient, index]
          }),
          el('label.form-check-label', {for: id}, client.name)
        ])
      })
    },
    renderClient: function (vm, data) {
      var client = data.apiClients[data.selectedClientIndex]
      return el('h5', client.name)
    },
    renderClientWarnings: function (vm, data) {
      var client = data.apiClients[data.selectedClientIndex]
      var lis = client.warnings.map(function(warn){
        switch (warn) {
          case 'read-channel':
            return "This client can also read the current channel"
          case 'push-any':
            return "This client can also push to any other chanel"
          case 'read-any':
            return "This client can read any of your channels"
        }
      }).map(function(text){
        return el('li.warning', text)
      })
      return lis.length ? el('ul', lis) : null
    },
    renderNoClient: function (vm, data) {
      return el('p.alert.alert-danger', "Bookmarklet is not available as no API client is allowed to push to this channel. Please create the required client.")
    },
    renderButton: function (vm, data) {
      var client = data.apiClients[data.selectedClientIndex]
      var buttonText = "Push page"
      var scriptUrl = window.location.href + '/'+ client.api_key +'/bookmarklet-run.js'
      var source = "javascript:(function(){document.body.appendChild(document.createElement('script')).src='"+scriptUrl+"';})();"
      return el('a.btn.btn-primary', {href: source}, buttonText)
    }
  }
}

var state = {
  apiClients: window.bookmarkletAppData.apiClients,
  selectedClientIndex: 0
}

function setApiClient(index) {
  state.selectedClientIndex = index
  render(state)
}

var vm = domvm.createView(BookmarkletView, state);

function render(state) {
  var sync = true
  vm.update(state, sync)
}


vm.mount(document.getElementById('bookmarklet-app'))


// End wrap
}());
