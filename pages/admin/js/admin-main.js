//globals
var promoTable = null;
var beerTable = null;
//initialization
$(init);
function init() {
    $(document.body).append('<div hidden id="extra-page-items"></div>');
    $(document.body).mCustomScrollbar({
        theme: 'dark',
        scrollButtons: {
            enable: true
        }
    });
    setListeners();
}

function createPromoTable() {
    promoTable = $('#promos-table').DataTable({
        'order': [[4, 'asc']],
        responsive: true,
        'aoColumns': [
            null, //title
            null, //omschrijving
            null, //start
            null, //eind
            {'aDataSort': [4, 2]}, //status
            {'bSortable': false}
        ]
    });
}


function setListeners() {
    setPwChangeProtocol();
    $('.confirmation-trigger-parent').on('click', '.confirmation-trigger', function (event) {
        event.preventDefault();
        var $destin = $(this).attr('href');
        var $type = $(this).data('confirmation-type');
        confirmationTriggers($type, $destin);
    });
    $('#promoForm').on('submit', function (event) {
        event.preventDefault();
        $hasError = false;
        $.getScript('pages/admin/js/formValidators.js', function () {
            $result = validatePromoForm();
            $.each($result, function (index, element) {
                $field = element.fieldId.split('#').pop();
                if (element.message) {
                    $hasError = true;
                    removeErrors($field);
                    removeSuccess($field);
                    addErrors($field, element.message);
                } else {
                    removeErrors($field);
                    addSuccess($field);
                }
            });
            if (!$hasError) {
                $action = $('#promoForm').attr('action');
                $.post($action, $('#promoForm').serialize(), function ($recieve) {
                    if ($recieve && $recieve.toLowerCase().indexOf('error') < 0 && $recieve.toLowerCase().indexOf('notice') < 0) {
                        window.location.href = 'index.php?action=adminPromoMgr';
                    } else {
                        $('#extra-page-items').append(getErrorPageContents($recieve));
                        $('.errorConfirm').attr('href', 'index.php?action=adminPromoMgr');
                        $('#extra-page-items').show();
                    }
                });
            }
        });
    });
}


function setDatePickerLocal() {
    /* Dutch (Belgium) initialisation for the jQuery UI date picker plugin. */
    /* David De Sloovere @DavidDeSloovere */
    (function (factory) {
        if (typeof define === "function" && define.amd) {

            // AMD. Register as an anonymous module.
            define(["../widgets/datepicker"], factory);
        } else {

            // Browser globals
            factory(jQuery.datepicker);
        }
    }(function (datepicker) {

        datepicker.regional[ "nl-BE" ] = {
            closeText: "Sluiten",
            prevText: "←",
            nextText: "→",
            currentText: "Vandaag",
            monthNames: ["januari", "februari", "maart", "april", "mei",
                "juni",
                "juli", "augustus", "september", "oktober", "november",
                "december"],
            monthNamesShort: ["jan", "feb", "mrt", "apr", "mei", "jun",
                "jul", "aug", "sep", "okt", "nov", "dec"],
            dayNames: ["zondag", "maandag", "dinsdag", "woensdag", "donderdag",
                "vrijdag", "zaterdag"],
            dayNamesShort: ["zon", "maa", "din", "woe", "don", "vri", "zat"],
            dayNamesMin: ["zo", "ma", "di", "wo", "do", "vr", "za"],
            weekHeader: "Wk",
            dateFormat: "dd/mm/yy",
            firstDay: 1,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: ""};
        datepicker.setDefaults(datepicker.regional[ "nl-BE" ]);
        return datepicker.regional[ "nl-BE" ];
    }));
}

//Hack to force scroll redraw
function scrollReDraw() {
    $('body').css('overflow', 'hidden').height();
    $('body').css('overflow', 'auto');
}

//error handling stuff 
function getErrorPageContents($htmlString) {
    $return = '';
    $start = $htmlString.indexOf('js-content-start');
    $end = $htmlString.indexOf('js-content-end');
    if ($start && $end) {
        $start += 18;
        $end -= 12;
        $return = $htmlString.substr($start, $end - $start);
    }
    return $return;
}

//confirmation handlers

function confirmationTriggers($type, $destination) {
    switch ($type) {
        case 'beer-delete':
            confirmationBeerDelete($destination);
            break;
        case 'promo-delete':
            confirmationPromoDelete($destination);
            break;
        case 'promo-activate':
            displayUpdateDates($destination);
            break;
        case 'promo-deactivate':
            activationConfirm(false, $destination, '');
            break;
        default :
            console.log('confirmation type not found');
            return;
    }
}

