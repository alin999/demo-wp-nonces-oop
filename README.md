# Composer Package - WoprPress Nonces OOP Class

Composer package, which serves the functionality working with WordPress Nonces.
Implementing wp_nonce_*() function in an object orientated way.

## Installation 
shell:
```shell
	composer require alin999/demo-wp-nonces-oop
```
or, just add in your composer file:
```
{
    "repositories": [
        {
            "type": "vcs",
            "url" : "https://github.com/alin999/demo-wp-nonces-oop"
        }
    ],
    "require": {
        "alin999/demo-wp-nonces-oop" : "dev-master"
    }
}
```

## Public Functions 

  - [set_action](#set_action)
  - [get_action](#get_action )
  - [set_nonce_name](#set_nonce_name)
  - [get_nonce_name](#get_nonce_name)
  - [create_nonce](#create_nonce)
  - [nonce_to_url](#nonce_to_url)
  - [nonce_to_field](#nonce_to_field)
  - [nonce_ays](#nonce_ays)
  - [verify_nonce](#verify_nonce)
  - [check_admin_referer](#check_admin_referer)
  - [check_ajax_referer](#check_ajax_referer)
  
  
## Usage 

```php
    $my_nonce = new WP_Nonce;
```
 
### set_action
_It sets a custom nonce action_  
Parameters:
- __$action__ (string/int) (optional) Nonce action. If missing, the daluat value will be -1
```php
    $my_nonce->set_action('test-action');
    echo $my_nonce->action;
```

### get_action
_Returns the nonce action_  

```php
    $action = $my_nonce->get_action();
    echo $action;
```

### set_nonce_name
_It sets a custom nonce name  
Parameters:
- __$name__ (string/int) (optional) Action name. If missing, the default value is __wpnonce_
```php
    $my_nonce->set_nonce_name( '_wpnonce' );
    echo $my_nonce->nouce_name;
```

### get_nonce_name
_Returns the nonce name_  
```php
    $name = $my_nonce->get_nonce_name();
    echo $name;
```

### create_nonce
_Function to generate and return a nonce based on WordPress [wp_create_nonce](https://codex.wordpress.org/Function_Reference/wp_create_nonce) function_  
Returns: string  
Parameters:
- __$action__ (string/int) (optional) Nonce action.Optional. If missing, the class var ($my_nonce->action) will be used
```php
    $nonce = $my_nonce->create_nonce();
    echo $nonce;
```

### nonce_to_url
_Function to add a nonce to an URL and return the updated URL. It wraps WordPress function [wp_nonce_url](https://codex.wordpress.org/Function_Reference/wp_nonce_url)_  
Returns: string : the URL with nonce action added  
Parameters:
- __$actionurl__  string      URL where to add nonce action
- __$action__     string|int  Optional. Nonce action name. If null or blank, the class var (action) will be used
- __$name__       string      Optional. Nonce name. If missing, null or blank, the class var (nonce_name) will be used
```php
    $test_url = $my_nonce->nonce_to_url( 'http://my-wp-site.com', -1, '_wpnonce');
    echo $test_url;
```

### nonce_to_field
_Function to retrieve or display the nonce hidden form field. It wraps WordPress function [wp_nonce_field](https://codex.wordpress.org/Function_Reference/wp_nonce_url)_  
Returns: string : the nonce hidden form field  
Parameters:
- __$action__	string|int	Optional. Nonce action name. If null or blank, the class var (action) will be used
- __$name__		string		Optional. Nonce name. If missing, null or blank, the class var (nonce_name) will be used
- __$referer__	boolean		Optional. Whether also the referer hidden form field should be created with the wp_referer_field() function. Default is true
- __$echo__		boolean		Optional. Whether to display or return the nonce hidden form field. Defalut is true                         
```php
    $nonce_field = $my_nonce->nonce_to_field( -1, '_wpnonce', true, false );
    echo $nonce_field;
```

### nonce_ays
_Function to display 'Are you sure you want to do this?' message to confirm the action being taken.It wraps WordPress function [wp_nonce_ays](https://codex.wordpress.org/Function_Reference/wp_nonce_ays)_  
Parameters:
- __$action__	string|int	Optional. Nonce action name. If null or blank, the class var (action) will be used                       
```php
    $my_nonce->nonce_ays();
```

### verify_nonce
_Function to verify that a nonce is correct and unexpired with the respect to a specified action.It wraps WordPress function [wp_verify_nonce](https://codex.wordpress.org/Function_Reference/wp_verify_nonce)_  
Returns: (boolean|integer)       False if the nonce is invalid. Otherwise, returns an integer with the value of: _1_ – if generated in the past 12 hours or less or  _2_ – if generated between 12 and 24 hours ago.  
Parameters:
- __$nonce__	string		Required. Nonce to verify.
- __$action__	string|int	Optional. Nonce action name. If null or blank, the class var (action) will be used                   
```php
    $nonce = $my_nonce->create_nonce();
    $verify_nonce_response = $my_nonce->verify_nonce($nonce);
    echo $verify_nonce_response;
```

### check_admin_referer
_Function to tests either if the current request carries a valid nonce, or if the current request was referred from an administration screen; depending on whether the $action argument is given (which is prefered), or not, respectively. On failure, the function dies after calling the wp_nonce_ays() function._  
_It wraps WordPress function [check_admin_referer](https://codex.wordpress.org/Function_Reference/check_admin_referer)_    
Returns: To return boolean true, in the case of the obsolete usage, the current request must be referred from an administration screen; in the case of the prefered usage, the nonce must be sent and valid. Otherwise the function dies with an appropriate message ("Are you sure you want to do this?" by default).
Parameters:
- __$action__	string|int	Optional. Nonce action name. If null or blank, the class var (action) will be used 
- __$query_arg_name__   string           Optional.   Nonce name. Where to look for nonce in the $REQUEST PHP variable. If missing, null or blank, the class var (nonce_name) will be used    

```php
	//You add a nonce to a form using the wp_nonce_field() function:
		<form method="post">
		   <!-- some inputs here -->
		   <?php $my_nonce->nonce_to_field( 'name_of_my_action', 'name_of_nonce_field' , true , true ); ?>
		</form>
	
	//Then in the page where the form submits to, you can verify whether or not the form was submitted and update values if it was successfully submitted:
	<?php
	$my_nonce->check_admin_referer( 'name_of_my_action', 'name_of_nonce_field' );
	// process form data, e.g. update fields
	// you can use it in a IF statement if you want, not mandatory because there is not "false" return, only true or die().
```

### check_ajax_referer
This function can be overridden by plugins. If no plugin redefines this function, then the standard functionality will be used.The standard function verifies the AJAX request, to prevent any processing of requests which are passed in by third-party sites or systems.  
It wraps WordPress function [check_ajax_referer](https://codex.wordpress.org/Function_Reference/check_ajax_referer)_  
Returns: boolean . If parameter $die is set to false, this function will return a boolean of true if the check passes or false if the check fails.  
Parameters:
- __$action__	string|int	Optional. Nonce action name. If null or blank, the class var (action) will be used 
- __$query_arg__	string       optional. Where to look for nonce in $REQUEST. Default: false
- __$die__	@param   boolean             optional. Whether to die if the nonce is invalid. Default: true
Example
```
	$my_nonce->check_ajax_referer('my-action', 'my-query-arg');
```
