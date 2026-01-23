<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\Location;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    public function index()
    {
        $portfolios = Portfolio::orderBy('sort_order')->get();
        return view('portfolios.index', compact('portfolios'));
    }

    public function create()
    {
        return view('portfolios.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'link' => 'nullable|url|max:255',
            'color_index' => 'nullable|integer|min:0|max:7',
        ]);

        // Standaard color_index op 0 als niet opgegeven
        if (!isset($validated['color_index'])) {
            $validated['color_index'] = 0;
        }

        // Maak portfolio aan
        $portfolio = Portfolio::create($validated);

        // Standaard locatie 'all' toewijzen (aanmaken indien niet aanwezig)
        $allLocation = \App\Models\Location::firstOrCreate(
            ['name' => 'all'],
            ['display_name' => 'Overzicht']
        );
        $portfolio->locations()->sync([$allLocation->id]);

        // Initieer sort_order als laatste
        $maxOrder = \App\Models\Portfolio::max('sort_order') ?? 0;
        $portfolio->update(['sort_order' => $maxOrder + 1]);

        return redirect()->route('posts.index')->with('success_portfolio', 'Portfolio succesvol toegevoegd!');
    }

    public function edit(Portfolio $portfolio)
    {
        $portfolio->load('locations');
        return view('portfolios.edit', [
            'portfolio' => $portfolio,
        ]);
    }

    public function update(Request $request, Portfolio $portfolio)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'link' => 'nullable|url|max:255',
            'color_index' => 'nullable|integer|min:0|max:7',
        ]);

        $portfolio->update($validated);

        return redirect()->route('posts.index')->with('success_portfolio', 'Portfolio succesvol bijgewerkt!');
    }

    public function destroy(Portfolio $portfolio)
    {
        $portfolio->delete();
        return redirect()->route('posts.index')->with('success_portfolio', 'Portfolio succesvol verwijderd!');
    }

    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|distinct'
        ]);

        // Update sort_order in een transactie
        // Alleen portfolios die in de 'all' locatie staan worden gesorteerd
        \DB::transaction(function () use ($validated) {
            $allLocation = Location::where('name', 'all')->first();
            
            if ($allLocation) {
                // Update sort_order voor portfolios in de volgorde (1,2,3,...)
                // We controleren eerst of het portfolio in 'all' locatie staat
                foreach ($validated['order'] as $index => $id) {
                    $portfolio = Portfolio::with('locations')->find($id);
                    if ($portfolio) {
                        // Controleer of portfolio in 'all' locatie staat
                        $isInAll = $portfolio->locations->contains(function($location) use ($allLocation) {
                            return $location->id === $allLocation->id;
                        });
                        
                        if ($isInAll) {
                            // Update sort_order: index + 1 (dus 1, 2, 3, ...)
                            $portfolio->sort_order = $index + 1;
                            $portfolio->save();
                        }
                    }
                }
            }
        });

        return response()->json(['success' => true]);
    }

    public function updateLocation(Request $request, $id)
    {
        try {
            $portfolio = Portfolio::findOrFail($id);
            $location = Location::where('name', $request->location)->firstOrFail();

            $portfolio->locations()->detach();
            $portfolio->locations()->attach($location->id);

            // Als portfolio naar 'all' locatie wordt verplaatst en geen sort_order heeft, geef het een sort_order
            if ($location->name === 'all' && !$portfolio->sort_order) {
                $maxOrder = Portfolio::whereHas('locations', function($q) use ($location) {
                    $q->where('locations.id', $location->id);
                })->max('sort_order') ?? 0;
                $portfolio->update(['sort_order' => $maxOrder + 1]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Portfolio locatie succesvol bijgewerkt'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Fout bij bijwerken portfolio locatie'
            ], 500);
        }
    }

    public function resetAllToOverview()
    {
        $allLocation = Location::where('name', 'all')->first();
        if (!$allLocation) {
            return redirect()->route('posts.index')->with('error_portfolio', 'Locatie "all" niet gevonden.');
        }
        $portfolios = Portfolio::all();
        foreach ($portfolios as $portfolio) {
            $portfolio->locations()->sync([$allLocation->id]);
        }
        return redirect()->route('posts.index')->with('success_portfolio', 'Alle portfolio\'s zijn teruggezet naar het overzicht.');
    }
}


