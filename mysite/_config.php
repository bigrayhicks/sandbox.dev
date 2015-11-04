<?php

global $project;
$project = 'mysite';

global $database;
$database = '';

require_once('conf/ConfigureFromEnv.php');

// Set the current theme. More themes can be downloaded from
// http://www.silverstripe.org/themes/
SSViewer::set_theme('pure');

// Enable nested URLs for this site (e.g. page/sub-page/)
if(class_exists('SiteTree')) SiteTree::enable_nested_urls();

// public
EnvironmentCheckSuite::register('pingdom', 'URLCheck("")', "Homepage accessible");
EnvironmentCheckSuite::register('pingdom', 'DatabaseCheck', "Connect to database");
EnvironmentCheckSuite::register('pingdom', 'ExternalURLCheck("https://stojg.se/", "5")', "Connect to stojg.se?");
EnvironmentCheckSuite::register('support', 'DatabaseCheck', "Connect to database");

NonExistingClassThrowsFatalInDevBuild::init();
