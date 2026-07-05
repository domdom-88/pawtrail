<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $spot->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

            @if (session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white p-6 shadow sm:rounded-lg">
                <div id="spot-map" style="height: 300px; border-radius: 0.5rem;" class="mb-4"></div>

                @if($spot->description)
                    <p class="text-gray-700 mb-4">{{ $spot->description }}</p>
                @endif

                <p class="text-sm text-gray-600 mb-2" id="paw-count-{{ $spot->id }}">
                    {{ $spot->pawed_by_users_count }} paw{{ $spot->pawed_by_users_count === 1 ? '' : 's' }}
                </p>

                <form method="POST" action="{{ route('spots.paw', $spot) }}" class="paw-form" data-spot-id="{{ $spot->id }}">
                    @csrf
                    <button type="submit" id="paw-button-{{ $spot->id }}" class="text-sm {{ $spot->pawedByUsers->isNotEmpty() ? 'text-amber-600 font-semibold' : 'text-gray-400' }}">
                        🐾 {{ $spot->pawedByUsers->isNotEmpty() ? 'Pawed' : 'Paw' }}
                    </button>
                </form>

                @if(auth()->user()->is_admin)
                    <div class="flex gap-3 text-sm mt-4">
                        <a href="{{ route('spots.edit', $spot) }}" class="text-indigo-600">Edit</a>
                        <form method="POST" action="{{ route('spots.destroy', $spot) }}" onsubmit="return confirm('Remove {{ $spot->name }}?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600">Delete</button>
                        </form>
                    </div>
                @endif
            </div>

            <div class="bg-white p-6 shadow sm:rounded-lg">
                <h3 class="text-lg font-medium mb-4">Log a visit</h3>

                @if(auth()->user()->dogs->isEmpty())
                    <p class="text-gray-500">You need to <a href="{{ route('dogs.index') }}" class="text-indigo-600">add a dog</a> before logging a visit.</p>
                @else
                    <form method="POST" action="{{ route('visits.store', $spot) }}" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Dog</label>
                            <select name="dog_id" class="mt-1 block w-full rounded-md border-gray-300">
                                @foreach(auth()->user()->dogs as $dog)
                                    <option value="{{ $dog->id }}">{{ $dog->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Notes</label>
                            <textarea name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300" placeholder="How was the walk?"></textarea>
                        </div>

                        <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-md">
                            Log visit
                        </button>
                    </form>
                @endif
            </div>

            <div class="bg-white p-6 shadow sm:rounded-lg">
                <h3 class="text-lg font-medium mb-4">Visits</h3>

                @forelse ($spot->visits as $visit)
                    <div class="border-b py-3">
<p class="font-semibold">
    {{ $visit->dog->name }}
    @if($visit->dog->breed)
        ({{ $visit->dog->breed }})
    @endif
    <span class="text-gray-500 font-normal">— logged by {{ $visit->user->name }}</span>
</p>                        <p class="text-sm text-gray-600">{{ $visit->visited_at->format('jS F Y') }}</p>
                        @if($visit->notes)
                            <p class="text-sm text-gray-700 mt-1">{{ $visit->notes }}</p>
                        @endif
                    </div>
                @empty
                    <p class="text-gray-500">No visits logged here yet — be the first!</p>
                @endforelse
            </div>

            <a href="{{ route('spots.index') }}" class="text-sm text-gray-600">&larr; Back to all spots</a>

        </div>
    </div>
</x-app-layout>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    const map = L.map('spot-map').setView([{{ $spot->latitude }}, {{ $spot->longitude }}], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 19,
    }).addTo(map);

    L.marker([{{ $spot->latitude }}, {{ $spot->longitude }}]).addTo(map)
        .bindPopup(@json($spot->name))
        .openPopup();
</script>

<script>
    document.querySelectorAll('.paw-form').forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
            });

            const data = await response.json();

            const spotId = form.dataset.spotId;
            const button = document.getElementById(`paw-button-${spotId}`);
            const countEl = document.getElementById(`paw-count-${spotId}`);

            button.textContent = data.pawed ? '🐾 Pawed' : '🐾 Paw';
            button.classList.toggle('text-amber-600', data.pawed);
            button.classList.toggle('font-semibold', data.pawed);
            button.classList.toggle('text-gray-400', !data.pawed);

            countEl.textContent = `${data.count} paw${data.count === 1 ? '' : 's'}`;
        });
    });
</script>