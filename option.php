<?php


add_action('admin_menu', 'my_custom_menu');

function my_custom_menu() {

    $current_page = isset($_GET['page']) ? sanitize_text_field($_GET['page']) : 'view'; // Default to 'view'
    // Add main menu page
    add_menu_page(
        __( 'ZATCA', 'zatca' ), // Page title
        __( 'ZATCA', 'zatca' ), // Menu title
        'manage_options', // Capability required to access menu
        'zatca', // Menu slug
        'zatca_main_page', // Callback function to display content
        'dashicons-admin-multisite', // Icon URL or WP Dashicons class
        25 // Position in the menu
    );

    // Add sub-menu pages
    add_submenu_page(
        'zatca', // Parent menu slug
        __( 'Devices', 'zatca' ), // Page title
        __( 'Devices', 'zatca' ), // Menu title
        'manage_options', // Capability required to access menu
        'zatca-devices', // Menu slug
        'zatca_devices_page' // Callback function to display content
    );

    add_submenu_page(
        'zatca', // Parent menu slug
        __( 'Customers', 'zatca' ), // Page title
        __( 'Customers', 'zatca' ), // Menu title
        'manage_options', // Capability required to access menu
        'zatca-customers', // Menu slug
        'zatca_customers_page' // Callback function to display content
    );

    add_submenu_page(
        'zatca', // Parent menu slug
        __( 'Company', 'zatca' ), // Page title
        __( 'Company', 'zatca' ), // Menu title
        'manage_options', // Capability required to access menu
        'zatca-company', // Menu slug
        'zatca_company_page' // Callback function to display content
    );

    
    add_submenu_page(
        'zatca', // Parent menu slug
        __( 'Documents', 'zatca' ), // Page title
        __( 'Documents', 'zatca' ), // Menu title
        'manage_options', // Capability required to access menu
        'zatca-documents', // Menu slug
        'zatca_documents_page' // Callback function to display content
    );

    add_submenu_page(
        'zatca', // Parent menu slug
        __( 'Logs', 'zatca' ), // Page title
        __( 'Logs', 'zatca' ), // Menu title
        'manage_options', // Capability required to access menu
        'zatca-logs', // Menu slug
        'zatca_logs_page' // Callback function to display content
    );

    add_submenu_page(
        'zatca', // Parent menu slug
        __( 'Users', 'zatca' ), // Page title
        __( 'Users', 'zatca' ), // Menu title
        'manage_options', // Capability required to access menu
        'zatca-users', // Menu slug
        'zatca_users_page' // Callback function to display content
    );
}

// Callback functions to display content for each page
function zatca_main_page() {?>

    <!-- Content for the main page -->
    <div class="col-xl-12 mx-auto mt-3">
        <h3 class="mb-0 text-uppercase text-center"><?php echo _e('ZATCA', 'zatca') ?></h3>
        <h4 class="mb-0 text-center"><?php echo _e('Operations', 'zatca') ?></h4>
    </div>
<?php
}

function zatca_devices_page() {

    // Content for the customers page
    $action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : 'view'; // Default action to 'view'



    // Include specific content based on action
    switch ($action) {
        case 'view':
            include 'devices/view.php';
            break;
        case 'edit-device':
            include 'devices/edit-device.php';
            break;
        case 'insert-device':
            include 'devices/insert.php';
            break;
        case 'delete':
            include 'devices/delete.php';
            break;
        default:
            // Handle invalid actions (optional)
            break;
    }
}

function zatca_customers_page() {
    // Content for the customers page
    $action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : 'view'; // Default action to 'view'



    // Include specific content based on action
    switch ($action) {
        case 'view':
            include 'customers/view.php';
            break;
        case 'edit-customer':
            include 'customers/edit-customer.php';
            break;
        case 'insert':
            include 'customers/insert.php';
            break;
        case 'delete':
            include 'customers/delete.php';
            break;
        default:
            // Handle invalid actions (optional)
            break;
    }
}

function zatca_company_page() {
    // Content for the company page
    include 'company/view.php';
}

function zatca_logs_page() {
    // Content for the company page
    include 'log/view.php';
}

function zatca_documents_page() {
   
     // Content for the Documents page
     $action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : 'view'; // Default action to 'view'



     // Include specific content based on action
     switch ($action) {
         case 'view':
             include 'documents/view.php';
             break;
         case 'doc-add-customer':
             include 'documents/doc-add-customer.php';
             break;
         case 'edit-document':
             include 'documents/edit-document.php';
             break;
         case 'insert':
             include 'documents/insert.php';
             break;
         case 'delete':
             include 'documents/delete.php';
             break;
         default:
             // Handle invalid actions (optional)
             break;
     }
}

function zatca_users_page() {
   
     // Content for the Documents page
     $action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : 'view'; // Default action to 'view'



     // Include specific content based on action
     switch ($action) {
         case 'view':
             include 'users/view.php';
             break;
         case 'edit-user':
             include 'users/edit-user.php';
             break;
         case 'insert':
             include 'users/insert.php';
             break;
         case 'delete':
             include 'users/delete.php';
             break;
         default:
             // Handle invalid actions (optional)
             break;
     }
}