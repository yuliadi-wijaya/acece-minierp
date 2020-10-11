

<!-- Person Start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('personal_loan') ?></h1>
            <small><?php echo $title ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('personal_loan') ?></a></li>
                <li class="active"><?php echo html_escape($sub_title) ?></li>
            </ol>
        </div>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group text-left">
                    <a href="<?php echo base_url('Csettings/add_person') ?>" class="btn btn-primary"><i class="ti-plus"> </i> <?php echo display('add_person') ?> </a>

                    <a href="<?php echo base_url('Csettings/add_loan') ?>" class="btn btn-warning"><i class="ti-plus"> </i> <?php echo display('add_loan') ?> </a>

                    <a href="<?php echo base_url('Csettings/add_payment') ?>" class="btn btn-success"><i class="ti-plus"> </i> <?php echo display('add_payment') ?> </a>

                    <a href="<?php echo base_url('Csettings/manage_person') ?>" class="btn btn-info"><i class="ti-align-justify"> </i> <?php echo display('manage_loan') ?> </a>
                </div>
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo display('search') ?></h4>
                        </div>
                    </div>
                    <?php echo form_open('Csettings/person_loan_search', array('class' => 'form-vertical', 'id' => 'person_ledger')); ?>
                    <div class="panel-body"> 
                        <?php
                        date_default_timezone_set("Asia/Jakarta");
                        $today = date('Y-m-d');
                        ?>
                        <div class="form-group row">
                            <label for="name" class="col-sm-2 col-form-label"><?php echo display('name') ?> <i class="text-danger">*</i></label>
                            <div class="col-sm-10">
                                <select class="form-control" name="person_id" id="namepersonloan">
                                    <option><?php echo display('select_one') ?></option>
                                    {person_details_all}
                                    <option value="{person_id}">{person_name}</option>
                                    {/person_details_all}
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="phone" class="col-sm-2 col-form-label"><?php echo display('phone') ?> <i class="text-danger">*</i></label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control phone" name="phone" id="phone" required="" placeholder="<?php echo display('phone') ?>" min="0"/>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="from_date" class="col-sm-2 col-form-label"><?php echo display('from') ?>: <i class="text-danger">*</i></label>
                            <div class="col-sm-10">
                                <input type="text" id="from_date" name="from_date" value="<?php echo $today?>" class="form-control datepicker" required/>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="to_date" class="col-sm-2 col-form-label"><?php echo display('to') ?>: <i class="text-danger">*</i></label>
                            <div class="col-sm-10">
                                <input type="text" id="to_date" name="to_date" value="<?php echo $today?>" class="form-control datepicker" required/>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
						<div class="form-group" style="margin-bottom:0%">
							<button type="submit" class="btn btn-success w-md m-b-5"><?php echo display('search') ?></button>
                            <a class="btn btn-primary w-md m-b-5" href="#" onclick="printDiv('printableArea')"><?php echo display('print') ?></a>
						</div>
					</div>
                    <?php echo form_close() ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo html_escape($sub_title) ?></h4>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div id="printableArea">
                            <div class="text-center">
                                <?php if ($person_details) { ?>
                                    {person_details}
                                    <h3> {person_name} </h3>
                                    <h4><?php echo display('address') ?> : {person_address} </h4>
                                    <h4 ><?php echo display('phone') ?> : {person_phone} </h4>
                                    {/person_details}
                                <?php } ?>	
                                <h4> <?php echo display('print_date') ?>: <?php echo date("d/m/Y h:i:s"); ?> </h4>
                            </div>

                            <div class="table-responsive">
                                <table id="" class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-center"><?php echo display('date') ?></th>
                                            <th class="text-center"><?php echo display('details') ?></th>

                                            <th class="text-right"><?php echo display('debit') ?></th>

                                            <th class="text-right"><?php echo display('credit') ?></th>
                                            <th class="text-right"><?php echo display('balance') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($ledger) {
                                            ?>

                                            <?php foreach ($ledger as $value) {
                                                ?>
                                                <tr>
                                                    <td class="text-center"><?php echo $value['date'] ?></td>
                                                    <td align="center"><?php echo $value['details'] ?></td>
                                                    <td align="right"><?php
                                                        echo (($position == 0) ? "$currency" : " $currency");
                                                        echo number_format($value['debit'], 2, '.', ',');
                                                        ?></td>
                                                    <td align="right"><?php
                                                        echo (($position == 0) ? "$currency " : " $currency");
                                                        echo number_format($value['credit'], 2, '.', ',');
                                                        ?></td>
                                                    <td align="right"><?php
                                                        echo (($position == 0) ? "$currency" : " $currency");
                                                        echo number_format($value['balance'], 2, '.', ',')
                                                        ?></td>

                                                <?php } ?>
                                                <?php
                                            }
                                            ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="2"  align="right"><b><?php echo display('grand_total') ?>:</b></td>
                                            <td align="right"><b><?php echo (($position == 0) ? "$currency {subtotalDebit}" : "{subtotalDebit} $currency") ?></b></td>

                                            <td align="right"><b><?php echo (($position == 0) ? "$currency {subtotalCredit}" : "{subtotalCredit} $currency") ?></b></td>

                                            <td align="right"><b>
                                                <?php echo (($position == 0) ? "$currency {subtotalBalance}" : "{subtotalBalance} $currency") ?></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="text-right"><?php echo $links ?></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- Person ledger End -->

