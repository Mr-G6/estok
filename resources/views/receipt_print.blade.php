<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>Receipt # {{$receipt->id}} - {{$receipt->name}}</title>
    <link rel="stylesheet" type="text/css" href="/assets/lib/bootstrap/dist/css/bootstrap.min.css">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300,400' rel='stylesheet' type='text/css'>
    <style>
        *{
            font-family: 'Open Sans', sans-serif;
        }
        h2, h3{
            display:inline;
        }
        h3{
            padding: 20px;
        }

        h4, button{
            display:inline;
        }

    </style>
</head>
<body>
    <div class="container">
        <div class="page-header">
            <h1>Sales Receipt</h1>
            <h2>
                <small>
                    Name : {{$receipt->name}}
                </small>
            </h2>
            <h3>
                <small>Receipt id # {{$receipt->id}}</small>
            </h3>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>
                        Name
                    </th>

                    <th>
                        Quantity
                    </th>
                    <th>
                        Unit Cost
                    </th>
                    <th>
                        Total Retail
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
                            {{$transaction->item_name}}
                        </td>
                        <td>
                            {{$transaction->item_quantity}}
                        </td>
                        <td>
                            {{$transaction->cost_total / $transaction->item_quantity}}
                        </td>
                        <td>
                            {{$transaction->retail_total}}
                        </td>
                        <td>
                            {{date_format($transaction->created_at, 'Y-m-d')}}
                        </td>
                        <td>
                            {{date_format($transaction->created_at, 'H:i:s a')}}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <table>
            <tr>
                <div class="well">
                    <h4>
                        Total : {{\App\Models\Transaction::where('receipt_id', '=', $receipt->id)->sum('retail_total')}}
                    </h4>

                    <button class="btn btn-default pull-right" id="print-receipt">
                        Print Receipt
                    </button>
                </div>
            </tr>
        </table>
    </div>
    <script type="text/javascript" src="/assets/lib/jquery/dist/jquery.min.js"></script>
    <script type="text/javascript">
        $('#print-receipt').on('click', function(){
            window.print();
        });
    </script>
</body>
</html>