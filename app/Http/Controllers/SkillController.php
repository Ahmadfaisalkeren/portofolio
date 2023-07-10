<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class SkillController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Skill::latest()->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    $url = asset('storage/' . $row->image); // Replace 'image' with your image column name
                    return '<img src="' . $url . '" width="100px" />';
                })
                ->addColumn('action', function ($data) {
                    $deleteBtn = '<button onclick="deleteData(`' . route('skills.destroy', $data->id) . '`)" class="btn btn-sm mt-1 btn-danger">Delete</button>';

                    $editBtn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $data->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm mt-1 editSkill">Edit</a>';

                    return $editBtn . ' ' . $deleteBtn;
                })
                ->rawColumns(['image', 'action'])
                ->make(true);
        }

        return view('backend.skills.index');
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
            'skill' => 'required|string',
            'skill_detail' => 'required|string',
            'image' => 'required|image|mimes:jpg,png,jpeg,svg|max:3000',
        ]);

        $image = $request->file('image');
        $imageName = time() . '.' . $image->getClientOriginalExtension();

        $imagePath = $image->storeAs('images/skill', $imageName, 'public');

        Skill::create([
            'id' => $request->skill_id,
            'skill' => $request->skill,
            'skill_detail' => $request->skill_detail,
            'image' => $imagePath,
        ]);

        return response()->json(['success' => 'Record Saved Successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Skill $skill)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $skill = Skill::find($id);
        return response()->json($skill);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Skill $skill)
    {
        try {
            $request->validate([
                'skill' => 'nullable|string|max:255',
                'skill_detail' => 'nullable|string|max:255',
                'image' => 'nullable|image|mimes:jpg,png,jpeg,svg|max:3000',
            ]);

            $skill->skill = $request->input('skill');
            $skill->skill_detail = $request->input('skill_detail');

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('public/images/skill', $imageName);

                if ($skill->image) {
                    Storage::delete('public/' . $skill->image);
                }

                $skill->image = str_replace('public/', '', $imagePath);
            }

            $skill->save();

            return response()->json(['success' => 'Record updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error updating record: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $skill = Skill::findOrFail($id);

        if ($skill->image) {
            Storage::delete('public/' . $skill->image);
        }

        $skill->delete();

        return response()->json(['success' => 'Record deleted successfully.']);
    }
}
