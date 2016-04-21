<?php

//require_once dirname(__FILE__) . '/dao.php';

interface PromoDao extends Dao {

    public function getPromos();

    public function getCurrentPromos();

    public function updatePromo($id, $promoTitle, $promoText, $format, $promoStart, $promoEnd, $active);
}
