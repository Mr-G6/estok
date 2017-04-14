@extends('layout')

@section('title')
    <title>{{$inventory->name}} - {{\App\Config::where('name','=', 'app_name')->first()->value}}</title>
@stop

@section('content')
    <div class="receipts" data-token="{{csrf_token()}}">
        <div class="page-header">
            @include('inventory.header_nav')

            <h3>{{$inventory->name}}
                <small>{{$inventory->location}}</small>
            </h3>
        </div>

        <div class="sales-search">
            <input type="text"
                   class="form-control pull-right"
                   id="sales-search"
                   data-warehouse="{{$inventory->id}}"
                   placeholder="Search by id or name"
                   required>
        </div>

        <div class="alert alert-danger" id="receipt_error" role="alert"></div>

        @if(count($receipts))
            <div class="table-responsive receipts-table">
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
                        <th>
                            Status
                        </th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($receipts as $receipt)
                        <tr class="receipt-list @if($receipt->paid) paid @else unpaid @endif" data-receipt-id="{{$receipt->id}}">
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

                            <td class="pay-status" data-id="{{$receipt->id}}">
                                @if($receipt->paid)
                                    Paid
                                @else
                                    <button type="button" class="btn btn-warning clear-dues" data-id="{{$receipt->id}}">
                                        Pay <span class="glyphicon glyphicon-ok" aria-hidden="true"> </span>
                                    </button>
                                @endif
                            </td>

                            <td>
                                <div class="btn-group pull-right" role="group" aria-label="...">
                                    <a class="receipt-details" data-receipt-id="{{$receipt->id}}" data-name="{{$receipt->name}}" href="#">
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

            <div class="table-responsive">
                <table id="receipts-lists" class="table table-striped">

                </table>
            </div>

        @else
            <div class="alert alert-warning" role="alert">No sales record for this inventory : {{$inventory->name}}.</div>
        @endif

        <div class="links">
            {{$receipts->links()}}
        </div>
    </div>

    <div class="modal fade" id="receipt-dt-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <div class="header-data">
                        <h3 class="modal-title" id="receipt-dt-title">Sales Invoice</h3>
                        <h4 id="receipt-dt-id">Receipt id # 1</h4>
                        <h4 id="receipt-dt-name">Buyer : Waleed Ahmad</h4>
                    </div>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">

                    </table>
                </div>
                <div class="modal-footer">
                    <span id="receipt-dt-total" class="pull-left" style="font-size: 16px;"></span>
                    <a target="_blank" href="" id="print-receipt">
                        <button type="button" class="btn btn-default">Print</button>
                    </a>
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