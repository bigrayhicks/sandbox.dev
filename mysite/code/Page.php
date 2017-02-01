<?php
class Page extends SiteTree {

	/**
	 * Sometimes dev/build gets borked just because Page doesn't have a value
	 * and get's turned into an obsolete _table. Then Error page or something
	 * tries to do a count and fails because there is no Page_version table.
	 *
	 * Oh joy of joys. Therefore the $db below that has no other use than
	 * to prevent dev/build failures...
	 *
	 * @var array
	 */
	private static $db = [
		'IsAPage' => 'Boolean'
	];

	public function EnvDetails() {

		$phpExtensionList = new ArrayList();
		$extensions = get_loaded_extensions();
		natcasesort($extensions);
		foreach($extensions as $extension) {
			$version = phpversion($extension);
			if($extension == 'gd') {
				$info = function_exists('gd_info') ? gd_info() : array();
				$version = isset($info['GD Version']) ? $info['GD Version'] : '';
			}
			if($extension == 'pcre') {
				$version = PCRE_VERSION;
			}
			if($extension == 'curl') {
				$version = curl_version()['version'];
			}
			if($extension == 'openssl') {
				$version = OPENSSL_VERSION_TEXT;
			}
			if($extension == 'libxml') {
				$version = LIBXML_DOTTED_VERSION;
			}
			// Extension version shows as 0.7-dev, which isn't useful. Use SQLite3::version() instead.
			if($extension == 'sqlite3') {
				$version = SQLite3::version()['versionString'];
			}
			$phpExtensionList->push(new ArrayData([
				'Value' => $version ? sprintf('%s - %s', $extension, $version) : $extension
			]));
		}

		$platform = new ArrayList();
		foreach($this->getPlatformYaml() as $key => $value) {
			$platform->push(new ArrayData([
				'Value' => sprintf("%s: %s", $key, var_export($value, true))
			]));
		}
		$list = new ArrayList();
		foreach(array(
			'Hostname' => $_SERVER['HTTP_HOST'],
			'Document root' => $_SERVER['DOCUMENT_ROOT'],
			'Platform' => $platform,
			'Web server' => $_SERVER['SERVER_SOFTWARE'],
			'PHP' => phpversion(),
			'OS' => php_uname(),
			'PHP extensions' => $phpExtensionList,
		) as $k => $v) {
			$list->push(new ArrayData(array(
				'Name' => $k,
				'Value' => $v
			)));
		}
		return $list;
	}

	protected function getPlatformYaml() {

		require_once(BASE_PATH.'/'.FRAMEWORK_DIR.'/thirdparty/spyc/spyc.php');

		$filePath = BASE_PATH.'/.platform.yml';
		if(is_readable($filePath)) {
			return Spyc::YAMLLoad($filePath);
		}

		return [];
	}
}

class Page_Controller extends ContentController {

	public function init() {
		parent::init();
		Requirements::themedCSS('reset');
		Requirements::themedCSS('layout');
		Requirements::themedCSS('typography');
		Requirements::themedCSS('form');
	}

	public function SearchForm() {
		return new Form(
			SearchController::create(),
			'/',
			new FieldList([
				new TextField('for', '', $this->getRequest()->requestVar('for'))
			]),
			new FieldList([
				new FormAction('submit', 'search')
			])
		);


	}
}
