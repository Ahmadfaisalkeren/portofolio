<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Contact::latest()->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    $url = asset('storage/' . $row->image); // Replace 'image' with your image column name
                    return '<img src="' . $url . '" width="100px" />';
                })
                ->addColumn('action', function ($data) {
                    $deleteBtn = '<button onclick="deleteData(`' . route('contact.destroy', $data->id) . '`)" class="btn btn-sm mt-1 btn-danger">Delete</button>';

                    $editBtn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $data->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm mt-1 editContact">Edit</a>';

                    return $editBtn . ' ' . $deleteBtn;
                })
                ->rawColumns(['image', 'action'])
                ->make(true);
        }

        return view('backend.contact.index');
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
            'contact' => 'required|string',
            'link' => 'required|string',
            'image' => 'required|image|mimes:jpg,png,jpeg,svg|max:3000',
        ]);

        $image = $request->file('image');
        $imageName = time() . '.' . $image->getClientOriginalExtension();

        $imagePath = $image->storeAs('images/contact', $imageName, 'public');

        Contact::create([
            'id' => $request->contact_id,
            'contact' => $request->contact,
            'link' => $request->link,
            'image' => $imagePath,
        ]);

        return response()->json(['success' => 'Record Saved Successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $contact = Contact::find($id);
        return response()->json($contact);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contact $contact)
    {
        try {
            $request->validate([
                'contact' => 'nullable|string|max:255',
                'link' => 'nullable|string|max:255',
                'image' => 'nullable|image|mimes:jpg,png,jpeg,svg|max:3000',
            ]);

            $contact->contact = $request->input('contact');
            $contact->link = $request->input('link');

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('public/images/contact', $imageName);

                if ($contact->image) {
                    Storage::delete('public/' . $contact->image);
                }

                $contact->image = str_replace('public/', '', $imagePath);
            }

            $contact->save();

            return response()->json(['success' => 'Record updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error updating record: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);

        if ($contact->image) {
            Storage::delete('public/' . $contact->image);
        }

        $contact->delete();

        return response()->json(['success' => 'Record deleted successfully.']);
    }
}
