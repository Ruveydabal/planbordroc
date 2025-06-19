<?php
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PostController::class, 'students'])->name('posts.index');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{posts}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{posts}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{posts}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::post('/posts/{student}/location', [PostController::class, 'updateLocation'])->name('posts.updateLocation');
    Route::post('/posts/reset-all', [PostController::class, 'resetAllToOverview'])->name('posts.resetAll');
});

Route::get('/health', function () {
    try {
        $config = config('database.connections.pgsql');
        $connection = \DB::connection()->getPdo();
        
        // Test query om users te selecteren
        $users = \DB::table('users')->select('id', 'name', 'email')->limit(5)->get();
        
        return response()->json([
            'status' => 'healthy',
            'database' => [
                'connected' => true,
                'host' => $config['host'],
                'port' => $config['port'],
                'database' => $config['database'],
                'username' => $config['username'],
                'driver' => $config['driver']
            ],
            'test_query' => [
                'success' => true,
                'users_count' => $users->count(),
                'users' => $users
            ],
            'timestamp' => now()
        ]);
    } catch (\Exception $e) {
        $config = config('database.connections.pgsql');
        return response()->json([
            'status' => 'unhealthy',
            'database' => [
                'connected' => false,
                'host' => $config['host'],
                'port' => $config['port'],
                'database' => $config['database'],
                'username' => $config['username'],
                'driver' => $config['driver']
            ],
            'error' => $e->getMessage(),
            'timestamp' => now()
        ], 500);
    }
})->withoutMiddleware(['web', 'session']);

require __DIR__.'/auth.php';
