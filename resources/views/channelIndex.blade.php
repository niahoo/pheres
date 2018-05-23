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

            <h1><small>/p/</small>{{ $channel->getName() }}</h1>

            <div class="card">
                <div class="card-header">Bokmarklet</div>
                <div class="card-body">

                {{--

                  Bookmarklet works as the following to work around a CSP bug in
                  Firefox :
                  - Create a stylesheet with the hs code as a property for a selector
                  - Create an element matching the selector
                  - Select the element in the DOM and eval() its style property

                  see https://stackoverflow.com/questions/7607605/does-content-security-policy-block-bookmarklets/25224109#25224109

                --}}
                <p>
                    <a class="btn btn-primary" id="bookmarklet" href="">Push Page</a>
                </p>
                <script type="text/javascript">
                  var runtime = function() {
                    var sheet = document.createElement("link");
                    sheet.rel = "stylesheet";
                    sheet.href = '{{ route('bookmarkletScriptWithCssExt', $channel->getName()) }}?t='+(new Date).getTime();
                    var span = document.createElement("span");
                    span.id = "leit-inject";
                    sheet.onload = function() {
                        var family = span.currentStyle ? span.currentStyle.fontFamily : document.defaultView.getComputedStyle(span, null).fontFamily;
                        eval(family.replace(/^["']|\\|["']$/g, ""));
                    };
                    document.body.appendChild(sheet);
                    document.body.appendChild(span);
                  }
                  var href = "javascript:("+runtime.toString()+"());";
                  document.getElementById('bookmarklet').href = href;
                </script>
                    <p>
                        Drag this button to your bookmarks bar and you can use
                        it on any web-page to push the page to the channel.
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
