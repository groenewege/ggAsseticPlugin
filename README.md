# ggAsseticPlugin #

*Including a small subset of the Assetic Library in symfony 1.4*

This plugin uses as module for combining files and partials as one javascript or css asset.
This plugin has a task allowing you to combine and minify (YUI) files and partials into one javascript or css file. 

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


## Configuration ##

Update the settings.yml file in every application where you want to use this plugin so that plugin module can be used:

    enabled_modules:        [ggAssetic]

Update the settings.yml file in every application where you want to use the plugin so that the plugin helper function can be used:

    standard_helpers:       [ggAssetic]

Or include the ggAssectic helper in your views:
    
    <?php use_helper('ggAssetic'); ?>

Make sure the *include_stylesheets* and *include_javascripts* commands are present in your layout file.


## Combining assets using the ggAssetic module ##

You use the gg_use_stylesheet function in your view or layout file to include an asset collection.
You clarify in your app.yml configuration which files and partials to include in this bundle.
The ggAssetic module serves the combination of these elements as one css or javascript asset.
If the files and partials haven't changed since the last request, the module sends only a 304 header.

**This method is best used in your development environment**

### including an asset collection ###

Choose a name for your asset collection.
Use the gg_use_stylesheet function to include the collection in a view or layout file.

    <?php gg_use_stylesheet('main'); ?>
    <?php gg_use_javascript('main'); ?>

If the configuration does not use a version number (see later), this function will add a script tag or css link to the ggAssetic module using the following route:
    
    <script type="text/javascript" src="/frontend_dev.php/js/main.js"></script>
    <link rel="stylesheet" type="text/css" media="all" href="/frontend_dev.php/css/main.css" />


### Configuring the asset collection ###

In your app.yml file you specify the files and partials that have to be included in your collection.
The following configuration file specifies that in the development environment the main javascript bundle must contain the backend.js file ande the contents of the general/javascipt partial.
The main css bundle must contain two files and one partial.

    dev:
      gg_assetic:
          javascript:
            main:
              files:
                - frontend.js
              partials:
                - general/javascript
              version: 0
          css:
            main:
              files:
                - reset.css
                - frontend.css
              partials:
                - general/css
              version: 0


**The plugin will only send a request to the ggAssetic module if the version number of the colleciton is not set or if the version number is zero.**

### Files and partials combined ###

The files must be situated in the sf_web_dir/js and sf_web_dir/css directories.

The partials can use the symfony routing to create urls, very handy for ajax stuff.

**An example :**
    
    <file: web/js/frontend.js>
    console.info("frontend js");
    
    <file: apps/backend/modules/general/templates/_javascript.php>
    console.info("partial with link : <?php echo $routing->generate('contact'); ?>")

When visiting *frontend_dev.php/js/main.js* (dev environment) the output is :
    
    console.info("frontend js");
    console.info("partial with link : /frontend_dev.php/contact")

When visiting */js/main.js* (production environment) the output is :
    
    console.info("frontend js");
    console.info("partial with link : /contact")

**Do not forget to clear the symfony cache and the browser cache when running into problems**



