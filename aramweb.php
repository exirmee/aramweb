<?php
/*
Plugin Name: Aram Web Wordpress Customizer
Description: Change the logo in the WordPress login page.
Version: 1.0
Author: Morteza Hatami  
Author URI: https://aramweb.ir/
*/

// Plugin activation and deactivation hooks
register_activation_hook(__FILE__, 'wplogin_logo_changer_activate');
register_deactivation_hook(__FILE__, 'wplogin_logo_changer_deactivate');

function wplogin_logo_changer_activate() {
    // Activation tasks (if any)
}

function wplogin_logo_changer_deactivate() {
    // Deactivation tasks (if any)
}
function wplogin_logo_changer_custom_login_logo() {
    ?>
    <style type="text/css">
        .login h1 a {
            background-image: url(<?php echo plugins_url('aram-logo.png', __FILE__); ?>);
            height: 100px; /* Adjust the height as needed */
            width: 100px; /* Adjust the width as needed */
            background-size: contain;
            background-repeat: no-repeat;
            /*padding-bottom: 10px; /* Adjust the logo position as needed */
        }
    </style>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            var loginLogoLink = document.querySelector('.login h1 a');
            loginLogoLink.setAttribute('href', 'http://aramweb.ir/'); // Set the logo link to http://aramweb.ir/
        });
    </script>
    <?php
}
add_action('login_head', 'wplogin_logo_changer_custom_login_logo');
