<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>{{ (!empty($sub_status) && $sub_status == 'proforma') ? __('lang_v1.proforma_invoice') : 'QUOTATION' }}-{{ $receipt_details->invoice_no }}</title>
  <style>
    body {
      font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
      margin: 0;
      padding: 0;
      color: #111111;
      background: #ffffff;
    }
    table {
      border-collapse: collapse;
    }
  </style>
</head>
<body>
  @include('sale_pos.receipts.pinterix')
</body>
</html>
