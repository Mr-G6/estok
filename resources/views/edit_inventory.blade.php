@extends('layout')

@section('title')
    <title> {{$inventory->name}} - {{\App\Config::where('name','=', 'app_name')->first()->value}}</title>
@stop

@section('content')

    <div class="page-header">
        <h3>Edit Inventory
            <small></small>
        </h3>
    </div>

    <div class="config col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <form method="POST" action="/inventory/update">
            <div class="form-group">
                <label for="name">Inventory Name </label>
                <input type="text"
                       class="form-control"
                       name="name"
                       placeholder="Inventory Name"
                       value="{{$inventory->name}}"
                       required>
            </div>

            <input type="hidden"
                   name="id"
                   value="{{$inventory->id}}">
            <input type="hidden"
                   name="_token"
                   value="{{csrf_token()}}">

            <a class="btn btn-default" href="/" role="button">
                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Cancel
            </a>

            <button type="submit" class="btn btn-success">
                <span class="glyphicon glyphicon-save" aria-hidden="true"></span> Update
            </button>

            <button type="submit" class="btn btn-danger delete-inventory" data-inventory-id="{{$inventory->id}}">
                <span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Delete
            </button>

        </form>
    </div>

@stop