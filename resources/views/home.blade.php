@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if (session('status'))
                <div class="card">
                    <div class="card-header">Success !</div>

                    <div class="card-body">
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    </div>
                </div>
            @endif

            @if (session('errmsg'))
                <div class="card">
                    <div class="card-header">Error !</div>

                    <div class="card-body">
                        <div class="alert alert-danger">
                            {{ session('errmsg') }}
                        </div>
                    </div>
                </div>
            @endif

            <div class="card">
                <div class="card-header">Api clients</div>

                <div class="card-body">
                    <a href="/clients/add" class="btn btn-primary">Add client</a>
                    @foreach ($apiClients as $apiClient)
                        <hr/>
                        <form class="form" method="POST" action="/clients/{{ $apiClient->id }}/update">
                            @csrf
                            <h3>{{ $apiClient->name }}</h3>

                            <div class="form-group">
                                <input type="text" readonly size="36" value="{{ $apiClient->api_key }}" />
                            </div>
                            <div class="form-check">
                              <input class="form-check-input" name="channel_read" type="checkbox" value="on" id="ckb_channel_read"
                              @if($apiClient->isAuthorized(App\FeedChannel::aclTopic('*', App\FeedChannel::ACL_LEVEL_READ)))
                                checked
                              @endif
                              >
                              <label class="form-check-label" for="ckb_channel_read">
                                Can read feeds
                              </label>
                            </div>

                            <div class="form-check">
                              <input class="form-check-input" name="channel_push" type="checkbox" value="on" id="ckb_channl_push"
                              @if($apiClient->isAuthorized(App\FeedChannel::aclTopic('*', App\FeedChannel::ACL_LEVEL_PUSH)))
                                checked
                              @endif
                              >
                              <label class="form-check-label" for="ckb_channl_push">
                                Can push feeds
                              </label>
                            </div>
                            <button type="submit" name="delete_client" value="delete"
                                class="btn btn-danger">Delete</button>
                            <button type="submit" name="update_client" value="update"
                                class="btn btn-primary">Save</button>
                        </form>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
