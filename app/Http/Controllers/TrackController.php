<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Week;
use App\Models\Track;
use App\Players\Player;
use App\Rules\PlayerUrl;
use App\Services\UserService;
use App\Exceptions\PlayerException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class TrackController extends Controller
{
    /**
     * Show given track.
     */
    public function show(Request $request, Week $week, Track $track, Player $player): View
    {
        return view('app.tracks.show', [
            'week' => $week->loadCount('tracks'),
            'track' => $track->loadCount('likes'),
            'tracks_count' => $week->tracks_count,
            'position' => $week->getTrackPosition($track),
            'liked' => $request->user()->likes()->whereTrackId($track->id)->exists(),
            'embed' => $player->embed($track->player, $track->player_track_id),
        ]);
    }

    /**
     * Show create track form.
     */
    public function create(UserService $user): View
    {
        return view('app.tracks.create', [
            'week' => Week::current(),
            'remaining_tracks_count' => $user->remainingTracksCount(),
            'categories' => Category::getAllCategories(),
        ]);
    }

    /**
     * Create a new track.
     */
    public function store(Request $request, Player $player): RedirectResponse
    {
        $this->authorize('create', Track::class);

        // Validation des données de la requête
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'artist' => ['required', 'string', 'max:255'],
            'url' => ['required', 'url', new PlayerUrl()],
            'category_id' => ['required', 'exists:categories,id'], // Validation de l'ID de la catégorie
        ]);

        DB::beginTransaction();

        // Création du track avec les données validées
        $track = new Track($validated);

        // Association du track avec l'utilisateur et la semaine actuels
        $track->user()->associate($request->user());
        $track->week()->associate(Week::current());

        // Association de la catégorie au track
        $track->category_id = $validated['category_id'];

        try {
            // Récupérer les détails du track depuis le fournisseur (ex: YouTube, SoundCloud)
            $details = $player->details($track->url);

            // Définition des détails du track
            $track->player = $details->player_id;
            $track->player_track_id = $details->track_id;
            $track->player_thumbnail_url = $details->thumbnail_url;

            // Enregistrement du track
            $track->save();

            DB::commit();
        } catch (PlayerException $th) {
            DB::rollBack();
            throw $th;
        }

        return redirect()->route('app.tracks.show', [
            'week' => $track->week->uri,
            'track' => $track,
        ]);
    }


    /**
     * Toggle like.
     */
    public function like(Request $request, Week $week, Track $track): RedirectResponse
    {
        $user = $request->user();

        $track->likes()->toggle([
            $user->id => ['liked_at' => now()]
        ]);

        return redirect()->route('app.tracks.show', [
            'week' => $week->uri,
            'track' => $track,
        ]);
    }
}
