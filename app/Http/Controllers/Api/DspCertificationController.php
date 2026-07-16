<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\DspCertificationResource;
use App\Models\DspCertification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DspCertificationController extends BaseController
{
    /**
     * List the authenticated DSP's certifications.
     *
     * GET /api/dsp/certifications
     */
    public function index()
    {
        try {
            $certifications = DspCertification::where('user_id', Auth::id())
                ->orderBy('name')
                ->get();

            return $this->sendResponse(DspCertificationResource::collection($certifications), 'Certifications retrieved successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Certifications could not be retrieved.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Add a new certification for the authenticated DSP.
     *
     * POST /api/dsp/certifications
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'expires_at' => 'nullable|date',
            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors()->toArray(), 422);
            }

            $certification = DspCertification::create([
                ...$validator->validated(),
                'user_id' => Auth::id(),
            ]);

            return $this->sendResponse(new DspCertificationResource($certification), 'Certification created successfully.', 201);
        } catch (\Exception $e) {
            return $this->sendError('Certification could not be created.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Show a single certification belonging to the authenticated DSP.
     *
     * GET /api/dsp/certifications/{id}
     */
    public function show(string $id)
    {
        try {
            $certification = DspCertification::where('user_id', Auth::id())->findOrFail($id);

            return $this->sendResponse(new DspCertificationResource($certification), 'Certification retrieved successfully.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->sendError('Certification not found.', [], 404);
        } catch (\Exception $e) {
            return $this->sendError('Certification could not be retrieved.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update a certification belonging to the authenticated DSP.
     *
     * PUT/PATCH /api/dsp/certifications/{id}
     */
    public function update(Request $request, string $id)
    {
        try {
            $certification = DspCertification::where('user_id', Auth::id())->findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
                'expires_at' => 'nullable|date',
            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors()->toArray(), 422);
            }

            $certification->update($validator->validated());

            return $this->sendResponse(new DspCertificationResource($certification), 'Certification updated successfully.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->sendError('Certification not found.', [], 404);
        } catch (\Exception $e) {
            return $this->sendError('Certification could not be updated.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete a certification belonging to the authenticated DSP.
     *
     * DELETE /api/dsp/certifications/{id}
     */
    public function destroy(string $id)
    {
        try {
            $certification = DspCertification::where('user_id', Auth::id())->findOrFail($id);
            $certification->delete();

            return $this->sendResponse([], 'Certification deleted successfully.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->sendError('Certification not found.', [], 404);
        } catch (\Exception $e) {
            return $this->sendError('Certification could not be deleted.', ['error' => $e->getMessage()], 500);
        }
    }
}
