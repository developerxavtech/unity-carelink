<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\TodoNoteResource;
use App\Models\TodoNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TodoNoteController extends BaseController
{
    /**
     * List the authenticated user's todo notes.
     *
     * GET /api/todo-notes
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 15);

            $notes = TodoNote::where('user_id', Auth::id())
                ->latest()
                ->paginate($perPage);

            return $this->sendResponse([
                'notes' => TodoNoteResource::collection($notes->items()),
                'pagination' => [
                    'total' => $notes->total(),
                    'per_page' => $notes->perPage(),
                    'current_page' => $notes->currentPage(),
                    'last_page' => $notes->lastPage(),
                ],
            ], 'Todo notes retrieved successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Todo notes could not be retrieved.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Create a new todo note for the authenticated user.
     *
     * POST /api/todo-notes
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors()->toArray(), 422);
            }

            $note = TodoNote::create([
                ...$validator->validated(),
                'user_id' => Auth::id(),
            ]);

            return $this->sendResponse(new TodoNoteResource($note), 'Todo note created successfully.', 201);
        } catch (\Exception $e) {
            return $this->sendError('Todo note could not be created.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Show a single todo note belonging to the authenticated user.
     *
     * GET /api/todo-notes/{id}
     */
    public function show(string $id)
    {
        try {
            $note = TodoNote::where('user_id', Auth::id())->findOrFail($id);

            return $this->sendResponse(new TodoNoteResource($note), 'Todo note retrieved successfully.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->sendError('Todo note not found.', [], 404);
        } catch (\Exception $e) {
            return $this->sendError('Todo note could not be retrieved.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update a todo note belonging to the authenticated user.
     *
     * PUT/PATCH /api/todo-notes/{id}
     */
    public function update(Request $request, string $id)
    {
        try {
            $note = TodoNote::where('user_id', Auth::id())->findOrFail($id);

            $validator = Validator::make($request->all(), [
                'title' => 'sometimes|required|string|max:255',
                'description' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors()->toArray(), 422);
            }

            $note->update($validator->validated());

            return $this->sendResponse(new TodoNoteResource($note), 'Todo note updated successfully.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->sendError('Todo note not found.', [], 404);
        } catch (\Exception $e) {
            return $this->sendError('Todo note could not be updated.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete a todo note belonging to the authenticated user.
     *
     * DELETE /api/todo-notes/{id}
     */
    public function destroy(string $id)
    {
        try {
            $note = TodoNote::where('user_id', Auth::id())->findOrFail($id);
            $note->delete();

            return $this->sendResponse([], 'Todo note deleted successfully.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->sendError('Todo note not found.', [], 404);
        } catch (\Exception $e) {
            return $this->sendError('Todo note could not be deleted.', ['error' => $e->getMessage()], 500);
        }
    }
}
