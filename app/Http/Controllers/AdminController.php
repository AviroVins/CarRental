<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class AdminController extends Controller
{
    // Lista tabel
    private $tables = ['users', 'cars', 'reservations', 'rentals', 'payments'];

    // Klucze główne dla tabel
    private $primaryKeys = [
        'users' => 'user_id',
        'cars' => 'plate_number',
        'reservations' => 'reservation_id',
        'rentals' => 'rental_id',
        'payments' => 'payment_id',
    ];

    // Relacje (klucze obce) - do prostego podawania w formularzach
    private $foreignKeys = [
        'reservations' => ['user_id' => 'users', 'plate_number' => 'cars'],
        'rentals' => ['reservation_id' => 'reservations'],
        'payments' => ['rental_id' => 'rentals'],
    ];

    // Wykluczamy kolumny do formularza np. timestampy
    private $excludeColumns = ['created_at', 'updated_at'];

    public function index(): View
    {
        return view('admin.dashboard', ['tables' => $this->tables]);
    }

    public function showTable(string $name): View
    {
        if (!in_array($name, $this->tables)) {
            abort(404, 'Tabela nie istnieje.');
        }

        $data = DB::table($name)->get();

        return view('admin.table', compact('name', 'data'));
    }

    // Formularz dodawania rekordu
    public function create(string $name): View
    {
        $this->checkTable($name);

        $columns = $this->getTableColumns($name);
        $primaryKey = $this->primaryKeys[$name];
        $excludeColumns = array_merge($this->excludeColumns, [$primaryKey]);

        $relatedData = $this->getRelatedData($name);

        return view('admin.form', compact('name', 'columns', 'primaryKey', 'excludeColumns', 'relatedData'))
               ->with('isEdit', false);
    }

    // Zapis nowego rekordu
    public function store(Request $request, string $name)
    {
        $this->checkTable($name);

        $columns = $this->getTableColumns($name);
        $primaryKey = $this->primaryKeys[$name];
        $excludeColumns = array_merge($this->excludeColumns, [$primaryKey]);

        // Walidacja - prosta, wszystkie kolumny wymagane, poza kluczem głównym i wykluczonymi
        $rules = [];
        foreach ($columns as $col) {
            if (!in_array($col, $excludeColumns)) {
                $rules[$col] = 'required';
            }
        }

        $validated = $request->validate($rules);

        DB::table($name)->insert($validated);

        return redirect()->route('admin.table', ['name' => $name])
                         ->with('success', "Rekord dodany do tabeli $name.");
    }

    // Formularz edycji rekordu
    public function edit(string $name, $id): View
    {
        $this->checkTable($name);

        $primaryKey = $this->primaryKeys[$name];

        $record = DB::table($name)->where($primaryKey, $id)->first();

        if (!$record) {
            abort(404, 'Rekord nie znaleziony.');
        }

        $record = (array) $record;

        $columns = $this->getTableColumns($name);
        $excludeColumns = $this->excludeColumns;

        $relatedData = $this->getRelatedData($name);

        return view('admin.form', compact('name', 'columns', 'primaryKey', 'excludeColumns', 'record', 'relatedData'))
               ->with('isEdit', true);
    }

    // Aktualizacja rekordu
    public function update(Request $request, string $name, $id)
    {
        $this->checkTable($name);

        $columns = $this->getTableColumns($name);
        $primaryKey = $this->primaryKeys[$name];
        $excludeColumns = $this->excludeColumns;

        $rules = [];
        foreach ($columns as $col) {
            if (!in_array($col, $excludeColumns) && $col !== $primaryKey) {
                $rules[$col] = 'required';
            }
        }

        $validated = $request->validate($rules);

        DB::table($name)->where($primaryKey, $id)->update($validated);

        return redirect()->route('admin.table', ['name' => $name])
                         ->with('success', "Rekord zaktualizowany w tabeli $name.");
    }

    // Usuwanie rekordu
    public function destroy(string $name, $id)
    {
        $this->checkTable($name);

        $primaryKey = $this->primaryKeys[$name];

        DB::table($name)->where($primaryKey, $id)->delete();

        return redirect()->route('admin.table', ['name' => $name])
                         ->with('success', "Rekord usunięty z tabeli $name.");
    }

    // --- Metody pomocnicze ---

    private function checkTable(string $name)
    {
        if (!in_array($name, $this->tables)) {
            abort(404, 'Tabela nie istnieje.');
        }
    }

    private function getTableColumns(string $name): array
    {
        return Schema::getColumnListing($name);
    }

    private function getRelatedData(string $name): array
    {
        $relatedData = [];
        if (isset($this->foreignKeys[$name])) {
            foreach ($this->foreignKeys[$name] as $fk => $relatedTable) {
                // Pobieramy tylko id i ewentualnie nazwę do wyboru
                // Załóżmy, że każda tabela ma 'name' lub 'plate_number' jako klucz do wyświetlenia
                $cols = Schema::getColumnListing($relatedTable);

                $displayCol = in_array('name', $cols) ? 'name' : $this->primaryKeys[$relatedTable] ?? $cols[0];

                $relatedData[$fk] = DB::table($relatedTable)->select($this->primaryKeys[$relatedTable], $displayCol)->get();
            }
        }
        return $relatedData;
    }
}
