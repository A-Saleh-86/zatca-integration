<?php

// Create country table & insert Data:
function create_zatca_country_table() {

    global $wpdb;
    $table_name = 'country';
    $charset_collate = $wpdb->get_charset_collate();

    // SQL to create the table
    $sql = "
        CREATE TABLE IF NOT EXISTS  $table_name (
             `country_id` int(11) NOT NULL AUTO_INCREMENT,
            `english_name` varchar(150) NOT NULL,
            `arabic_name` varchar(150) NOT NULL,
            `french_name` varchar(150) DEFAULT NULL,
            `iso_3166_1_alpha_2` char(2) DEFAULT NULL,
            `iso_3166_1_alpha_3` char(3) DEFAULT NULL,
            `itu` char(3) DEFAULT NULL,
            `marc` varchar(15) DEFAULT NULL,
            `wmo` char(2) DEFAULT NULL,
            `ds` char(3) DEFAULT NULL,
            `dialing_code` varchar(30) NOT NULL,
            `fifa` varchar(30) DEFAULT NULL,
            `fips` varchar(50) DEFAULT NULL,
            `gaul` smallint(6) DEFAULT NULL,
            `ioc` char(3) DEFAULT NULL,
            `currency_numeric_code` int(11) DEFAULT NULL,
            `currency_alphabetic_code` char(3) DEFAULT NULL,
            `currency_minor_unit` smallint(6) DEFAULT NULL,
            `currency_english_name` varchar(150) DEFAULT NULL,
            `currency_arabic_name` varchar(150) DEFAULT NULL,
            `currency_english_sign` varchar(6) DEFAULT NULL,
            `currency_arabic_sign` varchar(6) DEFAULT NULL,
            `capital_city_id` int(11) DEFAULT NULL,
            `gps_latitude` decimal(10,8) DEFAULT NULL,
            `gps_longitude` decimal(11,8) DEFAULT NULL,
            `exchange_rate_to_base_currency` decimal(10,4) DEFAULT NULL,
            `decimal_places` smallint(6) DEFAULT NULL,
            `decimal_separator` varchar(6) DEFAULT NULL,
            `thousands_separator` varchar(6) DEFAULT NULL,
            `cr_currency_position_type` smallint(6) DEFAULT NULL,
            `cr_negative_type` smallint(6) DEFAULT NULL,
            `account_number` int(11) DEFAULT NULL,
            `row_timestamp` timestamp NULL DEFAULT current_timestamp(),
            PRIMARY KEY (`country_id`)
        ) ENGINE=InnoDB $charset_collate;
    ";

    // Execute the query
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    // Data to be inserted:
    $data = [
        [
            'country_id' => 1,
            'english_name' => 'Egypt',
            'arabic_name' => 'مصر',
            'french_name' => NULL,
            'iso_3166_1_alpha_2' => NULL,
            'iso_3166_1_alpha_3' => NULL,
            'itu' => NULL,
            'marc' => NULL,
            'wmo' => NULL,
            'ds' => NULL,
            'dialing_code' => '',
            'fifa' => NULL,
            'fips' => NULL,
            'gaul' => NULL,
            'ioc' => NULL,
            'currency_numeric_code' => NULL,
            'currency_alphabetic_code' => NULL,
            'currency_minor_unit' => NULL,
            'currency_english_name' => NULL,
            'currency_arabic_name' => NULL,
            'currency_english_sign' => NULL,
            'currency_arabic_sign' => NULL,
            'capital_city_id' => NULL,
            'gps_latitude' => NULL,
            'gps_longitude' => NULL,
            'exchange_rate_to_base_currency' => NULL,
            'decimal_places' => NULL,
            'decimal_separator' => NULL,
            'thousands_separator' => NULL,
            'cr_currency_position_type' => NULL,
            'cr_negative_type' => NULL,
            'account_number' => NULL,
            'row_timestamp' => '2024-05-04 14:24:50'
        ],
        [
            'country_id' => 2,
            'english_name' => 'Sauid Arabia',
            'arabic_name' => 'السعوديه',
            'french_name' => NULL,
            'iso_3166_1_alpha_2' => NULL,
            'iso_3166_1_alpha_3' => NULL,
            'itu' => NULL,
            'marc' => NULL,
            'wmo' => NULL,
            'ds' => NULL,
            'dialing_code' => '',
            'fifa' => NULL,
            'fips' => NULL,
            'gaul' => NULL,
            'ioc' => NULL,
            'currency_numeric_code' => NULL,
            'currency_alphabetic_code' => NULL,
            'currency_minor_unit' => NULL,
            'currency_english_name' => NULL,
            'currency_arabic_name' => NULL,
            'currency_english_sign' => NULL,
            'currency_arabic_sign' => NULL,
            'capital_city_id' => NULL,
            'gps_latitude' => NULL,
            'gps_longitude' => NULL,
            'exchange_rate_to_base_currency' => NULL,
            'decimal_places' => NULL,
            'decimal_separator' => NULL,
            'thousands_separator' => NULL,
            'cr_currency_position_type' => NULL,
            'cr_negative_type' => NULL,
            'account_number' => NULL,
            'row_timestamp' => '2024-05-04 14:25:15'
        ],
        [
            'country_id' => 3,
            'english_name' => 'United State',
            'arabic_name' => 'امريكا',
            'french_name' => NULL,
            'iso_3166_1_alpha_2' => NULL,
            'iso_3166_1_alpha_3' => NULL,
            'itu' => NULL,
            'marc' => NULL,
            'wmo' => NULL,
            'ds' => NULL,
            'dialing_code' => '',
            'fifa' => NULL,
            'fips' => NULL,
            'gaul' => NULL,
            'ioc' => NULL,
            'currency_numeric_code' => NULL,
            'currency_alphabetic_code' => NULL,
            'currency_minor_unit' => NULL,
            'currency_english_name' => NULL,
            'currency_arabic_name' => NULL,
            'currency_english_sign' => NULL,
            'currency_arabic_sign' => NULL,
            'capital_city_id' => NULL,
            'gps_latitude' => NULL,
            'gps_longitude' => NULL,
            'exchange_rate_to_base_currency' => NULL,
            'decimal_places' => NULL,
            'decimal_separator' => NULL,
            'thousands_separator' => NULL,
            'cr_currency_position_type' => NULL,
            'cr_negative_type' => NULL,
            'account_number' => NULL,
            'row_timestamp' => '2024-05-04 14:25:38'
        ]
    ];

    // Insert data
    foreach ($data as $record) {
        $wpdb->insert($table_name, $record);
    }
}

