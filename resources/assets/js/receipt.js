let $receiptDetails = $('.receipt-details'),
    $deleteReceipt = $('.delete-receipt'),
    $clearDues = $('.clear-dues'),
    $salesSearch = $('#sales-search'),
    $receipts = $('.receipts-table');

addEventHandler($receiptDetails, {}, 'click', getInvoiceDetails);
addEventHandler($deleteReceipt, {}, 'click', deleteReceipt);
addEventHandler($clearDues, {}, 'click', clearDues);
addEventHandler($salesSearch, {}, 'keyup', salesSearch);

/**
 * Show receipt transactions
 * @param e
 */
function getInvoiceDetails(e){
    e.preventDefault();
    let id = $.trim($(this).attr('data-receipt-id')),
        name = $.trim($(this).attr('data-name'));

    $.ajax({
        type : 'GET',
        url : '/inventory/sales/byReceiptId',
        data : {
            receipt_id : id
        },
        success : function(transactions){
            console.log(transactions);
            if(transactions.length){
                renderTransactionsItems(transactions, id, name);
            }
        }
    });
}

/**
 * Render transactions
 * @param transactions
 * @param id
 * @param name
 */
function renderTransactionsItems(transactions, id, name){
    $.get('/templates/receipt_details.html', function(template) {
        let rendered = Mustache.render(template,  {
            transactions: transactions,
            id : id,
            name : name,
            retail_total : getRetailTotal(transactions),
            unitCost : function(){
                return this.cost_total / this.quantity;
            },
            date : function(){
                return moment(this.created_at).format('YYYY-MM-DD')
            },
            time : function(){
                return moment(this.created_at).format('HH:mm:ss a')
            }
        });
        $('.receipts').append(rendered);
        addEventHandler("#receipt-dt-modal", {}, 'hidden.bs.modal', function(){
            $(this).remove();
        });
        $("#receipt-dt-modal").modal('show');
    });
}

/**
 * Get transaction total
 * @param transactions
 * @returns {number}
 */
function getRetailTotal(transactions){
    var total = 0;
    transactions.forEach(function (transaction) {
        total += parseFloat(transaction.retail_total);
    });
    return total;
}

/**
 * Delete receipt
 * @param e
 */
function deleteReceipt(e){
    let id = $(this).attr('data-receipt-id');

    bootbox.confirm({
        message: "Are you sure you want to delete this receipt?",
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
                    url : '/inventory/sales/delete',
                    data : {
                        receipt_id : id,
                        _token : getToken()
                    },
                    success : function(done){
                        if(done){
                            removeReceiptFromList(id);
                        }
                    }
                })
            }
        }
    });
}

/**
 * Delete receipt from view
 * @param id
 */
function removeReceiptFromList(id){
    $(".receipt-list[data-receipt-id='" + id +"']").slideUp('slow');
}

/**
 * Search receipt
 * @param e
 */
function salesSearch(e){
    e.preventDefault();
    let query = $.trim($(this).val()).toLowerCase(),
        inventory_id = $.trim($(this).attr('data-warehouse'));

    errors.clearErrors();

    if(e.which === 13){
        if(query.length){
            searchReceipts(inventory_id, query);
        }else{
            errors.add('Product name required!');
            errors.appendErrorsToDOM();
            errors.showErrorDOM();
            showReceiptsTable();
            clearReceiptSearchResults();
        }
    }

    if(e.which === 27){
        $(this).val('');
        $(this).blur();
        clearReceiptSearchResults();
        errors.hideErrorDOM();
        showReceiptsTable();
    }
}

/**
 * Get receipt search results
 * @param inventory_id
 * @param query
 */
function searchReceipts(inventory_id, query){
    clearReceiptSearchResults();

    $.ajax({
        type : 'GET',
        url : '/inventory/sales/byReceiptIdOrName',
        data : {
            inventory_id : inventory_id,
            query : query
        },
        success : function(receipts){
            if (receipts.length) {
                hideReceiptsTable();
                renderSearchReceipts(receipts);
            } else {
                errors.add('No Receipts found with this id or name.');
                errors.appendErrorsToDOM();
                errors.showErrorDOM();
                clearReceiptSearchResults();
                showReceiptsTable();
            }
        }
    })
}

