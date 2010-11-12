<?php

/**
 * oaiHarvestedData filter form.
 *
 * @package    symfony
 * @subpackage filter
 * @author     José Nahuel Cuesta Luengo <ncuesta@cespi.unlp.edu.ar>
 */
class oaiHarvestedDataFormFilter extends BaseoaiHarvestedDataFormFilter
{
  public function configure()
  {
    $this->getWidget('datestamp')->setOption('with_empty', false);
    
    $this->useFields(array(
      'identifier',
      'oai_data_provider_id',
      'datestamp'
    ));
  }

}
