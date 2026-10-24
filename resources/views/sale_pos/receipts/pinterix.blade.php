<!-- Pinterix Quotation / Invoice Template -->
@php
  $formatAmount = function($val) {
      if (empty($val)) return 'Free';
      $num = floatval(str_replace([',', ' '], '', $val));
      if ($num == 0) return 'Free';
      return 'Rs ' . number_format($num, $num == intval($num) ? 0 : 2, '.', '');
  };

  $formatTotal = function($val) {
      if (empty($val)) return '';
      $num = floatval(str_replace([',', ' '], '', $val));
      if ($num == 0) return '';
      return 'Rs ' . number_format($num, $num == intval($num) ? 0 : 2, '.', '');
  };

  // Safe line calculation
  $lines_sum = 0;
  if (!empty($receipt_details->lines)) {
      foreach ($receipt_details->lines as $l) {
          $l_total = isset($l['line_total_uf']) && floatval($l['line_total_uf']) > 0 
              ? floatval($l['line_total_uf']) 
              : floatval(preg_replace('/[^0-9.]/', '', $l['line_total'] ?? 0));
          $lines_sum += $l_total;
      }
  }

  // Subtotal
  if (!empty($receipt_details->subtotal_unformatted) && floatval($receipt_details->subtotal_unformatted) > 0) {
      $subtotal_val = floatval($receipt_details->subtotal_unformatted);
  } elseif (!empty($receipt_details->subtotal)) {
      $subtotal_val = floatval(preg_replace('/[^0-9.]/', '', $receipt_details->subtotal));
  } else {
      $subtotal_val = $lines_sum;
  }

  // Total
  if (!empty($receipt_details->total_unformatted) && floatval($receipt_details->total_unformatted) > 0) {
      $total_val = floatval($receipt_details->total_unformatted);
  } elseif (!empty($receipt_details->total)) {
      $total_val = floatval(preg_replace('/[^0-9.]/', '', $receipt_details->total));
  } elseif (!empty($receipt_details->final_total)) {
      $total_val = floatval(preg_replace('/[^0-9.]/', '', $receipt_details->final_total));
  } else {
      $total_val = $subtotal_val > 0 ? $subtotal_val : $lines_sum;
  }

  if ($total_val == 0 && $lines_sum > 0) {
      $total_val = $lines_sum;
  }

  $subtotal_disp = 'Rs ' . number_format($subtotal_val, $subtotal_val == intval($subtotal_val) ? 0 : 2, '.', '');
  $total_disp = 'Rs ' . number_format($total_val, $total_val == intval($total_val) ? 0 : 2, '.', '');
  
  // Date only (remove time)
  $date_only = '';
  if (!empty($receipt_details->invoice_date)) {
      $date_parts = explode(' ', trim($receipt_details->invoice_date));
      $date_only = $date_parts[0];
  } elseif (!empty($receipt_details->transaction_date)) {
      $date_only = \Carbon\Carbon::parse($receipt_details->transaction_date)->format('m/d/Y');
  }

  $heading = !empty($receipt_details->invoice_heading) ? $receipt_details->invoice_heading : 'Quotation';
@endphp

