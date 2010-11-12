<?php

require_once dirname(__FILE__).'/../lib/oai_data_providerGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/oai_data_providerGeneratorHelper.class.php';

/**
 * base oai_data_provider actions.
 *
 * @package    symfony
 * @subpackage plugin
 * @author     José Nahuel Cuesta Luengo <ncuesta@cespi.unlp.edu.ar>
 * @version    SVN: $Id$
 */
class BaseOaiDataProviderActions extends autoOai_data_providerActions
{
  public function executeHarvest(sfWebRequest $request)
  {
    if ($request->hasParameter('ids'))
    {
      $data_providers = oaiDataProviderPeer::retrieveByPKs($request->getParameter('ids'));
    }
    else if ($request->hasParameter('id'))
    {
      $data_providers = array(oaiDataProviderPeer::retrieveByPK($request->getParameter('id')));
    }
    else
    {
      $data_providers = oaiDataProviderPeer::retrieveEnabled();
    }
    
    $service_provider = new OAIServiceProvider($data_providers);

    if ($service_provider->canHarvest() && $service_provider->harvest())
    {
      $updates = $service_provider->getNbUpdates();
      if ($updates > 0)
      {
        $notice = 'Successfully harvested '.$updates.' records.';
      }
      else
      {
        $notice = 'No new records to harvest. Everything is up to date.';
      }

      $this->getUser()->setFlash('notice', $notice);

      $errors = $service_provider->getErrors();
      if (count($errors) > 0)
      {
        $error = 'Some errors have been found while harvesting: ';

        foreach ($errors as $url => $specific_errors)
        {
          $error .= sprintf('%s (%s). ', implode(', ', $specific_errors), $url);
        }

        $this->getUser()->setFlash('error', $error);
      }
    }
    else
    {
      $errors = $service_provider->getErrors();
      if (count($errors) > 0)
      {
        $error = 'Unable to complete harvesting. Too many errors have been found while trying to do so: ';

        foreach ($errors as $url => $specific_errors)
        {
          $error .= sprintf('%s (%s). ', implode(', ', $specific_errors), $url);
        }

        $this->getUser()->setFlash('error', $error);
      }
    }

    $this->redirect('@oai_data_provider');
  }

  public function executeBatchHarvest(sfWebRequest $request)
  {
    $this->forward('oai_data_provider', 'harvest');
  }

  public function executeBatchEnable(sfWebRequest $request)
  {
    $ids = $request->getParameter('ids');

    $count = 0;
    foreach (oaiDataProviderPeer::retrieveByPks($ids) as $oai_data_provider)
    {
      $oai_data_provider->enable();
      if ($oai_data_provider->getIsEnabled())
      {
        $count++;
      }
    }

    if ($count >= count($ids))
    {
      $this->getUser()->setFlash('notice', 'The selected items have been enabled successfully.');
    }
    else
    {
      $this->getUser()->setFlash('error', 'A problem occurs when enabling the selected items.');
    }

    $this->redirect('@oai_data_provider');
  }

  public function executeBatchDisable(sfWebRequest $request)
  {
    $ids = $request->getParameter('ids');

    $count = 0;
    foreach (oaiDataProviderPeer::retrieveByPks($ids) as $oai_data_provider)
    {
      $oai_data_provider->disable();
      if (!$oai_data_provider->getIsEnabled())
      {
        $count++;
      }
    }

    if ($count >= count($ids))
    {
      $this->getUser()->setFlash('notice', 'The selected items have been disabled successfully.');
    }
    else
    {
      $this->getUser()->setFlash('error', 'A problem occurs when disabling the selected items.');
    }

    $this->redirect('@oai_data_provider');
  }

}