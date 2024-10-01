<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Promotions</title>
    <style>
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        border: 1px solid #ddd;
        padding: 8px;
    }
    th {
        background-color: #f2f2f2;
    }
</style>

</head>
<body>
    <h1>Liste des Promotions</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Libellé</th>
                <th>Date Début</th>
                <th>Date Fin</th>
                <th>État</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($promotions as $promotion)
            <tr>
                <td>{{ $promotion->id }}</td>
                <td>{{ $promotion->libelle }}</td>
                <td>{{ $promotion->date_debut }}</td>
                <td>{{ $promotion->date_fin }}</td>
                <td>{{ $promotion->etat }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
