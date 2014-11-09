<?php

class Jobs extends Eloquent {

    protected $table = 'jobs';

    protected $fillable = array('job_id', 'status');

}