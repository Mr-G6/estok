<a class="pull-right" href="/inventory/{{$inventory->id}}/sales">
    <button type="button" class="btn btn-default">
        <i class="fa fa-credit-card"></i></span> Sales
    </button>
</a>

<a class="pull-right" href="/inventory/{{$inventory->id}}/profit">
    <button type="button" class="btn btn-default">
        <span class="glyphicon glyphicon-stats" aria-hidden="true"></span> Profit
    </button>
</a>

<a class="pull-right" href="/inventory/checkout/{{$inventory->id}}">
    <button type="button" class="btn btn-default">
        <span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span> Checkout
    </button>
</a>

<a class="pull-right" href="/inventory/{{$inventory->id}}/products/add">
    <button type="button" class="btn btn-default">
        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add Products
    </button>
</a>