<?php

namespace WHMCS\Module\Addon\Serverqrcode\Client;

use WHMCS\Database\Capsule;

/**
 * Sample Client Area Controller
 */
class Controller {

    /**
     * Index action.
     *
     * @param array $vars Module configuration parameters
     *
     * @return array
     */
    public function index($vars)
    {
        // Get common module parameters
        $modulelink = $vars['modulelink']; // eg. addonmodules.php?module=addonmodule


        $notfound = [
            'pagetitle' => 'QR Sunucu Bilgisi',
            'breadcrumb' => array(
                'index.php?m=addonmodule' => '404 Bulunamadı',
            ),
            'templatefile' => 'notfound',
            'vars' => array(
                'data' => 'Sorguladığınız sunucu bulunamadı!',
            ),
        ];
        
        $permission = [
            'pagetitle' => 'Yetkisiz Erişim',
            'breadcrumb' => array(
                'index.php?m=addonmodule' => 'Yetkisiz Erişim',
            ),
            'templatefile' => 'notfound',
            'vars' => array(
                'data' => 'Yetkisiz Erişim!',
            ),
        ];

        if (!isset($_GET['id'])){
            return $notfound;
        }

        $id = $_GET['id'];

        $custom = 'ZYXWVUTSRQPONMLKJIHGFEDCBAzyxwvutsrqponmlkjihgfedcba9876543210+/';
        $default = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/';
        $id = base64_decode(strtr($id, $custom, $default));

        try {
            $data = Capsule::table('mod_serverqrcode')
                ->where("id", "=", $id)
                ->first();
        } catch(\Illuminate\Database\QueryException $ex){
            echo $ex->getMessage();
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        if (count($data) == 0){
            return $notfound;
        }

        $requirelogin = false;
        if ($vars['hidden'] == 2){
            $requirelogin = true;
        }

        
        if ($vars['hidden'] == 3 && !$_SESSION['adminid']){
            return $permission;
        }

        return [
            'pagetitle' => 'QR Sunucu Bilgisi',
            'breadcrumb' => array(
                '' => $data->name,
            ),
            'templatefile' => 'publicpage',
            'requirelogin' => $requirelogin,
            'vars' => array(
                'data' => $data,
            ),
        ];
    }

}
