<?php

$RETRIEVED_USERS = $userController->getAllUsers();
?>
<style>
    .table>* {
        font-family: var(--bs-body-font-family) !important;
    }
</style>
<table id="example" class="table table-striped border" style="width: 100%">
    <thead style="background-color: var(--c-brand-primary-a0) !important;">
        <tr>
            <th>User Id</th>
            <th>Username</th>
            <th>FirstName</th>
            <th>LastName</th>
            <th>Date of Birth</th>
            <th>Role</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (isset($RETRIEVED_USERS['data']) && count($RETRIEVED_USERS['data']) > 0) {
            // print_r($RETRIEVED_USERS);
            foreach ($RETRIEVED_USERS['data'] as $userdata => $user) {
                if (strtolower($_GET['view']) !== strtolower($user['role'])) {
                    continue;
                }

                $userid = $user['user_id'];
                $username = $user['username'];
                $firstname = $user['first_name'];
                $lastname = $user['last_name'];
                $dob = $user['dob'];
                $role = $user['role'];
                $status = $user['status'];
                echo <<<HTML
                    <tr>
                        <td>$userid</td>
                        <td>$username</td>
                        <td>$firstname</td>
                        <td>$lastname</td>
                        <td>$dob</td>
                        <td>$role</td>
                        <td>$status</td>
                        <td><a href='#' class='btn btn-primary'>Edit</a></td>
                    </tr>
                HTML;
            }
        }
        ?>
</table>

<script>
    $(document).ready(function() {
        $('#example').DataTable({
            //disable sorting on last column
            "columnDefs": [{
                "orderable": false,
                "targets": 5
            }],
            language: {
                //customize pagination prev and next buttons: use arrows instead of words
                'paginate': {
                    'previous': '<span class="bi bi-chevron-left"></span>',
                    'next': '<span class="bi bi-chevron-right"></span>'
                },
                //customize number of elements to be displayed
                "lengthMenu": 'Display <select class="form-control input-sm">' +
                    '<option value="10">10</option>' +
                    '<option value="20">20</option>' +
                    '<option value="30">30</option>' +
                    '<option value="40">40</option>' +
                    '<option value="50">50</option>' +
                    '<option value="-1">All</option>' +
                    '</select> results'
            }
        })
    });
</script>