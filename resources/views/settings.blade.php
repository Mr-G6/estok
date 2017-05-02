@extends('layout')

@section('title')
    <title>Settings - {{\App\Config::where('name','=', 'app_name')->first()->value}}</title>
@stop

@section('content')
    <div class="dashboard">
        <div class="page-header">
            <h3>Settings
                <small></small>
            </h3>
        </div>
    </div>

    <div class="config col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <form method="POST" action="/settings">
            <div class="form-group">
                <label for="name">Application Name </label>
                <input type="text"
                       class="form-control"
                       name="name"
                       placeholder="Application Name"
                       value="{{\App\Config::where('name','=', 'app_name')->first()->value}}"
                       required>
            </div>

            <input type="hidden"
                   name="_token"
                   value="{{csrf_token()}}">

            <div class="form-group">
                @if (Session::has('message'))
                    <div class="alert alert-success">
                        <strong>{{Session::get('message')}}</strong>
                    </div>
                @endif
            </div>

            <a class="btn btn-default" href="/" role="button">
                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Cancel
            </a>

            <button type="submit" class="btn btn-success">
                <span class="glyphicon glyphicon-save" aria-hidden="true"></span> Update
            </button>

        </form>
    </div>
@stop