// Create met_vatcategorycode table & insert Data:
function create_zatca_met_vatcategorycode_table() {

    global $wpdb;
    $table_name = 'met_vatcategorycode';
    $charset_collate = $wpdb->get_charset_collate();

    // SQL to create the table
    $sql = "
        CREATE TABLE IF NOT EXISTS  $table_name (
            `VATCategoryCodeNo` int(11) NOT NULL,
            `codeName` varchar(50) NOT NULL,
            `aName` varchar(250) NOT NULL,
            `eName` varchar(250) NOT NULL,
            `row_timestamp` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`VATCategoryCodeNo`)
        ) ENGINE=InnoDB $charset_collate;
    ";

    // Execute the query
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    // Data to be inserted:
    $data = [
        [
            'VATCategoryCodeNo' => 0,
            'codeName' => 'S',
            'aName' => 'التوريدات الخاضعة للضريبة',
            'eName' => 'Standard rate',
            'row_timestamp' => '2024-05-06 21:00:00'
        ],
        [
            'VATCategoryCodeNo' => 1,
            'codeName' => 'E',
            'aName' => 'التوريدات المعفاة',
            'eName' => 'Exempt from Tax',
            'row_timestamp' => '2024-05-06 21:00:00'
        ],
        [
            'VATCategoryCodeNo' => 2,
            'codeName' => 'Z',
            'aName' => 'التوريدات الخاضعة لنسبة الصفر',
            'eName' => 'Zero rated goods',
            'row_timestamp' => '2024-05-06 21:00:00'
        ],
        [
            'VATCategoryCodeNo' => 3,
            'codeName' => 'O',
            'aName' => 'التوريدات الخاضعة للضريبة',
            'eName' => 'Services outside scope of tax / Not subject to VAT',
            'row_timestamp' => '2024-05-06 21:00:00'
        ]
    ];

    // Insert data
    foreach ($data as $record) {
        $wpdb->insert($table_name, $record);
    }
}

