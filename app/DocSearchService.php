<?php namespace App;

use Elasticsearch\Client;
use Elasticsearch\Common\Exceptions\BadRequest400Exception;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use Illuminate\Filesystem\Filesystem;
use ParsedownExtra;

/**
 * @todo Separate this into an indexer and a searcher
 */
class DocSearchService {

	/**
	 * @var Client
	 */
	private $client;

	/**
	 * @var ParsedownExtra
	 */
	private $markdown;

	/**
	 * @var Filesystem
	 */
	private $files;

	private $noIndex = [
		'documentation',
		'license'
	];

	public function __construct(Client $client, ParsedownExtra $markdown, Filesystem $files)
	{
		$this->client = $client;
		$this->markdown = $markdown;
		$this->files = $files;
	}

	/**
	 * @todo Make the search broader--for example, include and value more highly the title
	 * @todo Make the search smarter--for example, care more about h2s, h3s, etc.
	 * @param  string $version
	 * @param  string $term
	 * @return array
	 * @throws \Exception
	 */
	public function searchForTerm($version, $term)
	{
		$params['index'] = $this->getIndexName($version);
		$params['type'] = 'page';
		$params['body']['query']['match']['body.md'] = $term;

		try {
			$response = $this->client->search($params);
		} catch (Missing404Exception $e) {
			throw new \Exception('ElasticSearch Index was not initialized.');
		}

		// @todo Validate response

		return $response['hits']['hits'];
	}

	/**
	 * Initialize ElasticSearch Index
	 *
	 * Not necessary (ElasticSearch will auto create an index if it doesn't exist) *unless*
	 * it becomes necessary to define parameters--sharding, etc.
	 */
	public function initializeIndexes()
	{
		foreach (Documentation::getDocVersions() as $versionKey => $versionTitle)
		{
			try {
				$this->client->indices()->create([
					'index' => $this->getIndexName($versionKey)
				]);
			} catch (BadRequest400Exception $e) {
				// Cool, we already have that index. Still want to create the others...
			}
		}
	}

	/**
	 * Index all docs for all versions
	 */
	public function indexAllDocuments()
	{
		foreach (Documentation::getDocVersions() as $versionKey => $versionTitle)
		{
			$this->indexAllDocumentsForVersion($versionKey);
		}
	}

	/**
	 * Index all docs for this version
	 *
	 * @param $version
	 */
	public function indexAllDocumentsForVersion($version)
	{
		foreach ($this->files->files($this->getDocsPath($version)) as $path)
		{
			if (! in_array($this->getSlugFromPath($path), $this->noIndex))
			{
				$this->indexDocument($version, $path);
			}
		}
	}

	/**
	 * Index a given document in ElasticSearch
	 *
	 * @todo Split up the document into h2/h3-split sections and index individually
	 * for better split of the search
	 *
	 * @param string $version
	 * @param string $path
	 */
	public function indexDocument($version, $path)
	{
		$slug = $this->getSlugFromPath($path);
		$markdown = $this->getFileContents($path);
		$html = $this->convertMarkdownToHtml($markdown);
		$title = $this->extractTitleFromMarkdown($markdown);

		$params['index'] = $this->getIndexName($version);
		$params['body'] = [
			'slug' => $slug,
			'title' => $title,
			'body.html' => $html,
			'body.md' => $markdown,
		];
		$params['type'] = 'page';
		$params['id'] = $this->generateDocIdFromSlug($slug);

		$return = $this->client->index($params);

		echo "Indexed $version.$slug\n";
	}

	/**
	 * Get the ElasticSearch index name for this version
	 *
	 * @param $version
	 * @return string
	 */
	private function getIndexName($version)
	{
		return 'docs.' . $version;
	}

	/**
	 * @param string $version
	 * @return string
	 */
	private function getDocsPath($version)
	{
		return base_path('resources/docs/' . $version . '/');
	}

	/**
	 * @param  string $slug
	 * @return string
	 */
	private function generateDocIdFromSlug($slug)
	{
		return md5($slug);
	}

	/**
	 * @param  string $path
	 * @return string
	 * @throws \Exception
	 */
	private function getFileContents($path)
	{
		$this->verifyFileExists($path);

		return file_get_contents($path);
	}

	/**
	 * @param  string $path
	 * @throws \Exception
	 */
	private function verifyFileExists($path)
	{
		if ( ! file_exists($path))
		{
			throw new \Exception('File does not exist at path: ' . $path);
		}
	}

	/**
	 * @param  string $markdown
	 * @return string
	 */
	private function convertMarkdownToHtml($markdown)
	{
		return $this->markdown->text($markdown);
	}

	/**
	 * @param  string $markdown
	 * @return string
	 */
	private function extractTitleFromMarkdown($markdown)
	{
		preg_match_all("/^# (.*)$/m", $markdown, $m);

		if (empty($m[1])) dd($m);
		return $m[1][0];
	}

	/**
	 * @param  string $slug
	 * @return string
	 * @throws \Exception If path does not exist
	 */
	public function getPathFromSlug($slug)
	{
		return $this->docsPath . "/" . $path . ".md";
	}

	/**
	 * @param  string $path
	 * @return string
	 */
	public function getSlugFromPath($path)
	{
		$fileName = last(explode('/', $path));
		return str_replace('.md', '', $fileName);
	}
}
