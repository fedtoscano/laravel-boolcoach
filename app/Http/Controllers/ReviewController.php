<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vote;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reviews = Review::where('coach_id', Auth::id())->orderBy('created_at', 'desc')->get();

        $user = Auth::user();
        $userVotes = $user->votes()->orderBy('created_at', 'desc')->get();

        return view('reviews.index', compact('user', 'reviews', 'userVotes'));
    }
}
