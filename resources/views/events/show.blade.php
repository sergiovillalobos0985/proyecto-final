<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $event->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div>
                            <h3 class="text-lg font-bold text-indigo-600 mb-2">Sobre el evento</h3>
                            <p class="text-gray-700 dark:text-gray-300 mb-4">{{ $event->description }}</p>

                            <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg">
                                <ul class="space-y-2 text-sm">
                                    <li><strong>📅 Fecha:</strong> {{ $event->start_date }}</li>
                                    <li><strong>📍 Ubicación:</strong> {{ $event->location }}</li>
                                    <li><strong>📂 Categoría:</strong> {{ $event->category->name }}</li>
                                    <li><strong>👤 Organizador:</strong> {{ $event->user->name }}</li>
                                </ul>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-bold text-indigo-600 mb-2">Etiquetas</h3>
                            <div class="flex flex-wrap gap-2 mb-6">
                                @forelse($event->tags as $tag)
                                    <span class="px-3 py-1 bg-indigo-100 text-indigo-800 text-xs rounded-full">
                                        #{{ $tag->name }}
                                    </span>
                                @empty
                                    <span class="text-gray-500 italic text-sm">Sin etiquetas</span>
                                @endforelse
                            </div>

                            @if(auth()->id() === $event->user_id || auth()->user()->role === 'admin')
                                <div class="border-t pt-4 mt-4">
                                    <h4 class="text-sm font-semibold mb-2">Acciones de Administrador</h4>
                                    <div class="flex gap-2">
                                        <a href="{{ route('events.edit', $event) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded">
                                            Editar
                                        </a>
                                        
                                        <form action="{{ route('events.destroy', $event) }}" method="POST" onsubmit="return confirm('¿Seguro que quieres borrar este evento?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">
                                                Borrar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mt-8">
                        <a href="{{ route('events.index') }}" class="text-indigo-600 hover:text-indigo-800 underline">
                            &larr; Volver al listado
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>