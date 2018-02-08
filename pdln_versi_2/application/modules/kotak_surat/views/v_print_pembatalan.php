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

        <table class="first-table" width="100%" cellpadding="1px"> 
            <tr>					
                <td> <img src="<?= base_url() ?>assets/psd/kop_permohonan.png" /> </td> 
            </tr>
            <tr>
                <td>
                    <table>
                        <tr>
                            <?php $tanggal = !empty($data_sp->tgl_sp) ? strftime("%d %B %Y",$data_sp->tgl_sp) : ''; ?>
                            <td width="10%">Nomor</td><td width="3%">:</td><td width="57%"><?echo $data_sp->no_sp;?></td><td width="30%">Jakarta, <?php echo $tanggal;?></td> 
                        </tr>
                        <tr> 
                            <td>Sifat</td><td>:</td><td>Segera</td><td></td>   
                        </tr> 
                        <tr>
                            <td>Lampiran</td><td>:</td><td>-</td><td></td>
                        </tr>
                        <tr>
                            <td>Hal</td><td>:</td><td>Persetujuan Pembatalan Perjalanan Dinas Luar Negeri</td><td></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td><br/>
                    <table>
                        <tr>
                            <td width="50%"> Yth. <?php echo $data_sp->pejabat_sign_sp; ?> <br/>
                                di Jakarta
                            </td>
                            <td width="50%"></td> 
                        </tr>

                        <tr>
                            <td colspan="2" style="text-align:justify;vertical-align:top;padding:0"><br/>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sehubungan dengan surat <?php echo $data_sp->pejabat_sign_sp; ?> nomor <?php echo $data_sp->no_surat_usulan_fp; ?> tanggal <?php echo strftime("%d %B %Y",$data_sp->tgl_surat_usulan_fp);?> hal tersebut di atas, dengan hormat diberitahukan bahwa Pemerintah menyetujui pembatalan surat persetujuan penugasan ke luar negeri Kementerian Sekretariat Negara nomor <?php echo $data_sp_lama->no_sp; ?> tanggal <?php echo strftime("%d %B %Y",$data_sp_lama->tgl_sp);?>.
                                <br/><br/>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Atas perhatian dan kerja sama yang baik, kami sampaikan terima kasih.<br/><br/><br/><br/>
                            </td>
                        </tr>

                        <tr>
                            <td width="50%"></td>
                            <td width="50%">a.n.	Sekretaris Kementerian Sekretariat Negara <br/>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Kepala Biro Kerja Sama Teknik Luar Negeri<br/><br/><br/><br/>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Rika Kiswardani 
                            </td> 
                        </tr>

                        <tr>
                            <td width="50%"><br/><br/><br/>
                                Tembusan:<br/>
                                <?php
                                $no = 1;
                                foreach ($unit_tembusan as $ut) {
                                    echo $no . '. ' . $ut->Nama . '<br/>';
                                    $no++;
                                }
                                echo $no . '. Yang Bersangkutan<br/>';
                                ?> 
                            </td>
                            <td width="50%"></td> 
                        </tr>

                    </table>
                <td>
            </tr>
        </table>	
    </body>
</html>