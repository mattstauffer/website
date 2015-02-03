<?php namespace LaravelWebSite;

use dflydev\markdown\MarkdownExtraParser;
use Elasticsearch\Client;
use Illuminate\Filesystem\Filesystem;

class DocSearchService {

	/**
	 * @var Client
	 */
	private $client;

	/**
	 * @var MarkdownExtraParser
	 */
	private $markdown;

	/**
	 * @var Filesystem
	 */
	private $files;

	private $indexName = 'docs';

	private $docsPath;

	// @todo: Consider passing docsVersion into the individual methods
	public function __construct($docsVersion = 'master', Client $client, MarkdownExtraParser $markdown, Filesystem $files)
	{
		$this->client = $client;
		$this->markdown = $markdown;
		$this->files = $files;

		$this->docsPath = base_path() . '/docs/' . $docsVersion;
	}

	/**
	 * Initialize ElasticSearch Index
	 *
	 * Not necessary (ElasticSearch will auto create an index if it doesn't exist) *unless*
	 * it becomes necessary to define parameters--sharding, etc.
	 *
	 * @throws \Elasticsearch\Common\Exceptions\BadRequest400Exception If index already exists
	 */
	public function initializeIndex()
	{
		$indexParams['index'] = $this->indexName;
		$this->client->indices()->create($indexParams);
	}

	/**
	 * Index all docs
	 */
	public function indexAllDocuments()
	{
		$docs = $this->files->files($this->docsPath);

		foreach ($docs as $path)
		{
			$this->indexDocument($path);
		}
	}

	/**
	 * Index a given document in ElasticSearch, given its path
	 *
	 * @param $path
	 */
	public function indexDocument($path)
	{
		$slug = $this->getSlugFromPath($path);
		$markdown = $this->getFileContents($path);
		$html = $this->convertMarkdownToHtml($markdown);

		$params['index'] = $this->indexName;
		$params['body'] = [
			'slug' => $slug,
			'title' => '@todo',
			'body.html' => $html,
			'body.md' => $markdown,
		];
		$params['type'] = 'page';
		$params['id'] = $this->generateDocIdFromSlug($slug);

		$return = $this->client->index($params);

		echo "Indexed $slug";
	}

	/**
	 * @param string $slug
	 * @return string
	 */
	private function generateDocIdFromSlug($slug)
	{
		return md5($slug);
	}

	/**
	 * @param string $path
	 * @return string
	 * @throws \Exception
	 */
	private function getFileContents($path)
	{
		$this->verifyFileExists($path);

		return file_get_contents($path);
	}

	/**
	 * @param $path
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
	 * @param string $markdown
	 * @return string
	 */
	private function convertMarkdownToHtml($markdown)
	{
		return $this->markdown->transformMarkdown($markdown);
	}

	/**
	 * @param string $slug
	 * @return string
	 * @throws \Exception If path does not exist
	 */
	public function getPathFromSlug($slug)
	{
		return $this->docsPath . "/" . $path . ".md";
	}

	/**
	 * @param string $path
	 * @return string
	 */
	public function getSlugFromPath($path)
	{
		$fileName = last(explode('/', $path));
		return str_replace('.md', '', $fileName);
	}
}
