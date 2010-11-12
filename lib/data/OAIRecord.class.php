<?php

/**
 * OAIRecord
 *
 * Class that represents an OAI-PMH record.
 *
 * @author José Nahuel Cuesta Luengo <ncuesta@cespi.unlp.edu.ar>
 */
class OAIRecord
{
  protected
    $_identifier,
    $_datestamp,
    $_set_spec,
    $_title,
    $_creator,
    $_subject,
    $_description,
    $_date,
    $_publisher,
    $_type,
    $_dc_identifier,
    $_relation;

  /**
   * Load the attributes stored in $data to this object.
   *
   * @param  array $data Attributes to load.
   *
   * @return OAIRecord This object, for fluent interface.
   */
  public function fromArray($data)
  {
    foreach ($this->getAttributesMapping() as $name => $attribute)
    {
      if (isset($data[$name]))
      {
        $this->$attribute = $data[$name];
      }
    }

    return $this;
  }

  /**
   * Add an attribute under the key $name with $value as its value to
   * this OAI Record.
   * If any value should already exist under key $name, the values of
   * attribute $name will be an array instead of a single value.
   *
   * @param string $name  The name of the attribute.
   * @param string $value The value of the attribute.
   */
  public function addAttribute($name, $value)
  {
    $attribute = $this->getAttributeFromName($name);

    if (false !== $attribute)
    {
      if (null !== $this->$attribute)
      {
        if (!is_array($this->$attribute))
        {
          $this->$attribute = array($this->$attribute);
        }

        array_push($this->$attribute, $value);
      }
      else
      {
        $this->$attribute = $value;
      }
    }
  }

  /**
   * Get an array indicating the mappings of attribute names from XML to
   * internal object properties.
   *
   * @return array
   */
  public function getAttributesMapping()
  {
    return array(
      'IDENTIFIER'     => '_identifier',
      'DATESTAMP'      => '_datestamp',
      'SETSPEC'        => '_set_spec',
      'DC:TITLE'       => '_title',
      'DC:CREATOR'     => '_creator',
      'DC:SUBJECT'     => '_subject',
      'DC:DESCRIPTION' => '_description',
      'DC:DATE'        => '_date',
      'DC:PUBLISHER'   => '_publisher',
      'DC:TYPE'        => '_type',
      'DC:IDENTIFIER'  => '_dc_identifier',
      'DC:RELATION'    => '_relation'
    );
  }

  /**
   * Get the internal object property name associated to tag $name of the
   * XML document.
   *
   * @param  string $name The name of the XML document tag.
   *
   * @return string
   */
  public function getAttributeFromName($name)
  {
    if (array_key_exists($name, $this->getAttributesMapping()))
    {
      $mapping = $this->getAttributesMapping();

      return $mapping[$name];
    }

    return false;
  }

  public function getIdentifier()
  {
    return $this->flatten($this->_identifier);
  }

  public function getDatestamp()
  {
    if (null !== $this->_datestamp)
    {
      return strtotime($this->_datestamp);
    }
  }

  public function getSetSpec()
  {
    return $this->flatten($this->_set_spec);
  }

  public function getTitle()
  {
    return $this->flatten($this->_title);
  }

  public function getCreator()
  {
    return $this->flatten($this->_creator);
  }

  public function getSubject()
  {
    return $this->flatten($this->_subject);
  }

  public function getDescription()
  {
    return $this->flatten($this->_description);
  }

  public function getDate()
  {
    if (null !== $this->_date)
    {
      return strtotime($this->_date);
    }
  }

  public function getPublisher()
  {
    return $this->flatten($this->_publisher);
  }

  public function getType()
  {
    return $this->flatten($this->_type);
  }

  public function getDcIdentifier()
  {
    return $this->flatten($this->_dc_identifier);
  }

  public function getRelation()
  {
    return $this->flatten($this->_relation);
  }

  protected function flatten($values, $glue = ' ')
  {
    if (is_array($values))
    {
      return implode($glue, $values);
    }

    return $values;
  }

}