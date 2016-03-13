@extends('layout')

@section('title')
    <title>{{$warehouse->name}} - UrbanWare</title>
@stop

@section('warehouse')
    <div class="products">
        <div class="page-header">
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
                        Wholesale
                    </th>
                    <th>
                        Retail
                    </th>
                    <th>
                        Quantity
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
                        <td>
                            {{$product->name}}
                        </td>
                        <td>
                            Rs.{{$product->price}}
                        </td>
                        <td>
                            Rs.{{$product->retail_price}}
                        </td>
                        <td>
                            {{$product->quantity}}
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
                </tbody>
            </table>
        @else
            <div class="alert alert-warning" role="alert">No Products Available in {{$warehouse->name}}.</div>
        @endif

    </div>
@stop