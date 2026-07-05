<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Spots
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 text-red-800 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<div class="bg-white p-6 shadow sm:rounded-lg">
    <h3 class="text-lg font-medium mb-4">Map</h3>
    <div id="map" style="height: 400px; border-radius: 0.5rem;"></div>
</div>

@if(auth()->user()->is_admin)
    <div class="bg-white p-6 shadow sm:rounded-lg">
        <h3 class="text-lg font-medium mb-4">Add a spot</h3>

        <form method="POST" action="{{ route('spots.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700">Place name</label>
                <input type="text" name="place_name" value="{{ old('place_name') }}" class="mt-1 block w-full rounded-md border-gray-300" placeholder="e.g. Formby Beach">
                @error('place_name') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300">{{ old('description') }}</textarea>
            </div>

            <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-md">
                Find and save
            </button>
        </form>
    </div>
@endif

            <div class="bg-white p-6 shadow sm:rounded-lg">
                <h3 class="text-lg font-medium mb-4">All spots</h3>

            @forelse ($spots as $spot)
                <div class="border-b py-3 flex justify-between items-start">
                    <div>
                        <p class="font-semibold">{{ $spot->name }}</p>
                        <p class="text-sm text-gray-600">{{ $spot->latitude }}, {{ $spot->longitude }}</p>
                        @if($spot->description)
                            <p class="text-sm text-gray-600 mt-1">{{ $spot->description }}</p>
                        @endif

                        <p class="text-sm text-gray-600 mt-1" id="paw-count-{{ $spot->id }}">
                            {{ $spot->pawed_by_users_count }} paw{{ $spot->pawed_by_users_count === 1 ? '' : 's' }}
                        </p>

                        <form method="POST" action="{{ route('spots.paw', $spot) }}" class="paw-form" data-spot-id="{{ $spot->id }}">
                            @csrf
                            <button type="submit" id="paw-button-{{ $spot->id }}" class="text-sm {{ $spot->pawedByUsers->isNotEmpty() ? 'text-amber-600 font-semibold' : 'text-gray-400' }}">
                                🐾 {{ $spot->pawedByUsers->isNotEmpty() ? 'Pawed' : 'Paw' }}
                            </button>
                        </form>
                    </div>
                @if(auth()->user()->is_admin)
                    <div class="flex gap-3 text-sm">
                        <a href="{{ route('spots.edit', $spot) }}" class="text-indigo-600">Edit</a>
                        <form method="POST" action="{{ route('spots.destroy', $spot) }}" onsubmit="return confirm('Remove {{ $spot->name }}?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600">Delete</button>
                        </form>
                    </div>
                @endif
                </div>
            @empty
                <p class="text-gray-500">No spots yet — add one above.</p>
            @endforelse
            </div>

        </div>
    </div>
</x-app-layout>

@php
    $spotPins = $spots->map(fn ($spot) => [
        'slug' => $spot->slug,
        'name' => $spot->name,
        'description' => $spot->description,
        'lat' => (float) $spot->latitude,
        'lng' => (float) $spot->longitude,
    ]);
@endphp

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    const map = L.map('map').setView([53.4808, -3.0093], 10);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 19,
    }).addTo(map);

    const spots = @json($spotPins);

    const markers = [];

    spots.forEach(spot => {
        const marker = L.marker([spot.lat, spot.lng]).addTo(map);
        marker.bindPopup(`<strong>${spot.name}</strong>${spot.description ? '<br>' + spot.description : ''}<br><a href="/spots/${spot.slug}">View details</a>`);        markers.push(marker);
    });

    if (markers.length > 0) {
        const group = new L.featureGroup(markers);
        map.fitBounds(group.getBounds().pad(0.2));
    }
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