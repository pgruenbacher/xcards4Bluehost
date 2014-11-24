<?php 
return array(
/**
 * Model title
 *
 * @type string
 */
'title' => 'Orders',
/**
 * The singular name of your model
 *
 * @type string
 */
'single' => 'order',
/**
 * The class name of the Eloquent model that this config represents
 *
 * @type string
 */
'model' => 'Orders',
/**
 * The columns array
 *
 * @type array
 */
'columns' => array(
	'id' => array(
        'title' => 'Id'
    ),
	'reference' => array(
        'title' => 'Ref #'
    ),
    'credits' => array(
        'title' => 'Credits'
    ),
    'charge' => array(
        'title' => 'Charged',
        'output'=> function($value){
        	return '$'.$value/100;
    	},
    ),
	'user' => array(
	        'title' => 'Order By',
	        'relationship'=>'user',
         	'select' => "(:table).email"
    ),
    'card' => array(
	        'title' => 'CardID',
	        'relationship'=>'card',
         	'select' => "(:table).id"
    ),
    'cards' => array(
	        'title' => '#Cards',
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
    'reference' => array(
        'title' => 'Ref #',
        'type' => 'text'
    ),
    'credits' => array(
        'title' => 'Credits',
        'type'	=> 'number',
    ),
    'charge' => array(
        'title' => 'Charged',
        'type'	=> 'number',
    ),
),
/**
 * The filter fields
 *
 * @type array
 */
'filters' => array(
    'id',
    'user' => array(
        'title' => 'Owner',
    ),
    'created_at' => array(
        'title' => 'Date',
        'type' => 'date',
    ),
    'credits' => array(
        'title' => 'Credits',
        'type'	=> 'number',
    ),
    'charge' => array(
        'title' => 'Charged',
        'type'	=> 'number',
    ),
),

);
