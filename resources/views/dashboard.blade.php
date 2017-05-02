@extends('layout')

@section('title')
    <title>{{\App\Config::where('name','=', 'app_name')->first()->value}}</title>
@stop

@section('content')
    <div class="dashboard">
        <div class="page-header">
            <h3>Available Inventories
                <small></small>
            </h3>
        </div>

        @foreach($inventories as $inventory)
            <div class="row col-xs-12 col-sm-6 col-md-6 col-lg-6">
                <div class="panel panel-default">
                    <div class="panel-heading">

                        <a class="pull-right" href="/inventory/edit/{{$inventory->id}}">
                            <span class="glyphicon glyphicon glyphicon-cog" aria-hidden="true"></span>
                        </a>

                        <h4>{{$inventory->name}}</h4>
                    </div>
                    <div class="panel-body">

                        <div>
                            <a class="center-block" href="/inventory/{{$inventory->id}}/sales">
                                <button type="button" class="btn btn-default">
                                    <i class="fa fa-credit-card"></i> Sales
                                </button>
                            </a>
                            <a class="center-block" href="/inventory/{{$inventory->id}}/products">
                                <button type="button" class="btn btn-default">
                                    <span class="glyphicon glyphicon-list" aria-hidden="true"></span> Inventory ({{$inventory->products->count()}} items)
                                </button>
                            </a>
                            <a class="center-block" href="/inventory/{{$inventory->id}}/products/add">
                                <button type="button" class="btn btn-default">
                                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add Products
                                </button>
                            </a>

                            <a class="center-block" href="/inventory/{{$inventory->id}}/profit">
                                <button type="button" class="btn btn-default">
                                    <i class="fa fa-line-chart"></i> Profit Calculator
                                </button>
                            </a>

                            <a class="center-block" href="/inventory/checkout/{{$inventory->id}}">
                                <button type="button" class="btn btn-default">
                                    <i class="fa fa-cart-plus"></i> Checkout
                                </button>
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        @endforeach
    </div>
@stop