<?php

namespace App\Http\Controllers;

use App\Models\About;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class AboutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = About::latest()->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    $url = asset('storage/' . $row->image); // Replace 'image' with your image column name
                    return '<img src="' . $url . '" width="100px" />';
                })
                ->addColumn('action', function ($data) {
                    $deleteBtn = '<button onclick="deleteData(`' . route('about.destroy', $data->id) . '`)" class="btn btn-sm mt-1 btn-danger">Delete</button>';

                    $editBtn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $data->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm mt-1 editAbout">Edit</a>';

                    return $editBtn . ' ' . $deleteBtn;
                })
                ->rawColumns(['image', 'action'])
                ->make(true);
        }

        return view('backend.about.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpg,png,jpeg,svg|max:3000',
        ]);

        $image = $request->file('image');
        $imageName = time() . '.' . $image->getClientOriginalExtension();

        $imagePath = $image->storeAs('images/about', $imageName, 'public');

        About::create([
            'id' => $request->about_id,
            'description' => $request->description,
            'image' => $imagePath,
        ]);

        return response()->json(['success' => 'Record Saved Successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(About $about)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $about = About::find($id);
        return response()->json($about);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, About $about)
    {
        try {
            $request->validate([
                'description' => 'nullable|string|max:255',
                'image' => 'nullable|image|mimes:jpg,png,jpeg,svg|max:3000',
            ]);

            $about->description = $request->input('description');

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('public/images/about', $imageName);

                if ($about->image) {
                    Storage::delete('public/' . $about->image);
                }

                $about->image = str_replace('public/', '', $imagePath);
            }

            $about->save();

            return response()->json(['success' => 'Record updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error updating record: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $about = About::findOrFail($id);

        if ($about->image) {
            Storage::delete('public/' . $about->image);
        }

        $about->delete();

        return response()->json(['success' => 'Record deleted successfully.']);
    }
}
