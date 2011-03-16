<?php

/**
 * ggAsseticHelper handles the Assetic js and css.
 *
 * @package    ggAsseticPlugin
 * @subpackage helper
 * @author     Gunther Groenewege <gunther@groenewege.com>
 * @version    1.0.0
 */
use_helper('Asset');

/**
 * Prints <link> tag for the jquery library.
 */
function use_jquery()
{
  use_javascript(sfConfig::get('app_gg_assetic_jquery'));
}

/**
 * Prints <link> tag for a javascript file.
 */
function gg_use_javascript($js)
{
  $config = sfConfig::get('app_gg_assetic_javascript');
  
  if (isset($config[$js]['version']) && $config[$js]['version'] > 0) {
  	use_javascript($js.'.'.$config[$js]['version'].'.min.js');
  } else {
    use_javascript(url_for('@asset_js?name='.$js));
  }
}

/**
 * Prints <link> tag for a css file.
 */
function gg_use_stylesheet($css)
{
  $config = sfConfig::get('app_gg_assetic_css');
  
  if (isset($config[$css]['version']) && $config[$css]['version'] > 0) {
  	use_stylesheet($css.'.'.$config[$css]['version'].'.min.css');
  } else {
    use_stylesheet(url_for('@asset_css?name='.$css), '', array('media' => 'all'));
  }
}