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
                <div class="card-header">Bokmarklet</div>
                <div class="card-body">
<pre>

                    @todo
                        - List all api clients that can push to this channel
                        - Show a warning that if the api client is modified
                          (loses the right to push to the channel, the
                          bookmarklet will not work no more).
                        - Pré sélectionner le premier api client de la liste.
                          Par défaut, le premier est celui qui n'a pas le droit
                          en lecture, s'il existe.
                        - Proposer de sélectionner un autre api client si
                          disponible.
                        - Si aucun client existe, afficher une erreur et
                          indiquer qu'il faut en créer un, avec un lien vers le
                          dashboard.
                        - Afficher un texte indiquant comment glisser le
                          bookmarklet.
                        - à l'initialisation et lors du clic sur un api client,
                          render() le bookmarklet.

</pre>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
