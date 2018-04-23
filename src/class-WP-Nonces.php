<?php
/**
 *  WordPress Nonces OOP example
 * 
 * Package URI: https://github.com/alin999/demo-wp-nonces-oop-class
 * Description: A demo composer package for a class that handles WordPress Nonces in an OOP way
 * Author:      Alin Duica
 * Version:     1.0.0
 * License:     http://opensource.org/licenses/MIT MIT
 */

namespace WpNonces;


/**
 * Class WP_Nonce
 *
 * @author  Alin Duica
 * @package alin999/demo-wp-nonces-oop-class
 * @license http://opensource.org/licenses/MIT MIT
 */


class WP_Nonce {

    /**
     * Stores nonce name
     *
     * @var     string      defalut : _wpnonce
     */
    private $nonce_name='_wpnonce';    

    /**
     * Stores nonce action name
     *
     * @var     int|string  defalut : -1
     */
    private $action=-1;
    
 
    /**
     * Initialize Nonce object
     * 
     * @param   string  $action Optional. Nonce action
     */
    public function __constructor( $action = -1 ) {
        if( $this->exists_and_not_empty($action) ){
            $this->action = $action;
        }
    }
    
    /**
     * Group of Basic Set/Get Section
     * 
     * Set/Get for action and nonce_name class variables
     * @param   string|int  $action         (Nonce action) - for set_action method
     * @param   string      $nonce_name    (Nonce name) - for set_nonce_name method
     */  
    public function get_action() {
        return $this->action;
    }
    public function set_action( $action = NULL ) {
        $this->action = $action;
    }
    
    public function get_nonce_name() {
        return $this->nonce_name;
    }
    public function set_nonce_name($nonce_name) {
        $this->nonce_name = $nonce_name;
    }
    
    /**
     * Function to generate and return a nonce based on WordPress wp_create_nonce function 
     *                                  (https://codex.wordpress.org/Function_Reference/wp_create_nonce)
     *
     * @param   string|int  $action     Optional. If missing, the class var (action) will be used
     *
     * @return  string
     */
    public function create_nonce( $action = NULL ) {
        if( $this->exists_and_not_empty($action ) ){
            return wp_create_nonce( $action );
        }else{
            return wp_create_nonce( $this->action );
        }
    }
    
    /**
     * Function to add a nonce to an URL and return the updated URL. It wraps WordPress function wp_nonce_url
     *                                  (https://codex.wordpress.org/Function_Reference/wp_nonce_url)
     *
     * @param   string      $actionurl  URL where to add nonce action
     * @param   string|int  $action     Optional. Nonce action name. If null or blank, the class var (action) will be used
     * @param   string      $name       Optional. Nonce name. If missing, null or blank, the class var (nonce_name) will be used
     *                              
     * @return  string  URL with nonce action added
     */
    public function nonce_to_url( $actionurl,  $action = NULL, $name = NULL ) {
        if( !$this->exists_and_not_empty($action) ){
            $action = $this->action;
        }
        if( !$this->exists_and_not_empty($name) ){
            $name = $this->nonce_name;
        }
      return wp_nonce_url( $actionurl, $action, $name );
    }

    /**
     * Function to retrieve or display the nonce hidden form field. It wraps WordPress function wp_nonce_field
     *                                  (https://codex.wordpress.org/Function_Reference/wp_nonce_url)
     *
     * @param   string|int  $action     Optional (strongly recommended). Nonce action name. If null or blank, the class var (action) will be used
     * @param   string      $name       Optional (strongly recommended). Nonce name. If missing, null or blank, the class var (nonce_name) will be used    
     * @param   boolean     $referer    Optional. Whether also the referer hidden form field should be created with the wp_referer_field() function.
     * @param   boolean     $echo       Optional. Whether to display or return the nonce hidden form field
     *                            
     * @return  string  The nonce hidden form field
     */
    public function nonce_to_field( $action = NULL, $name = NULL, $referer = true, $echo = true ) {
        if( !$this->exists_and_not_empty($action) ){
            $action = $this->action;
        }
        if( !$this->exists_and_not_empty($name) ){
            $name = $this->nonce_name;
        } 
        return wp_nonce_field( $this->action, $name, $referer, $echo );
    }    
    
    

