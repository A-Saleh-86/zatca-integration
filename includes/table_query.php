<?php

/*
    Function Name: get_all_data_from_table
    params: table_name
    use: get all rows from spacific table
*/

function get_all_data_from_table($table_name){

    global $wpdb;

    $results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name "));

    return $results;
}