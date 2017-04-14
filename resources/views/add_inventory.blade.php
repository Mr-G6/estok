@extends('layout')

@section('title')
    <title>Add Inventory - {{\App\Config::where('name','=', 'app_name')->first()->value}}</title>
@stop

@section('content')
    <div class="page-header">
        <h3>Add Inventory
            <small></small>
        </h3>
    </div>

    <div class="config col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <form action="/add/inventory" method="post">
            {{ csrf_field() }}

            <div class="form-group {{ $errors->has('inventory_name') ? ' has-error' : '' }}">
                <label>Inventory Name</label>
                <input type="text" class="form-control" name="inventory_name" placeholder="Inventory name">

                @if ($errors->has('inventory_name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('inventory_name') }}</strong>
                    </span>
                @endif
            </div>
            <button type="submit" class="btn btn-success">Create Inventory</button>
        </form>
    </div>
@stop