   $(document).ready(function() {
        "use strict";
     $("#service_quotation_div").click(function () {
         $("#quotation_service").toggle(1500,"easeOutQuint",function(){
          });
  });  
  });

     "use strict";
    function get_customer_info(t) {
        var csrf_test_name = $('[name="csrf_test_name"]').val();
        var base_url = $("#base_url").val();
        $.ajax({
            url: base_url + "Cquotation/get_customer_info",
            type: 'POST',
            data: {customer_id: t,csrf_test_name:csrf_test_name},
            success: function (r) {
                r = JSON.parse(r);
                $("#address").val(r.customer_address);
                $("#mobile").val(r.customer_mobile);
                $("#website").val(r.customer_email);
            }
        });
       
    }


    "use strict";
function invoice_productList(sl) {

        var priceClass = 'price_item'+sl;
        var available_quantity = 'available_quantity_'+sl;
        var unit = 'unit_'+sl;
        var tax = 'total_tax_'+sl;
        var serial_no = 'serial_no_'+sl;
        var discount_type = 'discount_type_'+sl;
        var csrf_test_name = $('[name="csrf_test_name"]').val();
        var base_url = $("#base_url").val();

    // Auto complete
    var options = {
        minLength: 0,
        source: function( request, response ) {
            var product_name = $('#product_name_'+sl).val();
        $.ajax( {
          url: base_url + "Cinvoice/autocompleteproductsearch",
          method: 'post',
          dataType: "json",
          data: {
            term: request.term,
            product_name:product_name,
            csrf_test_name:csrf_test_name,
          },
          success: function( data ) {
            response( data );

          }
        });
      },
       focus: function( event, ui ) {
           $(this).val(ui.item.label);
           return false;
       },
       select: function( event, ui ) {
            $(this).parent().parent().find(".autocomplete_hidden_value").val(ui.item.value); 
                $(this).val(ui.item.label);
                var id=ui.item.value;
                var dataString = 'product_id='+ id;
                var base_url = $('.baseUrl').val();

                $.ajax
                   ({
                        type: "POST",
                        url: base_url+"Cinvoice/retrieve_product_data_inv",
                        data: {product_id:id,csrf_test_name:csrf_test_name,},
                        cache: false,
                        success: function(data)
                        {
                            var obj = jQuery.parseJSON(data);
                            for (var i = 0; i < (obj.txnmber); i++) {
                            var txam = obj.taxdta[i];
                            var txclass = 'total_tax'+i+'_'+sl;
                           $('.'+txclass).val(obj.taxdta[i]);
                            }
                            $('.'+priceClass).val(obj.price);
                            $('.'+available_quantity).val(obj.total_product.toFixed(2,2));
                            $('.'+unit).val(obj.unit);
                            $('.'+tax).val(obj.tax);
                            $('#txfieldnum').val(obj.txnmber);
                            $('#supplier_price_'+sl).val(obj.supplier_price);
                            $('#'+serial_no).html(obj.serial);
                            $('#'+discount_type).val(obj.discount_type);
                                   quantity_calculate(sl);
                                   //This Function Stay on others.js page
                            
                            
                        } 
                    });

            $(this).unbind("change");
            return false;
       }
   }

   $('body').on('keypress.autocomplete', '.productSelection', function() {
       $(this).autocomplete(options);
   });

}

       "use strict";
 function addService(t) {
    var row = $("#serviceInvoice tbody tr").length;
    var count = row + 1;
    var limits = 500;
    var taxnumber = $("#sertxfieldnum").val();
    var tbfild ='';
    for(var i=0;i<taxnumber;i++){
        var taxincrefield = '<input id="total_service_tax'+i+'_'+count+'" class="total_service_tax'+i+'_'+count+'" type="hidden"><input id="all_servicetax'+i+'_'+count+'" class="total_service_tax'+i+'" type="hidden" name="stax[]">';
         tbfild +=taxincrefield;
    }
    if (count == limits)
        alert("You have reached the limit of adding " + count + " inputs");
    else {
        var a = "service_name" + count,
                tabindex = count * 5,
                e = document.createElement("tr");
        //e.setAttribute("id", count);
        tab1 = tabindex + 1;
        tab2 = tabindex + 2;
        tab3 = tabindex + 3;
        tab4 = tabindex + 4;
        tab5 = tabindex + 5;
        tab6 = tabindex + 6;
        e.innerHTML = "<td><input type='text' name='service_name' onkeypress='invoice_serviceList(" + count + ");' class='form-control serviceSelection common_product' placeholder='Service Name' id='" + a + "'  tabindex='" + tab1 + "'><input type='hidden' class='common_product autocomplete_hidden_value  service_id_" + count + "' name='service_id[]' id='SchoolHiddenId'/></td><td> <input type='text' name='service_quantity[]'  onkeyup='serviceCAlculation(" + count + ");' onchange='serviceCAlculation(" + count + ");' id='total_service_qty_" + count + "' class='common_qnt total_service_qty_" + count + " form-control text-right'  placeholder='0.00' min='0' tabindex='" + tab2 + "'/></td><td><input type='text' name='service_rate[]' onkeyup='serviceCAlculation(" + count + ");' onchange='serviceCAlculation(" + count + ");' id='service_rate_" + count + "' class='common_rate service_rate" + count + " form-control text-right'  placeholder='0.00' min='0' tabindex='" + tab3 + "'/></td><td><input type='text' name='sdiscount[]' onkeyup='serviceCAlculation(" + count + ");' onchange='serviceCAlculation(" + count + ");' id='sdiscount_" + count + "' class='form-control text-right common_servicediscount' placeholder='0.00' min='0' tabindex='" + tab4 + "' /><input type='hidden' value='' name='discount_type' id='sdiscount_type_" + count + "'></td><td class='text-right'><input class='common_total_service_amount total_serviceprice form-control text-right' type='text' name='total_service_amount[]' id='total_service_amount_" + count + "' value='0.00' readonly='readonly'/></td><td>"+tbfild+"<input type='hidden'  id='totalServiceDicount_" + count + "' class='totalServiceDicount_" + count + "' /><input type='hidden' id='all_service_discount_" + count + "' class='totalServiceDicount' name='sdiscount_amount[]'/><button tabindex='" + tab5 + "'  class='btn btn-danger text-center' type='button' onclick='deleteServicraw(this)'><i class='fa fa-close'></i></button></td>",
                document.getElementById(t).appendChild(e),
                document.getElementById(a).focus(),
                document.getElementById("add_service_item").setAttribute("tabindex", tab6);
        count++
    }
}
//Quantity calculat
    "use strict";
