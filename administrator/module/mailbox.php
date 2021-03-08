



    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-3">
          <a href="compose.html" class="btn btn-primary btn-block margin-bottom">Compose</a>

          <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Folders</h3>

              <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="box-body no-padding">
              <ul class="nav nav-pills nav-stacked">
                <li class="active"><a href="#"><i class="fa fa-inbox"></i> Inbox
                  <span class="label label-primary pull-right">12</span></a></li>
                <li><a href="#"><i class="fa fa-envelope-o"></i> Sent</a></li>
                <li><a href="#"><i class="fa fa-file-text-o"></i> Drafts</a></li>
                <li><a href="#"><i class="fa fa-filter"></i> Junk <span class="label label-warning pull-right">65</span></a>
                </li>
                <li><a href="#"><i class="fa fa-trash-o"></i> Trash</a></li>
              </ul>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Inbox</h3>

              <div class="box-tools pull-right">
                <div class="has-feedback">
                  <input type="text" class="form-control input-sm" placeholder="Search Mail">
                  <span class="glyphicon glyphicon-search form-control-feedback"></span>
                </div>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
              <div class="mailbox-controls">
                <!-- Check all button -->
                <button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i>
                </button>
                <div class="btn-group">
                  <button type="button" class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i></button>
                  <button type="button" class="btn btn-default btn-sm"><i class="fa fa-reply"></i></button>
                  <button type="button" class="btn btn-default btn-sm"><i class="fa fa-share"></i></button>
                </div>
                <!-- /.btn-group -->
                <button type="button" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>
                <div class="pull-right">
                  1-50/200
                  <div class="btn-group">
                    <button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></button>
                    <button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></button>
                  </div>
                  <!-- /.btn-group -->
                </div>
                <!-- /.pull-right -->
              </div>
              <div class="table-responsive mailbox-messages">
                <table class="table table-hover table-striped">
                  <tbody>
                    <?php
                                    $usermail = 'nyimasantam@gmail.com';
                                    $password = 'P@55w.rdnyimasantam';
                                    $hostname = '{imap.gmail.com:993/imap/ssl/novalidate-cert}INBOX';

                                    // if(strpos($usermail, "@yahoo")==true || strpos($usermail, "@ymail")==true){
                                    //     $hostname = '{imap.mail.yahoo.com:993/imap/ssl/novalidate-cert}INBOX';
                                    //   }elseif(strpos($usermail, "@aol")==true){
                                    //     $hostname = '{imap.aol.com:993/imap/ssl}INBOX';
                                    //   }else{
                                    //     $hostname = '{imap.gmail.com:993/imap/ssl/novalidate-cert}INBOX';
                                    //   }

                                    $mbox = imap_open($hostname,$usermail,$password) or die('Cannot connect to mail server: ' . imap_last_error());
                                     // var_dump($mbox);
                                    $MC=imap_check($mbox);
                                    $MN=$MC->Nmsgs;
                                    $overview=imap_fetch_overview($mbox,"1:$MN",0);
                                    $size=sizeof($overview);
                                    echo "";

                                    // var_dump($MC);
                                    //$no=0;
                                    for($i=$size-1;$i>=0;$i--){
                                        $val=$overview[$i];
                                      $msg=$val->msgno;
                                      $date=date('Y-m-d H:i:s', strtotime($val->date));
                                      $subj=isset($val->subject)?$val->subject:"(no subject)";
                                      $header = imap_header($mbox, $msg);
                                      $from = $header->from;
                                      $email_size = $val->size;
                                      $size2 = number_format ($email_size/1024);
                                      foreach ($from as $id => $object){
                                          $fromname = isset($object->personal)?$object->personal:$object->mailbox;
                                          $fromaddress = $object->mailbox . "@" . $object->host;
                                      }//$no++;
                              ?>
                  <tr>
                   <!--  <td> <?php //echo $msg; ?> </td>
                    <td> <?php //echo $fromaddress; ?> </td>
                    <td> <?php //echo $date; ?> </td>
                    <td> <?php //echo substr($subj, 0,15)."..."; ?> </td>
                    <td> <?php //echo $size2; ?> KB</td> -->
                    <td><input type="checkbox"></td>
                    <td class="mailbox-star"><a href="#"><i class="fa fa-star text-yellow"></i></a><?php echo $msg; ?></td>
                    <td class="mailbox-name"><a href="read-mail.html"><?php echo $fromaddress; ?></a></td>
                    <td class="mailbox-subject"><b><?php echo substr($subj, 0,15); ?> </b>
                    </td>
                    <td class="mailbox-attachment"><?php echo $size2; ?> KB</td>
                    <td class="mailbox-date"><?php echo $date; ?></td>
                  </tr>
                <?php } imap_close($mbox); ?>
                  </tbody>
                </table>
                <!-- /.table -->
              </div>
              <!-- /.mail-box-messages -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer no-padding">
              <div class="mailbox-controls">
                <!-- Check all button -->
                <button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i>
                </button>
                <div class="btn-group">
                  <button type="button" class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i></button>
                  <button type="button" class="btn btn-default btn-sm"><i class="fa fa-reply"></i></button>
                  <button type="button" class="btn btn-default btn-sm"><i class="fa fa-share"></i></button>
                </div>
                <!-- /.btn-group -->
                <button type="button" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>
                <div class="pull-right">
                  1-50/200
                  <div class="btn-group">
                    <button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></button>
                    <button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></button>
                  </div>
                  <!-- /.btn-group -->
                </div>
                <!-- /.pull-right -->
              </div>
            </div>
          </div>
          <!-- /. box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
