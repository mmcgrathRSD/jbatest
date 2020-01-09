<?php
namespace JBAShop;

use Money\Currencies\ISOCurrencies;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Money;
use Money\Currency;

class Listener extends \Shop\Listener
{
   

    public function onGetYmmLink($event)
    {
        $ymm = $event->getArgument('model');
        $slug = $ymm->slug;

        $event->setArgument('link', "/fits/{$slug}");
    }

    public function onCreateYmmTextBlock($event) {
    
        $product = $event->getArgument('model');
    
        /*
         * RSD METHOD
         */
        $weigher = array();
        $makes = [];
        if (count($product->ymms) > 0) {
            foreach ($product->ymms as $ymm) {
                if ($fullYmm = (new \Shop\Models\YearMakeModels)->setCondition('slug', (string) $ymm['slug'])->getItem()) {
                    //get the highest Makes
                    if (empty($makes[$fullYmm['vehicle_make']])) {
                        $makes[$fullYmm['vehicle_make']] = 1;
                    } else {
                        $makes[$fullYmm['vehicle_make']] = $makes[$fullYmm['vehicle_make']] + 1;
                    }
                    $weigher[$fullYmm['vehicle_make']][$fullYmm['vehicle_model']][$fullYmm['vehicle_sub_model']][] = $fullYmm['vehicle_year'];
                }
            }
            asort($makes);
            $numberofmakes = 2;
            $interations = 1;
            $text = '';
            $first = true;
            if (array_key_exists('Subaru', $weigher)) {
                $reordered = [];
                $weigher['Subaru'] = $product->sortArrayByArray($weigher['Subaru'], ['Impreza', 'BRZ', 'Legacy', 'Forester', 'Outback']);
                foreach ($weigher['Subaru'] as $model => $subModel) {
                    $reordered[$model] = $product->sortArrayByArray($subModel, ['STI', 'WRX']);
                }
                $weigher['Subaru'] = $reordered;
            }
            $weigher = $product->sortArrayByArray($weigher, ['Subaru', 'Mitsubishi']);
            $makes = $product->sortArrayByArray($makes, ['Subaru', 'Mitsubishi']);
            foreach($makes as $make => $makecount) {
                //CREATED THE main fits
                if($first) {
                    $fits = [];
                    $primary = '';
                    foreach($weigher[$make] as $model => $subs) {
                        foreach($subs as $sub => $years) {
                            $fit = '<div class="make-model">';
                            $fit .= '<strong>'.$make.' '.$model.'</strong>';
                            asort($years);
                            //$years = str_replace([20,19],['',''], $years);
                            $fit .= '<strong class="subFit"> '. $sub .' </strong>'. $product->rangeImplode(array_unique($years));
                            $fit .= '</div>';
                            $fits[] = $fit;
                            if(empty($primary)) {
                                $limitedSub = array_slice (explode(' ', $sub), 0, 2);
        
        
                                $primary .= $make .' '. implode(' ', $limitedSub) .' '. $product->rangeImplode(array_unique($years));
                            }
                        }
                    }
                    $todisplay = array_chunk($fits,2);
                    $text = implode(',', $todisplay[0]);
                    $product->set('ymmtext.single', $text);
                    $product->set('ymmtext.primary', $primary);
                    $text = '';
                }
                $first = false;
                $numberofmodels = 1;
                foreach($weigher[$make] as $model => $subs) {
                    if($numberofmodels == $numberofmakes) {
                        break;
                    }
                    $text .= '<div class="'.strtolower($make).'">';
                    $text .= '<strong>'.$make.' '.$model.'</strong>';
                    $text .= '<div class="'.strtolower($make).' makemodels">';
                    foreach($subs as $sub => $years) {
                        asort($years);
                         
        
        
                        $text .= '<small>';
                        $text .= '<span class="subFit">'. $sub .'</span> '. implode(',',$years);
                        $text .= '</small><br>';
                    }
                    $text .= '</div></div>';
                    $numberofmodels++;
                }
                if($interations == $numberofmakes) {
                    break;
                } else {
                    $interations++;
                }
            }
            $product->set('ymmtext.main', $text);
            $aText = '';
            $times = 0;
            foreach($makes as $make => $makecount) {
                foreach($weigher[$make] as $model => $subs) {
                    $aText .= '<div class="makeModel '.strtolower($make).'-'. strtolower($model).'">';
                    $aText .=  '<strong>'.$make . ' '.$model.'</strong><div class="make-models">';
                    foreach($subs as $sub => $years) {
                        asort($years);
                        $linkedYears = [];
                        foreach ($years as $year) {
                             
                            $slug = $year .'-'.$make.'-'.$model.'-'.$sub;
                            $slug = \Web::instance()->slug($slug);
                            $search =  (new \Shop\Models\YearMakeModels)->collection();
                             
                            $regex = new \MongoDB\BSON\Regex("^".$slug,"i");

                            $doc = $search->findOne(array('slug' => $regex));
        
                            if(!empty($doc['slug'])) {
                                $linkedYears[] = '<a href="/fits/'.$doc['slug'].'">'.$year.'</a>';
                            } else {
                                $linkedYears[] = $year;
                            }
                             
                        }
                         
        
                        $aText .= '<span class="makeModelsubFit"><span class="subModel">'. $sub .'</span><span class="years"> '. implode(', ', array_unique($linkedYears)) .'</span></span>';
                    }
                    $aText .= '</div></div>';
                }
            }
            $product->set('ymmtext.additional', $aText);
            $product->clear('universalpart');
        } else {
            $product->clear('ymmtext');
            $product->set('universalpart', true);
        }
   
    
        $event->setArgument('model', $product);
    }

    
    public function onCreateGuestCheckoutAccount($event)
    {
       $identity =  $event->getArgument('identity');
        
       $identity = (new \JBAShop\Models\Customers)->bind($identity->cast());
       $identity->ensureValidCustomer();
       $identity->store();
       
        $event->setArgument('identity', $identity);
    }
    public function onAffirmBeginCheckout($event)
    {
        $event->setArgument('checkout', \JBAShop\Models\Checkout::instance());
    }

