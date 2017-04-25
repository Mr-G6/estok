let $addProd = $('#add-product-form'),
    $editProd = $('#edit-product-form'),
    $prodDetails = $('.product-details'),
    $prodSearch = $('#product-search'),
    $prodDelete = $('.delete-product'),
    $modal = $('#product-dt-modal'),
    $modal_table = $('#product-dt-modal .modal-body .table');


addEventHandler($addProd, {action : 'add'}, 'submit', validateForm);
addEventHandler($editProd, {action : 'edit'}, 'submit', validateForm);
addEventHandler($prodDetails, {}, 'click', showProductDetails);
addEventHandler($prodSearch, {}, 'keyup', productSearch);
addEventHandler($prodDelete, {}, 'click', confirmProductDelete);
addEventHandler($modal, {}, 'hidden.bs.modal', clearTransactionModalBody);

function getProductID(){
    return $.trim($("#p_id").val());
}

function getName(){
    return $.trim($("#p_name").val());
}

function getOldName(){
    return $("#p_name").attr('data-old-name');
}

function getUnitPrice(){
    return $.trim($("#unit_price").val());
}

function getRetailPrice(){
    return $.trim($("#retail_price").val());
}

function getQuantity(){
    return $.trim($("#p_quantity").val());
}

function getWareHouseId(){
    return $.trim($("#w_id").val());
}

function detectEditChange(){
    var name = getName(),
        old_name = getOldName();

    return (name === old_name);
}

function confirmProductDelete(e) {
    e.preventDefault();
    var _this = this;
    bootbox.confirm("Are you sure you want to delete this product!", function(result){
        if(result){
            window.location = $(_this).attr('href');
        }
    });

}
function validateForm(e){
    e.preventDefault();
    let action = (e.data.action),
        name = getName(),
        unit_price = getUnitPrice(),
        retail_price = getRetailPrice(),
        p_quantity = getQuantity(),
        inventory_id = getWareHouseId();

    removeEventHandler($addProd, 'submit');

    if (isNumeric(unit_price) &&
        isNumeric(retail_price) &&
        isInt(p_quantity) &&
        isAPositiveNumber(unit_price) &&
        isAPositiveNumber(p_quantity) &&
        isAPositiveNumber(retail_price)
    ) {
        if (!detectEditChange()) {
            verifyProductExist(inventory_id, name, action);
            errors.hideErrorDOM();
        } else {
            if (action === 'edit') {
                updateProduct(
                    getWareHouseId(),
                    getProductID(),
                    getName(),
                    getUnitPrice(),
                    getRetailPrice(),
                    getQuantity(),
                    getToken()
                );
            }

            if (action === 'add') {
                saveProduct(
                    getWareHouseId(),
                    getName(),
                    getUnitPrice(),
                    getRetailPrice(),
                    getQuantity(),
                    getToken()
                );
            }
        }
    } else {
        addEventHandler($addProd, {action : 'add'}, 'submit', validateForm);

        errors.clearErrors();
        if (!isNumeric(unit_price)) {
            errors.add("Price must be a numerical value.");
        }

        if (!isNumeric(retail_price)) {
            errors.add("Retail must be a numerical value.");
        }

        if (!isInt(p_quantity)) {
            errors.add("Quantity must be a Integer value.");
        }

        if(!isAPositiveNumber(unit_price)){
            errors.add("Invalid Price value.");
        }

        if(!isAPositiveNumber(p_quantity)){
            errors.add("Invalid Quantity value.");
        }

        if(!isAPositiveNumber(retail_price)){
            errors.add("Invalid Retail Price value.");
        }

        errors.appendErrorsToDOM();
        errors.showErrorDOM();
    }
    return false;
}


