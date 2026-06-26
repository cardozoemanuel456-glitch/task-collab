<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaginaRequest;
use App\Http\Requests\UpdatePaginaRequest;
use App\Models\Pagina;
use Illuminate\Support\Facades\Gate;

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
        Gate::authorize('view', $pagina);

        // Cargamos las relaciones que necesitamos mostrar en la vista
        $pagina->load('subpaginas', 'tareas', 'miembros');

        // Volvemos a mandar las listas del sidebar para que no desaparezca el menú
        $paginasPrivadas = auth()->user()->paginas()->whereNull('padre_id')->latest()->get();
        $paginasColaborativas = auth()->user()->paginasCompartidas()->whereNull('padre_id')->get();

        return view('paginas.show', compact('pagina', 'paginasPrivadas', 'paginasColaborativas'));
    }

    // Crea una nueva página o subpágina
    public function store(StorePaginaRequest $request)
    {
        $data = $request->validated();

        $data['user_id'] = auth()->id();
        $data['titulo'] = $data['titulo'] ?? 'Nueva página';
        $data['icono'] = '📄'; // Ícono por defecto

        $pagina = Pagina::create($data);

        return redirect()->route('paginas.show', $pagina);
    }

    // Actualiza la página (por ejemplo, su título)
    public function update(UpdatePaginaRequest $request, Pagina $pagina)
    {
        $data = $request->validated();

        $pagina->update($data);

        return redirect()->back();
    }

    // Elimina la página (y en cascada sus subpáginas y tareas gracias a la BD)
    public function destroy(Pagina $pagina)
    {
        Gate::authorize('delete', $pagina);

        $pagina->delete();

        return redirect()->route('paginas.index');
    }
}
