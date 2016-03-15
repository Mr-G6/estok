@extends('layout')

@section('title')
    <title>{{$warehouse->name}} - UrbanWare</title>
@stop

@section('transactions')
    <div class="transactions" data-wh-id="{{$warehouse->id}}">
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

            <a class="pull-right" href="/warehouse/{{$warehouse->id}}/products">
                <button type="button" class="btn btn-default">
                    <span class="glyphicon glyphicon-list" aria-hidden="true"></span> Products
                </button>
            </a>

            <h3>
                Transactions
                <small>{{$warehouse->name}}</small>
            </h3>
        </div>

        @if(count($transactions))

            <div class="date">
                <form class="form-inline">
                    <div class="form-group">
                        <label for="datepicker"> From :  </label>
                        <input type="text"
                               class="form-control"
                               id="datepicker-from"
                               placeholder="Date From"
                               required>
                    </div>

                    <div class="form-group">
                        <label for="datepicker"> To : </label>
                        <input type="text"
                               class="form-control"
                               id="datepicker-to"
                               placeholder="Date To"
                               required>
                    </div>
                </form>
            </div>

            <div class="alert alert-danger" id="transaction_error" role="alert"></div>

        
            <div class="transaction-table table-responsive">
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
                            Total Cost
                        </th>
                        <th>
                            Total Retail
                        </th>
                        <th>
                            Profit
                        </th>
                        <th>
                            Date
                        </th>
                        <th>
                            Time
                        </th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($transactions as $transaction)
                        <tr>
                            <td>
                                {{$transaction->id}}
                            </td>
                            <td>
                                {{$transaction->item_name}}
                            </td>
                            <td>
                                {{$transaction->item_quantity}}
                            </td>
                            <td>
                                Rs.{{$transaction->cost_total}}
                            </td>
                            <td>
                                Rs.{{$transaction->retail_total}}
                            </td>
                            <td>
                                Rs.{{$transaction->retail_total - $transaction->cost_total}}
                            </td>
                            <td>
                                {{date_format($transaction->created_at, 'Y-m-d')}}
                            </td>
                            <td>
                                {{date_format($transaction->created_at, 'h:i:s a')}}
                            </td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <center>{{$transactions->links()}}</center>
        @else
            <div class="alert alert-warning" role="alert">No Transactions Available. {{$warehouse->name}}.</div>
        @endif

        <div class="table-responsive">
            <table id="transaction-lists" class="table table-striped">

            </table>
        </div>
    </div>
@stop