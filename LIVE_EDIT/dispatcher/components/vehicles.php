<?php
	$terminal_query = mysqli_query($link, "SELECT * FROM ".DB_PREFIX."vehicles");
?>
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Vehicles</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Terminal List
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <div class="dataTable_wrapper">
                                    <table class="table table-striped table-bordered table-hover" id="dataTables-members">
                                        <thead>
                                            <tr>
                                                <th>Plate Number</th>
                                                <th>Vehicle Owner</th>
                                                <th>Driver</th>
                                                <th>Capacity</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php 
											WHILE($terminal = mysqli_fetch_array($terminal_query)){
												$ownerID = $terminal['owner_ID'];
												$driverID = $terminal['driver_ID1'];
												
											$vowner = mysqli_fetch_array(mysqli_query($link, "SELECT CONCAT (fname,' ',lname) AS fullname FROM ".DB_PREFIX."users WHERE user_ID='".$ownerID."' "));
											$driver = mysqli_fetch_array(mysqli_query($link, "SELECT CONCAT (fname,' ',lname) AS fullname FROM ".DB_PREFIX."users WHERE user_ID='{$driverID}' "));
										?>
                                            <tr class="gradeA">
                                                <td><a href="gen_id_bridge?unitID=<?= $terminal['unit_ID']; ?>"><?= $terminal['plate_number']; ?></a></td>
                                                <td><a href="gen_id_bridge?memberID=<?= $ownerID; ?>"><?= strtoupper($vowner['fullname']); ?><a/></td>
                                                <td><a href="gen_id_bridge?memberID=<?= $driverID; ?>"><?= strtoupper($driver['fullname']); ?></a></td>
                                                <td class="center"><?= $terminal['capacity']; ?></td>
                                                <td class="center"><?= ($terminal['suspended'] ? 'Pending' : 'Active'); ?></td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- /.panel-body -->
                        </div>
                        <!-- /.panel -->
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->