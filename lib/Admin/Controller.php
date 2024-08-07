<?php

namespace WHMCS\Module\Addon\Serverqrcode\Admin;

use WHMCS\Database\Capsule;
/**
 * Sample Admin Area Controller
 */
class Controller {

    /**
     * Index action.
     *
     * @param array $vars Module configuration parameters
     *
     * @return string
     */
    public function index($vars)
    {
        // Get common module parameters
        $modulelink = $vars['modulelink']; // eg. addonmodules.php?module=addonmodule

        $qrCodeUrl = 'https://'.$_SERVER['HTTP_HOST'].'/index.php?m=serverqrcode&id=';

        if (isset($_POST['serverqrcodeDelete'])){
            try {
                Capsule::table('mod_serverqrcode')
                    ->where('id', '=', $_POST['serverqrcodeDelete'])
                    ->delete();
            } catch(\Illuminate\Database\QueryException $ex){
                echo $ex->getMessage();
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }

        $tbody = '';
        foreach (Capsule::table('mod_serverqrcode')->get() as $client) {
            $tbody .= '<tr>
        <th>'.$client->serino.'</th>
        <th>'.$client->services.'</th>
        <th>'.$client->name.'</th>
        <th>'.$client->datacenter.'</th>
        <th>'.$client->cabin.'</th>
        <th>'.$client->cpu.'</th>
        <th>'.$client->ram.'</th>
        <th>'.$client->disk.'</th>
        <th>'.$client->psu.'</th>
        <th style="text-align: right;min-width: 110px;">
        <a href="'.$modulelink.'&action=view&id='.$client->id.'" class="btn btn-sm btn-info"  target="_blank"> <i class="fa fa-eye"></i></a>
        <a href="https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl='.urlencode($qrCodeUrl.$this->base64($client->id)).'&choe=UTF-8"  target="_blank" class="btn btn-sm btn-warning"> <i class="fa fa-qrcode"></i></a>
        <form style="display: inline-block" action="" method="POST">
        <button type="submit" name="serverqrcodeDelete" class="btn btn-sm btn-danger" value="'.$client->id.'" onclick="return confirm(\'Gerçekten silmek mi istiyorsun ?\')"><i class="fa fa-trash"></i></button>
        </form>
        </th>
    </tr>';
        }


        return <<<EOF

<h2>Ekli Sunucular</h2>

    <table id="tableServerQr" style="border-radius: 4px" class="border table table-striped table-bordered table-hover">
    <thead>
    <tr>
        <th>Seri No/th>
        <th>Hizmet No</th>
        <th>Sunucu</th>
        <th>Datacenter</th>
        <th>Kabin</th>
        <th>İşlemci</th>
        <th>Ram</th>
        <th>Disk</th>
        <th>PSU</th>
        <th style="text-align: right;">İşlem</th>
    </tr>
</thead>
<tbody>
    {$tbody}
</tbody>
</table>


<script>

 $('#contentarea h1').html('Sunucu QR Kod Takip Sistemi <a href="{$modulelink}&action=create" style=" float: right; " class="btn btn-success"> <i class="fa fa-plus"></i> Yeni Sunucu Ekle</a>');
    
$(document).ready(function() {
    $('#tableServerQr').DataTable({
     "language":{
	"sProcessing":   "İşleniyor...",
	"sLengthMenu":   "Sayfada _MENU_ Kayıt Göster",
	"sZeroRecords":  "Eşleşen Kayıt Bulunmadı",
	"sInfo":         "  _TOTAL_ Kayıttan _START_ - _END_ Arası Kayıtlar",
	"sInfoEmpty":    "Kayıt Yok",
	"sInfoFiltered": "( _MAX_ Kayıt İçerisinden Bulunan)",
	"sInfoPostFix":  "",
	"sSearch":       "Bul:",
	"sUrl":          "",
	"oPaginate": {
		"sFirst":    "İlk",
		"sPrevious": "Önceki",
		"sNext":     "Sonraki",
		"sLast":     "Son"
	}
}
    });
    
    
} );
</script>


EOF;

    }

    /**
     * Show action.
     *
     * @param array $vars Module configuration parameters
     *
     * @return string
     */
    public function create($vars)
    {

        $modulelink = $vars['modulelink']; // eg. addonmodules.php?module=addonmodule

        if (isset($_POST['serverqrcodeCreate'])){

            try {

                Capsule::connection()->transaction(
                    function ($connectionManager)
                    {
                        /** @var \Illuminate\Database\Connection $connectionManager */
                        $connectionManager->table('mod_serverqrcode')->insert(
                            [
                                'name' => $_POST['name'],
                                'services' => $_POST['services'],
                                'serino' => $_POST['serino'],
                                'psu' => $_POST['psu'],
                                'datacenter' => $_POST['datacenter'],
                                'cabin' => $_POST['cabin'],
                                'cpu' => $_POST['cpu'],
                                'ram' => $_POST['ram'],
                                'disk' => $_POST['disk'],
                                'info' => $_POST['info'],
                                'status' => $_POST['status']
                            ]
                        );

                    }
                );

                header('Location: '.$modulelink);

            } catch (\Exception $e) {
                echo "Uh oh! Inserting didn't work, but I was able to rollback. {$e->getMessage()}";
            }


        }

        return <<<EOF

<div class="row">
<div class="col-md-8">
<h2>Yeni Sunucu Ekle</h2>
</div>
<div class="col-md-4 text-right">
  <a href="{$modulelink}" class="btn btn-info">
        <i class="fa fa-arrow-left"></i>
        Geri Git
    </a>
</div>
</div>

<hr/>
   
   <form action="" method="post" class="form-horizontal">
   
    <div class="form-group">
    <label for="inputservices" class="col-sm-2 control-label">Seri Numarası *</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="inputserino" required name="serino" placeholder="ABCDEFG">
    </div>
  </div> 
  
  <div class="form-group">
    <label for="inputservices" class="col-sm-2 control-label">Hizmet Numarası *</label>
    <div class="col-sm-10">
      <input type="number" class="form-control" id="inputservices" required name="services" placeholder="0001">
    </div>
  </div>  
  
  <div class="form-group">
    <label for="inputName" class="col-sm-2 control-label">Kısa İsim *</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="inputName" name="name" required placeholder="Dell EMC(Onur Taşcı)">
    </div>
  </div>
   
  <div class="form-group">
    <label for="inputdatacenter" class="col-sm-2 control-label">Datacenter *</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="inputdatacenter" name="datacenter" required placeholder="Radore">
    </div>
  </div>
   
  <div class="form-group">
    <label for="inputcabin" class="col-sm-2 control-label">Kabin *</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="inputcabin" name="cabin" required placeholder="A12">
    </div>
  </div>
    
  <div class="form-group">
    <label for="inputcpu" class="col-sm-2 control-label">CPU *</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="inputcpu" name="cpu" required placeholder="2x İntel X">
    </div>
  </div> 
  <div class="form-group">
    <label for="inputram" class="col-sm-2 control-label">RAM *</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="inputram" name="ram" required placeholder="8 GB">
    </div>
  </div>
   <div class="form-group">
    <label for="inputdisk" class="col-sm-2 control-label">Disk *</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="inputdisk" name="disk" required placeholder="100 GB">
    </div>
  </div> 
   
     <div class="form-group">
    <label for="inputpsu" class="col-sm-2 control-label">PSU *</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="inputpsu" name="psu" required placeholder="850 W">
    </div>
  </div> 
  
   <div class="form-group">
    <label for="inputstatus" class="col-sm-2 control-label">Durumu</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="inputstatus" name="status" placeholder="Boşta">
    </div>
  </div> 
  
    
   <div class="form-group">
    <label for="inputinfo" class="col-sm-2 control-label" data-toggle="tooltip" data-placement="top" title="Sadece Burada Gözükür">Özel Not</label>
    <div class="col-sm-10">
      <textarea type="text" class="form-control" id="inputinfo" name="info"></textarea>
    </div>
  </div>
  
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-4">
      <button type="submit" name="serverqrcodeCreate" class="btn btn-success">Kaydet</button>
    </div>
  </div>
</form>


EOF;
    }

    /**
     * Show action.
     *
     * @param array $vars Module configuration parameters
     *
     * @return string
     */
    public function view($vars)
    {

        $modulelink = $vars['modulelink']; // eg. addonmodules.php?module=addonmodule

        if (isset($_POST['serverqrcodeUpdate'])){

            try {

                $update_data =  [
                    'name' => $_POST['name'],
                    'services' => $_POST['services'],
                    'serino' => $_POST['serino'],
                    'psu' => $_POST['psu'],
                    'datacenter' => $_POST['datacenter'],
                    'cabin' => $_POST['cabin'],
                    'cpu' => $_POST['cpu'],
                    'ram' => $_POST['ram'],
                    'disk' => $_POST['disk'],
                    'info' => $_POST['info'],
                    'status' => $_POST['status']
                ];
                Capsule::table('mod_serverqrcode')
                    ->where('id', '=', $_POST['serverqrcodeUpdate'])
                    ->update($update_data);

            } catch (\Exception $e) {
                echo "Uh oh! Inserting didn't work, but I was able to rollback. {$e->getMessage()}";
            }

        }


        if (!isset($_GET['id'])){
            header('Location: '.$modulelink);
        }
        try {
            $data = Capsule::table('mod_serverqrcode')
                ->where("id", "=", $_GET['id'])
                ->first();
        } catch(\Illuminate\Database\QueryException $ex){
            echo $ex->getMessage();
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        return <<<EOF

<div class="row">
<div class="col-md-8">
<h2>{$data->name}</h2>
</div>
<div class="col-md-4 text-right">
  <a href="{$modulelink}" class="btn btn-info">
        <i class="fa fa-arrow-left"></i>
        Geri Git
    </a>
</div>
</div>

<hr/>
   
   <form action="" method="post" class="form-horizontal">
  <div class="form-group">
    <label for="inputservices" class="col-sm-2 control-label">Seri Numarası *</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="inputserino" required name="serino" value="{$data->serino}" placeholder="ABCDEFG">
    </div>
  </div> 
   <div class="form-group">
    <label for="inputservices" class="col-sm-2 control-label">Hizmet Numarası *</label>
    <div class="col-sm-10">
      <input type="number" class="form-control" id="inputservices" required name="services" value="{$data->services}" placeholder="0001">
    </div>
  </div>  
  
  <div class="form-group">
    <label for="inputName" class="col-sm-2 control-label">Kısa İsim *</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="inputName" name="name" required value="{$data->name}"  placeholder="Dell EMC(Onur Taşcı)">
    </div>
  </div>
   
  <div class="form-group">
    <label for="inputdatacenter" class="col-sm-2 control-label">Datacenter *</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="inputdatacenter" name="datacenter" value="{$data->datacenter}"  required placeholder="Radore">
    </div>
  </div>
   
  <div class="form-group">
    <label for="inputcabin" class="col-sm-2 control-label">Kabin *</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="inputcabin" name="cabin" required value="{$data->cabin}"  placeholder="A12">
    </div>
  </div>
    
  <div class="form-group">
    <label for="inputcpu" class="col-sm-2 control-label">CPU *</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="inputcpu" name="cpu" required value="{$data->cpu}"  placeholder="2x İntel X">
    </div>
  </div> 
  <div class="form-group">
    <label for="inputram" class="col-sm-2 control-label">RAM *</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="inputram" name="ram" required value="{$data->ram}"  placeholder="8 GB">
    </div>
  </div>
   <div class="form-group">
    <label for="inputdisk" class="col-sm-2 control-label">Disk *</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="inputdisk" name="disk" required value="{$data->disk}"  placeholder="100 GB">
    </div>
  </div> 
   
   <div class="form-group">
    <label for="inputpsu" class="col-sm-2 control-label">PSU *</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="inputpsu" name="psu" required value="{$data->psu}"  placeholder="850 W">
    </div>
  </div> 
   
   <div class="form-group">
    <label for="inputstatus" class="col-sm-2 control-label">Durumu</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="inputstatus" name="status" value="{$data->status}"  placeholder="Boşta">
    </div>
  </div> 
  
   <div class="form-group">
    <label for="inputinfo" class="col-sm-2 control-label"  data-toggle="tooltip" data-placement="top" title="Sadece Burada Gözükür">Özel Not</label>
    <div class="col-sm-10">
      <textarea type="text" class="form-control" id="inputinfo" name="info">{$data->info}</textarea>
    </div>
  </div>
  
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-4">
      <button type="submit" name="serverqrcodeUpdate"  value="{$data->id}" class="btn btn-success">Güncelle</button>
    </div>
  </div>
</form>
EOF;
    }

    public function base64($input){
        $default = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";
        $custom  = "ZYXWVUTSRQPONMLKJIHGFEDCBAzyxwvutsrqponmlkjihgfedcba9876543210+/";
        return strtr(base64_encode($input), $default, $custom);
    }
}
