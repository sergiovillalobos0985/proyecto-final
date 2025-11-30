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
                    
                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-gray-700">Descripción</h3>
                        <p class="mt-2">{{ $event->description }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <h3 class="text-lg font-bold text-gray-700">Detalles</h3>
                            <ul class="list-disc list-inside mt-2">
                                <li><strong>Fecha:</strong> {{ $event->start_date }}</li>
                                <li><strong>Lugar:</strong> {{ $event->location }}</li>
                                <li><strong>Categoría:</strong> {{ $event->category->name }}</li>
                                <li><strong>Organizador:</strong> {{ $event->user->name }}</li>
                            </ul>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-700">Etiquetas</h3>
                            <div class="mt-2">
                                @foreach($event->tags as $tag)
                                    <span class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2" style="background-color: {{ $tag->color }}33;">
                                        #{{ $tag->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="border-t pt-4">
                        <a href="{{ route('events.index') }}" class="text-blue-500 hover:text-blue-700">← Volver al listado</a>
                        
                        @if(auth()->id() === $event->user_id)
                            <a href="{{ route('events.edit', $event) }}" class="ml-4 text-yellow-500 hover:text-yellow-700">Editar</a>
                            
                            <form action="{{ route('events.destroy', $event) }}" method="POST" class="inline ml-4">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('¿Borrar evento?')">Borrar</button>
                            </form>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>