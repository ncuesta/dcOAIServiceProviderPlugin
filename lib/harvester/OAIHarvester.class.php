<?php

/**
 * OAIHarvester
 *
 * Harvester for OAI-PMH Data Providers.
 *
 * @author José Nahuel Cuesta Luengo <ncuesta@cespi.unlp.edu.ar>
 */
class OAIHarvester
{
  protected
    $_data_provider,
    $_updates = 0;

  public function harvest(oaiDataProvider $data_provider)
  {
    $this->_data_provider = $data_provider;

    $xml = $this->listRecords(
      $this->_data_provider->getMetadataPrefix(),
      $this->_data_provider->getLastUpdateDate('Y-m-d')
    );

    if (null !== $xml)
    {
      $parser = $this->getParser($xml);

      if ($parser->couldParse())
      {
        $result = $parser->getResult();
        
        $result->setDataProviderId($data_provider->getId());

        $this->_updates = oaiHarvestedDataPeer::updateFromOAIResult($result);

        if ($this->_updates > 0)
        {
          $data_provider
            ->setLastUpdateDate(time())
            ->save()
          ;
        }

        return $result;
      }
    }

    throw new LogicException('Unable to harvest data provider.');
  }

  /**
   * Get the number of updates performed.
   *
   * @return int
   */
  public function getNbUpdates()
  {
    return $this->_updates;
  }

  /**
   * Set this object's parser class to be the one configured in app.yml file.
   * Parser classes must be subclasses of BaseOAIParser so if the provided
   * class name does not meet such requirement a LogicException will be
   * thrown.
   *
   * @see    BaseOAIParser
   *
   * @throws LogicException
   *
   * @return string The name of parser class that was set.
   */
  public function getParserClass()
  {
    $parser_class = sfConfig::get('app_oai_service_provider_plugin_parser_class', 'DublinCoreOAIParser');
    
    $reflection_class = new ReflectionClass($parser_class);

    if (!$reflection_class->isSubclassOf('BaseOAIParser'))
    {
      throw new LogicException('Parser classes must be subclasses of BaseOAIParser.');
    }

    return $parser_class;
  }

  /**
   * Get the parser for $xml.
   *
   * @param  string $xml The XML document to pass to the parser.
   *
   * @return BaseOAIParser
   */
  public function getParser($xml)
  {
    $class = $this->getParserClass();

    return new $class($xml);
  }

  /**
   * This verb is used to retrieve an individual metadata record from a repository.
   * Required arguments specify the identifier of the item from which the record
   * is requested and the format of the metadata that should be included in the
   * record. Depending on the level at which a repository tracks deletions, a
   * header with a "deleted" value for the status attribute may be returned, in
   * case the metadata format specified by the metadataPrefix is no longer available
   * from the repository or from the specified item.
   *
   * Arguments
   * * identifier: a required argument that specifies the unique identifier of
   * the item in the repository from which the record must be disseminated.
   * * metadataPrefix: a required argument that specifies the metadataPrefix of
   * the format that should be included in the metadata part of the returned
   * record. A record should only be returned if the format specified by the
   * metadataPrefix can be disseminated from the item identified by the value
   * of the identifier argument. The metadata formats supported by a repository
   * and for a particular record can be retrieved using the ListMetadataFormats
   * request.
   *
   * Error and Exception Conditions
   * * badArgument - The request includes illegal arguments or is missing
   * required arguments.
   * * cannotDisseminateFormat - The value of the metadataPrefix argument is not
   * supported by the item identified by the value of the identifier argument.
   * * idDoesNotExist - The value of the identifier argument is unknown or
   * illegal in this repository.
   *
   * @return mixed
   */
  public function getRecord($id, $metadata_prefix)
  {
    return $this->request('GetRecord', array('identifier' => $id, 'metadataPrefix' => $metadata_prefix));
  }

  /**
   * This verb is used to retrieve information about a repository. Some of the
   * information returned is required as part of the OAI-PMH. Repositories may
   * also employ the Identify verb to return additional descriptive information.
   *
   * Arguments
   * * None
   *
   * Error and Exception Conditions
   * * badArgument - The request includes illegal arguments.
   * 
   * @return mixed
   */
  public function identify()
  {
    return $this->request('Identify');
  }

  /**
   * This verb is an abbreviated form of ListRecords, retrieving only headers
   * rather than records. Optional arguments permit selective harvesting of
   * headers based on set membership and/or datestamp. Depending on the
   * repository's support for deletions, a returned header may have a status
   * attribute of "deleted" if a record matching the arguments specified in the
   * request has been deleted.
   *
   * Arguments
   * * from: an optional argument with a UTCdatetime value, which specifies a
   * lower bound for datestamp-based selective harvesting.
   * * until: an optional argument with a UTCdatetime value, which specifies a
   * upper bound for datestamp-based selective harvesting.
   * * metadataPrefix: a required argument, which specifies that headers should
   * be returned only if the metadata format matching the supplied
   * metadataPrefix is available or, depending on the repository's support for
   * deletions, has been deleted. The metadata formats supported by a repository
   * and for a particular item can be retrieved using the ListMetadataFormats
   * request.
   * * set: an optional argument with a setSpec value , which specifies set
   * criteria for selective harvesting.
   * * resumptionToken: an exclusive argument with a value that is the flow
   * control token returned by a previous ListIdentifiers request that issued an
   * incomplete list.
   *
   * Error and Exception Conditions
   * * badArgument - The request includes illegal arguments or is missing
   * required arguments.
   * * badResumptionToken - The value of the resumptionToken argument is invalid
   * or expired.
   * * cannotDisseminateFormat - The value of the metadataPrefix argument is not
   * supported by the repository.
   * * noRecordsMatch - The combination of the values of the from, until, and
   * set arguments results in an empty list.
   * * noSetHierarchy - The repository does not support sets.
   * 
   * @return mixed
   */
  public function listIdentifiers($metadata_prefix, $from = null, $until = null, $set = null, $resumption_token = null)
  {
    return $this->request('ListIdentifiers', array(
      'metadataPrefix'  => $metadata_prefix,
      'from'            => $from,
      'until'           => $until,
      'set'             => $set,
      'resumptionToken' => $resumption_token
    ));
  }

