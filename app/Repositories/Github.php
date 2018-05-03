<?php
namespace App\Repositories;

use App\Contracts\RepositoryContract;
use Github\Client;
use GrahamCampbell\GitHub\Facades\Github as GithubBridge;
use Illuminate\Support\Collection;
use Psr\Cache\CacheItemPoolInterface;

/**
 * Class Github
 * @package App\Repositories
 */
class Github implements RepositoryContract
{
    /**
     * @var int
     */
    protected $perPage = 25;

    /**
     * @var string
     */
    protected $sorting = 'score';

    /**
     * @var CacheItemPoolInterface
     */
    protected $cachePool;

    /**
     * @var Client
     */
    protected $githubClient;

    /**
     * @var bool
     */
    protected $useCache = false;

    /**
     * $pool will be used to store cached results if caching is enabled.
     *
     * @param CacheItemPoolInterface $pool
     */
    public function __construct(CacheItemPoolInterface $pool)
    {
        $this->cachePool = $pool;
        $this->githubClient = GithubBridge::connection('main');
    }

    /**
     * @param bool $useCache
     */
    public function setCacheUsage(bool $useCache)
    {
        $this->useCache = $useCache;
    }

    /**
     * @return void
     */
    public function removeCache()
    {
        $this->githubClient->removeCache();
    }

    /**
     * @param string $query
     * @param int $page
     * @param int $perPage
     * @param string $sorting
     * @return Collection
     */
    public function searchCode(string $query, int $page, int $perPage, string $sorting) : Collection
    {
        $results = collect([]);

        $_page = $page ? $page : 1;
        $_perPage = $perPage ? $perPage : $this->perPage;
        $_sorting = $sorting ? $sorting : $this->sorting;

        /**
         * For the sake of example, we can optionally cache the results locally, for a faster access
         * in the future. However, for this type of request (search), GitHub will send `no-cache` headers
         * which will hinder from caching the response.
         */
        if ($this->useCache)
            $this->githubClient->addCache($this->cachePool);

        $matches = $this->githubClient->api('search')
            ->setPerPage($_perPage)
            ->setPage($_page)
            ->code($query, $_sorting);

        if ($matches['total_count'] > 0) {
            $collection = collect($matches['items']);

            $results = $collection->map(function ($match) {
                return [
                    'owner' => array_get($match,'repository.owner.login'),
                    'repository' => array_get($match, 'repository.full_name'),
                    'file' => $match['path']
                ];
            });
        }

        return $results;
    }
}