<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Project::latest()->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    $url = asset('storage/' . $row->image); // Replace 'image' with your image column name
                    return '<img src="' . $url . '" width="100px" />';
                })
                ->addColumn('action', function ($data) {
                    $deleteBtn = '<button onclick="deleteData(`' . route('project.destroy', $data->id) . '`)" class="btn btn-sm mt-1 btn-danger">Delete</button>';

                    $editBtn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $data->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm mt-1 editProject">Edit</a>';

                    return $editBtn . ' ' . $deleteBtn;
                })
                ->rawColumns(['image', 'action'])
                ->make(true);
        }

        return view('backend.project.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $request->validate([
            'link' => 'required|string',
            'image' => 'required|image|mimes:jpg,png,jpeg,svg|max:3000',
        ]);

        $image = $request->file('image');
        $imageName = time() . '.' . $image->getClientOriginalExtension();

        $imagePath = $image->storeAs('images/project', $imageName, 'public');

        Project::create([
            'id' => $request->project_id,
            'link' => $request->link,
            'image' => $imagePath,
        ]);

        return response()->json(['success' => 'Record Saved Successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $project = Project::find($id);
        return response()->json($project);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        try {
            $request->validate([
                'link' => 'nullable|string|max:255',
                'image' => 'nullable|image|mimes:jpg,png,jpeg,svg|max:3000',
            ]);

            $project->link = $request->input('link');

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('public/images/project', $imageName);

                if ($project->image) {
                    Storage::delete('public/' . $project->image);
                }

                $project->image = str_replace('public/', '', $imagePath);
            }

            $project->save();

            return response()->json(['success' => 'Record updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error updating record: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $project = Project::findOrFail($id);

        if ($project->image) {
            Storage::delete('public/' . $project->image);
        }

        $project->delete();

        return response()->json(['success' => 'Record deleted successfully.']);
    }
}
