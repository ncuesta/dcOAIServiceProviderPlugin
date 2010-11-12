<?php

/**
 * oaiDataProvider form.
 *
 * @package    symfony
 * @subpackage form
 * @author     José Nahuel Cuesta Luengo <ncuesta@cespi.unlp.edu.ar>
 */
class oaiDataProviderForm extends BaseoaiDataProviderForm
{
  public function configure()
  {
    $this->setValidator('url', new sfValidatorUrl(array('required' => true)));

    $this->useFields(array(
      'name',
      'url',
      'metadata_prefix',
      'is_enabled'
    ));
  }

}
