// NUMBERS ONLY
			function addSeparatorsNF(nStr, inD, outD, sep){
			 nStr += '';
			 var dpos = nStr.indexOf(inD);
			 var nStrEnd = '';
			 if (dpos != -1) {
			  nStrEnd = outD + nStr.substring(dpos + 1, nStr.length);
			  nStr = nStr.substring(0, dpos);
			 }
			 var rgx = /(\d+)(\d{3})/;
			 while (rgx.test(nStr)) {
			  nStr = nStr.replace(rgx, '$1' + sep + '$2');
			 }
			 return nStr + nStrEnd;
			}

			function isNumberKey(evt)
			{
				var charCode = (evt.which) ? evt.which : event.keyCode
				if (charCode > 31 && (charCode < 48 || charCode > 57))
				return false;
				return true;
			}
		
            $(document).ready(function() {
				
				$('#dataTables-terminaltransacations').DataTable({
					responsive: true
                });
				
				$('#dataTables-passengertransacations').DataTable({
					responsive: true
                });
				
				$('#dataTables-terminaltransaction').DataTable({
					responsive: true
                });
				
				//Balance Checking - dispatcher
				$('#ReadCECard').submit(function (e) {

					// Prevent form submission which refreshes page
					e.preventDefault();

					// Serialize data
					var formData = $(this).serialize();
				
					var mID = $("#memberID").val();
					if(mID == ''|| mID == 0 ){
						$('#MemberChecked').html("<span class='text-warning text-center'>Please enter valid Member's ID.</span>");
					}
					else if(mID.length < 10|| mID.length > 10 ){
						$('#MemberChecked').html("<span class='text-warning text-center'>CE Card number must be at least but not exceeding 10 characters.</span>");
					}
					else{
						$.post('./trigger/exec', { 'mID': mID, checkval: 1}, function(data) {
							var checkdata = data;

							if(checkdata == 1){
								$('#MemberChecked').html("<span class='text-warning text-center'>Member's ID doesn't exists</span>");
							}
							else{
								$('#MemberChecked').html(data);
								//$('#MemberChecked').hide();
							}
						});
					}
					$('#memberID').val('');
					$('#memberID').focus();
				});

				// Reloading - Dispatcher
				$("#memberamountsubmit").click(function(){
					var amount = $("#memberamount").val();
					var wallet = $("#wallet").val();
				
					if(amount ==''|| amount ==0 ){
						$('#valcheck').html("<h3 class='text-danger text-center'>Please enter valid amount.</h3>");
					}
					else if (amount > Math.round(wallet)) {
						$('#valcheck').html("<h3 class='text-danger text-center'>Insufficient fund!</h3>");
					}
					else { 
						$("#newval").html("Reload Php<b>"+$("#memberamount").val()+".00</b> To");
						$('#enteraccount').show('slow');
						$('#enteramount').hide();
						$('#memberIDNo').focus();
						$('#memberIDNo').val('');
					} 
				});

				$("#memberamountreload").click(function(){
					var mval = $("#memberIDNo").val();
					var loadamount = $("#memberamount").val();
					var currentbal = $("#currentbal").val();
					var newval = currentbal - loadamount;
					newval = addSeparatorsNF(newval,',','.',',');

					$.post('./trigger/exec', {'mID':mval, 'load':loadamount, 'wallet':currentbal, validate: 1}, function(data) {
						var newdata = data;
						if (newdata == 1) {
							$('#newid').html("<h3 class='text-danger text-center'>Insufficient fund!</h3>");
						} else if (newdata == 2) {
							$('#newid').html("<h3 class='text-danger text-center'>Member ID cannot be blank or at least 7 character long</h3>");
						} else if (newdata == 3) {
							$('#newid').html("<h3 class='text-danger text-center'>Account Doesn't Exist!</h3>");
						} else if (newdata == 4){
							$('#loadSuccessMessage').html("<span class='text-success lead'>Php"+$("#memberamount").val()+".00</span> Has been reloaded to <span class='text-success lead'>"+$("#memberIDNo").val()+"</span>");
							//document.getElementById("loadSuccessMessage").textContent="Php"+$("#memberamount").val()+".00 Has been reloaded to "+$("#memberIDNo").val();
							$('#currentloadwallet').html("Current Load Wallet Php"+newval+".00");
							$('#Verification').show('slow');
							$('#enteraccount').hide();
						}
 					});
				});

				$("#resetamount").click(function(){

					$.post("./trigger/exec",{ resetamount: 1},
						function(data) {
							document.getElementById("resetval").textContent="Amount has been reset to Php0.00";
							$("#memberamount").focus();
							$('#memberamount').val('');
							//$('#terminalID').val('');
							$('#dispatcherID').val('');
							$('#memberIDNo').val('');
						}
					);
				});
				
				$("#newmemberamountreloaded").click(function(){
					$.post("./trigger/exec",{ resetamount: 1},
						function(data) {
							$("#memberamount").focus();
							$('#memberamount').val('');
							//$('#terminalID').val('');
							$('#memberIDNo').val('');
							$('#dispatcherID').val('');
							$('#Verification').hide();
							$('#enteraccount').hide();
							$('#enteramount').show("slow");
						}
					);
				});
				
				// Dashboard Icon and clearing input data
				// Balance Inquiry
				$("#view_details_member").click(function(){
					$.post("./trigger/exec",{ setcommuter: 1});
					$("#memberID").focus();
					
					// close load
					$('#resetval').html('');
					$('#valcheck').html('');
					$('#newval').html('');
					$('#newid').html('');
					$('#loadSuccessMessage').html('');
					$('#memberamount').val('');
					$('#memberIDNo').val('');
					
					$('#Verification').hide();
					$('#enteraccount').hide();
					
					$('#enteramount').show();
				});
				
				$("#hide_details_member").click(function(){
					$.post("./trigger/exec",{ setcommuter: 1});
					$('#BalanceChecked').html('');
					$('#memberID').val('');
					$('#BalResult').hide();
					$('#entermemberaccount').show();
				});
				
				// Reloading
				$("#view_details_loading").click(function(){
					$.post("./trigger/exec",{ setloading: 1});
					$("#memberamount").focus();
					
					// close balcheck
					$('#BalanceChecked').html('');
					$('#memberID').val('');
					$('#BalResult').hide();
					$('#entermemberaccount').show();
				});
				
				$("#hide_details_loading").click(function(){
					$.post("./trigger/exec",{ setloading: 1});
				});
				
				
				$("#view_details_terminal").click(function(){
					$.post("./trigger/exec",{ setterminal: 1});
					
					// close balcheck
					$('#BalanceChecked').html('');
					$('#memberID').val('');
					$('#BalResult').hide();
					$('#entermemberaccount').show();
				});
				
				$("#hide_details_terminal").click(function(){
					$.post("./trigger/exec",{ setterminal: 1});
				});
				
				$("#view_details_vehicle").click(function(){
					$.post("./trigger/exec",{ setvehicle: 1});
					
					// close balcheck
					$('#BalanceChecked').html('');
					$('#memberID').val('');
					$('#BalResult').hide();
					$('#entermemberaccount').show();
				});
				
				$("#hide_details_vehicle").click(function(){
					$.post("./trigger/exec",{ setvehicle: 1});
				});
            });