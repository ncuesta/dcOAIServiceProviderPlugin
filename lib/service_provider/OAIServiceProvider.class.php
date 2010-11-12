<?php

/**
 * OAIServiceProvider
 *
 * @author José Nahuel Cuesta Luengo <ncuesta@cespi.unlp.edu.ar>
 */
class OAIServiceProvider
{
  protected
    $_data_providers = array(),
    $_errors         = array(),
    $_nb_updates     = 0,
    $_last_status;

  /**
   * Constructor.
   *
   * @param array  $data_providers A collection of oaiDataProvider objects.
   */
  public function __construct(array $data_providers)
  {
    $this->setDataProviders($data_providers);
  }

  /**
   * Set the data providers for this Service Provider.
   * This method will in addition check $data_providers
   * to see if any of them is available, and update the
   * status (_last_status) of this object accordingly.
   *
   * @param array $data_providers A collection of data providers.
   */
  public function setDataProviders(array $data_providers)
  {
    $valid_data_providers = array();

    foreach ($data_providers as $data_provider)
    {
      if ($data_provider->isReachable())
      {
        $valid_data_providers[] = $data_provider;
      }
      else
      {
        $this->addError('Unreachable OAI data provider.', $data_provider->getUrl());

        $data_provider
          ->setLastError('Unreachable OAI data provider.')
          ->save()
        ;
      }
    }

    $this->_data_providers = $valid_data_providers;

    $this->updateStatus(count($this->_data_providers) > 0);
  }

  /**
   * Append an error message to the collection of errors found.
   *
   * @param string $error         The error message.
   * @param string $data_provider The data provider that caused the error.
   */
  public function addError($error, $data_provider)
  {
    if (!array_key_exists($data_provider, $this->_errors))
    {
      $this->_errors[$data_provider] = array($error);
    }
    else
    {
      $this->_errors[$data_provider][] = $error;
    }
  }

  /**
   * Get the messages for the errors found as an array.
   * The keys are the data providers
   * 
   * @return array
   */
  public function getErrors()
  {
    return $this->_errors;
  }

  /**
   * Update this Service Provider's status to be $status.
   * 
   * @param mixed $status The new status.
   */
  protected function updateStatus($status)
  {
    $this->_last_status = $status;
  }

  /**
   * Return a boolean value indicating whether this Service Provider
   * can harvest its data providers.
   *
   * @return bool
   */
  public function canHarvest()
  {
    return (count($this->_data_providers) > 0 && $this->_last_status);
  }

  /**
   * Harvest the data providers using an OAI harvester and return the harvested
   * data. This method updates the status of this object.
   *
   * @return array
   */
  public function harvest()
  {
    $harvester = $this->getHarvester();
    
    $data = array();

    foreach ($this->_data_providers as $data_provider)
    {
      try
      {
        $data[$data_provider->getUrl()] = $harvester->harvest($data_provider);
        
        $this->_nb_updates += $harvester->getNbUpdates();

        $data_provider
          ->setLastError(null)
          ->save()
        ;
      }
      catch (Exception $e)
      {
        unset($data[$data_provider->getUrl()]);

        $this->addError($e->getMessage(), $data_provider->getUrl());

        $data_provider
          ->setLastError($e->getMessage())
          ->save()
        ;
      }
    }

    $this->updateStatus(count($data) > 0);

    return $data;
  }

  /**
   * Get a harvester object.
   * 
   * @return OAIHarvester
   */
  public function getHarvester()
  {
    return new OAIHarvester();
  }

  /**
   * Get the number of updates performed by this OAI Service Provider.
   *
   * @return int
   */
  public function getNbUpdates()
  {
    return $this->_nb_updates;
  }

}
