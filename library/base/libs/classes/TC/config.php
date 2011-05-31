<?
/*
 * Thumbnail Collection Configuration File
 */

if(!defined('TC_CONFIG')){
    $tc_config = array();
    $tc_config['tables'] =
        array(
            "sections"   => "tc_sections",
            "sizes"      => "tc_sizes",
            "images"     => "tc_images",
            "thumbnails" => "tc_thumbnails"
            );

    $tc_config['PHPThumb_PATH'] = EXTENRAL_LIB_PATH."PHPTumb".DS."";

    define('TC_CONFIG', serialize($tc_config));
    
    }