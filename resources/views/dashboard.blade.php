@extends('layout')

@section('title')
    <title>UrbanWare</title>
@stop

@section('body')
    <div class="dashboard">
        <div class="page-header">
            <h3>Available Warehouses
                <small></small>
            </h3>
        </div>

        @foreach($warehouses as $warehouse)
            <div class="row col-xs-12 col-sm-6 col-md-6 col-lg-6">
                <div class="panel panel-default">
                    <div class="panel-heading">

                        <a class="pull-right" href="/warehouse/edit/{{$warehouse->id}}">
                            <span class="glyphicon glyphicon glyphicon-cog" aria-hidden="true"></span>
                        </a>

                        <h4>{{$warehouse->name}}</h4>
                    </div>
                    <div class="panel-body">

                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                            <a class="center-block" href="/warehouse/{{$warehouse->id}}/sales">
                                <button type="button" class="btn btn-default">
                                    <i class="fa fa-credit-card"></i> Sales
                                </button>
                            </a>
                            <a class="center-block" href="/warehouse/{{$warehouse->id}}/products">
                                <button type="button" class="btn btn-default">
                                    <span class="glyphicon glyphicon-list" aria-hidden="true"></span> Inventory
                                </button>
                            </a>
                            <a class="center-block" href="/warehouse/{{$warehouse->id}}/products/add">
                                <button type="button" class="btn btn-default">
                                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add Products
                                </button>
                            </a>

                            <a class="center-block" href="/warehouse/{{$warehouse->id}}/profit">
                                <button type="button" class="btn btn-default">
                                    <i class="fa fa-line-chart"></i> Profit
                                </button>
                            </a>

                            <a class="center-block" href="/warehouse/checkout/{{$warehouse->id}}">
                                <button type="button" class="btn btn-default">
                                    <i class="fa fa-cart-plus"></i> Checkout
                                </button>
                            </a>
                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-8 col-lg-8">
                            <table class="table table-bordered">
                                <tr>
                                    <td>
                                        Location
                                    </td>

                                    <td>
                                        @if($warehouse->location)
                                            {{$warehouse->location}}
                                        @else
                                            N/A
                                        @endif

                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        Owner
                                    </td>

                                    <td>
                                        @if($warehouse->owner)
                                            {{$warehouse->owner}}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        Products
                                    </td>

                                    <td>
                                        @if($warehouse->products_count)
                                            @if($warehouse->products_count == 1)
                                                {{$warehouse->products_count}} Product
                                            @else
                                                {{$warehouse->products_count}} Products in Stock
                                            @endif

                                        @else
                                            No Stock Available
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        Updated at
                                    </td>

                                    <td>
                                        @if(isset($warehouse->updated))
                                            {{$warehouse->updated}}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        @endforeach
    </div>
@stop