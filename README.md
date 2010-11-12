# dcOAIServiceProviderPlugin

## Brief description

[Symfony](http://www.symfony-project.com) plugin that provides an implementation of an OAI-PMH Service Provider.

This symfony plugin is a simple implementation of an OAI-PMH Service Provider. It requires symfony 1.4+.

## Installation

1. Just install the plugin (you can `git clone` it or just download it).
2. Build your model, forms and db:

        $ php symfony propel:build --all

3. Enable the plugin (if needed):

        // In config/ProjectConfiguration.class.php:
        // ...
        $this->enablePlugins('dcOAIServiceProviderPlugin');

4. Enable the administration modules provided (give them a try!):

        # In apps/<application>/config/settings.yml
        all:
          .settings:
            enabled_modules: [default, myFancyModule, anotherCoolModule, oai_data_provider, oai_harvested_data]

5. Clear your cache:

        $ php symfony cache:clear

Please note that this plugin uses i18n.
