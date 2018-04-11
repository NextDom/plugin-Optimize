# Description

This plugin research and offers solutions to optimize your Jeedom installation.

<img src="../images/Screenshot.png" alt="Screenshot" />

The plugin is composed mainly of a page that provides access to all the features.

# Optimizations

## Scenarios

* Logs: Whenever a message is written into the logs, it is also written into a file. The time savings can be negligible, but if your Jeedom runs on a Raspberry, you will increase the life of your SD card.
* Mode: Set the scenario to synchronous mode. It will trigger and run faster because no return will be expected after each command. However, this option should be used with caution especially if your scenarios are called between them.
* Enabled: A disabled scenario is still stored in database and is accessed by Jeedom regularly.

## The plugins

* Logs: Some plugins generate a lot of logs. They must be disabled for the same reasons as for the scenarios.
* Path: If you have installed your plugins without the Market, you may have bad names for your directories. This option allows you to rename them according to the information provided by the plugin.
* Enabled: Like scenarios, a disabled plugin is still stored and its directory is regularly browsed by Jeedom.

## System

* Logs: As for other cases, if you don't have problem with your installation, it is better to disable them,
* Minify all javascripts files in Jeedom,
* Minify all CSS files in Jeedom.

## Raspberry Pi

If Jeedom is installed on a Raspberry Pi, you can : 
* Limit the video memory,
* Limit access to the L2 cache to the processor.
