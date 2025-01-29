<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

abstract class ApiCollection extends ResourceCollection
{
    /**
     * Get links from the collections
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function dataLinks($request)
    {
        return [
            'first_page' => $this->url($this->onFirstPage()),
            'last_page'  => $this->url($this->lastPage()),
            'prev_page'  => $this->previousPageUrl(),
            'next_page'  => $this->nextPageUrl()
        ];
    }

    /**
     * Get metadata from the collections
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function dataMeta($request)
    {
        return [
            'total' => $this->total(),
            'count' => $this->count(),
            'per_page' => $this->perPage(),
            'current_page' => $this->currentPage(),
            'total_pages' => $this->lastPage()
        ];
    }
}
