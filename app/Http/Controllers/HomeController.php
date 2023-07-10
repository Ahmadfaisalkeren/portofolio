<?php

namespace App\Http\Controllers;

use App\Models\Home;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Home::latest()->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    $url = asset('storage/' . $row->image); // Replace 'image' with your image column name
                    return '<img src="' . $url . '" width="100px" />';
                })
                ->addColumn('action', function ($data) {
                    $deleteBtn = '<button onclick="deleteData(`' . route('home.destroy', $data->id) . '`)" class="btn btn-sm mt-1 btn-danger">Delete</button>';

                    $editBtn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $data->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm mt-1 editHome">Edit</a>';

                    return $editBtn . ' ' . $deleteBtn;
                })
                ->rawColumns(['image', 'action'])
                ->make(true);
        }

        return view('backend.home.index');
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
            'description' => 'required|string',
            'description_2' => 'required|string',
            'image' => 'required|image|mimes:jpg,png,jpeg,svg|max:5000',
        ]);

        $image = $request->file('image');
        $imageName = time() . '.' . $image->getClientOriginalExtension();

        $imagePath = $image->storeAs('images/home', $imageName, 'public');

        Home::create([
            'id' => $request->home_id,
            'description' => $request->description,
            'description_2' => $request->description_2,
            'image' => $imagePath,
        ]);

        return response()->json(['success' => 'Record Saved Successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Home $home)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $home = Home::find($id);
        return response()->json($home);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Home $home)
    {
        try {
            $request->validate([
                'description' => 'nullable|string|max:255',
                'description_2' => 'nullable|string|max:255',
                'image' => 'nullable|image|mimes:jpg,png,jpeg,svg|max:3000',
            ]);

            $home->description = $request->input('description');
            $home->description_2 = $request->input('description_2');

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('public/images/home', $imageName);

                if ($home->image) {
                    Storage::delete('public/' . $home->image);
                }

                $home->image = str_replace('public/', '', $imagePath);
            }

            $home->save();

            return response()->json(['success' => 'Record updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error updating record: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $home = Home::findOrFail($id);

        if ($home->image) {
            Storage::delete('public/' . $home->image);
        }

        $home->delete();

        return response()->json(['success' => 'Record deleted successfully.']);
    }
}
