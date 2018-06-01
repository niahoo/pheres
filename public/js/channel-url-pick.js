;(function () {

var el = domvm.defineElement

var ChannelUrlPickView = function(){
  return {
    render: function (vm, data) {
      if (data.readClients.length === 0) {
        return this.renderNoClient()
      } else {
        return this.renderWidget(vm, data)
      }
    },
    renderWidget: function (vm, data) {
      return el('div', [
        ((data.selectedClient && data.selectedFormat)
          ? this.renderUrl(vm, data)
          : ''),
        el('br'),
        this.renderClientPick(vm, data),
        this.renderFormatPick(vm, data)
      ])
    },
    renderNoClient: function (vm, data) {
      return el('p.alert.alert-danger', "Feed is not available as no API client is allowed to read it. Please create the required client.")
    },
    renderUrl: function (vm, data) {
      var client = data.readClients.filter(function(candidate){
        return candidate.id === data.selectedClient
      }).pop()
      var url = data.channelUrl
        + '/' + data.selectedFormat
        + '?api_token=' + client.api_key
      return el('div', [
        el('h5', "Feed URL"),
        el('a', {href: url}, url)
      ])
    },
    renderClientPick: function (vm, data) {
      return this.renderPick(vm, data, {
        group: 'feedClient',
        items: data.readClients,
        title: "Choose API Client",
        idKey: 'id',
        nameKey: 'name',
        update: setApiClient,
        current: data.selectedClient
      })
    },
    renderFormatPick: function (vm, data) {
      return this.renderPick(vm, data, {
        group: 'feedFormat',
        items: data.formats,
        title: "Choode output format",
        idKey: 'id',
        nameKey: 'name',
        update: setFeedFormat,
        current: data.selectedFormat
      })
    },
    renderPick: function(vm, data, opts) {
      vm.state = vm.state || {}
      var pickerKey = opts.group + '__' + 'pickerState'
      if (! vm.state[pickerKey]) {
        vm.state[pickerKey] = {
          previousId: null
        }
      }
      var self = vm.state[pickerKey]
      function handleClick(e, input) {
        var newId = input.data.id
        if (newId !== self.previousId) {
          self.previousId = newId
          opts.update(newId)
        }
      }
      return [
        el('h5', opts.title || "Pick"),
        el('ul', {
          onclick: {'input': handleClick}
        }, [
          opts.items.map(function(item){
            return el('li', [
              el('input.aaa', {
                  type: 'radio',
                  name: opts.group,
                  _data: {id: item[opts.idKey]},
                  id: opts.group + '_' + item[opts.idKey],
                  checked: item[opts.idKey] === opts.current
                }),
              ' ',
              el('label', {
                  for: opts.group + '_' + item[opts.idKey]
                },
                item[opts.nameKey])
            ])
          })
        ])
      ]
    }
  }
}

var state = {
  readClients: [],
  formats: [
    {id: 'atom', name: "Atom"},
    {id: 'rss', name: "RSS"}
  ],
  selectedClient: null,
  selectedFormat: 'atom',
}

function update(patchState) {
  console.log('patchState', patchState)
  Object.keys(patchState).forEach(function(k){
    state[k] = patchState[k]
  })
  var sync = true
  vm.update(state, sync)
}

function setApiClient(id) {
  update({selectedClient: id})
}
function setFeedFormat(id) {
  update({selectedFormat: id})
}

var vm = domvm.createView(ChannelUrlPickView, state)

vm.mount(document.getElementById('channel-url-pick-app'))

window.channelUrlPick = {
  init: function(data) {
    console.log('data', JSON.stringify(data, 0, '  '))
    var state = {
      readClients: data.readClients || [],
      channelUrl: data.channelUrl
    }
    update(state)
    if (state.readClients.length) {
      setApiClient(state.readClients[0].id)
    }
  }
}

// End wrap
}());
