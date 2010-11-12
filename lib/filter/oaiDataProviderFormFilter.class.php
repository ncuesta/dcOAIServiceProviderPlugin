<?php

/**
 * oaiDataProvider filter form.
 *
 * @package    symfony
 * @subpackage filter
 * @author     José Nahuel Cuesta Luengo <ncuesta@cespi.unlp.edu.ar>
 */
class oaiDataProviderFormFilter extends BaseoaiDataProviderFormFilter
{
  public function configure()
  {
    $this->getWidget('name')->setOption('with_empty', false);
    $this->getWidget('last_update_date')->setOption('empty_label', 'Never');
    
    $this->useFields(array('name', 'url', 'is_enabled', 'last_update_date'));
  }
}
