
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <div class="login-panel panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Terminal Fund Replenishment</h3>
                        </div>
                        <div class="panel-body" id="enteramount">
                            <fieldset>
                                <div class="form-group">
									<span class="newval" id="resetval" name="resetval"></span>
									Please Enter Amount without decimal
                                </div>
								
								<div class="form-group input-group">
                                    <span class="input-group-addon">₱</span>
                                    <input class="form-control" placeholder="Amount" id="amount" name="amount" class="amount" type="text" value="<?= $_SESSION['temp_amount']; ?>" onkeypress="return isNumberKey(event)">
                                    <span class="input-group-addon">.00</span>
								</div>
                                <button type="button" class="btn btn-lg btn-primary btn-block" id="amountsubmit" name="amountsubmit" />Next</button>
                            </fieldset>
                        </div>
						
						<div class="panel-body" id="enteraccount" style="display:none;">
                            <fieldset>
                                <div class="form-group">
									<h4><span class="newval" id="newval" name="newval"></span></h4>
									<h4>Tap ID or enter ID number</h4>
                                </div>
                                <div class="form-group">
									<input class="form-control" placeholder="Terminal ID" id="terminalID" name="terminalID" autofocus>
                                </div>
								<div class="form-group">
									<input class="form-control" placeholder="Dispatcher ID" id="dispatcherID" name="dispatcherID">
                                </div>
									<label>
										<input name="paid" type="checkbox" value="1">   Paid
									</label>
                                <button type="button" class="btn btn-lg btn-success btn-block" id="amountreload" name="amountreload"/>Reload</button>
								<button type="button" class="btn btn-lg btn-warning btn-block" id="resetamount" name="resetamount" onclick="$('#enteraccount').hide();$('#enteramount').show('slow');"/>Back</button>
                            </fieldset>
                        </div>

						<div class="panel-body" id="Verification" style="display:none;">
                            <fieldset>
                                <div class="form-group">
									<span class="loadSuccessMessage" id="loadSuccessMessage" name="loadSuccessMessage"></span>
                                </div>
								<button type="button" class="btn btn-lg btn-success btn-block" id="newamountreloaded" name="newamountreloaded"  onclick="$('#enteraccount').hide();$('#enteramount').show('slow');$('#verification').hide();"/>Back</button>
                            </fieldset>
                        </div>
	
                    </div>
                </div>
            </div>
			
			