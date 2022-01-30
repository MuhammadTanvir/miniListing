<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Listings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            @if (session('message'))
                <div class="p-2 mb-4 bg-gray-200">{{ session('message') }}</div>
            @endif

            <a href="{{ route('listings.create') }}"
               class="inline-flex items-center px-4 py-2 mb-4 text-xs font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out bg-gray-800 border border-transparent rounded-md hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25">{{ __('Add new listing') }}</a>

            <div class="mb-4">
                <form method="GET" action="">
                    <input type="text" name="title" placeholder="Title" value="{{ request('title') }}" />
                    <select name="category">
                        <option value="">-- choose category --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                    @if (request('category') == $category->id) selected @endif>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <select name="size">
                        <option value="">-- choose size --</option>
                        @foreach ($sizes as $size)
                            <option value="{{ $size->id }}"
                                    @if (request('size') == $size->id) selected @endif>{{ $size->name }}</option>
                        @endforeach
                    </select>
                    <select name="color">
                        <option value="">-- choose color --</option>
                        @foreach ($colors as $color)
                            <option value="{{ $color->id }}"
                                    @if (request('color') == $color->id) selected @endif>{{ $color->name }}</option>
                        @endforeach
                    </select>
                    <select name="city">
                        <option value="">-- choose city --</option>
                        @foreach ($cities as $city)
                            <option value="{{ $city->id }}"
                                    @if (request('city') == $city->id) selected @endif>{{ $city->name }}</option>
                        @endforeach
                    </select>
                    @livewire('listing-saved-checkbox')
                    <button type="submit" class="inline-flex items-center px-4 py-2 mb-4 text-xs font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out bg-gray-800 border border-transparent rounded-md hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25">Search</button>
                </form>
            </div>

            <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                        </th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            Title
                        </th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            Description
                        </th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            Categories
                        </th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            Sizes
                        </th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            Colors
                        </th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            City
                        </th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            Price
                        </th>
                        <th class="relative px-6 py-3" colspan="2"></th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($listings as $listing)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <img src="{{ $listing->getFirstMediaUrl('listings', 'thumb') }}" />
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $listing->title }}
                            <br />
                            <a href="{{ route('messages.create') }}?listing_id={{ $listing->id }}" class="underline">Send a message</a></td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $listing->description }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @foreach ($listing->categories as $category)
                                    {{ $category->name }}
                                @endforeach
                            </td>

                           <td class="px-6 py-4 whitespace-nowrap">
                                @foreach ($listing->sizes as $size)
                                    {{ $size->name }}
                                @endforeach
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @foreach ($listing->colors as $color)
                                    {{ $color->name }}
                                @endforeach
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $listing->user->city->name ?? '' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">${{ $listing->price }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($listing->user_id != auth()->id())
                                    @livewire('listing-save-button', ['listingId' => $listing->id])
                                @endif

                                @can('update', $listing)
                                    <a href="{{ route('listings.edit', $listing) }}"
                                       class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out bg-gray-800 border border-transparent rounded-md hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25">Edit</a>
                                @endcan
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @can('delete', $listing)
                                    <form method="POST" action="{{ route('listings.destroy', $listing) }}">
                                        @csrf
                                        @method('DELETE')
                                        <x-jet-danger-button onclick="return confirm('Are you sure?')" type="submit">
                                            Delete
                                        </x-jet-danger-button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <div class="mt-4 mb-4 ml-4">
                    {{ $listings->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
