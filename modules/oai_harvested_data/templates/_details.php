<?php use_helper('Date', 'Text') ?>

<?php echo __('Harvested:') ?> <?php echo format_date($oaiHarvestedData->getDatestamp('U'), 'P') ?>
 &mdash;
<?php echo __('Identifier:') ?> <?php echo $oaiHarvestedData->getIdentifier() ?>

 <?php echo truncate_text($oaiHarvestedData->getDescription(), 10, '...') ?>