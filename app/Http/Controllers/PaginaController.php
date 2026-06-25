<?php

namespace App\Http\Controllers;

use App\Models\Pagina;
use Illuminate\Http\Request;

class PaginaController extends Controller
{
    // Carga la vista principal al entrar al sistema
    public function index()
    {
        // Traemos las páginas raíz (sin padre) del usuario actual para el Sidebar
        $paginasPrivadas = auth()->user()->paginas()->whereNull('padre_id')->latest()->get();
        
        // Traemos las páginas compartidas con el usuario
        $paginasColaborativas = auth()->user()->paginasCompartidas()->whereNull('padre_id')->get();

        return view('paginas.index', compact('paginasPrivadas', 'paginasColaborativas'));
    }

    // Muestra una página específica, sus subpáginas y su gestor de tareas
    public function show(Pagina $pagina)
    {
        // Validamos seguridad básica: que sea tuya o estés invitado
        // (Podés ampliar esta lógica después)
        
        // Cargamos las relaciones que necesitamos mostrar en la vista
        $pagina->load('subpaginas', 'tareas', 'miembros');

        // Volvemos a mandar las listas del sidebar para que no desaparezca el menú
        $paginasPrivadas = auth()->user()->paginas()->whereNull('padre_id')->latest()->get();
        $paginasColaborativas = auth()->user()->paginasCompartidas()->whereNull('padre_id')->get();

        return view('paginas.show', compact('pagina', 'paginasPrivadas', 'paginasColaborativas'));
    }

    // Crea una nueva página o subpágina
    public function store(Request $request)
    {
        $data = $request->validate([
            'titulo' => 'nullable|string|max:255',
            'padre_id' => 'nullable|exists:paginas,id', // Si viene este dato, es una subpágina
        ]);

        $data['user_id'] = auth()->id();
        $data['titulo'] = $data['titulo'] ?? 'Nueva página';
        $data['icono'] = '📄'; // Ícono por defecto

        $pagina = Pagina::create($data);

        return redirect()->route('paginas.show', $pagina);
    }

    // Actualiza la página (por ejemplo, su título)
    public function update(Request $request, Pagina $pagina)
    {
        if ($pagina->user_id !== auth()->id()) {
            abort(403, 'Solo el creador puede editar esta página.');
        }

        $data = $request->validate([
            'titulo' => 'required|string|max:255',
        ]);

        $pagina->update($data);

        return redirect()->back();
    }

    // Elimina la página (y en cascada sus subpáginas y tareas gracias a la BD)
    public function destroy(Pagina $pagina)
    {
        if ($pagina->user_id !== auth()->id()) {
            abort(403, 'Solo el creador puede eliminar esta página.');
        }

        $pagina->delete();

        return redirect()->route('paginas.index');
    }
}