// Create met_vatcategorycodesubtype table & insert Data:
function create_zatca_met_vatcategorycodesubtype_table() {

    global $wpdb;
    $table_name = 'met_vatcategorycodesubtype';
    $charset_collate = $wpdb->get_charset_collate();

    // SQL to create the table
    $sql = "
        CREATE TABLE IF NOT EXISTS  $table_name (
            `VATCategoryCodeNo` int(11) NOT NULL,
            `VATCategoryCodeSubTypeNo` int(11) NOT NULL,
            `codeName` varchar(50) NOT NULL,
            `aName` varchar(250) NOT NULL,
            `eName` varchar(250) NOT NULL,
            `row_timestamp` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`VATCategoryCodeNo`,`VATCategoryCodeSubTypeNo`)
        ) ENGINE=InnoDB $charset_collate;
    ";

    // Execute the query
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    // Data to be inserted:
    $data = [
        ['VATCategoryCodeNo' => 0, 'VATCategoryCodeSubTypeNo' => 0, 'codeName' => '-', 'aName' => 'التوريدات الخاضعة للضريبة', 'eName' => 'Standard rate', 'row_timestamp' => '0000-00-00 00:00:00'],
        ['VATCategoryCodeNo' => 1, 'VATCategoryCodeSubTypeNo' => 1, 'codeName' => '-', 'aName' => 'الخدمات المالية', 'eName' => 'Financial services mentioned in Article 29 of the VAT Regulations', 'row_timestamp' => '0000-00-00 00:00:00'],
        ['VATCategoryCodeNo' => 1, 'VATCategoryCodeSubTypeNo' => 2, 'codeName' => '-', 'aName' => 'عقد تأمين على الحياة', 'eName' => 'Life insurance services mentioned in Article 29 of the VAT Regulations', 'row_timestamp' => '0000-00-00 00:00:00'],
        ['VATCategoryCodeNo' => 1, 'VATCategoryCodeSubTypeNo' => 3, 'codeName' => '-', 'aName' => 'التوريدات العقارية المعفاة من الضريبة ', 'eName' => 'Real estate transactions mentioned in Article 30 of the VAT Regulations', 'row_timestamp' => '0000-00-00 00:00:00'],
        ['VATCategoryCodeNo' => 2, 'VATCategoryCodeSubTypeNo' => 4, 'codeName' => '-', 'aName' => 'صادرات السلع من المملكة', 'eName' => 'Export of goods ', 'row_timestamp' => '0000-00-00 00:00:00'],
        ['VATCategoryCodeNo' => 2, 'VATCategoryCodeSubTypeNo' => 5, 'codeName' => '-', 'aName' => 'صادرات الخدمات من المملك', 'eName' => 'Export of services ', 'row_timestamp' => '0000-00-00 00:00:00'],
        ['VATCategoryCodeNo' => 2, 'VATCategoryCodeSubTypeNo' => 6, 'codeName' => '-', 'aName' => 'النقل الدولي للسلع', 'eName' => 'The international transport of Goods', 'row_timestamp' => '0000-00-00 00:00:00'],
        ['VATCategoryCodeNo' => 2, 'VATCategoryCodeSubTypeNo' => 7, 'codeName' => '-', 'aName' => 'النقل الدولي للركاب', 'eName' => 'international transport of passengers', 'row_timestamp' => '0000-00-00 00:00:00'],
        ['VATCategoryCodeNo' => 2, 'VATCategoryCodeSubTypeNo' => 8, 'codeName' => '-', 'aName' => 'الخدمات المرتبطة مباشرة أو عرضياً بتوريد النقل الدولي للركاب', 'eName' => 'services directly connected and incidental to a Supply of international passenger transport', 'row_timestamp' => '0000-00-00 00:00:00'],
        ['VATCategoryCodeNo' => 2, 'VATCategoryCodeSubTypeNo' => 9, 'codeName' => '-', 'aName' => 'توريد وسائل النقل المؤهلة ', 'eName' => 'Supply of a qualifying means of transport', 'row_timestamp' => '0000-00-00 00:00:00'],
        ['VATCategoryCodeNo' => 2, 'VATCategoryCodeSubTypeNo' => 10, 'codeName' => '-', 'aName' => 'الخدمات ذات الصلة بنقل السلع أو الركاب، وفقاً للتعريف الوارد بالمادة الخامسة والعشرين من اللائحة التنفيذية لنظام ضريبة القيامة المضافة', 'eName' => 'Any services relating to Goods or passenger transportation, as defined in article twenty five of these Regulations', 'row_timestamp' => '0000-00-00 00:00:00'],
        ['VATCategoryCodeNo' => 2, 'VATCategoryCodeSubTypeNo' => 11, 'codeName' => '-', 'aName' => 'الأدوية والمعدات الطبية', 'eName' => 'Medicines and medical equipment ', 'row_timestamp' => '0000-00-00 00:00:00'],
        ['VATCategoryCodeNo' => 2, 'VATCategoryCodeSubTypeNo' => 12, 'codeName' => '-', 'aName' => 'المعادن المؤهلة', 'eName' => 'Qualifying metals', 'row_timestamp' => '0000-00-00 00:00:00'],
        ['VATCategoryCodeNo' => 2, 'VATCategoryCodeSubTypeNo' => 13, 'codeName' => '-', 'aName' => 'الخدمات التعليمية الخاصة للمواطنين', 'eName' => 'Private education to citizen', 'row_timestamp' => '0000-00-00 00:00:00'],
        ['VATCategoryCodeNo' => 2, 'VATCategoryCodeSubTypeNo' => 14, 'codeName' => '-', 'aName' => 'الخدمات الصحية الخاصة للمواطنين', 'eName' => 'Private healthcare to citizen', 'row_timestamp' => '0000-00-00 00:00:00'],
        ['VATCategoryCodeNo' => 2, 'VATCategoryCodeSubTypeNo' => 15, 'codeName' => '-', 'aName' => 'توريد السلع العسكرية المؤهلة', 'eName' => 'supply of qualified military goods ', 'row_timestamp' => '0000-00-00 00:00:00'],
        ['VATCategoryCodeNo' => 3, 'VATCategoryCodeSubTypeNo' => 16, 'codeName' => '-', 'aName' => 'السبب يتم تزويده من قبل المكلف على أساس كل حالة على حدة', 'eName' => 'Reason is free text, to be provided by the taxpayer on case to case basis.', 'row_timestamp' => '0000-00-00 00:00:00']
    ];

    // Insert data
    foreach ($data as $record) {
        $wpdb->insert($table_name, $record);
    }
}

