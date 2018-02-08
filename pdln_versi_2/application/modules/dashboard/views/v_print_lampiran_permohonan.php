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
		<div class="title">
			<h3>Lampiran Surat Sekretaris Kementerian Sekretariat Negara</h3></div>
			<div class="subtitle">
				<p>Nomor : <?echo $data_lampiran->no_sp;?></p>
				<p>Tanggal : <?php if($data_sp->tgl_sp!=null || $data_sp->tgl_sp!= "") {echo strftime("%d %B %Y",$data_sp->tgl_sp);}?></p>				
			</div><br>
			<div class="subtitle-left">
				<p>Daftar Peserta</p>
				<p><?php echo ucwords($kegiatan->NamaKegiatan);?></p>
				<p>di <?php echo ucwords($kegiatan->Negara);?>. </p>
			</div><br/>
		<table class="first-table" width="100%" cellpadding="2px">
			<thead>
				<tr>
					<th align="center" width="3%" > No.</th>
					<th align="center" width="20%"> Nama/NIP </th>
					<th align="center" width="35%"> Jabatan</th>					
					<th align="center" width="10%"> Jangka Waktu</th>					
					<th align="center" width="30%"> Biaya Penugasan</th> 
				</tr>
			</thead>
			<tbody>
				<?php
					$no = 1;
					foreach($list_pemohon as $lp){
						$instansi = ", " . $lp->instansi;
						if(($lp->jabatan == "") || !isset($lp->jabatan)){
							$instansi = ", " . $lp->instansi_lainnya;
						}?>
						<tr>
							<td align="center" ><?php echo $no++.'.';?></td>
							<td align="left" ><?php echo $lp->nama;?><?php echo ($lp->nip_nrp == '0' OR empty($lp->nip_nrp)) ? '' : '<br/>'.$lp->nip_nrp;?></td>
							<td align="left" ><?php echo ucwords($lp->jabatan . $instansi );?></td>
							<td align="left" ><?php echo ucwords(day(date("Y-m-d",$lp->start_date)));?> s.d. <?php echo ucwords(day(date("Y-m-d",$lp->end_date)));?></td>
							<td align="left" ><?php echo $lp->pembiayaan;?></td>
						</tr>
					<?}?>
			</tbody>
		</table>
		<br/><br/>
		<table width="100%" style="border:none;" cellpadding="2px">
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
		</table>
	</body>
</html>