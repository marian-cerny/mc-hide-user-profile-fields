<?php
/*
Plugin name: Hide user profile fields
Author name: Marian Cerny
Author URL: http://mariancerny.com
Description: A simple plug-in for hiding unnecessary fields from the user profile page.
*/




// *******************************************************************
// ------------------------------------------------------------------
//						COLOURS
// ------------------------------------------------------------------
// *******************************************************************

remove_action("admin_color_scheme_picker", "admin_color_scheme_picker");



// *******************************************************************
// ------------------------------------------------------------------
//						CONTACT METHODS
// ------------------------------------------------------------------
// *******************************************************************

function remove_contactmethods( $contactmethods ) {
  unset($contactmethods['aim']);
  unset($contactmethods['jabber']);
  unset($contactmethods['yim']);
  return $contactmethods;
}
add_filter('user_contactmethods','remove_contactmethods',10,1);



// *******************************************************************
// ------------------------------------------------------------------
//						ABOUT YOU
// ------------------------------------------------------------------
// *******************************************************************

if (! function_exists('remove_plain_bio') ){
    function remove_plain_bio($buffer) {
        $titles = array('#<h3>About Yourself</h3>#','#<h3>About the user</h3>#');
        $buffer=preg_replace($titles,'<h3>Password</h3>',$buffer,1);
        $biotable='#<h3>Password</h3>.+?<table.+?/tr>#s';
        $buffer=preg_replace($biotable,'<h3>Password</h3> <table class="form-table">',$buffer,1);
        return $buffer;
    }

    function profile_admin_buffer_start() { ob_start("remove_plain_bio"); }
    function profile_admin_buffer_end() { ob_end_flush(); }
}
add_action('admin_head', 'profile_admin_buffer_start');
add_action('admin_footer', 'profile_admin_buffer_end');



// *******************************************************************
// ------------------------------------------------------------------
//						PERSONAL OPTIONS
// ------------------------------------------------------------------
// *******************************************************************

if ( ! function_exists( 'cor_remove_personal_options' ) ) {
  /**
   * Removes the leftover 'Visual Editor', 'Keyboard Shortcuts' and 'Toolbar' options.
   */
  function cor_remove_personal_options( $subject ) {
    $subject = preg_replace( '#<h3>Personal Options</h3>.+?/table>#s', '', $subject, 1 );
    return $subject;
  }

  function cor_profile_subject_start() {
    ob_start( 'cor_remove_personal_options' );
  }

  function cor_profile_subject_end() {
    ob_end_flush();
  }
}
add_action( 'admin_head-profile.php', 'cor_profile_subject_start' );
add_action( 'admin_footer-profile.php', 'cor_profile_subject_end' );