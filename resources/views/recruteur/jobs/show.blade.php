@auth
    @if(auth()->user()->role === 'candidat')
        <form action="{{ route('jobs.favorite.toggle', $job) }}" method="POST" class="mb-4">
            @csrf
            @if(auth()->user()->favoriteJobs->contains($job->id))
                <button type="submit" class="w-full flex items-center justify-center gap-2 border border-amber-500 bg-amber-50 text-amber-700 py-2 rounded font-semibold hover:bg-amber-100 transition">
                    <span class="text-lg">⭐</span> Retirer des favoris
                </button>
            @else
                <button type="submit" class="w-full flex items-center justify-center gap-2 border border-gray-300 bg-white text-gray-700 py-2 rounded font-semibold hover:bg-gray-50 transition">
                    <span class="text-lg">☆</span> Enregistrer l'offre (Favoris)
                </button>
            @endif
        </form>
    @endif
@endauth