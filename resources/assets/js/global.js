let errors = new Errors('#error');

/**
 * Get CSRF Token
 * @returns {*|jQuery}
 */
function getToken(){
    return $('meta[name=_token]').attr("content");
}

/**
 * check if value is integer
 * @param value
 * @returns {boolean}
 */
function isInt(value){
    var er = /^-?[0-9]+$/;
    return er.test(value);
}

/**
 * Check if value is numeric
 * @param value
 * @returns {boolean}
 */
function isNumeric(value){
    return !isNaN(value);
}

/**
 * Check if value is a positive number
 * @param value
 * @returns {boolean}
 */
function isAPositiveNumber(value){
    return (value > 0);
}

/**
 * Remove event handlers
 * @param $target
 * @param action
 */
function removeEventHandler($target , action){
    $($target).off(action);
}

/**
 * Attach event handlers
 * @param $target
 * @param options
 * @param action
 * @param callback
 */
function addEventHandler($target, options, action, callback){
    $($target).on(action, options , callback);
}
