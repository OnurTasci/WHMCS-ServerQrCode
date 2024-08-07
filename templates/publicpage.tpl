<h2 class="text-center">{$data->name}</h2>
<div  class="form-horizontal">
    <div class="form-group">
        <label class="col-sm-2 control-label">Seri Numarası </label>
        <div class="col-sm-10">
            <input type="text" class="form-control" readonly value="{$data->serino}">
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">Hizmet Numarası </label>
        <div class="col-sm-10">
            <input type="number" class="form-control" readonly value="{$data->services}">
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">Kısa İsim </label>
        <div class="col-sm-10">
            <input type="text" class="form-control" value="{$data->name}" readonly>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">Datacenter </label>
        <div class="col-sm-10">
            <input type="text" class="form-control" value="{$data->datacenter}"  readonly>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">Kabin </label>
        <div class="col-sm-10">
            <input type="text" class="form-control" value="{$data->cabin}" readonly>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">CPU </label>
        <div class="col-sm-10">
            <input type="text" class="form-control" value="{$data->cpu}" readonly>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">RAM </label>
        <div class="col-sm-10">
            <input type="text" class="form-control" value="{$data->ram}" readonly>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Disk </label>
        <div class="col-sm-10">
            <input type="text" class="form-control" value="{$data->disk}"  readonly >
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">PSU </label>
        <div class="col-sm-10">
            <input type="text" class="form-control"  value="{$data->psu}"  readonly>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">Durumu</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" value="{$data->status}" readonly>
        </div>
    </div>

</div>