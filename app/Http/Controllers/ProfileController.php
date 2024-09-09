<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Course;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use phpDocumentor\Reflection\Types\Integer;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $data = $request->validated();
        if (is_null($data['lastname'])) {
            unset($data['lastname']);
        }
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }





    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = $request->user();

        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $path = $avatar->store('avatars', 'public');

            // Update user's avatar path in the database
            $user->avatar = $path;
            $user->save();
        }

        return Inertia::render('Profile/Edit', [
            'message' => $request->hasFile('avatar') ? 'Avatar uploaded successfully!' : 'No file uploaded.',
            'auth' => [
                'user' => $user->only(['id', 'name', 'email', 'avatar']), // Ensure avatar is passed
            ],
        ]);
    }
    public function updateImage(Request $request)
    {
        $data = $request->validate([
            'cover'=> ['nullable', 'image'],
            'avatar'=> ['nullable', 'image']
        ]);
        $user = $request->user();
        $avatar = $data['avatar'] ?? null;
        /**@var UploadedFile $cover*/
        $cover = $data['cover'] ?? null;

        $success = '';
        if ($cover){
            if ($user->cover_path){
                Storage::disk('public')->delete($user->cover_path);
            }
            $path = $cover->store('user-'.$user->id, 'public');
            $user->update(['cover_path'=> $path]);
            $success = 'Your new Cover Image has been saved.';
        }

        if ($avatar){
            if ($user->avatar_path){
                Storage::disk('public')->delete($user->avatar_path);
            }
            $path = $avatar->store('user-'.$user->id, 'public');
            $user->update(['avatar_path'=> $path]);
            $success = 'Your new Avatar Image has been saved.';
        }


        session('success', 'Cover Image saved');
        return back()->with('success', $success);
    }


    public function profilePage($id)
    {
        $currentUser = Auth::user();

        $user = User::with([
            'achievements' => function ($query) {
                $query->with('course'); // Eager load course data for achievements
            },
            'purchasedCourses' => function ($query) {
                $query->with('teacher'); // Eager load teacher data for purchased courses
            },
            'engagements' => function ($query) {
                $query->with('course'); // Eager load course data for engagements
            },
            'comments' => function ($query) {
                $query->with('lesson', 'likes') // Eager load lesson data and likes for comments
                ->latest() // Order by latest comments
                ->take(5);
            },

        ])->findOrFail($id);


        $commentCount = $user->comments()->count();
        $purchasedCoursesCount = $user->purchasedCourses()->count();








        $registrationDate = $user->created_at->timezone('UTC');
        $currentDate = Carbon::now('UTC');

// Calculate the difference in days and ensure it's positive
        $daysSinceRegistration = abs($currentDate->diffInDays($registrationDate));
















        // For users with the "teacher" role, count the number of created courses
        $createdCoursesCount = 0;
        if ($user->role === 'teacher') {
            $createdCoursesCount = Course::where('teacher_id', $user->id)->count();
        }

        $statistics = [
            'commentCount' => $user->comments()->count(),
            'completedLessonsCount' => $user->courses()->wherePivot('completed', true)->count(),
            'purchasedCoursesCount' => $user->purchasedCourses()->count(),
            'registrationDate' => $registrationDate->format('Y-m-d'),
            'daysSinceRegistration' => $daysSinceRegistration, // This should be an integer
            'createdCoursesCount' => $user->courses()->count(),
        ];

        return Inertia::render('UserProfile', [
            'user' => $user,
            'authenticated' => (bool)$currentUser,
            'currentUser' => $currentUser ?: null,
            'statistics' => $statistics,
        ]);
    }
}
