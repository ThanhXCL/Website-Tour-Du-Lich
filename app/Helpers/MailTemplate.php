<?php

namespace App\Helpers;

class MailTemplate
{
    /**
     * Tao HTML email xac nhan dat tour
     *
     * @param array $order  Du lieu don hang
     * @param string $websiteDomain  Domain website (dung de tao link)
     * @return string  HTML email
     */
    public static function orderConfirmation(array $order, string $websiteDomain = ''): string
    {
        $code       = htmlspecialchars($order['code'] ?? '');
        $fullName   = htmlspecialchars($order['fullName'] ?? '');
        $phone      = htmlspecialchars($order['phone'] ?? '');
        $note       = htmlspecialchars($order['note'] ?? '—');
        $total      = number_format((float)($order['total'] ?? 0), 0, ',', '.');
        $subTotal   = number_format((float)($order['subTotal'] ?? 0), 0, ',', '.');
        $discount   = number_format((float)($order['discount'] ?? 0), 0, ',', '.');
        $createdAt  = isset($order['createdAt'])
            ? date('H:i - d/m/Y', strtotime($order['createdAt']))
            : date('H:i - d/m/Y');

        // Map ten phuong thuc thanh toan
        $methodMap = [
            'money'  => 'Thanh toán tiền mặt khi đi tour',
            'vnpay'  => 'VNPay',
            'bank'   => 'Chuyển khoản ngân hàng',
            'zalopay'=> 'ZaloPay',
        ];
        $paymentMethod = $methodMap[$order['paymentMethod'] ?? ''] ?? ($order['paymentMethod'] ?? '');

        // Tao HTML cho danh sach tour
        $itemsHtml = '';
        foreach (($order['items'] ?? []) as $item) {
            $tourName      = htmlspecialchars($item['name'] ?? '');
            $avatar        = htmlspecialchars($item['avatar'] ?? '');
            $departureDate = isset($item['departureDate'])
                ? date('d/m/Y', strtotime($item['departureDate']))
                : '';

            $qtyAdult    = (int)($item['quantityAdult'] ?? 0);
            $qtyChildren = (int)($item['quantityChildren'] ?? 0);
            $qtyBaby     = (int)($item['quantityBaby'] ?? 0);

            $priceAdult    = number_format((float)($item['priceNewAdult'] ?? 0), 0, ',', '.');
            $priceChildren = number_format((float)($item['priceNewChildren'] ?? 0), 0, ',', '.');
            $priceBaby     = number_format((float)($item['priceNewBaby'] ?? 0), 0, ',', '.');

            $avatarHtml = $avatar
                ? "<img src=\"{$avatar}\" alt=\"{$tourName}\" style=\"width:100%;max-width:120px;border-radius:8px;object-fit:cover;\">"
                : '';

            $itemsHtml .= "
            <tr>
              <td style=\"padding:16px;border-bottom:1px solid #f0f0f0;\">
                <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
                  <tr>
                    <td width=\"130\" valign=\"top\" style=\"padding-right:16px;\">
                      {$avatarHtml}
                    </td>
                    <td valign=\"top\">
                      <p style=\"margin:0 0 6px;font-size:16px;font-weight:700;color:#1a1a2e;\">{$tourName}</p>
                      <p style=\"margin:0 0 4px;font-size:13px;color:#666;\">
                        <span style=\"color:#f06b1f;font-weight:600;\">&#128197;</span> Ngày khởi hành: <b>{$departureDate}</b>
                      </p>
                      <table style=\"margin-top:8px;font-size:13px;color:#444;\" cellpadding=\"2\" cellspacing=\"0\">
                        <tr>
                          <td style=\"padding-right:10px;\">Người lớn:</td>
                          <td><b>{$qtyAdult}</b> x {$priceAdult}đ</td>
                        </tr>
                        <tr>
                          <td style=\"padding-right:10px;\">Trẻ em:</td>
                          <td><b>{$qtyChildren}</b> x {$priceChildren}đ</td>
                        </tr>
                        <tr>
                          <td style=\"padding-right:10px;\">Em bé:</td>
                          <td><b>{$qtyBaby}</b> x {$priceBaby}đ</td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>";
        }

        $trackingLink = $websiteDomain
            ? "<a href=\"{$websiteDomain}/order/success?orderCode={$code}&phone={$phone}\" style=\"display:inline-block;margin-top:16px;padding:12px 28px;background:linear-gradient(135deg,#f06b1f,#e85d04);color:#fff;text-decoration:none;border-radius:8px;font-weight:700;font-size:15px;\">Xem chi tiết đơn hàng</a>"
            : '';

        return "<!DOCTYPE html>
<html lang=\"vi\">
<head>
  <meta charset=\"UTF-8\">
  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
  <title>Xác nhận đặt tour #{$code}</title>
</head>
<body style=\"margin:0;padding:0;background:#f4f6fb;font-family:'Segoe UI',Arial,sans-serif;\">
  <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"background:#f4f6fb;padding:30px 0;\">
    <tr>
      <td align=\"center\">
        <table width=\"640\" cellpadding=\"0\" cellspacing=\"0\" style=\"max-width:640px;background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.08);\">

          <!-- HEADER -->
          <tr>
            <td style=\"background:linear-gradient(135deg,#1a1a2e 0%,#16213e 60%,#0f3460 100%);padding:36px 40px;text-align:center;\">
              <p style=\"margin:0;font-size:28px;font-weight:800;color:#f06b1f;letter-spacing:1px;\">&#9992; TOUR TRAVEL</p>
              <p style=\"margin:10px 0 0;font-size:16px;color:#cdd5e0;letter-spacing:0.5px;\">Xác nhận đặt tour thành công</p>
            </td>
          </tr>

          <!-- GREETING -->
          <tr>
            <td style=\"padding:32px 40px 16px;\">
              <p style=\"margin:0;font-size:18px;font-weight:600;color:#1a1a2e;\">Xin chào <span style=\"color:#f06b1f;\">{$fullName}</span>! &#127881;</p>
              <p style=\"margin:10px 0 0;font-size:14px;color:#555;line-height:1.7;\">
                Cảm ơn bạn đã đặt tour tại <b>Tour Travel</b>. Đơn hàng của bạn đã được tiếp nhận thành công.
                Chúng tôi sẽ liên hệ xác nhận trong thời gian sớm nhất.
              </p>
            </td>
          </tr>

          <!-- ORDER CODE BANNER -->
          <tr>
            <td style=\"padding:0 40px 24px;\">
              <div style=\"background:linear-gradient(135deg,#fff8f3,#fff0e6);border:2px dashed #f06b1f;border-radius:12px;padding:20px;text-align:center;\">
                <p style=\"margin:0;font-size:13px;color:#888;text-transform:uppercase;letter-spacing:1px;\">Mã đơn hàng</p>
                <p style=\"margin:6px 0 0;font-size:28px;font-weight:900;color:#f06b1f;letter-spacing:3px;\">{$code}</p>
              </div>
            </td>
          </tr>

          <!-- ORDER INFO TABLE -->
          <tr>
            <td style=\"padding:0 40px 24px;\">
              <p style=\"margin:0 0 12px;font-size:16px;font-weight:700;color:#1a1a2e;border-left:4px solid #f06b1f;padding-left:12px;\">Thông tin đặt hàng</p>
              <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-radius:10px;overflow:hidden;border:1px solid #eee;font-size:14px;\">
                <tr style=\"background:#fafafa;\">
                  <td style=\"padding:12px 16px;color:#666;width:45%;\">Khách hàng:</td>
                  <td style=\"padding:12px 16px;font-weight:700;color:#1a1a2e;\">{$fullName}</td>
                </tr>
                <tr>
                  <td style=\"padding:12px 16px;color:#666;border-top:1px solid #f0f0f0;\">Số điện thoại:</td>
                  <td style=\"padding:12px 16px;font-weight:600;color:#1a1a2e;border-top:1px solid #f0f0f0;\">{$phone}</td>
                </tr>
                <tr style=\"background:#fafafa;\">
                  <td style=\"padding:12px 16px;color:#666;border-top:1px solid #f0f0f0;\">Phương thức TT:</td>
                  <td style=\"padding:12px 16px;color:#1a1a2e;border-top:1px solid #f0f0f0;\">{$paymentMethod}</td>
                </tr>
                <tr>
                  <td style=\"padding:12px 16px;color:#666;border-top:1px solid #f0f0f0;\">Ghi chú:</td>
                  <td style=\"padding:12px 16px;color:#1a1a2e;border-top:1px solid #f0f0f0;\">{$note}</td>
                </tr>
                <tr style=\"background:#fafafa;\">
                  <td style=\"padding:12px 16px;color:#666;border-top:1px solid #f0f0f0;\">Ngày đặt:</td>
                  <td style=\"padding:12px 16px;color:#1a1a2e;border-top:1px solid #f0f0f0;\">{$createdAt}</td>
                </tr>
              </table>
            </td>
          </tr>

          <!-- TOUR ITEMS -->
          <tr>
            <td style=\"padding:0 40px 24px;\">
              <p style=\"margin:0 0 12px;font-size:16px;font-weight:700;color:#1a1a2e;border-left:4px solid #f06b1f;padding-left:12px;\">Danh sách tour</p>
              <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-radius:10px;overflow:hidden;border:1px solid #eee;\">
                {$itemsHtml}
              </table>
            </td>
          </tr>

          <!-- TOTAL -->
          <tr>
            <td style=\"padding:0 40px 32px;\">
              <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-radius:12px;background:linear-gradient(135deg,#1a1a2e,#0f3460);overflow:hidden;font-size:14px;color:#cdd5e0;\">
                <tr>
                  <td style=\"padding:14px 24px;\">Tạm tính:</td>
                  <td style=\"padding:14px 24px;text-align:right;\">{$subTotal}đ</td>
                </tr>
                <tr style=\"border-top:1px solid rgba(255,255,255,0.1);\">
                  <td style=\"padding:14px 24px;\">Giảm giá:</td>
                  <td style=\"padding:14px 24px;text-align:right;\">-{$discount}đ</td>
                </tr>
                <tr style=\"border-top:1px solid rgba(255,255,255,0.2);\">
                  <td style=\"padding:16px 24px;font-size:18px;font-weight:800;color:#ffffff;\">Tổng thanh toán:</td>
                  <td style=\"padding:16px 24px;text-align:right;font-size:22px;font-weight:900;color:#f06b1f;\">{$total}đ</td>
                </tr>
              </table>

              <div style=\"text-align:center;margin-top:8px;\">
                {$trackingLink}
              </div>
            </td>
          </tr>

          <!-- FOOTER -->
          <tr>
            <td style=\"background:#f8f9fb;padding:24px 40px;text-align:center;border-top:1px solid #eee;\">
              <p style=\"margin:0;font-size:13px;color:#888;line-height:1.6;\">
                Nếu bạn có bất kỳ câu hỏi nào, vui lòng liên hệ chúng tôi qua email hoặc số điện thoại.<br>
                <b style=\"color:#f06b1f;\">Tour Travel</b> &mdash; Trải nghiệm tuyệt vời, kỷ niệm khó quên.
              </p>
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>
</body>
</html>";
    }
}
