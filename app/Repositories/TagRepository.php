<?php

namespace App\Repositories;

use App\Models\Tag;
use Illuminate\Http\Request;
class TagRepository extends BaseRepository
{
    public function __construct(Tag $tag)
    {
        parent::__construct($tag);
    }

    public function addContacts($tagId, array $contactIds)
    {
        $tag = $this->model->find($tagId);
        if (!$tag) {
            return null;
        }
        $tag->contacts()->syncWithoutDetaching($contactIds);
        return $tag->contacts;
    }
    public function addOpportunities($tagId, array $opporIds)
    {
        $tag = $this->model->find($tagId);
        if (!$tag) {
            return null;
        }
        $tag->contacts()->syncWithoutDetaching($opporIds);
        return $tag->contacts;
    }
    public function updateContacts($tagId, array $contactIds)
    {
        $tag = $this->model->find($tagId);
        if (!$tag) {
            return null;
        }
        $tag->contacts()->sync($contactIds);
        return $tag->contacts;
    }

    public function removeContact($tagId, $contactId)
    {
        $tag = $this->model->find($tagId);
        if (!$tag) {
            return false;
        }
        return $tag->contacts()->detach($contactId);
    }

}
