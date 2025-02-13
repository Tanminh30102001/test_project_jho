<?php

namespace App\Http\Controllers;

use App\Repositories\TaskRepository;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    protected $taskRepo;

    public function __construct(TaskRepository $taskRepo)
    {
        $this->taskRepo = $taskRepo;
    }
    public function getAllTask(Request $request)
    {
        $filters = $request->all();
        $tasks = $this->taskRepo->getAllTasks($filters, 10); // Sử dụng phân trang
        return $this->successResponse($tasks, "success", 200);
    }
    public function create(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string',
                'description' => 'nullable|string',
                'status' => 'required|in:pending,completed,in_progress',
                'due_date' => 'required|date',
                'opportunity_id' => 'required|exists:opportunities,id',
                'contact_id' => 'required|exists:contacts,id',
                'manager_id' => 'required|exists:managers,id',
            ]);

            $task = $this->taskRepo->create($data);
            return $this->successResponse($task, "success", 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse("Validation Error", 400, $e->errors());
        }

    }
    public function updateMultiTask(Request $request)
    {
        try {
            $data = $request->validate([
                'tasks' => 'required|array',
                'tasks.*.id' => 'required|exists:tasks,id',
                'tasks.*.name' => 'nullable|string',
                'tasks.*.description' => 'nullable|string',
                'tasks.*.status' => 'nullable|in:pending,completed,in_progress',
                'tasks.*.due_date' => 'nullable|date',
                'tasks.*.opportunity_id' => 'nullable|exists:opportunities,id',
                'tasks.*.contact_id' => 'nullable|exists:contacts,id',
                'tasks.*.manager_id' => 'nullable|exists:managers,id',
            ]);

            $tasks = $data['tasks'];
            $updatedTasks = [];
            foreach ($tasks as $taskData) {
                $task = $this->taskRepo->findById($taskData['id']);
                if (!$task) {
                    continue;
                }
                $task = $this->taskRepo->update($taskData['id'], $taskData);
                if ($task) {
                    $updatedTasks[] = $task;
                }
            }
            return $this->successResponse($updatedTasks, "Tasks updated successfully", 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse("Validation Error", 400, $e->errors());
        }
    }
    public function deleteMultiTask(Request $request)
    {
        try {
            $data = $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'required|exists:tasks,id'
            ]);
            $ids = $data['ids'];
            $deletedTasks = [];
            $errors = [];
            foreach ($ids as $id) {
                $task = $this->taskRepo->findById($id);
                if (!$task) {
                    $errors[] = "Tasks with ID " . $id . " not found.";
                    continue;
                }
                $task->delete();
                $deletedTasks[] = $id;
            }
            if (!empty($errors)) {
                return $this->errorResponse("Some tasks were not found", 404, ['errors' => $errors]);
            }
            return $this->successResponse($deletedTasks, "Tasks deleted successfully", 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse("Validation Error", 400, $e->errors());
        }
    }
}
