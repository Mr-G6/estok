class Transactions{

    /**
     * -------------------
     * Search by date
     * -------------------
     */

    /**
     * Get Warehouse ID
     * @returns {*|jQuery}
     */
    getWareHouseID() {
        return $(".transactions").attr('data-wh-id');
    }

    /**
     * Get From Date Input
     * @returns {*}
     */
    getFromDate() {
        return $.trim($("#datepicker-from").val());
    }

    /**
     * Get To Date Input
     * @returns {*}
     */
    getToDate() {
        return $.trim($("#datepicker-to").val());
    }

    /**
     * Validate Date
     * @param from
     * @param to
     * @returns {boolean}
     */
    validateDate(from, to) {
        var from = new Date(from);
        var to = new Date(to);
        return (from <= to);
    }

    /**
     * Set Date Ranges for search
     * @param type
     * @param date
     */
    setDate(type, date) {
        this.errors.clearErrors();
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
                this.errors.add('Invalid Date Range');
                this.errors.appendErrorsToDOM();
                this.errors.showErrorDOM();
                this.showTransactionTable();
                this.clearProductSearchTable();
            }
        }
    }

    /**
     * GET Products by Date
     * @param from
     * @param to
     * @param wh_id
     */
    getTransactions(from, to, wh_id) {
        this.clearProductSearchTable();
        var _this = this;
        $.ajax({
            type: 'GET',
            url: '/warehouse/' + wh_id + '/profit/byDate',
            data: {
                from: from,
                to: to
            },
            success: function (res) {
                console.log(res);

                if (res.length) {
                    _this.hideTransactionTable();
                    _this.appendProductSearchTableHeader();
                    _this.appendItemsToProductsSearchDOM(res);
                    _this.setTotals(res);
                    _this.setRange(from, to);
                } else {
                    _this.errors.add('No Transactions Available in between these dates.');
                    _this.errors.appendErrorsToDOM();
                    _this.errors.showErrorDOM();
                    _this.showTransactionTable();
                }
            }
        })
    }

    /**
     * Caculate Searched Products Total
     * @param transactions
     */
    setTotals(transactions) {
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
    }

    /**
     * Set Search Query on Panel
     * @param name
     */
    setQuery(name){
        var $DOM = `<div class="panel panel-default">
                        <div class="panel-body">
                            Product Search : <b>${name}</b>
                        </div>
                    </div>`;

        $("#transaction-lists").parent('.table-responsive').prepend($DOM);
    }

    /**
     * Set Date Range on Panel
     * @param from
     * @param to
     */
    setRange(from, to) {
        var $DOM = `<div class="panel panel-default">
                        <div class="panel-body">
                            Transactions from <b>${from}</b> to <b>${to}</b>
                        </div>
                    </div>`;

        $("#transaction-lists").parent('.table-responsive').prepend($DOM);
    }

    /**
     * -----------------------
     * Search by Name
     * -----------------------
     */

    /**
     * Profit Product Search
     * @param e
     */
    productSearch(e){
        e.preventDefault();
        var _this = e.data.context,
            query = $.trim($(this).val()).toLowerCase();

        if(e.which == 13){
            if(query.length){
                _this.getTransactionsByName(query);
            }else{
                _this.errors.add('Product name required!');
                _this.errors.appendErrorsToDOM();
                _this.errors.showErrorDOM();
                _this.showTransactionTable();
                _this.clearProductSearchTable();
            }
        }

        if(e.which == 27){
            $(this).val('');
            $(this).blur();
            _this.showTransactionTable();
            _this.clearProductSearchTable();
            _this.errors.hideErrorDOM();
        }
    }

    /**
     * Get Transactions by name
     * @param name
     */
    getTransactionsByName(name) {
        this.clearProductSearchTable();
        var _this = this,
            wh_id = this.getWareHouseID();
        $.ajax({
            type: 'GET',
            url: '/warehouse/' + wh_id + '/profit/byName',
            data: {
                name : name
            },
            success: function (res) {
                console.log(res);

                if (res.length) {
                    _this.hideTransactionTable();
                    _this.appendProductSearchTableHeader();
                    _this.appendItemsToProductsSearchDOM(res);
                    _this.setTotals(res);
                    _this.setQuery(name);
                    _this.errors.hideErrorDOM();
                } else {
                    _this.errors.add('No Transactions matched with product name.');
                    _this.errors.appendErrorsToDOM();
                    _this.errors.showErrorDOM();
                    _this.showTransactionTable();
                }
            }
        })
    }

    /**
     * Hide Transactions table
     */
    hideTransactionTable() {
        $(".transaction-table").slideUp();
    }

    /**
     * Show Transactions table
     */
    showTransactionTable() {
        $(".transaction-table").slideDown();
    }

    /**
     * Append Product Search table header
     */
    appendProductSearchTableHeader () {
        var head_titles = ['# ID', 'Name', 'Quantity', 'Total Cost', 'Total Retail', 'Profit', 'Date', 'Time'];
        var $head = `<thead><tr><th>${head_titles.join('</th><th>')}</th></tr></thead>`;
        $("#transaction-lists").append($head);
    }

    /**
     * Append items to product search table
     * @param transactions
     */
    appendItemsToProductsSearchDOM(transactions) {
        transactions.forEach(function (transaction) {
            var $transaction = [
                transaction.id,
                transaction.item_name,
                transaction.item_quantity,
                transaction.cost_total,
                transaction.retail_total,
                transaction.retail_total - transaction.cost_total,
                moment(transaction.created_at).format('YYYY-MM-DD'),
                moment(transaction.created_at).format('HH:mm:ss a')
            ];

            var $item = `<tbody><tr><td>${$transaction.join('</td><td>')}</td></tr></tbody>`;
            $("#transaction-lists").append($item);
        });
    }

    /**
     * Clear Product Search table
     */
    clearProductSearchTable() {
        $("#transaction-lists").html('');
        $("#transaction-totals").remove();
        $("#transaction-lists").parent('.table-responsive').find('.panel').remove();
    }

    constructor(){
        this.fromDate = '';
        this.toDate = '';
        this.errors = new Errors('#transaction_error');

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

        $("#p-product-name").on('keyup', {context : this}, this.productSearch);
    }
}

var transactions = new Transactions();