<html>

<head>
    <meta charset="utf-8">
    <title>Acquisition Slip</title>
    <style>
        @page {
            margin: 50px;
            font-family: 'sans-serif';
            font-size: 12px;

        }

        body {
            padding-bottom: 10px;
        }

        header {
            width: 100%;
            font-size: 14px;
        }

        header table td {
            vertical-align: top;
            font-size: 13px;
        }

        header table .tags {
            font-size: 12px;
            text-align: right;
        }

        .main-body {
            width: 100%;
            padding: 1.5rem 0;
            border-top: 1px solid rgba(0, 0, 0, 0.5);
            border-bottom: 1px solid rgba(0, 0, 0, 0.5);
        }

        .acquistion-details th {
            width: 25%;
            font-weight: normal;
        }

        .acquistion-details td,
        .acquistion-details th {
            vertical-align: top;
            text-align: left;
            padding: 3px 10px;
        }

        .accessioning td,
        .accessioning th {
            padding: 5px;
        }

        footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            opacity: 0.75;
            vertical-align: middle;
        }
    </style>
</head>

<body>
    <header>
        <table style="width: 100%">
            <tbody>
                <tr>
                    <td style="width: 60px;">
                        <img src="{{ $logo }}" width="45" alt="Logo">
                    </td>
                    <td>
                        <h6 style="font-size: 16px; margin:0;margin-bottom: 5px 0; text-transform: uppercase;">
                            Isabela State University
                        </h6>
                        <p style="margin: 0; margin-bottom: 3px"> {{ $data['campus'] }} ({{ $data['branch'] }})</p>
                        {{-- <br> --}}
                        <p style="margin: 0;">{{ $data['campus_address'] }}</p>
                    </td>
                    <td class="tags">
                        <strong>Order ID: {{ $data['transaction_id'] }}</strong>
                        <br>
                        <span>{{ $data['transaction_date'] }}</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </header>

    <h3 style="font-weight: bold; text-align: center; margin: 25px; color: #07a451">ACQUISITION SLIP</h3>

    <div class="main-body">
        <div style="margin-bottom: 1.5rem">
            <h4 style="margin: 0; margin-bottom: 0.8rem;">GENERAL INFORMATION</h4>
            <table class="acquistion-details" style="width: 100%; margin-bottom: 10px;">
                <tbody>
                    <tr>
                        <th>Transaction ID</th>
                        <td>{{ $data['transaction_id'] }}</td>
                    </tr>
                    <tr>
                        <th>Item Name</th>
                        <td>{{ $data['title'] }}</td>
                    </tr>
                    <tr>
                        <th>Call Number</th>
                        <td>{{ $data['call_number'] }}</td>
                    </tr>
                    <tr>
                        <th>Mode of Acquisition </th>
                        <td style="text-transform: capitalize">{{ $data['acquisition_mode'] }}</td>
                    </tr>
                    <tr>
                        <th>Date of Acquisition</th>
                        <td>{{ $data['acquisition_date'] }}</td>
                    </tr>
                    <tr>
                        <th>Dealer</th>
                        <td>{{ $data['dealer'] }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div style="margin-bottom: 1.5rem">
            <h4 style="margin: 0; margin-bottom: 0.8rem;">TOTAL COST</h4>
            <table class="acquistion-details" style="width: 100%">
                <tbody>
                    <tr>
                        <th>Quantity</th>
                        <td>{{ $data['qty'] }}</td>
                    </tr>
                    <tr>
                        <th>Unit Price</th>
                        <td>PHP {{ number_format($data['unit_price'], 2) }}</td>
                    </tr>
                    <tr>
                        <th>Discount</th>
                        <td>PHP {{ number_format($data['discount'], 2) }}</td>
                    </tr>
                    <tr style="font-weight: bold">
                        <th>Total Price ({{ $data['unit_price'] . ' x ' . $data['qty'] }})</th>
                        <td style="font-size: 1.25rem">PHP {{ number_format($data['total_cost'], 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <hr>

        <div style="margin-top: 1.5rem; margin-bottom: 1.5rem">
            <h4 style="margin: 0; margin-bottom: 0.8rem;">COPIES INFORMATION</h4>
            <table class="accessioning" style="width: 100%; table-layout: fixed; text-align: center; margin">
                <thead>
                    <tr>
                        <th style="width: 10%">COPY</th>
                        <th style="width: 75%">ACCESSION NUMBER</th>
                        <th style="width: 15%">STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data['accessions'] as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->accession_number ?? 'Not Set' }}</td>
                            <td style="text-transform: capitalize">{{ $item->status }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div style="margin-top: 2.5rem">
        <h4 style="margin: 0; margin-bottom: 2.5rem;">Signed By:</h4>
        <table class="" style="width: 100%; table-layout: fixed; text-align: left;">
            <thead>
                <tr>
                    <td style="text-align: left; padding-bottom: 1.5rem;">Recorded by</td>
                    <td style="text-align: left; padding-bottom: 1.5rem;">Approved by</td>
                </tr>
            </thead>
            <tbody>
                <tr style="text-transform: uppercase; font-weight: 700">
                    <td>{{ $data['recorded_by'] }}</td>
                    <td>{{ $data['approved_by'] }}</td>
                </tr>
                <tr>
                    <td>{{ $data['recorder_information'] }}</td>
                    <td>{{ $data['approved_information'] }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <footer>

        <table style="width: 100%; table-layout: fixed">
            <tbody>
                <tr>
                    <td>
                        Property of Isabela State University |
                        <img src="{{ $logo }}" width="10" alt="Logo" style="margin-right: 2px;">
                        e-Libra
                    </td>
                    <td style="text-align: right">
                        {{ date('F m, Y - h:i A') }}
                    </td>
                </tr>
            </tbody>
        </table>
    </footer>
</body>

</html>
