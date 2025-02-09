<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Question;
class AnswerController extends Controller
{
    public function index()
    {
        // Retrieve all answers from the database
        $answers = Answer::all();

        // Return a view to display all answers
        return view('answers.index', compact('answers'));
    }

    /**
     * Show the form for creating a new answer.
     */
    public function create(Question $question)
    {
        return view('pages/answers.create', compact('question'));
    }


    /**
     * Store a newly created answer in storage.
     */
    public function store(Request $request, $postId)
    {
        

        $request->validate([
            'content' => 'required|string',
        ]);

        $question = Question::where('posts_id', $postId)->first();

        if (!$question) {
            return redirect()->route('questions.index')->with('error', 'Question not found.');
        }

        $post = Post::create([
            'content' => $request->content,
            'date' => now(),
            'users_id' => auth()->user()->id,
        ]);

        Answer::create([
            'posts_id' => $post->id,         
            'questions_id' => $question->posts_id,  
        ]);

        return redirect()->route('questions.show', $postId)->with('success', 'Answer added successfully!');
    }





    /**
     * Display the specified answer.
     */
    public function show(Answer $answer)
    {
        // Return a view to display the details of a specific answer
        return view('answers.show', compact('answer'));
    }

    /**
     * Show the form for editing the specified answer.
     */
    public function edit(Answer $answer)
    {
        $this->authorize('update', $answer);
        // Return a view to edit the specified answer
        return view('pages.editAnswer', compact('answer'));
    }

    /**
     * Update the specified answer in storage.
     */
    public function update(Request $request, Answer $answer)
    {
        $this->authorize('update', $answer);

        $request->validate([
            'content' => 'required|string',
        ]);

        $answer->post->update([
            'content' => $request->input('content'),
        ]);

        return redirect()->route('questions.show', $answer->question->posts_id)->with('success', 'Answer updated successfully');
    }

    /**
     * Remove the specified answer from storage.
     */
    public function destroy(Answer $answer)
    {
        $this->authorize('delete', $answer);

        $question_id = $answer->question->posts_id;
        // Delete the answer from the database
        $answer->delete();

        // Redirect to the answers index page with a success message
        return redirect()->route('questions.show', $question_id)->with('success', 'Answer deleted successfully');
    }
}