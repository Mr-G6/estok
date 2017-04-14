class Checkout {

    /**
     * Get CSRF Token
     * @returns {*|jQuery}
     */
    getToken() {
        return $(".checkout").attr('data-token');
    }

    /**
     * Is an Integer
     * @param value
     * @returns {boolean}
     */
    isInt(value) {
        var er = /^-?[0-9]+$/;
        return er.test(value);
    }

    /**
     * Is Numeric
     * @param value
     * @returns {boolean}
     */
    isNumeric(value) {
        return !isNaN(value);
    }

    /**
     * Is a positive number
     * @param value
     * @returns {boolean}
     */
    isAPositiveNumber(value) {
        return (value > 0);
    }

    /**
     * ---------------------
     * Checkout Form
     * ---------------------
     */

    /**
     * Get Product Names for checkout autocomplete input
     * @param inventory_id
     * @param context
     */
    getNames(inventory_id, context) {
        $.ajax({
            url: '/inventory/checkout/' + inventory_id + '/products',
            success: function (res) {
                context.productNames = res;
                context.setAutoComplete();
            }
        });
    }

    /**
     * Set Product names for checkout autocomplete input
     */
    setAutoComplete() {
        var _this = this,
            inventory_id = this.getWareHouseId();
        $("#c_p_name").autocomplete({
            source: this.productNames,
            select: function (event, ui) {
                var label = ui.item.label;
                var value = ui.item.value;
                _this.clearFields(['#c_p_quantity', '#c_p_r_price']);
                _this.setProductDetails(inventory_id, value);
                $("#c_p_quantity").focus();
                _this.errors.clearErrors();
            }
        });
    }

    /**
     * Clear Checkout input fields after add product
     * @param fields
     */
    clearFields(fields) {
        fields.forEach(function (field) {
            $(field).val('');
        });
    }

    /**
     * Set Add Product Details
     * @param inventory_id
     * @param name
     */
    setProductDetails(inventory_id, name) {
        var _this = this;
        $.ajax({
            type: 'GET',
            url: '/inventory/checkout/product/details',
            data: {
                inventory_id: inventory_id,
                name: name
            },
            success: function (product) {
                _this.product.id = product.id;
                _this.product.unit_price = product.unit_price;
                _this.product.retail_price = product.retail_price;
                _this.setUnitPrice(_this.product.unit_price);
                _this.setRetailPrice(_this.product.retail_price);
            }
        });
    }

    /**
     * Set Unit price
     * @param quantity
     */
    setUnitPrice(quantity) {
        $("#c_p_unit_price").val(quantity);
    }

    /**
     * Get Product names
     * @returns {*}
     */
    getName() {
        return $.trim($("#c_p_name").val());
    }

    /**
     * Get Product quantity
     * @returns {*}
     */
    getQuantity() {
        return $.trim($("#c_p_quantity").val());
    }

    getDiscount(){
        return $.trim($("#c_p_discount").val());
    }

    /**
     * Get Retail price
     * @returns {*}
     */
    getRetailPrice() {
        return $.trim($("#c_p_r_price").val());
    }

    /**
     * Set Retail Price
     * @param price
     */
    setRetailPrice(price){
        $("#c_p_r_price").val(price);
    }

    /**
     * Get Unit price
     * @returns {*}
     */
    getUnitPrice() {
        return $.trim($("#c_p_unit_price").val());
    }

    /**
     * Get warehouse id
     * @returns {*|jQuery}
     */
    getWareHouseId() {
        return $(".checkout").attr('data-wh-id');
    }

    /**
     * Process Checkout Form Submission
     * @param e
     */
    checkout(e) {
        var _this = e.data.context,
            name = _this.getCheckoutName(),
            address = _this.getCheckoutAddress(),
            phone_no = _this.getCheckoutPhoneNo(),
            pay_later = _this.payLater();
        console.log(pay_later);
        if(pay_later){
            if(!name.length || !address.length || !phone_no.length){
                bootbox.alert('Please provide valid name, address or phone no to avail pay later option');
                return;
            }
        }else{
            if(!name.length || !address.length || !phone_no.length){
                name = name.length ? name : 'N/A',
                address = address.length ? address : 'N/A',
                phone_no = phone_no.length ? phone_no : 'N/A';
                console.log(name, address, phone_no);
            }
        }

        $.ajax({
            type: "POST",
            url: '/inventory/checkout/transaction',
            data: {
                inventory_id: _this.getWareHouseId(),
                _token: _this.getToken(),
                items: JSON.stringify(_this.products),
                buyer: name,
                address: address,
                phone: phone_no,
                paid : pay_later ? 0 : 1
            },
            success: function (res) {
                if (res) {
                    $(".checkout-confirmation").modal('show');
                    $('.checkout-confirmation').find('p').text('Checkout Successful, to view transaction record visit warehouse transactions page');
                    _this.clearCheckout();
                    _this.setCheckoutTotals();
                    _this.products = [];
                }
            }
        });
    }

    /**
     * Remove items from checkout
     */
    clearCheckout() {
        this.clearCheckoutFields();
        $("#checkout-list").find('.item').remove();
        this.handleCheckoutSubmit();
    }

    /**
     * Get Checkout Buyer name
     * @returns {*}
     */
    getCheckoutName() {
        return $.trim($('#receipt_name').val());
    }

    /**
     * Get Checkout Buyer Address
     * @returns {*}
     */
    getCheckoutAddress() {
        return $.trim($('#receipt_address').val());
    }

    payLater() {
        return $('#pay-later').is(':checked');
    }

    /**
     * Get Checkout Buyer phone #
     * @returns {*}
     */
    getCheckoutPhoneNo() {
        return $.trim($('#receipt_no').val());
    }

    validateDiscount(discount){
        if(!discount.length){
            return true;
        }

        if(discount.length){
            if(this.isNumeric(discount) && discount >= 0){
                return true;
            }
        }

        return false;
    }

    /**
     * Add Product to checkout list
     * @param e
     */
    addProduct(e) {
        var _this = e.data.context;
        if (e.which == 1 || e.which == 13) {
            var name = _this.getName(),
                quantity = _this.getQuantity(),
                r_price = _this.getRetailPrice(),
                unit_price = _this.getUnitPrice(),
                discount = _this.getDiscount(),
                inventory_id = _this.getWareHouseId();

            _this.errors.clearErrors();
            if (name.length && quantity.length && r_price.length && unit_price.length &&
                _this.productInProducts(name) &&
                _this.validateDiscount(discount) &&
                _this.isInt(quantity) &&
                _this.isNumeric(quantity) &&
                _this.isNumeric(r_price) &&
                _this.isNumeric(unit_price)
            ) {
                _this.validateQuantity(inventory_id, name);
            } else {
                if (!name.length) {
                    _this.errors.add("Product Name Required");
                }

                if (!quantity.length) {
                    _this.errors.add("Product Quantity Required");
                }

                if (!r_price.length) {
                    _this.errors.add("Product Retail Price Required");
                }

                if (!unit_price.length) {
                    _this.errors.add("Product Unit Price Required");
                }

                if (!_this.productInProducts(name)) {
                    _this.errors.add("Product Doesn't Exist in Stock");
                }

                if (!_this.isInt(quantity)) {
                    _this.errors.add("Quantity must be an Integer");
                }

                if (!_this.isNumeric(quantity)) {
                    _this.errors.add("Invalid Quantity");
                }

                if (!_this.isNumeric(r_price)) {
                    _this.errors.add("Invalid Retail Price");
                }

                if (!_this.isNumeric(unit_price)) {
                    _this.errors.add("Invalid Unit Price");
                }

                if(!_this.validateDiscount(discount)){
                    _this.errors.add("Invalid Discount Price");
                }

                _this.errors.appendErrorsToDOM();
                _this.errors.showErrorDOM();
            }
        } else {
            _this.errors.clearErrors();
        }
    }

    /**
     * Check if product exist in Checkout Products list
     * @param product
     * @returns {boolean}
     */
    productInProducts(product) {
        return (this.productNames.indexOf(product) > -1);
    }

    /**
     * Validate product quanity in stock
     * @param inventory_id
     * @param name
     */
    validateQuantity(inventory_id, name) {
        var _this = this,
            name = this.getName(),
            quantity = this.getQuantity(),
            r_price = this.getRetailPrice(),
            discount = this.getDiscount().length ? this.getDiscount() : 0,
            unit_price = this.getUnitPrice();

        this.errors.clearErrors();

        $.ajax({
            url: '/inventory/checkout/product/quantity',
            data: {
                inventory_id: inventory_id,
                name: name
            },
            success: function (res) {
                if (quantity > parseInt(res)) {
                    _this.errors.add("Not Enough Stock Available for this Product.");
                    _this.errors.appendErrorsToDOM();
                    _this.errors.showErrorDOM();
                } else {
                    _this.product.name = name;
                    _this.product.discount = discount;
                    _this.product.quantity = quantity;
                    _this.product.r_price = r_price - discount;
                    _this.product.unit_price = unit_price;
                    _this.product.total_quantity = res;

                    if (_this.findIndex(name) != -1) {
                        _this.errors.add("Products already added to checkout list, use edit feature to change values.");
                        _this.errors.appendErrorsToDOM();
                        _this.errors.showErrorDOM();
                    } else {
                        _this.products.push(_this.product);
                        _this.renderToCheckout(_this.product);
                        _this.clearCheckoutFields();
                        _this.handleCheckoutSubmit();
                        _this.product = {};
                    }
                }
            }
        });
    }

    /**
     * Find Index of product in Checkout products list
     * @param name
     * @returns {number}
     */
    findIndex(name) {
        for (var i = 0; i < this.products.length; i++) {
            if (this.products[i].name === name) {
                return i;
            }
        }
        return -1;
    }

    /**
     * Render product to checkout list
     * @param product
     */
    renderToCheckout(product) {
        var $item = `<tr class='item' data-name='${product.name}'>
                        <td class='item-name'>${product.name}</td>
                        <td class='item-quantity'>${product.quantity}</td>
                        <td class='item-price'>${product.r_price}</td>
                        <td class='item-unit_price'>${product.unit_price}</td>
                        <td class="'item-total-price">${product.unit_price * product.quantity}</td>
                        <td class="item-total-retail">${product.r_price * product.quantity}</td>
                        <td class="item-discount">${product.discount}</td>
                        <td>
                            <span class="glyphicon glyphicon-edit item-edit" aria-hidden="true"></span>
                            <span class="glyphicon glyphicon-trash item-delete" aria-hidden="true"></span>
                        </td>
                    </tr>`;
        $("#checkout-list").append($item);
        this.setEditDelete();
        this.setCheckoutTotals();
    }

    /**
     * Set Checkout total on product add
     */
    setCheckoutTotals() {
        $(".checkout-actions").find('.total-items').text(this.getTotalItems());
        $(".checkout-actions").find('.cost-total').text(this.getTotalCost());
        $(".checkout-actions").find('.retail-total').text(this.getProductsTotalRetail());
    }

    /**
     * Get Products Total Retail
     * @returns {number}
     */
    getProductsTotalRetail() {
        var sum = 0;
        for (var i = 0; i < this.products.length; i++) {
            sum += parseFloat(this.products[i].r_price * this.products[i].quantity);
        }
        return sum;
    }

    /**
     * Get Products Total Cost
     * @returns {number}
     */
    getTotalCost() {
        var sum = 0;
        for (var i = 0; i < this.products.length; i++) {
            sum += parseFloat(this.products[i].unit_price * this.products[i].quantity);
        }
        return sum;
    }

    /**
     * Get Total Productsi in Checkout list
     * @returns {Number}
     */
    getTotalItems() {
        return this.products.length;
    }

    /**
     * Set Edit Delete Event handlers
     * on new Checkout list item
     */
    setEditDelete() {
        $(".item-edit,.item-delete").unbind('click');
        $(".item-edit").on('click', {context: this}, this.editProduct);
        $(".item-delete").on('click', {context: this}, this.deleteProduct);
    }

    /**
     * Edit Product
     * @param e
     */
    editProduct(e) {
        var _this = e.data.context,
            item_name = $(this).parents('.item').attr('data-name'),
            item_quantity = $.trim($(this).parents('.item').children('.item-quantity').text()),
            item_price = $.trim($(this).parents('.item').children('.item-price').text()),
            item_unit_price = $.trim($(this).parents('.item').children('.item-unit_price').text()),
            discount = $.trim($(this).parents('.item').children('.item-discount').text());

        $("#c_p_name").val(item_name);
        $("#c_p_quantity").val(item_quantity);
        $("#c_p_r_price").val(item_price);
        $("#c_p_discount").val(discount);
        $("#c_p_unit_price").val(item_unit_price);

        _this.products.splice(_this.findIndex(item_name), 1);
        $(this).parents('.item').remove();
        _this.errors.clearErrors();
        $("#c_p_quantity").focus();
        _this.handleCheckoutSubmit();
        _this.setCheckoutTotals();
    }

    /**
     * Delete Product From Checkout Products list
     * @param e
     */
    deleteProduct(e) {
        var _this = e.data.context,
            item_name = $(this).parents('.item').attr('data-name');

        _this.products.splice(_this.findIndex(item_name), 1);
        $(this).parents('.item').remove();
        _this.handleCheckoutSubmit();
        _this.setCheckoutTotals();
    }

    /**
     * Clear Checkout field on product add in checkout list
     */
    clearCheckoutFields() {
        $("#c_p_name").val('').focus();
        $("#c_p_quantity").val('');
        $("#c_p_r_price").val('');
        $("#c_p_unit_price").val('');
        $("#c_p_discount").val('');
        $("#receipt_name, #receipt_address, #receipt_no").val('');
        this.product = {};
    }

    /**
     * Show Checkout footer options if item_count
     */
    handleCheckoutSubmit() {
        var items_count = $("#checkout-list").find('.item').length;
        console.log(items_count);

        if (items_count > 0) {
            $(".checkout-actions").show();
        } else {
            $(".checkout-actions").hide();
        }
    }

    /**
     * Set Product details event on Product name input
     */
    handleProductSubmit() {
        var _this = this;
        $("#c_p_name").on('keypress', function (e) {
            _this.errors.clearErrors();
            if (e.which == 13) {
                console.log("Set Product");

                var name = _this.getName(),
                    inventory_id = _this.getWareHouseId();

                if (_this.productInProducts(name)) {
                    _this.clearFields(['#c_p_quantity', '#c_p_r_price']);
                    _this.setProductDetails(inventory_id, name);
                    $("#c_p_quantity").focus();
                } else {
                    _this.errors.add("Product Doesn't Exist in Stock");
                    _this.errors.appendErrorsToDOM();
                    _this.errors.showErrorDOM();
                }
            }
        });
    }

    constructor() {
        this.productNames = [];

        this.product = {};

        this.products = [];

        this.errors = [];

        this.errors = new Errors('#c_p_error');

        var inventory_id = this.getWareHouseId();
        this.getNames(inventory_id, this);
        this.handleProductSubmit();
        $("#product-add").on('click', {context: this}, this.addProduct);
        $("#c_p_quantity, #c_p_r_price, #c_p_discount").on('keypress', {context: this}, this.addProduct);
        $("#go-checkout").on('click', {context: this}, this.checkout);
    }
}

var checkout = new Checkout();