function confirmationPromoDelete($destination) {
    swal({title: "Ben je zeker dat je deze promotie wil verwijderen?",
        text: "De promotie zal dan definitief worden verwijderd",
        type: "warning",
        customClass: 'swalert',
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Ja, ik ben zeker!",
        cancelButtonText: "Neen, deze promotie niet verwijderen!",
        closeOnConfirm: false,
        closeOnCancel: false}, function (isConfirm) {
        if (isConfirm) {
            scrollReDraw();
            swal({
                title: "Verwijderd!",
                text: "De promotie is verwijderd.",
                type: "success",
                showCancelButton: false,
                dconfirmButtonText: "Ok!",
                closeOnConfirm: true
            }, function () {
                $.getScript('pages/admin/js/promojsonhandler.js', function () {
                    updatePromoTable($destination + '&isJson=t', '');
                });
            });
        } else {
            swal("Geannuleerd", "De promotie is niet verwijderd. :)", "error");
        }
    });
}

function displayUpdateDates($destination) {
    $id = getIdFromUrl($destination);
    $.post('index.php?action=admin_getJsonPromo', {'promoId': $id}, function ($data) {
        $promo = $.parseJSON($data);
        $now = new Date();
        $start = $promo['promo_start'];
        $end = $promo['promo_end'];
        if ($.datepicker.parseDate('dd/mm/yy', $end) < $now) {
            $start = $.datepicker.formatDate('dd/mm/yy', $now);
            $end = $.datepicker.formatDate('dd/mm/yy', new Date($now.getTime() + 24 * 60 * 60 * 1000));
        }
        $html = '<div id="changeDateHolder">' +
                '<form style="display: none;" id="changeDateForm" method="POST" action="">' +
                '<fieldset>' +
                '<legend>' +
                'Vul de nieuwe start en eind datum van deze promotie in' +
                '</legend>' +
                '<div class="form-group"><label for="changeDateStart">' +
                'Nieuwe start datum:' +
                '</label>' +
                '<input id="changeDateStart" type="text" class="form-control" value="' +
                $start +
                '"/></div>' +
                '<div class="form-group"><label for="changeDateEnd">' +
                'Nieuwe eind datum:' +
                '</label>' +
                '<input id="changeDateEnd" type="text" class="form-control" value="' +
                $end +
                '"/></div>' +
                '</fieldset>' +
                '<a class="btn btn-danger" id="change-date-cancel-btn">Annuleren</a>' +
                '<button class="btn btn-success" id="change-date-submit">Klaar</button>' +
                '<div class="fix"></div></form>' +
                '</div>';
        $('#extra-page-items').append($html);
        $('#extra-page-items').show();
        setDatePickerLocal();
        $('#changeDateStart').datepicker($.datepicker.regional[ "nl-BE" ]);
        $('#changeDateEnd').datepicker($.datepicker.regional[ "nl-BE" ]);
        $('#changeDateForm').slideDown();
        $('#changeDateForm').on('submit', function (event) {
            event.preventDefault();
            $start = $('#changeDateStart').val();
            $end = $('#changeDateEnd').val();
            if (validateChangeDates($start, $end)) {
                $data = {'promoStart': $start, 'promoEnd': $end};
                $('#changeDateForm').slideUp('slow', function () {
                    activationConfirm(true, $destination, $data);
                });
            }
        });
        $('#change-date-cancel-btn').on('click', function (event) {
            event.preventDefault();
            $('#changeDateForm').slideUp('slow', function () {
                activationCancelConfirm($destination);
            });
        });
    });
}

function validateChangeDates($start, $end) {
    $dpStart = '';
    $dbEnd = '';
    if ($start) {
        $dpStart = $.datepicker.parseDate('dd/mm/yy', $start);
    } else {
        setDateError('Start datum is verplicht!', '1');
        return false;
    }
    if ($end) {
        $dpEnd = $.datepicker.parseDate('dd/mm/yy', $end);
    } else {
        setDateError('Eind datum is verplicht!', '2');
        return false;
    }
    if (!$dpStart) {
        setDateError('Start was geen correcte datum (dd/mm/yyyy)', '1');
        return false;
    }
    if (!$dpEnd) {
        setDateError('Eind was geen correcte datum (dd/mm/yyyy)', '2');
        return false;
    }
    $now = new Date();
    if ($dpEnd < $now) {
        setDateError('Met deze start en eind datum zou de promo al voorbij zijn', '1');
        return false;
    }
    if ($dpStart > $dpEnd) {
        setDateError('De start datum kan niet later zijn dan de begin datum!', '3');
        return false;
    } else {
        removeDateErrors();
        return true;
    }
}

