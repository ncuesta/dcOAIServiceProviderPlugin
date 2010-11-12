<?php

/**
 * OAIResult
 *
 * Class that represents an OAI-PMH result (Collection of attributes and OAI
 * records).
 *
 * @author José Nahuel Cuesta Luengo <ncuesta@cespi.unlp.edu.ar>
 */
class OAIResult
{
  protected
    $_data_provider_id,
    $_attributes = array(),
    $_records    = array();

  /**
   * Add an attribute under the key $name with $value as its value to
   * this OAI Result.
   * If any value should already exist under key $name, the values of
   * attribute $name will be an array instead of a single value.
   *
   * @param string $name  The name of the attribute.
   * @param string $value The value of the attribute.
   */
  public function addAttribute($name, $value)
  {
    if (0 == strcasecmp($name, 'responsedate') && '' != trim($value))
    {
      $value = strtotime($value);
    }
    
    if (array_key_exists($name, $this->_attributes))
    {
      if (!is_array($this->_attributes[$name]))
      {
        $this->_attributes[$name] = array($this->_attributes[$name]);
      }

      $this->_attributes[$name][] = $value;
    }
    else
    {
      $this->_attributes[$name] = $value;
    }
  }

  /**
   * Add $oai_record to the collection of records contained by this
   * OAI Result.
   *
   * @param OAIRecord $oai_record The OAI record to append.
   */
  public function addRecord(OAIRecord $oai_record)
  {
    $this->_records[] = $oai_record;
  }

  /**
   * Get the records obtained in this OAI Result.
   *
   * @return array OAIRecord[]
   */
  public function getRecords()
  {
    return $this->_records;
  }

  /**
   * Get the attributes of this OAI Result.
   *
   * @return array
   */
  public function getAttributes()
  {
    return $this->_attributes;
  }

  /**
   * Get the id of the oaiDataProvider related to this result.
   *
   * @return int
   */
  public function getDataProviderId()
  {
    return $this->_data_provider_id;
  }

  /**
   * Set the id of the oaiDataProvider related to this result.
   *
   * @param $oai_data_provider_id int The id of the data provider.
   */
  public function setDataProviderId($oai_data_provider_id)
  {
    $this->_data_provider_id = $oai_data_provider_id;
  }

}