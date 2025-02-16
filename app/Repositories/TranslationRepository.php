<?php

namespace App\Repositories;

use App\Repositories\Interfaces\TranslationInterface;
use App\Models\Translation;

class TranslationRepository extends BaseRepository implements TranslationInterface {
    public function __construct(Translation $model)
    {
        $this->model = $model;
    }

    public function listing($query = []) {
        $translations = $this->model->query();
        
        if (isset($query['locale'])) {
            $translations = $translations->where('locale', $query['locale']);
        }
        
        if (isset($query['key'])) {
            $translations = $translations->where('key', $query['key']);
        }
        
        if (isset($query['tag'])) {
            $translations = $translations->whereJsonContains('tags', $query['tag']);
        }
        
        if (isset($query['tag'])) {
            $translations = $translations->where('content', 'like', '%' . $query['query'] . "%");
        }
        
        return $translations->paginate(config('pagination.default'));
    }
}