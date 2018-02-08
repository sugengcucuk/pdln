<html lang="id">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->
    <head>
        <meta charset="utf-8" />
        <title>SIMPEL | <?php echo $title; ?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="" name="description" />
        <meta content="" name="author" />
        <!-- BEGIN GLOBAL MANDATORY STYLES -->        
        <link href="<?php echo base_url();?>assets/custom/css/pdf_sp.css" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
    </head>
	<body>		
		<table width="100%" cellpadding="1px"> 
			<tr>					
				<td rowspan="4" align="center" width="110px" style="vertical-align: middle;"><img height="100px" src="<?php echo base_url();?>assets/psd/logo_setneg.png" /> </td> 
				<td style="vertical-align: middle;" align="center" class="text-center"><h3 class="kop_surat">KEMENTERIAN SEKRETARIAT NEGARA</h3></td></tr>
			<tr><td style="vertical-align: middle;" align="center" class="text-center"><h3 class="kop_surat">REPUBLIK INDONESIA</h3></td></tr>
			<tr><td style="vertical-align: middle;" align="center" class="text-center"><span class="address">Jalan Veteran No. 17-18, Jakarta 10110, Telepon (021) 3845627, 3442237</span></td></tr>
			<tr><td style="vertical-align: middle;" align="center" class="text-center"><span class="sites">Situs : <a href="">www.setneg.go.id</a></span></td></tr>
			<tr><td colspan="2"><br/><img src="<?php echo base_url();?>assets/psd/header_line.png" /></td></tr>
		</table>
		<table width="100%" cellpadding="2px">
			<tr>
				<td>
				<br/>
					<table width="100%">
						<tr>
							<td width="">Nomor</td>
							<td width="">:</td>
							<td width=""><?echo $data_sp->no_sp;?></td>
							<td width="" align="right">Jakarta,&nbsp;<?php if($data_sp->tgl_sp!=null || $data_sp->tgl_sp!= "") {echo strftime("%d %B %Y",$data_sp->tgl_sp);}?></td> 
						</tr>
						<tr> 
							<td>Sifat</td>
							<td>:</td>
							<td>Segera</td>
							<td></td>   
						</tr> 
						<tr>
							<td>Lampiran</td>
							<td>:</td>
							<td>1 (Satu) Berkas</td>
							<td></td>
						</tr>
						<tr>
							<td>Hal</td>
							<td>:</td>
							<td>Persetujuan Perjalanan Dinas Luar Negeri</td>
							<td></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td><br/>
					<table>
						<tr>
							<td width="50%"> Yth. <?php echo ucwords($data_sp->pejabat_sign_sp) ?> <br/>
											di Jakarta
							</td>
							<td width="50%"></td> 
						</tr>						
						<tr>
							<td colspan="2"><br/>
							<table>
								<tr>
								<td colspan="2" style="text-align:justify;vertical-align:top;padding:0">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sehubungan dengan surat <?php echo $data_sp->pejabat_sign_sp;?> nomor <?php echo $data_sp->no_surat_usulan_fp;?> tanggal <?echo ($data_sp->tgl_surat_usulan_fp == 0) ? '' : strftime("%d %B %Y",$data_sp->tgl_surat_usulan_fp);?> hal tersebut di atas, dengan hormat diberitahukan bahwa Pemerintah menyetujui perjalanan dinas luar negeri bagi pejabat/pegawai sebagaimana tercantum dalam daftar terlampir.</td>
								<br/><br/></tr>
								<tr>
									<td colspan="2" style="text-align:justify;vertical-align:top;padding:0">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Persetujuan Pemerintah ini diberikan dengan ketentuan sebagai berikut:<br/></td>
								</tr>
								<tr>
									<td style="text-align:left;vertical-align:top;padding:0" width="5%">1.</td><td style="text-align:justify;vertical-align:top;padding:0" width="95%">Perjalanan dinas luar negeri dilakukan untuk kepentingan penyelenggaraan pemerintahan yang sangat tinggi.</td>
								</tr>
								<tr>
									<td style="text-align:left;vertical-align:top;padding:0" width="5%">2.</td><td style="text-align:justify;vertical-align:top;padding:0" width="95%">Yang bersangkutan menghubungi Kedutaan Besar RI/ Perwakilan RI di negara setempat untuk menyampaikan maksud kedatangan.</td>
								</tr>
								<tr>
									<td style="text-align:left;vertical-align:top;padding:0" width="5%">3.</td><td style="text-align:justify;vertical-align:top;padding:0" width="95%">Laporan tertulis hasil perjalanan dinas tersebut agar disampaikan kepada Kementerian Sekretariat Negara.</td><br/><br/>
								</tr>
								<tr><td colspan="2" style="text-align:justify;vertical-align:top;padding:0">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Atas perhatian dan kerja sama yang baik, kami sampaikan terima kasih.</td><br/><br/><br/></tr> 
							</table>
							</td>
						</tr>
						<tr>
							<td width="50%"></td>
							<td width="50%">
								<?php echo $label_penandatangan;?><br/><br/>
									<?php if(isset($penandatangan->signature)){?>
										<img src="<?php echo get_file_pdln('signature',date('d-m-yyyy'),$penandatangan->UserID,$penandatangan->signature);?>"></img>
									<?php }else{?>
									<?php }?>
									<!-- <br/>
									<div class="col-md-ofsset-3">
										<p style="margin-left:50px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									</div> -->
							</td>
						</tr>
						<tr>
							<td width="50%"><br/><br/><br/>
								Tembusan:<br/>
								<?php 
								$no = 1;
								foreach($unit_tembusan as $ut){
									echo $no .'. '. $ut->Nama . '<br/>';
								$no++; 
								}
									echo $no .'. Yang Bersangkutan<br/>';
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