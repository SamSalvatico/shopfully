<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

class RestResourceCollection extends ResourceCollection
{
    public function withResponse($request, $response)
        {
            if ($this->resource instanceof LengthAwarePaginator) {
                $baseQueryString = $request->query;
                $baseUrl = $request->url() . "?";
                foreach ($baseQueryString as $q => $value)
                    if ($q !== "page")
                        $baseUrl .= $q . "=" . $value . "&";
                $currentPage = $this->resource->currentPage();
                $lastPage = $this->resource->lastPage();
                $previousPage = $this->resource->onFirstPage() ? null : ($currentPage - 1);
                $nextPage = ($currentPage < $lastPage) ? ($currentPage + 1) : null;

                $format = "<%s>; rel=\"%s\"";
                $prev = sprintf($format, empty($previousPage) ? "" : ($baseUrl . "page=" . $previousPage), 'prev');
                $next = sprintf($format, empty($nextPage) ? "" : ($baseUrl . "page=" . $nextPage), 'next');
                $first = sprintf($format, (empty($previousPage) && empty($nextPage)) ? "" : ($baseUrl . "page=1"), 'first');
                $last = sprintf($format, empty($lastPage) ? "" : ($baseUrl . "page=" . $lastPage), 'last');

                $links = "{$prev}, {$next}, {$first}, {$last}";

                $response->header('Link', $links);
                $response->header('total', $this->resource->total());
            }
        }
}
