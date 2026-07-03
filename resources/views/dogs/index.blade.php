<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            My Dogs
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white p-6 shadow sm:rounded-lg">
                <h3 class="text-lg font-medium mb-4">Add a dog</h3>

                <form method="POST" action="{{ route('dogs.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="mt-1 block w-full rounded-md border-gray-300">
                        @error('name') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Breed</label>
                        <input type="text" name="breed" value="{{ old('breed') }}" class="mt-1 block w-full rounded-md border-gray-300">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Age</label>
                        <input type="number" name="age" value="{{ old('age') }}" class="mt-1 block w-full rounded-md border-gray-300">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Favourite food</label>
                        <input type="text" name="favourite_food" value="{{ old('favourite_food') }}" class="mt-1 block w-full rounded-md border-gray-300">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300">{{ old('description') }}</textarea>
                    </div>

                    <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-md">
                        Save dog
                    </button>
                </form>
            </div>

            <div class="bg-white p-6 shadow sm:rounded-lg">
                <h3 class="text-lg font-medium mb-4">Your dogs</h3>

            @forelse ($dogs as $dog)
                <div class="border-b py-3 flex justify-between items-start">
                    <div>
                        <p class="font-semibold">{{ $dog->name }} @if($dog->breed) ({{ $dog->breed }}) @endif</p>
                        @if($dog->age)
                            <p class="text-sm text-gray-600">Age: {{ $dog->age }}</p>
                        @endif
                        @if($dog->favourite_food)
                            <p class="text-sm text-gray-600">Loves: {{ $dog->favourite_food }}</p>
                        @endif
                        @if($dog->description)
                            <p class="text-sm text-gray-600 mt-1">{{ $dog->description }}</p>
                        @endif
                    </div>

                    <div class="flex gap-3 text-sm">
                        <a href="{{ route('dogs.edit', $dog) }}" class="text-indigo-600">Edit</a>
                        <form method="POST" action="{{ route('dogs.destroy', $dog) }}" onsubmit="return confirm('Remove {{ $dog->name }}?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600">Delete</button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-gray-500">No dogs yet — add one above.</p>
            @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
