@extends('layout')

@section('title')
    <title>Edit Product - UrbanWare</title>
@stop

@section('edit_product')
    <div class="edit-product">
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
                    <label for="p_price">Wholesale Price </label>
                    <input type="text"
                           class="form-control"
                           name="p_price"
                           id="p_price"
                           value="{{$product->price}}"
                           placeholder="Product Wholesale Price"
                           required>
                </div>

                <div class="form-group">
                    <label for="p_price">Retail Price </label>
                    <input type="text"
                           class="form-control"
                           name="p_r_price"
                           id="p_r_price"
                           value="{{$product->retail_price}}"
                           placeholder="Product Retail Price" required>
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
                    <div class="alert alert-danger" id="p_error" role="alert"></div>
                </div>

                <input type="hidden" name="id" id="p_id" value="{{$product->id}}" value="">
                <input type="hidden" name="id" id="w_id" value="{{$warehouse->id}}" value="">
                <input type="hidden" name="_token" id="token" value="{{csrf_token()}}">

                <button type="submit"  class="btn btn-default pull-right">Update Product</button>

            </form>
        </div>
    </div>
@stop