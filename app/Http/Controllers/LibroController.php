<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\LibroRequest;

class LibroController extends Controller
{
    public function index()
    {
        if (!session('usuario_logueado')) {
            return redirect()->route('login');
        }

        try {
            // Obtener todos los libros usando consulta directa
            $libros = DB::connection('oracle')->select('
                SELECT id, titulo, autor, isbn, categoria, descripcion, disponible, fecha_publicacion, created_at 
                FROM libros 
                ORDER BY titulo
            ');

            return view('libros.index', compact('libros'));

        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with('error', 'Error al cargar libros: ' . $e->getMessage());
        }
    }

    public function create()
    {
        if (!session('usuario_logueado')) {
            return redirect()->route('login');
        }

        return view('libros.create');
    }

    public function store(Request $request)
    {
        if (!session('usuario_logueado')) {
            return redirect()->route('login');
        }

        $request->validate([
            'titulo' => 'required|string|max:200',
            'autor' => 'required|string|max:150',
            'isbn' => 'nullable|string|max:20',
            'categoria' => 'nullable|string|max:50',
            'descripcion' => 'nullable|string',
            'fecha_publicacion' => 'nullable|date'
        ]);

        try {
            // Usar procedimiento almacenado pkg_libros.crear_libro
            $pdo = DB::connection('oracle')->getPdo();
            $stmt = $pdo->prepare('
                BEGIN 
                    pkg_libros.crear_libro(:titulo, :autor, :isbn, :categoria, :descripcion, :fecha_publicacion, :result); 
                END;
            ');

            $titulo = $request->titulo;
            $autor = $request->autor;
            $isbn = $request->isbn;
            $categoria = $request->categoria;
            $descripcion = $request->descripcion;
            $fecha_publicacion = $request->fecha_publicacion ? date('Y-m-d', strtotime($request->fecha_publicacion)) : null;
            $result = null;

            $stmt->bindParam(':titulo', $titulo);
            $stmt->bindParam(':autor', $autor);
            $stmt->bindParam(':isbn', $isbn);
            $stmt->bindParam(':categoria', $categoria);
            $stmt->bindParam(':descripcion', $descripcion);
            $stmt->bindParam(':fecha_publicacion', $fecha_publicacion);
            $stmt->bindParam(':result', $result, \PDO::PARAM_INT);

            $stmt->execute();

            if ($result == 1) {
                return redirect()->route('libros.index')->with('success', 'Libro creado exitosamente');
            } elseif ($result == -2) {
                return back()->with('error', 'El ISBN ya existe')->withInput();
            } else {
                return back()->with('error', 'Error al crear libro')->withInput();
            }

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        if (!session('usuario_logueado')) {
            return redirect()->route('login');
        }

        try {
            $libro = DB::connection('oracle')->select('
                SELECT id, titulo, autor, isbn, categoria, descripcion, disponible, fecha_publicacion, created_at 
                FROM libros 
                WHERE id = ?
            ', [$id]);

            if (empty($libro)) {
                return redirect()->route('libros.index')->with('error', 'Libro no encontrado');
            }

            return view('libros.show', ['libro' => $libro[0]]);

        } catch (\Exception $e) {
            return redirect()->route('libros.index')->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        if (!session('usuario_logueado')) {
            return redirect()->route('login');
        }

        try {
            $libro = DB::connection('oracle')->select('
                SELECT id, titulo, autor, isbn, categoria, descripcion, disponible, fecha_publicacion 
                FROM libros 
                WHERE id = ?
            ', [$id]);

            if (empty($libro)) {
                return redirect()->route('libros.index')->with('error', 'Libro no encontrado');
            }

            return view('libros.edit', ['libro' => $libro[0]]);

        } catch (\Exception $e) {
            return redirect()->route('libros.index')->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        if (!session('usuario_logueado')) {
            return redirect()->route('login');
        }

        $request->validate([
            'titulo' => 'required|string|max:200',
            'autor' => 'required|string|max:150',
            'isbn' => 'nullable|string|max:20',
            'categoria' => 'nullable|string|max:50',
            'descripcion' => 'nullable|string',
            'disponible' => 'boolean',
            'fecha_publicacion' => 'nullable|date'
        ]);

        try {
            // Usar procedimiento almacenado pkg_libros.actualizar_libro
            $pdo = DB::connection('oracle')->getPdo();
            $stmt = $pdo->prepare('
                BEGIN 
                    pkg_libros.actualizar_libro(:id, :titulo, :autor, :isbn, :categoria, :descripcion, :disponible, :result); 
                END;
            ');

            $titulo = $request->titulo;
            $autor = $request->autor;
            $isbn = $request->isbn;
            $categoria = $request->categoria;
            $descripcion = $request->descripcion;
            $disponible = $request->has('disponible') ? 1 : 0;
            $result = null;

            $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
            $stmt->bindParam(':titulo', $titulo);
            $stmt->bindParam(':autor', $autor);
            $stmt->bindParam(':isbn', $isbn);
            $stmt->bindParam(':categoria', $categoria);
            $stmt->bindParam(':descripcion', $descripcion);
            $stmt->bindParam(':disponible', $disponible, \PDO::PARAM_INT);
            $stmt->bindParam(':result', $result, \PDO::PARAM_INT);

            $stmt->execute();

            if ($result == 1) {
                return redirect()->route('libros.index')->with('success', 'Libro actualizado exitosamente');
            } elseif ($result == -1) {
                return back()->with('error', 'Libro no encontrado')->withInput();
            } else {
                return back()->with('error', 'Error al actualizar libro')->withInput();
            }

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        if (!session('usuario_logueado') || session('usuario_rol') !== 'BIBLIOTECARIO') {
            return redirect()->route('dashboard')->with('error', 'No tiene permisos para esta acción');
        }

        try {
            // Eliminar directamente (o usar procedimiento si lo tienes)
            DB::connection('oracle')->delete('DELETE FROM libros WHERE id = ?', [$id]);
            
            return redirect()->route('libros.index')->with('success', 'Libro eliminado exitosamente');

        } catch (\Exception $e) {
            return redirect()->route('libros.index')->with('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }

    public function buscar(Request $request)
    {
        if (!session('usuario_logueado')) {
            return redirect()->route('login');
        }

        $termino = $request->get('q', '');

        if (empty($termino)) {
            return redirect()->route('libros.index');
        }

        try {
            // Usar procedimiento de búsqueda
            $pdo = DB::connection('oracle')->getPdo();
            $stmt = $pdo->prepare('BEGIN :cursor := pkg_libros.buscar_libros(:termino); END;');
            $cursor = null;
            $stmt->bindParam(':termino', $termino);
            $stmt->bindParam(':cursor', $cursor, \PDO::PARAM_STMT);
            $stmt->execute();

            $libros = [];
            if ($cursor) {
                while ($row = $cursor->fetch(\PDO::FETCH_ASSOC)) {
                    $libros[] = (object) $row; // Convertir array a objeto
                }
            }

            return view('libros.index', compact('libros', 'termino'));

        } catch (\Exception $e) {
            return redirect()->route('libros.index')->with('error', 'Error en búsqueda: ' . $e->getMessage());
        }
    }
}