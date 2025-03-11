<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Cache;
use App\Models\Student;
Route::get('/', function () {
    return redirect('/admin/login');
});

Route::get('/students/view', function () {
    $key = 'students';

    if (!Redis::exists($key)) {
        $students = Student::all();
        // $students = Student::select('name', 'email')->get();
        Redis::setex($key, 600, json_encode($students)); // Store for 10 min
    } else {
        // $students = json_decode(Redis::get($key));
        $students = Redis::get($key);
    }

    // return response()->json($students);

    // return view('students', compact('students'));//screen hangs up every time
    // return view('studentsjs', compact('students'));//Speed Index=5.4 s to 5.7s
    // return view('studentsjs2', compact('students'));//Speed Index=5.3 s to 5.8s
    return view('studentsjsDocumentFragment', compact('students'));//Speed Index=3.6s to 4.4 s
});

Route::get('/students/query', function (Request $request) {
    // $key = 'students:' . md5(json_encode($request->all())); // Cache key based on query params
    $key = 'students:' . json_encode($request->all()); // Cache key based on query params

    if (!Redis::exists($key)) {
        $query = Student::query();

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        if ($request->has('email')) {
            $query->where('email', 'like', '%' . $request->input('email') . '%');
        }

        $students = $query->get();

        Redis::setex($key, 600, json_encode($students)); // Store for 10 min
    } else {
        $students = json_decode(Redis::get($key));
    }

    return view('studentsjs2', compact('students'));
});



Route::get('/addkeys', function () {
    Redis::set('nameq', 'dfsdf');
});


Route::get('/redirect', function (Request $request) {
    $request->session()->put('state', $state = Str::random(40));

    $query = http_build_query([
        'client_id' => '9e33522a-a3a5-44c4-9381-7586cab83169',
        'redirect_uri' => 'http://third-party-app.com/callback',
        'response_type' => 'code',
        'scope' => '',
        'state' => $state,
        // 'prompt' => '', // "none", "consent", or "login"
        'prompt' => 'login', // "none", "consent", or "login"
    ]);

    return redirect('http://passport-app.test/oauth/authorize?'.$query);
});

Route::get('/check-cache', function () {
    if (Cache::has('students')) {
        return response()->json([
            'cached' => true,
            'data' => Cache::get('students')
        ]);
    }
    return response()->json(['cached' => false]);
});

Route::get('/check-cache2', function () {
    $key = 'students';

    if (Redis::exists($key)) {
        $students = json_decode(Redis::get($key), true); // Decode JSON
        return response()->json(['cached' => true, 'data' => $students]);
    }

    return response()->json(['cached' => false, 'data' => null]);
});

Route::get('/clear-cache', function () {
    // Flush all Redis keys
    Redis::flushall();
    // Clear Laravel cache
    Cache::flush();
    return response()->json(['message' => 'All cache and Redis keys cleared!']);
});

// -------------------

Route::get('/students/cached', function (Request $request) {
    $students = Cache::remember('students', 600, function () {
        // $students =  Student::all();
        $students = Student::select('name', 'email')->limit(30)->get();
        // $students2 = json_encode($students);
        return $students;
    });

    // return $students;
    return response()->json($students);
});

Route::get('/students/db', function (Request $request) {
    $students = Student::all(); // Fetch all students directly from MySQL
    // return Student::select('name', 'email')->limit(30000)->get();
    return response()->json($students);
});

Route::get('/students/boost', function () {
    $key = 'students';

    if (!Redis::exists($key)) {
        $students = Student::all();
        // $students = Student::select('name', 'email')->get();
        Redis::setex($key, 600, json_encode($students)); // Store for 10 min
    } else {
        $students = json_decode(Redis::get($key));
    }

    return response()->json($students);
});


Route::get('/students/cache_delete', function () {
    Redis::del('students'); // call it on add, edit, delete
    return response()->json(['message' => 'Cache deleted!']);
});


Route::get('/students/boost1', function () {
    $key = 'students';

    if (!Redis::exists($key)) {
        $students = Student::all();
        $jsonData = json_encode($students);

        Redis::pipeline(function ($pipe) use ($key, $jsonData) {
            $pipe->setex($key, 600, $jsonData);
        });
    } else {
        $students = json_decode(Redis::get($key), true); // Use `true` for associative array (faster)
    }

    return response()->json($students);
});

Route::get('/students/boost2', function () {
    $key = 'students';

    if (!$students = Redis::get($key)) {
        $students = Student::all();
        $jsonData = json_encode($students);
        Redis::setex($key, 600, $jsonData);
    } else {
        $students = json_decode($students, true); // Use true for faster array decoding
    }

    return response()->json($students);
});
