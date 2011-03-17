# ggAsseticPlugin #

*Using parts of the Assetic Library in symfony 1.4*

## Installation ##

### Clone this plugin  ###

Use this to install as a plugin in a symfony app using git clone:

	$ cd plugins && git clone git://github.com/groenewege/ggAsseticPlugin.git

### Update the submodules ###

The Assetic library is integrated in this plugin as a submodule. 
You must run the following commands from inside the ggAsseticPlugin directory:

	$ git submodule init
	$ git submodule update

### enable the plugin in the config/ProjectConfiguration class ###

    public function setup()
    {
      $this->enablePlugins('ggAsseticPlugin');
    }


## Usage ##

### Prepare ###

Update the settings.yml file in every application where you want to use this plugin so that the helper functions and module can be used:
 
    standard_helpers:       [ggAssetic]
    enabled_modules:        [ggAssetic]

Make sure the *include_stylesheets* and *include_javascripts* commands are present in your layout file.

### Configuration ###

- app.yml
- gg_use_stylesheet