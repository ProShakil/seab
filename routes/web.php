<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ParameterController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Route::get('/', function () {
//     return Inertia::render('Home', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });
Route::get('/', [ParameterController::class, 'front_page'])->name('homepage');
Route::get('/about/{param?}', [ParameterController::class, 'about_section'])->name('about');
Route::get('/committee-details/{param?}/{id?}', [ParameterController::class, 'committee_details'])->name('committee.details');
Route::get('/member/{id}', [ParameterController::class, 'memberProfile'])->name('member.profile');
Route::get('/member-list', [ParameterController::class, 'memberList'])->name('member.list');
Route::get('/gallery-list', [ParameterController::class, 'gallerylist'])->name('gallery.list');
Route::get('/contact', [ParameterController::class, 'contact'])->name('contact');
Route::post('/contact', [ParameterController::class, 'storeContact']);
// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/dashboard', [ProfileController::class, 'view'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/admin_acess', function () {
    return Inertia::render('AdminView');
})->middleware(['auth', 'verified'])->name('admin_acess');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/committee-names', [ParameterController::class, 'index']);
    Route::post('/committee-names', [ParameterController::class, 'store']);
    Route::put('/committee-names/{id}', [ParameterController::class, 'update']);
    Route::put('/committee-names/{id}/toggle', [ParameterController::class, 'toggle'])->name('admin.committee-names.toggle');
    Route::delete('/committee-names/{id}', [ParameterController::class, 'destroy']);

    Route::post('/add-member', [ParameterController::class, 'addMember']);
    Route::post('/remove-member/{id}', [ParameterController::class, 'removeMember']);


    Route::get('/committee-designation', [ParameterController::class, 'designation_index']);
    Route::post('/committee-designation', [ParameterController::class, 'designation_store']);
    Route::put('/committee-designation/{id}', [ParameterController::class, 'designation_update']);
    Route::put('/committee-designation/{id}/toggle', [ParameterController::class, 'designation_toggle'])->name('admin.committee-designation.toggle');
    Route::delete('/committee-designation/{id}', [ParameterController::class, 'designation_destroy']);

    Route::get('/membership-type', [ParameterController::class, 'membership_index']);
    Route::post('/membership-type', [ParameterController::class, 'membership_store']);
    Route::put('/membership-type/{id}', [ParameterController::class, 'membership_update']);
    Route::put('/membership-type/{id}/toggle', [ParameterController::class, 'membership_toggle'])->name('admin.membership-type.toggle');
    Route::delete('/membership-type/{id}', [ParameterController::class, 'membership_destroy']);

    Route::get('/occupation', [ParameterController::class, 'occupation_index']);
    Route::post('/occupation', [ParameterController::class, 'occupation_store']);
    Route::put('/occupation/{id}', [ParameterController::class, 'occupation_update']);
    Route::put('/occupation/{id}/toggle', [ParameterController::class, 'occupation_toggle'])->name('admin.occupation.toggle');
    Route::delete('/occupation/{id}', [ParameterController::class, 'occupation_destroy']);

    Route::get('/relationship', [ParameterController::class, 'relationship_index']);
    Route::post('/relationship', [ParameterController::class, 'relationship_store']);
    Route::put('/relationship/{id}', [ParameterController::class, 'relationship_update']);
    Route::put('/relationship/{id}/toggle', [ParameterController::class, 'relationship_toggle'])->name('admin.relationship.toggle');
    Route::delete('/relationship/{id}', [ParameterController::class, 'relationship_destroy']);

    Route::get('/technology', [ParameterController::class, 'technology_index']);
    Route::post('/technology', [ParameterController::class, 'technology_store']);
    Route::put('/technology/{id}', [ParameterController::class, 'technology_update']);
    Route::put('/technology/{id}/toggle', [ParameterController::class, 'technology_toggle'])->name('admin.technology.toggle');
    Route::delete('/technology/{id}', [ParameterController::class, 'technology_destroy']);

    Route::get('/users', [ParameterController::class, 'user_list']);
    Route::put('/user-update/{id}', [ParameterController::class, 'user_update'])->name('users.update');
    Route::get('/users/show/{id}', [ParameterController::class, 'memberProfile'])->name('users.show');

    Route::get('/front-message', [ParameterController::class, 'front_index']);
    Route::post('/front-message', [ParameterController::class, 'frontMessagestore']);

    Route::get('/gallery', [ParameterController::class, 'galleryIndex']);
    Route::post('/gallery/photos', [ParameterController::class, 'storePhoto']);
    Route::post('/gallery/video', [ParameterController::class, 'storeVideo']);
    Route::delete('/gallery/{id}', [ParameterController::class, 'destroyGallery']);

    // Blog
    Route::get('/blog', [ParameterController::class, 'admin_blog'])->name('admin.blog.index');
    Route::get('blog/add_edit/{id?}', [ParameterController::class, 'addEdit'])->name('admin.blog.create');
    Route::post('blog/store/{id?}', [ParameterController::class, 'blogStore'])->name('admin.blog.store');
    Route::post('blog/toggle/{id?}', [ParameterController::class, 'blogToggle'])->name('admin.blog.toggle');
    Route::post('blog/delete/{id?}', [ParameterController::class, 'blogDelete'])->name('admin.blog.delete');

    Route::get('/settings', [ParameterController::class, 'settings']);
    Route::post('/site-settings/update', [ParameterController::class, 'siteUpdate'])->name('site.settings.update');
    
    Route::get('/contact', [ParameterController::class, 'admin_contact']);
    Route::post('/contact/read', [ParameterController::class, 'markRead'])->name('admin.contact.read');
    Route::post('/contact/toggle', [ParameterController::class, 'contactToggle'])->name('admin.contact.toggle');
    Route::delete('/contact/{id}', [ParameterController::class, 'contactDelete'])->name('admin.contact.delete');

});

Route::get('/profilelayout', function () {
    return Inertia::render('ProfileView');
})->middleware(['auth', 'verified'])->name('profile-view');

Route::prefix('user')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'view'])->name('user.profile');
    Route::get('/membership-form', [ProfileController::class, 'membership_form']);
    Route::get('/change-password', [ProfileController::class, 'update_password']);
});

require __DIR__.'/auth.php';
