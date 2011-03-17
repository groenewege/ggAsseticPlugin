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

If the configuration does not use a version number (see later), this function will add a script tag or css link to the ggAssetic module. Example:
    
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

When visiting */js/main.js* (prod environment) the output is :
    
    console.info("frontend js");
    console.info("partial with link : /contact")

**Do not forget to clear the symfony cache and the browser cache when running into problems**


## Combining and compressing assets with the ggAssetic task ##

You use the gg_use_stylesheet function in your view or layout file to include an asset collection.
You clarify in your app.yml configuration which files and partials to include in this bundle and you give a version number to the file.
The ggAssetic task let's you create a new versioned file that combines and minifies the assets in a new file. This allows you to use a far future Expires header for your css and javascript assets.

**This method is best used in your production environment**

### including an asset collection ###

Choose a name for your asset collection.
Use the gg_use_stylesheet function to include the collection in a view or layout file.

    <?php gg_use_stylesheet('main'); ?>
    <?php gg_use_javascript('main'); ?>

If the configuration uses a version number (see later), this function will add a script tag or css link to the generated files. Example:
    
    <script type="text/javascript" src="/js/main.1.min.js"></script>
    <link rel="stylesheet" type="text/css" media="screen" href="/css/main.1.min.css" />

### Configuring the asset collection ###

In your app.yml file you specify the files and partials that have to be included in your collection.
The following configuration file specifies that in the production environment the main javascript bundle must contain the backend.js file ande the contents of the general/javascipt partial.
The main css bundle must contain two files and one partial.

This configuration file also sets the version number for each collection to 1 and clarifies the path to the YUI compressor.

    all:
      gg_assetic:
        yui_path: '/usr/local/bin/yuicompressor-2.4.2.jar'
        javascript:
          main:
            files:
              - frontend.js
            partials:
              - general/javascript
            version: 1
        css:
          main:
            files:
              - reset.css
              - frontend.css
            partials:
              - general/css
            version: 1

### Using the ggAssetic task ###

This plugin provides a CLI task to combine and compile your asset collections.
When your use the task a new file will be generated in the sf_web_dir/js or sf_web_dir/css directory combining the files and partials and minifying them. A version number is included into the filename.

**Make sure your css and js directories are writable.**

To combine and minify all the assets for the frontend application, run:
    
    symfony assetic:build frontend
    symfony assetic:build frontend --type=all
    
To combine and minify only the css files for the frontend application, run:
    
    symfony assetic:build frontend --type=css

To combine and minify only the javascript files for the frontend application, run:
    
    symfony assetic:build frontend --type=javascript


## Credits ##

[Assetic Library by Kris Wallsmith](https://github.com/kriswallsmith/assetic)