function serviceCAlculation(item) {
    var quantity = $("#total_service_qty_" + item).val();
    var service_rate = $("#service_rate_" + item).val();
    var service_discount = $("#service_discount").val();
    var discount = $("#sdiscount_" + item).val();
    var taxnumber = $("#sertxfieldnum").val();
    var totalServiceDicount = $("#totalServiceDicount_" + item).val();
    var dis_type = $("#sdiscount_type_" + item).val();

    if (quantity > 0 || discount > 0) {
        if (dis_type == 1) {
            var price = quantity * service_rate;
            var dis = +(price * discount / 100);
 

            $("#all_service_discount_" + item).val(dis);

            //Total price calculate per product
            var temp = price - dis;
            var ttletax = 0;
            $("#total_service_amount_" + item).val(price);
             for(var i=0;i<taxnumber;i++){
           var tax = (temp-ttletax) * $("#total_service_tax"+i+"_" + item).val();
           ttletax += Number(tax);
            $("#all_servicetax"+i+"_" + item).val(tax);
    }

          
        } else if (dis_type == 2) {
            var price = quantity * service_rate;

            // Discount cal per product
            var dis = (discount * quantity);

            $("#all_service_discount_" + item).val(dis);

            //Total price calculate per product
            var temp = price - dis;
            $("#total_service_amount_" + item).val(price);

            var ttletax = 0;
             for(var i=0;i<taxnumber;i++){
           var tax = (temp-ttletax) * $("#total_service_tax"+i+"_" + item).val();
           ttletax += Number(tax);
            $("#all_servicetax"+i+"_" + item).val(tax);
    }
        } else if (dis_type == 3) {
            var total_service_amount = quantity * service_rate;
            
            // Discount cal per product
            $("#all_service_discount_" + item).val(discount);
            //Total price calculate per product
            var price = (total_service_amount - discount);
            $("#total_service_amount_" + item).val(total_service_amount);

             var ttletax = 0;
             for(var i=0;i<taxnumber;i++){
           var tax = (price-ttletax) * $("#total_service_tax"+i+"_" + item).val();
           ttletax += Number(tax);
            $("#all_servicetax"+i+"_" + item).val(tax);
    }
        }
    } else {
        var n = quantity * service_rate;
        var c = quantity * service_rate * total_service_tax;
        $("#total_service_amount_" + item).val(n),
        $("#all_servicetax_" + item).val(c)
    }
    ServiceCalculation();
   
}
//Calculate Sum
    "use strict";
