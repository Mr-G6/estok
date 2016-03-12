@extends('layout')

@section('title')
    <title>Add Product - UrbanWare</title>
@stop

@section('edit_warehouse')
    <div class="add-product">
        <div class="wrapper">
            <form id="add-product-form" method="POST" action="/warehouse/product/add">
                <div class="form-group">
                    <label for="p_name">Name </label>
                    <input type="text" class="form-control" name="p_name" id="p_name" placeholder="Product Name" required>
                </div>

                <div class="form-group">
                    <label for="p_price">Wholesale Price </label>
                    <input type="text" class="form-control" name="p_price" id="p_price" placeholder="Product Wholesale Price" required>
                </div>

                <div class="form-group">
                    <label for="p_price">Retail Price </label>
                    <input type="text" class="form-control" name="p_r_price" id="p_r_price" placeholder="Product Retail Price" required>
                </div>

                <div class="form-group">
                    <label for="p_quantity">Quantity </label>
                    <input type="text" class="form-control" name="p_quantity" id="p_quantity" placeholder="Product Quantity" required>
                </div>

                <div class="form-group">
                    <div class="alert alert-danger" id="p_error" role="alert"></div>
                </div>


                <input type="hidden" name="id" id="w_id" value="{{$id}}">
                <input type="hidden" name="_token" id="token" value="{{csrf_token()}}">

                <button type="submit"  class="btn btn-default pull-right">Add Product</button>

            </form>
        </div>
    </div>
@stop