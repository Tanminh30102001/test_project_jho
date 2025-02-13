<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\OpportunityRepository;
use App\Repositories\PipelineRepository;
use App\Repositories\PipelineColumnRepository;

class OpportunityController extends Controller
{
    protected $opportunityRepo;
    protected $pipelineRepo;
    protected $columnRepo;
    public function __construct(OpportunityRepository $opportunityRepo, PipelineRepository $pipelineRepo, PipelineColumnRepository $columnRepo)
    {
        $this->opportunityRepo = $opportunityRepo;
        $this->pipelineRepo = $pipelineRepo;
        $this->columnRepo = $columnRepo;
    }
    public function getAllOpportunity(Request $request)
    {
        $perPage = $request->input('per_page', 15);
        $filters = $request->only(['created_at', 'created_by', 'manager_id', 'search', 'tags', ]);
        $Opportunites = $this->opportunityRepo->getAll($filters, $perPage);
        return $this->successResponse(['data' => $Opportunites], "success", 200);
    }
    public function createOpportunity(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'contact_id' => 'required|exists:contacts,id',
                'manager_id' => 'required|exists:managers,id'
            ]);
            $opportunity = $this->opportunityRepo->create($data);
            return $this->successResponse(['data' => $opportunity], "success", 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse("Validation Error", 400, ['errors' => $e->errors()]);
        }
    }
    public function updateMultiOpportunity(Request $request)
    {
        try {
            $data = $request->validate([
                'opportunites' => 'required|array',
                'opportunites.*.id' => 'required|exists:opportunities,id',
                'opportunites.*.name' => 'nullable|string|max:255',
                'opportunites.*.contact_id' => 'required|exists:contacts,id',
                'opportunites.*.manager_id' => 'required|exists:managers,id'
            ]);

            $opportunites = $data['opportunites'];
            $updatedOpportunites = [];
            foreach ($opportunites as $opportunityData) {
                $opportunity = $this->opportunityRepo->findById($opportunityData['id']);
                if (!$opportunity) {
                    continue;
                }
                $opportunity = $this->opportunityRepo->update($opportunityData['id'], $opportunityData);
                if ($opportunity) {
                    $updatedOpportunites[] = $opportunity;
                }
            }
            return $this->successResponse($updatedOpportunites, "Opportunites updated successfully", 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse("Validation Error", 400, $e->errors());
        }
    }
    public function deleteMultiContract(Request $request)
    {
        try {
            $data = $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'required|exists:opportunities,id'
            ]);
            $ids = $data['ids'];
            $deletedOpportunites = [];
            $errors = [];
            foreach ($ids as $id) {
                $opportunity = $this->opportunityRepo->findById($id);
                if (!$opportunity) {
                    $errors[] = "Opportunity with ID " . $id . " not found.";
                    continue;
                }
                $opportunity->delete();
                $deletedOpportunites[] = $id;
            }
            if (!empty($errors)) {
                return $this->errorResponse("Some contacts were not found", 404, ['errors' => $errors]);
            }
            return $this->successResponse($deletedOpportunites, "Opportunites deleted successfully", 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse("Validation Error", 400, $e->errors());
        }
    }
    public function getAllPiplines()
    {
        $piplines = $this->pipelineRepo->getAll();
        return $this->successResponse($piplines, "Success", 200);
    }
    public function getDetailsPipLine($id)
    {
        $pipline = $this->pipelineRepo->findById($id);
        if (!$pipline) {
            return $this->errorResponse("Not found pipline", 400);
        }
        return $this->successResponse($pipline, "Success", 200);
    }
    public function createPipline(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string',
            ]);
            return  $this->successResponse($this->pipelineRepo->create($data), "Success", 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse("Validation Error", 400, $e->errors());
        }
    }
    public function updatePipline(Request $request, $id)
    {
        try {
            $data = $request->validate([
                'name' => 'string',
            ]);
            $pipline = $this->pipelineRepo->findById($id);
            if (!$pipline) {
                return  $this->errorResponse("Not found pipline", 400);
            }
            return  $this->successResponse($this->pipelineRepo->update($id, $data), "Success", 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse("Validation Error", 400, $e->errors());
        }
    }
    public function deletePipline($id)
    {
        $pipline = $this->pipelineRepo->findById($id);
        if (!$pipline) {
            return  $this->errorResponse("Not found pipline", 400);
        }
        return  $this->successResponse($this->pipelineRepo->delete($id), "Delete success", 201);
    }
    public function getColumnsInPipline($pipelineId)
    {
        $pipline = $this->pipelineRepo->findById($pipelineId);
        if (!$pipline) {
            return  $this->errorResponse("Not found pipline", 400);
        }
        return  $this->successResponse($this->columnRepo->getByPipeline($pipelineId), "Success", 201);
    }
    public function addColumnToPipline(Request $request, $pipelineId)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string',
                'order' => 'required|integer',
            ]);
            $pipline = $this->pipelineRepo->findById($pipelineId);
            if (!$pipline) {
                return  $this->errorResponse("Not found pipline", 400);
            }
            $data['pipeline_id'] = $pipelineId;
            return  $this->successResponse($this->columnRepo->create($data), "Success", 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse("Validation Error", 400, $e->errors());
        }
    }
    public function updateColumn(Request $request, $columnId)
    {

        try {
            $data = $request->validate([
                'name' => 'string',
                'order' => 'integer',
            ]);

            $column = $this->columnRepo->findById($columnId);
            if (!$column) {
                return $this->errorResponse("Column not found", 400);
            }
            return  $this->successResponse($this->columnRepo->update($columnId, $data), "Success", 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse("Validation Error", 400, $e->errors());
        }
    }
    public function deleteColumn($columnId)
    {
        $column = $this->columnRepo->findById($columnId);
        if (!$column) {
            return $this->errorResponse("Column not found", 400);
        }
        return  $this->successResponse($this->columnRepo->delete($columnId), " Delete Success", 200);
    }
    public function addOpportunityToColumn($opportunityId, $pipelineColumnId)
    {

        $existOpportunity = $this->opportunityRepo->findById($opportunityId);
        $piplineColumn = $this->columnRepo->findById($pipelineColumnId);
        if (!$existOpportunity || !$piplineColumn) {
            return $this->errorResponse("Column or Opportunity not found", 400);
        }
        $opportunity = $this->opportunityRepo->addOpportunityToPipelineColumn($opportunityId, $pipelineColumnId);
        return $this->successResponse($opportunity, " Opportunity added to column successfully!", 200);
    }

    public function addTagsToOpportunity(Request $request, $opportunityId)
    {

        try {
            $tagIds =  $request->validate([
                'tag_ids' => 'array',
            ]);
            $opportunity = $this->opportunityRepo->addTagsToOpportunity($opportunityId, $tagIds['tag_ids']);
            if ($opportunity == null) {
                return  $this->errorResponse("Opportunity not found", 400);
            }
            return  $this->successResponse($opportunity, " Add tag successfully!", 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse("Validation Error", 400, $e->errors());
        }
    }
    public function removeTagsFromOpportunity(Request $request, $opportunityId)
    {
        try {
            $tagIds =  $request->validate([
                'tag_ids' => 'array',
            ]);
            $opportunity = $this->opportunityRepo->removeTagsFromOpportunity($opportunityId, $tagIds['tag_ids']);
            if ($opportunity == null) {
                return  $this->errorResponse("Opportunity not found", 400);
            }
            return $this->successResponse($opportunity, " Remove tag successfully!", 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse("Validation Error", 400, $e->errors());
        }
    }

    public function getTagsForOpportunity($opportunityId)
    {
        $tags = $this->opportunityRepo->getTagsForOpportunity($opportunityId);
        if ($tags == null) {
            return  $this->errorResponse("Opportunity not found", 400);
        }
        return $this->successResponse($tags, "success!", 200);
    }
}
