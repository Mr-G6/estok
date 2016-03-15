@extends('layout')

@section('title')
    <title>Checkout - UrbanWare</title>
@stop

@section('dashboard')
    <div class="checkout" data-wh-id="{{$warehouse->id}}" data-token="{{csrf_token()}}">
        <div class="page-header">
            <a class="pull-right" href="/warehouse/{{$warehouse->id}}/transactions">
                <button type="button" class="btn btn-default">
                    <span class="glyphicon glyphicon-stats" aria-hidden="true"></span> Reports
                </button>
            </a>

            <a class="pull-right" href="/warehouse/{{$warehouse->id}}/products">
                <button type="button" class="btn btn-default">
                    <span class="glyphicon glyphicon-list" aria-hidden="true"></span> Products
                </button>
            </a>

            <a class="pull-right" href="/warehouse/{{$warehouse->id}}/products/add">
                <button type="button" class="btn btn-default">
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add Products
                </button>
            </a>

            <h3> Checkout
                <small>{{$warehouse->name}}</small>
            </h3>
        </div>

        <div class="panel panel-default">
            <div class="panel-body">
                <div id="checkout-form">
                    <div class="form-group col-xs-12 col-sm-4 col-md-4 col-lg-3">
                        <label for="c_p_name"> Name </label>
                        <input type="text"
                               class="form-control"
                               id="c_p_name"
                               placeholder="Product Name"
                               required>
                    </div>

                    <div class="form-group col-xs-12 col-sm-4 col-md-4 col-lg-3">
                        <label for="c_p_quantity"> Quantity </label>
                        <input type="text"
                               class="form-control"
                               id="c_p_quantity"
                               placeholder="Product Quantity"
                               required>
                    </div>

                    <div class="form-group col-xs-12 col-sm-4 col-md-4 col-lg-3">
                        <label for="c_p_r_price"> Retail Price </label>
                        <input type="text"
                               class="form-control"
                               id="c_p_r_price"
                               placeholder="Retail Price"
                               required>
                    </div>

                    <div class="form-group col-xs-12 col-sm-4 col-md-4 col-lg-3">
                        <label for="c_p_unit_price"> Unit Price </label>
                        <input type="text"
                               class="form-control"
                               id="c_p_unit_price"
                               placeholder="Unit Price" readonly
                               required>
                    </div>

                    <div class="form-group col-xs-12 col-sm-4 col-md-4 col-lg-3">
                        <button type="submit" id="product-add" class="btn btn-default">Add Product</button>
                    </div>
                </div>
            </div>
        </div>
        <div id="c_p_error" class="alert alert-danger" role="alert"></div>

        <div class="table-responsive">
            <table id="checkout-list" class="table table-bordered">
                <tr>
                    <th>
                        Name
                    </th>
                    <th>
                        Quantity
                    </th>
                    <th>
                        Retail Price
                    </th>
                    <th>
                        Unit Price
                    </th>
                    <th>
                        Total Cost
                    </th>
                    <td>
                        Total Retail
                    </td>
                    <td>
                        Actions
                    </td>
                </tr>
            </table>
        </div>
        <div class="panel panel-default checkout-actions">
            <div class="panel-body">
                <table class="table table-bordered">
                    <tr>
                        <td>
                            <b>Total Items :</b> <span class="total-items"></span>
                        </td>
                        <td>
                            <b>Total Cost :</b> <span class="cost-total"></span>
                        </td>
                        <td>
                            <b>Total Retail :</b> <span class="retail-total"></span>
                        </td>
                        <td>
                            <button id="go-checkout" class="btn btn-default center-block">Confirm Checkout</button>
                        </td>
                    </tr>
                </table>

            </div>
        </div>
    </div>

    <div class="modal fade checkout-confirmation" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Checkout Status</h4>
                </div>
                <div class="modal-body">
                    <p class="message"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@stop