// Create zatcabusinessidtype table & insert Data:
function create_zatcabusinessidtype_table() {

    global $wpdb;
    $table_name = 'zatcabusinessidtype';
    $charset_collate = $wpdb->get_charset_collate();

    // SQL to create the table
    $sql = "
        CREATE TABLE IF NOT EXISTS  $table_name (
            `codeNumber` int(11) NOT NULL,
            `codeInfo` char(10) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
            `aName` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
            `eName` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
            `isSeller` bit(1) DEFAULT NULL,
            `isBuyer` bit(1) DEFAULT NULL,
            PRIMARY KEY (`codeNumber`)
        ) ENGINE=InnoDB $charset_collate;
    ";

    // Execute the query
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    // Data to be inserted:
    $data = [
        ['codeNumber' => 1, 'codeInfo' => 'CRN', 'aName' => 'السجل التجاري CR', 'eName' => 'CRN - Commercial registration number', 'isSeller' => 1, 'isBuyer' => 1],
        ['codeNumber' => 2, 'codeInfo' => 'MOM', 'aName' => 'ترخيص وزارة الشؤون البلدية والقروية', 'eName' => 'MOM - MOMRAH license - Ministry of Municipal and Rural Affairs license', 'isSeller' => 1, 'isBuyer' => 1],
        ['codeNumber' => 3, 'codeInfo' => 'MLS', 'aName' => 'ترخيص وزارة الموارد البشرية والتنمية الاجتماعية', 'eName' => 'MLS - MLSD license - License of the Ministry of Human Resources and Social Development', 'isSeller' => 1, 'isBuyer' => 1],
        ['codeNumber' => 4, 'codeInfo' => '700', 'aName' => 'رقم 700', 'eName' => '700 - 700 Number', 'isSeller' => 1, 'isBuyer' => 1],
        ['codeNumber' => 5, 'codeInfo' => 'SAG', 'aName' => 'ترخيص الهيئة العامة للاستثمار', 'eName' => 'SAG - Sagia license - General Investment Authority license', 'isSeller' => 1, 'isBuyer' => 1],
        ['codeNumber' => 6, 'codeInfo' => 'OTH', 'aName' => 'اخرى', 'eName' => 'OTH - Other OD', 'isSeller' => 1, 'isBuyer' => 1],
        ['codeNumber' => 7, 'codeInfo' => 'TIN', 'aName' => 'رقم التعريف الضريبي', 'eName' => 'TIN - Tax Identification Number', 'isSeller' => 0, 'isBuyer' => 1],
        ['codeNumber' => 8, 'codeInfo' => 'NAT', 'aName' => 'الهوية الوطنية', 'eName' => 'NAT - National ID', 'isSeller' => 0, 'isBuyer' => 1],
        ['codeNumber' => 9, 'codeInfo' => 'GCC', 'aName' => 'معرف دول مجلس التعاون الخليجي', 'eName' => 'GCC - GCC ID', 'isSeller' => 0, 'isBuyer' => 1],
        ['codeNumber' => 10, 'codeInfo' => 'IQA', 'aName' => 'رقم الاقامة للأجنبي', 'eName' => 'IQA - Iqama Number', 'isSeller' => 0, 'isBuyer' => 1],
        ['codeNumber' => 11, 'codeInfo' => 'PAS', 'aName' => 'رقم جواز السفر', 'eName' => 'PAS - Passport ID', 'isSeller' => 0, 'isBuyer' => 1]
    ];

    // Insert data
    foreach ($data as $record) {
        $wpdb->insert($table_name, $record);
    }
}

