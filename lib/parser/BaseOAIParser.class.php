<?php

/**
 * BaseOAIParser
 *
 * Base class for OAI parsers.
 *
 * @author José Nahuel Cuesta Luengo <ncuesta@cespi.unlp.edu.ar>
 */
abstract class BaseOAIParser
{
  protected
    $_xml,
    $_attributes = array(),
    $_result;
  
  public function __construct($xml)
  {
    $this->setXML($xml);

    try
    {
      $this->parse();
    }
    catch (Exception $e)
    {
      $this->_result = null;

      throw $e;
    }
  }

  /**
   * Set $xml as this parser's XML to be parsed.
   * $xml will be checked for consistency prior to setting
   * it as the XML to parse. If any error should occur,
   * _xml attribute of this object will be NULL.
   *
   * @param string $xml The XML to set.
   */
  protected function setXML($xml)
  {
    if ($this->isValid($xml))
    {
      $this->_xml = $xml;
    }
    else
    {
      $this->_xml = null;
    }
  }

  /**
   * Get the OAIResult obtained from the XML by this parser.
   *
   * @return OAIResult
   */
  public function getResult()
  {
    return $this->_result;
  }

  /**
   * Parse the inner XML and return the resulting OAIResult.
   * If XML is NULL, throw an Exception.
   * This method is the basic validation, the method that
   * *must* be implemented in subclasses is doParse().
   *
   * @throws LogicException If inner XML is NULL.
   *
   * @see    doParse()
   *
   * @return OAIResult
   */
  public function parse()
  {
    if (null === $this->_xml)
    {
      throw new LogicException('Unable to parse an empty XML document.');
    }

    return $this->doParse($this->_xml);
  }

  /**
   * Check if $xml is a valid XML document, and return a boolean
   * value indicating the result of the validation.
   *
   * @param  string $xml The XML document to check.
   *
   * @return bool
   */
  protected function isValid($xml)
  {
    if (null === $xml || '' == trim($xml))
    {
      return false;
    }

    return $this->isReallyValid($xml);
  }

  /**
   * Perform further validation and checks on $xml
   * than the ones made by isValid() method.
   * This method is a basic implementation that
   * simply returns true, but allows subclasses to override
   * its behavior.
   *
   * @param  string $xml The XML document to check.
   *
   * @return bool
   */
  protected function isReallyValid($xml)
  {
    return true;
  }

  /**
   * Answer whether this Parser could parse the XML document passed
   * as an argument for the constructor.
   *
   * @return bool
   */
  public function couldParse()
  {
    return (null !== $this->_xml && null !== $this->_result);
  }

  /**
   * Get a low-level XML parser.
   *
   * @return resource
   */
  protected function getXMLParser()
  {
    return xml_parser_create();
  }

  /**
   * Method that performs the actual parsing of the XML
   * document $xml.
   * This method should return the OAIResult object
   * obtained from parsing or throw an Exception on failure.
   *
   * @param  string $xml The XML document to parse.
   *
   * @return OAIResult The generated OAIResult object.
   */
  abstract protected function doParse($xml);

}