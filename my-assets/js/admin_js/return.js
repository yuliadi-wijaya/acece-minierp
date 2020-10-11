     "use strict";
 function checkboxcheck(sl) {
        var check_id = 'check_id_' + sl;
        var total_qntt = 'total_qntt_' + sl;
        var product_id = 'product_id_' + sl;
        var total_price = 'total_price_' + sl;
        var discount = 'discount_' + sl;
        if ($('#' + check_id).prop("checked") == true) {
            document.getElementById(total_qntt).setAttribute("required", "required");
            document.getElementById(product_id).setAttribute("name", "product_id[]");
            document.getElementById(total_qntt).setAttribute("name", "product_quantity[]");
            document.getElementById(total_price).setAttribute("name", "total_price[]");
            document.getElementById(discount).setAttribute("name", "discount[]");
        } else if ($('#' + check_id).prop("checked") == false) {
            document.getElementById(total_qntt).removeAttribute("required");
            document.getElementById(product_id).removeAttribute("name", "");
            document.getElementById(total_qntt).removeAttribute("name", "");
            document.getElementById(total_price).setAttribute("name", "total_price[]");
            document.getElementById(discount).setAttribute("name", "");
        }
    }

    //Quantity calculat
        "use strict";
    function quantity_calculate(item) {
         var a = 0,o = 0,d = 0,p = 0;
        var sold_qty = $("#sold_qty_" + item).val();
        var quantity = $("#total_qntt_" + item).val();
        var price_item = $("#price_item_" + item).val();
        var discount = $("#discount_" + item).val();
        if(parseInt(sold_qty) < parseInt(quantity)){
            alert("Sold quantity less than quantity!");
            $("#total_qntt_"+item).val("");
        }
        if (parseInt(quantity) > 0) {
            var price = (quantity * price_item);
            var dis = price * (discount / 100);
            $("#all_discount_" + item).val(dis);
            var ttldis = $("#all_discount_" + item).val();

            //Total price calculate per product
            var temp = price - ttldis;
            $("#total_price_" + item).val(temp);//

            $(".total_price").each(function () {
                isNaN(this.value) || o == this.value.length || (a += parseFloat(this.value));
            }),
                    $("#grandTotal").val(a.toFixed(2, 2));

                  $(".total_discount").each(function () {
                isNaN(this.value) || p == this.value.length || (d += parseFloat(this.value));
            }),
                    $("#total_discount_ammount").val(d.toFixed(2, 2));
        }

    }



      $(document).ready(function () {
            "use strict";
        $('input[type=checkbox]').each(function () {
            if (this.nextSibling.nodeName != 'label') {
                $(this).after('<label for="' + this.id + '"></label>')
            }
        })


    $('#add_invoice').prop("disabled", true);
    $('input:checkbox').click(function () {
        if ($(this).is(':checked')) {
            $('#add_invoice').prop("disabled", false);
        } else {
            if ($('.chk').filter(':checked').length < 1) {
                $('#add_invoice').attr('disabled', true);
            }
        }
    });
    })


        "use strict";
    function checkboxcheckSreturn(sl){
         var check_id    ='check_id_'+sl;
        var total_qntt  ='total_qntt_'+sl;
        var product_id  ='product_id_'+sl;
        var total_price  ='total_price_'+sl;
        var discount  ='discount_'+sl;
            if($('#'+check_id).prop("checked") == true){
                document.getElementById(total_qntt).setAttribute("required","required");
                  document.getElementById(product_id).setAttribute("name","product_id[]");
                   document.getElementById(total_qntt).setAttribute("name","product_quantity[]");
                    document.getElementById(total_price).setAttribute("name","total_price[]");
                   document.getElementById(discount).setAttribute("name","discount[]");
            }
            else if($('#'+check_id).prop("checked") == false){
               document.getElementById(total_qntt).removeAttribute("required");
                document.getElementById(product_id).removeAttribute("name","");
                document.getElementById(total_qntt).removeAttribute("name","");
                document.getElementById(total_price).setAttribute("name","total_price[]");
                  document.getElementById(discount).setAttribute("name","");
            }
        };
        

            "use strict";
        function quantity_calculateSreturn(item) {
         var a = 0,o = 0 , d = 0,p = 0;
        var sold_qty = $("#sold_qty_" + item).val();
        var quantity = $("#total_qntt_" + item).val();
        var price_item = $("#price_item_" + item).val();
        var discount = $("#discount_" + item).val();
        if(parseInt(sold_qty) < parseInt(quantity)){
            alert("Purchase quantity less than quantity!");
            $("#total_qntt_"+item).val("");
        }
        if (parseInt(quantity) > 0) {
            var price = (quantity * price_item);
            var dis = price * (discount / 100);
            $("#all_discount_" + item).val(dis);

            //Total price calculate per product
            var temp = price - dis;
            $("#total_price_" + item).val(temp);

            $(".total_price").each(function () {
                isNaN(this.value) || o == this.value.length || (a += parseFloat(this.value));
            }),
            $("#grandTotal").val(a.toFixed(2, 2));
            $(".total_discount").each(function () {
                isNaN(this.value) || p == this.value.length || (d += parseFloat(this.value));
            }),
                    $("#total_discount_ammount").val(d.toFixed(2, 2));     
        }

    }


