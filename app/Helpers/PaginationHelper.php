<?php

namespace App\Helpers;

use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

/**
 * https://stackoverflow.com/a/65711265/1299357
 */
class PaginationHelper
{
    /**
     * @param Collection<int, mixed> $results
     * @param int $showPerPage
     * @return LengthAwarePaginator<array>
     */
    public static function paginate(Collection $results, int $showPerPage): LengthAwarePaginator
    {
        $pageNumber = Paginator::resolveCurrentPage('page');

        $totalPageNumber = $results->count();

        return self::paginator($results->forPage($pageNumber, $showPerPage), $totalPageNumber, $showPerPage, $pageNumber, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => 'page',
        ]);

    }

    /**
     * Create a new length-aware paginator instance.
     *
     * @param \Illuminate\Support\Collection<int, mixed> $items
     * @param int $total
     * @param int $perPage
     * @param int $currentPage
     * @param array $options
     * @return LengthAwarePaginator<array>
     * @throws BindingResolutionException
     */
    protected static function paginator(Collection $items, int $total, int $perPage, int $currentPage, array $options): LengthAwarePaginator
    {
        return Container::getInstance()->makeWith(LengthAwarePaginator::class, compact(
            'items', 'total', 'perPage', 'currentPage', 'options'
        ));
    }
}