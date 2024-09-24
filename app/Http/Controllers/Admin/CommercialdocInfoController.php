<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CommercialdocInfoType as InfoType;
use App\Http\Controllers\Controller;
use App\Http\Requests\CommercialdocInfoRequest;
use App\Models\Commercialdoc;
use App\Models\CommercialdocInfo;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Mavinoo\Batch\Batch as Batch;

class CommercialdocInfoController extends Controller
{
    /**
     * Create and persist a new item
     */
    public function store(string $commercialdoc, CommercialdocInfoRequest $request)
    {
        $commercialdoc_id = (new Commercialdoc)->hashidToId($commercialdoc);
        $doc              = Commercialdoc::findOrFail($commercialdoc_id);

        $validated = $request->validated();

        $item = $doc->infos()->create($validated);

        return response()->json($this->normalizeModels($item), 201); // 201 Created
    }

    /**
     * Update the specified item
     */
    public function update(Commercialdoc $commercialdoc, CommercialdocInfo $info, CommercialdocInfoRequest $request)
    {
        if ($commercialdoc->id !== $info->commercialdoc_id) {
            return response()->json([
                'error' => 'The item does not belong to the specified document.',
            ], 403); // 403 Forbidden
        }

        $info->update($request->validated());

        return response()->json($this->normalizeModels($info), 200); // 200 OK
    }

    /**
     * Delete item from quote or invoice
     */
    public function destroy(Commercialdoc $commercialdoc, int $item)
    {
        $item = CommercialdocInfo::findOrFail($item);

        // if item is not found, then it's fine!
        if (!$item) return response()->json([
            'error' => "The item #$item not found.",
        ], 404);


        if ($commercialdoc->id !== $item->commercialdoc_id) {
            return response()->json([
                'error' => 'The item does not belong to the specified document.',
            ], 403); // 403 Forbidden
        }

        $item->delete();

        return response()->json(null, 204); // 204 No Content
    }

    public function updateOrder(Commercialdoc $commercialdoc, Request $request)
    {

        // Validate that the request contains an 'IDs' array
        $validated = $request->validate([
            'IDs'   => 'required|array',
            'IDs.*' => 'integer' // Ensure each ID exists in the table
        ]);

        if (!($ids = $validated['IDs'])) {
            return response()->json(['message' => 'Nothing to reorder.'], 200);
        }

        // Prepare the data for bulk update
        foreach ($ids as $index => $id) {
            $data[] = [
                'conditions' => ['id' => $id, 'commercialdoc_id' => $commercialdoc->id],
                'columns'    => [
                    'ord' => $index,
                ],
            ];
        }

        // Use the batch package to update the 'ord' field for each model
        batch()->updateMultipleCondition(new CommercialdocInfo, $data, 'id');

        $updatedModels = CommercialdocInfo::where('commercialdoc_id', $commercialdoc->id)
            ->get(['id', 'ord'])
            ->extractNormalizedRelationsForFrontEnd();

        // Flash a success message to the session
        session()->flash('success', 'Order updated successfully.');

        return response()->json($updatedModels);
    }

}