    protected function checkForFreeGiftcards($cart) {
        return $cart;
    }
    
    public function afterAffirmCapture($event) {
               
        $order = $event->getArgument('order');
        $affirm = $event->getArgument('affirm');
       
        $authCode = $order->affirm_auth;
        
        $moneyFormatter = new DecimalMoneyFormatter(new ISOCurrencies());
        $money = new Money($affirm->amount, new Currency('USD'));

        $baseNumber = $order->number;
        if(strpos($baseNumber, '.')) {
            $baseNumber = substr($order->number, 0, strpos($order->number, '.'));
        }

        $splitOrder = (new \Shop\Models\Orders)->setCondition('number', $baseNumber . '.1')->getItem();
        if (!empty($splitOrder)) {
            $order = $splitOrder;
        }

        $orderNum = $order->number;
        $customerNum = $order->get('customer.number');
        $amount = (float) $moneyFormatter->format($money);
        $paymentType = 3;
        $cardName = 'AFFIRM';
        $giftCode = null;

        \Dsc\Queue::task(
            '\Rally\Models\eConnect\Payments::createXML',
            [$orderNum, $customerNum, $amount, $authCode, $paymentType, $cardName, $giftCode],
            [
                'title'=> 'Creating  PAYMENT XML for order ' . $order->number,
                'batch' => 'local',
                'priority' => 10
            ]
        );
        
        \Dsc\System::addMessage('Payment is Queued');
    }
    
    
    public  function afterSaveShopModelsCarts($event) {
        $cart = $event->getArgument('model');
    
        $cart = $this->checkForFreeGiftcards($cart);
    
        $cart->store([], ['skip_listeners' => true]);
    
        return $event;
    }

    public function newItemsFlagChanged()
    {
        if (\Base::instance()->get('SITE_TYPE') != 'wholesale') {
            \Dsc\Queue::task('\JBAShop\Models\Collections::updateNewItems');
        }
    }
    
    /*
      * adds the wholesale site to the default sales channel when a new product is created.
      */
     public function afterCreateShopModelsProducts($event) {
        $model = $event->getArgument('model');
        $model->sales_channel_ids = ['596e669a7fdd985d47d24488'];
        $model->set('publication.status','published');
        $model->save();
     }
    
     public function afterCreateJBAShopModelsProducts($event) {
         $model = $event->getArgument('model');
         $model->sales_channel_ids = ['596e669a7fdd985d47d24488'];
         $model->set('publication.status','published');
         $model->save();
     }
}
