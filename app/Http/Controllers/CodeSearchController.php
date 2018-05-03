<?php
namespace App\Http\Controllers;

use App\Contracts\RepositoryContract;
use App\Http\Requests\SearchCodeRequest;
use Illuminate\Http\Request;

/**
 * Class CodeSearchController
 * @package App\Http\Controllers
 */
class CodeSearchController extends Controller
{
    /**
     * @var RepositoryContract
     */
    protected $repository;

    /**
     * The repository class will be retrieved from Laravel service container.
     *
     * @param RepositoryContract $repository
     */
    public function __construct(RepositoryContract $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param SearchCodeRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(SearchCodeRequest $request)
    {
        list($query, $page, $per_page, $sorting) = array_values($request->all('query', 'page', 'per_page', 'sorting'));

        $results = $this->repository->searchCode($query, (int)$page, (int)$per_page, (string)$sorting);

        return response()->json($results);
    }
}
