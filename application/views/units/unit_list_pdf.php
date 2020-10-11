<!-- Manage Unit list Start -->
<div class="content-wrapper">
    <section class="content">
        <!-- Manage Category -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo display('unit_list') ?></h4>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table   border="1" width="100%">
                                  <caption >
                               {company_info}
                                     <address>
                                        <strong>{company_name}</strong><br>
                                        {address}<br>
                                        <abbr><b><?php echo display('mobile') ?>:</b></abbr> {mobile}<br>
                                        <abbr><b><?php echo display('email') ?>:</b></abbr> 
                                        {email}<br>
                                        <abbr><b><?php echo display('website') ?>:</b></abbr> 
                                        {website}
                                    </address>

                               {/company_info}
                           </caption>
                                <thead>
                                    <tr>
                                        <th class="text-center"><?php echo display('unit_id') ?></th>
                                        <th class="text-center"><?php echo display('unit_name') ?></th>
                                      
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($unit_list) {
                                        ?>
                                        {unit_list}
                                        <tr>
                                            <td class="text-center">{unit_id}</td>
                                            <td class="text-center">{unit_name}</td>
                                           
                                    </tr>
                                    {/unit_list}
                                    <?php
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>



