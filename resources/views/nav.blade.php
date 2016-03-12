<nav class="navbar navbar-default">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/"><b>UrbanWare</b></a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

            <ul class="nav navbar-nav navbar-right">
                @if(!Request::is('/'))
                    <li><a href="/"> <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"> </span> Warehouses</a></li>
                @endif

                @if(Request::is('warehouse/*/products/edit/*'))
                    <li><a href="/warehouse/{{$warehouse->id}}/products"> <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Products </a></li>
                @endif

                <li><a href="/checkout"> <span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"> </span> Checkout</a></li>
            </ul>
        </div>
    </div>
</nav>