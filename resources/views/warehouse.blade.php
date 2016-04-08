@extends('layout')

@section('title')
    <title>{{$warehouse->name}} - UrbanWare</title>
@stop

@section('body')
    <div class="products">
        <div class="page-header">

            <a class="pull-right" href="/warehouse/{{$warehouse->id}}/sales">
                <button type="button" class="btn btn-default">
                    <i class="fa fa-credit-card"></i></span> Sales
                </button>
            </a>

            <a class="pull-right" href="/warehouse/{{$warehouse->id}}/profit">
                <button type="button" class="btn btn-default">
                    <span class="glyphicon glyphicon-stats" aria-hidden="true"></span> Profit
                </button>
            </a>

            <a class="pull-right" href="/warehouse/checkout/{{$warehouse->id}}">
                <button type="button" class="btn btn-default">
                    <span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span> Checkout
                </button>
            </a>

            <a class="pull-right" href="/warehouse/{{$warehouse->id}}/products/add">
                <button type="button" class="btn btn-default">
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add Products
                </button>
            </a>

            <h3>{{$warehouse->name}}
                <small>{{$warehouse->location}}</small>
            </h3>
        </div>

        @if(count($products))
            <div class="product-search">
                <input type="text"
                       class="form-control pull-right"
                       id="product-search"
                       placeholder="Search Product"
                       required>
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>
                            # ID
                        </th>
                        <th>
                            Name
                        </th>
                        <th>
                            Quantity
                        </th>
                        <th>
                            Unit Price
                        </th>
                        <th>
                            Total Cost
                        </th>
                        <th>
                            Added at
                        </th>
                        <td>

                        </td>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td>
                                {{$product->id}}
                            </td>
                            <td class="product-search-name" data-name="{{$product->name}}">
                                {{$product->name}}
                            </td>
                            <td>
                                {{$product->quantity}}
                            </td>
                            <td>
                                Rs.{{$product->unit_price}}
                            </td>
                            <td>
                                Rs.{{$product->total_cost}}
                            </td>
                            <td>
                                {{date_format($product->created_at, 'Y-m-d')}}
                            </td>

                            <td>
                                <div class="btn-group pull-right" role="group" aria-label="...">
                                    <a class="product-details" data-product-name="{{$product->name}}" data-wh-id="{{$product->wh_id}}" href="#">
                                        <button type="button" class="btn btn-default">
                                            <span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Details
                                        </button>
                                    </a>

                                    <a href="/warehouse/{{$warehouse->id}}/products/edit/{{$product->id}}">
                                        <button type="button" class="btn btn-default">
                                            <span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Edit
                                        </button>
                                    </a>

                                    <a href="/warehouse/{{$warehouse->id}}/products/delete/{{$product->id}}">
                                        <button type="button" class="btn btn-default">
                                            <span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Delete
                                        </button>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td>
                            <b>
                                Total
                            </b>
                        </td>
                        <td>
                        </td>
                        <td>
                            <b>
                                {{App\Models\Products::where('wh_id','=',$warehouse->id)->sum('quantity')}} Items
                            </b>
                        </td>
                        <td>
                            <b>
                                Rs.{{App\Models\Products::where('wh_id','=',$warehouse->id)->sum('unit_price')}}
                            </b>
                        </td>
                        <td>
                            <b>
                                Rs.
                                {{$total_cost}}
                            </b>
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-warning" role="alert">No Products Available in {{$warehouse->name}}.</div>
        @endif
    </div>

    <div class="modal fade" id="product-dt-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="product-dt-title">Checkout Products List</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">

                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@stop