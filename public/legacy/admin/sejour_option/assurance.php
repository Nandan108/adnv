
      <div class="divTable blueTable" id="assurance_show"  style="display:none">
      <div class="divTableHeading">
        <div class="divTableRow">
        <div class="divTableHead" style="width: 8%">Annuler</div>

        <div class="divTableHead" style="width: 10%;">Assurance</div>
        <div class="divTableHead" style="width: 48%;border-right: 0;background: none;border-left: 0;"></div>
        <div class="divTableHead" style="width: 22%;border-right: 0;background: none;border-left: 0;"></div>
        <div class="divTableHead" style="width: 10%;border-right: 0;background: none;border-left: 0;"></div>
        </div>
      </div>

      <div class="divTableBody">


      <div class="divTableRow">
          <div class="divTableCell" style="border-right: 0;background: none;border-left: 0;"></div>
          <div class="divTableCell" style=""><span style="font-weight: 700;">Personne</span></div>
          <div class="divTableCell" style=""><span style="font-weight: 700;">Type</span></div>
          <div class="divTableCell" style=""><span style="font-weight: 700;">Prestation</span></div>
          <div class="divTableCell" style=";"><span style="font-weight: 700;">Tarif</span></div>
          
          

      </div>

      <div class="divTableRow" id="assurance_show1"  style="display:none">
          <div class="divTableCell" style="">

            <input type="hidden" name="check_assurance_adulte1" value="0">

            <input type="checkbox" name="check_assurance_adulte" id="check_assurance_adulte" class="check_assurance_adulte" value="0" onclick="myFunctionassuranceadulte()"></div>
          <div class="divTableCell" style="">Adulte</div>
          <div class="divTableCell" style=""><div id="nom_assurance_1"></div></div>
          <div class="divTableCell" style=""><div id="prestation_assurance_1"></div></div>
          <div class="divTableCell" >
              <div id="assurance_1" style="font-weight: 700;">
                <?php
                    if(isset($_POST['ass_adulte1']))
                      {
                        echo $ass_adulte1=$_POST['ass_adulte1']; 
                        ?>
                        <INPUT TYPE="hidden" NAME="ass_adulte1_1" value="<?php echo $_POST['ass_adulte1']; ?>"/>  
                        <?php       
                      }
                    else
                    {
                      $ass_adulte1="0";
                    }
                ?>
              </div>

          </div>
   


      </div>

<script type="text/javascript">



  function myFunctionassuranceadulte() {

    var checkBox = document.getElementById("check_assurance_adulte");
    if (checkBox.checked == true)
    {


        var ckbox = $("input[name='check_assurance_adulte']");
        var checkedValue = document.querySelector('.check_assurance_adulte:checked').value;

        document. form2.check_assurance_adulte1.value = parseFloat(checkedValue);
        var tot1 = parseFloat(document. form2.prix_total_total.value) - parseFloat(checkedValue);

        document. form2.totalb.value = parseFloat(tot1);
        document. form2.prix_total_total.value = parseFloat(tot1);
        document. form2.totalb_initiale.value = parseFloat(tot1);
        document.getElementById("totalb").innerHTML = parseFloat(tot1).toFixed(2);

        document. form2.check_assurance_adulte_1.value = 0;

        $("#assurance_show1").hide(500);
        document.getElementById("check_assurance_adulte").checked = false;
        $("input[type=radio][name=assurance_1]").prop('checked', false);



    } 
    else 
    {

      var checkedValue = parseFloat(document. form2.check_assurance_adulte1.value);
      var tot1 = parseFloat(document. form2.prix_total_total.value) + parseFloat(checkedValue);
      
      document. form2.totalb.value = parseFloat(tot1);
      document. form2.prix_total_total.value = parseFloat(tot1);
      document. form2.totalb_initiale.value = parseFloat(tot1);
      document.getElementById("totalb").innerHTML = parseFloat(tot1) ;

      document. form2.check_assurance_adulte_1.value = 1;
    }
} 
</script>



      <div class="divTableRow" id="assurance_show2"  style="display:none">
          <div class="divTableCell" style="">

            <input type="hidden" name="check_assurance_adulte12" value="0">

            <input type="checkbox" name="check_assurance_adulte2" id="check_assurance_adulte2" class="check_assurance_adulte2" value="0" onclick="myFunctionassuranceadulte2()"></div>
          <div class="divTableCell" style="">Adulte 2</div>
          <div class="divTableCell" style=""><div id="nom_assurance_2"></div></div>
          <div class="divTableCell" style=""><div id="prestation_assurance_2"></div></div>
          <div class="divTableCell" >
              <div id="assurance_2" style="font-weight: 700;">
                <?php
                    if(isset($_POST['ass_adulte2']))
                      {
                        echo $ass_adulte1=$_POST['ass_adulte2']; 
                        ?>
                        <INPUT TYPE="hidden" NAME="ass_adulte2_2" value="<?php echo $_POST['ass_adulte2']; ?>"/>  
                        <?php       
                      }
                    else
                    {
                      $ass_adulte1="0";
                    }
                ?>
              </div>

          </div>
   


      </div>