/**
 * Hide receipts table
 */
function hideReceiptsTable() {
    $($receipts).slideUp();
}

/**
 * Show receipts table
 */
function showReceiptsTable() {
    $($receipts).slideDown();
}

/**
 * Register receipt search results event handlers
 */
function registerSearchResultsHandlers(){
    removeEventHandler('.receipt-details', 'click');
    removeEventHandler('.delete-receipt', 'click');

    addEventHandler('.receipt-details', {}, 'click', getInvoiceDetails);
    addEventHandler('.delete-receipt', {}, 'click', deleteReceipt);
}

/**
 * Render receipt search results
 * @param receipts
 */
function renderSearchReceipts(receipts){
    $.get('/templates/receipts_search.html', function(template) {
        let rendered = Mustache.render(template,  {
            receipts: receipts,
            date : function(){
                return moment(this.created_at).format('YYYY-MM-DD')
            },
            time : function(){
                return moment(this.created_at).format('HH:mm:ss a')
            }
        });
        $('.receipts').append(rendered);
        registerSearchResultsHandlers();
    });
}

/**
 * Remove receipt search results
 */
function clearReceiptSearchResults () {
    $(".receipt-search-results").remove();
}

/**
 * Clear receipt dues
 * @param e
 */
function clearDues(e) {
    let id = $(this).attr('data-id');

    $.get('/inventory/sales/receipt', {
        id : id
    }, function(receipt){
        console.log(receipt);
        $.get('/templates/dues.html', function(template) {
            var rendered = Mustache.render(template, {
                id : receipt.id,
                name : receipt.name,
                address : receipt.address,
                number : receipt.phone_no,
                due : receipt.unpaid
            });
            $('.receipts').append(rendered);
            addEventHandler('#clear-dues', {}, 'hidden.bs.modal', function(){
                $(this).remove();
            });
            addEventHandler('#clear-partial', {}, 'click', clearPartialDues);
            addEventHandler('#clear-all-dues', {}, 'click', clearAllDues);
            addEventHandler('#due-amount', {}, 'keypress', function(){
                $(this).attr('placeholder', 'Enter Amount');
            })
            $("#clear-dues").modal('show');
        });
    });
}

/**
 * Clear partial dues
 * @param e
 */
function clearPartialDues(e){
    let receipt_id = $(this).attr('data-receipt-id'),
        due = parseFloat($.trim($(this).attr('data-due'))),
        clear_amount = parseFloat($.trim($('#due-amount').val()));

    if( isNumeric(clear_amount) &&
        isAPositiveNumber(clear_amount) &&
        clear_amount <= due &&
        clear_amount > 0
    ){
        if(clear_amount === due){
            $('#clear-all-dues').click();
        }else{
            $('#clear-dues').modal('toggle');
            bootbox.confirm({
                message: `Are you sure you want to clear Rs. ${clear_amount} from this receipt?`,
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
                            url  : '/inventory/clear/sales/partial',
                            data : {
                                id : receipt_id,
                                amount : clear_amount,
                                _token : getToken()
                            },
                            success : function(done){
                                if(done){
                                    bootbox.alert(
                                        `Rs. ${clear_amount} has been cleared from receipt`
                                    );
                                }
                            }
                        });
                    }
                }
            });
        }
    }else{
        $('#due-amount').val('').attr('placeholder','Invalid Amount');
    }
    console.log(receipt_id, due);
}


/**
 * Clear all dues
 * @param e
 */
function clearAllDues(e){
    let receipt_id = $(this).attr('data-receipt-id');
    console.log(receipt_id);

    $('#clear-dues').modal('toggle');

    bootbox.confirm({
        message: "Are you sure you want to clear dues for this receipt?",
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
                    url  : '/inventory/clear/sales',
                    data : {
                        id : receipt_id,
                        _token : getToken()
                    },
                    success : function(done){
                        if(done){
                            markReceiptAsPaid(receipt_id);
                        }
                    }
                });
            }
        }
    });
}

/**
 * Mark receipt as paid
 * @param id
 */
function markReceiptAsPaid(id){
    $(".receipt-list[data-receipt-id='" + id +"']").removeClass('unpaid')
        .find('.pay-status').html('Paid');
}