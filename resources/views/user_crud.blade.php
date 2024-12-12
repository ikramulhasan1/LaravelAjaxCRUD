<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Ajax CRUD</title>
    </span>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.css"><span class="pln">
        <meta name="csrf-token" content="{{ csrf_token() }}">

</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center">Laravel Ajax CRUD</h2>

        <div class="card mb-4">
            <div class="card-header">Add Record</div>
            <div class="card-body">
                <form id="dataForm">
                    <input type="text" name="name" class="form-control mb-2" placeholder="Name (max 10 chars)">
                    <input type="email" name="email" class="form-control mb-2" placeholder="Email">
                    <input type="text" name="phone" class="form-control mb-2" placeholder="Phone">
                    <input type="file" name="pdf" class="form-control mb-2" accept="application/pdf">
                    <input type="file" name="image" class="form-control mb-2" accept="image/*">
                    <button type="submit" class="btn btn-success">Add</button>
                </form>
            </div>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="dataTable"></tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {
            fetchRecords();

            // Fetch all records
            function fetchRecords() {
                $.get("{{ route('user-data.index') }}", function(data) {
                    $('#dataTable').html('');
                    data.forEach(record => {
                        $('#dataTable').append(`
                    <tr>
                        <td>${record.name}</td>
                        <td>${record.email}</td>
                        <td>${record.phone}</td>
                        <td>
                            <button class="btn btn-danger btn-sm" onclick="deleteRecord(${record.id})">Delete</button>
                        </td>
                    </tr>
                `);
                    });
                });
            }

            // Store new record
            $('#dataForm').on('submit', function(e) {
                e.preventDefault();

                let formData = new FormData(this);
                $.ajax({
                    url: "{{ route('user-data.store') }}",
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        alert(response.success);
                        fetchRecords();
                        $('#dataForm')[0].reset();
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error:", status, error);
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            console.error("Validation Errors:", xhr.responseJSON.errors);
                            let errorMessages = [];
                            for (const key in xhr.responseJSON.errors) {
                                errorMessages = errorMessages.concat(xhr.responseJSON.errors[key]);
                            }
                            alert(errorMessages.join('\n'));
                        } else if (xhr.responseJSON && xhr.responseJSON.error) {
                            console.error("Server Error:", xhr.responseJSON.error);
                            alert("Server Error: " + xhr.responseJSON.error);
                        } else {
                            alert("An error occurred. Please check the console.");
                        }
                    }
                });
            });

            window.deleteRecord = function(id) {
                $.ajax({
                    url: `/user-data/${id}`,
                    method: 'DELETE',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        alert(response.success);
                        fetchRecords();
                    }
                });
            };
        });
    </script>
</body>

</html>