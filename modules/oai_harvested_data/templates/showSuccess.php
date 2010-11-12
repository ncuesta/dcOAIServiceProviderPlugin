<?php use_helper('Date') ?>
<?php include_partial('oai_harvested_data/assets') ?>

<div id="sf_admin_container">
  <h1><?php echo __('OAI-PMH Harvested record') ?></h1>

  <div id="sf_admin_content">
    <fieldset>
      <h2><?php echo __('Record information') ?></h2>

      <div class="sf_admin_form_row">
        <label for="oai_data_provider_id"><?php echo __('OAI-PMH Data provider') ?></label>
        <div class="content" id="oai_data_provider_id">
          <?php echo $oai_harvested_data->getOaiDataProvider() ?>
        </div>
      </div>

      <div class="sf_admin_form_row">
        <label for="oai_title"><?php echo __('Title') ?></label>
        <div class="content" id="oai_title">
          <?php echo $oai_harvested_data->getTitle() ?>
        </div>
      </div>

      <div class="sf_admin_form_row">
        <label for="oai_identifier"><?php echo __('Identifier') ?></label>
        <div class="content" id="oai_identifier">
          <?php echo $oai_harvested_data->getIdentifier() ?>
        </div>
      </div>

      <div class="sf_admin_form_row">
        <label for="oai_datestamp"><?php echo __('Publication date') ?></label>
        <div class="content" id="oai_datestamp">
          <?php echo format_date($oai_harvested_data->getDatestamp('U'), 'P') ?>
        </div>
      </div>

      <div class="sf_admin_form_row">
        <label for="oai_created_at"><?php echo __('Harvesting date') ?></label>
        <div class="content" id="oai_created_at">
          <?php echo format_date($oai_harvested_data->getCreatedAt('U'), 'f') ?>
        </div>
      </div>

      <div class="sf_admin_form_row">
        <label for="oai_updated_at"><?php echo __('Last update') ?></label>
        <div class="content" id="oai_updated_at">
          <?php echo format_date($oai_harvested_data->getUpdatedAt('U'), 'f') ?>
        </div>
      </div>

      <div class="sf_admin_form_row">
        <label for="oai_description"><?php echo __('Description') ?></label>
        <div class="content" id="oai_description">
          <?php if ('' != trim($oai_harvested_data->getDescription())): ?>
            <?php echo $oai_harvested_data->getDescription() ?>
          <?php else: ?>
          &nbsp;
          <?php endif; ?>
        </div>
      </div>

      <div class="sf_admin_form_row">
        <label for="oai_relation"><?php echo __('Relation') ?></label>
        <div class="content" id="oai_relation">
          <?php if ('' != trim($oai_harvested_data->getRelation())): ?>
          <a href="<?php echo $oai_harvested_data->getRelation() ?>" target="_blank" title="<?php echo __('Open this link in a new window') ?>"><?php echo $oai_harvested_data->getRelation() ?></a>
          <?php else: ?>
          &nbsp;
          <?php endif; ?>
        </div>
      </div>

      <div class="sf_admin_form_row">
        <label for="oai_set_spec"><?php echo __('Set(s)') ?></label>
        <div class="content" id="oai_set_spec">
          <?php if ('' != trim($oai_harvested_data->getSetSpec())): ?>
            <?php echo $oai_harvested_data->getSetSpec() ?>
          <?php else: ?>
          &nbsp;
          <?php endif; ?>
        </div>
      </div>

      <div class="sf_admin_form_row">
        <label for="oai_creator"><?php echo __('Creator') ?></label>
        <div class="content" id="oai_creator">
          <?php if ('' != trim($oai_harvested_data->getCreator())): ?>
            <?php echo $oai_harvested_data->getCreator() ?>
          <?php else: ?>
          &nbsp;
          <?php endif; ?>
        </div>
      </div>

      <div class="sf_admin_form_row">
        <label for="oai_subject"><?php echo __('Subject') ?></label>
        <div class="content" id="oai_subject">
          <?php if ('' != trim($oai_harvested_data->getSubject())): ?>
            <?php echo $oai_harvested_data->getSubject() ?>
          <?php else: ?>
          &nbsp;
          <?php endif; ?>
        </div>
      </div>

      <div class="sf_admin_form_row">
        <label for="oai_date"><?php echo __('Date') ?></label>
        <div class="content" id="oai_date">
          <?php if (null !== $oai_harvested_data->getDate('U')): ?>
            <?php echo format_date($oai_harvested_data->getDate('U'), 'P') ?>
          <?php else: ?>
          &nbsp;
          <?php endif; ?>
        </div>
      </div>

      <div class="sf_admin_form_row">
        <label for="oai_publisher"><?php echo __('Publisher') ?></label>
        <div class="content" id="oai_publisher">
          <?php if ('' != trim($oai_harvested_data->getPublisher())): ?>
            <?php echo $oai_harvested_data->getPublisher() ?>
          <?php else: ?>
          &nbsp;
          <?php endif; ?>
        </div>
      </div>

      <div class="sf_admin_form_row">
        <label for="oai_type"><?php echo __('Type') ?></label>
        <div class="content" id="oai_type">
          <?php if ('' != trim($oai_harvested_data->getType())): ?>
            <?php echo $oai_harvested_data->getType() ?>
          <?php else: ?>
          &nbsp;
          <?php endif; ?>
        </div>
      </div>

      <div class="sf_admin_form_row">
        <label for="oai_dc_identifier"><?php echo __('Dublin Core Identifier') ?></label>
        <div class="content" id="oai_dc_identifier">
          <?php if ('' != trim($oai_harvested_data->getDcIdentifier())): ?>
            <?php echo $oai_harvested_data->getDcIdentifier() ?>
          <?php else: ?>
          &nbsp;
          <?php endif; ?>
        </div>
      </div>
    </fieldset>

    <ul class="sf_admin_actions">
      <li class="sf_admin_action_list">
        <a href="<?php echo url_for('@oai_harvested_data') ?>"><?php echo __('Go back') ?></a>
      </li>
    </ul>
  </div>
</div>