<?php use_helper('Date') ?>

<?php echo format_date($oaiDataProvider->getLastUpdateDate('U'), 'P') ?>

<?php if ($oaiDataProvider->hasLastError()): ?>
<img src="<?php echo image_path('/dcOAIServiceProviderPlugin/images/exclamation.png') ?>" alt="<?php echo __('Last error') ?>" onmouseover="oai_toggle('last_error_<?php echo $oaiDataProvider->getId() ?>', 'inline-block'); return false;" onmouseout="oai_toggle('last_error_<?php echo $oaiDataProvider->getId() ?>', 'inline-block'); return false;" />

<span id="last_error_<?php echo $oaiDataProvider->getId() ?>" class="oai_last_error"><?php echo $oaiDataProvider->getLastError() ?></span>

<script type="text/javascript">
//<![CDATA[
oai_toggle('last_error_<?php echo $oaiDataProvider->getId() ?>');
//]]>
</script>
<?php endif; ?>
