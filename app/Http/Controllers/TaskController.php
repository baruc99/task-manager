<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TaskController extends Controller
{
    public function index()
    {
        return Auth::user()->tasks()->latest()->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pendiente,en progreso,completada',
            'due_date' => 'nullable|date',
            'priority' => 'boolean',
            'category' => 'required|in:trabajo,estudio,casa,personal,finanzas,salud,viaje,social,tecnología',
        ]);

        // $task = Auth::user()->tasks()->create($validated);
        $priority = $this->assignPriority($request->due_date, $request->status);
        $task = Auth::user()->tasks()->create([
            ...$validated,
            'priority' => $priority,
        ]);


        return response()->json($task, 201);
    }

    public function show(Task $task)
    {
        $this->authorizeTask($task);
        return $task;
    }

    public function update(Request $request, Task $task)
    {
        $this->authorizeTask($task);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pendiente,en progreso,completada',
            'due_date' => 'nullable|date',
            'priority' => 'boolean',
            'category' => 'required|in:trabajo,estudio,casa,personal,finanzas,salud,viaje,social,tecnología',
        ]);

        $priority = $this->assignPriority($request->due_date, $request->status);
        $task->update([
            ...$validated,
            'priority' => $priority,
        ]);


        return response()->json($task);
    }

    public function destroy(Task $task)
    {
        $this->authorizeTask($task);
        $task->delete();

        return response()->json(['message' => 'Tarea eliminada correctamente.']);
    }

    private function authorizeTask(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403, 'No autorizado');
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pendiente,en progreso,completada'
        ]);

        $task = Task::findOrFail($id);
        $task->status = $request->status;
        $task->save();

        return response()->json(['message' => 'Status updated successfully']);
    }

    private function assignPriority($dueDate, $status)
    {
        if ($status === 'completada') {
            return 'low';
        }

        $due = Carbon::parse($dueDate);

        if ($due->isToday()) {
            return 'high';
        } elseif ($due->isTomorrow()) {
            return 'medium';
        } else {
            return 'low';
        }
    }


}
