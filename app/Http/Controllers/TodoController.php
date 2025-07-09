<?php

namespace App\Http\Controllers;

use App\Models\todo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TodoController extends Controller
{
    public function storeTodo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:pending,completed,in_progress',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'required|date',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        $todo = todo::create($request->all());
        return response()->json(['message' => 'Todo created successfully', 'todo' => $todo], 200);
    }

    public function getUserTodos(Request $request)
    {
        $validator = Validator::make(['id' => $request->user_id], [
            'id' => 'required|exists:users,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        $userToDo = User::find($request->user_id);
        $todostitle = $userToDo->todos[0]->title;


        $todos = $userToDo->todos->map(function ($todos,$key) {
            return [

                'task' => $key + 1,
                'description' => $todos->description,
                'status' => $todos->status,
                'priority' => $todos->priority,
                'due_date' => $todos->due_date,
            ];
        });
        return response()->json(['ToDoTitle' => $todostitle, 'todolist' => $todos], 200);
    }
}
