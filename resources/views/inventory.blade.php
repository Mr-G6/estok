@extends('layout')

@section('title')
    <title>{{$inventory->name}} - {{\App\Config::where('name','=', 'app_name')->first()->value}}</title>
@stop

@section('content')
    <div class="products">
        <div class="page-header">

            @include('inventory.header_nav')

            <h3>{{$inventory->name}}</h3>
        </div>

        @if($inventory->products->count())
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
                            Retail Price
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
                    @foreach($inventory->products as $product)
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
                                {{$product->retail_price}}
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
                                    <a class="product-details" data-product-id="{{$product->id}}" data-inventory-id="{{$product->inventory_id}}" href="#">
                                        <button type="button" class="btn btn-default">
                                            <span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Details
                                        </button>
                                    </a>

                                    <a href="/inventory/{{$inventory->id}}/products/edit/{{$product->id}}">
                                        <button type="button" class="btn btn-default">
                                            <span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Edit
                                        </button>
                                    </a>

                                    <a href="/inventory/{{$inventory->id}}/products/delete/{{$product->id}}" class="delete-product">
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
                                {{App\Products::where('inventory_id','=',$inventory->id)->sum('quantity')}} Items
                            </b>
                        </td>
                        <td>
                            <b>
                                Rs.{{App\Products::where('inventory_id','=',$inventory->id)->sum('retail_price')}}
                            </b>
                        </td>
                        <td>
                            <b>
                                Rs.{{App\Products::where('inventory_id','=',$inventory->id)->sum('unit_price')}}
                            </b>
                        </td>
                        <td>
                            <b>
                                Rs.
                                {{--{{$total_cost}}--}}
                            </b>
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-warning" role="alert">No Products Available in {{$inventory->name}}.</div>
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