<?php
namespace App\Contracts;

use Illuminate\Support\Collection;

/**
 * Interface Repository
 * @package App\Contracts
 */
interface RepositoryContract
{
    /**
     * Strict scalar and result types used to reduce error proneness while switching the repository.
     *
     * @param $query
     * @param int $page
     * @param int $perPage
     * @param $sorting
     * @return Collection
     */
    public function searchCode(string $query, int $page, int $perPage, string $sorting) : Collection;
}