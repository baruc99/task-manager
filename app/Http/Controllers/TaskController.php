<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

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
            'status' => 'required|in:pendiente,en progreso,completada', // Asegúrate de que coincida con el enum en la base de datos
            'due_date' => 'nullable|date',
            'priority' => 'nullable|in:low,medium,high', // Asegúrate de que coincida con el enum en la base de datos
            'category' => 'required|in:trabajo,estudio,casa,personal,finanzas,salud,viaje,social,tecnología', // Coincidir con la base de datos
        ]);

        // Asignar prioridad basada en la fecha de vencimiento y el estado
        $priority = $request->priority ?? $this->assignPriority($request->due_date, $request->status);


        // Crear la tarea con los datos validados y asignación de prioridad
        $task = Auth::user()->tasks()->create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'status' => $validated['status'],
            'due_date' => $validated['due_date'],
            'priority' => $priority,
            'category' => $validated['category'],
        ]);

        return response()->json($task, 201);
    }

    public function show(Task $task)
    {
        $this->authorizeTask($task);  // Verifica que el usuario sea dueño de la tarea
        return response()->json($task);
    }

    public function update(Request $request, Task $task)
    {
        $this->authorizeTask($task); // Verifica que el usuario sea dueño de la tarea

        // Validación de los datos de la tarea
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pendiente,en progreso,completada',
            'due_date' => 'nullable|date',
            'priority' => 'nullable|in:low,medium,high', // Verificar que la prioridad esté en los valores permitidos
            'category' => 'required|in:trabajo,estudio,casa,personal,finanzas,salud,viaje,social,tecnología',
        ]);

        // Recalcular la prioridad siempre, independientemente de si se envió una prioridad
        $priority = $this->assignPriority($request->due_date, $request->status);

        // Actualizar la tarea con los datos validados
        $task->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'status' => $validated['status'],
            'due_date' => $validated['due_date'],
            'priority' => $priority,  // Actualiza la prioridad con el valor recalculado
            'category' => $validated['category'],
        ]);

        return response()->json($task);
    }



    public function destroy(Task $task)
    {
        $this->authorizeTask($task); // Verifica que el usuario sea dueño de la tarea
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
            'status' => 'required|in:pendiente,en progreso,completada', // Coincidir con la base de datos
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

        $due = Carbon::parse($dueDate)->startOfDay(); // Asegura que solo se compare la fecha sin la hora
        $today = Carbon::today(); // Ya es sin la hora por defecto

        // Si la fecha es hoy o ya pasó, prioridad alta
        if ($due->isToday() || $due->isPast()) {
            return 'high';
        }

        // Si la fecha está dentro de 5 días, prioridad media
        if ($due->diffInDays($today) < 5) {
            return 'medium';
        } else if ($due->diffInDays($today) >= 6) {
            // Si la fecha está dentro de 7 días, prioridad baja
            return 'low';
        }

        // Si la fecha está a más de 7 días, prioridad baja
        return 'low';
    }
}
