var Receipt = function(){

    this.fromDate = '';

    this.toDate = '';

    this.errors = [];

    /**
     * Clean all Form Errors
     */
    this.clearErrors = function () {
        this.errors = [];
        this.hideErrorDoM();
    };

    /**
     * Clear and Hide Form Error DOM
     */
    this.hideErrorDoM = function () {
        $("#receipt_error").slideUp();
        $("#receipt_error").html('');
    };

    /**
     * Display Error DOM
     */
    this.showErrorDoM = function () {
        $("#receipt_error").slideDown();
    };

    /**
     * Append Errors to DOM
     */
    this.appendErrorsOnDom = function () {
        this.errors.forEach(function (err) {
            $("<li>" + err + "</li>").appendTo("#receipt_error");
        });
    };

    /**
     * Hide Transaction Static Listings
     */
    this.hideReceiptsTable = function () {
        $(".receipts-table").slideUp();
    };

    /**
     * Show Transaction Static Listings
     */
    this.showReceiptsTable = function () {
        $(".receipts-table").slideDown();
    };

    /**
     * Returns csrf token
     * @returns {*|jQuery}
     */
    this.getToken = function(){
        return $(".receipts").attr('data-token');
    }

    /**
     * Show Transaction Modal
     */
    this.showTransactionsModal = function(){
        $("#receipt-dt-modal").modal('show');
    };

    /**
     * Hide Transaction details Modal
     */
    this.hideTransactionModal = function(){
        $("#receipt-dt-modal").modal('hide');
    };

    /**
     * Clear Transaction details Modal body
     */
    this.clearTransactionModalBody = function(){
        $("#receipt-dt-modal .modal-body .table").empty();
    };

    /**
     * Get Receipt items list
     * @param e
     */
    this.showReceiptDetails = function(e){
        e.preventDefault();
        var _this = e.data.context,
            id = $.trim($(this).attr('data-receipt-id')),
            name = $.trim($(this).attr('data-name'));

        $.ajax({
            type : 'GET',
            url : '/warehouse/sales/byReceiptId',
            data : {
                receipt_id : id
            },
            success : function(transactions){
                console.log(transactions);
                if(transactions.length){
                    _this.setTransactionModalHeader(id, name);
                    _this.setReceiptPrintID(id);
                    _this.appendTableHeader();
                    _this.appendTableBody();
                    _this.appendItemsToDOM(transactions);
                    _this.showTransactionsModal();
                }
            }
        });
    };

    this.setReceiptPrintID = function(id){
        $("#print-receipt").attr('href','/print/receipt/'+id);
    };

    /**
     * Remove Receipt item from DOM
     * @param id
     */
    this.removeReceiptFromList = function(id){
        $(".receipt-list[data-receipt-id='" + id +"']").slideUp('slow');
    };

    /**
     * Confirm Delete Dialogue for Receipt delete
     * @param e
     */
    this.confirmReceiptDelete = function(e){
        var _this = e.data.context,
            id = $(this).attr('data-receipt-id');
        $("#delete-receipt").attr('data-receipt-id',id);
        $("#receipt-delete-modal").modal('show');
    };

    /**
     * Delete Receipt list item
     * @param e
     */
    this.deleteReceipt = function(e){
        var _this = e.data.context,
            id = $(this).attr('data-receipt-id');

        $.ajax({
            type : 'POST',
            url : '/warehouse/sales/delete',
            data : {
                receipt_id : id,
                _token : _this.getToken()
            },
            success : function(done){
                if(done){
                    _this.removeReceiptFromList(id);
                }
            }
        })
    };

    this.getReceipts = function(wh_id, query){
        this._clearTransactions();

        var _this = this;
        $.ajax({
            type : 'GET',
            url : '/warehouse/sales/byReceiptIdOrName',
            data : {
                wh_id : wh_id,
                query : query
            },
            success : function(res){
                if (res.length) {
                    _this.hideReceiptsTable();
                    _this._appendTableHeader();
                    _this._appendTableBody();
                    _this._appendItemsToDOM(res);
                    _this.attachDynamicHandlers();
                } else {
                    _this.errors.push('No Receipts found with this id or name.');
                    _this.appendErrorsOnDom();
                    _this.showErrorDoM();
                    _this._clearTransactions();
                    _this.showReceiptsTable();
                }
            }
        })
    };

    this.attachDynamicHandlers = function(){
        console.log("Attaching Event Handlers");
        $(".receipt-details").unbind('click');
        $(".delete-receipt").unbind('click');
        $("#delete-receipt").unbind('click');

        $(".receipt-details").on('click', {context : this} , this.showReceiptDetails);
        $(".delete-receipt").on('click', {context : this} , this.confirmReceiptDelete);
        $("#delete-receipt").on('click', {context : this} , this.deleteReceipt);
    };

    this.salesSearch = function(e){

        e.preventDefault();
        var _this = e.data.context,
            query = $.trim($(this).val()).toLowerCase(),
            wh_id = $.trim($(this).attr('data-warehouse'));

        _this.clearErrors();

        if(e.which == 13){
            if(query.length){
                _this.getReceipts(wh_id, query);
            }else{
                _this.errors.push('Product name required!');
                _this.appendErrorsOnDom();
                _this.showErrorDoM();
                _this.showTransactionTable();
                _this._clearTransactions();
            }
        }

        if(e.which == 27){
            $(this).val('');
            $(this).blur();
            _this._clearTransactions();
            _this.hideErrorDoM();
            _this.showReceiptsTable();
        }
    };

    /**
     * Initialize Handlers
     */
    this.init = function(){
        $(".receipt-details").on('click', {context : this} , this.showReceiptDetails);
        $(".delete-receipt").on('click', {context : this} , this.confirmReceiptDelete);
        $("#delete-receipt").on('click', {context : this} , this.deleteReceipt);
        $("#sales-search").on('keyup', {context : this}, this.salesSearch);
        $('#receipt-dt-modal').on('hidden.bs.modal', {context :this }, this.clearTransactionModalBody);
    };
};

