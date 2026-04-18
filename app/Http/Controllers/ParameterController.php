<?php
namespace App\Http\Controllers;
use App\Models\CommitteeName;
use App\Models\Committee;
use App\Models\CommitteeDesignation;
use App\Models\MembershipType;
use App\Models\Occupation;
use App\Models\Relationship;
use App\Models\Technology;
use App\Models\Contact;
use App\Models\User;
use App\Models\FrontMessage;
use App\Models\Gallery;
use App\Models\BlogPost;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ParameterController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $committeeNames = CommitteeName::when($search, function ($q) use ($search) {
                $q->where('name', 'like', "%$search%");
            })
            ->with(['members.user', 'members.designation'])
            ->paginate(10)
            ->withQueryString();

        $users = User::select('id', 'name')->get();
        $designations = CommitteeDesignation::select('id', 'name')->get();

        return inertia('Admin/CommitteeName/Index', [
            'data' => $committeeNames,
            'filters' => ['search' => $search],
            'users' => $users,
            'designations' => $designations,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required']);

        CommitteeName::create([
            'name' => $request->name,
            'data_status' => 1,
        ]);
        Cache::forget('committees_cache');
        Artisan::call('cache:clear');
        return back()->with('success', 'Created successfully');
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required']);

        CommitteeName::findOrFail($id)->update([
            'name' => $request->name,
        ]);
        Cache::forget('committees_cache');
        Artisan::call('cache:clear');

        return back()->with('success', 'Updated successfully');
    }

    public function toggle($id)
    {
        $item = CommitteeName::findOrFail($id);
        $item->data_status = $item->data_status == 1 ? 0 : 1;
        $item->save();
        Cache::forget('committees_cache');
        Artisan::call('cache:clear');

        return back()->with('success', 'Status updated');
    }

    public function destroy($id)
    {
        CommitteeName::findOrFail($id)->delete();
        Cache::forget('committees_cache');
        Artisan::call('cache:clear');

        return back()->with('success', 'Deleted successfully');
    }

    // Committee List
    public function addMember(Request $request)
    {
        $request->validate([
            'committee_name_id' => 'required|exists:committee_names,id',
            'user_id' => [
                'required',
                Rule::unique('committees')
                    ->where(fn ($q) =>
                        $q->where('committee_name_id', $request->committee_name_id)
                    )
            ],
            'designation_id' => 'required|exists:committee_designations,id',
        ]);

        Committee::create([
            'committee_name_id' => $request->committee_name_id,
            'user_id' => $request->user_id,
            'designation_id' => $request->designation_id,
        ]);

        Cache::forget('committees_cache');
        Artisan::call('cache:clear');

        return back()->with('success', 'Member added');
    }

    public function removeMember($id)
    {
        Committee::findOrFail($id)->delete();
        Cache::forget('committees_cache');
        Artisan::call('cache:clear');
        return back()->with('success', 'Member removed');
    }

    // Designation

    public function designation_index(Request $request)
    {
        $query = committeeDesignation::query();

        // SEARCH
        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        return Inertia::render('Admin/CommitteeDesignation/Index', [
            'data' => $query->orderBy('id', 'asc')->paginate(10)->withQueryString(),
            'filters' => $request->only(['search']),
        ]);
    }

    public function designation_store(Request $request)
    {
        $request->validate(['name' => 'required']);

        committeeDesignation::create([
            'name' => $request->name,
            'data_status' => 1,
        ]);

        return back()->with('success', 'Created successfully');
    }

    public function designation_update(Request $request, $id)
    {
        $request->validate(['name' => 'required']);

        committeeDesignation::findOrFail($id)->update([
            'name' => $request->name,
        ]);

        return back()->with('success', 'Updated successfully');
    }

    public function designation_toggle($id)
    {
        $item = committeeDesignation::findOrFail($id);
        $item->data_status = $item->data_status == 1 ? 0 : 1;
        $item->save();

        return back()->with('success', 'Status updated');
    }

    public function designation_destroy($id)
    {
        committeeDesignation::findOrFail($id)->delete();

        return back()->with('success', 'Deleted successfully');
    }

    // Membership

    public function membership_index(Request $request)
    {
        $query = MembershipType::query();

        // SEARCH
        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        return Inertia::render('Admin/MembershipType/Index', [
            'data' => $query->orderBy('id', 'asc')->paginate(10)->withQueryString(),
            'filters' => $request->only(['search']),
        ]);
    }

    public function membership_store(Request $request)
    {
        $request->validate(['name' => 'required']);

        MembershipType::create([
            'name' => $request->name,
            'data_status' => 1,
        ]);

        return back()->with('success', 'Created successfully');
    }

    public function membership_update(Request $request, $id)
    {
        $request->validate(['name' => 'required']);

        MembershipType::findOrFail($id)->update([
            'name' => $request->name,
        ]);

        return back()->with('success', 'Updated successfully');
    }

    public function membership_toggle($id)
    {
        $item = MembershipType::findOrFail($id);
        $item->data_status = $item->data_status == 1 ? 0 : 1;
        $item->save();

        return back()->with('success', 'Status updated');
    }

    public function membership_destroy($id)
    {
        MembershipType::findOrFail($id)->delete();

        return back()->with('success', 'Deleted successfully');
    }

    // Occupation

    public function occupation_index(Request $request)
    {
        $query = Occupation::query();

        // SEARCH
        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        return Inertia::render('Admin/Occupation/Index', [
            'data' => $query->orderBy('id', 'asc')->paginate(10)->withQueryString(),
            'filters' => $request->only(['search']),
        ]);
    }

    public function occupation_store(Request $request)
    {
        $request->validate(['name' => 'required']);

        Occupation::create([
            'name' => $request->name,
            'data_status' => 1,
        ]);

        return back()->with('success', 'Created successfully');
    }

    public function occupation_update(Request $request, $id)
    {
        $request->validate(['name' => 'required']);

        Occupation::findOrFail($id)->update([
            'name' => $request->name,
        ]);

        return back()->with('success', 'Updated successfully');
    }

    public function occupation_toggle($id)
    {
        $item = Occupation::findOrFail($id);
        $item->data_status = $item->data_status == 1 ? 0 : 1;
        $item->save();

        return back()->with('success', 'Status updated');
    }

    public function occupation_destroy($id)
    {
        Occupation::findOrFail($id)->delete();

        return back()->with('success', 'Deleted successfully');
    }

    // Relationship

    public function relationship_index(Request $request)
    {
        $query = Relationship::query();

        // SEARCH
        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        return Inertia::render('Admin/Relationship/Index', [
            'data' => $query->orderBy('id', 'asc')->paginate(10)->withQueryString(),
            'filters' => $request->only(['search']),
        ]);
    }

    public function relationship_store(Request $request)
    {
        $request->validate(['name' => 'required']);

        Relationship::create([
            'name' => $request->name,
            'data_status' => 1,
        ]);

        return back()->with('success', 'Created successfully');
    }

    public function relationship_update(Request $request, $id)
    {
        $request->validate(['name' => 'required']);

        Relationship::findOrFail($id)->update([
            'name' => $request->name,
        ]);

        return back()->with('success', 'Updated successfully');
    }

    public function relationship_toggle($id)
    {
        $item = Relationship::findOrFail($id);
        $item->data_status = $item->data_status == 1 ? 0 : 1;
        $item->save();

        return back()->with('success', 'Status updated');
    }

    public function relationship_destroy($id)
    {
        Relationship::findOrFail($id)->delete();

        return back()->with('success', 'Deleted successfully');
    }

    // Technology

    public function technology_index(Request $request)
    {
        $query = Technology::query();

        // SEARCH
        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        return Inertia::render('Admin/Technology/Index', [
            'data' => $query->orderBy('id', 'asc')->paginate(10)->withQueryString(),
            'filters' => $request->only(['search']),
        ]);
    }

    public function technology_store(Request $request)
    {
        $request->validate(['name' => 'required']);

        Technology::create([
            'name' => $request->name,
            'data_status' => 1,
        ]);

        return back()->with('success', 'Created successfully');
    }

    public function technology_update(Request $request, $id)
    {
        $request->validate(['name' => 'required']);

        Technology::findOrFail($id)->update([
            'name' => $request->name,
        ]);

        return back()->with('success', 'Updated successfully');
    }

    public function technology_toggle($id)
    {
        $item = Technology::findOrFail($id);
        $item->data_status = $item->data_status == 1 ? 0 : 1;
        $item->save();

        return back()->with('success', 'Status updated');
    }

    public function technology_destroy($id)
    {
        Technology::findOrFail($id)->delete();

        return back()->with('success', 'Deleted successfully');
    }

    // Users Form

    public function user_list(Request $request)
    {
        $query = User::with(['technology']);

        // SEARCH
        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        return Inertia::render('Admin/Users/Index', [
            'users' => $query->orderBy('id', 'asc')->paginate(10)->withQueryString(),
            'filters' => $request->only(['search']),
            'membershipTypes' => MembershipType::where('data_status', 1)->get(),
        ]);
    }

    public function user_update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'membership_type_id' => 'required',
            'status' => 'required',
        ]);

        $user->membership_type_id = $request->membership_type_id;
        $user->data_status = $request->status;

        // Admin access
        $user->is_admin = $request->is_admin ? 1 : 0;
        $user->admin_role_id = $request->admin_role;

        if (!$user->membership_id && $user->membership_type_id) {
            $user->membership_id = $this->generateMembershipNumber();
        }

        $user->save();

        return back()->with('success', 'User updated successfully');
    }

    private function generateMembershipNumber()
    {
        $prefix = now()->format('Ym'); // 202604

        // Get last number for same month
        $last = User::where('membership_id', 'like', $prefix . '%')
            ->orderBy('membership_id', 'desc')
            ->first();

        if (!$last) {
            return $prefix . '0001';
        }

        // Extract last 4 digits
        $lastNumber = (int) substr($last->membership_id, -4);

        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        return $prefix . $newNumber;
    }

    // Front Message
    public function front_index()
    {
        $data = FrontMessage::first(); // single row

        return inertia('Admin/FrontMessage/Index', [
            'data' => $data
        ]);
    }

    public function frontMessagestore(Request $request)
    {
        $data = FrontMessage::first();

        if (!$data) {
            FrontMessage::create($request->all());
        } else {
            $data->update($request->all());
        }

        return back()->with('success', 'Updated successfully');
    }

    // Gallery Section
    public function galleryIndex()
    {
        return inertia('Admin/Gallery/Index', [
           'galleries' => Gallery::latest()->paginate(12)
        ]);
    }

    /* ---------------- PHOTO UPLOAD ---------------- */
    public function storePhoto(Request $request)
    {
        $request->validate([
            'images' => 'required',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        if (!$request->hasFile('images')) {
            return back()->withErrors(['images' => 'No images uploaded']);
        }

        foreach ($request->file('images') as $file) {

            $path = $file->store('gallery', 'public');

            Gallery::create([
                'type' => 'photo',
                'image' => $path
            ]);
        }

        return back()->with('success', 'Uploaded successfully');
    }

    /* ---------------- VIDEO (YOUTUBE) ---------------- */
    public function storeVideo(Request $request)
    {
        $request->validate([
            'video_url' => 'required|url'
        ]);

        $embed = $this->convertYoutube($request->video_url);

        Gallery::create([
            'type' => 'video',
            'video_url' => $request->video_url,
            'embed_url' => $embed
        ]);

        return back();
    }

    /* ---------------- DELETE ---------------- */
    public function destroyGallery($id)
    {
        Gallery::findOrFail($id)->delete();

        return back();
    }

    /* ---------------- YOUTUBE CONVERTER ---------------- */
    private function convertYoutube($url)
    {
        preg_match(
            '/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/|v\/|shorts\/))([\w-]+)/',
            $url,
            $matches
        );

        $id = $matches[1] ?? null;

        return $id ? "https://www.youtube.com/embed/$id" : null;
    }

    // Blog
    public function admin_blog()
    {
        return inertia('Admin/Blog/Index', [
           'blogs' => BlogPost::with('user')->latest()->paginate(12)
        ]);
    }
    public function addEdit($id = null)
    {
        $blog = null;

        if ($id) {
            $blog = BlogPost::findOrFail($id);
        }

        return Inertia::render('Admin/Blog/Create', [
            'blog' => $blog
        ]);
    }

    public function blogStore(Request $request, $id = null)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);

        // INSERT or UPDATE
        $blog = $id ? BlogPost::findOrFail($id) : new BlogPost();

        $blog->title = $request->title;

        // slug from hidden translated field
        $slug = $request->slug ?? Str::slug($request->title);
        $blog->slug = $slug ?: 'blog-' . time();

        $blog->content = $request->content;
        $blog->excerpt = Str::limit(strip_tags($request->content), 120);
        $blog->status = 1;
        $blog->user_id = auth()->id();

        // 📸 thumbnail handling
        if ($request->hasFile('thumbnail')) {

            // delete old image if update
            if ($blog->thumbnail && Storage::disk('public')->exists($blog->thumbnail)) {
                Storage::disk('public')->delete($blog->thumbnail);
            }

            $path = $request->file('thumbnail')->store('thumbnail', 'public');
            $blog->thumbnail = $path;
        }

        $blog->save();

        return redirect()->route('admin.blog.index');
    }

    public function blogToggle(Request $request)
    {
        $item = BlogPost::findOrFail($request->id);
        $item->status = $item->status == 1 ? 0 : 1;
        $item->save();
        return back()->with('success', 'Status updated');
    }

    public function blogDelete($id)
    {
        $blog = BlogPost::findOrFail($id);
        if ($blog->thumbnail && \Storage::disk('public')->exists($blog->thumbnail)) {
            \Storage::disk('public')->delete($blog->thumbnail);
        }
        $blog->delete();        
        return back();
    }
    // Settings

    public function settings()
    {
        return inertia('Admin/Settings/Index', [
            'setting' => SiteSetting::first()
        ]);
    }

    public function siteUpdate(Request $request)
    {
        $setting = SiteSetting::first(); // assuming single row

        $request->validate([
            'site_title' => 'required|string',
            'headline' => 'nullable|string',
            'subtitle' => 'nullable|string',
            'logo' => 'nullable|image',
            'favicon' => 'nullable|image',
        ]);

        // LOGO
        if ($request->hasFile('logo')) {

            if ($setting->logo && Storage::disk('public')->exists($setting->logo)) {
                Storage::disk('public')->delete($setting->logo);
            }

            $setting->logo = $request->file('logo')->store('site/logo', 'public');
        }

        // FAVICON
        if ($request->hasFile('favicon')) {

            if ($setting->favicon && Storage::disk('public')->exists($setting->favicon)) {
                Storage::disk('public')->delete($setting->favicon);
            }

            $setting->favicon = $request->file('favicon')->store('site/favicon', 'public');
        }

        $setting->site_title = $request->site_title;
        $setting->headline = $request->headline;
        $setting->subtitle = $request->subtitle;

        $setting->save();
        Cache::forget('siteSettings');
        Artisan::call('cache:clear');

        return back()->with('success', 'Settings updated successfully');
    }

    // Contact
    public function admin_contact()
    {
        return inertia('Admin/Contact/Index', [
           'contacts' => Contact::latest()->paginate(12)
        ]);
    }

    public function markRead(Request $request)
    {
        Contact::where('id', $request->id)->update(['view_status' => 1]);
        return back();
    }

    public function contactToggle(Request $request)
    {
        $c = Contact::findOrFail($request->id);
        $c->view_status = $c->view_status == 0 ? 1 : 0;
        $c->save();

        return back();
    }

    public function contactDelete($id)
    {
        Contact::findOrFail($id)->delete();
        return back();
    }

    
    // Front Controller

    public function front_page(Request $request)
    {
        $frontMessages = DB::table('front_messages')
        ->select(
            'president_message',
            'vice_president_message',
            'mission',
            'vision'
        )
        ->latest()
        ->first();
        $committees = DB::table('committees as c')
            ->select(
                'c.id',
                'c.designation_id',
                'c.user_id',
                'u.name as user_name',
                'u.profile_image'
            )
            ->join('users as u', 'u.id', '=', 'c.user_id')
            ->where('c.committee_name_id', 1)
            ->whereIn('c.designation_id', [1, 2])
            ->whereRaw("
                c.id IN (
                    SELECT MIN(id)
                    FROM committees
                    WHERE committee_name_id = 1
                    AND designation_id IN (1,2)
                    GROUP BY designation_id
                )
            ")
        ->get();
        $type = $request->type;
        $query = Gallery::latest();
        if ($type === 'photo' || $type === 'video') {
            $query->where('type', $type);
        }
        return Inertia::render('Home', [
            'frontMessages' => $frontMessages,
            'committees' => $committees,
            'galleries' => $query->take(12)->get(),
            'activeFilter' => $type ?? 'all',
        ]);
    }

    public function about_section($param)
    {
        $allowed = [
            'president_message',
            'vice_president_message',
            'mission',
            'vision',
            'about_seab',
            'membership_process'
        ];

        if (!in_array($param, $allowed)) {
            abort(404);
        }

        $meta = [
            'president_message' => [
                'title' => 'President Message',
                'subtitle' => 'Leadership message from SEAB President'
            ],
            'vice_president_message' => [
                'title' => 'Vice President Message',
                'subtitle' => 'Message from Vice President'
            ],
            'mission' => [
                'title' => 'Our Mission',
                'subtitle' => 'What drives SEAB forward'
            ],
            'vision' => [
                'title' => 'Our Vision',
                'subtitle' => 'Future goals of SEAB'
            ],
            'about_seab' => [
                'title' => 'About SEAB',
                'subtitle' => 'Sarishabari Engineers Association Bangladesh'
            ],
            'membership_process' => [
                'title' => 'Membership Process',
                'subtitle' => 'How to become a member'
            ],
        ];
        
        
        $frontMessages = DB::table('front_messages')
        ->select($param)
        ->latest()
        ->first();
        if (!$frontMessages || !isset($frontMessages->$param)) {
            abort(404);
        }
        return Inertia::render('About', [
            'frontMessages' => $frontMessages->$param,
            'meta' => $meta[$param] ?? null
        ]);
    }

    public function committee_details($param,$id)
    {

       $committees = Committee::with([
            'user:id,name,profile_image',
            'designation:id,name',
            'committeeName:id,name'
        ])
        ->where('committee_name_id', $id)
        ->get();

        return Inertia::render('Committee', [
            'committees' => $committees,
            'committee_name' => Str::title(str_replace('-', ' ', $param))
        ]);
    }

    public function memberList(Request $request)
    {
        $query = User::select(
            'id',
            'name',
            'membership_id',
            'membership_type_id',
            'designation',
            'employer_name',
            'tech_id',
            'profile_image'
        )
        ->with([
            'membershipType:id,name',
            'technology:id,name'
        ]);

        

        // 🔍 Search
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                ->orWhere('designation', 'like', "%{$request->search}%")
                ->orWhere('employer_name', 'like', "%{$request->search}%");
            });
        }

        // 🎯 Technology filter
        if ($request->technology_id) {
            $query->where('tech_id', $request->technology_id);
        }

        // 🪪 Membership filter
        if ($request->membership_type_id) {
            $query->where('membership_type_id', $request->membership_type_id);
        }

        return Inertia::render('MemberList', [
            'memberlist' => $query->paginate(12)->withQueryString(),

            'membershipTypes' => MembershipType::select('id','name')->get(),
            'technologies' => Technology::select('id','name')->get(),

            'filters' => $request->only([
                'search',
                'technology_id',
                'membership_type_id'
            ])
        ]);
    }

    public function memberProfile($id)
    {
        $user = User::with([
            'occupation',
            'membershipType',
            'technology',
            'relationship'
        ])->findOrFail($id);

        return Inertia::render('Profile', [
            'user' => $user
        ]);
    }

    public function gallerylist(Request $request)
    {
        $type = $request->type;

        $query = Gallery::latest();

        if ($type === 'photo' || $type === 'video') {
            $query->where('type', $type);
        }

        return Inertia::render('Gallery', [
            'galleries' => $query->paginate(12)->withQueryString(),
            'activeFilter' => $type ?? 'all',
        ]);
    }

    public function contact()
    {
        return Inertia::render('Contact');
    }

    public function storeContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|regex:/^\+?[0-9]{7,15}$/',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'subject' => $request->subject,
            'message' => $request->message,
            'data_status' => 1,
            'view_status' => 0,
        ]);

        return back()->with('success', 'Message sent successfully!');
    }

}