<?php
# . Made by Soren Isager @ Sorenisager.com @ https://github.com/sorenisager/
# . Free to use, MIT license
# . If any improvements, send me a new request ;)

 # Load Config
    include "config.php";
    include "functions.php";

 # Check ReverseLookup
    if ($ReverseLookup AND $ReverseLookupJsonFilePath) { $ReverseLookupData = json_decode(file_get_contents($ReverseLookupJsonFilePath), true); }

 ?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $SiteTitle; ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>

<body class="hold-transition sidebar-collapse">
    <div class="wrapper">

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="?" class="brand-link">
                <span class="brand-text font-weight-light"><?php echo $ApplicationName; ?></span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">


            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>NSX LOG</h1>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card card-default">
                                <div class="card-header">
                                    <h3 class="card-title">Search filter</h3>

                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                                class="fas fa-minus"></i></button>
                                    </div>
                                </div>
                                <form action="#" method="get">
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Timeframe</label>
                                                    <select class="form-control" name="timeframe" style="width: 100%;">
                                                        <?php
                                                          foreach ($SearchInterval as $key => $value) {
                                                              # Check if current search is within the value.
                                                                if ($_GET["timeframe"] == $value)
                                                                  {
                                                                    $SelectBoxOption = "selected";
                                                                  }
                                                                else 
                                                                  {
                                                                    $SelectBoxOption = "";
                                                                  }
                                                            echo '<option value="' . $value . '" '.$SelectBoxOption.'>' . $key . '</option>';
                                                          }
                                                        ?>
                                                    </select>
                                                </div>
                                                <!-- /.form-group -->

                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>IP:</label>

                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-laptop"></i></span>
                                                        </div>
                                                        <input type="text" name="ipaddress" value="<?php echo $_GET["ipaddress"]; ?>" class="form-control" data-inputmask="'alias': 'ip'" data-mask>
                                                        <button class="btn btn-success" type="submit">Search</button>
                                                    </div>
                                                    <!-- /.input group -->
                                                </div>

                                                <!-- /.form group -->
                                </form>
                            </div>

                            <!-- /.col -->

                        </div>
                        <!-- /.row -->
                    </div>
                    <!-- /.card-body -->
                </div>
                <?php 
                      if ($_GET["ipaddress"] AND $_GET["timeframe"])
                      {
                        # Get data from webservice
                          $SearchData = json_decode(SearchLog($_GET["timeframe"],$_GET["ipaddress"]),true);
                ?>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">NSX LOG ENTRIES</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="datatable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Timestamp</th>
                                    <th>Action</th>
                                    <th>Source/port</th>
                                    <th>Destination/port</th>
                                    <th>Protocol</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                  foreach ($SearchData as $Traffic)
                                    {
                                        # IPs
                                          preg_match_all('/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/', $Traffic["text"], $ip_matches);
                                        
                                        # Other info
                                          preg_match('/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}.*\d.*/', $Traffic["text"], $Otherinfo);
                                          $tmp = explode("->", $Otherinfo[0]);
                                          $tmpdestinationpart = explode(" ", $tmp[1]);
                                          
                                        
                                        # Get Protocol
                                              preg_match_all('#\b(TCP|UDP|ICMP)\b#', $Traffic["text"], $protocol);
                                              $Protocol = $protocol[0][0];
                                              
                                        # Find TAG
                                              if ($Protocol == "UDP" OR $Protocol == "ICMP")
                                                {
                                                  $Tag = 	explode("".$tmpdestinationpart[0]." ", $Traffic["text"])[1];
                                                  $TCPflag = "N/A";
                                                  $TCPFlagDescription = "";
                                                }
                                              else
                                                {
                                                  $Tagtmp = 	explode("".$tmpdestinationpart[0]." ", $Traffic["text"])[1];
                                                  $Tag = explode(" ", $Tagtmp)[1];
                                                  $TCPflag = explode(" ", $Tagtmp)[0];
                                                  $TCPFlagDescription = "(".$TCPflag.")";
                                                }

                                        # Match Action
                                          preg_match_all('#\b(PASS|DROP|PUNT|REDIRECT|COPY|TERMINATE|TERM)\b#', $Traffic["text"], $logaction);
                                          $TrafficAction = $logaction[0][0];
                                          
                                        # Set coloring on action
                                          $ColorLog = $ShowLogActionColor[$TrafficAction];
                                          
                                        # Other variables
                                          $Source = $tmp[0];
                                          $Destination = $tmpdestinationpart[0];
                                          
                                          
                                        # Get Source and Destination Machine name (Only if enabled in config)

                                                if ($ReverseLookupData)
                                                  {
                                                    $SourceMachine = ReverseLookup(explode("/", $Source)[0]);
                                                    $SourceIPAddress = explode("/", $Source)[0];

                                                    $DestinationMachine = ReverseLookup(explode("/", $Destination)[0]);
                                                    $DestinationIPAddress = explode("/", $Destination)[0];
                                                  }

                                        # Show Only DROP/PASS
                                          if (in_array($TrafficAction,$ShowLogAction))
                                            {
                                              $CorrectTimestamp = round(($Traffic["timestamp"] / 1000));
                                              $dt = new DateTime('@' . $CorrectTimestamp);
                                              $dt->setTimeZone(new DateTimeZone($DateTimeZone));
                                          
                                              echo "<tr>";
                                                echo "<td>" . $dt->format(''.$DateFormat.' '.$TimeFormat.'') . "</td>";
                                                echo "<td class='bg-".$ColorLog."'>". $TrafficAction ."</td>";
                                                echo "<td>" . $tmp[0] . "<br>".$SourceMachine."</td>";
                                                echo "<td>" . $tmpdestinationpart[0] . "<br>".$DestinationMachine."</td>";
                                                echo "<td>" . $Protocol ." ".$TCPFlagDescription."</td>";
                                              echo "</tr>";
                                            }
                                            
                                            
                                    }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Timestamp</th>
                                    <th>Action</th>
                                    <th>Source/port</th>
                                    <th>Destination/port</th>
                                    <th>Protocol</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <?php } ?>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <div class="float-right d-none d-sm-block">
            <b>Version</b> 3.0.5
        </div>
        <strong>Copyright &copy; 2014-2019 <a href="http://adminlte.io">AdminLTE.io</a>.</strong> All rights
        reserved. || FREE LOG VIEWER FROM sorenisager.com
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables -->
    <script src="plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="plugins/inputmask/min/jquery.inputmask.bundle.min.js"></script>
    <script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    
    <!-- page script -->
    <script>
    $(function() {
        $("#datatable").DataTable({
            "responsive": true,
            "autoWidth": false,
            
        });
    });
    </script>
    <script>
    $(function() {
        //Money Euro
        $('[data-mask]').inputmask()


    })
    </script>
</body>

</html>
