@extends('layout')

@section('title')
    <title>Edit Product - UrbanWare</title>
@stop

@section('content')
    <div class="edit-product">

        <div class="page-header">
            <h3>Edit Product - {{$product->name}}
            </h3>
        </div>

        <div class="wrapper">
            <form id="edit-product-form" method="POST" action="/warehouse/product/add">
                <div class="form-group">
                    <label for="p_name">Name </label>
                    <input type="text"
                           class="form-control"
                           name="p_name"
                           id="p_name"
                           value="{{$product->name}}"
                           data-old-name="{{$product->name}}"
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
                           value="{{$product->retail_price}}"
                           required>
                </div>

                <div class="form-group">
                    <label for="unit_price">Unit Price </label>
                    <input type="text"
                           class="form-control"
                           name="unit_price"
                           id="unit_price"
                           value="{{$product->unit_price}}"
                           placeholder="Unit Price"
                           required>
                </div>

                <div class="form-group">
                    <label for="p_quantity">Quantity </label>
                    <input type="text"
                           class="form-control"
                           name="p_quantity"
                           id="p_quantity"
                           value="{{$product->quantity}}"
                           placeholder="Product Quantity"
                           required>
                </div>

                <div class="form-group">
                    <div class="alert alert-danger" id="error" role="alert"></div>
                </div>

                <input type="hidden"
                       name="id"
                       id="p_id"
                       value="{{$product->id}}"
                       value="">

                <input type="hidden"
                       name="id"
                       id="w_id"
                       value="{{$warehouse->id}}"
                       value="">

                <input type="hidden"
                       name="_token"
                       id="token"
                       value="{{csrf_token()}}">

                <button type="submit" class="btn btn-default pull-right">
                    <span class="glyphicon glyphicon-save" aria-hidden="true"></span> Update Product
                </button>
                <a class="btn btn-default pull-right" href="/inventory/{{$warehouse->id}}/products" role="button">
                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Cancel
                </a>

            </form>
        </div>
    </div>
@stop