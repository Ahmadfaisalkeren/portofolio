<?php

namespace App\Http\Controllers;

use App\Models\About;
use App\Models\Certificate;
use App\Models\Contact;
use App\Models\Home;
use App\Models\Project;
use App\Models\Skill;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function index()
    {
        $home = Home::all();
        $about = About::all();
        $certificate = Certificate::all();
        $contact = Contact::all();
        $project = Project::all();
        $skill = Skill::all();

        return view('frontend.index', compact('home', 'about', 'certificate', 'contact', 'project', 'skill'));
    }
}
