<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\FrontendCourseController;
use App\Http\Controllers\Moderator\CourseModerationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TeacherControlle;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UsersAuthController;
use Illuminate\Support\Facades\Route;



Route::get('/', [HomeController::class, 'index'])->name('home');
route::get('/lessons/{lessonId}/comments', [HomeController::class, 'getComments']);

Route::post('/login', [AuthController::class, 'login']);
Route::middleware(['auth:sanctum'])->group(function () {
    route::get('/lessons/{lessonId}/comments/authenticated', [HomeController::class, 'getCommentsForLesson']);

    route::put('/users/change-role/{id}', [UserController::class, 'updateRole']);

    Route::get('/analytics/course-engagement', [AnalyticsController::class, 'index']);

    Route::get('/analytics/financial-metrics', [AnalyticsController::class, 'financialMetrics']);
    Route::get('/analytics/dashboard-metrics', [AnalyticsController::class, 'getDashboardMetrics']);

});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/courses/{courseId}/lessons/{lessonId}/comments/{commentId}/toggle-like', [HomeController::class, 'toggleLike']);

//    Route::put('/courses/{id}', [CourseController::class, 'update']);
    Route::get('/teacher/courses', [CourseController::class, 'index']);
    Route::get('/courses/{id}', [CourseController::class, 'show']);
    Route::post('/courses', [CourseController::class, 'store']);
    Route::delete('/courses/{id}', [CourseController::class, 'destroy']);
    Route::get('/teacher-courses', [TeacherControlle::class, 'getOwnCourses']);
    Route::get('/course-enrollments/{courseId}', [TeacherControlle::class, 'getCourseEnrollments']);
    Route::get('/subscriptions', [TeacherControlle::class, 'getSubscriptions']);

    Route::get('/courses/{id}/lessons', [LessonController::class, 'fetchLessons']);
    Route::put('/lessons/{id}', [LessonController::class, 'update']);
    Route::get('/courses/{course}/lessons', [LessonController::class, 'getLessons']);
    Route::post('/courses/{course}/lessons', [LessonController::class, 'store']);

    Route::post('/tests/create', [LessonController::class, 'create'])->name('tests.create');
    Route::get('/tests/{id}', [LessonController::class, 'showTest'])->name('tests.showTest');
    Route::put('/tests/{id}', [LessonController::class, 'updateTest']);
    Route::get('/lessons/{lessonId}/questions', [LessonController::class, 'getQuestionsByCourse']);
    Route::put('/lessons/{lessonId}/questions', [LessonController::class, 'updateQuestionsForCourse'])->name('lessons.questions.update');
    Route::get('/reviews', [CourseModerationController::class, 'index']);

    Route::get('/comments', [CourseModerationController::class, 'getComment']);
    Route::put('/comments/{id}', [CourseModerationController::class, 'updateComment']);  // Route for editing a comment
    Route::delete('/comments/{id}', [CourseModerationController::class, 'destroyComment']);
    route::put('/reviews/{id}', [CourseModerationController::class, 'updateReview']);
    Route::delete('/reviews/{id}', [CourseModerationController::class, 'deleteReview']);

    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    Route::get('/user', [AuthController::class, 'getUser']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::put('/moderate/course/{id}', [CourseModerationController::class, 'editCourse']);
    Route::delete('/moderate/course/{id}', [CourseModerationController::class, 'deleteCourse']);

    // Lesson routes for moderator
    Route::put('/moderate/lesson/{id}', [CourseModerationController::class, 'editLesson']);
    Route::delete('/moderate/lesson/{id}', [CourseModerationController::class, 'deleteLesson']);

    // Comment routes for moderator
    Route::put('/moderate/comment/{id}', [CourseModerationController::class, 'editComment']);
    Route::delete('/moderate/comment/{id}', [CourseModerationController::class, 'deleteComment']);

    // User routes for moderator
    Route::put('/moderate/user/{id}', [CourseModerationController::class, 'editUser']);
    Route::delete('/moderate/user/{id}', [CourseModerationController::class, 'deleteUser']);

    Route::get('/admin/feedback', [FeedbackController::class, 'index'])->name('admin.feedback.index');
    Route::delete('/admin/feedback/{id}', [FeedbackController::class, 'destroy'])->name('admin.feedback.destroy');

//    TEACHER ROUTES
    Route::get('/courses/{id}/preview', [TeacherControlle::class, 'teacherPreview'])->name('teacherPreview');

    // Route to update a course
    Route::put('/courses/{id}', [TeacherControlle::class, 'teacherUpdate']) ;

    // Route to delete a course
    Route::delete('/courses/{id}', [TeacherControlle::class, 'teacherDestroy'])->name('teacherDestroy');
    Route::get('/teacher/dashboard-data', [TeacherControlle::class, 'getDashboardData']);
    Route::get('/student-performance', [TeacherControlle::class, 'showStudentPerformance']);

});






//FRONTEND END ROUTES
                        //FRONTEND END ROUTES
                                                //FRONTEND END ROUTES
                                                                        //FRONTEND END ROUTES



Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/save-comment', [FrontendCourseController::class, 'storeComment']);
    Route::post('/courses/{course}/lessons/{lesson}/comments', [FrontendCourseController::class, 'storeComment'])->name('createComment');
    Route::get('/api-private-courses/{courseId}', [FrontendCourseController::class, 'showCourse']);
    Route::post('/courses/{courseId}/lessons/{lessonId}/save-time', [HomeController::class, 'saveLessonTime'])->name('saveLessonTime');
    Route::post('/store-interactions/{courseId}', [HomeController::class, 'storeInteractions'])->name('storeInteractions');
//    Route::post('/courses/{courseId}/lessons/{lessonId}/comments/{commentId}/toggle-like', [HomeController::class, 'toggleLike'])
//        ->name('comments.toggleLike');
    Route::get('/get-profile-information', [UsersAuthController::class, 'getUserProfileData']);
    Route::patch('/profile/update', [UsersAuthController::class, 'updateUserProfile']);
    Route::post('/profile/updateImages', [UsersAuthController::class, 'uploadAvatar']);
    Route::post('/reviews', [ReviewController::class, 'store']);
    Route::post('/feedback', [FeedbackController::class, 'store']);
    Route::get('/bookmarks', [FrontendCourseController::class, 'showBookmark']);
    Route::post('/bookmarks/{course}', [FrontendCourseController::class, 'storeBookmark']);
    Route::delete('/bookmarks/{course}', [FrontendCourseController::class, 'destroyBookmark']);
    Route::get('/user/activities', [FrontendCourseController::class, 'getUserActivities']);
    Route::get('/user/latest-activities', [FrontendCourseController::class, 'getUserLatestActivities']);
    Route::get('/user/completed-courses', [FrontendCourseController::class, 'getCompletedCourses']);


    Route::post('/create-checkout-session', [SubscriptionController::class, 'createCheckoutSession']);
    Route::get('/success}', [SubscriptionController::class, 'success']);


});

Route::post('/stripe/webhook', [SubscriptionController::class, 'handleWebhook']);
Route::post('/login-user', [UsersAuthController::class, 'login']);
Route::post('/user-api/register', [UsersAuthController::class, 'register']);

Route::get('/api-course', [FrontendCourseController::class, 'getCourses']);
Route::get('/api-courses/{courseId}', [FrontendCourseController::class, 'showCourse']);
Route::get('/user/profile/{id}', [FrontendCourseController::class, 'profilePage']);
Route::get('/reviews', [ReviewController::class, 'index']);
Route::get('/course-catalog', [HomeController::class, 'search']);
Route::get('/courses-list', [FrontendCourseController::class, 'courseCatalog']);
