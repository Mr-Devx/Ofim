<?php

namespace App\Helpers;

use Illuminate\Pagination\LengthAwarePaginator;

class CustomPaginator extends LengthAwarePaginator
{
    /**
     * Get the pagination links.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'current_page' => $this->currentPage(),
            'data' => $this->items->toArray(),
            'first_page_url' => $this->url(1),
            'from' => $this->firstItem(),
            'last_page' => $this->lastPage(),
            'last_page_url' => $this->url($this->lastPage()),
            'links' => $this->renderLinks(),
            'next_page_url' => $this->nextPageUrl(),
            'path' => $this->path,
            'per_page' => $this->perPage(),
            'prev_page_url' => $this->previousPageUrl(),
            'to' => $this->lastItem(),
            'total' => $this->total(),
        ];
    }

    /**
     * Render the pagination links.
     *
     * @return array
     */
    public function renderLinks()
    {
        $links = [];

        if ($this->onFirstPage()) {
            $links[] = [
                'url' => null,
                'label' => '&laquo; Précédent',
                'active' => false,
            ];
        } else {
            $links[] = [
                'url' => $this->previousPageUrl(),
                'label' => '&laquo; Précédent',
                'active' => true,
            ];
        }

        $links[] = [
            'url' => $this->url(1),
            'label' => '1',
            'active' => $this->currentPage() === 1,
        ];

        if ($this->currentPage() === $this->lastPage()) {
            $links[] = [
                'url' => null,
                'label' => 'Suivant &raquo;',
                'active' => false,
            ];
        } else {
            $links[] = [
                'url' => $this->nextPageUrl(),
                'label' => 'Suivant &raquo;',
                'active' => true,
            ];
        }

        return $links;
    }
}