// Create zatcabranch table & insert Data:
function create_zatcabranch_table() {

    global $wpdb;
    $table_name = 'zatcabranch';
    $charset_collate = $wpdb->get_charset_collate();

    // SQL to create the table
    $sql = "
        CREATE TABLE IF NOT EXISTS  $table_name (
            `VendorId` int(11) NOT NULL DEFAULT 0,
            `buildingNo` int(11) NOT NULL DEFAULT 1,
            `deviceID` varchar(250) DEFAULT NULL,
            `zatcaStage` int(11) NOT NULL,
            `zatcaInvoiceType` varchar(10) DEFAULT NULL,
            `secondBusinessIDType` varchar(255) DEFAULT NULL,
            `secondBusinessID` varchar(18) DEFAULT NULL,
            `VATCategoryCodeNo` int(11) DEFAULT NULL,
            `VATCategoryCodeSubTypeNo` int(11) DEFAULT NULL,
            `apartmentNum` varchar(18) DEFAULT NULL,
            `POBox` varchar(30) DEFAULT NULL,
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
            `countryNo` varchar(10) DEFAULT NULL,
            `postalCode` varchar(18) DEFAULT NULL,
            `row_timestamp` timestamp NULL DEFAULT current_timestamp(),
            `ZATCA_B2C_SendingIntervalType` int(11) DEFAULT NULL,
            PRIMARY KEY (`buildingNo`,`VendorId`) USING BTREE
        ) ENGINE=InnoDB $charset_collate;
    ";

    // Execute the query
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

}

// Create zatcacompany table & insert Data:
function create_zatcacompany_table() {

    global $wpdb;
    $table_name = 'zatcacompany';
    $charset_collate = $wpdb->get_charset_collate();

    // SQL to create the table
    $sql = "
        CREATE TABLE IF NOT EXISTS  $table_name (
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
            `postalCode` int(11) DEFAULT NULL,
            PRIMARY KEY (`VendorId`,`companyNo`)
        ) ENGINE=InnoDB $charset_collate;
    ";

    // Execute the query
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

}

