<?php

/**
 * Data file for America/Panama timezone, compiled from the olson data.
 *
 * Auto-generated by the phing olson task on 02/14/2009 18:36:26
 *
 * @package    agavi
 * @subpackage translation
 *
 * @copyright  Authors
 * @copyright  The Agavi Project
 *
 * @since      0.11.0
 *
 * @version    $Id$
 */

return array (
  'types' => 
  array (
    0 => 
    array (
      'rawOffset' => -19176,
      'dstOffset' => 0,
      'name' => 'CMT',
    ),
    1 => 
    array (
      'rawOffset' => -18000,
      'dstOffset' => 0,
      'name' => 'EST',
    ),
  ),
  'rules' => 
  array (
    0 => 
    array (
      'time' => -2524502512,
      'type' => 0,
    ),
    1 => 
    array (
      'time' => -1946918424,
      'type' => 1,
    ),
  ),
  'finalRule' => 
  array (
    'type' => 'static',
    'name' => 'EST',
    'offset' => -18000,
    'startYear' => 1909,
  ),
  'name' => 'America/Panama',
);

?>