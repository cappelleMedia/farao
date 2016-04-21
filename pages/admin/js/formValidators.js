// should dubbles be alowed?
function validatePromoForm() {
    $result = {
        titel: {'fieldId': '#promoTitle', 'fieldname': 'Titel', 'message': ''},
        desc: {'fieldId': '#promoText', 'fieldname': 'Omschrijving', 'message': ''},
        start: {'fieldId': '#promoStart', 'fieldname': 'Start datum', 'message': ''},
        end: {'fieldId': '#promoEnd', 'fieldname': 'Eind datum', 'message': ''}
    };
    notNullOrEmptyValidation($result);
    correctDates($result);
    return $result;

}

function notNullOrEmptyValidation($valArr) {
    $.each($valArr, function (index, element) {
        $val = $(element.fieldId).val();
        if (!$val.trim()) {
            element['message'] = element.fieldname + ' kan niet leeg zijn';
        }
    });
    return $valArr;
}

function correctDates($start, $end) {

}
