<?php
namespace RallyShop\Admin\Controllers;

class Followups extends \Dsc\Controller
{
    public function sendInvoiceFollowups()
    {
        /** @var \Mailer\Factory $mailer */
        $mailer = \Dsc\System::instance()->get('mailer');

        $orders = (new \Shop\Models\Orders)
            ->setCondition('followup_sent', ['$ne' => true])
            ->setCondition('number', new \MongoDB\BSON\Regex('^INV-','i'))
            ->setCondition('grand_total', ['$gte' => 300])
            ->setCondition('metadata.created.time', [
                '$lte' => strtotime('-14 days'),
                '$gt' => strtotime('-15 days'),
            ])
            ->getItems();

        foreach ($orders as $order) {
            if ($content = $mailer->getEmailContents('customerservice.invoice_followup', ['order' => $order])) {
                $content['fromEmail'] = $order['user_email'];
                $content['fromName']  = !empty($quote['customer_name']) ? $quote['customer_name'] : 'Guest';

                $mailer->sendEvent('customerservice@rallysportdirect.com', $content);
            }

            $order->set('followup_sent', true);
            $order->store();
        }

        die('done');
    }
}
