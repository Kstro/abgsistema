<?php
namespace DGAbgSistemaBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
include_once '../src/DGAbgSistemaBundle/Resources/ipn/ipnlistener.php';

/**
 * CtlPais controller.
 *
 * @Route("/ipn")
 */
class CtlPaypalController extends Controller
{
    /**
     * 
     *
     * @Route("/datas", name="datas")
     * @Method("GET")
     */
    public function indexAction()
    {
       
         $listener = new \IpnListener();

            try {
                $verified = $listener->processIpn();
            } catch (Exception $e) {
                return Log::error($e->getMessage());
            }

            if ($verified) {
                
                $data = $_POST;
                $user_id = json_decode($data['custom'])->user_id;

                $subscription = ($data['mc_gross_1'] == '10') ? 2 : 1;

                $txn = array(
                    'txn_id'       => $data['txn_id'],
                    'user_id'      => $user_id,
                    'paypal_id'    => $data['subscr_id'],
                    'subscription' => $subscription,
                    'expires'      => date('Y-m-d H:i:s', strtotime('+1 Month')),
                );

                Payment::create($txn);
                
            } else {
                Log::error('Transaction not verified');
            }
        
        
        
        
    }

   
}
