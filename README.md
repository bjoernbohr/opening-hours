# Opening Hours Plugin

The **Opening Hours** Plugin is an extension for [Grav CMS](http://github.com/getgrav/grav). Lets you Set openingHours for your Buisness, like Google does

## Installation

Installing the Opening Hours plugin can be done in one of three ways: The GPM (Grav Package Manager) installation method lets you quickly install the plugin with a simple terminal command, the manual method lets you do so via a zip file, and the admin method lets you do so via the Admin Plugin.

### GPM Installation (Preferred)

To install the plugin via the [GPM](http://learn.getgrav.org/advanced/grav-gpm), through your system's terminal (also called the command line), navigate to the root of your Grav-installation, and enter:

    bin/gpm install opening-hours

This will install the Opening Hours plugin into your `/user/plugins`-directory within Grav. Its files can be found under `/your/site/grav/user/plugins/opening-hours`.

### Manual Installation

To install the plugin manually, download the zip-version of this repository and unzip it under `/your/site/grav/user/plugins`. Then rename the folder to `opening-hours`. You can find these files on [GitHub](https://github.com//grav-plugin-opening-hours) or via [GetGrav.org](http://getgrav.org/downloads/plugins#extras).

You should now have all the plugin files under

    /your/site/grav/user/plugins/opening-hours
	
> NOTE: This plugin is a modular component for Grav which may require other plugins to operate, please see its [blueprints.yaml-file on GitHub](https://github.com//grav-plugin-opening-hours/blob/master/blueprints.yaml).

### Admin Plugin

If you use the Admin Plugin, you can install the plugin directly by browsing the `Plugins`-menu and clicking on the `Add` button.

## Configuration

Before configuring this plugin, you should copy the `user/plugins/opening-hours/opening-hours.yaml` to `user/config/plugins/opening-hours.yaml` and only edit that copy.

Here is the default configuration and an explanation of available options:

```yaml
enabled: true
```

Note that if you use the Admin Plugin, a file with your configuration named opening-hours.yaml will be saved in the `user/config/plugins/`-folder once the configuration is saved in the Admin.

## Usage

Simple to Use opening hours Plugin for your Site.

Add the **{{ opening_hours() dicke ärsche}}** function anywhere to your site

You will have to setup the Plugin or save at least once, otherwise no inital yaml file will be created and the Plugin wont show up 

If you only want to show the current day, disable the **"listview-days"**
If you want to use your own styling you can disable the css by clicking the siable button at **"Disable Plugin CSS"**

Note that in order to get the **right timeformat** you will have to set your **default language** at **configuration/site/default language**
Secondly to get the **right time** you will have to set your **timezone** under **configuration/system/content/timezone**.
For the **translation** to work properly you will have to set the **supported language** at **configuration/system/languages/supported**. 
**Don't disable** the **Include default language** property. Otherwise the translation will not work properly.

## To Do

- [ ] If desired translate the Plugin to other Languages
- [ ] Add the possibility to set "special" opened/closed hours (like Christmas or so)
- [ ] Add daylight saving time support

