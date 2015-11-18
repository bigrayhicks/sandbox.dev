<?php

global $project;
$project = 'mysite';

global $database;
$database = '';

require_once('conf/ConfigureFromEnv.php');

// Set the current theme. More themes can be downloaded from
// http://www.silverstripe.org/themes/
SSViewer::set_theme('pure');

// public
EnvironmentCheckSuite::register('pingdom', 'URLCheck("")', "Homepage accessible");
EnvironmentCheckSuite::register('pingdom', 'DatabaseCheck', "Connect to database");
EnvironmentCheckSuite::register('pingdom', 'ExternalURLCheck("https://stojg.se/", "5")', "Connect to stojg.se?");
EnvironmentCheckSuite::register('support', 'DatabaseCheck', "Connect to database");
