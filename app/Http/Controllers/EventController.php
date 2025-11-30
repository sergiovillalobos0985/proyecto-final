<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Tag;

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
        // Necesitamos enviar las categorías y etiquetas a la vista para el <select>
        $categories = Category::all();
        $tags = Tag::all();
        
        return view('events.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. VALIDACIÓN (Lado Servidor - 70 Puntos)
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date|after:today', // Debe ser fecha futura
            'location' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id', // Debe existir en la tabla categories
            'tags' => 'array', // Debe ser un arreglo (checkboxes)
            'tags.*' => 'exists:tags,id', // Cada tag seleccionado debe existir
        ]);

        // 2. CREACIÓN
        // Usamos la relación del usuario para asignar automáticamente el user_id
        // $request->user() obtiene el usuario logueado actualmente
        $event = $request->user()->events()->create($validated);

        // 3. GUARDAR RELACIÓN M:N (Etiquetas)
        if ($request->has('tags')) {
            $event->tags()->sync($request->tags);
        }

        // 4. REDIRECCIÓN
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
        // 1. Necesitamos las categorías y etiquetas para llenar los selects y checkboxes
        $categories = \App\Models\Category::all();
        $tags = \App\Models\Tag::all();
        
        // 2. Retornamos la vista enviando el evento a editar y las opciones
        return view('events.edit', compact('event', 'categories', 'tags'));
    }

    // GUARDA LOS CAMBIOS EN LA BASE DE DATOS
    public function update(Request $request, Event $event)
    {
        // 1. VALIDACIÓN (Igual que en create, pero ajustamos reglas si es necesario)
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date', 
            'location' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'array',         // Verifica que sea una lista
            'tags.*' => 'exists:tags,id', // Verifica que cada tag exista
        ]);

        // 2. ACTUALIZAR DATOS BÁSICOS
        $event->update($validated);

        // 3. ACTUALIZAR RELACIÓN M:N (Etiquetas)
        // El método sync() es muy potente: 
        // Agrega las nuevas, borra las que quitaste y mantiene las que dejaste.
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
        // Autenticación básica: (Mejoraremos esto con Policies en el siguiente paso)
        if ($event->user_id !== auth()->id()) {
            abort(403, 'No tienes permiso para borrar este evento.');
        }

        $event->delete(); // Esto hace un Soft Delete gracias al modelo

        return redirect()->route('events.index')
                         ->with('status', 'Evento eliminado (enviado a la papelera).');
    }
}
