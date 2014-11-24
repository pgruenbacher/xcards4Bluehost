<?php 

return array(
/**
 * Model title
 *
 * @type string
 */
'title' => 'Users',
/**
 * The singular name of your model
 *
 * @type string
 */
'single' => 'user',
/**
 * The class name of the Eloquent model that this config represents
 *
 * @type string
 */
'model' => 'User',
/**
 * The columns array
 *
 * @type array
 */
'columns' => array(
	'id' => array(
        'title' => 'Id'
    ),
    'first' => array(
        'title' => 'First Name'
    ),
    'last' => array(
        'title' => 'Last'
    ),
    'email' => array(
        'title' => 'Email'
    ),
    'credits' => array(
        'title' => 'Credits'
    ),
    'active' => array(
        'title' => 'Activated'
    ),
    'created_at' => array(
        'title' => 'Created'
    ),
),
/**
 * The edit fields array
 *
 * @type array
 */
'edit_fields' => array(
    'first' => array(
        'title' => 'First Name',
        'type' => 'text'
    ),
     'last' => array(
        'title' => 'Last Name',
        'type' => 'text'
    ),
    'email' => array(
        'title' => 'Email',
        'type'=> 'text'
    ),
    'credits' => array(
        'title' => 'Credits',
        'type'=> 'text'
    ),
    'active' => array(
        'title' => 'Activated',
        'type'=>'bool'
    ),
),
'filters' => array(
    'email' => array(
        'title' => 'Email',
    ),
    'created_at' => array(
        'title' => 'Created',
        'type' => 'date',
    ),
    'guest'=>array(
    	'title'=>'Guest',
    	'type'=>'bool'
	),
),
'actions' => array(
    //Ordering an item up
    'recoverPassword' => array(
        'title' => 'Reset Password',
        'messages' => array(
            'active' => 'reseting...',
            'success' => 'Reseted!',
            'error' => 'There was an error while resetting',
        ),
        //the model is passed to the closure
        'action' => function($model)
        {
            //get all the items of this model and reorder them
            $model->recoverPassword();
			return true;
    	}
    ),
),
);
