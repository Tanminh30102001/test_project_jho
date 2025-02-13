<?php

namespace App\Http\Controllers;
use App\Repositories\TagRepository;
use Illuminate\Http\Request;

class TagController extends Controller
{
    protected $tagRepo;

    public function __construct(TagRepository $tagRepo)
    {
        $this->tagRepo = $tagRepo;
    }
    public function getAllTag(Request $request){
        $perPage = $request->input('per_page', 15);
        $tags = $this->tagRepo->getAllNotFilter($perPage);
        return $this->successResponse(['data'=>$tags], "success",200);
    }
    public function createTag(Request $request){
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
            ]);
            $tag = $this->tagRepo->create($data);
            return $this->successResponse( $tag, "success", 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse("Validation Error", 400, $e->errors());
        }
    }
    public function update(Request $request, $id)
    {
        try {
            $data = $request->validate([
                'name' => 'string|max:255|nullable'
            ]);

            $tag = $this->tagRepo->update($id, $data);
            if (!$tag)
            {
                $this->errorResponse("Tag not found", 400);
            }
            return $this->successResponse( $tag, "success", 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse("Validation Error", 400, $e->errors());
        }
    }
    public function deleteTag($tagId)
    {
        if ($this->tagRepo->delete($tagId)) {
            return $this->successResponse("Tag deleted", 200);
        }

        return $this->errorResponse("Tag Not found", 400);
    }
    public function addContacts(Request $request, $tagId)
    {
        try {
            $data = $request->validate(['contacts' => 'required|array|exists:contacts,id']);
        $contacts = $this->tagRepo->addContacts($tagId, $data['contacts']);
        if ($contacts === null) {
            return $this->errorResponse("Not found tag", 400,);
        }
       return $this->successResponse($contacts,"success", 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse("Validation Error", 400,['errors' => $e->errors()]);
        }
    }
    public function updateContactTag(Request $request, $tagId)
    {
        try {
            $data = $request->validate([
                'contacts' => 'required|array|exists:contacts,id'
            ]);

            $updatedContacts = $this->tagRepo->updateContacts($tagId, $data['contacts']);

            if ($updatedContacts === null) {
                return response()->json(['message' => 'Tag not found'], 400);
            }

            return $this->successResponse($updatedContacts,"Tag Updated", 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse("Validation Error", 400,$e->errors());
        }
    }
    public function removeContactFromtag($tagId, $contactId)
    {
        if ($this->tagRepo->removeContact($tagId, $contactId)) {
            return $this->successResponse("Contact removed from tag", 200);
        }

        return $this->errorResponse("Tag or Contact Not found", 400);
    }
}
