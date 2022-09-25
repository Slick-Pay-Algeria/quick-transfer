<?php

namespace SlickPay\QuickTransfer;

use Illuminate\Support\Facades\Validator;

/**
 * QuickTransfer
 *
 * @author     Slick-Pay <contact@slick-pay.com>
 */
class QuickTransfer
{
    /**
     * Initiate a new payment
     *
     * @param  array $params  Request params
     * @return array
     */
    public static function createPayment(array $params): array
    {
        $params = array_merge(config('quick-transfer.user'), $params);

        $validator = Validator::make($params, [
            'returnUrl' => 'nullable|url',
            'amount'    => 'required|numeric|min:100',
            'rib'       => 'required|string|size:20',
            'fname'     => 'required|string|min:3|max:255',
            'lname'     => 'required|string|min:3|max:255',
            'address'   => 'required|string|min:3|max:255',
        ]);

        if ($validator->fails()) return [
            'success'  => 0,
            'error'    => 1,
            'messages' => $validator->errors()->all(),
        ];

        try {

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, "http://slick-pay.com/api/slickapiv1/transfer");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
            curl_setopt($ch, CURLOPT_TIMEOUT, 20);

            $result = curl_exec($ch);

            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            curl_close($ch);

            $result = json_decode($result, true);

            if ($status < 200 || $status >= 300) return [
                'success'  => 0,
                'error'    => 1,
                'messages' => [
                    "Error ! Please, try later"
                ],
            ];

            elseif (isset($result['errors']) && boolval($result['errors']) == true) return [
                'success'  => 0,
                'error'    => 1,
                'messages' => [
                    $result['msg']
                ],
            ];

        } catch (\Exception $e) {

            return [
                'success'  => 0,
                'error'    => 1,
                'messages' => [
                    $e->getMessage()
                ],
            ];
        }

        return [
            'success'  => 1,
            'error'    => 0,
            'response' => [
                'transferId'  => $result['transfer_id'],
                'redirectUrl' => $result['url'],
            ]
        ];
    }

    /**
     * Check a payment status with it transfer_id
     *
     * @param  integer $transfer_id  The payment transfer_id provided as a return of the initiate function
     * @param  string  $rib          The merchant bank account ID
     * @return array
     */
    public static function paymentStatus(int $transfer_id, string $rib = null): array
    {
        try {

            $ch = curl_init();

            $merchant_rib = $rib ?? config('quick-transfer.user.rib');

            curl_setopt($ch, CURLOPT_URL, "http://slick-pay.com/api/slickapiv1/transfer/transferPaymentSatimCheck");
            curl_setopt($ch, CURLOPT_POSTFIELDS, [
                'rib'         => $merchant_rib,
                'transfer_id' => $transfer_id,
            ]);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
            curl_setopt($ch, CURLOPT_TIMEOUT, 20);

            $result = curl_exec($ch);

            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            curl_close($ch);

            $result = json_decode($result, true);

            if (!empty($result['msg']) && $result['msg'] == 'draft') return [
                'success' => 1,
                'error'   => 0,
                'status'  => "draft",
            ];

            if ($status < 200 || $status >= 300) return [
                'success'  => 0,
                'error'    => 1,
                'messages' => [
                    "Error ! Please, try later"
                ],
            ];

            elseif (!empty($result['errors'])) return [
                'success'  => 0,
                'error'    => 1,
                'messages' => [
                    $result['msg']
                ],
            ];

        } catch (\Exception $e) {

            return [
                'success' => 0,
                'error'   => 1,
                'messages' => [
                    $e->getMessage()
                ],
            ];
        }

        return [
            'success'  => 1,
            'error'    => 0,
            'status'   => "completed",
            'response' => [
                'date'         => $result['date'],
                'amount'       => $result['amount'],
                'orderId'      => $result['orderId'],
                'orderNumber'  => $result['orderNumber'],
                'approvalCode' => $result['approvalCode'],
                'pdf'          => $result['pdf'],
                'respCode'     => $result['respCode_desc'],
            ]
        ];
    }
}
