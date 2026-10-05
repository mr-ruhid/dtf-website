<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::orderByDesc('is_default')->orderBy('sort_order')->orderBy('name')->get();
        return view('admin.branch.index', compact('branches'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        $data['status'] = $request->boolean('status', true);
        $data['is_default'] = $request->boolean('is_default');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        if ($data['is_default']) {
            Branch::where('is_default', 1)->update(['is_default' => 0]);
        }

        Branch::create($data);

        return back()->with('status', 'Branch created successfully.');
    }

    public function update(Request $request, Branch $branch)
    {
        $data = $this->validateData($request, $branch->id);

        $data['status'] = $request->boolean('status');
        $data['is_default'] = $request->boolean('is_default');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        if ($data['is_default']) {
            Branch::where('is_default', 1)->where('id', '!=', $branch->id)->update(['is_default' => 0]);
        }

        $branch->update($data);

        return back()->with('status', 'Branch updated successfully.');
    }

    public function destroy(Branch $branch)
    {
        if ($branch->is_default) {
            return back()->withErrors(['error' => 'Cannot delete default branch.']);
        }

        $branch->delete();

        return back()->with('status', 'Branch deleted successfully.');
    }

    public function toggleStatus(Branch $branch)
    {
        $branch->update(['status' => !$branch->status]);
        return back()->with('status', 'Branch status updated.');
    }

    public function setDefault(Branch $branch)
    {
        Branch::where('is_default', 1)->update(['is_default' => 0]);
        $branch->update(['is_default' => 1, 'status' => 1]);

        return back()->with('status', 'Default branch updated.');
    }

    protected function validateData(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'code' => ['required', 'string', 'max:50', 'unique:branches,code' . ($ignoreId ? ',' . $ignoreId : '')],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:150'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:10'],
            'zip' => ['nullable', 'string', 'max:20'],
            'country' => ['nullable', 'string', 'max:5'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'boolean'],
            'is_default' => ['nullable', 'boolean'],
        ]);
    }
}
