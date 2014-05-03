<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
$cache = array (
  'table' => 'koken_tags',
  'fields' => 
  array (
    0 => 'id',
    1 => 'count',
    2 => 'album_count',
    3 => 'text_count',
    4 => 'content_count',
  ),
  'validation' => 
  array (
    'id' => 
    array (
      'field' => 'id',
      'rules' => 
      array (
        0 => 'integer',
      ),
    ),
    'count' => 
    array (
      'field' => 'count',
      'rules' => 
      array (
      ),
    ),
    'album_count' => 
    array (
      'field' => 'album_count',
      'rules' => 
      array (
      ),
    ),
    'text_count' => 
    array (
      'field' => 'text_count',
      'rules' => 
      array (
      ),
    ),
    'content_count' => 
    array (
      'field' => 'content_count',
      'rules' => 
      array (
      ),
    ),
  ),
  'has_one' => 
  array (
  ),
  'has_many' => 
  array (
  ),
  '_field_tracking' => 
  array (
    'get_rules' => 
    array (
    ),
    'matches' => 
    array (
    ),
    'intval' => 
    array (
      0 => 'id',
    ),
  ),
);