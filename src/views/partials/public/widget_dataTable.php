<?php

function generateDataTable(
    $dataTableID = 'default_',
    $checkboxEnabled = true,
    $uniqueIDTarget = 'null',
    $hiddenColumns = [],
    $columnVisualNames = [],
    $filterBy = [], // ['role' => 'admin']
    $filterSelectorByColumns = [],
    $data = []
) {
    $headers = '';
    $dataRows = '';

    // Calculate the number of visible columns
    $visibleColumnCount = ($checkboxEnabled ? 1 : 0); // Checkbox column if enabled
    if (!empty($data)) {
        $firstRow = $data[0];
        foreach ($firstRow as $key => $value) {
            if (!in_array($key, $hiddenColumns)) {
                $visibleColumnCount++;
                $headers .= "<th>" . htmlspecialchars($columnVisualNames[$key] ?? $key, ENT_QUOTES, 'UTF-8') . "</th>";
            }
        }
    }

    $visibleColumnCount++; // Add 1 for the Action column
    $headers = ($checkboxEnabled ? '<th><input type="checkbox" name="checkbox_selectAll" id="checkbox_selectAll" title="Select All" class="form-check-input"></th>' : '') . $headers;
    $headers .= "<th>Action</th>";

    // Filter data if applicable
    $filterResult = !empty($filterBy)
        ? array_filter($data, function ($row) use ($filterBy) {
            foreach ($filterBy as $key => $value) {
                if (isset($row[$key]) && $row[$key] !== $value) {
                    return false;
                }
            }
            return true;
        })
        : $data;

    // Reindex the filtered array
    $filterResult = array_values($filterResult);

    // Generate table rows dynamically
    foreach ($filterResult as $row) {
        $rowHTML = '<tr>';
        if ($checkboxEnabled) {
            $rowHTML .= '<td><input type="checkbox" class="form-check-input" value="' . htmlspecialchars($row[$uniqueIDTarget] ?? '', ENT_QUOTES, 'UTF-8') . '"></td>';
        }
        foreach ($row as $key => $value) {
            if (!in_array($key, $hiddenColumns)) {
                $rowHTML .= '<td>' . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '</td>';
            }
        }
        $uniqueIDValue = htmlspecialchars($row[$uniqueIDTarget] ?? '', ENT_QUOTES, 'UTF-8');
        $rowHTML .= '<td><a href="' . htmlspecialchars(updateUrlParams(['viewRole' => $_GET['viewRole'] ?? '', $uniqueIDTarget => $uniqueIDValue]), ENT_QUOTES, 'UTF-8') . '" title="Configure"><i class="bi bi-pencil-square"></i></a></td>';
        $rowHTML .= '</tr>';
        $dataRows .= $rowHTML;
    }

    // Handle case where no rows are available
    if (empty($dataRows)) {
        $dataRows = '<tr><td colspan="' . $visibleColumnCount . '" class="text-center">No data available</td></tr>';
    }

    // Adjust DataTable columnDefs to disable ordering for the Action column
    $totalColumns = $visibleColumnCount - 1; // Total visible columns excluding the Action column

    return <<<HTML
        <style>
            .pagination {
                --bs-pagination-color: var(--c-brand-primary-a0);
                --bs-pagination-active-bg: var(--c-brand-primary-a40);
                --bs-pagination-active-border-color: var(--c-brand-primary-a40);
            }
        </style>
        <div class="actionControls mb-2 p-1 bg-transparent d-flex gap-2 justify-content-end align-items-center">
            <button class="btn btn-danger" onclick="javascript:alert(1)">
                <i class="bi bi-trash"></i>
                Remove Selection
            </button>
        </div>
        <table id="$dataTableID" class="table table-striped border" style="width: 100%">
            <thead style="background-color: var(--c-brand-primary-a0) !important;">
                <tr>
                    $headers
                </tr>
            </thead>
            <tbody>
                $dataRows
            </tbody>
        </table>
        <script>
            $(document).ready(function() {
                // Initialize DataTable
                $('#$dataTableID').DataTable({
                    columnDefs: [
                        {"orderable": false, "targets": [$totalColumns]}
                    ],
                    language: {
                        "paginate": {
                            previous: '<span class="bi bi-chevron-left"></span>',
                            next: '<span class="bi bi-chevron-right"></span>'
                        },
                        "lengthMenu": '<select class="form-control input-sm">' +
                            '<option value="10">10</option>' +
                            '<option value="20">20</option>' +
                            '<option value="30">30</option>' +
                            '<option value="40">40</option>' +
                            '<option value="50">50</option>' +
                            '<option value="-1">All</option>' +
                            '</select> Entries per page',
                    }
                });

                // Select All functionality
                $('#checkbox_selectAll').on('change', function() {
                    const isChecked = $(this).is(':checked');
                    $('#$dataTableID tbody input[type="checkbox"]').prop('checked', isChecked);
                });

                // Ensure "Select All" reflects individual checkbox changes
                $('#$dataTableID tbody').on('change', 'input[type="checkbox"]', function() {
                    const totalCheckboxes = $('#$dataTableID tbody input[type="checkbox"]').length;
                    const checkedCheckboxes = $('#$dataTableID tbody input[type="checkbox"]:checked').length;

                    $('#checkbox_selectAll').prop('checked', totalCheckboxes === checkedCheckboxes);
                });
            });
        </script>
    HTML;
}
