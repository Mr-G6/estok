@extends('layout')

@section('title')
    <title>UrbanWare</title>
@stop

@section('dashboard')
    <div class="dashboard">
        <div class="page-header">
            <h3>Available Warehouses
                <small></small>
            </h3>
        </div>

        @foreach($warehouses as $warehouse)
            <div class="row col-xs-12 col-sm-6 col-md-4 col-lg-4">
                <div class="panel panel-default">
                    <div class="panel-heading">

                        <a class="pull-right" href="/warehouse/edit/{{$warehouse->id}}">
                            <span class="glyphicon glyphicon glyphicon-cog" aria-hidden="true"></span>
                        </a>

                        <h4>{{$warehouse->name}}</h4>
                    </div>
                    <div class="panel-body">
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


                        <a href="/warehouse/{{$warehouse->id}}/products">
                            <button type="button" class="btn btn-default">
                                <span class="glyphicon glyphicon-list" aria-hidden="true"></span> Products
                            </button>
                        </a>
                        <a href="/warehouse/{{$warehouse->id}}/products/add">
                            <button type="button" class="btn btn-default">
                                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add Products
                            </button>
                        </a>

                        <a href="/warehouse/{{$warehouse->id}}/transactions">
                            <button type="button" class="btn btn-default">
                                <span class="glyphicon glyphicon-stats" aria-hidden="true"></span> Reports
                            </button>
                        </a>

                        <a href="/warehouse/checkout/{{$warehouse->id}}">
                            <button type="button" class="btn btn-default">
                                <span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span> Checkout
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@stop