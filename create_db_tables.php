<?php

// function to create zatcadevice:
function create_zatca_device_table() {
    global $wpdb;
    $table_name = 'zatcadevice';
    $charset_collate = $wpdb->get_charset_collate();

    // SQL to create the table
    $sql = "
        CREATE TABLE $table_name (
            `VendorId` int(11) NOT NULL DEFAULT 0,
            `deviceNo` int(11) NOT NULL AUTO_INCREMENT,
            `deviceCSID` varchar(255) DEFAULT NULL,
            `CsID_ExpiryDate` datetime DEFAULT NULL,
            `tokenData` varchar(500) DEFAULT NULL,
            `lastHash` varchar(255) DEFAULT NULL,
            `lastDocumentNo` int(11) DEFAULT NULL,
            `lastDocumentDateTime` date DEFAULT NULL,
            `deviceStatus` tinyint(1) DEFAULT NULL,
            `row_timestamp` timestamp NULL DEFAULT current_timestamp(),
            PRIMARY KEY (`deviceNo`, `VendorId`) USING BTREE
        ) ENGINE=InnoDB $charset_collate;
    ";

    // Execute the query
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// function to create zatcacompany:
function create_zatca_company_table() {
    global $wpdb;
    $table_name = 'zatcacompany';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "
        CREATE TABLE $table_name (
            `VendorId` int(11) NOT NULL DEFAULT 0,
            `companyNo` int(11) NOT NULL DEFAULT 1,
            `zatcaStage` int(11) DEFAULT NULL,
            `secondBusinessIDType` int(11) DEFAULT NULL,
            `secondBusinessID` varchar(18) DEFAULT NULL,
            `VATCategoryCode` int(11) DEFAULT NULL,
            `VATCategoryCodeSubTypeNo` int(11) DEFAULT NULL,
            `VATID` varchar(50) DEFAULT NULL,
            `aName` varchar(255) NOT NULL,
            `apartmentNum` varchar(18) DEFAULT NULL,
            `postalCode` int(11) DEFAULT NULL,
            `POBox` varchar(18) DEFAULT NULL,
            `POBoxAdditionalNum` varchar(18) DEFAULT NULL,
            `street_Arb` varchar(150) DEFAULT NULL,
            `street_Eng` varchar(150) DEFAULT NULL,
            `district_Arb` varchar(150) DEFAULT NULL,
            `district_Eng` varchar(150) DEFAULT NULL,
            `city_Arb` varchar(50) DEFAULT NULL,
            `city_Eng` varchar(50) DEFAULT NULL,
            `countrySubdivision_Arb` varchar(150) DEFAULT NULL,
            `countrySubdivision_Eng` varchar(150) DEFAULT NULL,
            `country_Arb` varchar(150) DEFAULT NULL,
            `country_Eng` varchar(150) DEFAULT NULL,
            `countryNo` varchar(50) DEFAULT NULL,
            `row_timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
            PRIMARY KEY (`VendorId`, `companyNo`)
        ) $charset_collate;
    ";

    // Execute the query
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
// function to create zatcacustomer:
function create_zatca_customer_table() {
    global $wpdb;
    $table_name = 'zatcacustomer';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "
        CREATE TABLE $table_name (
            `VendorId` int(11) NOT NULL DEFAULT 0,
            `clientVendorNo` varchar(50) NOT NULL,
            `aName` varchar(255) NOT NULL,
            `eName` varchar(255) NOT NULL,
            `VATID` varchar(50) DEFAULT NULL,
            `secondBusinessIDType` varchar(50) DEFAULT NULL,
            `secondBusinessID` varchar(18) DEFAULT NULL,
            `zatcaInvoiceType` varchar(100) DEFAULT NULL,
            `apartmentNum` varchar(18) DEFAULT NULL,
            `postalCode` varchar(18) DEFAULT NULL,
            `POBox` varchar(18) DEFAULT NULL,
            `POBoxAdditionalNum` varchar(18) DEFAULT NULL,
            `street_Arb` varchar(150) DEFAULT NULL,
            `street_Eng` varchar(150) DEFAULT NULL,
            `district_Arb` varchar(150) DEFAULT NULL,
            `district_Eng` varchar(150) DEFAULT NULL,
            `city_Arb` varchar(50) DEFAULT NULL,
            `city_Eng` varchar(50) DEFAULT NULL,
            `countrySubdivision_Arb` varchar(150) DEFAULT NULL,
            `countrySubdivision_Eng` varchar(150) DEFAULT NULL,
            `country_No` int(11) DEFAULT NULL,
            `country_Arb` varchar(150) DEFAULT NULL,
            `country_Eng` varchar(150) DEFAULT NULL,
            `row_timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
            PRIMARY KEY (`clientVendorNo`,`VendorId`)
        ) $charset_collate;
    ";

    // Execute the query
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// run all create functions:
function create_custom_tables(){

    create_zatca_company_table();
    create_zatca_device_table();
    create_zatca_customer_table();

}