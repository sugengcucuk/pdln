<html lang="id">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->
    <head>
        <meta charset="utf-8" />
        <title>SIMPDLN INTERNAL | <?php echo $title; ?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="" name="description" />
        <meta content="" name="author" />
        <!-- BEGIN GLOBAL MANDATORY STYLES -->        

        <!-- END GLOBAL MANDATORY STYLES -->
    </head>
    <body>
        <table class="first-table" width="100%" cellpadding="2px"  style="text-align:center">
            <tr><td>Lampiran Surat Sekretaris Kementerian Sekretariat Negara<br/>
                    Nomor : <?echo $data_lampiran->no_sp;?><br/>
                    Tanggal      <?echo strftime("%d %B %Y",$data_sp->tgl_sp);?><br/>
                    Daftar Peserta<br/><br/><br/>  
                </td></tr>
        </table>


        <table class="first-table" width="100%" cellpadding="2px" style="border: 1px solid black;">
            <thead>		  		
                <tr>
                    <th align="center"  width="5%" style="border: 1px solid black;"> No.</th>
                    <th align="center" width="15%" style="border: 1px solid black;"> Nama/NIP </th> 
                    <th align="center" width="15%" style="border: 1px solid black;"> Jabatan</th>
                    <th align="center" width="15%" style="border: 1px solid black;"> Kegiatan</th>
                    <th align="center" width="15%" style="border: 1px solid black;"> Jangka Waktu</th>
                    <th align="center" width="15%" style="border: 1px solid black;"> Tempat Penugasan</th>
                    <th align="center" width="15%" style="border: 1px solid black;"> Biaya Penugasan</th> 
                </tr>
            </thead>
            <tbody>
                <!--
                <?php
                $no = 1;
                foreach ($list_pemohon as $lp) {
                    ?> 
                    <tr style="border: 1px solid black;">					
                        <td style="border: 1px solid black;"><?php echo $no++; ?></td>
                        <td style="border: 1px solid black;"><?php echo $lp->nama; ?></td>
                        <td style="border: 1px solid black;"><?php echo $lp->jabatan; ?></td>
                        <td style="border: 1px solid black;" rowspan="2"><?php echo $kegiatan->NamaKegiatan; ?></td>
                    <td style="border: 1px solid black;"></td>
                    <td style="border: 1px solid black;"></td>
                    <td style="border: 1px solid black;"></td> 
                </tr>
                <?}?>
                -->
                <?php
                $no = 1;
                $list_peserta = count($list_pemohon);
                foreach ($list_pemohon as $lp) {
                    $instansi = ", " . $lp->instansi;
                    if (($lp->jabatan == "") || !isset($lp->jabatan)) {
                        $instansi = ", " . $lp->instansi_lainnya;
                    }
                    ?>
                    <tr>
                        <td align="center"><?php echo $no. '.'; ?></td>
                        <td align="left"><?php echo $lp->nama; ?><?php echo ($lp->nip_nrp == '0' OR empty($lp->nip_nrp)) ? '' : '<br/>' . $lp->nip_nrp; ?></td>
                        <td align="left"><?php echo $lp->jabatan . $instansi; ?></td>
                        <?php if($no == 1){ ?>
                        <td align="left" rowspan="<?php echo $list_peserta; ?>" ><?php echo $kegiatan->NamaKegiatan; ?></td>
                        <?php } ?>
                        <td align="left"><?php echo ucwords(day(date("Y-m-d", $lp->start_date))); ?> s.d. <?php echo ucwords(day(date("Y-m-d", $lp->end_date))); ?></td>
                        <?php if($no == 1){ ?>
                        <td align="left"  rowspan="<?php echo $list_peserta; ?>" ><?php echo ucwords($kegiatan->Negara); ?></td>
                        <?php } ?>
                        <td align="left"><?php echo $lp->pembiayaan; ?></td>
                    </tr>
                    <?php $no++; ?>
                <?php } ?>
            </tbody>
        </table>

        <table class="first-table" width="100%" cellpadding="2px"  style="text-align:right">
            <tr><td><br/><br/><br/>a.n.	Sekretaris Kementerian Sekretariat Negara &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br/>
                    Kepala Biro Kerja Sama Teknik Luar Negeri&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br/><br/><br/><br/><br/><br/>
                    Rika Kiswardani &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </td></tr>
        </table>

    </body>
</html> 