<?php

require_once dirname(__FILE__).'/../lib/oai_harvested_dataGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/oai_harvested_dataGeneratorHelper.class.php';

/**
 * base oai_harvested_data actions.
 *
 * @package    OAI-PMH
 * @subpackage oai_harvested_data
 * @author     José Nahuel Cuesta Luengo <ncuesta@cespi.unlp.edu.ar>
 * @version    SVN: $Id$
 */
class BaseOaiHarvestedDataActions extends autoOai_harvested_dataActions
{
  public function executeListByDataProvider(sfWebRequest $request)
  {
    $oai_data_provider = oaiDataProviderPeer::retrieveByPK($request->getParameter('id'));

    if (null === $oai_data_provider)
    {
      $this->getUser()->setFlash('error', 'You must select an OAI-PMH data provider to list its harvested records.');
      $this->redirect('@oai_data_provider');
    }

    $this->setFilters(array('oai_data_provider_id' => $oai_data_provider->getId()));
    $this->redirect('@oai_harvested_data');
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->oai_harvested_data = $this->getRoute()->getObject();
  }

  public function executeGoToDataProviders(sfWebRequest $request)
  {
    $this->redirect('@oai_data_provider');
  }
  
}