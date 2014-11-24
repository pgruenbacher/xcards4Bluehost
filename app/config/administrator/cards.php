<?php 
return array(
/**
 * Model title
 *
 * @type string
 */
'title' => 'Cards',
/**
 * The singular name of your model
 *
 * @type string
 */
'single' => 'card',
/**
 * The class name of the Eloquent model that this config represents
 *
 * @type string
 */
'model' => 'Cards',
/**
 * The columns array
 *
 * @type array
 */
'columns' => array(
	'id' => array(
	        'title' => 'Id'
	    ),
    'id' =>array(
		'title' => 'Image',
		'output' => function($value){
			$card=Cards::find($value);
			$image='no upload';
			if(isset($card->originalImage->id)){
				$image='no thumbnail';
				if(isset($card->originalImage->thumbnail->url_path)){
					$image='<img width="100%" src="'.$card->originalImage->thumbnail->url_path.'"/>';
				}
			}
			return '<div style="width:150px">'.$image.'</div>';
		}
	),
	'setting' =>array(
		'title' => 'Type',
		'relationship'=>'cardSetting',
		'select' => "(:table).name",
	),
	'email' => array(
	        'title' => 'User Email',
	        'relationship'=>'user',
         	'select' => "(:table).email"
	    ),
    'back_message' => array(
        'title' => 'Text'
    ),
    'created_at' => array(
        'title' => 'created'
    ),
    'finished_at' => array(
        'title' => 'finished'
    ),
),
/**
 * The edit fields array
 *
 * @type array
 */
'edit_fields' => array(
    'back_message' => array(
        'title' => 'Text',
        'type' => 'wysiwyg'
    )
),
/**
 * The filter fields
 *
 * @type array
 */
'filters' => array(
    'id',
    'setting',
    'email' => array(
        'title' => 'Email',
    ),
    'created_at' => array(
        'title' => 'Created',
        'type' => 'date',
    ),
),
);
