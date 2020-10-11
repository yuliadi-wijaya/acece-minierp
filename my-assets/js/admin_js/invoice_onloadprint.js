"use strict";
 window.onload = function (){
      var url=document.getElementById("url").value; 
      var printContents = document.getElementById('printableArea').innerHTML;
        var originalContents = document.getElementById('printableArea').innerHTML;
        document.getElementById('printableArea').innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
         setTimeout(function(){
          document.location.href = url;
           }, 100);
}