<?php

/**
 * Class SearchController
 */
class SearchController extends Page_Controller {

	/**
	 * @var array
	 */
	private static $allowed_actions = [
		'index'
	];

	private static $dependencies = [
		'searchService' => '%$Heyday\Elastica\ElasticaService'
	];

	/**
	 * @var string
	 */
	private $lastSearchTerm = null;

	/**
	 * @var \Heyday\Elastica\PaginatedList
	 */
	private $_cached_result = null;

	/**
	 * @var \Heyday\Elastica\ElasticaService
	 */
	protected $searchService;

	/**
	 * Search results page action
	 *
	 * @return HTMLText
	 */
	public function index() {
		return $this->renderWith(['Page_results', 'Page']);
	}

	public function Link($action = null) {
		return 'search';
	}

	/**
	 * @return bool|\Heyday\Elastica\PaginatedList
	 */
	public function Results() {
		$request = $this->getRequest();

		$searchTerm = $request->requestVar('for');

		// prevent the template calls to do multiple calls to the search engine
		if ($this->_cached_result instanceof \PaginatedList) {
			return $this->_cached_result;
		}

		if ($searchTerm) {
			$this->lastSearchTerm = $searchTerm;
			$query = new \Elastica\Query\BoolQuery();
			$query->addMust(
				new \Elastica\Query\QueryString(strval($searchTerm))
			);
			$results = $this->searchService->search($query);
			if ($results->count() == 0) {
				return false;
			}
			$this->_cached_result = \Heyday\Elastica\PaginatedList::create($results, $request);
			return $this->_cached_result;
		}

		return false;
	}

	/**
	 * @return mixed
	 */
	public function SearchString() {
		return Convert::raw2xml($this->getRequest()->requestVar('for'));
	}
}