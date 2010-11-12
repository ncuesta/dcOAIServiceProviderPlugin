<?php

/**
 * DublinCoreOAIParser
 *
 * OAI Parser for Dublin Core XML responses.
 *
 * @author José Nahuel Cuesta Luengo <ncuesta@cespi.unlp.edu.ar>
 */
class DublinCoreOAIParser extends BaseOAIParser
{
  protected
    $_current_tag,
    $_result,
    $_current_record;

  /**
   * Handler for start of elements for XML Parser.
   *
   * @param resource $parser     The XML parser.
   * @param string   $name       The name of the tag.
   * @param array    $attributes The attributes of the tag.
   */
  public function handleElementStart($parser, $name, $attributes)
  {
    if (0 == strcasecmp($name, 'record'))
    {
      $this->_current_record = new OAIRecord();
    }

    $this->_current_tag = $name;
  }

  /**
   * Handler for end of elements for XML Parser.
   *
   * @param resource $parser     The XML parser.
   * @param string   $name       The name of the tag.
   */
  public function handleElementEnd($parser, $name)
  {
    if (0 == strcasecmp($name, 'record') && null !== $this->_current_record)
    {
      $this->_result->addRecord($this->_current_record);

      $this->_current_record = null;
    }

    $this->_current_tag = null;
  }

  /**
   * Handler for CDATA elements for XML Parser.
   *
   * @param resource $parser     The XML parser.
   * @param string   $data       The CDATA.
   */
  public function handleCDATA($parser, $data)
  {
    if (0 != strcasecmp($this->_current_tag, 'record') && '' != trim($data))
    {
      if (null !== $this->_current_record)
      {
        $this->_current_record->addAttribute($this->_current_tag, $data);
      }
      else
      {
        $this->_result->addAttribute($this->_current_tag, $data);
      }
    }
  }

  protected function doParse($xml)
  {
    $this->_result = new OAIResult();

    $xml_parser = $this->getXMLParser();
    
    xml_set_element_handler($xml_parser, array($this, 'handleElementStart'), array($this, 'handleElementEnd'));
    xml_set_character_data_handler($xml_parser, array($this, 'handleCDATA'));

    if (!xml_parse($xml_parser, $xml))
    {
      $error = sprintf('Unable to parse Dublin Core OAI XML document. XML parser error: %s at line %d.', xml_error_string(xml_get_error_code($xml_parser)), xml_get_current_line_number($xml_parser));

      throw new LogicException($error);
    }
    
    xml_parser_free($xml_parser);

    return $this->_result;
  }
  
}