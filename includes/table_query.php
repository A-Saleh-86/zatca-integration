<?php

/*
    Function Name: get_all_data_from_table
    params: table_name
    use: get all rows from spacific table
*/

function get_all_data($table_name){

    global $wpdb;

    $results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name "));

    return $results;
}

/*
    Function Name: get_all_data_from_table_with_one_condition
    params: table_name , where , condition
    use: get all rows from spacific table with define one condition
*/

function get_data_with_one_condition($table_name, $where, $condition){

    global $wpdb;

    $results = $wpdb->get_results( "SELECT * FROM $table_name WHERE $where = $condition" );

    return $results;
}

/*
    Function Name: get_all_data_from_table_with_one_condition
    params: table_name , where , condition
    use: get all rows from spacific table with define one condition
*/

function get_data_with_two_conditions_greaterThan($table_name, $where_1, $condition_1, $where_2, $condition_2){

    global $wpdb;

    $results = $wpdb->get_results( "SELECT * FROM $table_name WHERE $where_1 = $condition_1 AND $where_2 > $condition_2" );

    return $results;
}