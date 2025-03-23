<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Include DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

    <!-- Include jQuery and DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

    <title>Document</title>

    
</head>

<body>
    <table id="reportTable" class="display nowrap" style="width:100%">
        <thead>
            <tr>
                <th>Date</th>
                <th>Send OTP</th>
                <th>Total Back From OTP</th>
                <th>OTP Match</th>
                <th>OTP Failed</th>
                <th>Payment Success</th>
                <th>Insufficient Credit</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $row)
                <tr>
                    <td>{{ $row['date'] }}</td>
                    <td>{{ $row['sendOTP'] }}</td>
                    <td>{{ $row['totalBackFromOTP'] }}</td>
                    <td>{{ $row['otpMatch'] }}</td>
                    <td>{{ $row['otpFailed'] }}</td>
                    <td>{{ $row['paymentSuccess'] }}</td>
                    <td>{{ $row['insufficientCredit'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>


    <script>
        $(document).ready(function() {
            $('#reportTable').DataTable({
                dom: 'Bfrtip', // Adds Buttons
                buttons: [{
                    extend: 'excelHtml5', // Export to Excel
                    title: 'Bd gamers Daily (BDGD)', // Set your custom title here
                    className: 'custom-button'
                }]
            });
        });
    </script>
</body>

</html>
