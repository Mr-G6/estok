@extends('layout')

@section('title')
    <title>{{$warehouse->name}} - UrbanWare</title>
@stop

@section('warehouse')
    <div class="products">
        <div class="page-header">

            <a class="pull-right" href="/warehouse/{{$warehouse->id}}/transactions">
                <button type="button" class="btn btn-default">
                    <span class="glyphicon glyphicon-stats" aria-hidden="true"></span> Reports
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
                <form class="form-inline">
                    <div class="form-group pull-right">
                        <input type="text"
                               class="form-control"
                               id="product-search"
                               placeholder="Search Product"
                               required>
                    </div>
                </form>
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
                                Rs.{{$product->unit_price * $product->quantity}}
                            </td>
                            <td>
                                {{date_format($product->created_at, 'Y-m-d')}}
                            </td>

                            <td>
                                <div class="btn-group pull-right" role="group" aria-label="...">
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
                                {{
                                    App\Models\Products::where('wh_id','=',$warehouse->id)->sum('unit_price') *
                                    App\Models\Products::where('wh_id','=',$warehouse->id)->sum('quantity')
                                }}
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
@stop