function ServiceCalculation() {
  var taxnumber = $("#sertxfieldnum").val();
    
          var t = 0,          
            a = 0,
            e = 0,
            o = 0,
            p = 0,
            f = 0;
        
  //Total Tax
for(var i=0;i<taxnumber;i++){
      
var j = 0;
    $(".total_service_tax"+i).each(function () {
        isNaN(this.value) || 0 == this.value.length || (j += parseFloat(this.value))
    });
            $("#total_service_tax_amount"+i).val(j.toFixed(2, 2));
             
    }
 
        //Discount part
         $(".totalServiceDicount").each(function () {
        isNaN(this.value) || 0 == this.value.length || (p += parseFloat(this.value))
    }),
            $("#total_service_discount").val(p.toFixed(2, 2)),

    $(".totalServiceTax").each(function () {
        isNaN(this.value) || 0 == this.value.length || (f += parseFloat(this.value))
    }),
            $("#total_service_tax").val(f.toFixed(2, 2)),
         
            //Total Price
            $(".total_serviceprice").each(function () {
        isNaN(this.value) || 0 == this.value.length || (t += parseFloat(this.value))
    }),
            o = f.toFixed(2, 2),
            e = t.toFixed(2, 2);
    f = p.toFixed(2, 2);

    var test = +o + +e + -f;
    $("#serviceGrandTotal").val(test.toFixed(2, 2));
 
    var gt = $("#serviceGrandTotal").val();
    var invdis = $("#service_discount").val();
    var total_service_discount = $("#total_service_discount").val();
    var ttl_discount = +total_service_discount + +invdis;
    $("#total_service_discount").val(ttl_discount.toFixed(2, 2));
    var grnt_totals = gt;
    $("#serviceGrandTotal").val(grnt_totals);

}


//Delete a row of table
    "use strict";
function deleteServicraw(t) {
    var a = $("#serviceInvoice > tbody > tr").length;
//    alert(a);
    if (1 == a)
        alert("There only one row you can't delete.");
    else {
        var e = t.parentNode.parentNode;
        e.parentNode.removeChild(e),
                ServiceCalculation();
        var current = 1;
        $("#serviceInvoice > tbody > tr td input.productSelection").each(function () {
            current++;
            $(this).attr('id', 'product_name' + current);
        });
        var common_qnt = 1;
        $("#serviceInvoice > tbody > tr td input.common_qnt").each(function () {
            common_qnt++;
            $(this).attr('id', 'total_service_qty_' + common_qnt);
            $(this).attr('onkeyup', 'serviceCAlculation('+common_qnt+');');
            $(this).attr('onchange', 'serviceCAlculation('+common_qnt+');');
        });
        var common_rate = 1;
        $("#serviceInvoice > tbody > tr td input.common_rate").each(function () {
            common_rate++;
            $(this).attr('id', 'service_rate_' + common_rate);
            $(this).attr('onkeyup', 'serviceCAlculation('+common_qnt+');');
            $(this).attr('onchange', 'serviceCAlculation('+common_qnt+');');
        });
        var common_servicediscount = 1;
        $("#serviceInvoice > tbody > tr td input.common_servicediscount").each(function () {
            common_servicediscount++;
            $(this).attr('id', 'sdiscount_' + common_servicediscount);
            $(this).attr('onkeyup', 'serviceCAlculation('+common_qnt+');');
            $(this).attr('onchange', 'serviceCAlculation('+common_qnt+');');
        });
        var common_total_service_amount = 1;
        $("#serviceInvoice > tbody > tr td input.common_total_service_amount").each(function () {
            common_total_serviceprice++;
            $(this).attr('id', 'total_serviceprice_' + common_total_price);
        });




    }
}
var count = 2,
        limits = 500;
              "use strict";
          function bank_paymet(val){
        if(val==2){
           var style = 'block'; 
           document.getElementById('bank_id').setAttribute("required", true);
        }else{
   var style ='none';
    document.getElementById('bank_id').removeAttribute("required");
        }
           
    document.getElementById('bank_div').style.display = style;
    }

    $( document ).ready(function() {
        var is_quotation = $("#is_quotation").val();
        if(is_quotation !==''){
          $("#quotation_service").css("display", "block");        
      }else{
       $("#quotation_service").css("display", "none"); 
      }

    
    });

    