<script type="text/javascript">
  function myFunctionassuranceadulte2() {

    var checkBox = document.getElementById("check_assurance_adulte2");
    if (checkBox.checked == true)
    {

        var ckbox = $("input[name='check_assurance_adulte2']");
        var checkedValue = document.querySelector('.check_assurance_adulte2:checked').value;

        document. form2.check_assurance_adulte12.value = parseFloat(checkedValue);
        var tot1 = parseFloat(document. form2.prix_total_total.value) - parseFloat(checkedValue);

      document. form2.totalb.value = parseFloat(tot1);
      document. form2.prix_total_total.value = parseFloat(tot1);
      document. form2.totalb_initiale.value = parseFloat(tot1);
        document.getElementById("totalb").innerHTML = parseFloat(tot1).toFixed(2);

        document. form2.check_assurance_adulte_2.value = 0;
        $("#assurance_show2").hide(500);
        document.getElementById("check_assurance_adulte2").checked = false;
        $("input[type=radio][name=assurance_2]").prop('checked', false);

    } 
    else 
    {

      var checkedValue = parseFloat(document. form2.check_assurance_adulte12.value);
      var tot1 = parseFloat(document. form2.prix_total_total.value) + parseFloat(checkedValue);
      
      document. form2.totalb.value = parseFloat(tot1);
      document. form2.prix_total_total.value = parseFloat(tot1);
      document. form2.totalb_initiale.value = parseFloat(tot1);
      document.getElementById("totalb").innerHTML = parseFloat(tot1).toFixed(2);

      document. form2.check_assurance_adulte_2.value = 1;
    }
} 
</script>




      <div class="divTableRow" id="assurance_show3"  style="display:none">
          <div class="divTableCell" style="">

            <input type="hidden" name="check_assurance_adulte13" value="0">

            <input type="checkbox" name="check_assurance_adulte3" id="check_assurance_adulte3" class="check_assurance_adulte2" value="0" onclick="myFunctionassuranceadulte3()"></div>
          <div class="divTableCell" style="">Adulte 3</div>
          <div class="divTableCell" style=""><div id="nom_assurance_3"></div></div>
          <div class="divTableCell" style=""><div id="prestation_assurance_3"></div></div>
          <div class="divTableCell" >
              <div id="assurance_3" style="font-weight: 700;">
                <?php
                    if(isset($_POST['ass_adulte3']))
                      {
                        echo $ass_adulte1=$_POST['ass_adulte3']; 
                        ?>
                        <INPUT TYPE="hidden" NAME="ass_adulte3_3" value="<?php echo $_POST['ass_adulte3']; ?>"/>  
                        <?php       
                      }
                    else
                    {
                      $ass_adulte1="0";
                    }
                ?>
              </div>

          </div>
   


      </div>



