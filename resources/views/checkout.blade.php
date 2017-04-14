@extends('layout')

@section('title')
    <title>Checkout - {{\App\Config::where('name','=', 'app_name')->first()->value}}</title>
@stop

@section('content')
    <div class="checkout" data-wh-id="{{$inventory->id}}" data-token="{{csrf_token()}}">
        <div class="page-header">

            @include('inventory.header_nav')

            <h3> Checkout
                <small>{{$inventory->name}}</small>
            </h3>
        </div>

        <div class="panel panel-default">
            <div class="panel-body">
                <div id="checkout-form">
                    <div class="form-group col-xs-12 col-sm-4 col-md-4 col-lg-2">
                        <label for="c_p_name"> Name </label>
                        <input type="text"
                               class="form-control"
                               id="c_p_name"
                               placeholder="Product Name"
                               required>
                    </div>

                    <div class="form-group col-xs-12 col-sm-4 col-md-4 col-lg-2">
                        <label for="c_p_quantity"> Quantity </label>
                        <input type="text"
                               class="form-control"
                               id="c_p_quantity"
                               placeholder="Product Quantity"
                               required>
                    </div>

                    <div class="form-group col-xs-12 col-sm-4 col-md-4 col-lg-2">
                        <label for="c_p_quantity"> Discount </label>
                        <input type="text"
                               class="form-control"
                               id="c_p_discount"
                               placeholder="Discount"
                               required>
                    </div>

                    <div class="form-group col-xs-12 col-sm-4 col-md-4 col-lg-2">
                        <label for="c_p_r_price"> Retail Price </label>
                        <input type="text"
                               class="form-control"
                               id="c_p_r_price"
                               placeholder="Retail Price" readonly
                               required>
                    </div>

                    <div class="form-group col-xs-12 col-sm-4 col-md-4 col-lg-2">
                        <label for="c_p_unit_price"> Unit Price </label>
                        <input type="text"
                               class="form-control"
                               id="c_p_unit_price"
                               placeholder="Unit Price" readonly
                               required>
                    </div>

                    <div class="form-group col-xs-12 col-sm-4 col-md-4 col-lg-2">
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
                    <th>
                        Total Retail
                    </th>
                    <th>
                        Discount
                    </th>
                    <th>
                        Actions
                    </th>
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
                            <b>Pay Later :</b> <input type="checkbox" id="pay-later" class="pull-right">
                        </td>
                        <td>
                            <button id="go-checkout" class="btn btn-default center-block">Confirm Checkout</button>
                        </td>
                    </tr>
                </table>

                <div class="form-group col-xs-12 col-sm-4 col-md-4 col-lg-4">
                    <label for="c_p_name"> Buyer Name </label>
                    <input type="text"
                           class="form-control"
                           id="receipt_name"
                           placeholder="Buyer Name"
                           required>
                </div>

                <div class="form-group col-xs-12 col-sm-4 col-md-4 col-lg-4">
                    <label for="c_p_name"> Address </label>
                    <input type="text"
                           class="form-control"
                           id="receipt_address"
                           placeholder="Address"
                           required>
                </div>

                <div class="form-group col-xs-12 col-sm-3 col-md-4 col-lg-4">
                    <label for="c_p_name"> Phone No. </label>
                    <input type="text"
                           class="form-control"
                           id="receipt_no"
                           placeholder="Phone no."
                           required>
                </div>

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