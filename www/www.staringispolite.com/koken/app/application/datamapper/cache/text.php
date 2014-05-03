<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
$cache = array (
  'table' => 'koken_text',
  'fields' => 
  array (
    0 => 'id',
    1 => 'title',
    2 => 'slug',
    3 => 'featured_image_id',
    4 => 'featured',
    5 => 'featured_on',
    6 => 'featured_order',
    7 => 'custom_featured_image',
    8 => 'content',
    9 => 'excerpt',
    10 => 'published',
    11 => 'page_type',
    12 => 'published_on',
    13 => 'created_on',
    14 => 'modified_on',
    15 => 'tags',
    16 => 'internal_id',
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
    'page_type' => 
    array (
      'rules' => 
      array (
        0 => 'validate_type',
      ),
      'field' => 'page_type',
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
    'created_on' => 
    array (
      'rules' => 
      array (
        0 => 'validate_created_on',
      ),
      'field' => 'created_on',
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
      'get_rules' => 
      array (
        0 => 'readify',
      ),
      'field' => 'title',
      'rules' => 
      array (
      ),
    ),
    'content' => 
    array (
      'rules' => 
      array (
        0 => 'format_content',
      ),
      'field' => 'content',
    ),
    'published' => 
    array (
      'rules' => 
      array (
        0 => 're_slug',
      ),
      'field' => 'published',
    ),
    'id' => 
    array (
      'field' => 'id',
      'rules' => 
      array (
        0 => 'integer',
      ),
    ),
    'featured_image_id' => 
    array (
      'field' => 'featured_image_id',
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
    'custom_featured_image' => 
    array (
      'field' => 'custom_featured_image',
      'rules' => 
      array (
      ),
    ),
    'excerpt' => 
    array (
      'field' => 'excerpt',
      'rules' => 
      array (
      ),
    ),
    'published_on' => 
    array (
      'field' => 'published_on',
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
    'featured_image' => 
    array (
      'field' => 'featured_image',
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
    'album' => 
    array (
      'field' => 'album',
      'rules' => 
      array (
      ),
    ),
  ),
  'has_one' => 
  array (
    'featured_image' => 
    array (
      'class' => 'content',
      'auto_populate' => true,
      'other_field' => 'text',
      'join_self_as' => 'text',
      'join_other_as' => 'featured_image',
      'join_table' => '',
      'reciprocal' => false,
      'cascade_delete' => true,
    ),
  ),
  'has_many' => 
  array (
    'category' => 
    array (
      'auto_populate' => true,
      'class' => 'category',
      'other_field' => 'text',
      'join_self_as' => 'text',
      'join_other_as' => 'category',
      'join_table' => '',
      'reciprocal' => false,
      'cascade_delete' => true,
    ),
    'album' => 
    array (
      'auto_populate' => true,
      'order_by' => 'title',
      'class' => 'album',
      'other_field' => 'text',
      'join_self_as' => 'text',
      'join_other_as' => 'album',
      'join_table' => '',
      'reciprocal' => false,
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