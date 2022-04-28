<x-app-layout>
    <div class="container-menu py-8">
        <ul>
            @forelse ($products as $product)
                <x-product-list :product="$product" />
            
            @empty
            <li class="bg-white rounded-lg shadow-lg">
                <div class="p-4">
                    <p class="text-lg text-gray-700 font-semibold ">No existe ningún registro con los parámetros especificados</p>
                </div>
            </li>
            @endforelse
        </ul>
        <div class="mt-4">
            {{ $products->links() }}
        </div>
    </div>
</x-app-layout>