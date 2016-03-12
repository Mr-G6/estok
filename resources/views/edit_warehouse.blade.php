@extends('layout')

@section('title')
    <title>Edit Warehouse - UrbanWare</title>
@stop

@section('edit_warehouse')
    <div class="edit-warehouse">
        <div class="wrapper">
            <form method="POST" action="/warehouse/update">
                <div class="form-group">
                    <label for="name">Warehouse Name </label>
                    <input type="text" class="form-control" name="name" placeholder="Warehouse Name" value="{{$warehouse->name}}" required>
                </div>

                <div class="form-group">
                    <label for="location">Warehouse Location </label>
                    <input type="text" class="form-control" name="location" placeholder="Warehouse Location" value="{{$warehouse->location}}">
                </div>

                <div class="form-group">
                    <label for="owner">Warehouse Owner </label>
                    <input type="text" class="form-control" name="owner" placeholder="Warehouse Owner" value="{{$warehouse->owner}}">
                </div>
                <input type="hidden" name="id" value="{{$warehouse->id}}">
                <input type="hidden" name="_token" value="{{csrf_token()}}">

                <button type="submit"  class="btn btn-default pull-right">Update {{$warehouse->name}}</button>
                <a class="btn btn-default pull-right" href="/" role="button">Cancel</a>
            </form>
        </div>
    </div>
@stop