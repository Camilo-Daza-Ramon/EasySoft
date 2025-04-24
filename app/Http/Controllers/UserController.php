<?php
// filepath: app/Http/Controllers/Api/UserController.php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function show(Request $request)
    {
        return $request->user();
    }
}