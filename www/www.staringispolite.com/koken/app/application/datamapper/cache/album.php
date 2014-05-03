<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
$cache = array (
  'table' => 'koken_albums',
  'fields' => 
  array (
    0 => 'id',
    1 => 'title',
    2 => 'slug',
    3 => 'summary',
    4 => 'description',
    5 => 'listed',
    6 => 'level',
    7 => 'left_id',
    8 => 'right_id',
    9 => 'deleted',
    10 => 'featured',
    11 => 'featured_on',
    12 => 'featured_order',
    13 => 'total_count',
    14 => 'video_count',
    15 => 'created_on',
    16 => 'modified_on',
    17 => 'album_type',
    18 => 'tags',
    19 => 'internal_id',
  ),
  'validation' => 
  array (
    'internal_id' => 
    array (
      'label' => 'Internal id',
      'rules' => 
      array (
        0 => 'internalize',
        1 => 'required',
      ),
      'field' => 'internal_id',
    ),
    'created_on' => 
    array (
      'rules' => 
      array (
        0 => 'validate_created_on',
      ),
      'field' => 'created_on',
    ),
    'left_id' => 
    array (
      'rules' => 
      array (
        0 => 'into_tree',
        1 => 'required',
      ),
      'field' => 'left_id',
    ),
    'listed' => 
    array (
      'rules' => 
      array (
        0 => 'tree',
      ),
      'field' => 'listed',
    ),
    'tags' => 
    array (
      'rules' => 
      array (
        0 => 'format_tags',
      ),
      'field' => 'tags',
    ),
    'title' => 
    array (
      'rules' => 
      array (
        0 => 'required',
      ),
      'get_rules' => 
      array (
        0 => 'readify',
      ),
      'field' => 'title',
    ),
    'slug' => 
    array (
      'rules' => 
      array (
        0 => 'slug',
        1 => 'required',
      ),
      'field' => 'slug',
    ),
    'id' => 
    array (
      'field' => 'id',
      'rules' => 
      array (
        0 => 'integer',
      ),
    ),
    'summary' => 
    array (
      'field' => 'summary',
      'rules' => 
      array (
      ),
    ),
    'description' => 
    array (
      'field' => 'description',
      'rules' => 
      array (
      ),
    ),
    'level' => 
    array (
      'field' => 'level',
      'rules' => 
      array (
      ),
    ),
    'right_id' => 
    array (
      'field' => 'right_id',
      'rules' => 
      array (
      ),
    ),
    'deleted' => 
    array (
      'field' => 'deleted',
      'rules' => 
      array (
      ),
    ),
    'featured' => 
    array (
      'field' => 'featured',
      'rules' => 
      array (
      ),
    ),
    'featured_on' => 
    array (
      'field' => 'featured_on',
      'rules' => 
      array (
      ),
    ),
    'featured_order' => 
    array (
      'field' => 'featured_order',
      'rules' => 
      array (
      ),
    ),
    'total_count' => 
    array (
      'field' => 'total_count',
      'rules' => 
      array (
      ),
    ),
    'video_count' => 
    array (
      'field' => 'video_count',
      'rules' => 
      array (
      ),
    ),
    'modified_on' => 
    array (
      'field' => 'modified_on',
      'rules' => 
      array (
      ),
    ),
    'album_type' => 
    array (
      'field' => 'album_type',
      'rules' => 
      array (
      ),
    ),
    'content' => 
    array (
      'field' => 'content',
      'rules' => 
      array (
      ),
    ),
    'category' => 
    array (
      'field' => 'category',
      'rules' => 
      array (
      ),
    ),
    'text' => 
    array (
      'field' => 'text',
      'rules' => 
      array (
      ),
    ),
    'cover' => 
    array (
      'field' => 'cover',
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
    'category' => 
    array (
      'auto_populate' => true,
      'class' => 'category',
      'other_field' => 'album',
      'join_self_as' => 'album',
      'join_other_as' => 'category',
      'join_table' => '',
      'reciprocal' => false,
      'cascade_delete' => true,
    ),
    'text' => 
    array (
      'auto_populate' => true,
      'class' => 'text',
      'other_field' => 'album',
      'join_self_as' => 'album',
      'join_other_as' => 'text',
      'join_table' => '',
      'reciprocal' => false,
      'cascade_delete' => true,
    ),
    'cover' => 
    array (
      'class' => 'content',
      'join_table' => 'koken_join_albums_covers',
      'other_field' => 'covers',
      'join_other_as' => 'cover',
      'join_self_as' => 'album',
      'auto_populate' => true,
      'reciprocal' => false,
      'cascade_delete' => true,
    ),
    'content' => 
    array (
      'class' => 'content',
      'other_field' => 'album',
      'join_self_as' => 'album',
      'join_other_as' => 'content',
      'join_table' => '',
      'reciprocal' => false,
      'auto_populate' => NULL,
      'cascade_delete' => true,
    ),
  ),
  '_field_tracking' => 
  array (
    'get_rules' => 
    array (
      0 => 'title',
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