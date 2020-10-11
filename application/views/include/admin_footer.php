<?php
    $CI =& get_instance();
    $CI->load->model('Web_settings');
    $Web_settings = $CI->Web_settings->retrieve_setting_editdata();
?>
<footer class="main-footer">
    <strong>
    	<?php if (isset($Web_settings[0]['footer_text'])) { echo html_escape($Web_settings[0]['footer_text']); }?>
   	</strong><i class="fa fa-heart color-green"></i>
   	  <input type ="hidden" name="csrf_test_name" id="csrf_test_name" value="<?php echo $this->security->get_csrf_hash();?>">
   	   <input type ="hidden" name="base_url" id="base_url" value="<?php echo base_url();?>">
</footer>

  <!-- COMMON CALCULATOR MODAL -->
   <!-- calculator modal -->
    <div class="modal fade-scale" id="calculator" role="dialog">
    <div class="modal-dialog" id="calculatorcontent">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-body">
            <div class="calcontainer">
         <div class="screen">
      <h1 id="mainScreen">0</h1>
    </div>
    <table>
      <tr>
        <td><button value="7" id="7" onclick="InputSymbol(7)">7</button></td>
        <td><button value="8" id="8" onclick="InputSymbol(8)">8</button></td>
        <td><button value="9" id="9" onclick="InputSymbol(9)">9</button></td>
        <td><button onclick="DeleteLastSymbol()">CE</button></td>
      </tr>
      <tr>
        <td><button value="4" id="4" onclick="InputSymbol(4)">4</button></td>
        <td><button value="5" id="5" onclick="InputSymbol(5)">5</button></td>
        <td><button value="6" id="6" onclick="InputSymbol(6)">6</button></td>
        <td><button value="/" id="104" onclick="InputSymbol(104)">/</button></td>
      </tr>
      <tr>
        <td><button value="1" id="1" onclick="InputSymbol(1)">1</button></td>
        <td><button value="2" id="2" onclick="InputSymbol(2)">2</button></td>
        <td><button value="3" id="3" onclick="InputSymbol(3)">3</button></td>
        <td><button value="*" id="103" onclick="InputSymbol(103)">*</button></td>
      </tr>
      <tr>
        <td><button value="0" id="0" onclick="InputSymbol(0)">0</button></td>
        <td><button value="." id="128" onclick="InputSymbol(128)">.</button></td>
        <td><button value="-" id="102" onclick="InputSymbol(102)">-</button></td>
        <td><button value="+" id="101" onclick="InputSymbol(101)">+</button></td>
      </tr>
      <tr>
        <td colspan="2"><button onclick="ClearScreen()">C</button></td>
        <td colspan="1"><button onclick="CalculateTotal()">=</button></td>
        <td colspan="1"><button  data-dismiss="modal" class="btn-danger"><i class="fa fa-power-off"></i></button></td>
      </tr>
    </table>
</div>
        </div>
       
      </div>
      
    </div>
  </div>
