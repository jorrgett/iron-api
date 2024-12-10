<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;

class CustomPaginator
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }
    public function paginate($array)
    {
        $page = $this->request['page'] ?? 1;
        $perPage = $this->request['size'] ?? 10;
        $offset = ($page * $perPage) - $perPage;

        return new LengthAwarePaginator(
            array_slice($array, $offset, $perPage, true),
            count($array),
            $perPage,
            $page,
            ['path' => $this->request->url(), 'query' => $this->request->query()]
        );
    }

    public function merge(LengthAwarePaginator $collection1, LengthAwarePaginator $collection2)
    {
        $total = $collection1->total() + $collection2->total();

        $perPage = $collection1->perPage() + $collection2->perPage();

        $items = array_merge($collection1->items(), $collection2->items());

        $paginator = new LengthAwarePaginator($items, $total, $perPage);

        return $paginator;
    }

    
    public function paginateCollection($collection, $perPage, $page)
    {
        $offset = ($page - 1) * $perPage;

        return new \Illuminate\Pagination\LengthAwarePaginator(
            $collection->slice($offset, $perPage)->values(),
            $collection->count(), 
            $perPage,
            $page,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]  // Resolver la URL actual para la paginación
        );
    }
}