  /**
   * Function to display 'Are you sure you want to do this?' message to confirm the action being taken.It wraps 
   *                                    WordPress function wp_nonce_ays (https://codex.wordpress.org/Function_Reference/wp_nonce_ays)
   *
   * If the action has the nonce explain message, then it will be displayed along with the 'Are you sure?' message.
   *
   * @param   string|int    $action     Optional (strongly recommended). Nonce action name. If null or blank, the class var (action) will be used 
   */
    public function nonce_ays( $action = NULL ) {
        if( !$this->exists_and_not_empty($action) ){
            $action = $this->action;
        }
        wp_nonce_ays( $action );
    }

    /**
     * Function to verify that a nonce is correct and unexpired with the respect to a specified action.It wraps 
   *                                    WordPress function wp_verify_nonce (https://codex.wordpress.org/Function_Reference/wp_verify_nonce)
     *
     * The function is used to verify the nonce sent in the current request usually accessed by the $_REQUEST PHP variable.
     *
     * @param   string      $nonce      Required. Nonce to verify.
     * @param   string|int  $action     Optional. Nonce action name. If null or blank, the class var (action) will be used 
     *
     * @return  (boolean|integer)       False if the nonce is invalid. Otherwise, returns an integer with the value of:
     *                                      1 – if generated in the past 12 hours or less.
     *                                      2 – if generated between 12 and 24 hours ago.
     */
    public function verify_nonce( $nonce, $action = NULL ) {
        if( !$this->exists_and_not_empty($action) ){
            $action = $this->action;
        }
        return wp_verify_nonce( $nonce, $action );
    }
    
    /**
     * Function to tests either if the current request carries a valid nonce, or if the current request was referred from
     * an administration screen; depending on whether the $action argument is given (which is prefered), or not,
     * respectively. On failure, the function dies after calling the wp_nonce_ays() function.
     *
     * It wraps WordPress function check_admin_referer (https://codex.wordpress.org/Function_Reference/check_admin_referer)
     *
     * @param   string|int  $action             Optional.   Action name. Should give the context to what is taking place.
     *                                                      If null or blank, the class var (action) will be used
     * @param   string      $query_arg_name     Optional.   Nonce name. Where to look for nonce in the $_REQUEST PHP variable. 
     *                                                      If missing, null or blank, the class var (nonce_name) will be used    

     * @return To return boolean true, in the case of the obsolete usage, the current request must be referred from
     * an administration screen; in the case of the prefered usage, the nonce must be sent and valid. Otherwise the
     * function dies with an appropriate message ("Are you sure you want to do this?" by default).
     */
    public function check_admin_referer( $action = NULL, $query_arg_name = NULL ) {
        if( !$this->exists_and_not_empty($action) ){
            $action = $this->action;
        }
        if( !$this->exists_and_not_empty($query_arg_name) ){
            $query_arg_name = $this->nonce_name;
        } 
        return check_admin_referer( $action, $query_arg_name );
    }
    
    /**
     * This function can be overridden by plugins. If no plugin redefines this function, then the standard functionality
     * will be used.
     *
     * The standard function verifies the AJAX request, to prevent any processing of requests which are passed in by
     * third-party sites or systems.
     *
     * It wraps WordPress function check_ajax_referer (https://codex.wordpress.org/Function_Reference/check_ajax_referer)
     *
     * @param   string|int  $action     optional. Action nonce. If null or blank, the class var (action) will be used
     * @param   string      $query_arg  optional. Where to look for nonce in $_REQUEST. Default: false
     * @param   boolean     $die        optional. Whether to die if the nonce is invalid. Default: true
     *
     * @return  boolean     If parameter $die is set to false, this function will return a boolean of true if the check
     *                      passes or false if the check fails.
     */
    public function check_ajax_referer( $action = -1, $query_arg = NULL, $die=true ) {
        if( !$this->exists_and_not_empty($action) ){
            $action = $this->action;
        }
        if( !$this->exists_and_not_empty($query_arg) ){
            $query_arg = $this->nonce_name;
        } 
        return check_ajax_referer( $action, $query_arg, $die );
    }

    
    //INERNAL FUNCTIONS
    /**
     * Function to check if a variable exists and if is not empty (0,"",blank, null)
     *
     * @param   string  $var    Variable that will be checked      
     *
     * @return  boolean     
     */
    private function exists_and_not_empty($var){
        return (isset($var) && !empty($var));
    }

}