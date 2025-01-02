<div class="grid grid-cols-1 gap-4 md:grid-cols-3">
    @foreach($stats as $stat)
        <div class="p-4 border rounded-lg bg-white shadow">
            <h3 class="text-xl font-semibold">{{ $stat->getLabel() }}</h3>
            <p class="text-2xl">{{ $stat->getValue() }}</p> <!-- Usando getValue() -->
        </div>
    @endforeach
</div>
