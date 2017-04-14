class Errors{
    /**
     * Set DOM Selector
     * @param dom
     */
    constructor(dom){
        this.errors = [];
        this.errorDOM = dom;
    }

    /**
     * Clear Errors and DOM
     */
    clearErrors(){
        this.errors = [];
        this.hideErrorDOM();
    }

    /**
     * Hide and Empty Error DOM
     */
    hideErrorDOM(){
        $(this.errorDOM).slideUp();
        $(this.errorDOM).html('');
    }

    /**
     * Show Error DOM
     */
    showErrorDOM(){
        $(this.errorDOM).slideDown();
    }

    /**
     * Append Errors to DOM
     */
    appendErrorsToDOM(){
        var _this = this;
        this.errors.forEach(function (err) {
            $("<li>" + err + "</li>").appendTo(_this.errorDOM);
        });
    }

    /**
     * Add Error to Errors
     * @param err
     */
    add(err){
        this.errors.push(err);
    }
}
