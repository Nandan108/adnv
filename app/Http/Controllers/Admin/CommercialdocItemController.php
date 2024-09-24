<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commercialdoc;
use App\Models\CommercialdocInfo;
use App\Models\CommercialdocItem;
use Illuminate\Http\Request;
use Mavinoo\Batch\BatchFacade as Batch;

class CommercialdocItemController extends Controller
{
    /**
     * Validate the request data.
     */
    protected function validateData(Request $request)
    {
        return $request->validate([
            'description'  => 'required|string|max:255', // string(255)
            'unitprice'    => 'required|numeric|gte:0', // decimal(7,2)
            'qtty'         => 'required|integer|gte:1', // tinyint
            'discount_pct' => 'integer|gte:0|lte:100', // tinyint
            'section'      => ['required', 'regex:/^(primary|options)$/'], // enum ['primary', 'options']
        ]);
    }

    /**
     * Create and persist a new item
     */
    public function store(string $commercialdoc, Request $request)
    {
        $commercialdoc_id = (new Commercialdoc)->hashidToId($commercialdoc);
        $doc              = Commercialdoc::findOrFail($commercialdoc_id);

        $validated                     = $this->validateData($request);
        $validated['commercialdoc_id'] = $doc->id;

        $item = $doc->items()->create($validated);

        return response()->json($this->normalizeModels($item), 201); // 201 Created
    }

    /**
     * Update the specified item
     */
    public function update(Commercialdoc $commercialdoc, CommercialdocItem $item, Request $request)
    {
        $validated = $this->validateData($request);

        $item->update($validated);

        return response()->json($this->normalizeModels($item), 200); // 200 OK
    }

    /**
     * Delete item from quote or invoice
     */
    public function destroy(Commercialdoc $commercialdoc, CommercialdocItem $item)
    {
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
        batch()->updateMultipleCondition(new CommercialdocItem, $data, 'id');

        $updatedModels = CommercialdocItem::where('commercialdoc_id', $commercialdoc->id)
            ->get(['id', 'ord'])
            ->extractNormalizedRelationsForFrontEnd();

        // Flash a success message to the session
        session()->flash('success', 'Order updated successfully.');

        return response()->json($updatedModels);
    }
}
