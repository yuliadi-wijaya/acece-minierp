<!-- Manage Category Start -->
<div class="content-wrapper">
    

    <section class="content">

  
        <!-- Manage Category -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                         <h1>Category list</h1>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table  border="1" width="100%">
                                  <caption>
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
                                        <th> <?php echo display('sl')?></th>
                                        <th class="text-center"><?php echo display('category_id') ?></th>
                                        <th class="text-center"><?php echo display('category_name') ?></th>
                                     
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($category_list) {
                                        ?>
                                        {category_list}
                                        <tr>
                                            <td> {sl}</td>
                                            <td class="text-center">{category_id}</td>
                                            <td class="text-center">{category_name}</td>
                                          
                                    </tr>
                                    {/category_list}
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




