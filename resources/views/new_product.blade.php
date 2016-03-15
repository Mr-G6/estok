@extends('layout')

@section('title')
    <title>Add Product - UrbanWare</title>
@stop

@section('edit_warehouse')
    <div class="add-product">
        <div class="wrapper">
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
                       value="{{$id}}">
                <input type="hidden"
                       name="_token"
                       id="token"
                       value="{{csrf_token()}}">

                <button type="submit" class="btn btn-default pull-right">
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add Product
                </button>
                <a class="btn btn-default pull-right" href="/warehouse/{{$id}}/products" role="button">
                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Cancel
                </a>
            </form>
        </div>
    </div>
@stop