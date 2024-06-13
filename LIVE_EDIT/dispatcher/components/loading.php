			<?php
			// Terminal ID Declaration
			$terminal_ID = $_SESSION['terminal_ID'];
					
			$loadwallet = mysqli_fetch_array(mysqli_query($link, "SELECT ending_balance, terminal_ID, transaction_code, debit, credit, transaction_date, transaction_time FROM ".DB_PREFIX."terminalload_wallet WHERE terminal_ID = '".$terminal_ID."' AND `primary` = 1"));
			$loadwalletdate = date_create($loadwallet['transaction_date']." ".$loadwallet['transaction_time']);
			?>

            <div class="row">
                <div class="col-md-4 col-md-offset-4">
				<h4 id="currentloadwallet">Current Load Wallet Php<span id="currentwallet" name="currentwallet"><?= number_format($loadwallet['ending_balance'], 2, ".", "," ); ?></span></h4>
                    <div class="login-panel panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Member's Card Reloading</h3>
                        </div>
                        <div class="panel-body" id="enteramount">
                            <fieldset>
                                <div class="form-group">
									<span class="resetval" id="resetval" name="resetval"></span>
									<h4><span class="valcheck" id="valcheck" name="valcheck"></span></h4>
									Please Enter Amount without decimal
                                </div>

								<div class="form-group input-group">
                                    <span class="input-group-addon">₱</span>
									<input type="hidden" name="currentbal" id="currentbal" value="<?= $loadwallet['ending_balance']; ?>">
                                    <input class="form-control" placeholder="Amount" id="memberamount" name="memberamount" class="memberamount" type="text" value="" pattern=".{2,4}" required title="Minimum Load allowed is 50 and Maximum of 5000 Pesos" onkeypress="return isNumberKey(event)">
                                    <span class="input-group-addon">.00</span>
								</div>
                                <button type="button" class="btn btn-lg btn-primary btn-block" id="memberamountsubmit" name="memberamountsubmit" />Next</button>
                            </fieldset>
                        </div>

						<div class="panel-body" id="enteraccount" style="display:none;">
                            <fieldset>
                                <div class="form-group">
									<h4><span class="newval" id="newval" name="newval"></span></h4>
									<h4><span class="newid" id="newid" name="newid"></span></h4>
									<h5>Tap Card or enter ID number manually</h5>
                                </div>
                                <div class="form-group">
									<input class="form-control" placeholder="Member ID" id="memberIDNo" name="memberIDNo">
                                </div>
                                <button type="button" class="btn btn-lg btn-success btn-block" id="memberamountreload" name="memberamountreload"/>Reload</button>
								<button type="button" class="btn btn-lg btn-warning btn-block" id="resetamount" name="resetamount" onclick="$('#enteraccount').hide();$('#enteramount').show('slow');"/>Back</button>
                            </fieldset>
                        </div>

						<div class="panel-body" id="Verification" style="display:none;">
                            <fieldset>
                                <div class="form-group">
									<span class="loadSuccessMessage" id="loadSuccessMessage" name="loadSuccessMessage"></span>
                                </div>
								<button type="button" class="btn btn-lg btn-success btn-block" id="newmemberamountreloaded" name="newmemberamountreloaded"  onclick="$('#enteraccount').hide();$('#enteramount').show('slow');$('#verification').hide();"/>Back</button>
                            </fieldset>
                        </div>
                    </div>
                </div>
            </div>