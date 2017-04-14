@extends('layout')

@section('title')
    <title>{{$inventory->name}} - {{\App\Config::where('name','=', 'app_name')->first()->value}}</title>
@stop

@section('content')
    <div class="add-product">

        <div class="page-header">
            <h3>Add Product - {{$inventory->name}}
            </h3>
        </div>

        <form id="add-product-form" method="POST">
            <div class="form-group">
                <label for="p_name">Name </label>
                <input type="text"
                       class="form-control"
                       name="p_name" id="p_name"
                       placeholder="Product Name"
                       required>
            </div>

            <div class="form-group">
                <label for="retail_price">Retail Price </label>
                <input type="text"
                       class="form-control"
                       name="retail_price"
                       id="retail_price"
                       placeholder="Product Retail Price"
                       required>
            </div>

            <div class="form-group">
                <label for="unit_price">Unit Price </label>
                <input type="text"
                       class="form-control"
                       name="unit_price"
                       id="unit_price"
                       placeholder="Product Wholesale Price"
                       required>
            </div>

            <div class="form-group">
                <label for="p_quantity">Quantity </label>
                <input type="text"
                       class="form-control"
                       name="p_quantity"
                       id="p_quantity"
                       placeholder="Product Quantity"
                       required>
            </div>

            <div class="form-group">
                <div class="alert alert-danger" id="p_error" role="alert"></div>
            </div>


            <input type="hidden"
                   name="id"
                   id="w_id"
                   value="{{$inventory->id}}">
            <input type="hidden"
                   name="_token"
                   id="token"
                   value="{{csrf_token()}}">

            <button type="submit" class="btn btn-success pull-right">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add Product
            </button>

            <a class="btn btn-warning pull-right" href="/inventory/{{$inventory->id}}/products" role="button">
                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Cancel
            </a>
        </form>
    </div>
@stop