<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
$cache = array (
  'table' => 'koken_content',
  'fields' => 
  array (
    0 => 'id',
    1 => 'title',
    2 => 'slug',
    3 => 'old_slug',
    4 => 'filename',
    5 => 'caption',
    6 => 'visibility',
    7 => 'max_download',
    8 => 'license',
    9 => 'deleted',
    10 => 'featured',
    11 => 'featured_order',
    12 => 'favorite_order',
    13 => 'favorite',
    14 => 'favorited_on',
    15 => 'featured_on',
    16 => 'uploaded_on',
    17 => 'captured_on',
    18 => 'modified_on',
    19 => 'file_modified_on',
    20 => 'focal_point',
    21 => 'filesize',
    22 => 'width',
    23 => 'height',
    24 => 'aspect_ratio',
    25 => 'duration',
    26 => 'tags',
    27 => 'file_type',
    28 => 'lg_preview',
    29 => 'internal_id',
    30 => 'iptc',
    31 => 'exif',
    32 => 'exif_make',
    33 => 'exif_model',
    34 => 'exif_iso',
    35 => 'exif_camera_serial',
    36 => 'exif_camera_lens',
  ),
  'validation' => 
  array (
    'internal_id' => 
    array (
      'label' => 'Internal id',
      'rules' => 
      array (
        0 => 'required',
      ),
      'field' => 'internal_id',
    ),
    'uploaded_on' => 
    array (
      'rules' => 
      array (
        0 => 'validate_uploaded_on',
      ),
      'field' => 'uploaded_on',
    ),
    'filename' => 
    array (
      'rules' => 
      array (
        0 => 'before',
      ),
      'get_rules' => 
      array (
        0 => 'readify',
      ),
      'field' => 'filename',
    ),
    'title' => 
    array (
      'rules' => 
      array (
        0 => 'title_to_slug',
      ),
      'field' => 'title',
    ),
    'tags' => 
    array (
      'rules' => 
      array (
        0 => 'format_tags',
      ),
      'field' => 'tags',
    ),
    'license' => 
    array (
      'rules' => 
      array (
        0 => 'validate_license',
      ),
      'field' => 'license',
    ),
    'visibility' => 
    array (
      'rules' => 
      array (
        0 => 'validate_visibility',
      ),
      'field' => 'visibility',
    ),
    'max_download' => 
    array (
      'rules' => 
      array (
        0 => 'validate_max_download',
      ),
      'field' => 'max_download',
    ),
    'focal_point' => 
    array (
      'rules' => 
      array (
        0 => 'validate_focal_point',
      ),
      'field' => 'focal_point',
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
    'old_slug' => 
    array (
      'field' => 'old_slug',
      'rules' => 
      array (
      ),
    ),
    'caption' => 
    array (
      'field' => 'caption',
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
    'featured_order' => 
    array (
      'field' => 'featured_order',
      'rules' => 
      array (
      ),
    ),
    'favorite_order' => 
    array (
      'field' => 'favorite_order',
      'rules' => 
      array (
      ),
    ),
    'favorite' => 
    array (
      'field' => 'favorite',
      'rules' => 
      array (
      ),
    ),
    'favorited_on' => 
    array (
      'field' => 'favorited_on',
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
    'captured_on' => 
    array (
      'field' => 'captured_on',
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
    'file_modified_on' => 
    array (
      'field' => 'file_modified_on',
      'rules' => 
      array (
      ),
    ),
    'filesize' => 
    array (
      'field' => 'filesize',
      'rules' => 
      array (
      ),
    ),
    'width' => 
    array (
      'field' => 'width',
      'rules' => 
      array (
      ),
    ),
    'height' => 
    array (
      'field' => 'height',
      'rules' => 
      array (
      ),
    ),
    'aspect_ratio' => 
    array (
      'field' => 'aspect_ratio',
      'rules' => 
      array (
      ),
    ),
    'duration' => 
    array (
      'field' => 'duration',
      'rules' => 
      array (
      ),
    ),
    'file_type' => 
    array (
      'field' => 'file_type',
      'rules' => 
      array (
      ),
    ),
    'lg_preview' => 
    array (
      'field' => 'lg_preview',
      'rules' => 
      array (
      ),
    ),
    'iptc' => 
    array (
      'field' => 'iptc',
      'rules' => 
      array (
      ),
    ),
    'exif' => 
    array (
      'field' => 'exif',
      'rules' => 
      array (
      ),
    ),
    'exif_make' => 
    array (
      'field' => 'exif_make',
      'rules' => 
      array (
      ),
    ),
    'exif_model' => 
    array (
      'field' => 'exif_model',
      'rules' => 
      array (
      ),
    ),
    'exif_iso' => 
    array (
      'field' => 'exif_iso',
      'rules' => 
      array (
      ),
    ),
    'exif_camera_serial' => 
    array (
      'field' => 'exif_camera_serial',
      'rules' => 
      array (
      ),
    ),
    'exif_camera_lens' => 
    array (
      'field' => 'exif_camera_lens',
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
    'album' => 
    array (
      'field' => 'album',
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
    'covers' => 
    array (
      'field' => 'covers',
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
    'text' => 
    array (
      'other_field' => 'featured_image',
      'class' => 'text',
      'join_self_as' => 'featured_image',
      'join_other_as' => 'text',
      'join_table' => '',
      'reciprocal' => false,
      'auto_populate' => NULL,
      'cascade_delete' => true,
    ),
    'category' => 
    array (
      'auto_populate' => true,
      'class' => 'category',
      'other_field' => 'content',
      'join_self_as' => 'content',
      'join_other_as' => 'category',
      'join_table' => '',
      'reciprocal' => false,
      'cascade_delete' => true,
    ),
    'covers' => 
    array (
      'class' => 'album',
      'join_table' => 'koken_join_albums_covers',
      'other_field' => 'cover',
      'join_self_as' => 'cover',
      'join_other_as' => 'album',
      'reciprocal' => false,
      'auto_populate' => NULL,
      'cascade_delete' => true,
    ),
    'album' => 
    array (
      'class' => 'album',
      'other_field' => 'content',
      'join_self_as' => 'content',
      'join_other_as' => 'album',
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
      0 => 'filename',
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