<script type="text/javascript">
  function myFunctionassuranceadulte3() {

    var checkBox = document.getElementById("check_assurance_adulte3");
    if (checkBox.checked == true)
    {

        var ckbox = $("input[name='check_assurance_adulte3']");
        var checkedValue = document.querySelector('.check_assurance_adulte3:checked').value;

        document. form2.check_assurance_adulte13.value = parseFloat(checkedValue);
        var tot1 = parseFloat(document. form2.prix_total_total.value) - parseFloat(checkedValue);

      document. form2.totalb.value = parseFloat(tot1);
      document. form2.prix_total_total.value = parseFloat(tot1);
      document. form2.totalb_initiale.value = parseFloat(tot1);
        document.getElementById("totalb").innerHTML = parseFloat(tot1).toFixed(2);

        document. form2.check_assurance_adulte_3.value = 0;
                $("#assurance_show3").hide(500);
        document.getElementById("check_assurance_adulte3").checked = false;
        $("input[type=radio][name=assurance_3]").prop('checked', false);

    } 
    else 
    {

      var checkedValue = parseFloat(document. form2.check_assurance_adulte13.value);
      var tot1 = parseFloat(document. form2.prix_total_total.value) + parseFloat(checkedValue);
      
      document. form2.totalb.value = parseFloat(tot1);
      document. form2.prix_total_total.value = parseFloat(tot1);
      document. form2.totalb_initiale.value = parseFloat(tot1);
      
      document.getElementById("totalb").innerHTML = parseFloat(tot1).toFixed(2);

      document. form2.check_assurance_adulte_3.value = 1;
    }
} 
</script>







      <div class="divTableRow" id="assurance_show4"  style="display:none">
          <div class="divTableCell" style="">

            <input type="hidden" name="check_assurance_adulte14" value="0">

            <input type="checkbox" name="check_assurance_adulte4" id="check_assurance_adulte4" class="check_assurance_adulte2" value="0" onclick="myFunctionassuranceadulte4()"></div>
          <div class="divTableCell" style="">Adulte 4</div>
          <div class="divTableCell" style=""><div id="nom_assurance_4"></div></div>
          <div class="divTableCell" style=""><div id="prestation_assurance_4"></div></div>
          <div class="divTableCell" >
              <div id="assurance_4" style="font-weight: 700;">
                <?php
                    if(isset($_POST['ass_adulte4']))
                      {
                        echo $ass_adulte1=$_POST['ass_adulte4']; 
                        ?>
                        <INPUT TYPE="hidden" NAME="ass_adulte4_4" value="<?php echo $_POST['ass_adulte4']; ?>"/>  
                        <?php       
                      }
                    else
                    {
                      $ass_adulte1="0";
                    }
                ?>
              </div>

          </div>
   


      </div>



<script type="text/javascript">
  function myFunctionassuranceadulte4() {

    var checkBox = document.getElementById("check_assurance_adulte4");
    if (checkBox.checked == true)
    {

        var ckbox = $("input[name='check_assurance_adulte4']");
        var checkedValue = document.querySelector('.check_assurance_adulte4:checked').value;

        document. form2.check_assurance_adulte14.value = parseFloat(checkedValue);
        var tot1 = parseFloat(document. form2.prix_total_total.value) - parseFloat(checkedValue);

      document. form2.totalb.value = parseFloat(tot1);
      document. form2.prix_total_total.value = parseFloat(tot1);
      document. form2.totalb_initiale.value = parseFloat(tot1);
        document.getElementById("totalb").innerHTML = parseFloat(tot1).toFixed(2);

        document. form2.check_assurance_adulte_4.value = 0;
              $("#assurance_show4").hide(500);
        document.getElementById("check_assurance_adulte4").checked = false;
        $("input[type=radio][name=assurance_4]").prop('checked', false);

    } 
    else 
    {

      var checkedValue = parseFloat(document. form2.check_assurance_adulte14.value);
      var tot1 = parseFloat(document. form2.prix_total_total.value) + parseFloat(checkedValue);
      
      document. form2.totalb.value = parseFloat(tot1);
      document. form2.prix_total_total.value = parseFloat(tot1);
      document. form2.totalb_initiale.value = parseFloat(tot1);
      
      document.getElementById("totalb").innerHTML = parseFloat(tot1).toFixed(2);

      document. form2.check_assurance_adulte_4.value = 1;
    }
} 
</script>




 