function verifyProductExist(inventory_id, name, action){
    $.ajax({
        url: '/inventory/product/exist',
        type: 'GET',
        data: {
            name: name,
            inventory_id: inventory_id
        },
        success: function (res) {
            if (res) {
                errors.clearErrors();
                errors.add("Item Already Exist.");
                errors.appendErrorsToDOM();
                errors.showErrorDOM();
                addEventHandler($addProd, {action : 'add'}, 'submit', validateForm);
            } else {
                if (action === 'edit') {
                    updateProduct(
                        getWareHouseId(),
                        getProductID(),
                        getName(),
                        getUnitPrice(),
                        getRetailPrice(),
                        getQuantity(),
                        getToken()
                    );
                }

                if (action === 'add') {
                    saveProduct(
                        getWareHouseId(),
                        getName(),
                        getUnitPrice(),
                        getRetailPrice(),
                        getQuantity(),
                        getToken()
                    );
                }
            }
        }
    });
}

function saveProduct(inventory_id, name, unit_price, retail_price, quantity, token){
    $.ajax({
        url: '/inventory/product/add',
        type: 'POST',
        data: {
            _token: token,
            id: inventory_id,
            p_name: name,
            unit_price: unit_price,
            retail_price : retail_price,
            p_quantity: quantity
        },
        success: function (res) {
            if (res) {
                window.location = '/inventory/' + inventory_id + '/products';
            }
        }
    });
}

function updateProduct(inventory_id, p_id, name, unit_price, retail_price, quantity, token){
    $.ajax({
        url: '/inventory/product/update',
        type: 'POST',
        data: {
            _token: token,
            id: inventory_id,
            p_id: p_id,
            p_name: name,
            unit_price: unit_price,
            retail_price : retail_price,
            p_quantity: quantity
        },
        success: function (res) {
            if (res) {
                window.location = '/inventory/' + inventory_id + '/products';
            }
        }
    });
}

function showProductDetails(e){
    e.preventDefault();
    let prod_id = $.trim($(this).attr('data-product-id')),
        prod_name = $.trim($(this).attr('data-product-name')),
        inventory_id = $.trim($(this).attr('data-inventory-id'));
    console.log(inventory_id);

    $.ajax({
        type : 'GET',
        url : '/inventory/'+inventory_id+'/profit/byName',
        data : {
            id : prod_id,
            name : prod_name,
        },
        success : function(transactions){
            if(transactions.length){
                setTransactionProductName(prod_name);
                appendTableHeader();
                appendItemsToDOM(transactions);
                showTransactionsModal();
            }else{
                bootbox.alert("No transactions available for this product!");
            }
        }
    });
}


function showTransactionsModal(){
    $($modal).modal('show');
}

function clearTransactionModalBody(){
    $($modal_table).empty();
}

function setTransactionProductName(name){
    $("#product-dt-title").text(name + ' Details');
}

function appendTableHeader(){
    var head_titles = ['# ID', 'Quantity', 'Total Cost', 'Total Retail', 'Profit', 'Date', 'Time'];
    var $head = `<thead><tr><th>${head_titles.join('</th><th>')}</th></tr></thead>`;
    $($modal_table).append($head);
}

function appendItemsToDOM(transactions){
    transactions.forEach(function (transaction) {
        var $transaction = [
            transaction.id,
            transaction.quantity,
            transaction.cost_total,
            transaction.retail_total,
            transaction.retail_total - transaction.cost_total,
            moment(transaction.created_at).format('YYYY-MM-DD'),
            moment(transaction.created_at).format('HH:mm:ss a')
        ];
        var $item = `<tbody><tr><td>${$transaction.join('</td><td>')}</td></tr></tbody>`;
        $($modal_table).append($item);
    });
}

function productSearch(e){
    e.preventDefault();
    var query = $.trim($(this).val()).toLowerCase();

    var $products = $(".product-search-name");
    if(query.length){
        console.log($products.length);
        for(var i = 0; i < $products.length; i++){
            var value = $.trim($($products[i]).attr('data-name')).toLowerCase();
            if(value.search(query) != -1){
                $($products[i]).parent('tr').slideDown();
            }else{
                $($products[i]).parent('tr').slideUp();
            }
        }
    }else{
        $($products).parents('tr').slideDown();
    }
}