function removeDateErrors() {
    $fields = '#changeDateStart, #changeDateEnd';
    $($fields).siblings().remove('.errors');
    $($fields).parent().removeClass('has-error');
    $($fields).parent().addClass('has-success');
}

function setDateError($message, $startEndBoth) {
    $field = '#changeDateStart,#changeDateEnd';
    if ($startEndBoth === '1') {
        $field = '#changeDateStart';
    } else if ($startEndBoth === '2') {
        $field = '#changeDateEnd';
    }
    $($field).siblings().remove('.errors');
    $parent = $($field).parent();
    $parent.addClass('has-error');
    $parent.append('<span class="text-danger errors">' + $message + '</span>');
}

function activationConfirm($activate, $destination, $data) {
    $activateStr = '';
    $newDatesStr = '';
    if (!$activate) {
        $activateStr += 'de';
    } else {
        $newDatesStr = ' op de nieuwe datum(van ' + $data['promoStart'] + ' tot ' + $data['promoEnd'] + ')';
    }
    $title = 'Ben je zeker dat je deze promotie wil ' + $activateStr + 'activeren?';
    $text = 'De promotie zal dan ge' + $activateStr + 'activeerd worden' + $newDatesStr;
    swal({title: $title,
        text: $text,
        type: 'warning',
        customClass: 'swalert',
        showCancelButton: true,
        confirmButtonColor: '#DD6B55',
        confirmButtonText: 'Ja, ik ben zeker!',
        cancelButtonText: 'Deze promotie niet ' + $activateStr + 'activeren!',
        closeOnConfirm: false,
        closeOnCancel: false}, function (isConfirm) {
        scrollReDraw();
        if (isConfirm) {
            swal({
                title: 'Ge' + $activate ? '' : 'de' + 'activeerd!',
                text: 'De promotie is ge' + $activateStr + 'activeerd.',
                type: 'success',
                showCancelButton: false,
                dconfirmButtonText: 'Ok!',
                closeOnConfirm: true
            }, function () {
                $.getScript('pages/admin/js/promojsonhandler.js', function () {
                    $('#changeDateStart').datepicker('destroy');
                    $('#changeDateEnd').datepicker('destroy');
                    $('#changeDateSart, #changeDateEnd').removeClass('hasDatepicker');
                    $('#ui-datepicker-div').remove();
                    $('#changeDateHolder').remove();
                    updatePromoTable($destination + '&isJson=t', $data);
                });
            });
        } else {
            swal({
                title: 'Geannuleerd',
                text: 'De promotie is niet ge' + $activate ? '' : '' + 'activeerd. :)',
                type: 'error'
            }, function () {
                $('#changeDateForm').slideDown();
            });
        }
    });
}
function activationCancelConfirm() {
    $('#changeDateForm').slideUp('slow', function () {
        $title = 'Activatie annuleren';
        $text = 'Ben je zeker dat je het activeren van deze promo wil annuleren?';
        swal({
            title: $title,
            text: $text,
            type: 'warning',
            customClass: 'swalert',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Ja, de activatie stop zetten',
            cancelButtonText: 'Ik wil verder gaan met de activatie',
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (isConfirm) {
            scrollReDraw();
            if (isConfirm) {
                $('#extra-page-items').hide();
                $('#changeDateHolder').remove();
            } else {
                $('#changeDateForm').slideDown();
            }
        });
    });
}

function confirmationBeerDelete($destination) {
    swal({title: "Ben je zeker dat je dit bier wil verwijderen?",
        text: "Het bier zal dan definitief worden verwijderd",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Ja, ik ben zeker!",
        cancelButtonText: "Neen, dit bier niet verwijderen!",
        closeOnConfirm: false,
        closeOnCancel: false}, function (isConfirm) {
        scrollReDraw();
        if (isConfirm) {
            swal({title: "Verwijderd!",
                text: "Het bier is verwijderd.",
                type: "success"}, function () {
                window.location.href = $destination;
            });
        } else {
            swal("Geannuleerd", "Het bier is niet verwijderd. :)", "error");
        }
    });
}

function getIdFromUrl($fullUrl) {
    return $fullUrl.match(/id=(.*?)&/i)[1];
}


//password validation 
function setPwChangeProtocol() {
    $('#pwOld').on('keyup', function () {
        removeErrors('pwOld');
    });
    $('#pwNewRepeat, #pwNew').on('keyup', function () {
        checkPwFields1();
    });
}

function enableButton($btnId) {
    $('#' + $btnId).prop('disabled', false);
}
function disableButton($btnId) {
    $('#' + $btnId).prop('disabled', true);
}

function checkPwFields1() {
    //TODO need to validate old pasword field too (ajax)
    $pwNew = $('#pwNew').val();
    $pwRepeat = $('#pwNewRepeat').val();
    $htmlAfter = '';
    $validationResult = validatePassword($pwNew);
    $hasError = false;
    if ($validationResult['len'] !== '' || $validationResult['dig'] !== '' || $validationResult['low'] || $validationResult['cap'] !== '') {
        removeSuccess('pwNew');
        removeErrors('pwNew');
        addErrors('pwNew', $validationResult['len'] !== '' ? $validationResult['len'] : $validationResult['dig'] !== '' ? $validationResult['dig'] : $validationResult['low'] !== '' ? $validationResult['low'] : $validationResult['cap']);
        disableButton('pwChangeButton');
        $hasError = true;
    } else {
        removeSuccess('pwNew');
        removeErrors('pwNew');
        addSuccess('pwNew');
        $hasError = false;
    }
    if ($pwNew !== $pwRepeat) {
        removeSuccess('pwNewRepeat');
        removeErrors('pwNewRepeat');
        addErrors('pwNewRepeat', 'De twee wachtwoorden komen (nog) niet overeen');
        disableButton('pwChangeButton');
        $hasError = true;
    }
    if (!$hasError) {
        removeErrors('pwNew');
        addSuccess('pwNew');
        removeErrors('pwNewRepeat');
        removeSuccess('pwNewRepeat')
        addSuccess('pwNewRepeat');
        enableButton('pwChangeButton');
    }
}

function removeErrors($fieldID) {
    $('#' + $fieldID).parent().parent().removeClass('has-error');
    $iconId = '#' + $fieldID + 'ErrorIcon';
    $srOnlyId = '#' + $fieldID + 'Error';
    $errorTxtId = '#' + $fieldID + 'ErrorText';
    $($iconId).length ? $($iconId).remove() : '';
    $($srOnlyId).length ? $($srOnlyId).remove() : '';
    $($errorTxtId).length ? $($errorTxtId).remove() : '';
}

function addErrors($fieldID, $message) {
    $('#' + $fieldID).parent().parent().addClass('has-error');
    $iconId = $fieldID + 'ErrorIcon';
    $srOnlyId = $fieldID + 'Error';
    $errorTxtId = $fieldID + 'ErrorText';
    $html = '<span id="' + $iconId + '" class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>';
    $html += '<span id="' + $srOnlyId + '" class="sr-only">(error)</span>';
    $html += '<span style="display:block; margin-top: -13px;" id="' + $errorTxtId + '" class="text-danger">' + $message + '</span>';
    $('#' + $fieldID).parent().after($html);
}

function removeSuccess($fieldID) {
    $('#' + $fieldID).parent().parent().removeClass('has-success');
    $iconId = '#' + $fieldID + 'SuccessIcon';
    $srOnlyId = '#' + $fieldID + 'Success';
    $($iconId).length ? $($iconId).remove() : '';
    $($srOnlyId).length ? $($srOnlyId).remove() : '';
}

function addSuccess($fieldID) {
    $('#' + $fieldID).parent().parent().addClass('has-success');
    $iconId = $fieldID + 'SuccessIcon';
    $srOnlyId = $fieldID + 'Success';
    $html = '<span id="' + $iconId + '" class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>';
    $html += '<span id="' + $srOnlyId + '" class="sr-only">(success)</span>';
    $('#' + $fieldID).after($html);
}

function validatePassword($pw) {
    $validationResult = ['dig', 'low', 'cap', 'len'];
    $regDig = $pw.search(/\d/);
    $regLow = $pw.search(/[a-z]/);
    $regCap = $pw.search(/[A-Z]/);
    $length = $pw.length;
    $validationResult['dig'] = $regDig === -1 ? 'Je wachtwoord moet minstens 1 cijfer bevatten.' : '';
    $validationResult['low'] = $regLow === -1 ? 'Je wachtwoord moet minstens 1 kleine letter bevatten.' : '';
    $validationResult['cap'] = $regCap === -1 ? 'Je wachtwoord moet minstens 1 hoofdletter bevatten.' : '';
    $validationResult['len'] = $length < 5 ? 'Je wachtwoord moet minstens 5 characters lang zijn' : '';
    return $validationResult;
}
