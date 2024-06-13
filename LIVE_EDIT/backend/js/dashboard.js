			// NUMBERS ONLY
			function isNumberKey(evt)
			{
				var charCode = (evt.which) ? evt.which : event.keyCode
				if (charCode > 31 && (charCode < 48 || charCode > 57))
				return false;
				return true;
			}
		
            $(document).ready(function() {
                $('#dataTables-allmembers').DataTable({
					responsive: true
                });
				$('#dataTables-forID').DataTable({
					responsive: true
                });
				$('#dataTables-SuspendedDriver').DataTable({
					responsive: true
                });
				$('#dataTables-BannedAccounts').DataTable({
					responsive: true
                });
				$('#dataTables-CommuterAccounts').DataTable({
					responsive: true
                });
				
				
				$('#dataTables-terminals').DataTable({
					responsive: true
                });
				
				$('#dataTables-terminaltrans').DataTable({
					responsive: true
                });
				
				$('#dataTables-vehicles').DataTable({
					responsive: true
                });
				
				$('#dataTables-suspend').DataTable({
					responsive: true
                });
				$('#dataTables-report').DataTable({
					responsive: true
                });

				$("#amountsubmit").click(function(){
					var amount = $("#amount").val();
					
					if(amount ==''|| amount ==0 ){
							alert("Please enter valid amount.");   	
					}
					else{
						$.post("./trigger/exec",{ amount1: amount, setamount: 1},
							function(data) {
								document.getElementById("newval").textContent="Reload Php"+$("#amount").val()+".00 To";
								$("#terminalID").focus();
								$('#enteraccount').show('slow');
								$('#enteramount').hide();
							}
						);
					}
				});
				
				$("#amountreload").click(function(){
					var tval = $("#terminalID").val();
					var dval = $("#dispatcherID").val();
					var loadamount = $("#amount").val();

					if(tval == ''|| tval == 0 || dval == '' || dval == 0){
						alert("Terminal ID and Dispatcher ID cannot be blank!");   	
					}
					else{

						$.post('./trigger/exec', {'tID':tval, 'dID':dval, 'load':loadamount, validate: 1}, function(data) {
							var newdata = data;
							if (newdata == 1) {
								alert("Please enter valid Terminal ID number");
							} else if (newdata == 2) {
								alert("Please enter valid Dispatcher ID number");
							} else if (newdata == 3) {
								document.getElementById("loadSuccessMessage").textContent="Php"+$("#amount").val()+".00 Has been reloaded to "+$("#terminalID").val();
								$('#Verification').show('slow');
								$('#enteraccount').hide();
							} else {
								alert(data);
							}
						});
					}
				});

				$("#resetamount").click(function(){

					$.post("./trigger/exec",{ resetamount: 1},
						function(data) {
							document.getElementById("resetval").textContent="Amount has been reset to Php0.00";
							$("#amount").focus();
							$('#amount').val('');
							$('#terminalID').val('');
							$('#dispatcherID').val('');
						}
					);
				});
				
				$("#newamountreloaded").click(function(){
					$.post("./trigger/exec",{ resetamount: 1},
						function(data) {
							$("#amount").focus();
							$('#amount').val('');
							$('#terminalID').val('');
							$('#dispatcherID').val('');
							$('#Verification').hide();
							$('#enteraccount').hide();
							$('#enteramount').show("slow");
						}
					);
				});
				
				
				$("#view_details_member").click(function(){
					$.post("./trigger/exec",{ setcommuter: 1});
				});
				
				$("#hide_details_member").click(function(){
					$.post("./trigger/exec",{ setcommuter: 1});
				});
				
				$("#view_details_terminal").click(function(){
					$.post("./trigger/exec",{ setterminal: 1});
				});
				
				$("#hide_details_terminal").click(function(){
					$.post("./trigger/exec",{ setterminal: 1});
				});
				
				$("#view_details_vehicle").click(function(){
					$.post("./trigger/exec",{ setvehicle: 1});
				});
				
				$("#hide_details_vehicle").click(function(){
					$.post("./trigger/exec",{ setvehicle: 1});
				});
				
				$("#view_details_loading").click(function(){
					$.post("./trigger/exec",{ setloading: 1});
				});
				
				$("#hide_details_loading").click(function(){
					$.post("./trigger/exec",{ setloading: 1});
				});
					
            });