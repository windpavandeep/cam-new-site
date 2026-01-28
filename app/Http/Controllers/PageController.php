<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Video;

/**
 * Handles simple page views for the CNC learning website.
 */
class PageController extends Controller
{
    /**
     * Fallback milling videos when DB has none (thumbnail: https://img.youtube.com/vi/{id}/mqdefault.jpg).
     *
     * @var array<int, array{id: string, title: string, pdf: string}>
     */
    private const array MILLING_VIDEOS_FALLBACK = [
        ['id' => 'jN7UH0_4dW4', 'title' => 'Mastercam 2D Milling Basics', 'pdf' => 'mill-basics-model.pdf'],
        ['id' => 'wixpacygYig', 'title' => 'CNC Milling Toolpaths Explained', 'pdf' => 'toolpaths-model.pdf'],
        ['id' => '2F6shnzR2h4', 'title' => 'Pocket Milling in Mastercam', 'pdf' => 'pocket-milling-model.pdf'],
        ['id' => 'RlXbRVOQqy8', 'title' => 'Contour and Drilling Operations', 'pdf' => 'contour-drill-model.pdf'],
        ['id' => '9RBz2xq2uR4', 'title' => 'Advanced 3-Axis Milling', 'pdf' => 'advanced-3axis-model.pdf'],
    ];

    public function home(): \Illuminate\Contracts\View\View
    {
        $milling = Video::query()
            ->where('category', Video::CATEGORY_MILLING)
            ->orderBy('title')
            ->get()
            ->map(fn (Video $v) => $v->toHomeArray())
            ->all();
        $multiaxis = Video::query()
            ->where('category', Video::CATEGORY_MULTI_AXIS)
            ->orderBy('title')
            ->get()
            ->map(fn (Video $v) => $v->toHomeArray())
            ->all();
        $turning = Video::query()
            ->where('category', Video::CATEGORY_TURNING)
            ->orderBy('title')
            ->get()
            ->map(fn (Video $v) => $v->toHomeArray())
            ->all();

        if (empty($milling)) {
            $milling = self::MILLING_VIDEOS_FALLBACK;
        }

        return view('home', [
            'milling_videos' => $milling,
            'multiaxis_videos' => $multiaxis,
            'turning_videos' => $turning,
        ]);
    }

    public function videos(): \Illuminate\Contracts\View\View
    {
        return view('videos');
    }

    public function about(): \Illuminate\Contracts\View\View
    {
        return view('about');
    }

    public function contact(): \Illuminate\Contracts\View\View
    {
        return view('contact');
    }

    public function models(): \Illuminate\Contracts\View\View
    {
        return view('models');
    }

    /**
     * Placeholder: trigger download of a model PDF.
     * In production, serve from storage or generate dynamically.
     */
    public function downloadModel(string $filename): \Illuminate\Http\BinaryFileResponse|\Illuminate\Http\Response
    {
        if (! preg_match('/^[a-zA-Z0-9_.-]+\\.pdf$/', $filename)) {
            abort(400, 'Invalid filename.');
        }
        $path = public_path('models/'.$filename);

        if (! is_file($path)) {
            abort(404, 'Model file not found.');
        }

        return response()->download($path, $filename, [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
