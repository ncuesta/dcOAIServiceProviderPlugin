<?php


/**
 * Skeleton subclass for performing query and update operations on the 'oai_harvested_data' table.
 *
 * 
 *
 * This class was autogenerated by Propel 1.4.2 on:
 *
 * Thu Nov 11 14:03:55 2010
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    plugins.dcOAIServiceProviderPlugin.lib.model
 */
class oaiHarvestedDataPeer extends BaseoaiHarvestedDataPeer
{
  static public function updateFromOAIResult(OAIResult $result, PropelPDO $con = null)
  {
    $updated = 0;

    foreach ($result->getRecords() as $record)
    {
      $updated += self::updateFromOAIRecord($record, $result->getDataProviderId(), $con);
    }

    return $updated;
  }

  static public function updateFromOAIRecord(OAIRecord $record, $data_provider_id, PropelPDO $con = null)
  {
    $updated = 0;

    $oai_harvested_data = self::retrieveByIdentifier($record->getIdentifier(), $con);

    if (null === $oai_harvested_data)
    {
      // The record is new
      $oai_harvested_data = new oaiHarvestedData();
      $oai_harvested_data->setOaiDataProviderId($data_provider_id);
    }
    else if ($oai_harvested_data->getUpdatedAt('U') > $record->getDatestamp())
    {
      // The record hasn't been updated
      return 0;
    }

    $oai_harvested_data->fromArray(array(
      'identifier'           => $record->getIdentifier(),
      'datestamp'            => $record->getDatestamp(),
      'set_spec'             => $record->getSetSpec(),
      'title'                => $record->getTitle(),
      'creator'              => $record->getCreator(),
      'subject'              => $record->getSubject(),
      'description'          => $record->getDescription(),
      'date'                 => $record->getDate(),
      'publisher'            => $record->getPublisher(),
      'type'                 => $record->getType(),
      'dc_identifier'        => $record->getDcIdentifier(),
      'relation'             => $record->getRelation()
    ), BasePeer::TYPE_FIELDNAME);

    $updated += $oai_harvested_data->save($con);

    return $updated;
  }

  /**
   * Retrieve an oaiHarvestedData object by its identifier attribute.
   *
   * @param string    $identifier The desired identifier.
   * @param PropelPDO $con        Optional PDO object.
   *
   * @return oaiHarvestedData
   */
  static public function retrieveByIdentifier($identifier, PropelPDO $con = null)
  {
    $criteria = new Criteria();

    $criteria->add(self::IDENTIFIER, $identifier);

    return self::doSelectOne($criteria, $con);
  }

} // oaiHarvestedDataPeer
