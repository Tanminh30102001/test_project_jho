<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ContactRepository;
use App\Repositories\ManagerRepository;
use App\Repositories\ListRepository;

class ContactController extends Controller
{
    protected $contactRepo;
    protected $managerRepo;
    protected $listRepository;

    public function __construct(ContactRepository $contactRepo, ManagerRepository $managerRepo, ListRepository $listRepository)
    {
        $this->contactRepo = $contactRepo;
        $this->managerRepo = $managerRepo;
        $this->listRepository = $listRepository;
    }
    public function getAllContact(Request $request)
    {
        $perPage = $request->input('per_page', 15);
        $filters = $request->only(['created_at', 'created_by', 'email', 'manager_id', 'search', 'tags', 'lists']);
        $contacts = $this->contactRepo->getAll($filters, $perPage);
        return $this->successResponse( $contacts, "success", 200);
    }
    public function createContact(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:contacts,email',
                'phone' => 'required|regex:/^\+?\d{1,4}[\s]?\(?\d{1,3}\)?[\s]?\d{6,10}$/',
                'manager_id' => 'required|exists:managers,id'
            ]);
            $contact = $this->contactRepo->create($data);
            return $this->successResponse(['data' => $contact], "success", 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse("Validation Error", 400, ['errors' => $e->errors()]);
        }
    }
    public function updateMultiContact(Request $request)
    {
        try {
            $data = $request->validate([
                'contacts' => 'required|array',
                'contacts.*.id' => 'required|exists:contacts,id',
                'contacts.*.name' => 'nullable|string|max:255',
                'contacts.*.phone' => 'nullable|regex:/^\+?\d{1,4}[\s]?\(?\d{1,3}\)?[\s]?\d{6,10}$/',
                'contacts.*.manager_id' => 'nullable|exists:managers,id'
            ]);

            $contacts = $data['contacts'];
            $updatedContacts = [];
            foreach ($contacts as $contactData) {
                $contact = $this->contactRepo->findById($contactData['id']);
                if (!$contact) {
                    continue;
                }
                $contact = $this->contactRepo->update($contactData['id'], $contactData);
                if ($contact) {
                    $updatedContacts[] = $contact;
                }
            }
            return $this->successResponse($updatedContacts, "Contacts updated successfully", 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse("Validation Error", 400, $e->errors());
        }
    }
    public function deleteMultiContract(Request $request)
    {
        try {
            $data = $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'required|exists:contacts,id'
            ]);
            $ids = $data['ids'];
            $deletedContacts = [];
            $errors = [];
            foreach ($ids as $id) {
                $contact = $this->contactRepo->findById($id);
                if (!$contact) {
                    $errors[] = "Contact with ID " . $id . " not found.";
                    continue;
                }
                $contact->delete();
                $deletedContacts[] = $id;
            }
            if (!empty($errors)) {
                return $this->errorResponse("Some contacts were not found", 404, ['errors' => $errors]);
            }
            return $this->successResponse($deletedContacts, "Contacts deleted successfully", 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse("Validation Error", 400, $e->errors());
        }
    }
    public function getAllManager(Request $request)
    {
        $perPage = $request->input('per_page', 15);
        $managers = $this->managerRepo->getAll($perPage);
        return $this->successResponse(['data' => $managers], "success", 200);
    }
    public function createManager(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:contacts,email',
            ]);
            $contact = $this->managerRepo->create($data);
            return $this->successResponse(['data' => $contact], "success", 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse("Validation Error", 400, ['errors' => $e->errors()]);
        }
    }
    public function updateManager(Request $request, $id)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:managers,email',
            ]);
            $manager = $this->managerRepo->findById($id);
            if (!$manager) {
                return $this->errorResponse("Manager not found", 400);
            }
            $manager = $this->managerRepo->update($id, $data);
            return $this->successResponse(['data' => $manager], "success", 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse("Validation Error", 400, ['errors' => $e->errors()]);
        }
    }

    public function deleteManager($id)
    {
        try {

            $manager = $this->managerRepo->findById($id);
            if (!$manager) {
                return $this->errorResponse("Manager not found", 400);
            }
            $manager = $this->managerRepo->delete($id);
            return $this->successResponse("success", 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse("Validation Error", 400, ['errors' => $e->errors()]);
        }
    }
    public function getAllList(Request $request)
    {
        $perPage = $request->input('per_page', 15);
        $lists = $this->listRepository->getAll($perPage);
        return $this->successResponse(['data' => $lists], "success", 200);
    }
    public function createList(Request $request)
    {
        try {
            $data = $request->validate(['name' => 'required|string|max:255']);
            $list = $this->listRepository->create($data);
            return $this->successResponse(['data' => $list], "success", 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse("Validation Error", 400, ['errors' => $e->errors()]);
        }
    }
    public function updateList(Request $request, $listId)
    {
        try {
            $list = $this->listRepository->findById($listId);
            $data = $request->validate(['name' => 'required|string|max:255']);
            if (!$list) {
                return $this->errorResponse("List Not Found", 400);
            }
            $update = $this->listRepository->update($listId, $data);
            return $this->successResponse(['data' => $update], "success", 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse("Validation Error", 400, ['errors' => $e->errors()]);
        }
    }
    public function addContacts(Request $request, $listId)
    {
        try {
            $data = $request->validate(['contacts' => 'required|array|exists:contacts,id']);
            $contacts = $this->listRepository->addContacts($listId, $data['contacts']);
            if ($contacts === null) {
                return $this->errorResponse("Not found list", 400,);
            }
            return $this->successResponse($contacts, "success", 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse("Validation Error", 400, ['errors' => $e->errors()]);
        }
    }
    public function getContactsByList($listId)
    {
        $lists = $this->listRepository->getContacts($listId);

        if ($lists === null) {
            return $this->errorResponse("List Not found", 400);
        }
        return  $this->successResponse($lists, "success", 200);
    }
    public function removeContactFromList($listId, $contactId)
    {
        if ($this->listRepository->removeContact($listId, $contactId)) {
            return $this->successResponse("Contact removed from list", 200);
        }

        return $this->errorResponse("List or Contact Not found", 400);
    }
    public function updateContactList(Request $request, $listId)
    {
        try {
            $data = $request->validate([
                'contacts' => 'required|array|exists:contacts,id'
            ]);

            $updatedContacts = $this->listRepository->updateContacts($listId, $data['contacts']);

            if ($updatedContacts === null) {
                return response()->json(['message' => 'List not found'], 400);
            }

            return $this->successResponse($updatedContacts, "List Updated", 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse("Validation Error", 400, $e->errors());
        }
    }
    public function deleteList($listId)
    {
        if ($this->listRepository->delete($listId)) {
            return $this->successResponse("List deleted", 200);
        }

        return $this->errorResponse("List Not found", 400);
    }
}
