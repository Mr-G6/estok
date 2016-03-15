var Checkout;
Checkout = function () {

    this.productNames = [];

    this.product = {};

    this.products = [];

    this.errors = [];

    /**
     * ----------------------
     * Error Handling Methods
     * ----------------------
     */

    /**
     * Clear Errors and Hide Error DOM
     */
    this.clearErrors = function () {
        this.errors = [];
        this.hideErrorDoM();
    };

    /**
     * Clear and Hide Error DOM
     */
    this.hideErrorDoM = function () {
        $("#c_p_error").slideUp();
        $("#c_p_error").html('');
    };

    /**
     * Show Error DOM
     */
    this.showErrorDoM = function () {
        $("#c_p_error").slideDown();
    };

    /**
     * Append Errors on Error DOM
     */
    this.appendErrorsOnDom = function () {
        this.errors.forEach(function (err) {
            $("<li>" + err + "</li>").appendTo("#c_p_error");
        });
    };

    /**
     * Clear Input Fields
     * @param fields
     */
    this.clearFields = function (fields) {
        fields.forEach(function (field) {
            $(field).val('');
        });
    };

    /**
     * Clear Checkout Input Fields
     */
    this.clearCheckoutFields = function(){
        $("#c_p_name").val('').focus();
        $("#c_p_quantity").val('');
        $("#c_p_r_price").val('');
        $("#c_p_unit_price").val('');
        this.product = {};
    };

    this.clearCheckout = function(){
        this.clearCheckoutFields();
        $("#checkout-list").find('.item').remove();
        this.handleCheckoutSubmit();
    };

    /**
     * ---------------------
     * Getter Setter Methods
     * ---------------------
     */

    /**
     * Get Checkout Product Name
     * @returns {*}
     */
    this.getName = function () {
        return $.trim($("#c_p_name").val());
    };

    /**
     * Get Checkout Product Quantity
     * @returns {*}
     */
    this.getQuantity = function () {
        return $.trim($("#c_p_quantity").val());
    };

    /**
     * Get Checkout Product Retail Price
     * @returns {*}
     */
    this.getRetailPrice = function () {
        return $.trim($("#c_p_r_price").val());
    };

    /**
     * Get Checkout Product Unit Price
     * @returns {*}
     */
    this.getUnitPrice = function () {
        return $.trim($("#c_p_unit_price").val());
    };

    /**
     * Get WareHouse ID
     * @returns {*|jQuery}
     */
    this.getWareHouseId = function () {
        return $(".checkout").attr('data-wh-id');
    };

    /**
     * Set Checkout Product Unit Price
     * @param quantity
     */
    this.setUnitPrice = function (quantity) {
        $("#c_p_unit_price").val(quantity);
    };

    /**
     * Set AutoComplete on Checkout Product Name Field
     */
    this.setAutoComplete = function () {
        var _this = this,
            wh_id = this.getWareHouseId();
        $("#c_p_name").autocomplete({
            source: this.productNames,
            select: function (event, ui) {
                var label = ui.item.label;
                var value = ui.item.value;
                _this.clearFields(['#c_p_quantity' , '#c_p_r_price']);
                _this.setProductDetails(wh_id, value);
                $("#c_p_quantity").focus();
                _this.clearErrors();
            }
        });
    };

    /**
     * Find Index by Product Name
     */

    this.findIndex = function(name){
        for(var i = 0; i< this.products.length; i++){
            if(this.products[i].name === name){
                return i;
            }
        }
        return -1;
    };

    /**
     *
     */
    this.getToken = function(){
        return $(".checkout").attr('data-token');
    };

    this.getProductsTotalRetail = function(){
        var sum = 0;
        for(var i =0; i<this.products.length; i++){
            sum += parseFloat(this.products[i].r_price * this.products[i].quantity);
        }
        return sum;
    };

    this.getTotalCost = function(){
        var sum = 0;
        for(var i =0; i<this.products.length; i++){
            sum += parseFloat(this.products[i].unit_price * this.products[i].quantity);
        }
        return sum;
    };

    this.getTotalItems = function(){
        return this.products.length;
    };

    this.setCheckoutTotals = function(){
        $(".checkout-actions").find('.total-items').text(this.getTotalItems());
        $(".checkout-actions").find('.cost-total').text(this.getTotalCost());
        $(".checkout-actions").find('.retail-total').text(this.getProductsTotalRetail());
    };

    /**
     * ------------------
     * Validation Methods
     * ------------------
     */

    /**
     * Check if Product Exist in Product Names
     * @param product
     * @returns {boolean}
     */
    this.productInProducts = function (product) {
        return (this.productNames.indexOf(product) > -1);
    };

    /**
     * Check if number is an integer
     * @param value
     * @returns {boolean}
     */
    this.isInt = function (value) {
        var er = /^-?[0-9]+$/;
        return er.test(value);
    };

    /**
     * Check if number is numeric
     * @param value
     * @returns {boolean}
     */
    this.isNumeric = function (value) {
        return !isNaN(value);
    };

    /**
     * Check if Number is postitive
     * @param value
     * @returns {boolean}
     */
    this.isAPositiveNumber = function (value) {
        return (value > 0);
    };

    /**
     * -------------------
     * Xhr Request Methods
     * -------------------
     */

    /**
     * All Warehouse Products for Checkout
     * @param wh_id
     * @param context
     */
    this.getNames = function (wh_id, context) {
        $.ajax({
            url: '/warehouse/checkout/' + wh_id + '/products',
            success: function (res) {
                context.productNames = res;
                context.setAutoComplete();
            }
        });
    };

    /**
     * Set Checkout Product Input Fields
     * @param wh_id
     * @param name
     */
    this.setProductDetails = function (wh_id, name) {
        var _this = this;
        $.ajax({
            type: 'GET',
            url: '/warehouse/checkout/product/details',
            data: {
                wh_id: wh_id,
                name: name
            },
            success: function (product) {
                _this.product.unit_price = product.unit_price;
                _this.setUnitPrice(_this.product.unit_price);
            }
        });
    };

    /**
     * Check Product Quantity
     */
    this.validateQuantity = function (wh_id, name) {
        var _this = this,
            name = this.getName(),
            quantity = this.getQuantity(),
            r_price = this.getRetailPrice(),
            unit_price = this.getUnitPrice();

        this.clearErrors();

        $.ajax({
            url: '/warehouse/checkout/product/quantity',
            data: {
                wh_id: wh_id,
                name: name
            },
            success: function (res) {
                if (quantity > parseInt(res)) {
                    _this.errors.push("Not Enough Stock Available for this Product.");
                    _this.appendErrorsOnDom();
                    _this.showErrorDoM();
                } else {
                    _this.product.name = name;
                    _this.product.quantity = quantity;
                    _this.product.r_price = r_price;
                    _this.product.unit_price = unit_price;
                    _this.product.total_quantity = res;

                    if(_this.findIndex(name) != -1){
                        _this.errors.push("Products already added to checkout list, use edit feature to change values.");
                        _this.appendErrorsOnDom();
                        _this.showErrorDoM();
                    }else{
                        _this.products.push(_this.product);
                        _this.renderToCheckout(_this.product);
                        _this.clearCheckoutFields();
                        _this.handleCheckoutSubmit();
                        _this.product = {};
                    }
                }
            }
        });
    };

    this.handleCheckoutSubmit = function(){
        var items_count = $("#checkout-list").find('.item').length;
        console.log(items_count);

        if(items_count > 0){
            $(".checkout-actions").show();
        }else{
            $(".checkout-actions").hide();
        }
    };

    /**
     * Render Product to Checkout List
     * @param product
     */
    this.renderToCheckout = function (product) {
        var $item = `<tr class='item' data-name='${product.name}'>
                        <td class='item-name'>${product.name}</td>
                        <td class='item-quantity'>${product.quantity}</td>
                        <td class='item-price'>${product.r_price}</td>
                        <td class='item-unit_price'>${product.unit_price}</td>
                        <td class="'item-total-price">${product.unit_price * product.quantity}</td>
                        <td class="item-total-retail">${product.r_price * product.quantity}</td>
                        <td>
                            <span class="glyphicon glyphicon-edit item-edit" aria-hidden="true"></span>
                            <span class="glyphicon glyphicon-trash item-delete" aria-hidden="true"></span>
                        </td>
                    </tr>`;
        $("#checkout-list").append($item);
        this.setEditDelete();
        this.setCheckoutTotals();
    };

    /**
     * Edit Product
     * @param e
     */
    this.editProduct = function(e){
        var _this = e.data.context,
            item_name = $(this).parents('.item').attr('data-name'),
            item_quantity = $.trim($(this).parents('.item').children('.item-quantity').text()),
            item_price = $.trim($(this).parents('.item').children('.item-price').text()),
            item_unit_price = $.trim($(this).parents('.item').children('.item-unit_price').text());

        $("#c_p_name").val(item_name);
        $("#c_p_quantity").val(item_quantity);
        $("#c_p_r_price").val(item_price);
        $("#c_p_unit_price").val(item_unit_price);

        _this.products.splice(_this.findIndex(item_name), 1);
        $(this).parents('.item').remove();
        _this.clearErrors();
        $("#c_p_quantity").focus();
        _this.handleCheckoutSubmit();
        _this.setCheckoutTotals();
    };

    /**
     * Delete Product
     * @param e
     */
    this.deleteProduct = function(e){
        var _this = e.data.context,
            item_name = $(this).parents('.item').attr('data-name');

        _this.products.splice(_this.findIndex(item_name), 1);
        $(this).parents('.item').remove();
        _this.handleCheckoutSubmit();
        _this.setCheckoutTotals();
    };

    /**
     * Add Edit/Delete item event listeners
     */
    this.setEditDelete = function(){
        $(".item-edit,.item-delete").unbind('click');

        $(".item-edit").on('click', {context :this }, this.editProduct);
        $(".item-delete").on('click', {context : this}, this.deleteProduct);
    };

    /**
     * Checkout Product Name custom submit handlers
     */
    this.handleProductSubmit = function(){
        var _this = this;
        $("#c_p_name").on('keypress', function(e){
            _this.clearErrors();
            if(e.which == 13){
                console.log("Set Product");

                var name = _this.getName(),
                    wh_id = _this.getWareHouseId();

                if(_this.productInProducts(name)){
                    _this.clearFields(['#c_p_quantity' , '#c_p_r_price']);
                    _this.setProductDetails(wh_id, name);
                    $("#c_p_quantity").focus();
                }else{
                    _this.errors.push("Product Doesn't Exist in Stock");
                    _this.appendErrorsOnDom();
                    _this.showErrorDoM();
                }
            }
        });
    };

    /**
     * Handle Add Product To Checkout List
     * @param e
     */
    this.addProduct = function (e) {
        var _this = e.data.context;
        if(e.which == 1 || e.which == 13){
                var name = _this.getName(),
                quantity = _this.getQuantity(),
                r_price = _this.getRetailPrice(),
                unit_price = _this.getUnitPrice(),
                wh_id = _this.getWareHouseId();

            _this.clearErrors();
            if (name.length && quantity.length && r_price.length && unit_price.length &&
                _this.productInProducts(name) &&
                _this.isInt(quantity) &&
                _this.isNumeric(quantity) &&
                _this.isNumeric(r_price) &&
                _this.isNumeric(unit_price)
            ) {
                _this.validateQuantity(wh_id, name);
            } else {
                if (!name.length) {
                    _this.errors.push("Product Name Required");
                }

                if (!quantity.length) {
                    _this.errors.push("Product Quantity Required");
                }

                if (!r_price.length) {
                    _this.errors.push("Product Retail Price Required");
                }

                if (!unit_price.length) {
                    _this.errors.push("Product Unit Price Required");
                }

                if (!_this.productInProducts(name)) {
                    _this.errors.push("Product Doesn't Exist in Stock");
                }

                if (!_this.isInt(quantity)) {
                    _this.errors.push("Quantity must be an Integer");
                }

                if (!_this.isNumeric(quantity)) {
                    _this.errors.push("Invalid Quantity");
                }

                if (!_this.isNumeric(r_price)) {
                    _this.errors.push("Invalid Retail Price");
                }

                if (!_this.isNumeric(unit_price)) {
                    _this.errors.push("Invalid Unit Price");
                }
                _this.appendErrorsOnDom();
                _this.showErrorDoM();
            }
        }else{
            _this.clearErrors();
        }
    };

    /**
     * Process Checkout
     * @param e
     */
    this.checkout = function(e){
        var _this = e.data.context;
        $.ajax({
            type : "POST",
            url : '/warehouse/checkout/transaction',
            data : {
                wh_id : _this.getWareHouseId(),
                _token : _this.getToken(),
                items : JSON.stringify(_this.products)
            },
            success : function(res){
                if(res){
                    $(".checkout-confirmation").modal('show');
                    $('.checkout-confirmation').find('p').text('Checkout Successful, to view transaction record visit warehouse transactions page');
                    _this.clearCheckout();
                    _this.setCheckoutTotals();
                    _this.products = [];
                }
            }
        });
    };

    /**
     * Initialize Checkout
     */
    this.init = function () {
        var wh_id = this.getWareHouseId();
        this.getNames(wh_id, this);
        this.handleProductSubmit();
        $("#product-add").on('click', {context: this}, this.addProduct);
        $("#c_p_quantity, #c_p_r_price").on('keypress', {context : this}, this.addProduct);
        $("#go-checkout").on('click', {context : this}, this.checkout);
    };
};

var checkout = new Checkout();
checkout.init();