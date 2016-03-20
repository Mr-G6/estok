@extends('layout')

@section('title')
    <title>{{$warehouse->name}} - UrbanWare</title>
@stop

@section('body')
    <div class="receipts" data-token="{{csrf_token()}}">
        <div class="page-header">
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

            <a class="pull-right" href="/warehouse/{{$warehouse->id}}/products">
                <button type="button" class="btn btn-default">
                    <span class="glyphicon glyphicon-list" aria-hidden="true"></span> Inventory
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

        @if(count($receipts))
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
                            Address
                        </th>
                        <th>
                           Phone No
                        </th>
                        <th>
                            Items
                        </th>
                        <th>
                             Retail
                        </th>
                        <th>
                             Cost
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
                    @foreach($receipts as $receipt)
                        <tr class="receipt-list" data-receipt-id="{{$receipt->id}}">
                            <td>
                                {{$receipt->id}}
                            </td>
                            <td>
                                {{$receipt->name}}
                            </td>
                            <td>
                                {{$receipt->address}}
                            </td>
                            <td>
                                {{$receipt->phone_no}}
                            </td>
                            <td>
                                {{$receipt->total_items}}
                            </td>
                            <td>
                                Rs.{{$receipt->total_retail}}
                            </td>
                            <td>
                                Rs.{{$receipt->cost_total}}
                            </td>
                            <td>
                                Rs.{{$receipt->profit}}
                            </td>
                            <td>
                                {{date_format($receipt->created_at, 'Y-m-d')}}
                            </td>

                            <td>
                                {{date_format($receipt->created_at, 'h:i:s a')}}
                            </td>

                            <td>
                                <div class="btn-group pull-right" role="group" aria-label="...">
                                    <a class="receipt-details" data-receipt-id="{{$receipt->id}}" href="#">
                                        <button type="button" class="btn btn-default">
                                            <span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Details
                                        </button>

                                        <a class="delete-receipt" data-receipt-id="{{$receipt->id}}" href="#">
                                            <button type="button" class="btn btn-default">
                                                <span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Delete
                                            </button>
                                        </a>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-warning" role="alert">No Sales Under this Warehouse : {{$warehouse->name}}.</div>
        @endif
    </div>

    <div class="modal fade" id="receipt-dt-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="receipt-dt-title">Transaction Details</h4>
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

    <div class="modal fade" id="receipt-delete-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="receipt-dt-title">Warning!</h4>
                </div>
                <div class="modal-body">
                    <p>Deleting this Receipt will also delete all transaction records.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="delete-receipt" data-dismiss="modal">Delete</button>
                </div>
            </div>
        </div>
    </div>
@stop