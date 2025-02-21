<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\UserResponse;
use Illuminate\Support\Facades\Cache;

class SurveyController extends Controller
{
    // Метод для получения всех вопросов с ответами
    public function index()
    {
        $questions = Cache::remember('questions', 3600, function () {
            return Question::with('answers')->get();
        });

        return response()->json($questions);
    }

    // Метод для сохранения ответа
    public function store(Request $request)
    {
        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'answer_id' => 'nullable|exists:answers,id',
            'custom_answer' => 'nullable|string|max:255',
        ]);

        UserResponse::create([
            'question_id' => $request->question_id,
            'answer_id' => $request->answer_id,
            'custom_answer' => $request->custom_answer,
            'user_ip' => $request->ip(),
        ]);

        return response()->json(['message' => 'Ответ сохранен']);
    }
}
