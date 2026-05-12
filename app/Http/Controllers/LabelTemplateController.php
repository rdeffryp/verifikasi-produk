<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LabelTemplateController extends Controller
{
    public function index()
    {
        $templates = DB::table('label_templates')->orderBy('created_at', 'desc')->get();
        return view('label-templates.index', compact('templates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_template' => 'required|string|max:100',
            'file_template' => 'required|image|mimes:png|max:10240',
            'qr_size_px'    => 'required|integer|min:50|max:1000',
            'pos_x'         => 'required|integer|min:0',
            'pos_y'         => 'required|integer|min:0',
        ]);

        $file = $request->file('file_template');
        $filename = 'label_' . time() . '_' . str_replace(' ', '_', $request->nama_template) . '.png';
        $file->move(public_path('label-templates'), $filename);

        DB::table('label_templates')->insert([
            'nama_template' => $request->nama_template,
            'file_path'     => 'label-templates/' . $filename,
            'qr_size_px'    => $request->qr_size_px,
            'pos_x'         => $request->pos_x,
            'pos_y'         => $request->pos_y,
            'is_active'     => true,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        return redirect()->route('label-templates.index')
                         ->with('success', 'Template label berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $template = DB::table('label_templates')->find($id);

        if (!$template) {
            return redirect()->route('label-templates.index')
                             ->withErrors(['error' => 'Template tidak ditemukan.']);
        }

        if (file_exists(public_path($template->file_path))) {
            unlink(public_path($template->file_path));
        }

        DB::table('label_templates')->delete($id);

        return redirect()->route('label-templates.index')
                         ->with('success', 'Template berhasil dihapus!');
    }
}