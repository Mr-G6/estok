class Receipt{
    /**
     * Get CSRF Token
     * @returns {*|jQuery}
     */
    getToken(){
        return $(".receipts").attr('data-token');
    }

    /**
     * ------------------------
     * Invoice Details
     * ------------------------
     */
    
    /**
     * Get and set Invoice details to Invoice Modal body
     * @param e
     */
    showInvoiceDetails(e){
        e.preventDefault();
        var _this = e.data.context,
            id = $.trim($(this).attr('data-receipt-id')),
            name = $.trim($(this).attr('data-name'));

        $.ajax({
            type : 'GET',
            url : '/inventory/sales/byReceiptId',
            data : {
                receipt_id : id
            },
            success : function(transactions){
                if(transactions.length){
                    _this.setInvoiceModalHeader(id, name);
                    _this.setInvoicePrintID(id);
                    _this.appendInvoiceModalTableHeader();
                    _this.appendInvoiceModalTableBody();
                    _this.appendItemsToInvoiceModalDOM(transactions);
                    _this.showInvoiceModal();
                }
            }
        });
    }

    /**
     * Set Invoice Details Model Header
     * @param id
     * @param name
     */
    setInvoiceModalHeader(id, name){
        $("#receipt-dt-name").text('Buyer : ' + name);
        $("#receipt-dt-id").text('Receipt id # ' + id);
    }

    /**
     * Set Receipt print button id
     * @param id
     */
    setInvoicePrintID(id){
        $("#print-receipt").attr('href','/print/receipt/'+id);
    }

    /**
     * Appends Invoice Modal Table header
     */
    appendInvoiceModalTableHeader () {
        var head_titles = ['Name', 'Quantity', 'Unit Cost', 'Total Retail', 'Date', 'Time'];
        var $head = `<thead><tr><th>${head_titles.join('</th><th>')}</th></tr></thead>`;
        $("#receipt-dt-modal .modal-body .table").append($head);
    }

    /**
     * Appends Invoice Modal Table body
     */
    appendInvoiceModalTableBody () {
        var $body = `<tbody></tbody>`;
        $("#receipt-dt-modal .modal-body .table").append($body);
    }

    /**
     * Append Items to Invoice Modal
     * @param transactions
     */
    appendItemsToInvoiceModalDOM (transactions) {
        var retail_total = this.getRetailTotal(transactions);
        $("#receipt-dt-total").html('<b>Retail  Total</b> : Rs.' + retail_total);
        transactions.forEach(function (transaction) {
            var $transaction = [
                transaction.product.name ,
                transaction.quantity ,
                transaction.cost_total / transaction.quantity,
                transaction.retail_total,
                moment(transaction.created_at).format('YYYY-MM-DD'),
                moment(transaction.created_at).format('HH:mm:ss a')
            ];
            var $item = `<tr><td>${$transaction.join('</td><td>')}</td></tr>`;
            $("#receipt-dt-modal .modal-body .table > tbody").append($item);
        });
    }

    /**
     * Show Invoice Details Modal
     */
    showInvoiceModal(){
        $("#receipt-dt-modal").modal('show');
    }

    /**
     * Remove Invoice Details Modal body
     */
    clearInvoiceModalBody(){
        $("#receipt-dt-modal .modal-body .table").empty();
    }

    /**
     * ---------------------------
     * Delete Receipt
     * ---------------------------
     */

    /**
     * Confirm Receipt Delete
     * @param e
     */
    confirmReceiptDelete(e){
        var id = $(this).attr('data-receipt-id');
        $("#delete-receipt").attr('data-receipt-id',id);
        $("#receipt-delete-modal").modal('show');
    }

    /**
     * Delete Receipt
     * @param e
     */
    deleteReceipt(e){
        var _this = e.data.context,
            id = $(this).attr('data-receipt-id');

        $.ajax({
            type : 'POST',
            url : '/inventory/sales/delete',
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
    }

    /**
     * Remove Receipt item from table
     * @param id
     */
    removeReceiptFromList(id){
        $(".receipt-list[data-receipt-id='" + id +"']").slideUp('slow');
    }

    /**
     *  -----------------------------
     *  Receipt Search
     *  -----------------------------
     */

    /**
     * Search Receipt by Name or ID
     * @param e
     */
    salesSearch(e){
        e.preventDefault();
        var _this = e.data.context,
            query = $.trim($(this).val()).toLowerCase(),
            inventory_id = $.trim($(this).attr('data-warehouse'));

        _this.errors.clearErrors();

        if(e.which == 13){
            if(query.length){
                _this.getReceipts(inventory_id, query);
            }else{
                _this.errors.add('Product name required!');
                _this.errors.appendErrorsToDOM();
                _this.errors.showErrorDOM();
                _this.showTransactionTable();
                _this.clearReceiptSearchResults();
            }
        }

        if(e.which == 27){
            $(this).val('');
            $(this).blur();
            _this.clearReceiptSearchResults();
            _this.errors.hideErrorDOM();
            _this.showReceiptsTable();
        }
    }

    /**
     * Get Receipts for search
     * @param inventory_id
     * @param query
     */
    getReceipts(inventory_id, query){
        this.clearReceiptSearchResults();

        var _this = this;
        $.ajax({
            type : 'GET',
            url : '/inventory/sales/byReceiptIdOrName',
            data : {
                inventory_id : inventory_id,
                query : query
            },
            success : function(res){
                if (res.length) {
                    _this.hideReceiptsTable();
                    _this.appendReceiptSearchTableHeader();
                    _this.appendReceiptSearchItemsToDOM(res);
                    _this.attachReceiptSearchTableHandlers();
                } else {
                    _this.errors.add('No Receipts found with this id or name.');
                    _this.errors.appendErrorsToDOM();
                    _this.errors.showErrorDOM();
                    _this.clearReceiptSearchResults();
                    _this.showReceiptsTable();
                }
            }
        })
    }

    /**
     * Hide Receipts Table
     */
    hideReceiptsTable() {
        $(".receipts-table").slideUp();
    }

    /**
     * Show Receipts Table
     */
    showReceiptsTable() {
        $(".receipts-table").slideDown();
    }

    /**
     *  Bind/Unbind Receipt Search results table rows action handlers
     */
    attachReceiptSearchTableHandlers(){
        $(".receipt-details").unbind('click');
        $(".delete-receipt").unbind('click');
        $("#delete-receipt").unbind('click');

        $(".receipt-details").on('click', {context : this} , this.showInvoiceDetails);
        $(".delete-receipt").on('click', {context : this} , this.confirmReceiptDelete);
        $("#delete-receipt").on('click', {context : this} , this.deleteReceipt);
    }

    /**
     * Return Receipt sales total
     * @param transactions
     * @returns {number}
     */
    getRetailTotal(transactions){
        var total = 0;
        transactions.forEach(function (transaction) {
            total += parseFloat(transaction.retail_total);
        });
        return total;
    }

    /**
     * Append Receipt search table headers
     */
    appendReceiptSearchTableHeader () {
        var head_titles = ['ID', 'Name', 'Address', 'Phone No', 'Items', 'Retail', 'Cost', 'Profit', 'Date', 'Time'];
        var $head = `<thead><tr><th>${head_titles.join('</th><th>')}</th></tr></thead>`;
        $("#receipts-lists").append($head);
    }

    /**
     * Append Receipt Search results to DOM
     * @param receipts
     */
    appendReceiptSearchItemsToDOM(receipts) {
        receipts.forEach(function (receipt) {
            var $receipt = [
                receipt.id,
                receipt.name,
                receipt.address,
                receipt.phone_no,
                receipt.total_items,
                receipt.total_retail,
                receipt.cost_total,
                receipt.profit,
                moment(receipt.created_at).format('YYYY-MM-DD'),
                moment(receipt.created_at).format('HH:mm:ss a')
            ];
            var $item    = `<td>${$receipt.join('</td><td>')}</td>`;
            var $actions = `<td>
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
                            </td>`;
            $("#receipts-lists").append(`<tbody><tr>${$item}${$actions}</tr></tbody>`);
        });
    }

    /**
     * Clear Receipt search results table
     */
    clearReceiptSearchResults () {
        $("#receipts-lists").html('');
        $("#receipts-lists").parent('.table-responsive').find('.panel').remove();
    }

    constructor(){
        this.errors = new Errors('#receipt_error');

        // Receipt/Invoice Details
        $(".receipt-details").on('click', {context : this} , this.showInvoiceDetails);
        $('#receipt-dt-modal').on('hidden.bs.modal', {context :this }, this.clearInvoiceModalBody);

        // Delete Receipt
        $(".delete-receipt").on('click', {context : this} , this.confirmReceiptDelete);
        $("#delete-receipt").on('click', {context : this} , this.deleteReceipt);

        $(".clear-dues").on('click', {context : this} , this.clearDues);


        // Search Receipts
        $("#sales-search").on('keyup', {context : this}, this.salesSearch);
    }

    clearDues(e) {
        var _this = e.data.context,
            id = $(this).attr('data-id');

        bootbox.confirm({
            message: "Are you sure you want to clear this transaction dues?",
            buttons: {
                confirm: {
                    label: 'Yes',
                    className: 'btn-success'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                if(result){
                    $.ajax({
                        type : 'POST',
                        url : '/inventory/clear/sales',
                        data : {
                            id : id,
                            _token : _this.getToken()
                        },
                        success : function(done){
                            if(done){
                                $(".pay-status[data-id='" + id +"']").empty().text('Paid');
                                $(".receipt-list[data-receipt-id='" + id +"']").removeClass('unpaid').addClass('paid');
                            }
                        }
                    })
                }
            }
        });
    }
}

var receipts = new Receipt();