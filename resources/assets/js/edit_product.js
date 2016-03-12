var EditProduct = function(){
    this.errors = [];

    /**
     * Returns Product Name
     * @returns {*}
     */
    this.getName = function(){
        return $.trim($("#p_name").val());
    };

    /**
     * Returns Product Price
     * @returns {*}
     */
    this.getProductPrice = function(){
        return $.trim($("#p_price").val());
    };

    /**
     * Returns Product Retail Price
     * @returns {*}
     */
    this.getRetailPrice = function(){
        return $.trim($("#p_r_price").val());
    };

    /**
     * Returns Product Quantity
     * @returns {*}
     */
    this.getQuantity = function(){
        return $.trim($("#p_quantity").val());
    };

    /**
     * Returns Warehouse ID
     * @returns {*}
     */
    this.getWareHouseId = function(){
        return $.trim($("#w_id").val());
    };

    /**
     * Returns Product ID
     * @returns {*}
     */
    this.getProductID = function(){
        return $.trim($("#p_id").val());
    };

    /**
     * Return Old Product Name
     * @returns {*|jQuery}
     */
    this.getOldName = function(){
        return $("#p_name").attr('data-old-name');
    }

    /**
     * Check if number is an integer
     * @param value
     * @returns {boolean}
     */
    this.isInt = function(value){
        var er = /^-?[0-9]+$/;
        return er.test(value);
    };

    /**
     * Check if number is numeric
     * @param value
     * @returns {boolean}
     */
    this.isNumeric = function(value) {
        return !isNaN(value);
    };

    /**
     * Return CSRF Token value
     * @returns {*}
     */
    this.getToken = function(){
        return $.trim($("#token").val());
    }

    /**
     * Clean all Form Errors
     */
    this.clearErrors = function(){
        this.errors = [];
        this.hideErrorDoM();
    };

    /**
     * Clear and Hide Form Error DOM
     */
    this.hideErrorDoM = function(){
        $("#p_error").slideUp();
        $("#p_error").html('');
    };

    /**
     * Display Error DOM
     */
    this.showErrorDoM = function(){
        $("#p_error").slideDown();
    };

    /**
     * Append Errors to DOM
     */
    this.appendErrorsOnDom = function(){
        this.errors.forEach(function(err){
            $("<li>"+ err +"</li>").appendTo("#p_error");
        });
    };

    /**
     * Verify if Product Exist
     * @param wh_id
     * @param name
     * @param context
     */
    this.verifyProductExist = function(wh_id, name, context){
        $.ajax({
            url : '/warehouse/product/exist',
            type : 'GET',
            data : {
                name : name,
                wh_id : wh_id
            },
            success : function(res){
                if(res){
                    context.clearErrors();
                    context.errors.push("Item Already Exist.");
                    context.appendErrorsOnDom();
                    context.showErrorDoM();
                }else{
                    context.updateProduct(
                        context.getWareHouseId(),
                        context.getProductID(),
                        context.getName(),
                        context.getProductPrice(),
                        context.getRetailPrice(),
                        context.getQuantity(),
                        context.getToken()
                    );
                }
            }
        })
    };

    /**
     * Update Product
     * @param wh_id
     * @param p_id
     * @param name
     * @param price
     * @param r_price
     * @param quantity
     * @param token
     */
    this.updateProduct = function(wh_id , p_id , name, price, r_price, quantity, token){
        $.ajax({
            url : '/warehouse/product/update',
            type : 'POST',
            data : {
                _token : token,
                id : wh_id,
                p_id : p_id,
                p_name : name,
                p_price : price,
                p_r_price : r_price,
                p_quantity : quantity
            },
            success : function(res){
                if(res){
                    window.location = '/warehouse/'+wh_id+'/products';
                }
            }
        });
    };

    /**
     * Detects a change in Product Name on Edit Form
     * @returns {boolean}
     */
    this.detectEditChange = function(){
        var name = this.getName(),
            old_name = this.getOldName();

        return (name === old_name);
    };

    /**
     * Redirect back to warehouse page
     * @param wd_id
     */
    this.redirectAsUnChanged = function(wd_id){
        window.location = '/warehouse/'+wd_id+'/products';
    };

    /**
     * Validate Edit Product Form Fields
     * @param e
     * @returns {boolean}
     */
    this.validateForm = function(e){

        e.preventDefault();
        var _this = e.data.context,
            name = _this.getName(),
            p_price = _this.getProductPrice(),
            p_r_price = _this.getRetailPrice(),
            p_quantity = _this.getQuantity(),
            wh_id = _this.getWareHouseId();

        if(_this.isNumeric(p_price) && _this.isNumeric(p_r_price) && _this.isInt(p_quantity)){
            if(!_this.detectEditChange()){
                _this.verifyProductExist(wh_id, name, _this);
                _this.hideErrorDoM();
            }else{
                _this.updateProduct(
                    _this.getWareHouseId(),
                    _this.getProductID(),
                    _this.getName(),
                    _this.getProductPrice(),
                    _this.getRetailPrice(),
                    _this.getQuantity(),
                    _this.getToken()
                );
            }
        }else{
            _this.clearErrors();
            if(!_this.isNumeric(p_price)){
                _this.errors.push("Price must be a numerical value.");
            }

            if(!_this.isNumeric(p_r_price)){
                _this.errors.push("Retail Price must be a numerical value.");
            }

            if(!_this.isNumeric(p_quantity)){
                _this.errors.push("Quantity must be a numerical value.");
            }

            if(!_this.isInt(p_quantity)){
                _this.errors.push("Quantity must be a Integer value.");
            }

            _this.appendErrorsOnDom();
            _this.showErrorDoM();
        }
        return false;
    };

    /**
     * Edit Product Event Handlers
     */
    this.init = function(){
        $("#edit-product-form").on("submit", {context : this} , this.validateForm);
    };
};

var edit_product = new EditProduct();
edit_product.init();