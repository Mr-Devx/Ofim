<?php

    use Carbon\Carbon;

    /**Formater la date en format 'il y a 10min' */
    function date_diff_for_humans($date){
        $date =  Carbon::parse($date);
        return $date->diffForHumans();
    }

    /**Formater le amount en 20,000,000.20 */
    function format_amount($amount){
        return number_format($amount, 2, '.', ',');
    }

    function format_numer($amount){
        return number_format($amount, 2, '.', ',');
    }
?>