  /**
   * This verb is used to retrieve the metadata formats available from a
   * repository. An optional argument restricts the request to the formats
   * available for a specific item.
   *
   * Arguments
   * * identifier: an optional argument that specifies the unique identifier of
   * the item for which available metadata formats are being requested. If this
   * argument is omitted, then the response includes all metadata formats
   * supported by this repository. Note that the fact that a metadata format is
   * supported by a repository does not mean that it can be disseminated from
   * all items in the repository.
   *
   * Error and Exception Conditions
   * * badArgument - The request includes illegal arguments or is missing
   * required arguments.
   * * idDoesNotExist - The value of the identifier argument is unknown or
   * illegal in this repository.
   * * noMetadataFormats - There are no metadata formats available for the
   * specified item.
   * 
   * @return mixed
   */
  public function listMetadataFormats($id = null)
  {
    return $this->request('ListMetadataFormats', array('identifier' => $id));
  }

  /**
   * This verb is used to harvest records from a repository. Optional arguments
   * permit selective harvesting of records based on set membership and/or
   * datestamp. Depending on the repository's support for deletions, a returned
   * header may have a status attribute of "deleted" if a record matching the
   * arguments specified in the request has been deleted. No metadata will be
   * present for records with deleted status.
   *
   * Arguments
   *
   * * from: an optional argument with a UTCdatetime value, which specifies a
   * lower bound for datestamp-based selective harvesting.
   * * until: an optional argument with a UTCdatetime value, which specifies a
   * upper bound for datestamp-based selective harvesting.
   * * set: an optional argument with a setSpec value , which specifies set
   * criteria for selective harvesting.
   * * resumptionToken: an exclusive argument with a value that is the flow
   * control token returned by a previous ListRecords request that issued an
   * incomplete list.
   * * metadataPrefix: a required argument (unless the exclusive argument
   * resumptionToken is used) that specifies the metadataPrefix of the format
   * that should be included in the metadata part of the returned records.
   * Records should be included only for items from which the metadata format
   * matching the metadataPrefix can be disseminated. The metadata formats
   * supported by a repository and for a particular item can be retrieved using
   * the ListMetadataFormats request.
   *
   * Error and Exception Conditions
   * * badArgument - The request includes illegal arguments or is missing
   * required arguments.
   * * badResumptionToken - The value of the resumptionToken argument is invalid
   * or expired.
   * * cannotDisseminateFormat - The value of the metadataPrefix argument is not
   * supported by the repository.
   * * noRecordsMatch - The combination of the values of the from, until, set
   * and metadataPrefix arguments results in an empty list.
   * * noSetHierarchy - The repository does not support sets.
   *
   * @return mixed
   */
  public function listRecords($metadata_prefix, $from = null, $until = null, $set = null, $resumption_token = null)
  {
    return $this->request('ListRecords', array(
      'metadataPrefix'  => $metadata_prefix,
      'from'            => $from,
      'until'           => $until,
      'set'             => $set,
      'resumptionToken' => $resumption_token
    ));
  }

  /**
   * This verb is used to retrieve the set structure of a repository, useful for
   * selective harvesting.
   *
   * Arguments
   * * resumptionToken: an exclusive argument with a value that is the flow
   * control token returned by a previous ListSets request that issued an
   * incomplete list.
   *
   * Error and Exception Conditions
   * * badArgument - The request includes illegal arguments or is missing
   * required arguments.
   * * badResumptionToken - The value of the resumptionToken argument is invalid
   * or expired.
   * * noSetHierarchy - The repository does not support sets.
   * 
   * @return mixed
   */
  public function listSets($resumption_token = null)
  {
    return $this->request('ListSets', array('resumptionToken' => $resumption_token));
  }

  /**
   * Issue a request of verb $verb with the optional parameters specified in the
   * array $parameters. This method will in time call doRequest() method, which
   * will actually perform the request to the server specified by the attribute
   * _data_provider of this Harvester.
   * 
   * @throws LogicException
   *
   * @param  string $verb       OAI verb.
   * @param  array  $parameters Optional parameters for the request.
   * 
   * @return string
   */
  protected function request($verb, $parameters = array())
  {
    $non_empty_parameters = $this->sanitizeParameters($parameters);

    $non_empty_parameters['verb'] = $verb;

    return $this->doRequest($this->_data_provider->getRequestURL($non_empty_parameters));
  }

  /**
   * Perform an actual request to $url and return the response.
   * Throw an exception on failure.
   *
   * @throws LogicException
   *
   * @param  string $url The URL to which the request is to be performed.
   *
   * @return string
   */
  protected function doRequest($url)
  {
    $response = @file_get_contents($url);

    if (false === $response)
    {
      throw new LogicException('Unable to fetch URL: '.$url);
    }

    return $response;
  }

  /**
   * Sanitize $parameters, i.e. ignore every empty parameter from
   * the array, and return the resulting sanitized array.
   *
   * @param  array $parameters The parameters to sanitize.
   *
   * @return array
   */
  protected function sanitizeParameters($parameters = array())
  {
    $sanitized = array();

    foreach ($parameters as $key => $value)
    {
      if (null !== $value && '' != trim($value))
      {
        $sanitized[$key] = $value;
      }
    }

    return $sanitized;
  }

}