<?php

function getTotalPayment() { 
    global $wpdb;
    $dsp_payments_table = $wpdb->prefix . DSP_PAYMENTS_TABLE;
    $dsp_payments_query = "SELECT sum(pay_plan_amount) FROM $dsp_payments_table where payment_status = 1";
    $total = $wpdb->get_var($dsp_payments_query);
    return number_format($total, 2, '.', '');
}

function getOnDatePayment($date) {
    global $wpdb;
    $dsp_payments_table = $wpdb->prefix . DSP_PAYMENTS_TABLE;
    $dsp_payments_query = "SELECT sum( pay_plan_amount ) FROM $dsp_payments_table WHERE  payment_status = 1 AND payment_date = '$date'";
    $total = $wpdb->get_var($dsp_payments_query);
    return number_format($total, 2, '.', '');
}

function getByLastDaysPayment($days, $date) {
    global $wpdb;
    $dsp_payments_table = $wpdb->prefix . DSP_PAYMENTS_TABLE;
    $dsp_payments_query = "SELECT sum( pay_plan_amount ) FROM $dsp_payments_table WHERE  payment_status = 1 AND payment_date >= DATE_SUB('$date',INTERVAL $days DAY) AND payment_date <='$date'";
    $total = $wpdb->get_var($dsp_payments_query);
    return number_format($total, 2, '.', '');
}

function getPaymentBetweenDates($from, $to) {
    global $wpdb;
    $dsp_payments_table = $wpdb->prefix . DSP_PAYMENTS_TABLE;
    $dsp_payments_query = "SELECT sum( pay_plan_amount ) FROM $dsp_payments_table WHERE  payment_status = 1 AND payment_date >= '$from' AND payment_date <='$to'";
    $total = $wpdb->get_var($dsp_payments_query);
    return number_format($total, 2, '.', '');
}

?>