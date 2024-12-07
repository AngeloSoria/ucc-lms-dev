<?php
// Fake data
$data = [
    ['id' => 1, 'name' => 'John Doe', 'age' => 25],
    ['id' => 2, 'name' => 'Jane Smith', 'age' => 30],
    ['id' => 3, 'name' => 'Alice Johnson', 'age' => 28],
];

// Handle AJAX request to update fake data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $field = $_POST['field'];
    $value = $_POST['value'];

    // Simulate a successful update
    echo json_encode([
        'success' => true,
        'message' => "Field '{$field}' for ID {$id} updated to '{$value}'"
    ]);
    exit; // End execution after handling AJAX
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editable Table with Fake Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <h1>Editable Table (Fake Data)</h1>
    <table border="1" id="editableTable" class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Age</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $row): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td contenteditable="true" data-id="<?= $row['id'] ?>" data-field="name"><?= $row['name'] ?></td>
                    <td contenteditable="true" data-id="<?= $row['id'] ?>" data-field="age"><?= $row['age'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <script>
        $(document).ready(function() {
            $('#editableTable').on('blur', '[contenteditable="true"]', function() {
                let id = $(this).data('id');
                let field = $(this).data('field');
                let value = $(this).text();

                // Simulate sending data to the server
                $.ajax({
                    url: '', // This file will handle the request
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        id: id,
                        field: field,
                        value: value
                    },
                    success: function(response) {
                        if (response.success) {
                            console.log(response.message);
                        } else {
                            console.error('Failed to update');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Update failed: ', error);
                    }
                });
            });
        });
    </script>
</body>

</html>