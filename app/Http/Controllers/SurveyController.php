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
            'responses' => 'required|array|min:1',
            'responses.*.question_id' => 'required|exists:questions,id',
            'responses.*.answer_id' => 'nullable|exists:answers,id',
            'responses.*.custom_answer' => 'nullable|string|max:255',
        ]);

        foreach ($request->responses as $response) {
            // Проверяем, является ли ответ "Иное"
            $isCustomAnswer = false;

            if (!empty($response['answer_id'])) {
                $answer = \App\Models\Answer::find($response['answer_id']);
                if ($answer && mb_strtolower($answer->text) === 'иное') {
                    $isCustomAnswer = true;
                }
            }

            UserResponse::create([
                'question_id' => $response['question_id'],
                'answer_id' => $response['answer_id'] ?? null,
                'custom_answer' => $isCustomAnswer ? $response['custom_answer'] : null,
                'user_ip' => $request->ip(),
            ]);
        }
    
        return response()->json(['message' => 'Ответ сохранен']);
    }
}