<div class="pinterix-invoice-wrapper" style="position: relative; background: #ffffff; color: #111111; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; width: 100%; max-width: 820px; margin: 0 auto; padding: 25px 35px 35px 35px; box-sizing: border-box; overflow: hidden; min-height: 1100px;">

  <!-- Vector Corner Wave Decorations -->
  <!-- Top Right Wave -->
  <svg class="corner-top-right" viewBox="0 0 350 120" style="position: absolute; top: 0; right: 0; width: 280px; height: 96px; pointer-events: none; z-index: 1;">
    <polygon points="40,0 350,0 350,30 160,18" fill="#32b9f1" />
    <polygon points="170,0 350,0 350,85 240,48" fill="#0b5cb7" />
    <polygon points="260,0 350,0 350,120 310,65" fill="#073a78" opacity="0.9" />
  </svg>

  <!-- Bottom Right Wave (Widened & Spread Out) -->
  <svg class="corner-bottom-right" viewBox="0 0 360 220" style="position: absolute; bottom: 0; right: 0; width: 330px; height: 200px; pointer-events: none; z-index: 1;">
    <polygon points="0,220 360,20 360,220" fill="#32b9f1" />
    <polygon points="90,220 360,80 360,220" fill="#0b5cb7" />
    <polygon points="190,220 360,140 360,220" fill="#073a78" />
  </svg>

  <!-- Bottom Left Wave (Widened & Spread Out) -->
  <svg class="corner-bottom-left" viewBox="0 0 280 200" style="position: absolute; bottom: 0; left: 0; width: 250px; height: 180px; pointer-events: none; z-index: 1;">
    <polygon points="0,30 280,200 0,200" fill="#0b5cb7" />
    <polygon points="0,90 190,200 0,200" fill="#32b9f1" />
    <polygon points="0,150 110,200 0,200" fill="#073a78" />
  </svg>

  <!-- Main Content Container -->
  <div style="position: relative; z-index: 2;">
    
    <!-- Header Section -->
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 8px;">
      <tr>
        <td style="vertical-align: top; width: 55%;">
          <!-- Company Logo -->
          <div style="margin-bottom: 6px;">
            <img src="{{ asset('img/pinterix/pinterix_logo.jpeg') }}" alt="Printerix" style="max-height: 54px; width: auto; display: block;">
          </div>
          <!-- Company Details -->
          <div style="font-size: 13.5px; line-height: 1.45; color: #1a1a1a; font-weight: 500;">
            <div>20 / 11L Wetakeiyawala Road,</div>
            <div>Nugegoda.</div>
            <div>0770770261 | 0712812966 | 071418510</div>
            <div>Email - dmpsudarshana@gmail.com</div>
          </div>
        </td>
        <td style="vertical-align: middle; text-align: right; width: 45%;">
          <div style="font-family: 'Cinzel', 'Playfair Display', Georgia, 'Times New Roman', serif; font-size: 48px; font-weight: 500; color: #0b5cb7; letter-spacing: 0.5px; line-height: 1;">
            {{ $heading }}
          </div>
        </td>
      </tr>
    </table>

    <!-- Horizontal Blue Divider Line -->
    <div style="height: 3px; background-color: #0b5cb7; margin-top: 12px; margin-bottom: 22px; width: 100%;"></div>

    <!-- Customer & Quotation Meta Info -->
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 18px;">
      <tr>
        <td style="vertical-align: top;">
          <!-- Customer Name Only (Mobile number removed as requested) -->
          <div style="font-size: 22px; font-weight: 700; color: #000000; letter-spacing: -0.2px;">
            {{ !empty($receipt_details->customer_name) ? $receipt_details->customer_name : 'Catchy Lanka' }}
          </div>
        </td>
        <td style="vertical-align: top; text-align: right;">
          <div style="font-size: 13.5px; color: #222222; line-height: 1.5;">
            @if(!empty($receipt_details->invoice_no))
              <div><strong>{{ !empty($receipt_details->invoice_no_prefix) ? $receipt_details->invoice_no_prefix : 'Quotation No:' }}</strong> {{ $receipt_details->invoice_no }}</div>
            @endif
            @if(!empty($date_only))
              <div><strong>{{ !empty($receipt_details->date_label) ? rtrim($receipt_details->date_label, ':') . ':' : 'Date:' }}</strong> {{ $date_only }}</div>
            @endif
          </div>
        </td>
      </tr>
    </table>

    <!-- Products / Items Table -->
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
      <thead>
        <tr style="background-color: #0b5cb7; color: #ffffff;">
          <th style="padding: 10px 8px; font-size: 13.5px; font-weight: 700; text-align: center; width: 7%; letter-spacing: 0.5px;">NO</th>
          <th style="padding: 10px 14px; font-size: 13.5px; font-weight: 700; text-align: left; width: 53%; letter-spacing: 0.5px;">PRODUCT</th>
          <th style="padding: 10px 8px; font-size: 13.5px; font-weight: 700; text-align: center; width: 15%; letter-spacing: 0.5px;">PRICE</th>
          <th style="padding: 10px 8px; font-size: 13.5px; font-weight: 700; text-align: center; width: 11%; letter-spacing: 0.5px;">QTY</th>
          <th style="padding: 10px 14px; font-size: 13.5px; font-weight: 700; text-align: right; width: 14%; letter-spacing: 0.5px;">TOTAL</th>
        </tr>
      </thead>
      <tbody>
        @if(!empty($receipt_details->lines))
          @foreach($receipt_details->lines as $index => $line)
            @php
              $unit_price_raw = isset($line['unit_price_uf']) ? floatval($line['unit_price_uf']) : floatval(str_replace([',', ' '], '', $line['unit_price'] ?? 0));
              $line_total_raw = isset($line['line_total_uf']) ? floatval($line['line_total_uf']) : floatval(str_replace([',', ' '], '', $line['line_total'] ?? 0));
              $qty_raw = isset($line['quantity_uf']) ? floatval($line['quantity_uf']) : floatval(str_replace([',', ' '], '', $line['quantity'] ?? 0));
              $qty_disp = ($qty_raw == intval($qty_raw)) ? intval($qty_raw) : number_format($qty_raw, 2);

              $price_disp = ($unit_price_raw == 0) ? 'Free' : 'Rs ' . number_format($unit_price_raw, $unit_price_raw == intval($unit_price_raw) ? 0 : 2, '.', '');
              $total_disp_line = ($line_total_raw == 0) ? '' : 'Rs ' . number_format($line_total_raw, $line_total_raw == intval($line_total_raw) ? 0 : 2, '.', '');
            @endphp
            <tr style="border-bottom: 1px solid #f0f0f0;">
              <td style="padding: 12px 8px; text-align: center; font-weight: 600; font-size: 14px; color: #222222; vertical-align: middle;">
                {{ $index + 1 }}
              </td>
              <td style="padding: 12px 14px; font-weight: 600; font-size: 14px; color: #222222; line-height: 1.35; vertical-align: middle;">
                {{ $line['name'] }}
                @if(!empty($line['sub_sku']))
                  <small style="color: #666; font-weight: normal;">({{ $line['sub_sku'] }})</small>
                @endif
              </td>
              <td style="padding: 12px 8px; text-align: center; font-weight: 600; font-size: 14px; color: #222222; vertical-align: middle;">
                {{ $price_disp }}
              </td>
              <td style="padding: 12px 8px; text-align: center; font-weight: 600; font-size: 14px; color: #222222; vertical-align: middle;">
                {{ $qty_disp }}
              </td>
              <td style="padding: 12px 14px; text-align: right; font-weight: 600; font-size: 14px; color: #222222; vertical-align: middle;">
                {{ $total_disp_line }}
              </td>
            </tr>
          @endforeach
        @endif

        <!-- Aesthetic Placeholder Shaded Rows (as in reference document) -->
        @php
          $lineCount = !empty($receipt_details->lines) ? count($receipt_details->lines) : 0;
          $extraRows = max(0, 6 - $lineCount);
        @endphp
        @for($i = 0; $i < $extraRows; $i++)
          @php
            $isShaded = ($i % 2 == 0);
          @endphp
          <tr>
            <td colspan="5" style="padding: 5px 0;">
              <div style="height: 32px; background-color: {{ $isShaded ? '#f1f3f7' : 'transparent' }}; border-radius: 2px;"></div>
            </td>
          </tr>
        @endfor
      </tbody>
    </table>

    <!-- Totals and Authorized Signature Section -->
    <table style="width: 100%; border-collapse: collapse; margin-top: 15px; margin-bottom: 15px;">
      <tr>
        <!-- Left: Signature Box -->
        <td style="vertical-align: top; width: 45%;">
          <div style="display: inline-block;">
            <div style="border: 1px solid #c8d0dc; background: #ffffff; padding: 4px 12px; display: inline-block; min-width: 100px; margin-bottom: 8px;">
              <img src="{{ asset('img/pinterix/prasad_signature.png') }}" alt="Signature" style="height: 42px; width: auto; display: block;">
            </div>
            <div style="font-size: 13.5px; font-weight: 700; color: #111111; line-height: 1.3;">
              D M P S Dissanayaka
            </div>
            <div style="font-size: 12.5px; color: #333333; line-height: 1.3;">
              Authorized Signature
            </div>
          </div>
        </td>

        <!-- Right: Subtotal and Total -->
        <td style="vertical-align: top; width: 55%; text-align: right;">
          <div style="float: right; text-align: right; min-width: 260px;">
            <div style="margin-bottom: 8px;">
              <span style="font-size: 15px; font-weight: 700; color: #111111; display: inline-block; min-width: 110px; text-align: right; margin-right: 25px;">SUBTOTAL:</span>
              <span style="font-size: 15px; font-weight: 700; color: #111111; display: inline-block; min-width: 90px; text-align: right;">{{ $subtotal_disp }}</span>
            </div>
            <div>
              <span style="font-size: 16px; font-weight: 700; color: #111111; display: inline-block; min-width: 110px; text-align: right; margin-right: 25px;">TOTAL:</span>
              <span style="font-size: 16px; font-weight: 800; color: #111111; display: inline-block; min-width: 90px; text-align: right;">{{ $total_disp }}</span>
            </div>
          </div>
        </td>
      </tr>
    </table>

    <!-- Divider Line -->
    <div style="height: 1.5px; background-color: #d8dde3; margin-top: 15px; margin-bottom: 22px; width: 100%;"></div>

    <!-- Advance Payment Notice -->
    <div style="text-align: center; margin-bottom: 22px;">
      <div style="font-size: 14.5px; font-weight: 700; color: #111111; line-height: 1.45;">
        Required 50% Advance Payment<br>for Starting Print.
      </div>
    </div>

    <!-- Bank Details Side-by-Side -->
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 25px;">
      <tr>
        <td style="width: 10%;"></td>
        <!-- NSB Bank Details -->
        <td style="width: 40%; vertical-align: top; padding-right: 25px;">
          <div style="margin-bottom: 8px;">
            <img src="{{ asset('img/pinterix/nsb_logo.jpeg') }}" alt="NSB" style="height: 38px; max-width: 155px; width: auto; object-fit: contain; display: block;">
          </div>
          <div style="font-size: 13.5px; font-weight: 700; color: #111111; line-height: 1.45;">
            <div>100807732333</div>
            <div>NSB</div>
            <div>Battaramulla</div>
            <div>D M P S Dissanayaka</div>
          </div>
        </td>

        <!-- Commercial Bank Details (Large Cropped Logo) -->
        <td style="width: 40%; vertical-align: top; padding-left: 25px;">
          <div style="margin-bottom: 8px;">
            <img src="{{ asset('img/pinterix/commercial_logo.jpeg') }}" alt="Commercial Bank" style="height: 38px; max-width: 175px; width: auto; object-fit: contain; display: block;">
          </div>
          <div style="font-size: 13.5px; font-weight: 700; color: #111111; line-height: 1.45;">
            <div>8150929996</div>
            <div>Commercial Bank</div>
            <div>KANDY City office branch</div>
            <div>D M P S Dissanayaka</div>
          </div>
        </td>
        <td style="width: 10%;"></td>
      </tr>
    </table>

  </div>
</div>

<style>
@media print {
  @page {
    margin: 8mm 6mm;
    size: A4 portrait;
  }
  body, html {
    background: #ffffff !important;
    color: #000000 !important;
  }
  .pinterix-invoice-wrapper {
    max-width: 100% !important;
    width: 100% !important;
    padding: 10px 20px 20px 20px !important;
    box-shadow: none !important;
    -webkit-print-color-adjust: exact !important;
    print-color-adjust: exact !important;
    color-adjust: exact !important;
  }
  .pinterix-invoice-wrapper th {
    background-color: #0b5cb7 !important;
    color: #ffffff !important;
    -webkit-print-color-adjust: exact !important;
    print-color-adjust: exact !important;
  }
  .pinterix-invoice-wrapper svg {
    -webkit-print-color-adjust: exact !important;
    print-color-adjust: exact !important;
  }
}
</style>
