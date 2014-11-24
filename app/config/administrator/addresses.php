<?php 
return array(
/**
 * Model title
 *
 * @type string
 */
'title' => 'Addresses',
/**
 * The singular name of your model
 *
 * @type string
 */
'single' => 'address',
/**
 * The class name of the Eloquent model that this config represents
 *
 * @type string
 */
'model' => 'Addresses',
/**
 * The columns array
 *
 * @type array
 */
'columns' => array(
	'id' => array(
        'title' => 'Id'
    ),
	'address' => array(
        'title' => 'Address'
    ),
    'name' => array(
        'title' => 'Name'
    ),
    'email' => array(
        'title' => 'Email'
    ),
	'user' => array(
	        'title' => 'Owned By',
	        'relationship'=>'user',
         	'select' => "(:table).email"
	    ),
    
    'created_at' => array(
        'title' => 'created'
    ),
),
/**
 * The edit fields array
 *
 * @type array
 */
'edit_fields' => array(
    'address' => array(
        'title' => 'Address',
        'type' => 'text',
    ),
    'name' => array(
        'title' => 'Name',
        'type'	=> 'text',
    ),
),
/**
 * The filter fields
 *
 * @type array
 */
'filters' => array(
    'id',
    'name' => array(
        'title' => 'Email',
    ),
    'user' => array(
        'title' => 'Owner',
    ),
    'created_at' => array(
        'title' => 'Date',
        'type' => 'date',
    ),
),

);
