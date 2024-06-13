<?php
	$terminal_query = mysqli_query($link, "SELECT terminal_ID, route_origin, route_destination, terminal_name, operational FROM ".DB_PREFIX."terminal");
?>
                <!-- /.row -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Terminal List
								<span class="pull-right"><a href="add_terminal"><i class="fa fa-plus"></i> Add New Terminal</a></span>	
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <div class="dataTable_wrapper">
                                    <table class="table table-striped table-bordered table-hover" id="dataTables-terminals">
                                        <thead>
                                            <tr>
                                                <th>Terminal Name</th>
                                                <th>Route Origin</th>
                                                <th>Route Destination</th>
                                                <th>Terminal ID</th>
                                                <th>Status</th>
												<th>Edit</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php 
											WHILE($terminal = mysqli_fetch_array($terminal_query)){
										?>
                                            <tr class="gradeA">
                                                <td><?php IF(WM()): ?><a href="terminaltrans?terminal=<?= $terminal['terminal_ID']; ?>"><?= $terminal['terminal_name']; ?></a><?php ELSE: ?><?= $terminal['terminal_name']; ?><?php ENDIF; ?></td>
                                                <td><?= $terminal['route_origin']; ?></td>
                                                <td><?= $terminal['route_destination']; ?></td>
                                                <td class="center"><?= $terminal['terminal_ID']; ?></a></td>
                                                <td class="center"><?= ($terminal['operational'] ? 'Operational' : 'Pending'); ?></td>
												<td><a href="gen_id_bridge?terminal=<?= $terminal['terminal_ID']; ?>"><i class="fa fa-edit"></i></a></td>
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