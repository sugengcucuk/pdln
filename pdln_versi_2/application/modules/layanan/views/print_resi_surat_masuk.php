<html lang="id">
    <head>
        <meta charset="utf-8" />
        <title>SIMPDLN INTERNAL | <?php echo $title; ?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="" name="description" />
        <meta content="" name="author" />
		<link href="<?php echo base_url();?>assets/custom/css/resi.css" rel="stylesheet" type="text/css" />
    </head>
	<body>
		<?php
        $id_user = $this->session->user_id;
        $level = $this->db->get_where('m_user', array('UserID' => $id_user))->row()->level;
        ?>
		<?php if ($level == LEVEL_FOCALPOINT ) { ?>
		<h4>Tanda Bukti Registrasi Permohonan Focal Point</h4>
		<?}else{?>
		<h4>Tanda Bukti Registrasi Permohonan Unit Pemohon</h4>
		<?php } ?>
		<table class="first-table" width="100%" cellpadding="5px" style="border: 1px solid black;">
			<?php if ($level == LEVEL_FOCALPOINT ) { ?>
		  	<thead>
				<tr>
					<th align="center"  width="35%" style="border: 1px solid black;"> BIRO KERJA SAMA TEKNIK LUAR NEGERI <br/> KEMENTERIAN SEKRETARIAT NEGARA RI <br/>Jl. Veteran III No. 9 , Jakarta 10110</th>
					<th align="center" width="32%" style="border: 1px solid black;"> BUKTI REGISTRASI PDLN </th>
					<th align="center" width="33%" style="border: 1px solid black;"> NO. REGISTRASI : <?php echo str_pad($data_resi->no_register, 8, '0', STR_PAD_LEFT) ?></th>
				</tr>

			</thead>
			<tbody>
				<tr>
					<td>
					</td>
					<td colspan="2">
						<table class="no-border">
							<tr><td>&nbsp;&nbsp;INSTANSI PEMOHON&nbsp;&nbsp;</td><td>&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;</td><td><?php echo $data_resi->Nama; ?></td>
							</tr><td>&nbsp;&nbsp;TGL. REGISTRASI&nbsp;&nbsp;</td><td>&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;</td><td><?php echo date('d/m/Y',$data_resi->tgl_register); ?></td></tr>
							<tr><td>&nbsp;&nbsp;HAL&nbsp;&nbsp;</td><td>&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;</td><td><?php echo setJenisPermohonan($data_resi->jenis_permohonan); ?></td></tr>
							<tr><td>&nbsp;&nbsp;LAMPIRAN&nbsp;&nbsp;</td><td>&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;</td><td>1 Berkas</td></tr>
						</table>
					</td>
				</tr>
				<tr style="border: 1px solid black;">
					<td>
						<b>PELAYANAN FOCALPOINT KTLN :</b><br/><br/>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$data_resi->SubKategori?><br/>
						<br/><br/><br/>
					</td>
					<td style="border: 1px solid black;"> 	&nbsp;&nbsp;Jakarta , <?=date('d/m/Y',$data_resi->tgl_register)?><br/>
							&nbsp;&nbsp;Petugas Instansi<br/><br/><br/><br/><br/><br/><br/><br/><br/>
							&nbsp;&nbsp;( <?php echo $data_resi->usernameFp; ?> )
					</td>
					<td style="border: 1px solid black;"> 	&nbsp;&nbsp;Jakarta , <?=date('d/m/Y',$data_resi->tgl_register)?><br/>
							&nbsp;&nbsp;Petugas Loket<br/><br/><br/><br/><br/><br/><br/><br/><br/>
							&nbsp;&nbsp;(  )
					</td>
				</tr>
			</tbody>
			<?php }else{?>
			<tbody>
				<tr>
					<td style="border: 1px solid black;">&nbsp;&nbsp;INSTANSI PEMOHON&nbsp;&nbsp;
					</td>
					<td style="border: 1px solid black;"><?php echo $data_resi->Nama; ?>
					</td>
				</tr>

				<tr>
					<td style="border: 1px solid black;">&nbsp;&nbsp;NOMOR REGISTRASI&nbsp;&nbsp;
					</td>
					<td style="border: 1px solid black;"><?php echo str_pad($data_resi->no_register, 8, '0', STR_PAD_LEFT) ?>
					</td>
				</tr>
				<tr>
					<td style="border: 1px solid black;">&nbsp;&nbsp;TANGGAL DIBUAT&nbsp;&nbsp;
					</td>
					<td style="border: 1px solid black;"><?php echo date('d/m/Y',$data_resi->create_date); ?>
					</td>
				</tr>
				<tr>
					<td style="border: 1px solid black;">&nbsp;&nbsp;PEMOHON&nbsp;&nbsp;
					</td>
					<td style="border: 1px solid black;"><?php echo $data_resi->Nama; ?> - (  )
					</td>
				</tr>

					<?php }?>
		</table>
		<br/>
		* Bukti Regisrtasi Permohonan.<br/>
		<?php if ($level == LEVEL_FOCALPOINT ) { ?>
		* Terima kasih untuk tidak memberikan imbalan dalam bentuk apapun atas layanan yang diberikan Biro KTLN.
		<?php }?>
	</body>
</html>