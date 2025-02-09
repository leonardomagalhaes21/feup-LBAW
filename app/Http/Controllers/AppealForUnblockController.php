<?php

namespace App\Http\Controllers;

use App\Models\AppealForUnblock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppealForUnblockController extends Controller
{
    public function index()
    {
        return view('pages.appeal_for_unblock.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $user = Auth::user();

        if (!$user->is_blocked) {
            return redirect()->route('appealForUnblock.index')->with('error', 'You are not blocked, no appeal needed.'); 
        }

        $appeal = AppealForUnblock::where('users_id', $user->id)->first();

        if ($appeal) {
            $appeal->content = $request->content;
            $appeal->save();
        } else {
            AppealForUnblock::create([
                'content' => $request->content,
                'users_id' => $user->id,
            ]);
        }

        return redirect()->route('appealForUnblock.index')->with('success', 'Appeal submitted successfully.');
    }
}