//json fields = promo_active, promo_end, promo_id, promo_start, promo_text, promo_title
//MAIN PROMO FUNCTIONS
function updatePromoTable($url, $send) {
    $.getScript('pages/admin/js/jquery-ui/jquery-ui.min.js', function () {
        $('#extra-page-items').append('<div id="waitingicon-holder"><i class="fa fa-spin fa-refresh"></i><p>De tabel wordt vernieuwd...</p></div>');
        $('#extra-page-items').show();        
        $.post($url, $send, function ($recieve) {
            var $data = '$recieve';
            if ($recieve.toLowerCase().indexOf('error') < 0 && $recieve.toLowerCase().indexOf('notice') < 0) {
                try {
                    $data = $.parseJSON($recieve);
                    promoTable.destroy();
                    $('#promos-table tbody').empty();
                    $html = '';
                    $.each($data, function ($id, $promo) {
                        $html += createTableRow($id, $promo);
                    });
                    $('#promos-table tbody').append($html);
                    createPromoTable();
                    $('#extra-page-items').hide();
                    $('#waitingicon-holder').remove();
                } catch (e) {
                    //handle error
                    console.log('error: ' + e);
                }
            } else {
                console.log($send + '\nno data found\n' + $recieve);
            }
        });
    });
}

//HELPER FUNCTIONS
function createTableRow($id, $promo) {
    $status = getPromoStatus($promo);
    $statusIcon = getStatusIcon($status.code, $id);

    $row = '<tr>';

    $row += '<td>';
    $row += $promo['promo_title'];
    $row += '</td>';

    $row += '<td>';
    $row += $promo['promo_text'];
    $row += '</td>';

    $row += '<td>';
    $row += $promo['promo_start'];
    $row += '</td>';

    $row += '<td>';
    $row += $promo['promo_end'];
    $row += '</td>';

    $row += '<td data-order="' + $status.code + '">';
    $row += $status.displayName;
    $row += '</td>';

    $row += '<td>';
    $row += $statusIcon;
    $row += '<a class="btn btn-sm btn-primary" ' +
            'href="index.php?action=admin_updatePromoPage&promoId=' + $id + '">' +
            '<i class="fa fa-pencil fa-lg"></i>' +
            '</a>' +
            '<a class="btn btn-sm btn-danger confirmation-trigger" '+
            'href="index.php?action=admin_deletePromo&promoId=' + $id + '" data-confirmation-type="promo-delete">' +
            '<i class="fa fa-trash-o fa-lg"></i>' +
            '</a>';
    $row += '</td>';

    $row += '</tr>';
    return $row;
}

function getPromoTimeState($promo) {
    $start = $.datepicker.parseDate('dd/mm/yy', $promo['promo_start']);
    $end = $.datepicker.parseDate('dd/mm/yy', $promo['promo_end']);
    $now = new Date();
    if ($now <= $end && $now >= $start) {
        return 'current';
    } else if ($now < $start) {
        return 'future';
    } else {
        return 'past';
    }
}

function getPromoStatus($promo) {
    $display = 'displayName';
    $code = 'code';
    switch (getPromoTimeState($promo)) {
        case 'current':
            return getCurrentStatus($promo, $display, $code);
        case 'future' :
            return getUpcomingStatus($promo, $display, $code);
        case 'past' :
            return getPastStatus($promo, $display, $code);
    }
}

function getCurrentStatus($promo, $display, $code) {
    $status = [];
    $status[$code] = '1';
    if ($promo['promo_active'] === '1') {
        $status[$display] = 'Actief';
        $status[$code] += '0';
        return $status;
    }
    $status[$display] = 'Gepauzeerd';
    $status[$code] += 1;
    return $status;
}
function getUpcomingStatus($promo, $display, $code) {
    $status = [];
    $status[$display] = 'Aankomend';
    $status[$code] = '2';
    if ($promo['promo_active'] === '1') {
        $status[$display] += ' | Geactiveerd';
        $status[$code] += '0';
        return $status;
    }
    $status[$display] += ' | Gepauzeerd';
    $status[$code] += 1;
    return $status;
}
function getPastStatus($promo, $display, $code) {
    $status = [];
    $status[$display] = 'Voorbij';
    $status[$code] = '30';
    return $status;

}

function getStatusIcon($code, $id) {
    switch ($code) {
        case '10':
            return getStatusIconActive($id);
        case '11':
        case '20':
        case '30':
            return getStatusIconInactive($id);
    }
}

function getStatusIconActive($id) {
    $statusIcon = '<a href="index.php?action=admin_togglePromoActive&promoId=' + $id + '&promoActive=1" class="btn btn-sm btn-warning confirmation-trigger" ' +
            'data-confirmation-type="promo-deactivate"> ' +
            '<i class="fa fa-pause fa-lg"></i>' +
            '</a>';
    return $statusIcon;
}

function getStatusIconInactive($id) {
    $statusIcon ='<a href="index.php?action=admin_togglePromoActive&promoId=' + $id + '&promoActive=0" class="btn btn-sm btn-success confirmation-trigger" ' +
            'data-confirmation-type="promo-activate"> ' +
            '<i class="fa fa-play fa-lg"></i>' +
            '</a>';
    return $statusIcon;
}