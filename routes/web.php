<?php
use App\Http\Controllers\PostController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PostController::class, 'students'])->name('posts.index');

Route::get('/dashboard', function () {
    return redirect('/');
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

    // Portfolio routes
    // Optioneel index indien gebruikt
    // Route::get('/portfolios', [PortfolioController::class, 'index'])->name('portfolios.index');
    Route::get('/portfolios/create', [PortfolioController::class, 'create'])->name('portfolios.create');
    Route::post('/portfolios', [PortfolioController::class, 'store'])->name('portfolios.store');
    Route::get('/portfolios/{portfolio}/edit', [PortfolioController::class, 'edit'])->name('portfolios.edit');
    Route::put('/portfolios/{portfolio}', [PortfolioController::class, 'update'])->name('portfolios.update');
    Route::delete('/portfolios/{portfolio}', [PortfolioController::class, 'destroy'])->name('portfolios.destroy');
    Route::post('/portfolios/reorder', [PortfolioController::class, 'reorder'])->name('portfolios.reorder');
    Route::post('/portfolios/{portfolio}/location', [PortfolioController::class, 'updateLocation'])->name('portfolios.updateLocation');
    Route::post('/portfolios/reset-all', [PortfolioController::class, 'resetAllToOverview'])->name('portfolios.resetAll');

    // Classrooms
    Route::post('/classrooms', [ClassroomController::class, 'store'])->name('classrooms.store');
    Route::put('/classrooms/{classroom}', [ClassroomController::class, 'update'])->name('classrooms.update');
    Route::delete('/classrooms/{classroom}', [ClassroomController::class, 'destroy'])->name('classrooms.destroy');
    Route::post('/classrooms/{classroom}/assign', [ClassroomController::class, 'assignStudent'])->name('classrooms.assign');
    Route::post('/classrooms/{classroom}/remove', [ClassroomController::class, 'removeStudent'])->name('classrooms.remove');
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