var receipts = new Receipt();
receipts.init();

Receipt.prototype.setTransactionModalHeader = function(id, name){
    $("#receipt-dt-name").text('Buyer : ' + name);
    $("#receipt-dt-id").text('Receipt id # ' + id);
};

/**
 * Append Receipt Transactions details modal header
 */
Receipt.prototype.appendTableHeader = function () {
    var $head = `<thead>
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
                </thead>`;
    $("#receipt-dt-modal .modal-body .table").append($head);
};

/**
 * Append Receipt Transactions details modal body
 */
Receipt.prototype.appendTableBody = function () {
    var $body = `<tbody></tbody>`;
    $("#receipt-dt-modal .modal-body .table").append($body);
};

/**
 * Append items to Transaction details modal body
 */
Receipt.prototype.appendItemsToDOM = function (transactions) {
    var retail_total = this.getRetailTotal(transactions);
    $("#receipt-dt-total").html('<b>Retail  Total</b> : Rs.' + retail_total);
    transactions.forEach(function (transaction) {
        var $transaction = `<tr>
                                <td>
                                    ${transaction.item_name}
                                </td>

                                <td>
                                    ${transaction.item_quantity}
                                </td>
                                <td>
                                    ${transaction.cost_total / transaction.item_quantity}
                                </td>
                                <td>
                                    ${transaction.retail_total}
                                </td>
                                <td>
                                    ${moment(transaction.created_at).format('YYYY-MM-DD')}
                                </td>
                                <td>
                                    ${moment(transaction.created_at).format('HH:mm:ss a')}
                                </td>
                            </tr>`;
        $("#receipt-dt-modal .modal-body .table > tbody").append($transaction);
    });
};

Receipt.prototype.getRetailTotal = function(transactions){
    var total = 0;
    transactions.forEach(function (transaction) {
        total += parseFloat(transaction.retail_total);
    });
    return total;
};



/**
 * Receipts Dynamic Listings Header
 */
Receipt.prototype._appendTableHeader = function () {
    var $head = `<thead>
                    <tr>
                        <th>
                            ID
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

                        </th>
                    </tr>
                </thead>`;
    $("#receipts-lists").append($head);
};

/**
 * Receipts Dynamic Body
 */
Receipt.prototype._appendTableBody = function () {
    var $body = `<tbody></tbody>`;
    $("#receipts-lists").append($body);
};

/**
 * Append Rows to Dynamic Receipts Listing Table Body
 * @param transactions
 */
Receipt.prototype._appendItemsToDOM = function (receipts) {
    receipts.forEach(function (receipt) {
        console.log(receipt);
        var $transaction = `<tr>
                                <td>
                                    ${receipt.id}
                                </td>
                                <td>
                                    ${receipt.name}
                                </td>
                                <td>
                                    ${receipt.address}
                                </td>
                                <td>
                                    ${receipt.phone_no}
                                </td>
                                <td>
                                    ${receipt.total_items}
                                </td>
                                <td>
                                    ${receipt.total_retail}
                                </td>
                                <td>
                                    ${receipt.cost_total}
                                </td>
                                <td>
                                    ${receipt.profit}
                                </td>
                                <td>
                                    ${moment(receipt.created_at).format('YYYY-MM-DD')}
                                </td>
                                <td>
                                    ${moment(receipt.created_at).format('HH:mm:ss a')}
                                </td>
                                <td>
                                <div class="btn-group pull-right" role="group" aria-label="...">
                                    <a class="receipt-details" data-receipt-id="${receipt.id}" data-name="${receipt.name}" href="#">
                                        <button type="button" class="btn btn-default">
                                            <span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Details
                                        </button>

                                        <a class="delete-receipt" data-receipt-id="${receipt.id}" href="#">
                                            <button type="button" class="btn btn-default">
                                                <span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Delete
                                            </button>
                                        </a>
                                    </a>
                                </div>
                            </td>
                            </tr>`;
        $("#receipts-lists > tbody").append($transaction);
    });
};

/**
 * Clear Dynamic Receipts Listings
 */
Receipt.prototype._clearTransactions = function () {
    $("#receipts-lists").html('');
    $("#receipts-lists").parent('.table-responsive').find('.panel').remove();
};
