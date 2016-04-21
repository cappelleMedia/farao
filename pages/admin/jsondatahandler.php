<?php

if (!$_POST['jsonData']) {
    echo 'Error: Geen data gekregen';
} else {
    $data = $_POST['jsonData']; // FIXME unsafe?
    if (!$data) {
        echo 'Error: Data werd niet correct gefilterd';
    } else {
        $jsonStr = json_encode($data);
        if (!$jsonStr) {
            echo 'Error: De data kon niet worden omgezet naar Json formaat';
        } else {
            echo $jsonStr;
        }
    }
}
