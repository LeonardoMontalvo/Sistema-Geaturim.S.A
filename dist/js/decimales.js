/**
 * METODO PARA PERMITIR SOLO DECIMALES CON LIMITE DE DECIMAS
 * @param {type} id
 * @param {type} rigthAlign
 * @param {type} deci
 * @returns {undefined}
 */
function inputmaskDecimal(id, rigthAlign, deci) {
    $(id).inputmask('decimal', {
        digits: deci,
        rightAlign: rigthAlign,
        allowPlus: false,
        allowMinus: false
    });
    $(id).css('text-align', 'left');
}

/**
 * METODO PARA ASIGNAR , A DECIMALES Y RESTRINGIR , A ENTEROS
 * @param {type} tipo
 * @param {type} id
 * @returns {undefined}
 */
function inputmaskIntegerDecimal(id, rigthAlign, min, max) {
    if (rigthAlign !== null) {
        $(id).inputmask('decimal', {rightAlign: rigthAlign});
        $(id).css('text-align', 'left');
    }
    if (min !== null && max !== null) {
        $(id).inputmask('integer', {min: min, max: max});
    }
}