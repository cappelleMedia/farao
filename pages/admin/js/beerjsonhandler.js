//json fields

function updateBeerTable($url, $send) {
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