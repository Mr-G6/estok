var Transactions = function () {

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
        $("#transaction_error").slideUp();
        $("#transaction_error").html('');
    };

    /**
     * Display Error DOM
     */
    this.showErrorDoM = function () {
        $("#transaction_error").slideDown();
    };

    /**
     * Append Errors to DOM
     */
    this.appendErrorsOnDom = function () {
        this.errors.forEach(function (err) {
            $("<li>" + err + "</li>").appendTo("#transaction_error");
        });
    };

    /**
     * Get From Date Value
     * @returns {*}
     */
    this.getFromDate = function () {
        return $.trim($("#datepicker-from").val());
    };

    /**
     * Get To Date Value
     * @returns {*}
     */
    this.getToDate = function () {
        return $.trim($("#datepicker-to").val());
    };

    /**
     * Get Warehouse ID
     * @returns {*|jQuery}
     */
    this.getWareHouseID = function () {
        return $(".transactions").attr('data-wh-id');
    };

    /**
     * Hide Transaction Static Listings
     */
    this.hideTransactionTable = function () {
        $(".transaction-table").slideUp();
    };

    /**
     * Show Transaction Static Listings
     */
    this.showTransactionTable = function () {
        $(".transaction-table").slideDown();
    };

    /**
     * Set and Validate Dates
     * @param type
     * @param date
     */
    this.setDate = function (type, date) {
        this.clearErrors();
        if (type === 'from') {
            this.fromDate = date;
        }

        if (type === 'to') {
            this.toDate = date;
        }

        if (this.fromDate && this.toDate) {
            if (this.validateDate(this.fromDate, this.toDate)) {
                this.getTransactions(this.fromDate, this.toDate, this.getWareHouseID());
            } else {
                this.errors.push('Invalid Date Range');
                this.appendErrorsOnDom();
                this.showErrorDoM();
                this.showTransactionTable();
                this.clearTransactions();
            }
        }
    };

    /**
     * Get Transactions between date ranges
     * @param from
     * @param to
     * @param wh_id
     */
    this.getTransactions = function (from, to, wh_id) {
        this.clearTransactions();
        var _this = this;
        $.ajax({
            type: 'GET',
            url: '/warehouse/' + wh_id + '/transactions/byDate',
            data: {
                from: from,
                to: to
            },
            success: function (res) {
                console.log(res);

                if (res.length) {
                    _this.hideTransactionTable();
                    _this.appendTableHeader();
                    _this.appendTableBody();
                    _this.appendItemsToDOM(res);
                    _this.setTotals(res);
                    _this.setRange(from, to);
                } else {
                    _this.errors.push('No Transactions Available in between these dates.');
                    _this.appendErrorsOnDom();
                    _this.showErrorDoM();
                    _this.showTransactionTable();
                }
            }
        })
    };

    /**
     * Set Transaction Total Details
     * @param transactions
     */
    this.setTotals = function (transactions) {
        var total_products = 0,
            total_cost = 0,
            total_retail = 0,
            total_profit = 0;
        transactions.forEach(function (transaction) {
            total_products += parseInt(transaction.item_quantity);
            total_cost += parseFloat(transaction.cost_total);
            total_retail += parseFloat(transaction.retail_total);
            total_profit += parseFloat(transaction.retail_total) - parseFloat(transaction.cost_total);
        });

        var $total = `<table id="transaction-totals" class='table'>
                        <tr class="well">
                            <td>
                                <b>Total Products Sold : </b> ${total_products}
                            </td>

                            <td>
                                <b>Total Cost : </b> ${total_cost}
                            </td>

                            <td>
                                <b>Total Retail : </b> ${total_retail}
                            </td>

                            <td>
                                <b>Total Profit :  </b> ${total_profit}
                            </td>
                        </tr>
                     </table>`;
        $("#transaction-lists").parent('.table-responsive').append($total);
    };

    /**
     * Set Date Search Ranges
     * @param from
     * @param to
     */
    this.setRange = function (from, to) {
        var $DOM = `<div class="panel panel-default">
                        <div class="panel-body">
                            Transactions from <b>${from}</b> to <b>${to}</b>
                        </div>
                    </div>`;

        $("#transaction-lists").parent('.table-responsive').prepend($DOM);
    };

    /**
     * Validate Date Values
     * @param from
     * @param to
     * @returns {boolean}
     */
    this.validateDate = function (from, to) {
        var from = new Date(from);
        var to = new Date(to);
        return (from <= to);
    };

    /**
     * Initialize Transaction Handlers
     */
    this.init = function () {
        var _this = this;
        $("#datepicker-from").datepicker({
            dateFormat: 'yy-mm-dd',
            onSelect: function (dateText) {
                _this.setDate('from', dateText);
            }
        });

        $("#datepicker-to").datepicker({
            dateFormat: 'yy-mm-dd',
            onSelect: function (dateText) {
                _this.setDate('to', dateText);
            }
        });
    };
};

var transactions = new Transactions();
transactions.init();

/**
 * Transaction Dynamic Listings Header
 */
Transactions.prototype.appendTableHeader = function () {
    var $head = `<thead>
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
                </thead>`;
    $("#transaction-lists").append($head);
};

/**
 * Transaction Dynamic Body
 */
Transactions.prototype.appendTableBody = function () {
    var $body = `<tbody></tbody>`;
    $("#transaction-lists").append($body);
};

/**
 * Append Rows to Dynamic Transaction Listing Table Body
 * @param transactions
 */
Transactions.prototype.appendItemsToDOM = function (transactions) {
    transactions.forEach(function (transaction) {
        var $transaction = `<tr>
                                <td>
                                    ${transaction.id}
                                </td>
                                <td>
                                    ${transaction.item_name}
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
        $("#transaction-lists > tbody").append($transaction);
    });
};

/**
 * Clear Dynamic Transaction Listings
 */
Transactions.prototype.clearTransactions = function () {
    $("#transaction-lists").html('');
    $("#transaction-totals").remove();
    $("#transaction-lists").parent('.table-responsive').find('.panel').remove();
};