// Create zatcacustomer table & insert Data:
function create_zatcacustomer_table() {

    global $wpdb;
    $table_name = 'zatcacustomer';
    $charset_collate = $wpdb->get_charset_collate();

    // SQL to create the table
    $sql = "
        CREATE TABLE IF NOT EXISTS  $table_name (
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
        ) ENGINE=InnoDB $charset_collate;
    ";

    // Execute the query
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

}

// Create zatcadocument table & insert Data:
function create_zatcadocument_table() {

    global $wpdb;
    $table_name = 'zatcadocument';
    $charset_collate = $wpdb->get_charset_collate();

    // SQL to create the table
    $sql = "
        CREATE TABLE IF NOT EXISTS  $table_name (
            `vendorId` int(11) NOT NULL DEFAULT 0,
            `documentNo` int(11) NOT NULL AUTO_INCREMENT,
            `deviceNo` int(11) NOT NULL,
            `invoiceNo` int(11) DEFAULT NULL,
            `buildingNo` varchar(50) DEFAULT '0',
            `billTypeNo` int(11) DEFAULT 33,
            `dateG` date DEFAULT current_timestamp(),
            `deliveryDate` date DEFAULT current_timestamp(),
            `gaztLatestDeliveryDate` date DEFAULT current_timestamp(),
            `zatcaInvoiceType` int(11) DEFAULT NULL,
            `amountPayed01` float DEFAULT NULL,
            `amountPayed02` float DEFAULT 0,
            `amountPayed03` float DEFAULT 0,
            `amountPayed04` float DEFAULT 0,
            `amountPayed05` int(11) DEFAULT 0,
            `amountCalculatedPayed` int(11) DEFAULT NULL,
            `returnReasonType` int(11) DEFAULT NULL,
            `subTotal` decimal(18,6) DEFAULT NULL,
            `subTotalDiscount` decimal(18,6) DEFAULT NULL,
            `taxRate1_Percentage` int(11) NOT NULL,
            `taxRate1_Total` int(11) NOT NULL,
            `subNetTotal` decimal(18,6) DEFAULT NULL,
            `subNetTotalPlusTax` decimal(18,6) DEFAULT NULL,
            `amountLeft` decimal(18,6) DEFAULT NULL,
            `isAllItemsReturned` tinyint(1) DEFAULT NULL,
            `isZatcaRetuerned` tinyint(1) DEFAULT NULL,
            `reason` text DEFAULT NULL,
            `previousDocumentNo` int(11) DEFAULT NULL,
            `previousInvoiceHash` varchar(255) NOT NULL,
            `seller_secondBusinessIDType` int(11) DEFAULT NULL,
            `seller_secondBusinessID` varchar(18) DEFAULT NULL,
            `buyer_secondBusinessIDType` int(11) DEFAULT NULL,
            `buyer_secondBusinessID` varchar(18) DEFAULT NULL,
            `VATCategoryCodeNo` int(11) NOT NULL,
            `VATCategoryCodeSubTypeNo` int(11) DEFAULT NULL,
            `zatca_TaxExemptionReason` varchar(250) DEFAULT NULL,
            `zatcaInvoiceTransactionCode_isNominal` tinyint(1) DEFAULT NULL,
            `zatcaInvoiceTransactionCode_isExports` tinyint(1) DEFAULT NULL,
            `zatcaInvoiceTransactionCode_isSummary` tinyint(1) DEFAULT NULL,
            `zatcaInvoiceTransactionCode_is3rdParty` tinyint(1) DEFAULT NULL,
            `zatcaInvoiceTransactionCode_isSelfBilled` tinyint(1) DEFAULT NULL,
            `UUID` varchar(50) DEFAULT NULL,
            `seller_VAT` varchar(50) DEFAULT NULL,
            `seller_aName` varchar(150) DEFAULT NULL,
            `seller_eName` varchar(150) DEFAULT NULL,
            `seller_apartmentNum` varchar(18) DEFAULT NULL,
            `seller_countrySubdivision_Arb` varchar(150) DEFAULT NULL,
            `seller_countrySubdivision_Eng` varchar(150) DEFAULT NULL,
            `seller_street_Arb` varchar(150) DEFAULT NULL,
            `seller_street_Eng` varchar(150) DEFAULT NULL,
            `seller_district_Arb` varchar(150) DEFAULT NULL,
            `seller_district_Eng` varchar(150) DEFAULT NULL,
            `seller_city_Arb` varchar(50) DEFAULT NULL,
            `seller_city_Eng` varchar(50) DEFAULT NULL,
            `seller_country_Arb` varchar(150) DEFAULT NULL,
            `seller_country_Eng` varchar(150) DEFAULT NULL,
            `seller_country_No` int(11) DEFAULT NULL,
            `seller_PostalCode` varchar(18) DEFAULT NULL,
            `seller_POBox` varchar(18) DEFAULT NULL,
            `seller_POBoxAdditionalNum` varchar(18) DEFAULT NULL,
            `buyer_VAT` varchar(50) DEFAULT NULL,
            `buyer_aName` varchar(150) DEFAULT NULL,
            `buyer_eName` varchar(150) DEFAULT NULL,
            `buyer_apartmentNum` varchar(18) DEFAULT NULL,
            `buyer_countrySubdivision_Arb` varchar(150) DEFAULT NULL,
            `buyer_countrySubdivision_Eng` varchar(150) DEFAULT NULL,
            `buyer_street_Arb` varchar(150) DEFAULT NULL,
            `buyer_street_Eng` varchar(150) DEFAULT NULL,
            `buyer_district_Arb` varchar(150) DEFAULT NULL,
            `buyer_district_Eng` varchar(150) DEFAULT NULL,
            `buyer_city_Arb` varchar(50) DEFAULT NULL,
            `buyer_city_Eng` varchar(50) DEFAULT NULL,
            `buyer_country_Arb` varchar(150) DEFAULT NULL,
            `buyer_country_Eng` varchar(150) DEFAULT NULL,
            `buyer_country_No` int(11) DEFAULT NULL,
            `buyer_PostalCode` varchar(18) DEFAULT NULL,
            `buyer_POBox` varchar(18) DEFAULT NULL,
            `buyer_POBoxAdditionalNum` varchar(18) DEFAULT NULL,
            `zatcaSuccessResponse` int(11) DEFAULT NULL,
            `zatcaErrorResponse` text DEFAULT NULL,
            `zatcaResponseDate` datetime DEFAULT NULL,
            `zatcaB2B_isForced_To_B2C` tinyint(1) DEFAULT NULL,
            `zatcaRejectedBuildingNo` varchar(50) DEFAULT NULL,
            `zatcaRejectedInvoiceNo` varchar(50) DEFAULT NULL,
            `zatcaAcceptedReissueBuildingNo` varchar(50) DEFAULT NULL,
            `zatcaAcceptedReissueInvoiceNo` varchar(50) DEFAULT NULL,
            `isZatcaReissued` tinyint(1) DEFAULT NULL,
            `row_timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
            PRIMARY KEY (`documentNo`,`vendorId`,`deviceNo`) USING BTREE
        ) ENGINE=InnoDB $charset_collate;
    ";

    // Execute the query
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

}

// Create zatcadocumentunit table & insert Data:
function create_zatcadocumentunit_table() {

    global $wpdb;
    $table_name = 'zatcadocumentunit';
    $charset_collate = $wpdb->get_charset_collate();

    // SQL to create the table
    $sql = "
        CREATE TABLE IF NOT EXISTS  $table_name (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `vendorId` int(11) NOT NULL DEFAULT 0,
            `documentNo` int(11) NOT NULL,
            `deviceNo` int(11) DEFAULT NULL,
            `invoiceNo` int(11) DEFAULT NULL,
            `buildingNo` int(11) NOT NULL DEFAULT 0,
            `itemNo` int(11) DEFAULT NULL,
            `aName` text DEFAULT NULL,
            `eName` text DEFAULT NULL,
            `orderNo` int(11) DEFAULT NULL,
            `unitNo` int(11) DEFAULT NULL,
            `itemBuyerIdentifier` text DEFAULT NULL,
            `itemSellerIdentifier` text DEFAULT NULL,
            `itemStandardIdentifier` text DEFAULT NULL,
            `price` float DEFAULT NULL,
            `quantity` float DEFAULT NULL,
            `discount` float DEFAULT NULL,
            `vatRate` int(11) DEFAULT NULL,
            `vatAmount` float DEFAULT NULL,
            `netAmount` float DEFAULT NULL,
            `amountWithVAT` float DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY `asd` (`documentNo`)
        ) ENGINE=InnoDB $charset_collate;
    ";

    // Execute the query
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

}

// Create zatcadocumentxml table & insert Data:
function create_zatcadocumentxml_table() {

    global $wpdb;
    $table_name = 'zatcadocumentxml';
    $charset_collate = $wpdb->get_charset_collate();

    // SQL to create the table
    $sql = "
        CREATE TABLE IF NOT EXISTS  $table_name (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `documentNo` int(11) NOT NULL,
            `deviceNo` int(11) NOT NULL,
            `previousInvoiceHash` text DEFAULT NULL,
            `invoiceHash` text DEFAULT NULL,
            `APIRequest` text DEFAULT NULL,
            `APIResponse` text DEFAULT NULL,
            `typeClearanceOrReporting` int(11) DEFAULT NULL,
            `qrCode` text DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY `doc` (`documentNo`)
        ) ENGINE=InnoDB $charset_collate;
    ";

    // Execute the query
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

}

// Create zatcainfo table & insert Data:
function create_zatcainfo_table() {

    global $wpdb;
    $table_name = 'zatcainfo';
    $charset_collate = $wpdb->get_charset_collate();

    // SQL to create the table
    $sql = "
        CREATE TABLE IF NOT EXISTS  $table_name (
            `zatcaInfo1` longtext DEFAULT NULL,
            `zatcaInfo2` longtext DEFAULT NULL,
            `zatcaInfo3` longtext DEFAULT NULL
        ) ENGINE=InnoDB $charset_collate;
    ";

    // Execute the query
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

}

// Create zatcalog table & insert Data:
function create_zatcalog_table() {

    global $wpdb;
    $table_name = 'zatcalog';
    $charset_collate = $wpdb->get_charset_collate();

    // SQL to create the table
    $sql = "
        CREATE TABLE IF NOT EXISTS  $table_name (
            `VendorId` int(11) DEFAULT 0,
            `profilerLogNo` int(11) NOT NULL AUTO_INCREMENT,
            `dateG` timestamp NOT NULL DEFAULT current_timestamp(),
            `login_personNo` int(11) DEFAULT NULL,
            `login_personName` longtext DEFAULT NULL,
            `macAddress` longtext DEFAULT NULL,
            `IP` varchar(50) DEFAULT NULL,
            `isSuccess` tinyint(1) DEFAULT NULL,
            `operationType` int(11) DEFAULT NULL,
            PRIMARY KEY (`profilerLogNo`)
        ) ENGINE=InnoDB $charset_collate;
    ";

    // Execute the query
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

}

// Create zatcauser table & insert Data:
function create_zatcauser_table() {

    global $wpdb;
    $table_name = 'zatcauser';
    $charset_collate = $wpdb->get_charset_collate();

    // SQL to create the table
    $sql = "
        CREATE TABLE IF NOT EXISTS  $table_name (
            `personNo` int(11) NOT NULL,
            `aName` varchar(255) NOT NULL,
            `eName` varchar(255) NOT NULL,
            `ZATCA_B2C_NotIssuedDocuments_isRemind` tinyint(1) DEFAULT NULL,
            `ZATCA_B2C_NotIssuedDocumentsReminderInterval` int(11) DEFAULT NULL,
            PRIMARY KEY (`personNo`)
        ) ENGINE=InnoDB $charset_collate;
    ";

    // Execute the query
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

}


// run all create functions:
function create_custom_tables(){

    create_zatca_country_table();
    create_zatca_met_vatcategorycode_table();
    create_zatca_met_vatcategorycodesubtype_table();
    create_zatcabranch_table();
    create_zatcabusinessidtype_table();
    create_zatcacompany_table();
    create_zatcacustomer_table();
    create_zatcadocument_table();
    create_zatcadocumentunit_table();
    create_zatcadocumentxml_table();
    create_zatcainfo_table();
    create_zatcalog_table();
    create_zatcauser_table();

}