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

// Note: This is SilverStripe 3.0 compatible code, so we cannot use CONSTANTS in the yaml config
//       so this mean that we unfortunately need to set up ElasticSearch (ES) configuration in
//       runtime.
//
// The following constants are defined in SSP for connection to AWS ES Service:
//     - AWS_REGION_NAME
//     - ELASTICSEARCH_HOST
//     - ELASTICSEARCH_PORT
//     - ELASTICSEARCH_INDEX
if (defined('ELASTICSEARCH_HOST') && defined('ELASTICSEARCH_PORT')) {
	$config = [
		'host' => ELASTICSEARCH_HOST,
		'port' => ELASTICSEARCH_PORT,
		'timeout' => 5
	];

	if (defined('AWS_REGION_NAME')) {
		$config['transport'] = 'AwsAuthV4';
		$config['aws_region'] = AWS_REGION_NAME;
	}

	$esClient = new Elastica\Client($config);
	Injector::inst()->registerService($esClient, 'Elastica\Client');

	// This logging out of the box of ElasticaService isn't stellar, it outputs some connectivity errors, like wrong DNS,
	// but not much more. it will be searchable in the graylog with the query "log_type:SilverStripe_log"
	$esLogger = new Monolog\Logger('ElasticaService');
	$esSyslog = new Monolog\Handler\SyslogHandler('SilverStripe_log');
	$logFormat = "%channel%.%level_name%: %message%";
	if (!empty($_SERVER['HTTP_HOST'])) {
		$logFormat .= ' [' . $_SERVER['HTTP_HOST'] . ']';
	}
	$formatter = new Monolog\Formatter\LineFormatter($logFormat);
	$esSyslog->setFormatter($formatter);
	$esLogger->pushHandler($esSyslog);

	$esService = new Heyday\Elastica\ElasticaService($esClient, ELASTICSEARCH_INDEX, $esLogger, '64MB');
	Injector::inst()->registerService($esService, 'Heyday\Elastica\ElasticaService');
}
