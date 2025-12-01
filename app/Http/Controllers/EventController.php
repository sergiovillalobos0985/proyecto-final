<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Event;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = \App\Models\Event::with(['user', 'category', 'tags'])
                ->latest() // Ordenar por el más reciente
                ->paginate(10);

        return view('events.index', compact('events'));
        $categories = \App\Models\Category::all();

        return view('events.index', compact('events', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        
        return view('events.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date|after:today', // Debe ser fecha futura
            'location' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id', // Debe existir en la tabla categories
            'tags' => 'array', // Debe ser un arreglo (checkboxes)
            'tags.*' => 'exists:tags,id', // Cada tag seleccionado debe existir
        ]);

        $event = $request->user()->events()->create($validated);

        if ($request->has('tags')) {
            $event->tags()->sync($request->tags);
        }

        return redirect()->route('events.index')
                         ->with('status', '¡Evento creado con éxito!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        // Retornamos la vista con el evento
        return view('events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        $categories = \App\Models\Category::all();
        $tags = \App\Models\Tag::all();
        
        return view('events.edit', compact('event', 'categories', 'tags'));
        
        if (request()->user()->cannot('delete', $event)) {
            abort(403, 'No tienes permiso para eliminar este evento.');
        }

        $event->delete();

        return redirect()->route('events.index')
                         ->with('status', 'Evento eliminado correctamente.');
        $categories = Category::all();
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date', 
            'location' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'array',         // Verifica que sea una lista
            'tags.*' => 'exists:tags,id', // Verifica que cada tag exista
        ]);

        $event->update($validated);

        if ($request->has('tags')) {
            $event->tags()->sync($request->tags);
        } else {
            // Si el usuario desmarcó todas, quitamos las relaciones
            $event->tags()->detach();
        }

        // 4. REDIRECCIONAR
        return redirect()->route('events.index')
                         ->with('status', '¡Evento actualizado correctamente!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        if (request()->user()->cannot('delete', $event)) {
            abort(403, 'No tienes permiso para eliminar este evento.');
        }

        $event->delete();

        return redirect()->route('events.index')
                         ->with('status', 'Evento eliminado correctamente.');

        $event->delete(); // Esto hace un Soft Delete gracias al modelo

        return redirect()->route('events.index')
                         ->with('status', 'Evento eliminado (enviado a la papelera).');
    }
}
