var Receipt = function(){

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
            id = $.trim($(this).attr('data-receipt-id'));

        $.ajax({
            type : 'GET',
            url : '/warehouse/sales/byReceiptId',
            data : {
                receipt_id : id
            },
            success : function(transactions){
                console.log(transactions);
                if(transactions.length){
                    _this.appendTableHeader();
                    _this.appendTableBody();
                    _this.appendItemsToDOM(transactions);
                    _this.showTransactionsModal();
                }
            }
        });
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

    /**
     * Initialize Handlers
     */
    this.init = function(){
        $(".receipt-details").on('click', {context : this} , this.showReceiptDetails);
        $(".delete-receipt").on('click', {context : this} , this.confirmReceiptDelete);
        $("#delete-receipt").on('click', {context : this} , this.deleteReceipt);
        $('#receipt-dt-modal').on('hidden.bs.modal', {context :this }, this.clearTransactionModalBody);
    };
};

var receipts = new Receipt();
receipts.init();

/**
 * Append Receipt Transactions details modal header
 */
Receipt.prototype.appendTableHeader = function () {
    var $head = `<thead>
                    <tr>
                        <th>
                            # ID
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
    transactions.forEach(function (transaction) {
        var $transaction = `<tr>
                                <td>
                                    ${transaction.id}
                                </td>

                                <td>
                                    ${transaction.item_quantity}
                                </td>
                                <td>
                                    ${transaction.cost_total}
                                </td>
                                <td>
                                    ${transaction.retail_total}
                                </td>
                                <td>
                                    ${transaction.retail_total - transaction.cost_total}
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
